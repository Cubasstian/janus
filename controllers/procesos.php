<?php
require_once "libs/baseCrud.php";
require_once "solicitudes.php";

class procesos extends baseCrud{
    protected $tabla = 'procesos';

    public function crear($datos){
        //Primero debo verificar si tiene un proceso activo
        $hoy = date('Y-m-d');
        $sql = "SELECT
                    1
                FROM
                    procesos pro
                WHERE
                    pro.contratista = $datos[contratista]
                    AND ('$hoy' < pro.fecha_fin OR pro.fecha_fin is null)";
        $db = new database();
        $respuesta = $db->ejecutarConsulta($sql);
        if(count($respuesta['data']) >= 1){
            return [
                'ejecuto' => false,
                'mensajeError' => 'La persona tiene un proceso activo'
            ];
        }        

        //Luego la ingreso
        $datos['fecha_modificacion'] = date("Y-m-d H:i:s");
        $resultado = parent::insert([
            'info' => $datos
        ]);
        
        //Se crea solicitud en caso de crear bn el proceso contractual
        if($resultado['ejecuto']){
            //Crear solicitud
            $objSolicitudes = new solicitudes();
            $respuesta = $objSolicitudes->crear([
                'fk_procesos' => $resultado['insertId'],
                'estado' => 1,
                'fecha_modificacion' => $datos['fecha_modificacion']
            ]);            
            if($respuesta['ejecuto']){
                return $resultado;
            }
        }else{
            return $resultado;
        }
    }

    public function getProcesos($datos){
        $filtro = 0;
        switch ($datos['criterio']) {
            case 'id':
                $filtro = "pro.id = ".$datos['id'];
                break;
            case 'ps':
                $filtro = "pro.contratista = ".$_SESSION['usuario']['id'];
                break;
            case 'todos':
                $filtro = 1;
                break;
            default:
                $filtro = 0;
                break;
        }
        $sql = "SELECT
                    pro.id,
                    ger.nombre AS gerencia,
                    DATE(pro.fecha_creacion) AS fc,
                    pro.estado
                FROM
                    procesos pro INNER JOIN (necesidades nec INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id) ON pro.fk_necesidades = nec.id
                WHERE                   
                    $filtro";
        $db = new database();
        return $db->ejecutarConsulta($sql);
    }

    public function getFR($datos){
        $sql = "SELECT
                    pro.id,
                    pro.consecutivo_fr,
                    pro.consecutivo_ip,
                    pro.solped,
                    dep.ceco,
                    nec.id AS idNecesidad,
                    nec.pacc,
                    nec.definicion_tecnica,
                    nec.grado,
                    nec.nivel,
                    nec.justificacion,
                    nec.objeto,
                    nec.alcance,
                    pro.plazo,
                    pro.forma_pago
                FROM
                    (dependencias dep INNER JOIN necesidades nec ON dep.id = nec.fk_dependencias) INNER JOIN (procesos pro INNER JOIN solicitudes sol ON pro.id = sol.fk_procesos) ON nec.id = pro.fk_necesidades
                WHERE                   
                    pro.id = $datos[id]";
        $db = new database();
        return $db->ejecutarConsulta($sql);
    }
}