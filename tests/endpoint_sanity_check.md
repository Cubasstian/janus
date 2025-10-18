# Endpoint Sanity Check (Manual / Curl)

Guía rápida para validar endpoints críticos después de un despliegue.

## Precondiciones
- Sesión autenticada (obtener cookie de login) o usar entorno donde la sesión ya esté abierta en el navegador.
- Reemplazar {HOST} por el dominio o http://localhost/janus

## Notación curl básica (PowerShell)
```powershell
# Ejemplo genérico POST API
curl -X POST "{HOST}/api/" -H "Content-Type: application/x-www-form-urlencoded" -d "objeto=procesos&metodo=ping&datos[foo]=bar"
```

## 1. Ping
```powershell
curl -X POST "{HOST}/api/" -d "objeto=procesos&metodo=ping"
```
Esperado: {"ejecuto":true,"pong":<timestamp>}

## 2. Mapa de estados
```powershell
curl -X POST "{HOST}/api/" -d "objeto=procesos&metodo=getMapaEstados"
```

## 3. Definición de flujo
```powershell
curl -X POST "{HOST}/api/" -d "objeto=procesos&metodo=getDefinicionFlujo"
```

## 4. Throttle overrides (antes de forzar)
```powershell
curl -X POST "{HOST}/api/" -d "objeto=procesos&metodo=forceTransicionStatus"
```

## 5. Forzar transición (ejemplo) – usar IDs válidos / solo Admin
```powershell
curl -X POST "{HOST}/api/" -d "objeto=procesos&metodo=forceTransicion&datos[idProceso]=123&datos[estadoDestino]=11&datos[motivo]=PruebaOverride"
```
Respuestas de control:
- Motivo corto -> mensajeError.
- Exceso de frecuencia -> cooldown.
- Límite por hora -> límite alcanzado.

## 6. Histórico proceso
```powershell
curl -X POST "{HOST}/api/" -d "objeto=procesos&metodo=getHistoricoProceso&datos[idProceso]=123"
```

## 7. Métricas flujo
```powershell
curl -X POST "{HOST}/api/" -d "objeto=procesos&metodo=metricsFlujo"
```

## 8. Auditoría integridad
```powershell
curl -X POST "{HOST}/api/" -d "objeto=procesos&metodo=auditoriaIntegridad"
```

## 9. Export CSV (muestras base64)
```powershell
curl -X POST "{HOST}/api/" -d "objeto=procesos&metodo=exportMetricsFlujo" | %{ $_ -replace '.*"csv":"','' } | %{ $_ -replace '"}','','' }
```

## Validación rápida manual
- Verificar que timeline en Resumen muestra OVR con tooltip para estados override.
- Ejecutar un override y comprobar incremento en histórico + badges.
- Confirmar que throttle bloquea segundo override inmediato.

## Futuro (automatización)
Se puede crear un script PHP/Node para ejecutar estos POST y validar estructura JSON (pending).
