<?php
require_once "libs/baseCrud.php";

class usuarios extends baseCrud{
	protected $tabla = 'usuarios';

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
			if($resultado['data'][0]['rol'] == 'PS'){
				$datos['info']['password'] = md5($datos['info']['password']);
				$resultado = parent::select($datos);
				if(count($resultado['data']) == 0) {
					return [
						'ejecuto' => false,
						'mensajeError' => 'Credenciales erróneas'
					];
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
				'rol' => $resultado['data'][0]['rol'],
				'nombre' => $resultado['data'][0]['nombre'],
				'gerencia' => $resultado['data'][0]['fk_gerencias']
			];
			//Guardar variables en sesión
			$_SESSION['usuario'] = $usuario;

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
				$filtro = "usu.id = ".$datos['id'];
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
					usu.estado
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
		$datos['info']['password'] = md5($datos['info']['password']);
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
					usu.fk_fondos_pension
				FROM
					usuarios usu
				WHERE
					usu.id = $datos[id]";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}