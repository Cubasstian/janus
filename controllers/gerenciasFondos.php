<?php
require_once "libs/baseCrud.php";

class gerenciasFondos extends baseCrud{
	protected $tabla = 'gerencias_fondos';

	public function getFondos($datos){
		$sql = "SELECT
					GROUP_CONCAT(CONCAT(\"'\", fondo, \"'\")) AS fondos
				FROM
					gerencias_fondos gf
				WHERE
					gf.fk_gerencias = $datos[gerencia]
				GROUP BY
					gf.fk_gerencias";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}