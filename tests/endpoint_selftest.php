<?php
/**
 * Script: tests/endpoint_selftest.php
 * Uso: php tests/endpoint_selftest.php
 * Objetivo: Validar rápidamente endpoints críticos del backend sin depender de un cliente HTTP externo,
 * instanciando directamente los controladores y simulando el entorno mínimo de sesión.
 * No sustituye pruebas completas, pero detecta roturas estructurales.
 */

 error_reporting(E_ALL); ini_set('display_errors','1');

 $root = dirname(__DIR__);
 require_once $root.'/controllers/procesos.php';
 require_once $root.'/controllers/solicitudes.php';
 require_once $root.'/controllers/documentos.php';
 session_start();
 // Simular usuario Administrador
 $_SESSION['usuario'] = [ 'id'=>1, 'rol'=>'Administrador', 'login'=>'selftest' ];

 $proc = new procesos();

 $tests = [];

 function runTest($label, callable $fn){
     global $tests; $t0 = microtime(true);
     $res = null; $err = null;
     try { $res = $fn(); } catch(Throwable $e){ $err = $e->getMessage(); }
     $ok = $err===null && is_array($res) && isset($res['ejecuto']) && $res['ejecuto']===true;
     $tests[] = [ 'label'=>$label, 'ok'=>$ok, 'time'=>round((microtime(true)-$t0)*1000,2), 'res'=>$res, 'err'=>$err ];
 }

 // 1. Ping
 runTest('ping', function() use ($proc){ return $proc->ping([]); });
 // 2. Mapa estados
 runTest('getMapaEstados', function() use ($proc){ return $proc->getMapaEstados([]); });
 // 3. Definición flujo
 runTest('getDefinicionFlujo', function() use ($proc){ return $proc->getDefinicionFlujo([]); });
 // 4. Metrics flujo (sin abiertos)
 runTest('metricsFlujo', function() use ($proc){ return $proc->metricsFlujo(['incluirActualAbierto'=>0,'force'=>1]); });
 // 5. Metrics flujo (con abiertos)
 runTest('metricsFlujo_abiertos', function() use ($proc){ return $proc->metricsFlujo(['incluirActualAbierto'=>1,'force'=>1]); });
 // 6. Override stats ventana 7d
 runTest('overrideStats', function() use ($proc){ return $proc->overrideStats(['dias'=>7,'force'=>1]); });
 // 7. Anomalías overrides
 runTest('overrideAnomalies', function() use ($proc){ return $proc->overrideAnomalies(['dias'=>7]); });
 // 8. Health status
 runTest('healthStatus', function() use ($proc){ return $proc->healthStatus([]); });
 // 9. Auditoría integridad
 runTest('auditoriaIntegridad', function() use ($proc){ return $proc->auditoriaIntegridad([]); });
 // 10. Diagnóstico flujo (puede retornar vacío)
 runTest('diagnosticoFlujo', function() use ($proc){ return $proc->diagnosticoFlujo([]); });

 $okAll = true; foreach($tests as $t){ if(!$t['ok']){ $okAll=false; break; } }

 echo "SELFTEST RESULT: ".($okAll?"PASS":"FAIL")."\n";
 foreach($tests as $t){
     echo str_pad($t['label'],22).' : '.($t['ok']?'OK':'ERR').' ('.$t['time'].' ms)';
     if(!$t['ok']){
         if($t['err']) echo ' EXC='.$t['err'];
         elseif(isset($t['res']['mensajeError'])) echo ' MSG='.$t['res']['mensajeError'];
     }
     echo "\n";
 }

 // Salida detallada opcional JSON (solo si se pasa --json)
 if(in_array('--json', $argv||[])){
     $out = []; foreach($tests as $t){ $out[$t['label']] = [ 'ok'=>$t['ok'], 'time_ms'=>$t['time'] ]; }
     echo json_encode(['overall'=>$okAll,'tests'=>$out], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)."\n";
 }

 exit($okAll?0:1);
