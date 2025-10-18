<?php
require_once "libs/baseCrud.php";

class necesidadesImputaciones extends baseCrud{
	protected $tabla = 'necesidades_imputaciones';

	public function getComprometido($datos){
		$sql = "SELECT SUM(ni.valor) AS comprometido
				FROM necesidades nec INNER JOIN necesidades_imputaciones ni ON nec.id = ni.fk_necesidades
				WHERE nec.fk_vigencias = ? AND ni.imputacion = ?";
		$db = new database();
		return $db->ejecutarPreparado($sql, 'is', [ (int)$datos['vigencia'], (string)$datos['imputacion'] ]);
	}

	public function getTodas($datos){
		$sql = "SELECT ni.imputacion, SUM(ni.valor) AS comprometido
				FROM necesidades nec INNER JOIN necesidades_imputaciones ni ON nec.id = ni.fk_necesidades
				WHERE nec.fk_vigencias = ?
				GROUP BY ni.imputacion";
		$db = new database();
		return $db->ejecutarPreparado($sql, 'i', [ (int)$datos['vigencia'] ]);
	}

	public function deleteByNecesidad($idNecesidad){
		$sql = "DELETE FROM necesidades_imputaciones WHERE fk_necesidades = ?";
		$db = new database();
		return $db->ejecutarPreparado($sql, 'i', [ (int)$idNecesidad ]);
	}
}