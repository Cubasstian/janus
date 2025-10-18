<?php

class database extends mysqli{
	/*private $DB_HOST = 'localhost';
	private $DB_NAME = 'asimati';
	private $DB_USER = 'root';
	private $DB_PASS = '';*/

	private $DB_HOST = 'localhost';
	private $DB_NAME = 'contratacionps';
	private $DB_USER = 'root';
	private $DB_PASS = '';
	
	public function __construct(){
		parent::__construct($this->DB_HOST,$this->DB_USER,$this->DB_PASS,$this->DB_NAME);
		if(mysqli_connect_errno()){
			printf("Fallo la conexión: %s", mysqli_connect_error());
			exit();
		}

		if(!$this->set_charset("utf8")){
			printf("Fallo utf-8 %s", $this->error);
			exit();
		}
	}

	public function select($tabla, $datos){
		$sql = "SELECT * FROM $tabla WHERE 1";
		foreach ($datos['info'] as $key => $value) {
			$val = is_null($value) ? 'NULL' : "'".$this->real_escape_string($value)."'";
			$sql .= " AND $key = $val";
		}
		if(isset($datos['nodefault'])){
			$sql .= " AND id != 1";
		}
		if(isset($datos['orden'])){
			$sql .= " ORDER BY $datos[orden]";
		}
		return $this->ejecutarConsulta($sql);
	}

	public function insert($tabla, $datos){
		$sql = "INSERT INTO $tabla SET ";
		foreach ($datos['info'] as $key => $value) {
			$sql .= "$key = '".$this->real_escape_string($value)."',";
		}
		$sql .= "creado_por = ".$_SESSION['usuario']['id'].", fecha_creacion=NOW()";
		return $this->ejecutarConsulta($sql);
	}

	public function update($tabla, $datos){
		$sql = "UPDATE $tabla SET ";
		foreach ($datos['info'] as $key => $value) {
			$sql .= "$key = '".$this->real_escape_string($value)."',";
		}
		$sql .= "modificado_por = ".$_SESSION['usuario']['id'].", fecha_modificacion=NOW()";
		$sql .= " WHERE id = $datos[id]";
		return $this->ejecutarConsulta($sql);
	}

	public function delete($tabla, $datos){
		$sql = "DELETE FROM $tabla WHERE id = $datos[id]";
		return $this->ejecutarConsulta($sql);
	}

	public function cantidad($tabla, $datos){
		$sql = "SELECT COUNT(1) AS cantidad\tFROM $tabla WHERE 1";
		foreach ($datos['info'] as $key => $value) {
			$val = is_null($value) ? 'NULL' : "'".$this->real_escape_string($value)."'";
			$sql .= " AND $key = $val";
		}
		if(isset($datos['nodefault'])){
			$sql .= " AND id != 1";
		}
		return $this->ejecutarConsulta($sql);
	}

	public function ejecutarConsulta($sql, $cerrar = true){
		$respuesta = [];
		try {
			$resultado = $this->query($sql);		
			//Devuelve true en caso de que sea un INSERT, UPDATE o DELETE
			if($resultado === TRUE){
				$respuesta['ejecuto'] = true;
				$respuesta['insertId'] = $this->insert_id;
				$respuesta['affectedRows'] = $this->affected_rows;
			//Devulve un object cuando tiene resultados
			}elseif(is_object($resultado)){
				$respuesta['ejecuto'] = true;
				$respuesta['data'] = [];
				while($row = $resultado->fetch_array(MYSQLI_ASSOC)){
					$respuesta['data'][] = $row;
				}
				$resultado->free();
			//En caso de que el query retorne error
			}else{
				$respuesta['ejecuto'] = false;
				$respuesta['codigoError'] = $this->errno;
				$respuesta['mensajeError'] = $this->error;
			}
			if($cerrar){
				$this->close();
			}
			return $respuesta;	
		} catch (Exception $e) {
			if($e->getCode() == 1062){
		    	return [
						'ejecuto' => false,
						'codigoError' => 1000,
						'mensajeError' => 'Registro duplicado',
						'mensajeReal' => $e->getMessage()
					];
			}else{
				return [
						'ejecuto' => false,
						'codigoError' => 1000,
						'mensajeError' => 'Opss! tenemos un error',
						'mensajeReal' => $e->getMessage()
					];
			}
		}
	}

	// Nuevo: ejecutar consulta preparada con tipos y parámetros
	// $types: string de tipos (ej: 'i', 's', 'd', 'b'), $params: array de valores
	public function ejecutarPreparado($sql, $types = '', $params = [], $cerrar = true){
		$respuesta = [];
		try{
			$stmt = $this->prepare($sql);
			if(!$stmt){
				return [ 'ejecuto' => false, 'codigoError' => $this->errno, 'mensajeError' => $this->error ];
			}
			if($types && !empty($params)){
				// Asegurar referencias para bind_param
				$bindParams = [];
				$bindParams[] = & $types;
				foreach($params as $k => $v){ $bindParams[] = & $params[$k]; }
				call_user_func_array([$stmt, 'bind_param'], $bindParams);
			}
			if(!$stmt->execute()){
				$respuesta = [ 'ejecuto' => false, 'codigoError' => $stmt->errno, 'mensajeError' => $stmt->error ];
			}else{
				// Distinguir SELECT vs no-SELECT
				if($stmt->field_count > 0){
					// Intentar get_result (mysqlnd). Si no, fallback manual.
					if(method_exists($stmt, 'get_result')){
						$result = $stmt->get_result();
						$data = [];
						while($row = $result->fetch_assoc()){ $data[] = $row; }
						$result->free();
						$respuesta = [ 'ejecuto' => true, 'data' => $data ];
					}else{
						$meta = $stmt->result_metadata();
						$fields = [];
						$row = [];
						$bind = [];
						while($field = $meta->fetch_field()){
							$fields[] = $field->name;
							$row[$field->name] = null;
							$bind[] = & $row[$field->name];
						}
						call_user_func_array([$stmt, 'bind_result'], $bind);
						$data = [];
						while($stmt->fetch()){
							$data[] = array_map(function($v){ return $v; }, $row);
						}
						$respuesta = [ 'ejecuto' => true, 'data' => $data ];
					}
				}else{
					$respuesta = [ 'ejecuto' => true, 'insertId' => $stmt->insert_id, 'affectedRows' => $stmt->affected_rows ];
				}
			}
			$stmt->close();
			if($cerrar){ $this->close(); }
			return $respuesta;
		}catch(Exception $e){
			return [ 'ejecuto' => false, 'codigoError' => 1000, 'mensajeError' => 'Error al ejecutar consulta preparada', 'mensajeReal' => $e->getMessage() ];
		}
	}

	// Ejecuta varias consultas preparadas secuencialmente reutilizando la misma conexión.
	// $consultas: array de ['sql'=>..., 'types'=>..., 'params'=>array]
	// Retorna array indexado con la respuesta de cada ejecutarPreparado.
	public function ejecutarPreparados(array $consultas){
		$resultados = [];
		$lastIndex = count($consultas) - 1;
		foreach($consultas as $i => $c){
			$sql = isset($c['sql']) ? $c['sql'] : '';
			$types = isset($c['types']) ? $c['types'] : '';
			$params = isset($c['params']) ? $c['params'] : [];
			// Solo la última cierra la conexión
			$resultados[$i] = $this->ejecutarPreparado($sql, $types, $params, $i === $lastIndex);
			// Si alguna falla, opcionalmente podríamos romper; por ahora continuamos para devolver todo.
		}
		return $resultados;
	}
}