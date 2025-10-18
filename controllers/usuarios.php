<?php
require_once "libs/baseCrud.php";

class usuarios extends baseCrud{
	protected $tabla = 'usuarios';
	// Capacidad mínima para almacenar bcrypt ($2y$... ~60 chars)
	private $MIN_BCRYPT_LEN = 60;

	private function getPasswordColumnMaxLen(){
		try{
			$db = new database();
			$sql = "SELECT CHARACTER_MAXIMUM_LENGTH AS maxlen\n                    FROM information_schema.COLUMNS\n                    WHERE TABLE_SCHEMA = DATABASE()\n                      AND TABLE_NAME = 'usuarios'\n                      AND COLUMN_NAME = 'password'";
			$r = $db->ejecutarConsulta($sql);
			if(isset($r['ejecuto']) && $r['ejecuto'] && !empty($r['data'])){
				$len = (int)$r['data'][0]['maxlen'];
				return $len ?: null;
			}
		}catch(Exception $e){ /* ignore */ }
		return null;
	}

	public function login($datos){
		if($datos['info']['password'] == ''){
			$datos['info']['password'] = 'xxxxx';
		}
		//se busca en la base de datos
		$filtro['info']['login'] = $datos['info']['login'];
		$resultado = parent::select($filtro);
		if(count($resultado['data']) == 0){
			return [
				'ejecuto' => false,
				'mensajeError' => 'No estas autorizado en este aplicativo'
			];
		}elseif($resultado['data'][0]['estado'] == 'Cancelado'){
			return [
				'ejecuto' => false,
				'mensajeError' => 'Su usuario ha sido cancelado en este aplicativo'
			];
		}else{
			$rolDb = isset($resultado['data'][0]['rol']) ? trim($resultado['data'][0]['rol']) : '';
			$esPS = (strcasecmp($rolDb, 'PS') === 0);
			if($esPS){
				// Autenticación local PS: primero intentar password_verify, si no, fallback a MD5 y luego migrar a hash
				$usuarioRow = $resultado['data'][0];
				$passPlano = $datos['info']['password'];
				$hashBD = isset($usuarioRow['password']) ? (string)$usuarioRow['password'] : '';
				$autenticado = false; $necesitaUpgrade = false;
				if($hashBD !== ''){
					// Intentar verificar contra cualquier hash soportado por password_* (bcrypt, etc.)
					$autenticado = password_verify($passPlano, $hashBD);
				}
				if(!$autenticado){
					// Legacy MD5: consultar por login + md5(password)
					$datosMD5 = $datos; $datosMD5['info']['password'] = md5($passPlano);
					$resMD5 = parent::select($datosMD5);
					$autenticado = isset($resMD5['data']) && count($resMD5['data']) > 0;
					$necesitaUpgrade = $autenticado; // si autenticó por MD5, actualizaremos a hash
				}
				if(!$autenticado){
					return [ 'ejecuto' => false, 'mensajeError' => 'Credenciales erróneas' ];
				}
			}else{
				//Si no es PS, lo buscamos en IDM
				$respuestaIDM = $this->getIDM($datos,$resultado['data'][0]['is_autentica']);
				if($respuestaIDM->statusCode != 201){
					if($respuestaIDM->statusCode == 401){
						return [
							'ejecuto' => false,
							'mensajeError' => 'La contraseña no coincide con la Intranet'
						];	
					}
					return [
						'ejecuto' => false,
						'mensajeError' => $respuestaIDM->message
					];
				}
			}
			$usuario = [
				'id' => $resultado['data'][0]['id'],
				// Normalizar rol PS para consistencia en el frontend
				'rol' => $esPS ? 'PS' : $resultado['data'][0]['rol'],
				'nombre' => $resultado['data'][0]['nombre'],
				'gerencia' => $resultado['data'][0]['fk_gerencias'],
				'login' => $resultado['data'][0]['login']
			];
			//Guardar variables en sesión
			$_SESSION['usuario'] = $usuario;

			// Si autenticó por MD5 y requiere migración, actualizar password solo si la columna soporta bcrypt completo
			if(isset($necesitaUpgrade) && $necesitaUpgrade === true && isset($passPlano)){
				$maxLen = $this->getPasswordColumnMaxLen();
				if($maxLen === null || $maxLen >= $this->MIN_BCRYPT_LEN){
					$hashNuevo = password_hash($passPlano, PASSWORD_BCRYPT);
					parent::update([ 'info' => [ 'password' => $hashNuevo ], 'id' => (int)$usuario['id'] ]);
				} else {
					// No actualizar para evitar truncamiento; mantener MD5 hasta migración de esquema
				}
			}

			//registrar fecha de utimo acceso
			$acceso = [
				'info' => [
					'ultimo_acceso' => date("Y-m-d H:i:s")
				],
				'id' => $usuario['id']
			];
			parent::update($acceso);
			return [
				'ejecuto' => true,
				'data' => $_SESSION
			];			
		}
	}

	private function getIDM($datos, $autentica){
		if($autentica == 0){
			return json_decode('{"statusCode":201}');
		}
		$headers = [
    		"Content-Type: application/json"
		];

		$params = json_encode([
    		'username'=>$datos['info']['login'],
    		'password'=>$datos['info']['password'],
    		'token'=>'9868b572-5e21-460b-93cf-4ffabba2e72d',
    		'attributes'=>'employeeNumber,employeeID,cn,employeeType,displayName'
		]);
		
		//$apiUrl = "http://172.18.32.117:5006/users/auth";
		$apiUrl = "https://serviciosapppdn.emcali.com.co:5006/users/auth";

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $apiUrl);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);		
		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		
		$response = curl_exec($curl);
		if (curl_errno($curl)){
			$response = '{"statusCode":500, "message":"'.curl_errno($curl).':'.curl_error($curl).'"}';
		}
		curl_close($curl);
		return json_decode($response);
	}

	public function getUsuarios($datos){
		$filtro = 0;
		switch ($datos['criterio']) {
			case 'id':
				$filtro = "usu.id = ".(int)$datos['id'];
				break;
			case 'todos':
				$filtro = "usu.id != 1 AND usu.rol != 'PS'";
				break;
			default:
				$filtro = 0;
				break;
		}
		$sql = "SELECT 
					usu.id,
					usu.rol,
					ger.nombre AS gerencia,
					usu.nombre,
					usu.cedula,
					usu.login,					
					usu.estado,
					usu.foto
				FROM
					(usuarios usu INNER JOIN gerencias ger ON usu.fk_gerencias = ger.id)
				WHERE
					$filtro";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function insert($datos){
		//$datos['info']['password'] = md5($datos['info']['password']);
		return parent::insert($datos);
	}

	public function setPassword($datos){
		// Nuevo: almacenar con password_hash (bcrypt)
		$datos['info']['password'] = password_hash($datos['info']['password'], PASSWORD_BCRYPT);
		return parent::update($datos);
	}

	public function getInfoPersonas($datos){
		$sql = "SELECT 
					usu.id,
					usu.nombre,
					usu.cedula,
					usu.sexo,
					usu.fecha_nacimiento,
					usu.correo,
					usu.telefono,
					usu.fk_ciudades,
					usu.direccion,
					usu.fk_eps,
					usu.fk_arl,
					usu.fk_fondos_pension,
					usu.foto
				FROM
					usuarios usu
				WHERE
					usu.id = ".(int)$datos['id'];
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	// Subir foto de perfil
	public function uploadFoto($datos){
		error_log("=== uploadFoto llamado ===");
		error_log("Datos recibidos: " . print_r($datos, true));
		error_log("_FILES: " . print_r($_FILES, true));
		error_log("Sesión usuario: " . print_r($_SESSION['usuario'], true));
		
		if(!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK){
			error_log("Error: No se recibió archivo o error en upload. Error code: " . (isset($_FILES['foto']) ? $_FILES['foto']['error'] : 'N/A'));
			return [
				'ejecuto' => false,
				'mensajeError' => 'No se recibió ningún archivo o hubo un error en la subida'
			];
		}

		$userId = isset($datos['id']) ? (int)$datos['id'] : 0;
		error_log("UserID: $userId");
		
		if($userId <= 0){
			error_log("Error: ID inválido");
			return [
				'ejecuto' => false,
				'mensajeError' => 'ID de usuario inválido'
			];
		}

		// Validar sesión
		if(!isset($_SESSION['usuario']['id']) || $_SESSION['usuario']['id'] != $userId){
			// Solo el propio usuario puede subir su foto (o admin)
			$rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : '';
			error_log("Validación sesión - Usuario sesión: " . $_SESSION['usuario']['id'] . ", Usuario destino: $userId, Rol: $rol");
			if($rol !== 'Administrador'){
				error_log("Error: No autorizado");
				return [
					'ejecuto' => false,
					'mensajeError' => 'No autorizado para subir foto de este usuario'
				];
			}
		}

		$archivo = $_FILES['foto'];
		error_log("Archivo: " . $archivo['name'] . ", Tamaño: " . $archivo['size'] . " bytes");
		
		$extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
		$extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];

		if(!in_array($extension, $extensionesPermitidas)){
			error_log("Error: Extensión no permitida: $extension");
			return [
				'ejecuto' => false,
				'mensajeError' => 'Solo se permiten archivos JPG, PNG o GIF'
			];
		}

		// Tamaño máximo: 2MB
		if($archivo['size'] > 2 * 1024 * 1024){
			error_log("Error: Archivo muy grande");
			return [
				'ejecuto' => false,
				'mensajeError' => 'El archivo no debe superar los 2MB'
			];
		}

		// Generar nombre único
		$nombreArchivo = 'user_' . $userId . '_' . time() . '.' . $extension;
		$rutaDestino = __DIR__ . '/../fotos/' . $nombreArchivo;
		error_log("Ruta destino: $rutaDestino");
		error_log("Directorio existe: " . (is_dir(__DIR__ . '/../fotos/') ? 'SI' : 'NO'));
		error_log("Directorio writable: " . (is_writable(__DIR__ . '/../fotos/') ? 'SI' : 'NO'));

		// Eliminar foto anterior si existe
		$db = new database();
		$usuarioActual = $db->ejecutarConsulta("SELECT foto FROM usuarios WHERE id = $userId");
		if($usuarioActual['ejecuto'] && !empty($usuarioActual['data'][0]['foto'])){
			$fotoAnterior = __DIR__ . '/../fotos/' . $usuarioActual['data'][0]['foto'];
			if(file_exists($fotoAnterior)){
				error_log("Eliminando foto anterior: $fotoAnterior");
				@unlink($fotoAnterior);
			}
		}

		// Mover archivo
		error_log("Intentando mover de " . $archivo['tmp_name'] . " a $rutaDestino");
		if(move_uploaded_file($archivo['tmp_name'], $rutaDestino)){
			error_log("Archivo movido exitosamente");
			// Actualizar base de datos
			$resultado = parent::update([
				'info' => ['foto' => $nombreArchivo],
				'id' => $userId
			]);

			error_log("Resultado BD: " . print_r($resultado, true));

			return [
				'ejecuto' => true,
				'foto' => $nombreArchivo,
				'mensaje' => 'Foto subida correctamente'
			];
		} else {
			error_log("ERROR: No se pudo mover el archivo");
			return [
				'ejecuto' => false,
				'mensajeError' => 'Error al mover el archivo al destino'
			];
		}
	}

	// Eliminar foto de perfil
	public function deleteFoto($datos){
		$userId = isset($datos['id']) ? (int)$datos['id'] : 0;
		if($userId <= 0){
			return [
				'ejecuto' => false,
				'mensajeError' => 'ID de usuario inválido'
			];
		}

		// Validar sesión
		if(!isset($_SESSION['usuario']['id']) || $_SESSION['usuario']['id'] != $userId){
			$rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : '';
			if($rol !== 'Administrador'){
				return [
					'ejecuto' => false,
					'mensajeError' => 'No autorizado'
				];
			}
		}

		$db = new database();
		$usuario = $db->ejecutarConsulta("SELECT foto FROM usuarios WHERE id = $userId");
		
		if($usuario['ejecuto'] && !empty($usuario['data'][0]['foto'])){
			$fotoPath = __DIR__ . '/../fotos/' . $usuario['data'][0]['foto'];
			if(file_exists($fotoPath)){
				@unlink($fotoPath);
			}

			// Limpiar campo en base de datos
			parent::update([
				'info' => ['foto' => NULL],
				'id' => $userId
			]);

			return [
				'ejecuto' => true,
				'mensaje' => 'Foto eliminada correctamente'
			];
		}

		return [
			'ejecuto' => false,
			'mensajeError' => 'No hay foto para eliminar'
		];
	}
}