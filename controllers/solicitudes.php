<?php
require_once "libs/baseCrud.php";
require_once "procesos.php";
require_once "solicitudesHistorico.php";
require_once "necesidades.php";

class solicitudes extends baseCrud{
	protected $tabla = 'solicitudes';

	public function crear($datos){
		$resultado = parent::insert([
			'info' => $datos
		]);
		//Guardo historico
		if($resultado['ejecuto']){
			$info = [
				'info'=>[
					'fk_solicitudes'=>$resultado['insertId'],
					'informacion' => json_encode($datos)
				]
			];
			$objHistorico = new solicitudesHistorico();
			$respuesta = $objHistorico->insert($info);
			if($respuesta['ejecuto']){
				return $resultado;
			}
		}else{
			return $resultado;
		}
	}

	public function getSolicitudes($datos){
		$filtro = 0;
		switch ($datos['criterio']) {
			case 'id':
				$filtro = "sol.id = ".$datos['id'];
				break;
			case 'area':
				if($_SESSION['usuario']['rol'] == 'Administrador'){
					$filtro = 1;
				}else{
					$filtro = "ger.id = ".$_SESSION['usuario']['gerencia'];
				}
				break;
			case 'todas':
				$filtro = 1;
				break;
			default:
				$filtro = 0;
				break;
		}
		if(isset($datos['estado'])){
			$filtro .= " AND sol.estado = ".$datos['estado'];
		}
		$sql = "SELECT
					sol.id,
					ger.id AS idGerencia,
					ger.nombre AS gerencia,
					dep.dependencia,
					dep.unidad,
					nec.id AS idNecesidad,
					nec.profesion,
					nec.honorarios,
					nec.presupuesto,
					pro.id AS idProceso,
					usu.id AS idPS,
					usu.nombre AS ps,
					usu.cedula,
					usu.telefono,
					DATEDIFF(NOW(),sol.fecha_modificacion) AS tiempo
				FROM
					solicitudes sol INNER JOIN ((procesos pro INNER JOIN usuarios usu ON pro.contratista = usu.id) INNER JOIN (necesidades nec INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id) ON pro.fk_necesidades = nec.id) ON sol.fk_procesos = pro.id
				WHERE					
					$filtro";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function getSolicitudAll($datos){
		$filtro = 0;
		switch ($datos['criterio']) {
			case 'id':
				$filtro = "sol.id = ".$datos['valor'];
				break;
			default:
				$filtro = 0;
				break;
		}
		$sql = "SELECT
					sol.id,
					ger.id AS idGerencia,
					ger.nombre AS gerencia,
					dep.dependencia,
					dep.unidad,
					nec.id AS idNecesidad,
					nec.pacc,
					nec.profesion,
					nec.objeto,
					nec.alcance,
					nec.honorarios,
					nec.presupuesto,
					pro.id AS idProceso,
					usu.id AS idPS,
					usu.nombre AS ps,
					usu.cedula,
					usu.correo,
					usu.telefono,
					sol.cdp_numero,
					sol.cdp_valor,
					sol.cdp_fecha,
					sol.estado,
					sol.fecha_creacion
				FROM
					solicitudes sol INNER JOIN ((procesos pro INNER JOIN usuarios usu ON pro.contratista = usu.id) INNER JOIN (necesidades nec INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id) ON pro.fk_necesidades = nec.id) ON sol.fk_procesos = pro.id
				WHERE					
					$filtro";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function setEstado($datos){
        //Actualizo la solicitud
        $resultado = parent::update($datos);        
        //Guardo historico
        if($resultado['ejecuto']){
            $info = [
                'info'=>[
                    'fk_solicitudes'=>$datos['id'],
                    'informacion'=>json_encode($datos['info'])
                ]
            ];
            $sh = new solicitudesHistorico();
            $historico = $sh->insert($info);
            if($historico['ejecuto']){              
                return $resultado;
            }
        }else{
			return $resultado;
		}
    }

    public function setProceso($datos){    	
		//Actualizo el contrato
		$objProcesos = new procesos();
		$respuesta = $objProcesos->update($datos['infoP']);
		if($respuesta['ejecuto']){
			//Actualizo la solicitud
			return $this->setEstado($datos['infoS']);
		}
	}

	public function ocuparNecesidad($datos){
		//Ocupo Necesidad
		$objNecesidades = new necesidades();
		$respuesta = $objNecesidades->update($datos['infoN']);
		if($respuesta['ejecuto']){
			//Actualizo el proceso
			return $this->setProceso($datos);
		}
	}

	public function getExportCDP($datos){
		$sql = "SELECT
					usu.nombre,
					ni.imputacion,
					ni.valor
				FROM
					(necesidades nec INNER JOIN necesidades_imputaciones ni ON nec.id = ni.fk_necesidades) INNER JOIN ((procesos pro INNER JOIN solicitudes sol ON pro.id = sol.fk_procesos) INNER JOIN usuarios usu ON pro.contratista = usu.id) ON nec.id = pro.fk_necesidades
				WHERE
					sol.estado = 4
				ORDER BY
					usu.nombre";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}