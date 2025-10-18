<?php
require_once "libs/baseCrud.php";

class dependencias extends baseCrud{
	protected $tabla = 'dependencias';

	public function getDep($datos){
		$sql = "SELECT					
					dep.dependencia
				FROM
					dependencias dep
				WHERE
					dep.fk_gerencias = '{$datos['gerencia']}'
					AND (dep.estado = 'Activo' OR dep.estado = 'activo' OR dep.estado = '1' OR dep.estado IS NULL)
				GROUP BY
					dep.dependencia";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function getDepTodas($datos){
		$sql = "SELECT					
					dep.dependencia,
					dep.estado
				FROM
					dependencias dep
				WHERE
					dep.fk_gerencias = '{$datos['gerencia']}'
				GROUP BY
					dep.dependencia, dep.estado";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function getUnidades($datos){
		$sql = "SELECT
					dep.id,
					dep.unidad
				FROM
					dependencias dep
				WHERE
					dep.fk_gerencias = '{$datos['gerencia']}'
					AND dep.dependencia = '{$datos['dep']}'
					AND dep.estado = 'Activo'";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function getAllDependencias($datos = []){
		$sql = "SELECT DISTINCT
					dep.dependencia
				FROM
					dependencias dep
				WHERE
					(dep.estado = 'Activo' OR dep.estado = 'activo' OR dep.estado = '1' OR dep.estado IS NULL)
					AND dep.dependencia != ''
				ORDER BY
					dep.dependencia";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}