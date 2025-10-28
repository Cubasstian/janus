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
    <!-- Janus specific layout overrides (after sidebar-custom.css) -->
    <style>
        /* Desactiva las clases de AdminLTE para que no rompan el diseño ShadCN */
        .sidebar-mini .main-sidebar, .sidebar-mini .main-sidebar::before {
            width: 280px !important; 
        }
        .main-sidebar, .main-sidebar::before {
            transition: none !important; 
        }
        .sidebar-mini.sidebar-collapse .main-sidebar {
            margin-left: -280px !important; 
        }
        .sidebar-mini.sidebar-collapse .main-header {
            margin-left: 0 !important; 
        }
        .sidebar-mini:not(.sidebar-collapse) .content-wrapper,
        .sidebar-mini:not(.sidebar-collapse) .main-footer {
            margin-left: 280px !important; 
        }
        /* Eliminar scrollbar de AdminLTE y asegurar la altura del contenido */
        .sidebar .os-viewport, .main-sidebar .sidebar { 
            height: 100%; 
            overflow-y: auto !important; 
            padding: 0 !important; 
        }
        .sidebar .os-host-scrollbar-horizontal, .sidebar .os-host-scrollbar-vertical { 
            display: none; 
        }
    </style>
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
    <!-- Sidebar custom overrides loaded after principal.css to avoid breaking behavior -->
    <link rel="stylesheet" href="dist/css/sidebar-custom.css">
    
    <div class="wrapper">
        <!-- main header intentionally removed per user request; keep pushmenu accessible via sidebar header or other controls -->
    <aside class="main-sidebar janus-sidebar elevation-4">
            
            <div class="janus-logo-container">
                <a href="main/inicio/" class="janus-brand-link">
                    <img src="dist/img/logo-emcali.webp" alt="Logo EMCALI" class="janus-brand-image">
                </a>
            </div>

            <div class="janus-sidebar-content">
                <nav class="janus-menu-nav">
                    <ul class="janus-menu" id="janus-menu-dynamic">
                        </ul>
                </nav>
            </div>

            <div class="janus-user-footer" id="janus-user-footer">
                <div class="janus-user-avatar" id="sidebar_avatar">J</div>
                <div class="janus-user-info">
                    <span class="janus-user-name" id="sidebar_user"></span>
                    <span class="janus-user-role" id="sidebar_role"></span>
                </div>
                <i class="fas fa-sort janus-user-dropdown-icon"></i>

                <!-- Collapsible user submenu (appears above the footer) -->
                <ul class="janus-user-submenu" id="janus-user-submenu">
                    <li><a href="main/perfil/" class="janus-user-action"><i class="fas fa-user mr-2"></i>Mi Perfil</a></li>
                    <li><a href="#" id="salir_sidebar" class="janus-user-action"><i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión</a></li>
                </ul>
            </div>
            
        </aside>