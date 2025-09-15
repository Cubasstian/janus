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
			$sql .= " AND $key = '$value'";
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
		$sql = "SELECT COUNT(1) AS cantidad	FROM $tabla WHERE 1";
		foreach ($datos['info'] as $key => $value) {
			$sql .= " AND $key = '$value'";
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
}