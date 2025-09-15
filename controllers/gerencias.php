<?php
require_once "libs/baseCrud.php";

class gerencias extends baseCrud{
	protected $tabla = 'gerencias';

	public function getGerencias($datos){
		$filtro = 0;
		switch ($datos['criterio']) {
			case 'id':
				$filtro = "ger.id = ".$datos['valor'];
				break;
			case 'rol':
				if($_SESSION['usuario']['rol'] == 'Administrador'){
					$filtro = 1;
				}else{
					$filtro = "ger.id = ".$_SESSION['usuario']['gerencia'];
				}
				break;
			default:
				$filtro = 0;
				break;
		}
		$sql = "SELECT
					ger.id,
					ger.nombre
				FROM
					gerencias ger
				WHERE					
					$filtro
					AND ger.estado = 'Activo'
				ORDER BY
					id";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}