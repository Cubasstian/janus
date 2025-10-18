<?php
require_once "libs/baseCrud.php";
require_once "libs/database.php";

class topeHonorarios extends baseCrud{
	protected $tabla = 'tope_honorarios';

	public function select($datos){
		try {
			// Select personalizado con JOIN para traer el nombre de la vigencia
			$db = new database();
			$sql = "SELECT th.*, v.vigencia 
					FROM tope_honorarios th 
					LEFT JOIN vigencias v ON th.fk_vigencias = v.id";
			
			$where = "";
			$params = [];
			$types = "";
			
			if(isset($datos['info']) && is_array($datos['info'])){
				$conditions = [];
				foreach($datos['info'] as $key => $value){
					// Ignorar claves numéricas como "1" que se usan para cargar todos los registros
					if(is_numeric($key)){
						continue;
					}
					
					if($key == 'id'){
						$conditions[] = "th.id = ?";
						$params[] = (int)$value;
						$types .= "i";
					} elseif($key == 'fk_vigencias'){
						$conditions[] = "th.fk_vigencias = ?";
						$params[] = (int)$value;
						$types .= "i";
					} elseif($key == 'grado'){
						$conditions[] = "th.grado = ?";
						$params[] = (int)$value;
						$types .= "i";
					} elseif($key == 'nivel'){
						$conditions[] = "th.nivel = ?";
						$params[] = $value;
						$types .= "s";
					} elseif($key != 'nodefault'){
						// Para otros campos, verificar que existan en la tabla
						$conditions[] = "th.$key = ?";
						$params[] = $value;
						$types .= "s";
					}
				}
				if(!empty($conditions)){
					$where = " WHERE " . implode(" AND ", $conditions);
				}
			}
			
			// Ordenamiento por defecto
			$orderBy = " ORDER BY v.vigencia DESC, th.grado ASC, th.nivel ASC";
			
			$sql .= $where . $orderBy;
			
			// Usar el método select de baseCrud si no hay parámetros específicos, o ejecutar la query personalizada
			if(empty($params)){
				return $db->ejecutarConsulta($sql);
			} else {
				return $db->ejecutarPreparado($sql, $types, $params);
			}
		} catch (Exception $e) {
			// En caso de error, retornar usando el método padre
			error_log("Error en topeHonorarios select: " . $e->getMessage());
			return parent::select($datos);
		}
	}

	public function insert($datos){
		// Validación anti-duplicados por (fk_vigencias, grado, nivel)
		$f = $datos['info'];
		$db = new database();
		$sql = "SELECT id FROM tope_honorarios WHERE fk_vigencias = ? AND grado = ? AND nivel = ? LIMIT 1";
		$existe = $db->ejecutarPreparado($sql, 'iii', [ (int)$f['fk_vigencias'], (int)$f['grado'], (int)$f['nivel'] ]);
		if(isset($existe['data']) && count($existe['data']) > 0){
			return [
				'ejecuto' => false,
				'codigoError' => 1062,
				'mensajeError' => 'Ya existe un tope para esa vigencia, grado y nivel'
			];
		}
		return parent::insert($datos);
	}

	public function update($datos){
		// Validación anti-duplicados al actualizar
		$f = $datos['info'];
		$id = (int)$datos['id'];
		$db = new database();
		$sql = "SELECT id FROM tope_honorarios WHERE fk_vigencias = ? AND grado = ? AND nivel = ? AND id != ? LIMIT 1";
		$existe = $db->ejecutarPreparado($sql, 'iiii', [ (int)$f['fk_vigencias'], (int)$f['grado'], (int)$f['nivel'], $id ]);
		if(isset($existe['data']) && count($existe['data']) > 0){
			return [
				'ejecuto' => false,
				'codigoError' => 1062,
				'mensajeError' => 'Ya existe un tope para esa vigencia, grado y nivel'
			];
		}
		return parent::update($datos);
	}
}
