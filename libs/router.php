<?php
date_default_timezone_set('America/Bogota');

class router {
    public function __construct() {        
        if(isset($_GET['url']) and $_GET['url'] == 'api'){
            session_start();
            require_once "controllers/$_POST[objeto].php";
            $objeto = new $_POST['objeto']();
            echo json_encode($objeto->{$_POST['metodo']}($_POST['datos']), JSON_NUMERIC_CHECK);
            exit();
        }else{
            $url = '';
            $carpeta = 'main';
            $vista = 'login';
            $parametros = array();
            if(isset($_GET['url'])){
                $url = $_GET['url'];
                $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
                $url = explode('/', $_GET['url']);
                if(!empty($url)) {
                    $carpeta = array_shift($url);
                    if(!empty($url)) {
                        $vista = array_shift($url);
                        if(!empty($url)) {
                            $parametros = $url;
                        }
                    }
                }  
            }
            require_once "views/".$carpeta."/".$vista.".php";
        }
    }
}