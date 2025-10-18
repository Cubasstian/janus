<?php
require_once __DIR__ . "/documentos.php";
require_once __DIR__ . '/../libs/database.php';

class archivos{
	public function cargarDocumento($datos){
		$id = isset($datos['id']) ? (int)$datos['id'] : 0;
		if($id <= 0){
			return [ 'ejecuto' => false, 'msg' => 'ID de documento inválido' ];
		}
		// Verificar sesión y permisos (solo dueño del documento o Administrador)
		if(!isset($_SESSION) || !isset($_SESSION['usuario'])){
			return [ 'ejecuto' => false, 'msg' => 'Sesión no válida' ];
		}
		$userId = (int)$_SESSION['usuario']['id'];
		$rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : '';
		$db = new database();
		$rs = $db->ejecutarPreparado("SELECT contratista FROM documentos WHERE id = ? LIMIT 1", 'i', [ $id ]);
		if(!$rs['ejecuto'] || count($rs['data']) === 0){
			return [ 'ejecuto' => false, 'msg' => 'Documento no encontrado' ];
		}
		$ownerId = (int)$rs['data'][0]['contratista'];
		$isAdmin = ($rol === 'Administrador');
		if(!$isAdmin && $userId !== $ownerId){
			return [ 'ejecuto' => false, 'msg' => 'No autorizado para cargar este documento' ];
		}
		if(!isset($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name'])){
			return [ 'ejecuto' => false, 'msg' => 'Archivo no recibido' ];
		}
		// Validaciones básicas: tamaño y tipo
		$maxBytes = 15 * 1024 * 1024; // 15MB
		if(isset($_FILES['file']['size']) && $_FILES['file']['size'] > $maxBytes){
			return [ 'ejecuto' => false, 'msg' => 'El archivo supera el tamaño máximo permitido (15MB)' ];
		}
		$nombre = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : '';
		$ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));
		if($ext !== 'pdf'){
			return [ 'ejecuto' => false, 'msg' => 'Solo se permiten archivos PDF' ];
		}
		// Verificar MIME (cuando sea posible)
		if(function_exists('finfo_open')){
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			if($finfo){
				$mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
				finfo_close($finfo);
				if($mime !== 'application/pdf' && $mime !== 'application/octet-stream'){
					return [ 'ejecuto' => false, 'msg' => 'Tipo de archivo inválido' ];
				}
			}
		}
		// Directorio destino consistente con generadores de PDF
	$dir = __DIR__ . '/../documentos';
		if(!is_dir($dir)){
			@mkdir($dir, 0775, true);
		}
		$dest = $dir . '/' . $id . '.pdf';
		if(move_uploaded_file($_FILES['file']['tmp_name'], $dest)){
			return [ 'ejecuto' => true, 'msg' => 'Carga correcta' ];
		}else{
			return [ 'ejecuto' => false, 'msg' => 'Error al mover el archivo al destino' ];
		}
	}

	public function getDocumento($datos){
		$docId = isset($datos['id']) ? (int)$datos['id'] : 0;
		if($docId <= 0){
			return [ 'ejecuto' => false, 'mensajeError' => 'ID inválido' ];
		}
		// Autorización: Admin, Revisor o dueño del documento
		if(!isset($_SESSION) || !isset($_SESSION['usuario'])){
			return [ 'ejecuto' => false, 'mensajeError' => 'Sesión no válida' ];
		}
		$userId = (int)$_SESSION['usuario']['id'];
		$rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : '';
		$db = new database();
		$rs = $db->ejecutarPreparado("SELECT contratista FROM documentos WHERE id = ? LIMIT 1", 'i', [ $docId ]);
		if(!$rs['ejecuto'] || count($rs['data']) === 0){
			return [ 'ejecuto' => false, 'mensajeError' => 'Documento no encontrado' ];
		}
		$ownerId = (int)$rs['data'][0]['contratista'];
		$isAdminOrRevisor = ($rol === 'Administrador' || $rol === 'Revisor');
		if(!$isAdminOrRevisor && $userId !== $ownerId){
			return [ 'ejecuto' => false, 'mensajeError' => 'No autorizado para acceder a este documento' ];
		}
		$file = __DIR__.'/../documentos/'.$docId.'.pdf';
		// Verificar si el archivo existe
		if(file_exists($file)) {
			// Lee el archivo en formato binario
    		$fileContent = file_get_contents($file);    
    		// Codifica el contenido en base64 para enviarlo en formato JSON
    		$base64File = base64_encode($fileContent);    
    		// Enviar la respuesta JSON con el archivo codificado
    		header('Content-Type: application/json');
    		return [
    			'ejecuto' => true,
    			'file' => $base64File
    		];
		}else{
    		return [
				'ejecuto' => false,
				'mensajeError' => 'El archivo no existe'
			];
		}
	}

	public function existDocumento($datos){
		$docId = isset($datos['id']) ? (int)$datos['id'] : 0;
		if($docId <= 0){
			return [ 'ejecuto' => false, 'mensajeError' => 'ID inválido' ];
		}
		// Autorización: Admin, Revisor o dueño del documento
		if(!isset($_SESSION) || !isset($_SESSION['usuario'])){
			return [ 'ejecuto' => false, 'mensajeError' => 'Sesión no válida' ];
		}
		$userId = (int)$_SESSION['usuario']['id'];
		$rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : '';
		$db = new database();
		$rs = $db->ejecutarPreparado("SELECT contratista FROM documentos WHERE id = ? LIMIT 1", 'i', [ $docId ]);
		if(!$rs['ejecuto'] || count($rs['data']) === 0){
			return [ 'ejecuto' => false, 'mensajeError' => 'Documento no encontrado' ];
		}
		$ownerId = (int)$rs['data'][0]['contratista'];
		$isAdminOrRevisor = ($rol === 'Administrador' || $rol === 'Revisor');
		if(!$isAdminOrRevisor && $userId !== $ownerId){
			return [ 'ejecuto' => false, 'mensajeError' => 'No autorizado para consultar este documento' ];
		}
		$file = __DIR__.'/../documentos/'.$docId.'.pdf';
		if(file_exists($file)) {
			return [
				'ejecuto' => true,
				'mensaje' => true
			];
		}else{
			return [
				'ejecuto' => false,
				'mensajeError' => 'El archivo no existe'
			];
		}
	}
}

// Manejo de llamadas directas (para subida de archivos)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    session_start();
    
    // Log temporal para debug
    error_log('ARCHIVOS DEBUG: Recibiendo petición POST');
    error_log('ARCHIVOS DEBUG: $_FILES = ' . print_r($_FILES, true));
    error_log('ARCHIVOS DEBUG: $_POST = ' . print_r($_POST, true));
    error_log('ARCHIVOS DEBUG: $_SESSION = ' . print_r($_SESSION, true));
    
    header('Content-Type: application/json');
    
    try {
        $archivos = new archivos();
        $resultado = $archivos->cargarDocumento($_POST);
        error_log('ARCHIVOS DEBUG: Resultado = ' . print_r($resultado, true));
        echo json_encode($resultado);
    } catch (Exception $e) {
        $error = [
            'ejecuto' => false,
            'msg' => 'Error del servidor: ' . $e->getMessage()
        ];
        error_log('ARCHIVOS DEBUG: Error = ' . print_r($error, true));
        echo json_encode($error);
    }
    exit;
}
?>