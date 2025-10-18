<?php
date_default_timezone_set('America/Bogota');

class router {
    public function __construct() {
        // Normaliza y divide la URL en segmentos, ignorando slashes vacíos
        $rawUrl = isset($_GET['url']) ? filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL) : '';
        $rawUrl = trim((string)$rawUrl, "/\t\n\r\0\x0B");
        $segments = array_values(array_filter(explode('/', $rawUrl), 'strlen'));

        // Rama API: acepta 'api' con o sin slash final (ej. 'api' o 'api/')
        if (!empty($segments) && strtolower($segments[0]) === 'api') {
            header('Content-Type: application/json; charset=utf-8');
            session_start();
            // Validaciones básicas
            if (!isset($_POST['objeto'], $_POST['metodo'])) {
                http_response_code(400);
                echo json_encode(['ejecuto'=>false, 'mensajeError'=>'Petición inválida']);
                exit();
            }
            $objKey = preg_replace('/[^a-zA-Z0-9_]/', '', (string)$_POST['objeto']);
            $metKey = preg_replace('/[^a-zA-Z0-9_]/', '', (string)$_POST['metodo']);
            $controllerPath = __DIR__ . '/../controllers/' . $objKey . '.php';
            if (!is_file($controllerPath)) {
                http_response_code(404);
                echo json_encode(['ejecuto'=>false, 'mensajeError'=>'Controlador no encontrado']);
                exit();
            }
            require_once $controllerPath;
            if (!class_exists($objKey)) {
                http_response_code(500);
                echo json_encode(['ejecuto'=>false, 'mensajeError'=>'Clase de controlador inválida']);
                exit();
            }
            $inst = new $objKey();
            if (!method_exists($inst, $metKey)) {
                http_response_code(404);
                echo json_encode(['ejecuto'=>false, 'mensajeError'=>'Método no encontrado']);
                exit();
            }
            // Si viene 'datos' como array plano (FormData), usarlo directamente
            // Si no, usar el formato antiguo con $_POST['datos']
            $datos = isset($_POST['datos']) ? $_POST['datos'] : [];
            // Si datos está vacío, pero hay otros campos POST (ej: datos[id]), construir el array
            if (empty($datos) && !empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    if (strpos($key, 'datos[') === 0 && substr($key, -1) === ']') {
                        $field = substr($key, 6, -1);
                        $datos[$field] = $value;
                    }
                }
            }
            echo json_encode($inst->{$metKey}($datos), JSON_NUMERIC_CHECK);
            exit();
        } else {
            // Vistas: carpeta/vista[/param1/param2]
            $carpeta = 'main';
            $vista = 'login';
            $parametros = [];
            if (!empty($segments)) {
                $carpeta = array_shift($segments) ?: 'main';
                if (!empty($segments)) {
                    $vista = array_shift($segments) ?: 'login';
                    if (!empty($segments)) { $parametros = $segments; }
                }
            }
            // Fallback seguro si la vista queda vacía
            if (!$carpeta) { $carpeta = 'main'; }
            if (!$vista) { $vista = 'login'; }
            require_once __DIR__ . '/../views/' . $carpeta . '/' . $vista . '.php';
        }
    }
}