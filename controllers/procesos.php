<?php
require_once "libs/baseCrud.php";
require_once "solicitudes.php";
require_once "documentos.php";

class procesos extends baseCrud{
    protected $tabla = 'procesos';

    // Cargar mapa de prerequisitos (documentos por acción)
    private function _getPrerequisitos(){
        static $cache = null; if($cache!==null) return $cache;
        $cfg = __DIR__.'/../config/prerequisitos.php';
        if(file_exists($cfg)){
            $tmp = include $cfg; if(is_array($tmp)) $cache = $tmp; else $cache = [];
        }else{ $cache = []; }
        return $cache;
    }

        // Cache estados y flujo para reducir lecturas de disco
        private function _getEstadosCfg(){
            static $cache = null; if($cache!==null) return $cache;
            $cfg = __DIR__.'/../config/estados.php';
            $cache = (file_exists($cfg) ? include $cfg : []);
            return $cache;
        }
        private function _getFlujoCfg(){
            static $cache = null; if($cache!==null) return $cache;
            $cfg = __DIR__.'/../config/flujo.php';
            $cache = (file_exists($cfg) ? include $cfg : []);
            return $cache;
        }

    // Validar si documentos requeridos para accion están presentes y aceptados (estado=2)
    private function _validarPrerequisitos($accion, $idProceso){
        $map = $this->_getPrerequisitos();
        if(!isset($map[$accion]) || empty($map[$accion])) return [ 'ok' => true ];
        $requeridos = $map[$accion];
        $db = new database();
        // Obtener contratista del proceso
        $rsP = $db->ejecutarPreparado("SELECT contratista FROM procesos WHERE id = ? LIMIT 1", 'i', [ (int)$idProceso ]);
        if(!$rsP['ejecuto'] || !count($rsP['data'])) return [ 'ok' => false, 'mensaje' => 'No se encontró el proceso para validar prerequisitos' ];
        $contratista = (int)$rsP['data'][0]['contratista'];
        if($contratista<=0) return [ 'ok' => false, 'mensaje' => 'Proceso sin contratista; no se pueden validar prerequisitos' ];
        // Consulta documentos existentes coincidiendo por nombre exacto de tipo
        $place = implode(',', array_fill(0, count($requeridos), '?'));
        $types = str_repeat('s', count($requeridos)).'ii';
        $params = $requeridos; $params[] = $contratista; $params[] = (int)$idProceso;
        $sql = "SELECT dt.nombre, d.estado FROM documentos d INNER JOIN documentos_tipo dt ON d.fk_documentos_tipo = dt.id WHERE dt.nombre IN ($place) AND d.contratista = ? AND d.fk_procesos = ?";
        $rs = $db->ejecutarPreparado($sql, $types, $params);
        $encontrados = []; if($rs['ejecuto']){ foreach($rs['data'] as $r){ $encontrados[$r['nombre']] = (int)$r['estado']; } }
        $faltantes = []; $pendientes = [];
        foreach($requeridos as $req){
            if(!isset($encontrados[$req])){ $faltantes[] = $req; continue; }
            if($encontrados[$req] !== 2){ $pendientes[] = $req; }
        }
        if($faltantes || $pendientes){
            $msg = [];
            if($faltantes) $msg[] = 'Faltan: '.implode(', ', $faltantes);
            if($pendientes) $msg[] = 'Pendientes aprobación: '.implode(', ', $pendientes);
            return [ 'ok' => false, 'mensaje' => 'Prerequisitos no cumplidos -> '.implode(' | ', $msg) ];
        }
        return [ 'ok' => true ];
    }

    // Helper: verifica existencia de columnas en una tabla
    private function _columnsExist($tabla, $columnas){
        $db = new database();
        $placeholders = implode(',', array_fill(0, count($columnas), '?'));
        $types = str_repeat('s', count($columnas) + 1);
        $params = array_merge([ $tabla ], $columnas);
        $sql = "SELECT COLUMN_NAME FROM information_schema.COLUMNS\n                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME IN ($placeholders)";
        $rs = $db->ejecutarPreparado($sql, $types, $params);
        if(!$rs['ejecuto']) return [ 'ok' => false, 'faltantes' => $columnas ];
        $presentes = [];
        foreach($rs['data'] as $r){ $presentes[$r['COLUMN_NAME']] = true; }
        $faltantes = [];
        foreach($columnas as $c){ if(!isset($presentes[$c])) $faltantes[] = $c; }
        return [ 'ok' => count($faltantes) === 0, 'faltantes' => $faltantes ];
    }

    // Resolver id de tipo de documento por nombre (e.g., 'Minuta')
    private function _getDocumentoTipoId($nombre){
        $db = new database();
        $sql = "SELECT id FROM documentos_tipo WHERE nombre = ? LIMIT 1";
        $rs = $db->ejecutarPreparado($sql, 's', [ (string)$nombre ]);
        if($rs['ejecuto'] && isset($rs['data'][0]['id'])){
            return (int)$rs['data'][0]['id'];
        }
        return 0; // 0 indica no encontrado
    }

    // Helper: permisos por acción (por ahora solo Administrador)
    private function _verificarPermiso($accion){
        $rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : null;
        // Intentar cargar configuración externa de permisos
        $permisos = null;
        $cfg = __DIR__ . '/../config/permisos.php';
        if(file_exists($cfg)){
            $tmp = include $cfg;
            if(is_array($tmp)){ $permisos = $tmp; }
        }
        // Fallback por defecto
        if(!$permisos){
            $permisos = [
                'evaluarEEP' => ['Administrador'],
                'validarPerfil' => ['Administrador'],
                'recogerPerfil' => ['Administrador'],
                'minuta' => ['Administrador','Juridica'],
                'numerar' => ['Administrador','Juridica'],
                'afiliarARL' => ['Administrador','GAE'],
                'expedirRP' => ['Administrador','Financiera'],
                'recogerRP' => ['Administrador','Financiera'],
                'designarSupervisor' => ['Administrador','GAE'],
                'actaInicio' => ['Administrador','Juridica']
            ];
        }
        if(!$rol || !isset($permisos[$accion]) || !in_array($rol, $permisos[$accion])){
            return [ 'ok' => false, 'respuesta' => [ 'ejecuto' => false, 'mensajeError' => 'Permisos insuficientes para realizar esta acción' ] ];
        }
        return [ 'ok' => true ];
    }

    // Helper: obtiene idSolicitud y estado actual
    private function _getSolicitudInfo($idProceso){
        $db = new database();
        $sql = "SELECT sol.id, sol.estado FROM solicitudes sol WHERE sol.fk_procesos = ? ORDER BY sol.id DESC LIMIT 1";
        $rs = $db->ejecutarPreparado($sql, 'i', [ (int)$idProceso ]);
        if(!$rs['ejecuto'] || count($rs['data']) === 0){
            return [ 'ok' => false];
        }
        return [ 'ok' => true, 'idSolicitud' => (int)$rs['data'][0]['id'], 'estado' => (int)$rs['data'][0]['estado'] ];
    }

    private function _validarTransicion($idProceso, $estadoEsperado){
        $info = $this->_getSolicitudInfo($idProceso);
        if(!$info['ok']){
            return [ 'ok' => false, 'respuesta' => [ 'ejecuto' => false, 'mensajeError' => 'No se encontró la solicitud asociada al proceso' ] ];
        }
        if($info['estado'] != $estadoEsperado){
            return [ 'ok' => false, 'respuesta' => [ 'ejecuto' => false, 'mensajeError' => 'Transición inválida. Estado actual: '.$info['estado'].', esperado: '.$estadoEsperado ] ];
        }
        return [ 'ok' => true, 'info' => $info ];
    }

    public function getProcesoDetalle($datos){
        $idProceso = (int)$datos['id'];
        $db = new database();
        // Incluir datos del contratista (usuario asociado)
        $sql = "SELECT pro.*, sol.id AS idSolicitud, sol.estado, u.nombre AS contratista_nombre, u.cedula AS contratista_cedula
                FROM procesos pro
                INNER JOIN solicitudes sol ON pro.id = sol.fk_procesos
                LEFT JOIN usuarios u ON pro.contratista = u.id
                WHERE pro.id = ?";
        return $db->ejecutarPreparado($sql, 'i', [ $idProceso ]);
    }

    public function crear($datos){
        //Primero debo verificar si tiene un proceso activo
        $hoy = date('Y-m-d');
        $db = new database();
        $sql = "SELECT 1 FROM procesos pro WHERE pro.contratista = ? AND (? < pro.fecha_fin OR pro.fecha_fin IS NULL)";
        $respuesta = $db->ejecutarPreparado($sql, 'is', [ (int)$datos['contratista'], $hoy ]);
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

    // Función de debug para verificar datos de procesos
    public function debugProcesos($datos){
        $db = new database();
        
        // Verificar usuarios PS
        $sqlUsuarios = "SELECT id, nombre, rol FROM usuarios WHERE rol = 'PS' LIMIT 10";
        $usuarios = $db->ejecutarConsulta($sqlUsuarios);
        
        // Verificar todos los procesos con contratista
        $sqlProcesos = "SELECT id, contratista, estado, fecha_creacion, fk_necesidades FROM procesos ORDER BY id DESC LIMIT 20";
        $procesos = $db->ejecutarConsulta($sqlProcesos);
        
        // Verificar específicamente el proceso 12
        $sqlProceso12 = "SELECT pro.*, nec.id as necesidad_id, dep.id as dependencia_id, ger.id as gerencia_id, ger.nombre as gerencia_nombre 
                         FROM procesos pro 
                         LEFT JOIN necesidades nec ON pro.fk_necesidades = nec.id
                         LEFT JOIN dependencias dep ON nec.fk_dependencias = dep.id
                         LEFT JOIN gerencias ger ON dep.fk_gerencias = ger.id
                         WHERE pro.id = 12";
        $proceso12 = $db->ejecutarConsulta($sqlProceso12);
        
        // Verificar estructura de tablas
        $sqlTablas = "SHOW TABLES";
        $tablas = $db->ejecutarConsulta($sqlTablas);
        
        return [
            'ejecuto' => true,
            'usuarios_ps' => $usuarios,
            'procesos' => $procesos,
            'proceso_12_detalle' => $proceso12,
            'tablas' => $tablas,
            'debug_info' => 'Debug específico para proceso 12'
        ];
    }

    // Función específica para debug de usuario
    public function debugUsuarioProcesos($datos){
        $userId = isset($datos['userId']) ? (int)$datos['userId'] : 0;
        
        if ($userId <= 0) {
            return ['ejecuto' => false, 'mensajeError' => 'ID de usuario requerido'];
        }
        
        $db = new database();
        
        // Verificar el usuario específico
        $sqlUsuario = "SELECT * FROM usuarios WHERE id = ?";
        $usuario = $db->ejecutarPreparado($sqlUsuario, 'i', [$userId]);
        
        // Buscar todos los procesos del usuario con query simple
        $sqlProcesosSimple = "SELECT * FROM procesos WHERE contratista = ?";
        $procesosSimple = $db->ejecutarPreparado($sqlProcesosSimple, 'i', [$userId]);
        
        // Buscar con JOIN completo
        $sqlProcesosJoin = "SELECT pro.id, pro.contratista, pro.estado, pro.fecha_creacion,
                                   ger.nombre AS gerencia, 
                                   nec.id as necesidad_id, 
                                   dep.id as dependencia_id
                            FROM procesos pro
                            LEFT JOIN necesidades nec ON pro.fk_necesidades = nec.id
                            LEFT JOIN dependencias dep ON nec.fk_dependencias = dep.id
                            LEFT JOIN gerencias ger ON dep.fk_gerencias = ger.id
                            WHERE pro.contratista = ?";
        $procesosJoin = $db->ejecutarPreparado($sqlProcesosJoin, 'i', [$userId]);
        
        // Verificar proceso 12 específicamente
        $sqlProceso12 = "SELECT * FROM procesos WHERE id = 12";
        $proceso12 = $db->ejecutarConsulta($sqlProceso12);
        
        return [
            'ejecuto' => true,
            'usuario' => $usuario,
            'procesos_simple' => $procesosSimple,
            'procesos_join' => $procesosJoin,
            'proceso_12' => $proceso12,
            'user_id_buscado' => $userId
        ];
    }

    // Función simple para test
    public function getProcesosSimple($datos){
        $userId = isset($datos['userId']) ? (int)$datos['userId'] : 0;
        
        if ($userId <= 0) {
            return ['ejecuto' => false, 'mensajeError' => 'ID de usuario requerido'];
        }
        
        $db = new database();
        
        // Consulta súper simple sin JOINs
        $sql = "SELECT id, contratista, estado, fecha_creacion FROM procesos WHERE contratista = ?";
        
        error_log("getProcesosSimple SQL: " . $sql);
        error_log("getProcesosSimple userId: " . $userId);
        
        $result = $db->ejecutarPreparado($sql, 'i', [$userId]);
        
        error_log("getProcesosSimple resultado: " . json_encode($result));
        
        return $result;
    }
    public function crearProcesoPrueba($datos){
        $userId = isset($datos['userId']) ? (int)$datos['userId'] : 0;
        if ($userId <= 0) {
            return ['ejecuto' => false, 'mensajeError' => 'ID de usuario requerido'];
        }
        
        $db = new database();
        
        // Verificar si el usuario existe y es PS
        $sqlUser = "SELECT id, rol FROM usuarios WHERE id = ? AND rol = 'PS'";
        $user = $db->ejecutarPreparado($sqlUser, 'i', [$userId]);
        
        if (!$user['ejecuto'] || empty($user['data'])) {
            return ['ejecuto' => false, 'mensajeError' => 'Usuario PS no encontrado'];
        }
        
        // Crear un proceso de prueba
        $sqlInsert = "INSERT INTO procesos (contratista, estado, fecha_creacion, fecha_modificacion) VALUES (?, 'Activo', NOW(), NOW())";
        $result = $db->ejecutarPreparado($sqlInsert, 'i', [$userId]);
        
        return [
            'ejecuto' => $result['ejecuto'],
            'mensaje' => $result['ejecuto'] ? 'Proceso de prueba creado' : 'Error al crear proceso',
            'insertId' => $result['insertId'] ?? null
        ];
    }

    public function getProcesos($datos){
        $where = '0=1'; $types = ''; $params = [];
        
        // Debug logging
        error_log("getProcesos llamado con datos: " . json_encode($datos));
        
        switch ($datos['criterio']) {
            case 'id':
                $where = 'pro.id = ?'; $types = 'i'; $params[] = (int)$datos['id']; break;
            case 'ps':
                // Para PS, usar el ID del usuario pasado en los datos
                $userId = isset($datos['userId']) ? (int)$datos['userId'] : 0;
                if ($userId > 0) {
                    $where = 'pro.contratista = ?'; $types = 'i'; $params[] = $userId;
                    error_log("Buscando procesos para usuario PS con ID: " . $userId);
                } else {
                    // Fallback: intentar usar sesión si está disponible
                    $sessionUserId = isset($_SESSION['usuario']['id']) ? (int)$_SESSION['usuario']['id'] : 0;
                    if ($sessionUserId > 0) {
                        $where = 'pro.contratista = ?'; $types = 'i'; $params[] = $sessionUserId;
                        error_log("Usando ID de sesión para PS: " . $sessionUserId);
                    } else {
                        $where = '0=1'; // No hay ID válido, no devolver nada
                        error_log("No se encontró ID válido para usuario PS");
                    }
                }
                break;
            case 'todos':
                $where = '1=1'; break;
            default:
                $where = '0=1'; break;
        }
        
        // Usar consulta más simple primero
        $sql = "SELECT pro.id, 
                       COALESCE(ger.nombre, 'Sin gerencia') AS gerencia, 
                       DATE(pro.fecha_creacion) AS fc, 
                       COALESCE(pro.estado, 'Sin estado') AS estado, 
                       pro.contratista
                FROM procesos pro
                LEFT JOIN necesidades nec ON pro.fk_necesidades = nec.id
                LEFT JOIN dependencias dep ON nec.fk_dependencias = dep.id
                LEFT JOIN gerencias ger ON dep.fk_gerencias = ger.id
                WHERE $where
                ORDER BY pro.id DESC";
        
        error_log("SQL ejecutado: " . $sql);
        error_log("Parámetros: " . json_encode($params));
        error_log("Types: " . $types);
        
        $db = new database();
        $result = $db->ejecutarPreparado($sql, $types, $params);
        
        error_log("Resultado getProcesos ejecuto: " . ($result['ejecuto'] ? 'true' : 'false'));
        error_log("Resultado getProcesos data count: " . (isset($result['data']) ? count($result['data']) : 'null'));
        error_log("Resultado getProcesos completo: " . json_encode($result));
        
        // Asegurar que siempre devolvemos un formato consistente
        if (!isset($result['ejecuto'])) {
            $result['ejecuto'] = false;
        }
        
        if (!isset($result['data'])) {
            $result['data'] = [];
        }
        
        // Si ejecuto es true pero data no es array, corregir
        if ($result['ejecuto'] && !is_array($result['data'])) {
            error_log("PROBLEMA: ejecuto es true pero data no es array. Corrigiendo...");
            $result['data'] = [];
        }
        
        return $result;
    }

    // Procesos pendientes de validación de perfil (estado solicitud = 7, sin perfil_validado aún)
    public function getProcesosValidacionPerfil($datos){
        $limit = isset($datos['limit']) ? (int)$datos['limit'] : 200; if($limit<=0 || $limit>500) $limit = 200;
        $db = new database();
        // Detectar columnas de validación de perfil
        $colsReq = ['perfil_validado','fecha_validacion_perfil','observaciones_perfil'];
        $colsPresent = [];
        try{
            $place = implode(',', array_fill(0,count($colsReq),'?'));
            $types = str_repeat('s', count($colsReq)+1);
            $params = array_merge(['procesos'],$colsReq);
            // IMPORTANTE: pasar $cerrar = false para mantener la conexión abierta para la siguiente consulta
            $chk = $db->ejecutarPreparado(
                "SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME IN ($place)",
                $types,
                $params,
                false // no cerrar aún
            );
            if($chk['ejecuto']){ foreach($chk['data'] as $r){ $colsPresent[$r['COLUMN_NAME']] = true; } }
        }catch(\Exception $e){ }
        $extra = '';
        foreach($colsReq as $c){ if(isset($colsPresent[$c])){ $extra .= ", pro.$c"; } }
    $sql = "SELECT pro.id, ger.nombre AS gerencia, DATE(pro.fecha_creacion) AS fc, sol.estado AS estado_solicitud, u.nombre AS contratista_nombre, u.cedula AS contratista_cedula$extra
        FROM procesos pro
        INNER JOIN solicitudes sol ON sol.fk_procesos = pro.id
        INNER JOIN (necesidades nec INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id)
            ON pro.fk_necesidades = nec.id
        LEFT JOIN usuarios u ON pro.contratista = u.id
    WHERE sol.estado = 8
        ORDER BY pro.id DESC
        LIMIT $limit";
        $res = $db->ejecutarConsulta($sql);
        $faltantes = array_values(array_diff($colsReq, array_keys($colsPresent)));
        if(!empty($faltantes)){ $res['faltanCamposPerfil'] = $faltantes; }
        return $res;
    }

    public function getFR($datos){
        $id = (int)$datos['id'];
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
                    pro.forma_pago,
                    sol.estado AS estado
                FROM
                    (dependencias dep INNER JOIN necesidades nec ON dep.id = nec.fk_dependencias) INNER JOIN (procesos pro INNER JOIN solicitudes sol ON pro.id = sol.fk_procesos) ON nec.id = pro.fk_necesidades
                WHERE                   
                    pro.id = ?";
        $db = new database();
        return $db->ejecutarPreparado($sql, 'i', [ $id ]);
    }

    public function numerar($datos){
        // Permisos
        $perm = $this->_verificarPermiso(__FUNCTION__); if(!$perm['ok']) return $perm['respuesta'];
        // Prerequisitos
        $pre = $this->_validarPrerequisitos(__FUNCTION__, (int)$datos['idProceso']); if(!$pre['ok']) return [ 'ejecuto'=>false, 'mensajeError'=>$pre['mensaje'] ];
        // Espera: idProceso, numero_contrato, fecha_numeracion (opcional)
        $idProceso = (int)$datos['idProceso'];
        $numero = $datos['numero_contrato'];
        $fecha = isset($datos['fecha_numeracion']) && $datos['fecha_numeracion'] !== '' ? $datos['fecha_numeracion'] : null;

    // Validar estado actual debe ser 11 (Númerar contrato tras Minuta ajustada por corrimiento)
    $vt = $this->_validarTransicion($idProceso, 11);
        if(!$vt['ok']) return $vt['respuesta'];

        // Evitar re-numeración
        $detalle = $this->getProcesoDetalle(['id'=>$idProceso]);
        if($detalle['ejecuto'] && count($detalle['data']) && $detalle['data'][0]['numero_contrato'] != ''){
            return [ 'ejecuto' => false, 'mensajeError' => 'El proceso ya tiene número de contrato' ];
        }

        // 1) Actualizar proceso con número de contrato y fecha de numeración
        $infoP = [
            'info' => [
                'numero_contrato' => $numero,
                'fecha_numeracion' => $fecha,
                'fecha_modificacion' => date("Y-m-d H:i:s")
            ],
            'id' => $idProceso
        ];
        $idSolicitud = $vt['info']['idSolicitud'];

        // 3) Avanzar estado de la solicitud (después de numerar contrato)
        // Nuevo flujo: Numerar contrato (11) -> Solicitud de afiliación (12)
        $infoS = [
            'info' => [
                'estado' => 12,
                'fecha_modificacion' => date("Y-m-d H:i:s")
            ],
            'id' => $idSolicitud
        ];

        // 4) Usar setProceso para actualizar proceso y solicitud en orden
        $objSolicitudes = new solicitudes();
        return $objSolicitudes->setProceso(['infoP' => $infoP, 'infoS' => $infoS]);
    }

    // Nuevo paso intermedio: registrar la solicitud formal de afiliación (estado 12) antes de ejecutar Afiliar ARL (13)
    public function solicitudAfiliacion($datos){
        $perm = $this->_verificarPermiso(__FUNCTION__); if(!$perm['ok']) return $perm['respuesta'];
        // Validar prerequisitos (ej: minuta firmada / contrato numerado) definidos en config/prerequisitos.php
        $pre = $this->_validarPrerequisitos(__FUNCTION__, (int)$datos['idProceso']); if(!$pre['ok']) return [ 'ejecuto'=>false, 'mensajeError'=>$pre['mensaje'] ];
        $idProceso = (int)$datos['idProceso'];
        $fecha = isset($datos['fecha_solicitud_afiliacion']) && $datos['fecha_solicitud_afiliacion']!=='' ? $datos['fecha_solicitud_afiliacion'] : null;
        $observ = isset($datos['observaciones_solicitud_afiliacion']) ? $datos['observaciones_solicitud_afiliacion'] : null;

        // Estado actual debe ser 12 (Solicitud de afiliación) proveniente de numerar()
        $vt = $this->_validarTransicion($idProceso, 12);
        if(!$vt['ok']) return $vt['respuesta'];

        // Evitar re-ejecución si ya existe la fecha
        $detalle = $this->getProcesoDetalle(['id'=>$idProceso]);
        if($detalle['ejecuto'] && count($detalle['data']) && !empty($detalle['data'][0]['fecha_solicitud_afiliacion'])){
            return [ 'ejecuto'=>false, 'mensajeError'=>'La solicitud de afiliación ya fue registrada' ];
        }

        $payload = [ 'fecha_modificacion'=>date('Y-m-d H:i:s') ];
        if($fecha) $payload['fecha_solicitud_afiliacion'] = $fecha;
        if($observ!==null) $payload['observaciones_solicitud_afiliacion'] = $observ;
        $infoP = [ 'info'=>$payload, 'id'=>$idProceso ];
        $idSolicitud = $vt['info']['idSolicitud'];
        // Avanza a 13 (Afiliar ARL)
        $infoS = [ 'info'=>[ 'estado'=>13, 'fecha_modificacion'=>date('Y-m-d H:i:s') ], 'id'=>$idSolicitud ];
        $objSolicitudes = new solicitudes();
        return $objSolicitudes->setProceso(['infoP'=>$infoP,'infoS'=>$infoS]);
    }

    // Endpoint utilitario para que la UI pueda consultar qué prerequisitos faltan antes de intentar la acción
    public function checkPrerequisitos($datos){
        $accion = isset($datos['accion']) ? $datos['accion'] : '';
        $idProceso = isset($datos['idProceso']) ? (int)$datos['idProceso'] : 0;
        if($accion==='') return [ 'ejecuto'=>false, 'mensajeError'=>'Falta parámetro accion' ];
        if($idProceso<=0) return [ 'ejecuto'=>false, 'mensajeError'=>'Falta parámetro idProceso' ];
        $map = $this->_getPrerequisitos();
        $req = isset($map[$accion]) ? $map[$accion] : [];
        if(!$req) return [ 'ejecuto'=>true, 'faltantes'=>[], 'pendientes'=>[], 'mensaje'=>'Sin prerequisitos configurados' ];
        // Reusar lógica interna: obtenemos detalle comparando documentos aceptados
        $tmp = $this->_validarPrerequisitos($accion, $idProceso);
        if($tmp['ok']) return [ 'ejecuto'=>true, 'faltantes'=>[], 'pendientes'=>[], 'mensaje'=>'Cumplidos' ];
        // Parseamos mensaje para separar faltantes / pendientes si posible
        $faltantes = [];$pendientes=[];
        if(isset($tmp['mensaje'])){
            if(preg_match('/Faltan: ([^|]+)/',$tmp['mensaje'],$m)){ $faltantes = array_map('trim', explode(',', str_replace('Prerequisitos no cumplidos ->','', $m[1]))); }
            if(preg_match('/Pendientes aprobación: (.+)$/',$tmp['mensaje'],$m2)){ $pendientes = array_map('trim', explode(',', $m2[1])); }
        }
        return [ 'ejecuto'=>true, 'faltantes'=>$faltantes, 'pendientes'=>$pendientes, 'mensaje'=>'No cumplidos' ];
    }

    public function designarSupervisor($datos){
        // Permisos
        $perm = $this->_verificarPermiso(__FUNCTION__); if(!$perm['ok']) return $perm['respuesta'];
        $pre = $this->_validarPrerequisitos(__FUNCTION__, (int)$datos['idProceso']); if(!$pre['ok']) return [ 'ejecuto'=>false, 'mensajeError'=>$pre['mensaje'] ];
        // Espera: idProceso, fk_supervisor, fecha_supervisor (opcional)
        $idProceso = (int)$datos['idProceso'];
        $supervisor = (int)$datos['fk_supervisor'];
        $fecha = isset($datos['fecha_supervisor']) && $datos['fecha_supervisor'] !== '' ? $datos['fecha_supervisor'] : null;

        // Validar estado actual debe ser 15
        $vt = $this->_validarTransicion($idProceso, 15);
        if(!$vt['ok']) return $vt['respuesta'];

        // Evitar re-asignación
        $detalle = $this->getProcesoDetalle(['id'=>$idProceso]);
        if($detalle['ejecuto'] && count($detalle['data']) && $detalle['data'][0]['fk_supervisor'] != ''){
            return [ 'ejecuto' => false, 'mensajeError' => 'El proceso ya tiene supervisor asignado' ];
        }

        $infoP = [
            'info' => [
                'fk_supervisor' => $supervisor,
                'fecha_supervisor' => $fecha,
                'fecha_modificacion' => date("Y-m-d H:i:s")
            ],
            'id' => $idProceso
        ];

        $idSolicitud = $vt['info']['idSolicitud'];

        // Avanzar al siguiente estado después de "Designar supervisor" (15 -> 16)
        $infoS = [
            'info' => [
                'estado' => 16,
                'fecha_modificacion' => date("Y-m-d H:i:s")
            ],
            'id' => $idSolicitud
        ];

        $objSolicitudes = new solicitudes();
        return $objSolicitudes->setProceso(['infoP' => $infoP, 'infoS' => $infoS]);
    }

    public function validarPerfil($datos){
        // Permisos
        $perm = $this->_verificarPermiso(__FUNCTION__); if(!$perm['ok']) return $perm['respuesta'];
        $pre = $this->_validarPrerequisitos(__FUNCTION__, (int)$datos['idProceso']); if(!$pre['ok']) return [ 'ejecuto'=>false, 'mensajeError'=>$pre['mensaje'] ];
        // Asegurar columnas necesarias
        $check = $this->_columnsExist('procesos', ['perfil_validado','fecha_validacion_perfil','observaciones_perfil']);
        if(!$check['ok']){
            return [ 'ejecuto'=>false, 'mensajeError'=>'Faltan columnas para validación de perfil: '.implode(', ',$check['faltantes']), 'sqlSugerido'=>'ALTER TABLE procesos ADD perfil_validado TINYINT(1) NULL, ADD fecha_validacion_perfil DATE NULL, ADD observaciones_perfil TEXT NULL;' ];
        }
        // Espera: idProceso, perfil_validado (0/1), fecha_validacion_perfil (opcional), observaciones_perfil (opcional)
        $idProceso = (int)$datos['idProceso'];
        $validado = isset($datos['perfil_validado']) && $datos['perfil_validado'] !== '' ? (int)$datos['perfil_validado'] : null;
        $fecha = isset($datos['fecha_validacion_perfil']) && $datos['fecha_validacion_perfil'] !== '' ? $datos['fecha_validacion_perfil'] : null;
        $observ = isset($datos['observaciones_perfil']) ? $datos['observaciones_perfil'] : null;

        // Evitar re-ejecución
        $detalle = $this->getProcesoDetalle(['id'=>$idProceso]);
        if($detalle['ejecuto'] && count($detalle['data'])){
            $row = $detalle['data'][0];
            if($row['perfil_validado'] !== null && $row['perfil_validado'] !== ''){
                return [ 'ejecuto' => false, 'mensajeError' => 'La validación de perfil ya fue registrada' ];
            }
        }

        // Construir payload para actualizar proceso solo con campos provistos
        $payload = [];
        if($validado !== null){ $payload['perfil_validado'] = $validado; }
        if($fecha !== null){ $payload['fecha_validacion_perfil'] = $fecha; }
        if($observ !== null){ $payload['observaciones_perfil'] = $observ; }
        $payload['fecha_modificacion'] = date("Y-m-d H:i:s");

        $infoP = [ 'info' => $payload, 'id' => $idProceso ];

    // Validar estado actual debe ser 8 (después de Examen preocupacional)
    $vt = $this->_validarTransicion($idProceso, 8);
        if(!$vt['ok']) return $vt['respuesta'];
        $idSolicitud = $vt['info']['idSolicitud'];

        // Flujo: 8 (Validación perfil) -> 9 (Recoger validación)
        $infoS = [
            'info' => [
                'estado' => 9,
                'fecha_modificacion' => date("Y-m-d H:i:s")
            ],
            'id' => $idSolicitud
        ];

        $objSolicitudes = new solicitudes();
        return $objSolicitudes->setProceso(['infoP' => $infoP, 'infoS' => $infoS]);
    }

    public function recogerPerfil($datos){
        // Permisos
        $perm = $this->_verificarPermiso(__FUNCTION__); if(!$perm['ok']) return $perm['respuesta'];
        // Verificar que existan las columnas necesarias para este paso
        $check = $this->_columnsExist('procesos', ['fecha_recoger_perfil','observaciones_recoger_perfil']);
        if(!$check['ok']){
            return [
                'ejecuto' => false,
                'mensajeError' => 'Faltan columnas en la tabla procesos: '.implode(', ', $check['faltantes']),
                'sqlSugerido' => 'ALTER TABLE procesos ADD fecha_recoger_perfil DATE NULL, ADD observaciones_recoger_perfil TEXT NULL;'
            ];
        }
        // Espera: idProceso, fecha_recoger_perfil (requerida por la vista), observaciones_recoger_perfil (opcional)
        $idProceso = (int)$datos['idProceso'];
        $fecha = isset($datos['fecha_recoger_perfil']) && $datos['fecha_recoger_perfil'] !== '' ? $datos['fecha_recoger_perfil'] : null;
        $observ = isset($datos['observaciones_recoger_perfil']) ? $datos['observaciones_recoger_perfil'] : null;

        // Evitar re-ejecución
        $detalle = $this->getProcesoDetalle(['id'=>$idProceso]);
        if($detalle['ejecuto'] && count($detalle['data'])){
            $row = $detalle['data'][0];
            if(isset($row['fecha_recoger_perfil']) && $row['fecha_recoger_perfil']){
                return [ 'ejecuto' => false, 'mensajeError' => 'La recepción de validación ya fue registrada' ];
            }
        }

        $payload = [];
        if($fecha !== null){ $payload['fecha_recoger_perfil'] = $fecha; }
        if($observ !== null){ $payload['observaciones_recoger_perfil'] = $observ; }
        $payload['fecha_modificacion'] = date("Y-m-d H:i:s");

        $infoP = [ 'info' => $payload, 'id' => $idProceso ];

    // Validar estado actual debe ser 9
    $vt = $this->_validarTransicion($idProceso, 9);
        if(!$vt['ok']) return $vt['respuesta'];
        $idSolicitud = $vt['info']['idSolicitud'];

        // Avanza de estado 9 (Recoger validación perfil) a 10 (Minuta)
        $infoS = [
            'info' => [
                'estado' => 10,
                'fecha_modificacion' => date("Y-m-d H:i:s")
            ],
            'id' => $idSolicitud
        ];

        $objSolicitudes = new solicitudes();
        return $objSolicitudes->setProceso(['infoP' => $infoP, 'infoS' => $infoS]);
    }

    // Listado de procesos en estado 8 (pendientes de 'recoger validación de perfil')
    public function getProcesosRecogerPerfil($datos){
        $limit = isset($datos['limit']) ? (int)$datos['limit'] : 200; if($limit<=0 || $limit>500) $limit = 200;
        $db = new database();
        // Detectar columnas relevantes (incluye validación y recoger)
        $colsReq = ['perfil_validado','fecha_validacion_perfil','observaciones_perfil','fecha_recoger_perfil','observaciones_recoger_perfil'];
        $colsPresent = [];
        try{
            $place = implode(',', array_fill(0,count($colsReq),'?'));
            $types = str_repeat('s', count($colsReq)+1);
            $params = array_merge(['procesos'],$colsReq);
            $chk = $db->ejecutarPreparado(
                "SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME IN ($place)",
                $types,
                $params,
                false
            );
            if($chk['ejecuto']){ foreach($chk['data'] as $r){ $colsPresent[$r['COLUMN_NAME']] = true; } }
        }catch(\Exception $e){ }
        $extra = '';
        foreach($colsReq as $c){ if(isset($colsPresent[$c])) $extra .= ", pro.$c"; }
        $sql = "SELECT pro.id, ger.nombre AS gerencia, DATE(pro.fecha_creacion) AS fc, sol.estado AS estado_solicitud, u.nombre AS contratista_nombre, u.cedula AS contratista_cedula$extra
                FROM procesos pro
                INNER JOIN solicitudes sol ON sol.fk_procesos = pro.id
                INNER JOIN (necesidades nec INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id)
                    ON pro.fk_necesidades = nec.id
                LEFT JOIN usuarios u ON pro.contratista = u.id
                WHERE sol.estado = 9
                ORDER BY pro.id DESC
                LIMIT $limit";
        $res = $db->ejecutarConsulta($sql);
        $faltantes = array_values(array_diff($colsReq, array_keys($colsPresent)));
        if(!empty($faltantes)){ $res['faltanCamposRecoger'] = $faltantes; }
        return $res;
    }

    public function minuta($datos){
        // Permisos
        $perm = $this->_verificarPermiso(__FUNCTION__); if(!$perm['ok']) return $perm['respuesta'];
        $pre = $this->_validarPrerequisitos(__FUNCTION__, (int)$datos['idProceso']); if(!$pre['ok']) return [ 'ejecuto'=>false, 'mensajeError'=>$pre['mensaje'] ];
        // Espera: idProceso, fecha_minuta (opcional), observaciones_minuta (opcional)
        $idProceso = (int)$datos['idProceso'];
        $fecha = isset($datos['fecha_minuta']) && $datos['fecha_minuta'] !== '' ? $datos['fecha_minuta'] : null;
        $observ = isset($datos['observaciones_minuta']) ? $datos['observaciones_minuta'] : null;

        // Evitar re-ejecución
        $detalle = $this->getProcesoDetalle(['id'=>$idProceso]);
        if($detalle['ejecuto'] && count($detalle['data'])){
            $row = $detalle['data'][0];
            if($row['fecha_minuta'] || $row['observaciones_minuta']){
                return [ 'ejecuto' => false, 'mensajeError' => 'La minuta ya fue registrada' ];
            }
        }

        $payload = [];
        if($fecha !== null){ $payload['fecha_minuta'] = $fecha; }
        if($observ !== null){ $payload['observaciones_minuta'] = $observ; }
        $payload['fecha_modificacion'] = date("Y-m-d H:i:s");

        $infoP = [ 'info' => $payload, 'id' => $idProceso ];

    // Validar estado actual debe ser 10 (Minuta)
    $vt = $this->_validarTransicion($idProceso, 10);
        if(!$vt['ok']) return $vt['respuesta'];
        $idSolicitud = $vt['info']['idSolicitud'];

        // Avanzar al siguiente estado después de "Minuta" (10 -> 11 Numerar contrato)
        $infoS = [
            'info' => [
                'estado' => 11,
                'fecha_modificacion' => date("Y-m-d H:i:s")
            ],
            'id' => $idSolicitud
        ];

        $objSolicitudes = new solicitudes();
        return $objSolicitudes->setProceso(['infoP' => $infoP, 'infoS' => $infoS]);
    }

    public function actaInicio($datos){
        // Permisos
        $perm = $this->_verificarPermiso(__FUNCTION__); if(!$perm['ok']) return $perm['respuesta'];
        $pre = $this->_validarPrerequisitos(__FUNCTION__, (int)$datos['idProceso']); if(!$pre['ok']) return [ 'ejecuto'=>false, 'mensajeError'=>$pre['mensaje'] ];
        // Espera: idProceso, fecha_acta_inicio (recomendado), numero_acta_inicio (opcional), observaciones_acta_inicio (opcional)
        $idProceso = (int)$datos['idProceso'];
        $fecha = isset($datos['fecha_acta_inicio']) && $datos['fecha_acta_inicio'] !== '' ? $datos['fecha_acta_inicio'] : null;
        $numero = isset($datos['numero_acta_inicio']) ? $datos['numero_acta_inicio'] : null;
        $observ = isset($datos['observaciones_acta_inicio']) ? $datos['observaciones_acta_inicio'] : null;

        // Evitar re-ejecución
        $detalle = $this->getProcesoDetalle(['id'=>$idProceso]);
        if($detalle['ejecuto'] && count($detalle['data'])){
            $row = $detalle['data'][0];
            if($row['fecha_acta_inicio']){
                return [ 'ejecuto' => false, 'mensajeError' => 'El acta de inicio ya fue registrada' ];
            }
        }

        $payload = [];
        if($fecha !== null){ $payload['fecha_acta_inicio'] = $fecha; }
        if($numero !== null){ $payload['numero_acta_inicio'] = $numero; }
        if($observ !== null){ $payload['observaciones_acta_inicio'] = $observ; }
        $payload['fecha_modificacion'] = date("Y-m-d H:i:s");

        $infoP = [ 'info' => $payload, 'id' => $idProceso ];

        // Validar estado actual debe ser 16
        $vt = $this->_validarTransicion($idProceso, 16);
        if(!$vt['ok']) return $vt['respuesta'];
        $idSolicitud = $vt['info']['idSolicitud'];

        // Avanzar al siguiente estado después de "Acta de inicio" (16 -> 17 Contratado)
        $infoS = [
            'info' => [
                'estado' => 17,
                'fecha_modificacion' => date("Y-m-d H:i:s")
            ],
            'id' => $idSolicitud
        ];

        $objSolicitudes = new solicitudes();
        return $objSolicitudes->setProceso(['infoP' => $infoP, 'infoS' => $infoS]);
    }

    public function expedirRP($datos){
        // Permisos
        $perm = $this->_verificarPermiso(__FUNCTION__); if(!$perm['ok']) return $perm['respuesta'];
        $pre = $this->_validarPrerequisitos(__FUNCTION__, (int)$datos['idProceso']); if(!$pre['ok']) return [ 'ejecuto'=>false, 'mensajeError'=>$pre['mensaje'] ];
        // Espera: idProceso, numero_rp, fecha_rp, observaciones_rp (opcional)
        $idProceso = (int)$datos['idProceso'];
        $numero = isset($datos['numero_rp']) ? $datos['numero_rp'] : null;
        $fecha = isset($datos['fecha_rp']) && $datos['fecha_rp'] !== '' ? $datos['fecha_rp'] : null;
        $observ = isset($datos['observaciones_rp']) ? $datos['observaciones_rp'] : null;

        // Evitar re-ejecución
        $detalle = $this->getProcesoDetalle(['id'=>$idProceso]);
        if($detalle['ejecuto'] && count($detalle['data'])){
            $row = $detalle['data'][0];
            if($row['numero_rp']){
                return [ 'ejecuto' => false, 'mensajeError' => 'El RP ya fue expedido' ];
            }
        }

        $payload = [];
        if($numero !== null){ $payload['numero_rp'] = $numero; }
        if($fecha !== null){ $payload['fecha_rp'] = $fecha; }
        if($observ !== null){ $payload['observaciones_rp'] = $observ; }
        $payload['fecha_modificacion'] = date("Y-m-d H:i:s");

        $infoP = [ 'info' => $payload, 'id' => $idProceso ];

    // Validar estado actual debe ser 13 (después de Afiliar ARL)
    $vt = $this->_validarTransicion($idProceso, 13);
        if(!$vt['ok']) return $vt['respuesta'];
        $idSolicitud = $vt['info']['idSolicitud'];

        // Avanza estado: 13 (Afiliar ARL) -> 14 (Expedir RP)
        $infoS = [
            'info' => [
                'estado' => 14,
                'fecha_modificacion' => date("Y-m-d H:i:s")
            ],
            'id' => $idSolicitud
        ];

        $objSolicitudes = new solicitudes();
        return $objSolicitudes->setProceso(['infoP' => $infoP, 'infoS' => $infoS]);
    }

    public function recogerRP($datos){
        // Permisos
        $perm = $this->_verificarPermiso(__FUNCTION__); if(!$perm['ok']) return $perm['respuesta'];
        // Espera: idProceso, fecha_recoger_rp, observaciones_recoger_rp (opcional)
        $idProceso = (int)$datos['idProceso'];
        $fecha = isset($datos['fecha_recoger_rp']) && $datos['fecha_recoger_rp'] !== '' ? $datos['fecha_recoger_rp'] : null;
        $observ = isset($datos['observaciones_recoger_rp']) ? $datos['observaciones_recoger_rp'] : null;

        // Evitar re-ejecución
        $detalle = $this->getProcesoDetalle(['id'=>$idProceso]);
        if($detalle['ejecuto'] && count($detalle['data'])){
            $row = $detalle['data'][0];
            if($row['fecha_recoger_rp']){
                return [ 'ejecuto' => false, 'mensajeError' => 'La recepción del RP ya fue registrada' ];
            }
        }

        $payload = [];
        if($fecha !== null){ $payload['fecha_recoger_rp'] = $fecha; }
        if($observ !== null){ $payload['observaciones_recoger_rp'] = $observ; }
        $payload['fecha_modificacion'] = date("Y-m-d H:i:s");

        $infoP = [ 'info' => $payload, 'id' => $idProceso ];

    // Validar estado actual debe ser 14
    $vt = $this->_validarTransicion($idProceso, 14);
        if(!$vt['ok']) return $vt['respuesta'];
        $idSolicitud = $vt['info']['idSolicitud'];

        // Avanza estado: 14 (Expedir RP) -> 15 (Recoger RP)
        $infoS = [
            'info' => [
                'estado' => 15,
                'fecha_modificacion' => date("Y-m-d H:i:s")
            ],
            'id' => $idSolicitud
        ];

        $objSolicitudes = new solicitudes();
        return $objSolicitudes->setProceso(['infoP' => $infoP, 'infoS' => $infoS]);
    }

    // Genera el PDF del Acta de inicio, lo registra en 'documentos' y guarda en /documentos/{id}.pdf
    public function generarActaInicioPDF($datos){
        // Permisos: usar permiso de actaInicio
        $perm = $this->_verificarPermiso('actaInicio'); if(!$perm['ok']) return $perm['respuesta'];
        $idProceso = isset($datos['idProceso']) ? (int)$datos['idProceso'] : 0;
        if($idProceso <= 0){ return [ 'ejecuto' => false, 'mensajeError' => 'Parámetro idProceso inválido' ]; }

        // Validar proceso y datos del acta
        $detalle = $this->getProcesoDetalle(['id' => $idProceso]);
        if(!$detalle['ejecuto'] || count($detalle['data']) === 0){
            return [ 'ejecuto' => false, 'mensajeError' => 'No se encontró el proceso' ];
        }
        $rowPro = $detalle['data'][0];
        if(empty($rowPro['fecha_acta_inicio'])){
            return [ 'ejecuto' => false, 'mensajeError' => 'Primero registre el Acta de inicio (fecha obligatoria) antes de generar el PDF' ];
        }

        // FR opcional (para objeto/alcance/ceco)
        $fr = $this->getFR(['id' => $idProceso]);
        $rowFR = ($fr['ejecuto'] && count($fr['data'])) ? $fr['data'][0] : [];

        // Contratista
        $idContratista = isset($rowPro['contratista']) ? (int)$rowPro['contratista'] : 0;
        if($idContratista <= 0){
            return [ 'ejecuto' => false, 'mensajeError' => 'Proceso sin contratista asociado' ];
        }
        $db = new database();
        $rsU = $db->ejecutarPreparado("SELECT nombre, cedula FROM usuarios WHERE id = ?", 'i', [ $idContratista ]);
        if(!$rsU['ejecuto'] || count($rsU['data']) === 0){
            return [ 'ejecuto' => false, 'mensajeError' => 'No se encontró información del contratista' ];
        }
        $contratistaNombre = $rsU['data'][0]['nombre'];
        $contratistaCedula = $rsU['data'][0]['cedula'];

        // Supervisor (opcional)
        $supervisor = '';
        if(!empty($rowPro['fk_supervisor'])){
            $rsSup = $db->ejecutarPreparado("SELECT nombre FROM usuarios WHERE id = ?", 'i', [ (int)$rowPro['fk_supervisor'] ]);
            if($rsSup['ejecuto'] && count($rsSup['data'])){ $supervisor = $rsSup['data'][0]['nombre']; }
        }

        // Crear documento tipo 'Acta de inicio'
        $tipoId = $this->_getDocumentoTipoId('Acta de inicio');
        if($tipoId === 0){
            // Intentar variante de capitalización
            $tipoId = $this->_getDocumentoTipoId('Acta de Inicio');
        }
        if($tipoId === 0){
            return [ 'ejecuto' => false, 'mensajeError' => "No existe el tipo de documento 'Acta de inicio' en la tabla documentos_tipo" ];
        }
        $objDocs = new documentos();
        $resDoc = $objDocs->crear([ 'contratista' => $idContratista, 'proceso' => $idProceso, 'tipo' => $tipoId ]);
        if(!$resDoc['ejecuto']){ return $resDoc; }
        $idDocumento = (int)$resDoc['insertId'];

        // Ruta de archivo
        $dir = __DIR__."/../documentos";
        if(!is_dir($dir)){@mkdir($dir, 0777, true);}        
        $ruta = $dir."/".$idDocumento.".pdf";

        // PDF con helper estándar
        require_once __DIR__."/../libs/pdf_emcali.php";
        $pdf = new \PDF_Emcali();
        $pdf->title = 'Acta de inicio';
        $logoPath = __DIR__."/../img/logoEmcali.png";
        if(file_exists($logoPath)){ $pdf->logoPath = $logoPath; }
        if(isset($_SESSION['usuario']['login'])){ $pdf->showUserInFooter = true; $pdf->userLabel = (string)$_SESSION['usuario']['login']; }
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','',11);
        // Sello de Aprobado (configurable)
        $cfgPdf = [];
        $cfgPath = __DIR__ . '/../config/pdf.php';
        if(file_exists($cfgPath)){
            $tmp = include $cfgPath;
            if(is_array($tmp)) $cfgPdf = $tmp;
        }
        $estadoActual = isset($rowPro['estado']) ? (int)$rowPro['estado'] : 0;
        $minEstado = isset($cfgPdf['stamps']['acta_aprobado_min_estado']) ? (int)$cfgPdf['stamps']['acta_aprobado_min_estado'] : 16;
        if($estadoActual >= $minEstado){
            $box = isset($cfgPdf['stamps']['box']) ? $cfgPdf['stamps']['box'] : ['x'=>135,'y'=>20,'w'=>65,'h'=>18];
            $color = isset($cfgPdf['stamps']['color']) ? $cfgPdf['stamps']['color'] : ['r'=>0,'g'=>128,'b'=>0];
            $tcolor = isset($cfgPdf['stamps']['textColor']) ? $cfgPdf['stamps']['textColor'] : ['r'=>0,'g'=>100,'b'=>0];
            $title = isset($cfgPdf['stamps']['title']) ? (string)$cfgPdf['stamps']['title'] : 'APROBADO';
            $pdf->SetDrawColor($color['r'], $color['g'], $color['b']);
            $pdf->SetTextColor($tcolor['r'], $tcolor['g'], $tcolor['b']);
            $pdf->Rect($box['x'], $box['y'], $box['w'], $box['h']);
            $pdf->SetFont('Arial','B',12);
            $pdf->SetXY($box['x'], $box['y']+2);
            $pdf->Cell($box['w'],6,utf8_decode($title),0,2,'C');
            $pdf->SetFont('Arial','',8);
            $usr = isset($_SESSION['usuario']['login']) ? (string)$_SESSION['usuario']['login'] : '';
            $pdf->Cell($box['w'],4,utf8_decode('Fecha: ').date('Y-m-d'),0,2,'C');
            if($usr!=='') $pdf->Cell($box['w'],4,utf8_decode('Usuario: ').$usr,0,2,'C');
            $pdf->SetTextColor(0,0,0);
        }

        // Encabezado de datos
        $lineas = [
            'Proceso: #'.$idProceso,
            'Contratista: '.utf8_decode($contratistaNombre).' - C.C. '.$contratistaCedula,
            'No. Contrato: '.(isset($rowPro['numero_contrato']) ? $rowPro['numero_contrato'] : ''),
            'No. Acta: '.(isset($rowPro['numero_acta_inicio']) ? $rowPro['numero_acta_inicio'] : ''),
            'Fecha de acta: '.(isset($rowPro['fecha_acta_inicio']) ? $rowPro['fecha_acta_inicio'] : ''),
            'Gerencia/CECO: '.(isset($rowFR['ceco']) ? $rowFR['ceco'] : ''),
            'RP: '.(isset($rowPro['numero_rp']) && $rowPro['numero_rp'] ? $rowPro['numero_rp'] : ''),
        ];
        foreach($lineas as $l){ $pdf->Cell(0,7,$l,0,1); }
        $pdf->Ln(2);

        // Objeto (si FR disponible)
        if(!empty($rowFR['objeto'])){
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(0,8,utf8_decode('Objeto contractual'),0,1);
            $pdf->SetFont('Arial','',11);
            $this->_multiCellUtf8($pdf, 0, 6, $rowFR['objeto']);
            $pdf->Ln(2);
        }
        // Supervisor (si existe)
        if(!empty($supervisor)){
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(0,8,utf8_decode('Supervisor designado'),0,1);
            $pdf->SetFont('Arial','',11);
            $this->_multiCellUtf8($pdf, 0, 6, $supervisor);
            $pdf->Ln(2);
        }
        // Observaciones del acta
        if(!empty($rowPro['observaciones_acta_inicio'])){
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(0,8,utf8_decode('Observaciones'),0,1);
            $pdf->SetFont('Arial','',11);
            $this->_multiCellUtf8($pdf, 0, 6, $rowPro['observaciones_acta_inicio']);
        }

        // Firmas: Contratista y Supervisor (si hay)
        $pdf->Ln(12);
        $colW = 90; $rowH = 8;
        $pdf->SetFont('Arial','',10);
        $pdf->Cell($colW, $rowH, '', 'T', 0, 'C');
        $pdf->Cell(10, $rowH, '');
        $pdf->Cell($colW, $rowH, '', 'T', 1, 'C');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($colW, 6, utf8_decode($contratistaNombre.' - C.C. '.$contratistaCedula), 0, 0, 'C');
        $pdf->Cell(10, 6, '');
        $pdf->Cell($colW, 6, utf8_decode(!empty($supervisor) ? $supervisor : 'Supervisor designado'), 0, 1, 'C');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell($colW, 5, utf8_decode('Contratista'), 0, 0, 'C');
        $pdf->Cell(10, 5, '');
        $pdf->Cell($colW, 5, utf8_decode('Supervisor'), 0, 1, 'C');

        // Guardar en disco
        try{ $pdf->Output('F', $ruta); }
        catch(\Exception $e){ return [ 'ejecuto' => false, 'mensajeError' => 'No fue posible guardar el PDF: '.$e->getMessage() ]; }

        return [ 'ejecuto' => true, 'idDocumento' => $idDocumento ];
    }

    public function afiliarARL($datos){
        // Permisos
        $perm = $this->_verificarPermiso(__FUNCTION__); if(!$perm['ok']) return $perm['respuesta'];
        $pre = $this->_validarPrerequisitos(__FUNCTION__, (int)$datos['idProceso']); if(!$pre['ok']) return [ 'ejecuto'=>false, 'mensajeError'=>$pre['mensaje'] ];
        // Espera: idProceso, fecha_arl, observaciones_arl (opcional)
        $idProceso = (int)$datos['idProceso'];
        $fecha = isset($datos['fecha_arl']) && $datos['fecha_arl'] !== '' ? $datos['fecha_arl'] : null;
        $observ = isset($datos['observaciones_arl']) ? $datos['observaciones_arl'] : null;

        // Evitar re-ejecución
        $detalle = $this->getProcesoDetalle(['id'=>$idProceso]);
        if($detalle['ejecuto'] && count($detalle['data'])){
            $row = $detalle['data'][0];
            if($row['fecha_arl']){
                return [ 'ejecuto' => false, 'mensajeError' => 'La afiliación ARL ya fue registrada' ];
            }
        }

        $payload = [];
        if($fecha !== null){ $payload['fecha_arl'] = $fecha; }
        if($observ !== null){ $payload['observaciones_arl'] = $observ; }
        $payload['fecha_modificacion'] = date("Y-m-d H:i:s");

        $infoP = [ 'info' => $payload, 'id' => $idProceso ];

    // Validar estado actual debe ser 12 (Solicitud de afiliación)
    $vt = $this->_validarTransicion($idProceso, 12);
        if(!$vt['ok']) return $vt['respuesta'];
        $idSolicitud = $vt['info']['idSolicitud'];

        // Avanzar al siguiente estado después de "Afiliar ARL" (13)
        $infoS = [
            'info' => [
                'estado' => 13,
                'fecha_modificacion' => date("Y-m-d H:i:s")
            ],
            'id' => $idSolicitud
        ];

        $objSolicitudes = new solicitudes();
        return $objSolicitudes->setProceso(['infoP' => $infoP, 'infoS' => $infoS]);
    }

    public function evaluarEEP($datos){
        // Permisos
        $perm = $this->_verificarPermiso(__FUNCTION__); if(!$perm['ok']) return $perm['respuesta'];
        // Verificar que las columnas necesarias existan
        $check = $this->_columnsExist('procesos', ['fecha_eep','resultado_eep','observaciones_eep']);
        if(!$check['ok']){
            return [ 'ejecuto' => false, 'mensajeError' => 'Faltan columnas en la tabla procesos: '.implode(', ', $check['faltantes']).'. Por favor ejecute la migración SQL para EEP.' ];
        }
        // Espera: idProceso, fecha_eep, resultado_eep (aprobado/no), observaciones_eep (opcional)
        $idProceso = (int)$datos['idProceso'];
        $fecha = isset($datos['fecha_eep']) && $datos['fecha_eep'] !== '' ? $datos['fecha_eep'] : null;
        $resultado = isset($datos['resultado_eep']) ? $datos['resultado_eep'] : null; // texto/código
        $observ = isset($datos['observaciones_eep']) ? $datos['observaciones_eep'] : null;

    // Nuevo flujo: CIIP ocupa estado 6; Examen preocupacional ahora estado 7
    $vt = $this->_validarTransicion($idProceso, 7);
        if(!$vt['ok']) return $vt['respuesta'];
        $idSolicitud = $vt['info']['idSolicitud'];

        // Evitar re-ejecución
        $detalle = $this->getProcesoDetalle(['id'=>$idProceso]);
        if($detalle['ejecuto'] && count($detalle['data'])){
            $row = $detalle['data'][0];
            if(isset($row['resultado_eep']) && $row['resultado_eep'] !== null && $row['resultado_eep'] !== ''){
                return [ 'ejecuto' => false, 'mensajeError' => 'El examen ya fue evaluado' ];
            }
        }

        $payload = [];
        if($fecha !== null){ $payload['fecha_eep'] = $fecha; }
        if($resultado !== null){ $payload['resultado_eep'] = $resultado; }
        if($observ !== null){ $payload['observaciones_eep'] = $observ; }
        $payload['fecha_modificacion'] = date("Y-m-d H:i:s");

        $infoP = [ 'info' => $payload, 'id' => $idProceso ];
        // Avanza a 8 (Validación perfil) por corrimiento de índices
        $infoS = [
            'info' => [ 'estado' => 8, 'fecha_modificacion' => date("Y-m-d H:i:s") ],
            'id' => $idSolicitud
        ];
        $objSolicitudes = new solicitudes();
        return $objSolicitudes->setProceso(['infoP'=>$infoP,'infoS'=>$infoS]);
    }

    // Nuevo paso: CIIP (estado 6) previo al Examen preocupacional (7)
    public function ciip($datos){
        $perm = $this->_verificarPermiso(__FUNCTION__); if(!$perm['ok']) return $perm['respuesta'];
        // Asegurar columnas
        $check = $this->_columnsExist('procesos', ['fecha_ciip','resultado_ciip','observaciones_ciip']);
        if(!$check['ok']){
            return [ 'ejecuto'=>false, 'mensajeError'=>'Faltan columnas CIIP: '.implode(', ',$check['faltantes']), 'sqlSugerido'=>'ALTER TABLE procesos ADD fecha_ciip DATE NULL, ADD resultado_ciip VARCHAR(100) NULL, ADD observaciones_ciip TEXT NULL;' ];
        }
        $idProceso = (int)$datos['idProceso'];
        $fecha = isset($datos['fecha_ciip']) && $datos['fecha_ciip']!=='' ? $datos['fecha_ciip'] : null;
        $res = isset($datos['resultado_ciip']) ? $datos['resultado_ciip'] : null;
        $obs = isset($datos['observaciones_ciip']) ? $datos['observaciones_ciip'] : null;
        // Validar estado actual debe ser 6 (CIIP)
        $vt = $this->_validarTransicion($idProceso, 6);
        if(!$vt['ok']) return $vt['respuesta'];
        // Evitar duplicado
        $detalle = $this->getProcesoDetalle(['id'=>$idProceso]);
        if($detalle['ejecuto'] && count($detalle['data'])){
            $row = $detalle['data'][0];
            if(!empty($row['fecha_ciip']) || !empty($row['resultado_ciip'])){
                return [ 'ejecuto'=>false, 'mensajeError'=>'CIIP ya registrado' ];
            }
        }
        $payload = [ 'fecha_modificacion'=>date('Y-m-d H:i:s') ];
        if($fecha) $payload['fecha_ciip'] = $fecha;
        if($res!==null) $payload['resultado_ciip'] = $res;
        if($obs!==null) $payload['observaciones_ciip'] = $obs;
        $infoP = [ 'info'=>$payload, 'id'=>$idProceso ];
        $idSolicitud = $vt['info']['idSolicitud'];
        // Avanza a estado 7 (Examen preocupacional)
        $infoS = [ 'info'=>[ 'estado'=>7, 'fecha_modificacion'=>date('Y-m-d H:i:s') ], 'id'=>$idSolicitud ];
        $objSolicitudes = new solicitudes();
        return $objSolicitudes->setProceso(['infoP'=>$infoP,'infoS'=>$infoS]);
    }

    // Genera el PDF de Minuta, lo registra en 'documentos' y guarda en /documentos/{id}.pdf
    public function generarMinutaPDF($datos){
        // Permisos
        $perm = $this->_verificarPermiso('minuta'); if(!$perm['ok']) return $perm['respuesta'];
        $idProceso = isset($datos['idProceso']) ? (int)$datos['idProceso'] : 0;
        if($idProceso <= 0){ return [ 'ejecuto' => false, 'mensajeError' => 'Parámetro idProceso inválido' ]; }

        // Validar que exista el proceso y obtener detalle y FR
        $detalle = $this->getProcesoDetalle(['id' => $idProceso]);
        if(!$detalle['ejecuto'] || count($detalle['data']) === 0){
            return [ 'ejecuto' => false, 'mensajeError' => 'No se encontró el proceso' ];
        }
        $rowPro = $detalle['data'][0];

        $fr = $this->getFR(['id' => $idProceso]);
        if(!$fr['ejecuto'] || count($fr['data']) === 0){
            return [ 'ejecuto' => false, 'mensajeError' => 'No se encontró la Ficha de Requerimiento (FR) del proceso' ];
        }
        $rowFR = $fr['data'][0];

        // Obtener datos del contratista
        $idContratista = isset($rowPro['contratista']) ? (int)$rowPro['contratista'] : 0;
        if($idContratista <= 0){
            return [ 'ejecuto' => false, 'mensajeError' => 'Proceso sin contratista asociado' ];
        }
        $db = new database();
        $rsU = $db->ejecutarPreparado("SELECT nombre, cedula FROM usuarios WHERE id = ?", 'i', [ $idContratista ]);
        if(!$rsU['ejecuto'] || count($rsU['data']) === 0){
            return [ 'ejecuto' => false, 'mensajeError' => 'No se encontró información del contratista' ];
        }
        $contratistaNombre = $rsU['data'][0]['nombre'];
        $contratistaCedula = $rsU['data'][0]['cedula'];

        // Crear registro en documentos con tipo 'Minuta'
        $tipoMinutaId = $this->_getDocumentoTipoId('Minuta');
        if($tipoMinutaId === 0){
            return [ 'ejecuto' => false, 'mensajeError' => "No existe el tipo de documento 'Minuta' en la tabla documentos_tipo" ];
        }
        $objDocs = new documentos();
        $resDoc = $objDocs->crear([ 'contratista' => $idContratista, 'proceso' => $idProceso, 'tipo' => $tipoMinutaId ]);
        if(!$resDoc['ejecuto']){ return $resDoc; }
        $idDocumento = (int)$resDoc['insertId'];

        // Ruta de guardado
        $dir = __DIR__."/../documentos"; // controllers/../documentos
        if(!is_dir($dir)){
            @mkdir($dir, 0777, true);
        }
        $ruta = $dir."/".$idDocumento.".pdf";

        // Generar PDF con helper reutilizable
        require_once __DIR__."/../libs/pdf_emcali.php";
    $pdf = new \PDF_Emcali();
        $pdf->title = 'Minuta de contrato de prestación de servicios';
        // Intentar resolver ruta del logo: /img/logoEmcali.png
        $logoPath = __DIR__."/../img/logoEmcali.png";
        if(file_exists($logoPath)){
            $pdf->logoPath = $logoPath;
        }
        if(isset($_SESSION['usuario']['login'])){
            $pdf->showUserInFooter = true;
            $pdf->userLabel = (string)$_SESSION['usuario']['login'];
        }
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','',11);

        // Encabezado con datos principales
        $lineas = [
            'Proceso: #'.$idProceso,
            'Contratista: '.utf8_decode($contratistaNombre).' - C.C. '.$contratistaCedula,
            'Gerencia: '.(isset($rowFR['ceco']) ? $rowFR['ceco'] : ''),
            'Plazo: '.(isset($rowFR['plazo']) ? $rowFR['plazo'] : ''),
            'Forma de pago: '.(isset($rowFR['forma_pago']) ? utf8_decode($rowFR['forma_pago']) : ''),
        ];
        foreach($lineas as $l){ $pdf->Cell(0,7,$l,0,1); }
        $pdf->Ln(2);

        // Cuerpo (objeto y alcance de la FR)
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,8,utf8_decode('Objeto contractual'),0,1);
        $pdf->SetFont('Arial','',11);
        $objeto = isset($rowFR['objeto']) ? $rowFR['objeto'] : '';
        $this->_multiCellUtf8($pdf, 0, 6, $objeto);
        $pdf->Ln(2);

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,8,utf8_decode('Alcance'),0,1);
        $pdf->SetFont('Arial','',11);
        $alcance = isset($rowFR['alcance']) ? $rowFR['alcance'] : '';
        $this->_multiCellUtf8($pdf, 0, 6, $alcance);
        $pdf->Ln(2);

        // Observaciones de la minuta (si existen)
        if(!empty($rowPro['observaciones_minuta'])){
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(0,8,utf8_decode('Observaciones de la minuta'),0,1);
            $pdf->SetFont('Arial','',11);
            $this->_multiCellUtf8($pdf, 0, 6, $rowPro['observaciones_minuta']);
        }

        // Firmas
        $pdf->Ln(12);
        $colW = 90; $rowH = 8;
        $pdf->SetFont('Arial','',10);
        // Línea de firma contratista
        $pdf->Cell($colW, $rowH, '', 'T', 0, 'C');
        $pdf->Cell(10, $rowH, '');
        // Línea de firma EMCALI
        $pdf->Cell($colW, $rowH, '', 'T', 1, 'C');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($colW, 6, utf8_decode($contratistaNombre.' - C.C. '.$contratistaCedula), 0, 0, 'C');
        $pdf->Cell(10, 6, '');
        $pdf->Cell($colW, 6, utf8_decode('Por EMCALI EICE ESP'), 0, 1, 'C');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell($colW, 5, utf8_decode('Contratista'), 0, 0, 'C');
        $pdf->Cell(10, 5, '');
        $pdf->Cell($colW, 5, utf8_decode('Representante autorizado'), 0, 1, 'C');

        // (El pie de página con numeración se maneja en Footer())

        // Guardar en disco
        try{
            $pdf->Output('F', $ruta);
        }catch(\Exception $e){
            return [ 'ejecuto' => false, 'mensajeError' => 'No fue posible guardar el PDF: '.$e->getMessage() ];
        }

        return [ 'ejecuto' => true, 'idDocumento' => $idDocumento ];
    }

    // Helper interno para imprimir texto UTF-8 con MultiCell
    private function _multiCellUtf8($pdf, $w, $h, $txt){
        $pdf->MultiCell($w, $h, utf8_decode((string)$txt));
    }

    // Exponer mapa de estados (para front dinámico / comparaciones)
    public function getMapaEstados($datos){
        $est = $this->_getEstadosCfg();
        if(is_array($est) && $est) return ['ejecuto'=>true,'data'=>$est];
        return ['ejecuto'=>false,'mensajeError'=>'No se encontró configuración de estados'];
    }

    // Exponer definición de flujo (acciones y siguientes estados)
    public function getDefinicionFlujo($datos){
        $fl = $this->_getFlujoCfg();
        if(is_array($fl) && $fl) return ['ejecuto'=>true,'data'=>$fl];
        return ['ejecuto'=>false,'mensajeError'=>'No se encontró definición de flujo'];
    }

    // Diagnóstico: detectar procesos "atascados" que cumplen prerequisitos pero no han avanzado
    public function diagnosticoFlujo($datos){
        $db = new database();
        $sql = "SELECT pro.id, sol.estado FROM procesos pro INNER JOIN solicitudes sol ON pro.id = sol.fk_procesos";
        $rs = $db->ejecutarConsulta($sql);
        if(!$rs['ejecuto']) return $rs;
        $pendientes = [];
        $flujo = $this->_getFlujoCfg();
        foreach($rs['data'] as $row){
            $estado = (int)$row['estado'];
            // mapear acción esperada para el estado
            $accion = null;
            foreach($flujo as $f){ if($f['estado']===$estado){ $accion = $f['accion']; break; } }
            if(!$accion) continue; // estados iniciales o finales
            // Chequear prerequisitos; si ok -> marcar como potencial pendiente de ejecución
            $pre = $this->_validarPrerequisitos($accion, (int)$row['id']);
            if($pre['ok']){ $pendientes[] = ['idProceso'=>$row['id'],'estado'=>$estado,'accion'=>$accion,'observacion'=>'Prerequisitos completos pero sin transición']; }
        }
        return ['ejecuto'=>true,'data'=>$pendientes];
    }

    // Snapshot consolidado de un proceso: detalle, prerequisitos por acción, documentos clave y flujo
    public function snapshot($datos){
        $id = isset($datos['idProceso']) ? (int)$datos['idProceso'] : 0;
        if($id<=0) return ['ejecuto'=>false,'mensajeError'=>'idProceso requerido'];
        $detalle = $this->getProcesoDetalle(['id'=>$id]);
        if(!$detalle['ejecuto'] || !count($detalle['data'])) return ['ejecuto'=>false,'mensajeError'=>'Proceso no encontrado'];
        $row = $detalle['data'][0];
        $flujo = $this->_getFlujoCfg();
        $acciones = [];
        foreach($flujo as $f){
            $pre = $this->_validarPrerequisitos($f['accion'], $id);
            $acciones[] = [
                'accion'=>$f['accion'],
                'estado'=>$f['estado'],
                'siguiente'=>$f['siguiente'],
                'prerequisitos_ok'=>$pre['ok'],
                'mensaje_prereq'=> $pre['ok']? 'OK' : $pre['mensaje']
            ];
        }
        // Documentos clave presentes
        $db = new database();
        $docsClave = ['Minuta','Acta de inicio','Acta de Inicio','Contrato','RP','Solicitud de afiliación'];
        $place = implode(',', array_fill(0,count($docsClave),'?'));
        $types = str_repeat('s', count($docsClave)).'ii';
        $params = $docsClave; $params[] = isset($row['contratista'])?(int)$row['contratista']:0; $params[] = $id;
        $sqlDocs = "SELECT dt.nombre, d.id, d.estado FROM documentos d INNER JOIN documentos_tipo dt ON d.fk_documentos_tipo = dt.id WHERE dt.nombre IN ($place) AND d.contratista = ? AND d.fk_procesos = ?";
        $rsDocs = $db->ejecutarPreparado($sqlDocs, $types, $params);
        $docs = [];
        if($rsDocs['ejecuto']){
            foreach($rsDocs['data'] as $d){ $docs[$d['nombre']] = ['id'=>$d['id'],'estado'=>$d['estado']]; }
        }
        return [
            'ejecuto'=>true,
            'proceso'=>$row,
            'acciones'=>$acciones,
            'documentos'=>$docs
        ];
    }

    // Endpoint simple de salud
    public function ping($datos){ return ['ejecuto'=>true,'pong'=>time()]; }

    // FORZAR TRANSICION: Permite a Administrador mover una solicitud a cualquier estado del flujo ignorando prerequisitos.
    // Parámetros: idProceso, estadoDestino, motivo (string opcional pero recomendado)
    public function forceTransicion($datos){
        // Seguridad: solo Administrador
        $rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : null;
        if($rol !== 'Administrador'){
            return ['ejecuto'=>false,'mensajeError'=>'Solo un Administrador puede forzar transiciones'];
        }
        $idProceso = isset($datos['idProceso']) ? (int)$datos['idProceso'] : 0;
        $estadoDestino = isset($datos['estadoDestino']) ? (int)$datos['estadoDestino'] : 0;
        $motivo = isset($datos['motivo']) ? trim($datos['motivo']) : '';
        // Reglas de gobierno adicionales
        if(strlen($motivo) < 5){
            return ['ejecuto'=>false,'mensajeError'=>'Motivo requerido (mínimo 5 caracteres)'];
        }
        // Throttle simple en sesión: mínimo 10s entre overrides y máximo 5 por hora
        $now = time();
        if(!isset($_SESSION['forceTx'])){ $_SESSION['forceTx'] = ['last'=>0,'hist'=>[]]; }
        $infoThrottle = &$_SESSION['forceTx'];
        // Limpiar hist > 1h
        $h1 = $now - 3600; $nuevaHist = [];
        foreach($infoThrottle['hist'] as $ts){ if($ts >= $h1) $nuevaHist[] = $ts; }
        $infoThrottle['hist'] = $nuevaHist;
        if(($now - (int)$infoThrottle['last']) < 10){
            return ['ejecuto'=>false,'mensajeError'=>'Debe esperar al menos 10 segundos entre transiciones forzadas'];
        }
        if(count($infoThrottle['hist']) >= 5){
            return ['ejecuto'=>false,'mensajeError'=>'Límite de 5 transiciones forzadas por hora alcanzado'];
        }
        if($idProceso<=0 || $estadoDestino<=0){ return ['ejecuto'=>false,'mensajeError'=>'Parámetros inválidos']; }
        // Obtener info solicitud actual
        $info = $this->_getSolicitudInfo($idProceso);
        if(!$info['ok']) return ['ejecuto'=>false,'mensajeError'=>'No se encontró solicitud'];
        if($info['estado'] === $estadoDestino){ return ['ejecuto'=>false,'mensajeError'=>'La solicitud ya está en el estado destino']; }
        // Validar que el destino exista dentro del mapa de estados (config/estados.php si disponible)
        $cfgEstados = __DIR__.'/../config/estados.php'; $mapOk=true;
        if(file_exists($cfgEstados)){
            $est = include $cfgEstados; if(is_array($est)){ if(!isset($est[$estadoDestino])) $mapOk=false; }
        }
        if(!$mapOk){ return ['ejecuto'=>false,'mensajeError'=>'Estado destino no reconocido en configuración']; }
        // Registrar override en historico usando setEstado
        $objSolicitudes = new solicitudes();
        $payload = [ 'estado'=>$estadoDestino, 'override'=>true, 'motivo_override'=>$motivo, 'fecha_modificacion'=>date('Y-m-d H:i:s') ];
        $res = $objSolicitudes->setEstado([ 'id'=>$info['idSolicitud'], 'info'=>$payload ]);
        if($res['ejecuto']){
            $res['override'] = true;
            $res['estadoAnterior'] = $info['estado'];
            $res['estadoNuevo'] = $estadoDestino;
            // Actualizar throttle
            $infoThrottle['last'] = $now; $infoThrottle['hist'][] = $now;
            $res['throttle'] = [ 'usadosHora'=>count($infoThrottle['hist']), 'limiteHora'=>5, 'cooldownSegundos'=>10 ];
        }
        return $res;
    }

    // Estado del throttle / límites de overrides para el usuario actual (Administrador)
    public function forceTransicionStatus($datos){
        $rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : null;
        if($rol !== 'Administrador') return ['ejecuto'=>false,'mensajeError'=>'No autorizado'];
        $now = time(); $h1 = $now - 3600; $limite = 5; $cooldown = 10;
        if(!isset($_SESSION['forceTx'])){ $_SESSION['forceTx']=['last'=>0,'hist'=>[]]; }
        $hist = array_values(array_filter($_SESSION['forceTx']['hist'], function($ts) use ($h1){ return $ts >= $h1; }));
        $_SESSION['forceTx']['hist'] = $hist; // limpiar
        $restante = max(0, $limite - count($hist));
        $nextDisponible = max(0, $cooldown - ($now - (int)$_SESSION['forceTx']['last']));
        return [
            'ejecuto'=>true,
            'usadosUltimaHora'=>count($hist),
            'limiteHora'=>$limite,
            'puedeEjecutar'=> ($nextDisponible===0 && count($hist)<$limite),
            'segundosCooldownRestantes'=>$nextDisponible,
            'restantesHora'=>$restante
        ];
    }

    // METRICAS DEL FLUJO: Estadísticas agregadas de estados actuales y duraciones históricas promedio.
    // Parámetros opcionales: maxRegistros (limita análisis de historico, default 10000), incluirActualAbierto (1 para medir tiempo desde último cambio hasta ahora)
    public function metricsFlujo($datos){
        // Permiso: Administrador (extensible en permisos.php si se desea)
        $rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : null;
        if($rol !== 'Administrador') return ['ejecuto'=>false,'mensajeError'=>'No autorizado'];
        // Cache ligera 30s configurable con parámetro force
        static $cache = [];
        $incluirAbiertosParam = isset($datos['incluirActualAbierto']) ? (int)$datos['incluirActualAbierto']===1 : false; // para clave
        $maxRegTmp = isset($datos['maxRegistros']) ? (int)$datos['maxRegistros'] : 10000; if($maxRegTmp<=0||$maxRegTmp>50000) $maxRegTmp=10000;
        $ckey = ($incluirAbiertosParam?'ab':'nb').'-'.$maxRegTmp;
        $force = isset($datos['force']) && (int)$datos['force']===1;
        if(!$force && isset($cache[$ckey]) && (time()-$cache[$ckey]['ts'])<30){ return $cache[$ckey]['data']; }
        $db = new database();
        // 1) Snapshot actual por estado
        $sqlResumen = "SELECT estado, COUNT(1) cantidad, ROUND(AVG(DATEDIFF(NOW(), fecha_modificacion)),2) edad_promedio_dias FROM solicitudes GROUP BY estado ORDER BY estado";
        $resResumen = $db->ejecutarConsulta($sqlResumen);
        // 2) Duraciones históricas por estado (tiempo que permanecen antes de pasar al siguiente)
        $maxReg = $maxRegTmp;
        $sqlHist = "SELECT fk_solicitudes, informacion, fecha_creacion FROM solicitudes_historico ORDER BY fk_solicitudes, fecha_creacion LIMIT $maxReg";
        $resHist = $db->ejecutarConsulta($sqlHist);
        $duraciones = []; // estado => [totalSeg, ocurrencias]
        $ultimoPorSolicitud = []; // fk_solicitudes => [estado, ts]
        if($resHist['ejecuto']){
            foreach($resHist['data'] as $row){
                $fk = (int)$row['fk_solicitudes'];
                $estado = null; $infoJson = @json_decode($row['informacion'], true);
                if(is_array($infoJson) && isset($infoJson['estado'])){ $estado = (int)$infoJson['estado']; }
                $ts = strtotime($row['fecha_creacion']); if(!$ts) continue;
                if($estado === null){ continue; }
                if(isset($ultimoPorSolicitud[$fk])){
                    $prev = $ultimoPorSolicitud[$fk];
                    // Si el estado cambió medimos duración del estado previo
                    if($prev['estado'] !== $estado){
                        $delta = $ts - $prev['ts'];
                        if($delta>0){
                            if(!isset($duraciones[$prev['estado']])){ $duraciones[$prev['estado']] = ['seg'=>0,'n'=>0]; }
                            $duraciones[$prev['estado']]['seg'] += $delta; $duraciones[$prev['estado']]['n']++;
                        }
                    }
                }
                $ultimoPorSolicitud[$fk] = ['estado'=>$estado,'ts'=>$ts];
            }
        }
        $incluirAbiertos = $incluirAbiertosParam;
        if($incluirAbiertos){
            $ahora = time();
            foreach($ultimoPorSolicitud as $fk=>$info){
                $delta = $ahora - $info['ts']; if($delta<=0) continue;
                if(!isset($duraciones[$info['estado']])) $duraciones[$info['estado']] = ['seg'=>0,'n'=>0];
                $duraciones[$info['estado']]['seg'] += $delta; $duraciones[$info['estado']]['n']++;
            }
        }
        $promedios = [];
        foreach($duraciones as $estado=>$d){
            if($d['n']>0){
                $dias = $d['seg']/86400; $promedios[$estado] = ['dias_promedio'=>round($dias/$d['n'],3), 'muestras'=>$d['n']];
            }
        }
        $resp = [
            'ejecuto'=>true,
            'resumenEstados'=> $resResumen['ejecuto'] ? $resResumen['data'] : [],
            'duracionesPromedio'=> $promedios,
            'limitHistorial'=>$maxReg,
            'incluyeAbiertos'=>$incluirAbiertos
        ];
        $cache[$ckey] = ['ts'=>time(),'data'=>$resp];
        return $resp;
    }

    // AUDITORIA DE INTEGRIDAD: Detecta incoherencias comunes entre documentos, campos de proceso y estado de solicitud.
    public function auditoriaIntegridad($datos){
        $rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : null;
        if($rol !== 'Administrador') return ['ejecuto'=>false,'mensajeError'=>'No autorizado'];
        $db = new database();
        $issues = [ 'contrato_sin_numero_en_estado_avanzado'=>[], 'numero_contrato_en_estado_temprano'=>[], 'minuta_doc_en_estado_temprano'=>[], 'rp_en_estado_temprano'=>[], 'documentos_inconsistentes'=>[] ];
        // Solicitudes con estado >=11 pero proceso sin numero_contrato
        $sql1 = "SELECT pro.id, sol.estado FROM procesos pro INNER JOIN solicitudes sol ON pro.id = sol.fk_procesos WHERE sol.estado >= 11 AND (pro.numero_contrato IS NULL OR pro.numero_contrato='')";
        $r1 = $db->ejecutarConsulta($sql1); if($r1['ejecuto']) $issues['contrato_sin_numero_en_estado_avanzado'] = $r1['data'];
        // Numero de contrato presente pero estado < 11
        $sql2 = "SELECT pro.id, sol.estado, pro.numero_contrato FROM procesos pro INNER JOIN solicitudes sol ON pro.id = sol.fk_procesos WHERE sol.estado < 11 AND pro.numero_contrato IS NOT NULL AND pro.numero_contrato <> ''";
        $r2 = $db->ejecutarConsulta($sql2); if($r2['ejecuto']) $issues['numero_contrato_en_estado_temprano'] = $r2['data'];
        // Documento Minuta en estado < 10
        $sqlMin = "SELECT d.id, d.fk_procesos, sol.estado FROM documentos d INNER JOIN documentos_tipo dt ON d.fk_documentos_tipo = dt.id INNER JOIN solicitudes sol ON d.fk_procesos = sol.fk_procesos WHERE dt.nombre='Minuta' AND sol.estado < 10";
        $rMin = $db->ejecutarConsulta($sqlMin); if($rMin['ejecuto']) $issues['minuta_doc_en_estado_temprano'] = $rMin['data'];
        // RP (numero_rp) en estado < 14
        $sqlRp = "SELECT pro.id, sol.estado, pro.numero_rp FROM procesos pro INNER JOIN solicitudes sol ON pro.id = sol.fk_procesos WHERE sol.estado < 14 AND pro.numero_rp IS NOT NULL AND pro.numero_rp <> ''";
        $rRp = $db->ejecutarConsulta($sqlRp); if($rRp['ejecuto']) $issues['rp_en_estado_temprano'] = $rRp['data'];
        // Documentos con estados fuera de rango (estado documento no 0/1/2) - defensivo
        $sqlDoc = "SELECT d.id, d.fk_procesos, d.estado FROM documentos d WHERE d.estado NOT IN (0,1,2)";
        $rDoc = $db->ejecutarConsulta($sqlDoc); if($rDoc['ejecuto']) $issues['documentos_inconsistentes'] = $rDoc['data'];
        return ['ejecuto'=>true,'issues'=>$issues];
    }

    // Historico parseado del proceso (añade banderas override)
    public function getHistoricoProceso($datos){
        $idProceso = isset($datos['idProceso']) ? (int)$datos['idProceso'] : 0;
        if($idProceso<=0) return ['ejecuto'=>false,'mensajeError'=>'idProceso requerido'];
        $info = $this->_getSolicitudInfo($idProceso);
        if(!$info['ok']) return ['ejecuto'=>false,'mensajeError'=>'Solicitud asociada no encontrada'];
        require_once 'solicitudesHistorico.php';
        $hist = new solicitudesHistorico();
        $rs = $hist->getHistorico(['solicitud'=>$info['idSolicitud']]);
        if(!$rs['ejecuto']) return $rs;
        // Parse JSON informacion
        $parsed = [];
        foreach($rs['data'] as $row){
            $infoJson = @json_decode($row['informacion'], true);
            if(!is_array($infoJson)) $infoJson = [];
            $parsed[] = [
                'usuario'=>$row['nombre'],
                'fecha'=>$row['fecha_creacion'],
                'estado'=> isset($infoJson['estado']) ? (int)$infoJson['estado'] : null,
                'override'=> isset($infoJson['override']) ? (bool)$infoJson['override'] : false,
                'motivo_override'=> isset($infoJson['motivo_override']) ? $infoJson['motivo_override'] : null
            ];
        }
        return ['ejecuto'=>true,'data'=>$parsed];
    }

    // Exportar métricas a CSV
    public function exportMetricsFlujo($datos){
        $m = $this->metricsFlujo($datos);
        if(!$m['ejecuto']) return $m;
        $csv = "#Resumen estados\nestado,cantidad,edad_promedio_dias\n";
        foreach($m['resumenEstados'] as $r){ $csv .= $r['estado'].",".$r['cantidad'].",".$r['edad_promedio_dias']."\n"; }
        $csv .= "\n#Duraciones promedio\nestado,dias_promedio,muestras\n";
        foreach($m['duracionesPromedio'] as $estado=>$d){ $csv .= $estado.",".$d['dias_promedio'].",".$d['muestras']."\n"; }
        return ['ejecuto'=>true,'csv'=>base64_encode($csv)];
    }

    // Exportar auditoría a CSV
    public function exportAuditoriaIntegridad($datos){
        $a = $this->auditoriaIntegridad($datos);
        if(!$a['ejecuto']) return $a;
        $csv = "categoria,proceso,estado,detalle\n";
        foreach($a['issues'] as $cat=>$rows){
            foreach($rows as $r){
                $pid = isset($r['id']) ? $r['id'] : (isset($r['fk_procesos'])?$r['fk_procesos']:'');
                $est = isset($r['estado']) ? $r['estado'] : '';
                $csv .= str_replace(',', ' ', $cat).",".$pid.",".$est.",".str_replace(["\n","\r",","],' ' , json_encode($r))."\n";
            }
        }
        return ['ejecuto'=>true,'csv'=>base64_encode($csv)];
    }

    // Estadísticas de overrides (transiciones forzadas)
    public function overrideStats($datos){
        $rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : null;
        if($rol !== 'Administrador') return ['ejecuto'=>false,'mensajeError'=>'No autorizado'];
        $dias = isset($datos['dias']) ? (int)$datos['dias'] : 7; if($dias<=0) $dias=7; if($dias>60) $dias=60;
        $force = isset($datos['force']) ? (int)$datos['force']===1 : false;
        // Cache estático por ventana para reducir carga si se consulta frecuente
        static $cache = [];
        $ckey = 'd'.$dias;
        if(!$force && isset($cache[$ckey]) && (time() - $cache[$ckey]['ts']) < 30){ return $cache[$ckey]['data']; }
        $db = new database();
        // Overrides dentro de la ventana
        $sqlWin = "SELECT sh.informacion, sh.fecha_creacion, sh.creado_por, s.fk_procesos, u.nombre AS usuario FROM solicitudes_historico sh INNER JOIN solicitudes s ON sh.fk_solicitudes = s.id INNER JOIN usuarios u ON sh.creado_por = u.id WHERE sh.fecha_creacion >= DATE_SUB(NOW(), INTERVAL $dias DAY) AND sh.informacion LIKE '%\\\"override\\\":true%' ORDER BY sh.fecha_creacion";
        $stmt = $db->ejecutarConsulta($sqlWin);
        $rows = []; if($stmt['ejecuto']) $rows = $stmt['data'];
        $items = [];
        foreach($rows as $row){
            $infoJson = @json_decode($row['informacion'], true); if(!is_array($infoJson)) $infoJson=[];
            if(!(isset($infoJson['override']) && $infoJson['override'])) continue;
            $estado = isset($infoJson['estado'])? (int)$infoJson['estado'] : null;
            $items[] = [
                'fecha'=>$row['fecha_creacion'],
                'proceso'=>(int)$row['fk_procesos'],
                'estado'=>$estado,
                'usuario'=>$row['usuario'],
                'motivo'=> isset($infoJson['motivo_override'])?$infoJson['motivo_override']:null
            ];
        }
        $totalVentana = count($items);
        $lim24 = time()-86400; $total24=0; $porDia=[]; $porEstado=[]; $porUsuario=[]; $ultimo=null;
        foreach($items as $it){
            $ts = strtotime($it['fecha']); if(!$ts) continue;
            if($ts >= $lim24) $total24++;
            $d = date('Y-m-d',$ts); if(!isset($porDia[$d])) $porDia[$d]=0; $porDia[$d]++;
            if($it['estado']!==null){ if(!isset($porEstado[$it['estado']])) $porEstado[$it['estado']]=0; $porEstado[$it['estado']]++; }
            if($it['usuario']){ if(!isset($porUsuario[$it['usuario']])) $porUsuario[$it['usuario']]=0; $porUsuario[$it['usuario']]++; }
            $ultimo = $it;
        }
        ksort($porDia); ksort($porEstado); arsort($porUsuario);
        // Total histórico de overrides
        $sqlAll = "SELECT COUNT(1) total FROM solicitudes_historico WHERE informacion LIKE '%\\\"override\\\":true%'";
        $rAll = $db->ejecutarConsulta($sqlAll); $totalAll = ($rAll['ejecuto'] && isset($rAll['data'][0]['total']))?(int)$rAll['data'][0]['total']:null;
        // Total de transiciones (cambios de estado) en la ventana para ratio (incluye overrides y normales)
        $sqlTransWin = "SELECT COUNT(1) c FROM solicitudes_historico WHERE fecha_creacion >= DATE_SUB(NOW(), INTERVAL $dias DAY) AND informacion LIKE '%\\\"estado\\\":%'";
        $rTransWin = $db->ejecutarConsulta($sqlTransWin); $totalTransWin = ($rTransWin['ejecuto'] && isset($rTransWin['data'][0]['c']))?(int)$rTransWin['data'][0]['c']:0;
        $ratioVentana = ($totalTransWin>0)? round($totalVentana / $totalTransWin,4) : null;
        // Total transiciones últimas 24h
        $sqlTrans24 = "SELECT COUNT(1) c FROM solicitudes_historico WHERE fecha_creacion >= DATE_SUB(NOW(), INTERVAL 1 DAY) AND informacion LIKE '%\\\"estado\\\":%'";
        $rTrans24 = $db->ejecutarConsulta($sqlTrans24); $totalTrans24 = ($rTrans24['ejecuto'] && isset($rTrans24['data'][0]['c']))?(int)$rTrans24['data'][0]['c']:0;
        $ratio24 = ($totalTrans24>0)? round($total24 / $totalTrans24,4) : null;
        $resp = [ 'ejecuto'=>true, 'totalVentana'=>$totalVentana, 'total24h'=>$total24, 'totalHistorico'=>$totalAll, 'porDia'=>$porDia, 'porEstado'=>$porEstado, 'topUsuarios'=>$porUsuario, 'ultimo'=>$ultimo, 'ventanaDias'=>$dias, 'totalTransicionesVentana'=>$totalTransWin, 'ratioOverridesVentana'=>$ratioVentana, 'totalTransiciones24h'=>$totalTrans24, 'ratioOverrides24h'=>$ratio24 ];
        $cache[$ckey] = ['ts'=>time(),'data'=>$resp];
        return $resp;
    }

    public function exportOverrideStats($datos){
        $stats = $this->overrideStats($datos); // admite dias
        if(!$stats['ejecuto']) return $stats;
        $csv = "#Resumen\nmetric,valor\n";
        $csv .= "ventanaDias,".$stats['ventanaDias']."\n";
        $csv .= "totalVentana,".$stats['totalVentana']."\n";
        $csv .= "total24h,".$stats['total24h']."\n";
        $csv .= "totalHistorico,".($stats['totalHistorico']!==null?$stats['totalHistorico']:'')."\n";
        $csv .= "totalTransicionesVentana,".$stats['totalTransicionesVentana']."\n";
        $csv .= "ratioOverridesVentana,".($stats['ratioOverridesVentana']!==null?$stats['ratioOverridesVentana']:'')."\n";
        $csv .= "totalTransiciones24h,".$stats['totalTransiciones24h']."\n";
        $csv .= "ratioOverrides24h,".($stats['ratioOverrides24h']!==null?$stats['ratioOverrides24h']:'')."\n";
        $csv .= "\n#PorDia\ndia,cantidad\n"; foreach($stats['porDia'] as $d=>$c){ $csv .= $d.",".$c."\n"; }
        $csv .= "\n#PorEstado\nestado,cantidad\n"; foreach($stats['porEstado'] as $e=>$c){ $csv .= $e.",".$c."\n"; }
        $csv .= "\n#TopUsuarios\nusuario,cantidad\n"; foreach($stats['topUsuarios'] as $u=>$c){ $csv .= str_replace(',', ' ', $u).",".$c."\n"; }
        $csv .= "\n#Ultimo\nfecha,proceso,estado,usuario,motivo\n";
        if($stats['ultimo']){
            $u = $stats['ultimo'];
            $csv .= ($u['fecha']?:'').",".$u['proceso'].",".$u['estado'].",".str_replace(',', ' ', $u['usuario']).",".str_replace(["\n","\r",","],' ',$u['motivo'])."\n";
        }
        return ['ejecuto'=>true,'csv'=>base64_encode($csv)];
    }

    // Estado de salud general rápido: ping + conteos + overrides recientes (ligero)
    public function healthStatus($datos){
        // Restringido a Administrador según permisos.php
        $rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : null;
        if($rol !== 'Administrador') return ['ejecuto'=>false,'mensajeError'=>'No autorizado'];
        $db = new database();
        $res = ['ejecuto'=>true];
        // Ping
        $res['time'] = time();
        // Conteo solicitudes y distribución rápida
        $sql = "SELECT estado, COUNT(1) c FROM solicitudes GROUP BY estado";
        $snap = $db->ejecutarConsulta($sql); $dist=[]; $total=0;
        if($snap['ejecuto']){ foreach($snap['data'] as $r){ $dist[$r['estado']] = (int)$r['c']; $total += (int)$r['c']; } }
        $res['solicitudesTotal'] = $total; $res['solicitudesPorEstado'] = $dist;
        // Overrides últimas 24h (rápido LIKE)
        $sqlOv = "SELECT COUNT(1) c FROM solicitudes_historico WHERE informacion LIKE '%\\\"override\\\":true%' AND fecha_creacion >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
        $ov = $db->ejecutarConsulta($sqlOv); $res['overrides24h'] = ($ov['ejecuto'] && isset($ov['data'][0]['c'])) ? (int)$ov['data'][0]['c'] : null;
        // Total transiciones 24h para ratio
        $sqlT24 = "SELECT COUNT(1) c FROM solicitudes_historico WHERE fecha_creacion >= DATE_SUB(NOW(), INTERVAL 1 DAY) AND informacion LIKE '%\\\"estado\\\":%'";
        $t24 = $db->ejecutarConsulta($sqlT24); $totT24 = ($t24['ejecuto'] && isset($t24['data'][0]['c']))?(int)$t24['data'][0]['c']:0;
        $res['ratioOverrides24h'] = ($totT24>0 && $res['overrides24h']!==null) ? round($res['overrides24h']/$totT24,4) : null;
        // Tiempo de respuesta simple
        return $res;
    }

    // Detección de anomalías de overrides: usuarios con alta concentración de overrides en la ventana.
    // Parámetros: dias (7), thresholdDia (promedio diario mínimo para marcar, default 3), top (10)
    public function overrideAnomalies($datos){
        $rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : null;
        if($rol !== 'Administrador') return ['ejecuto'=>false,'mensajeError'=>'No autorizado'];
        $dias = isset($datos['dias'])?(int)$datos['dias']:7; if($dias<=0) $dias=7; if($dias>60) $dias=60;
        $thresholdDia = isset($datos['thresholdDia'])?(int)$datos['thresholdDia']:3; if($thresholdDia<1) $thresholdDia=3;
        $top = isset($datos['top'])?(int)$datos['top']:10; if($top<=0) $top=10; if($top>50) $top=50;
        $db = new database();
        $sql = "SELECT u.nombre AS usuario, COUNT(1) c FROM solicitudes_historico sh INNER JOIN usuarios u ON sh.creado_por = u.id WHERE sh.fecha_creacion >= DATE_SUB(NOW(), INTERVAL $dias DAY) AND sh.informacion LIKE '%\\\"override\\\":true%' GROUP BY u.nombre";
        $rs = $db->ejecutarConsulta($sql); if(!$rs['ejecuto']) return $rs;
        $anomalies = [];
        foreach($rs['data'] as $r){
            $c = (int)$r['c']; $prom = $c / $dias; if($prom >= $thresholdDia){
                $anomalies[] = [ 'usuario'=>$r['usuario'], 'overrides'=>$c, 'dias'=>$dias, 'promedio_dia'=>round($prom,2) ];
            }
        }
        usort($anomalies, function($a,$b){ return $b['overrides'] <=> $a['overrides']; });
        if(count($anomalies) > $top) $anomalies = array_slice($anomalies,0,$top);
        return ['ejecuto'=>true,'dias'=>$dias,'thresholdDia'=>$thresholdDia,'anomalies'=>$anomalies,'top'=>$top];
    }

    // Self-test ligero vía API: valida endpoints críticos (subset) y devuelve estado.
    public function selfTest($datos){
        $rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : null; if($rol !== 'Administrador') return ['ejecuto'=>false,'mensajeError'=>'No autorizado'];
        $tests = [];
        $this->_runApiTest($tests, 'ping', function(){ return $this->ping([]); });
        $this->_runApiTest($tests, 'getMapaEstados', function(){ return $this->getMapaEstados([]); });
        $this->_runApiTest($tests, 'metricsFlujo', function(){ return $this->metricsFlujo(['incluirActualAbierto'=>0,'force'=>1,'maxRegistros'=>2000]); });
        $this->_runApiTest($tests, 'overrideStats', function(){ return $this->overrideStats(['dias'=>7,'force'=>1]); });
        $this->_runApiTest($tests, 'healthStatus', function(){ return $this->healthStatus([]); });
        $this->_runApiTest($tests, 'overrideAnomalies', function(){ return $this->overrideAnomalies(['dias'=>7]); });
        $overall = true; foreach($tests as $t){ if(!$t['ok']){ $overall=false; break; } }
        return ['ejecuto'=>true,'overall'=>$overall,'tests'=>$tests];
    }

    private function _runApiTest(&$arr, $label, callable $fn){
        $t0 = microtime(true); $res=null; $err=null; try{ $res = $fn(); }catch(\Throwable $e){ $err=$e->getMessage(); }
        $ok = ($err===null && is_array($res) && isset($res['ejecuto']) && $res['ejecuto']);
        $arr[] = [ 'label'=>$label, 'ok'=>$ok, 'ms'=>round((microtime(true)-$t0)*1000,1), 'mensaje'=> ($ok?'':($err?: (isset($res['mensajeError'])?$res['mensajeError']:'error'))) ];
    }

    // Generador de alertas basado en ratios y anomalías
    public function alertasFlujo($datos){
        $rol = isset($_SESSION['usuario']['rol']) ? $_SESSION['usuario']['rol'] : null; if($rol !== 'Administrador') return ['ejecuto'=>false,'mensajeError'=>'No autorizado'];
        // Cargar thresholds
        $thr = [ 'override_ratio_window'=>0.15, 'override_ratio_24h'=>0.2, 'override_user_avg_daily'=>3 ];
        $cfg = __DIR__.'/../config/monitoring.php'; if(is_file($cfg)){ $tmp = include $cfg; if(is_array($tmp)){ $thr = array_merge($thr,$tmp); } }
        $alerts = [];
        $simple = isset($datos['simple']) && (int)$datos['simple']===1; // si simple, no forzar refresco; usar caché disponible
        $stats = $this->overrideStats(['dias'=>7,'force'=> $simple?0:1 ]);
        if($stats['ejecuto']){
            if(isset($stats['ratioOverridesVentana']) && $stats['ratioOverridesVentana']!==null && $stats['ratioOverridesVentana'] > $thr['override_ratio_window']){
                $alerts[] = [ 'code'=>'override_ratio_window', 'severidad'=>'danger', 'mensaje'=>'Ratio de overrides en ventana > umbral', 'valor'=>$stats['ratioOverridesVentana'], 'umbral'=>$thr['override_ratio_window'] ];
            }
            if(isset($stats['ratioOverrides24h']) && $stats['ratioOverrides24h']!==null && $stats['ratioOverrides24h'] > $thr['override_ratio_24h']){
                $alerts[] = [ 'code'=>'override_ratio_24h', 'severidad'=>'danger', 'mensaje'=>'Ratio de overrides 24h > umbral', 'valor'=>$stats['ratioOverrides24h'], 'umbral'=>$thr['override_ratio_24h'] ];
            }
        }
        $anom = $this->overrideAnomalies(['dias'=>7, 'thresholdDia'=>$thr['override_user_avg_daily']]);
        if($anom['ejecuto'] && count($anom['anomalies'])){
            $alerts[] = [ 'code'=>'override_user_anomalies', 'severidad'=>'warning', 'mensaje'=>count($anom['anomalies']).' usuario(s) con promedio diario >= '.$thr['override_user_avg_daily'], 'valor'=>count($anom['anomalies']), 'umbral'=>$thr['override_user_avg_daily'] ];
        }
        $diag = $this->diagnosticoFlujo([]);
        if($diag['ejecuto'] && count($diag['data'])){
            $alerts[] = [ 'code'=>'procesos_atrasados', 'severidad'=>'info', 'mensaje'=>count($diag['data']).' proceso(s) con prerequisitos completos sin avanzar', 'valor'=>count($diag['data']), 'umbral'=>0 ];
        }
        $overall = 'ok'; foreach($alerts as $a){ if($a['severidad']==='danger'){ $overall='degraded'; break; } if($a['severidad']==='warning' && $overall!=='degraded') $overall='warning'; }
        return ['ejecuto'=>true,'overall'=>$overall,'alerts'=>$alerts,'thresholds'=>$thr];
    }

    // Exportar alertas actuales a CSV (reutiliza alertasFlujo)
    public function exportAlertasFlujo($datos){
        $res = $this->alertasFlujo($datos);
        if(!$res['ejecuto']) return $res;
        $csv = "overall,".$res['overall']."\ncode,severidad,mensaje,valor,umbral\n";
        foreach($res['alerts'] as $a){
            $csv .= str_replace(',', ' ', $a['code']).",".$a['severidad'].",".str_replace(["\n","\r",","],' ',$a['mensaje']).",".(isset($a['valor'])?$a['valor']:'').",".(isset($a['umbral'])?$a['umbral']:'')."\n";
        }
        return ['ejecuto'=>true,'csv'=>base64_encode($csv)];
    }
}