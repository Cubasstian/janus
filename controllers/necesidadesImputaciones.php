<?php
require_once "libs/baseCrud.php";

class necesidadesImputaciones extends baseCrud{
	protected $tabla = 'necesidades_imputaciones';

	public function getComprometido($datos){
		$sql = "SELECT
					SUM(ni.valor) AS comprometido
				FROM
					necesidades nec INNER JOIN necesidades_imputaciones ni ON nec.id = ni.fk_necesidades
				WHERE
					nec.fk_vigencias = $datos[vigencia]
					AND ni.imputacion = '$datos[imputacion]'";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function getTodas($datos){
		$sql = "SELECT
					ni.imputacion,
					SUM(ni.valor) AS comprometido
				FROM
					necesidades nec INNER JOIN necesidades_imputaciones ni ON nec.id = ni.fk_necesidades
				WHERE
					nec.fk_vigencias = $datos[vigencia]
				GROUP BY
					ni.imputacion";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}