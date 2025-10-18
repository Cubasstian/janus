<?php
require_once "libs/baseCrud.php";
require_once "necesidadesImputaciones.php";

class necesidades extends baseCrud{
	protected $tabla = 'necesidades';

	public function crear($datos){
		//Se guarda la necesidad
		$resultado = parent::insert([
			'info' => $datos['info']
		]);

		if($resultado['ejecuto']){
			//Ingreso imputaciones
			$objNI = new necesidadesImputaciones();
			foreach ($datos['imputaciones'] as $value) {
				$respuesta = $objNI->insert([
					'info' => [
						'fk_necesidades' => $resultado['insertId'],
						'imputacion' => $value['imputacion'],
						'valor' => $value['valor']
					]
				]);
			}
			if($respuesta['ejecuto']){
				return $resultado;
			}
		}
	}

	public function actualizar($datos){
		// Actualizar la necesidad
		$resultado = parent::update([
			'info' => $datos['info'],
			'id' => $datos['id']
		]);

		if($resultado['ejecuto']){
			// Eliminar imputaciones existentes
			$objNI = new necesidadesImputaciones();
			$objNI->deleteByNecesidad($datos['id']);
			
			// Insertar nuevas imputaciones
			foreach ($datos['imputaciones'] as $value) {
				$respuesta = $objNI->insert([
					'info' => [
						'fk_necesidades' => $datos['id'],
						'imputacion' => $value['imputacion'],
						'valor' => $value['valor']
					]
				]);
			}
			if($respuesta['ejecuto']){
				return $resultado;
			}
		}
		return $resultado;
	}

	public function getNecesidades($datos){
		$where = '0=1'; $types = ''; $params = [];
		switch ($datos['criterio']) {
			case 'id':
				$where = 'nec.id = ?'; $types = 'i'; $params[] = (int)$datos['valor'];
				break;
			case 'gerenciaVigencia':
				$where = 'dep.fk_gerencias = ? AND nec.fk_vigencias = ?';
				$types = 'ii';
				$params[] = (int)$datos['gerencia'];
				$params[] = (int)$datos['vigencia'];
				break;
			case 'gerencia':
				$where = 'dep.fk_gerencias = ?';
				$types = 'i';
				$params[] = (int)$datos['gerencia'];
				break;
			case 'todas':
				$where = '1=1';
				break;
			case 'libres':
				$where = "nec.estado = 'Libre' AND vig.vigencia = YEAR(CURDATE())";
				break;
			default:
				$where = '0=1';
				break;
		}
		$sql = "SELECT
					nec.id,
					nec.pacc,
					ger.nombre AS gerencia,
					nec.fk_dependencias,
					dep.dependencia AS dependencia,
					dep.unidad AS unidad,
					nec.definicion_tecnica,
					nec.profesion,
					nec.objeto,
					nec.alcance,
					nec.conocimientos,
					nec.honorarios,
					nec.presupuesto,
					ROUND(nec.presupuesto / nec.honorarios, 1) AS tiempo,
					nec.estado,
					vig.vigencia,
					nec.fk_vigencias,
					dep.fk_gerencias
				FROM
					(necesidades nec INNER JOIN vigencias vig ON nec.fk_vigencias = vig.id) INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id
				WHERE $where
				ORDER BY nec.id DESC";
		$db = new database();
		return $db->ejecutarPreparado($sql, $types, $params);
	}
}