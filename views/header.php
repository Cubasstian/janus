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
    <!-- Google Font: Source Sans Pro -->
    <!--link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"-->
    <!-- Personalizado -->
    <link rel="stylesheet" href="dist/css/principal.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <img src="dist/img/pajaro.png" style=/>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fas fa-user"></i>
                        <span id="menu_user"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" style="left: inherit; right: 0px;">                        
                        <a href="main/perfil/" class="dropdown-item">
                            <i class="fas fa-user-edit mr-2"></i>
                            Perfil
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" id="salir">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Salir                            
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar elevation-4 sidebar-light-warning">
            <a href="main/inicio/" class="brand-link elevation-2 navbar-success">
                <img src="dist/img/logote.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-4" style="opacity: .8">
                <span class="brand-text font-weight-light text-white"><b>JANUS</b></span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" id="menu"></ul>
                </nav>
            </div>
        </aside>