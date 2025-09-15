<?php
require_once "libs/baseCrud.php";
require_once "gerenciasCege.php";
require_once "gerenciasFondos.php";

class imputaciones extends baseCrud{
	protected $tabla = 'imputaciones';

	public function getImputaciones($datos){
		$filtro = 0;
		switch ($datos['criterio']) {
			case 'id':
				$filtro = "imp.id = ".$datos['valor'];
				break;
			case 'vigencia':
				$filtro = "imp.fk_vigencias = ".$datos['valor'];
				break;
			default:
				$filtro = 0;
				break;
		}
		$sql = "SELECT
					imp.id,
					imp.imputacion,
					imp.maximo
				FROM
					imputaciones imp
				WHERE					
					$filtro
				ORDER BY
					imp.id";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
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
		$sql = "SELECT
					imp.imputacion,
					imp.maximo
				FROM
					imputaciones imp
				WHERE
					imp.fk_vigencias = $datos[vigencia]
					AND ((SUBSTRING_INDEX(imp.imputacion,'.',1) IN (".$cege['data'][0]['centros'].") AND SUBSTRING_INDEX(SUBSTRING_INDEX(imp.imputacion,'.',3),'.',-1) = '10000') OR SUBSTRING_INDEX(SUBSTRING_INDEX(imp.imputacion,'.',3),'.',-1) IN (".$fondos['data'][0]['fondos']."))
				ORDER BY
					imp.id";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}