<?php
require_once "libs/baseCrud.php";

class documentosRevisiones extends baseCrud{
	protected $tabla = 'documentos_revisiones';

	public function getRevisiones($datos){
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