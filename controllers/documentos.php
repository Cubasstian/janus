<?php
require_once "libs/baseCrud.php";
require_once "documentosRevisiones.php";

class documentos extends baseCrud{
	protected $tabla = 'documentos';

	public function crear($datos){
		$sql = "INSERT INTO
					documentos
				SET
					contratista = $datos[contratista],
					fk_procesos = $datos[proceso],
					fk_documentos_tipo = $datos[tipo],
					creado_por = ".$_SESSION['usuario']['id'].",
					fecha_creacion = NOW()
				ON DUPLICATE KEY UPDATE
					fecha_modificacion=NOW()";
		$db = new database();
       	$resultado = $db->ejecutarConsulta($sql);
       	if($resultado['ejecuto']){
			$info = [
				'info'=>[
					'fk_documentos'=>$resultado['insertId'],
					'estado' => 1
				]
			];
			$objRevisiones = new documentosRevisiones();
			$respuesta = $objRevisiones->insert($info);
			if($respuesta['ejecuto']){
				return $resultado;
			}else{
				return $respuesta;
			}
		}else{
			return $resultado;
		}
	}

	public function getDocumentos($datos){
		$filtro = 0;
		switch ($datos['criterio']) {
			case 'id':
				$filtro = "doc.id = ".$datos['id'];
				break;
			case 'generales':
				$filtro = "doc.contratista = $datos[contratista] AND doc.fk_procesos = $datos[proceso] AND doc.fk_documentos_tipo != 2";
				break;
			default:
				$filtro = 0;
				break;
		}
		$sql = "SELECT
					doc.id,
					doc.fk_documentos_tipo AS tipo,
					(doc.fk_documentos_tipo - 2) AS numero,
					doc.conteo,
					doc.estado,
					doc.observaciones
				FROM
					documentos doc
				WHERE					
					$filtro";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function setEstado($datos){
        //Actualizo la solicitud
        $sql = "UPDATE
					documentos
				SET
					conteo = conteo + 1,
					estado = $datos[estado],					
					modificado_por = ".$_SESSION['usuario']['id'].",
					fecha_modificacion = NOW(),
					observaciones = '$datos[observaciones]'
				WHERE
					id = $datos[id]";
		$db = new database();
       	$resultado = $db->ejecutarConsulta($sql);
        //$resultado = parent::update($datos);
        //Guardo historico
        if($resultado['ejecuto']){
            $info = [
                'info'=>[
                    'fk_documentos'=>$datos['id'],
                    'estado'=> $datos['estado'],
                    'observaciones'=> $datos['observaciones']
                ]
            ];
            $objRevisiones = new documentosRevisiones();
			$respuesta = $objRevisiones->insert($info);
			if($respuesta['ejecuto']){
				return $resultado;
			}else{
				return $respuesta;
			}
        }else{
			return $resultado;
		}
    }

    public function contarPendientes($datos){
    	$sql = "SELECT
    				doc.fk_procesos,
    				COUNT(1) AS cantidad
    			FROM
    				documentos doc
    			WHERE
    				doc.fk_procesos IN ($datos[procesos])
    				AND doc.estado = 1
    			GROUP BY
    				doc.fk_procesos";
    	$db = new database();
       	return $db->ejecutarConsulta($sql);
    }
}