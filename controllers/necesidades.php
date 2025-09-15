<?php
require_once "libs/baseCrud.php";
require_once "necesidadesImputaciones.php";

class necesidades extends baseCrud{
	protected $tabla = 'necesidades';

	public function crear($datos){
		//Se guarda la necesidad
		$resultado = parent::insert([
			'info' => $datos['info']
		]);

		if($resultado['ejecuto']){
			//Ingreso imputaciones
			$objNI = new necesidadesImputaciones();
			foreach ($datos['imputaciones'] as $value) {
				$respuesta = $objNI->insert([
					'info' => [
						'fk_necesidades' => $resultado['insertId'],
						'imputacion' => $value['imputacion'],
						'valor' => $value['valor']
					]
				]);
			}
			if($respuesta['ejecuto']){
				return $resultado;
			}
		}
	}

	public function getNecesidades($datos){
		$filtro = 0;
		switch ($datos['criterio']) {
			case 'id':
				$filtro = "nec.id = ".$datos['valor'];
				break;
			case 'gerenciaVigencia':				
				$filtro = "dep.fk_gerencias = $datos[gerencia] AND nec.fk_vigencias = $datos[vigencia]";
				break;
			case 'libres':
				$filtro = "nec.estado = 'Libre' AND vig.vigencia = YEAR(CURDATE())";
				break;
			default:
				$filtro = 0;
				break;
		}
		$sql = "SELECT
					nec.id,
					ger.nombre AS gerencia,
					dep.dependencia AS dependencia,
					dep.unidad AS unidad,
					nec.definicion_tecnica,
					nec.profesion,
					nec.objeto,
					nec.alcance,
					nec.conocimientos,
					nec.honorarios,
					nec.presupuesto,
					ROUND(nec.presupuesto / nec.honorarios, 1) AS tiempo,
					nec.estado
				FROM
					(necesidades nec INNER JOIN vigencias vig ON nec.fk_vigencias = vig.id) INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id
				WHERE					
					$filtro";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}