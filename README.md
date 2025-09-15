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

---

Para onboarding de nuevos desarrolladores: sigue la sección “Instalación rápida”. Si surge un error, revisa “Habilitar extensiones” y “Problemas comunes”.