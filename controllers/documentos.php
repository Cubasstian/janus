<?php
require_once __DIR__ . "/../libs/baseCrud.php";
require_once __DIR__ . "/documentosRevisiones.php";

class documentos extends baseCrud{
protected $tabla = 'documentos';

public function crear($datos){
$sql = "INSERT INTO documentos (contratista, fk_procesos, fk_documentos_tipo, creado_por, fecha_creacion) VALUES (?,?,?,?, NOW()) ON DUPLICATE KEY UPDATE fecha_modificacion = NOW()";
$params = [ (int)$datos['contratista'], (int)$datos['proceso'], (int)$datos['tipo'], (int)$_SESSION['usuario']['id'] ];
$db = new database();
$resultado = $db->ejecutarPreparado($sql, 'iiii', $params);
if(!$resultado['ejecuto']){ return $resultado; }
$docId = 0;
if(isset($resultado['insertId']) && (int)$resultado['insertId'] > 0){
$docId = (int)$resultado['insertId'];
}else{
$sqlSel = "SELECT id FROM documentos WHERE contratista = ? AND fk_procesos = ? AND fk_documentos_tipo = ? LIMIT 1";
$sel = $db->ejecutarPreparado($sqlSel, 'iii', [ (int)$datos['contratista'], (int)$datos['proceso'], (int)$datos['tipo'] ]);
if($sel['ejecuto'] && count($sel['data'])){
$docId = (int)$sel['data'][0]['id'];
}
}
$resultado['insertId'] = $docId;
return $resultado;
}

public function getDocumentos($datos){
$db = new database();
$base = "SELECT doc.id, doc.fk_documentos_tipo AS tipo, (doc.fk_documentos_tipo - 2) AS numero, doc.conteo, doc.estado, doc.observaciones FROM documentos doc WHERE ";
$sql = $base; $types = ''; $params = [];
$criterio = isset($datos['criterio']) ? $datos['criterio'] : null;
if(!$criterio && (isset($datos['user']) || isset($datos['contratista'])) && isset($datos['proceso'])){
$criterio = 'generales';
if(!isset($datos['contratista']) && isset($datos['user'])){ $datos['contratista'] = $datos['user']; }
}
if(!$criterio){ return [ 'ejecuto' => false, 'mensajeError' => 'Criterio no especificado' ]; }
switch ($criterio) {
case 'id': $sql .= "doc.id = ?"; $types = 'i'; $params[] = (int)$datos['id']; break;
case 'generales': $sql .= "doc.contratista = ? AND doc.fk_procesos = ?"; $types = 'ii'; $params[] = (int)$datos['contratista']; $params[] = (int)$datos['proceso']; break;
default: return [ 'ejecuto' => false, 'mensajeError' => 'Criterio no soportado' ];
}
return $db->ejecutarPreparado($sql, $types, $params);
}

public function setEstado($datos){
$rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : null;
$cfg = __DIR__.'/../config/permisos_documentos.php'; $map = [];
if(file_exists($cfg)){ $tmp = include $cfg; if(is_array($tmp)) $map = $tmp; }
$idDocTmp = isset($datos['id']) ? (int)$datos['id'] : 0;
if($idDocTmp>0){
$dbp = new database();
$rsTipo = $dbp->ejecutarPreparado("SELECT dt.nombre FROM documentos d INNER JOIN documentos_tipo dt ON d.fk_documentos_tipo = dt.id WHERE d.id = ? LIMIT 1", 'i', [ $idDocTmp ]);
$tipoNombre = ($rsTipo['ejecuto'] && count($rsTipo['data'])) ? $rsTipo['data'][0]['nombre'] : null;
$permitidos = [];
if($tipoNombre && isset($map[$tipoNombre])){ $permitidos = $map[$tipoNombre]; } else if(isset($map['default'])){ $permitidos = $map['default']; }
if($rol===null || !in_array($rol, $permitidos)){ return [ 'ejecuto'=>false, 'mensajeError'=>'Permisos insuficientes para revisar este documento' ]; }
}
$estado = isset($datos['estado']) ? (int)$datos['estado'] : 0;
$obs = isset($datos['observaciones']) ? trim((string)$datos['observaciones']) : '';
if($estado === 2 && $obs === ''){ $obs = 'Aceptado'; }
if($estado === 3 && $obs === ''){ $obs = 'Rechazado'; }
if(!in_array($estado, [1,2,3], true)){ return [ 'ejecuto' => false, 'mensajeError' => 'Estado inválido' ]; }
$idDoc = isset($datos['id']) ? (int)$datos['id'] : 0;
if($idDoc <= 0){ return [ 'ejecuto' => false, 'mensajeError' => 'ID inválido' ]; }
$db = new database();
$sql = "UPDATE documentos SET conteo = conteo + 1, estado = ?, modificado_por = ?, fecha_modificacion = NOW(), observaciones = ? WHERE id = ?";
$resultado = $db->ejecutarPreparado($sql, 'iisi', [ $estado, (int)$_SESSION['usuario']['id'], $obs, $idDoc ], false);
if($resultado['ejecuto']){
$info = [ 'info'=>[ 'fk_documentos'=>$idDoc, 'estado'=> $estado, 'observaciones'=> $obs ] ];
$objRevisiones = new documentosRevisiones();
$respuesta = $objRevisiones->insert($info);
if(!$respuesta['ejecuto']){ return $respuesta; }
$snap = $db->ejecutarPreparado("SELECT id, fk_documentos_tipo AS tipo, conteo, estado, observaciones, fecha_modificacion FROM documentos WHERE id = ?", 'i', [ $idDoc ]);
if($snap['ejecuto'] && isset($snap['data'][0])){ $resultado['data'] = [ $snap['data'][0] ]; }
return $resultado;
}else{
return $resultado;
}
}

public function contarPendientes($datos){
$sql = "SELECT doc.fk_procesos, COUNT(1) AS cantidad FROM documentos doc WHERE doc.fk_procesos IN ($datos[procesos]) AND doc.estado = 1 GROUP BY doc.fk_procesos";
$db = new database();
return $db->ejecutarConsulta($sql);
}

public function buscarPorNombre($datos){
$contratista = isset($datos['contratista']) ? (int)$datos['contratista'] : 0;
$proceso = isset($datos['proceso']) ? (int)$datos['proceso'] : 0;
$nombreLike = isset($datos['nombreLike']) ? (string)$datos['nombreLike'] : '';
if($contratista<=0 || $proceso<=0 || $nombreLike===''){ return [ 'ejecuto' => false, 'mensajeError' => 'Parámetros inválidos' ]; }
$sql = "SELECT d.id, d.estado, d.fk_documentos_tipo AS tipo, dt.nombre AS tipo_nombre FROM documentos d INNER JOIN documentos_tipo dt ON d.fk_documentos_tipo = dt.id WHERE d.contratista = ? AND d.fk_procesos = ? AND dt.nombre LIKE ? ORDER BY d.id DESC LIMIT 1";
$db = new database();
return $db->ejecutarPreparado($sql, 'iis', [ $contratista, $proceso, $nombreLike ]);
}

public function delete($datos){
    error_log("=== DELETE DOCUMENTO INICIADO ===");
    error_log("Datos recibidos: " . print_r($datos, true));
    
    $idDoc = isset($datos['id']) ? (int)$datos['id'] : 0;
    error_log("ID documento: " . $idDoc);
    
    if($idDoc <= 0){ 
        error_log("ID inválido");
        return [ 'ejecuto' => false, 'mensajeError' => 'ID inválido' ]; 
    }
    
    $db = new database();
    // Solo consultar la tabla documentos, el archivo se guarda como {id}.pdf en la carpeta documentos/
    $checkSql = "SELECT d.contratista, d.estado, d.fk_documentos_tipo FROM documentos d WHERE d.id = ? LIMIT 1";
    error_log("SQL a ejecutar: " . $checkSql);
    error_log("Parámetros: ID=" . $idDoc);
    
    $check = $db->ejecutarPreparado($checkSql, 'i', [ $idDoc ], false); // NO cerrar conexión aún
    
    error_log("Check documento - ejecuto: " . ($check['ejecuto'] ? 'SI' : 'NO'));
    error_log("Check documento - mensajeError: " . (isset($check['mensajeError']) ? $check['mensajeError'] : 'N/A'));
    error_log("Check documento - count: " . (isset($check['data']) ? count($check['data']) : 0));
    error_log("Check documento - respuesta completa: " . print_r($check, true));
    
    if(!$check['ejecuto'] || !count($check['data'])){ 
        error_log("Documento no encontrado");
        return [ 'ejecuto' => false, 'mensajeError' => 'Documento no encontrado' ]; 
    }
    
    $docData = $check['data'][0];
    $contratista = (int)$docData['contratista'];
    $estado = (int)$docData['estado'];
    
    // La ruta del archivo es {id}.pdf en la carpeta documentos/
    $ruta = __DIR__ . '/../documentos/' . $idDoc . '.pdf';
    
    error_log("Contratista doc: " . $contratista);
    error_log("Estado: " . $estado);
    error_log("Ruta: " . ($ruta ? $ruta : 'NULL'));
    
    $usuarioId = (int)$_SESSION['usuario']['id'];
    $rol = $_SESSION['usuario']['rol'];
    
    error_log("Usuario ID: " . $usuarioId);
    error_log("Rol: " . $rol);
    
    if($rol !== 'admin' && $contratista !== $usuarioId){ 
        error_log("Sin permisos - no es admin ni propietario");
        return [ 'ejecuto' => false, 'mensajeError' => 'No tiene permisos para eliminar este documento' ]; 
    }
    
    if($estado === 2 && $rol !== 'admin'){ 
        error_log("Documento aprobado - solo admin puede eliminar");
        return [ 'ejecuto' => false, 'mensajeError' => 'No se puede eliminar un documento aprobado' ]; 
    }
    
    // Eliminar archivo físico
    if($ruta && file_exists($ruta)){ 
        error_log("Eliminando archivo: " . $ruta);
        unlink($ruta); 
    } else {
        error_log("Archivo no encontrado en: " . $ruta);
    }
    
    // Eliminar revisiones
    error_log("Eliminando revisiones...");
    $delRev = $db->ejecutarPreparado("DELETE FROM documentos_revisiones WHERE fk_documentos = ?", 'i', [ $idDoc ], false); // NO cerrar
    error_log("Revisiones eliminadas - ejecuto: " . ($delRev['ejecuto'] ? 'SI' : 'NO'));
    
    // Eliminar documento
    error_log("Eliminando documento...");
    $delDoc = $db->ejecutarPreparado("DELETE FROM documentos WHERE id = ?", 'i', [ $idDoc ], true); // SÍ cerrar ahora
    error_log("Documento eliminado - ejecuto: " . ($delDoc['ejecuto'] ? 'SI' : 'NO'));
    
    if($delDoc['ejecuto']){
        error_log("=== DELETE EXITOSO ===");
        return [ 'ejecuto' => true, 'mensaje' => 'Documento eliminado correctamente' ];
    }else{
        error_log("=== DELETE FALLIDO ===");
        return [ 'ejecuto' => false, 'mensajeError' => 'Error al eliminar el documento' ];
    }
}
}