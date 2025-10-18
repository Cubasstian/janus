<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-compatible" content="ie=edge">
    <meta name="author" content="GTI">
    <meta name="copyright" content="GTI">
    <title>JANUS</title>
    <base href="/janus/">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="favicon.png">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <!--link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"-->
    <link rel="stylesheet" href="dist/css/ioicons.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Kit UI - Sistema de Diseño Janus -->
    <link rel="stylesheet" href="dist/css/kit-ui.css">
    <!-- Google Font: Source Sans Pro -->
    <!--link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"-->
    <!-- Personalizado -->
    <link rel="stylesheet" href="dist/css/principal.css">
    <!-- Sistema de Tabla Estándar Janus -->
    <link rel="stylesheet" href="dist/css/tabla-procesos-estandar.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <!-- Loading Overlay Global - Spinner con fondo difuminado -->
    <div id="globalLoadingOverlay" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(3px); z-index: 9999; display: flex; align-items: center; justify-content: center;">
        <div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color: #518711; border-width: 0.3em;">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <style>
        /* Animación de spinner personalizada */
        .spinner-border {
            animation: spinner-border 0.75s linear infinite;
        }
        @keyframes spinner-border {
            to { transform: rotate(360deg); }
        }
    </style>
    
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand" style="background: white; border-bottom: 2px solid #000;">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color: #000;">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <img src="dist/img/pajaro.png" style="height: 30px; width: auto;"/>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false" style="color: #000;">
                        <i class="fas fa-user"></i>
                        <span id="menu_user"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" style="left: inherit; right: 0px; border: 2px solid #000; border-radius: 5px;">                        
                        <a href="main/perfil/" class="dropdown-item" style="color: #000;">
                            <i class="fas fa-user-edit mr-2"></i>
                            Perfil
                        </a>
                        <div class="dropdown-divider" style="border-color: #000;"></div>
                        <a href="#" class="dropdown-item" id="salir" style="color: #000;">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Salir                            
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar elevation-4" style="background: white; border-right: 2px solid #000;">
            <a href="main/inicio/" class="brand-link elevation-2" style="background: white; border-bottom: 2px solid #000; padding: 10px; display: flex; align-items: center; justify-content: center; height: 57px; overflow: hidden;">
                <img src="dist/img/logo-emcali.webp" alt="EMCALI Logo" class="brand-image" style="width: 130px; height: 130px; object-fit: contain; max-width: 100%; max-height: 100%;">
            </a>
            <div class="sidebar" style="background: white; padding-top: 10px;">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" id="menu"></ul>
                </nav>
            </div>
        </aside>