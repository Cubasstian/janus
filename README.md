# Janus – Guía de instalación y dependencias PHP

Esta guía explica cómo preparar el entorno en Windows con XAMPP y Composer para instalar las dependencias PHP del proyecto.

## Requisitos

- Windows 10/11
- XAMPP con PHP 8.1 o superior (recomendado)
- Composer (gestor de dependencias de PHP)

Dependencias del proyecto (definidas en `composer.json`):
- `phpoffice/phpspreadsheet:^2.0` (requiere PHP >= 8.1 y extensiones: `zip`, `mbstring`, `gd`, `xml`)
- `phpmailer/phpmailer:^6.9` (recomienda `openssl`)

## Instalación rápida

1) Instalar Composer
- Descarga el instalador: https://getcomposer.org/Composer-Setup.exe
- Durante la instalación, selecciona `C:\xampp\php\php.exe` como intérprete de PHP.

2) Abrir PowerShell y posicionarse en el proyecto
```powershell
cd C:\xampp\htdocs\janus
```

3) Instalar dependencias (desde `composer.lock`)
```powershell
composer install
```

4) Verificar requisitos de plataforma (opcional recomendado)
```powershell
composer check-platform-reqs
```

Si todo es correcto, se creará la carpeta `vendor/` y el archivo `vendor/autoload.php`.

## Habilitar extensiones de PHP en XAMPP (si fuera necesario)

Algunas funciones requieren extensiones. Si `composer` o PHP reportan que faltan, habilítalas:

1) Edita `C:\xampp\php\php.ini`
2) Asegúrate de que estas líneas estén activas (sin `;` al inicio):
```
extension=zip
extension=mbstring
extension=gd
extension=xml
extension=openssl
```
3) Guarda y reinicia Apache desde el XAMPP Control Panel.

Puedes listar extensiones activas con:
```powershell
php -m
```

## Uso del autoloader

Asegúrate de cargar el autoloader de Composer en tu punto de entrada (por ejemplo, `index.php`):
```php
require __DIR__ . '/vendor/autoload.php';
```

## Verificar Composer y PHP

```powershell
composer -V
where php
php -v
```
`where php` debería apuntar a `C:\xampp\php\php.exe` y `php -v` debe mostrar PHP 8.1+.

## Ejecutar el proyecto localmente

Este proyecto está pensado para ejecutarse bajo Apache de XAMPP, sirviendo `C:\xampp\htdocs\janus`.

- Inicia Apache en XAMPP Control Panel
- Abre en el navegador: http://localhost/janus/

Si usas la consola de PHP para pruebas rápidas:
```powershell
php -S localhost:8000 -t C:\xampp\htdocs\janus
```
(Nota: Algunas reglas `.htaccess` pueden requerir Apache para funcionar correctamente.)

## Problemas comunes

- "composer no se reconoce como un comando": cierra y reabre PowerShell después de instalar Composer, o agrega Composer al PATH.
- Composer usa otro PHP distinto al de XAMPP: reinstala Composer seleccionando `C:\xampp\php\php.exe`, o ajusta el PATH para que ese sea el primero que aparece en `where php`.
- Errores de extensiones faltantes (`zip`, `mbstring`, etc.): habilítalas en `php.ini` y reinicia Apache.
- No se crea `vendor/`: verifica permisos de la carpeta del proyecto y vuelve a correr `composer install`.

## Scripts útiles

Actualizar dependencias (respetando constraints):
```powershell
composer update
```

Limpiar cachés de Composer:
```powershell
composer clear-cache
```

## Estructura relevante

- `composer.json` / `composer.lock`: definición y bloqueo de dependencias
- `vendor/`: dependencias instaladas y autoload
- `index.php`: entrada de la aplicación
- `.htaccess`: reglas de Apache

## Gobernanza de overrides (forceTransicion)

El sistema permite a usuarios con rol Administrador forzar el cambio de estado de una solicitud ignorando prerequisitos. Para mitigar riesgos se implementaron los siguientes controles:

- Motivo obligatorio: mínimo 5 caracteres.
- Confirmación manual: el usuario debe escribir la palabra FORZAR en el modal.
- Throttle: máximo 1 override cada 10 segundos y 5 overrides por hora (por sesión de usuario).
- Auditoría: cada override queda registrado en `solicitudes_historico` con banderas `override=true` y el campo `motivo_override`.
- Visualización: en las vistas de proceso y resumen se muestran badges OVR; el timeline consolida múltiples overrides por estado con tooltip detallado.

### Endpoints relacionados

- `procesos/forceTransicion`: ejecuta el override.
- `procesos/forceTransicionStatus`: expone estado del throttle (usadosÚltimaHora, cooldown restante, etc.).
- `procesos/getHistoricoProceso`: retorna historial parseado (incluye overrides y motivo).
- `procesos/metricsFlujo`, `procesos/auditoriaIntegridad`: ayudan a detectar patrones anómalos tras múltiples overrides.

### Exportaciones

- Histórico a CSV desde Resumen o Diagnóstico.
- Métricas y Auditoría exportables vía botones CSV en Diagnóstico.

## Analítica y anomalías de overrides

Se añadieron métricas avanzadas para monitoreo operacional:

- `procesos/overrideStats` parámetros:
	- `dias` (7,14,30,60) ventana de análisis (default 7)
	- `force` (1) fuerza refresco ignorando caché de 30s
	- Respuesta incluye:
		- `totalVentana`, `totalTransicionesVentana`, `ratioOverridesVentana`
		- `total24h`, `totalTransiciones24h`, `ratioOverrides24h`
		- Distribuciones: `porDia`, `porEstado`, `topUsuarios`
		- Último override (`ultimo`)
- `procesos/overrideAnomalies` parámetros:
	- `dias` (default 7, máx 60)
	- `thresholdDia` (mín overrides promedio diarios para considerar anomalía, default 3)
	- `top` límite de resultados (default 10)
	- Devuelve lista de usuarios cuyo promedio diario ≥ umbral.
- `procesos/healthStatus`: snapshot ligero con conteos y ratioOverrides24h.

Frontend (vista Diagnóstico / botón Overrides):
- Gráfico (Chart.js) línea de overrides por día si la librería está presente.
- Panel de anomalías plegable con tabla de usuarios detectados.
- Ratios visibles en el resumen (overrides / total transiciones) para ventana y 24h.

CSV de `exportOverrideStats` incluye ahora campos:
`totalTransicionesVentana, ratioOverridesVentana, totalTransiciones24h, ratioOverrides24h`.

### Monitoreo y alertas

Nuevos endpoints:

- `procesos/selfTest`: auto‑prueba rápida (subset) para chequeo de salud lógico. Devuelve `overall` y lista de tests.
- `procesos/alertasFlujo`: genera conjunto de alertas basadas en:
	- Ratios override ventana vs umbral (`override_ratio_window`).
	- Ratio override 24h vs umbral (`override_ratio_24h`).
	- Usuarios con promedio diario de overrides ≥ `override_user_avg_daily`.
	- Procesos con prerequisitos completos sin avanzar (diagnóstico flujo).

Umbrales configurables en `config/monitoring.php`:
```
return [
	'override_ratio_window' => 0.15,
	'override_ratio_24h' => 0.20,
	'override_user_avg_daily' => 3
];
```
Respuesta ejemplo de `alertasFlujo`:
```
{
	"ejecuto": true,
	"overall": "warning",
	"alerts": [
		{"code":"override_ratio_window","severidad":"danger","mensaje":"Ratio de overrides en ventana > umbral","valor":0.18,"umbral":0.15},
		{"code":"override_user_anomalies","severidad":"warning", ... }
	],
	"thresholds": { ... }
}
```

## Índices recomendados

Archivo de migración: `migraciones/2025_09_28_indices_overrides.sql` agrega índices para acelerar:

- Consultas del historial (`fk_solicitudes, fecha_creacion`).
- Inventario por estado en `solicitudes`.
- Auditorías sobre `numero_contrato`, `numero_rp` y búsquedas por `contratista`.

Ejecutar la migración tras validar que los índices no existan previamente.

## Próximos pasos sugeridos

- Integrar en el modal de forzar transición la consulta previa a `forceTransicionStatus` para mostrar cooldown en tiempo real.
- Badge de prerequisitos en Timeline utilizando `checkPrerequisitos`.
- Suite de pruebas rápidas para endpoints críticos.
- Dashboard ligero de overrides (conteo últimos 7 días) usando agregación sobre `solicitudes_historico`.

## Self-test rápido de endpoints

Script CLI: `tests/endpoint_selftest.php`

Ejecuta en consola (desde raíz del proyecto):

```powershell
php tests/endpoint_selftest.php
```

Salida típica:
```
SELFTEST RESULT: PASS
ping                  : OK (2.11 ms)
getMapaEstados        : OK (3.02 ms)
...
```

Para JSON compacto:
```powershell
php tests/endpoint_selftest.php --json
```

Si algún endpoint falla, la línea mostrará `ERR` y el mensaje/ excepción asociada. Esto ayuda a validaciones post-deploy sin montar cliente HTTP.

---

Para onboarding de nuevos desarrolladores: sigue la sección “Instalación rápida”. Si surge un error, revisa “Habilitar extensiones” y “Problemas comunes”.