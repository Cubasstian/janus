<?php
require_once "libs/baseCrud.php";

class solicitudesHistorico extends baseCrud{
	protected $tabla = 'solicitudes_historico';

	public function getHistorico($datos){
		$sql = "SELECT
					usu.nombre,
					sh.informacion,
					sh.fecha_creacion
				FROM
					solicitudes_historico sh INNER JOIN usuarios usu ON sh.creado_por = usu.id
				WHERE
					sh.fk_solicitudes = $datos[solicitud]
				ORDER BY
					sh.fecha_creacion";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
    }
}