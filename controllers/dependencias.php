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
					dep.fk_gerencias = $datos[gerencia]
					AND dep.estado = 'Activo'
				GROUP BY
					dep.dependencia";
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
					dep.fk_gerencias = $datos[gerencia]
					AND dep.dependencia = '$datos[dep]'
					AND dep.estado = 'Activo'";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}