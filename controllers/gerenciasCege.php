<?php
require_once "libs/baseCrud.php";

class gerenciasCege extends baseCrud{
	protected $tabla = 'gerencias_cege';

	public function getCege($datos){
		$sql = "SELECT
					GROUP_CONCAT(CONCAT(\"'\", cege, \"'\")) AS centros
				FROM
					gerencias_cege gc
				WHERE
					gc.fk_gerencias = $datos[gerencia]
				GROUP BY
					gc.fk_gerencias";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}