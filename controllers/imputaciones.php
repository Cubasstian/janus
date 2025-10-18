<?php
require_once "libs/baseCrud.php";
require_once "gerenciasCege.php";
require_once "gerenciasFondos.php";

class imputaciones extends baseCrud{
	protected $tabla = 'imputaciones';

	public function getImputaciones($datos){
		$db = new database();
		$where = '0=1'; $types = ''; $params = [];
		switch ($datos['criterio']) {
			case 'id':
				$where = 'imp.id = ?'; $types = 'i'; $params[] = (int)$datos['valor'];
				break;
			case 'vigencia':
				$where = 'imp.fk_vigencias = ?'; $types = 'i'; $params[] = (int)$datos['valor'];
				break;
			default:
				$where = '0=1';
				break;
		}
		$sql = "SELECT imp.id, imp.imputacion, imp.maximo, imp.fk_vigencias, v.vigencia 
				FROM imputaciones imp 
				LEFT JOIN vigencias v ON imp.fk_vigencias = v.id 
				WHERE $where 
				ORDER BY imp.id";
		return $db->ejecutarPreparado($sql, $types, $params);
	}

	public function getImputacionesAplican($datos){
		//primero traigo los centros de gestión
		$objCege = new gerenciasCege();
		$cege = $objCege->getCege([
			'gerencia' => $datos['gerencia']
		]);
		//luego los fondos
		$objFondos = new gerenciasFondos();
		$fondos = $objFondos->getFondos([
			'gerencia' => $datos['gerencia']
		]);
		//Esto en el caso de los negocios que no tienen fondos centralizados
		if(count($fondos['data']) == 0){
			$fondos['data'][0]['fondos'] = 0;
		}
		$sql = "SELECT imp.imputacion, imp.maximo
				FROM imputaciones imp
				WHERE imp.fk_vigencias = ?
				  AND ((SUBSTRING_INDEX(imp.imputacion,'.',1) IN (".$cege['data'][0]['centros'].") AND SUBSTRING_INDEX(SUBSTRING_INDEX(imp.imputacion,'.',3),'.',-1) = '10000')
					   OR SUBSTRING_INDEX(SUBSTRING_INDEX(imp.imputacion,'.',3),'.',-1) IN (".$fondos['data'][0]['fondos']."))
				ORDER BY imp.id";
		$db = new database();
		return $db->ejecutarPreparado($sql, 'i', [ (int)$datos['gerencia'] ? (int)$datos['vigencia'] : (int)$datos['vigencia'] ]);
	}
}