<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-compatible" content="ie=edge">
    <meta name="author" content="Víctor Hugo Hernández">
    <meta name="copyright" content="GTI">
    <title>JANUS</title>
    <base href="/janus/">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="favicon.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <!--link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"-->
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <!--link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet"-->
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-success">
            <div class="card-header">                
                <div class="row">
                    <div class="col text-center m-0">
                        <img src="dist/img/logo.png" alt="Logo">
                    </div>
                </div>
            </div>
            <div class="card-body login-card-body">
                <form id="formulario">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="login" placeholder="Usuario" required="required">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Contraseña">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-success btn-block" id="botonEnviar">Entrar</button>
                        </div>
                    </div>
                </form>                
            </div>            
        </div>
    </div>

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="plugins/toastr/toastr.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <script src="dist/js/funciones.js"></script>
    <script type="text/javascript"> 
        $(function(){
            //Se crea evento para recibir ingreso
            $('#formulario').on('submit', function(e){
                e.preventDefault()
                $('#botonEnviar').prop('disabled', true)
                toastr.info("Autenticando...")
                let datos = parsearFormulario($(this))
                enviarPeticionPura('usuarios', 'login', {info:datos}, function(r){
                    if(r.ejecuto == true){
                        window.location.href = 'main/inicio/'
                    }else{
                        toastr.remove()
                        toastr.error(r.mensajeError)
                        $('#botonEnviar').prop('disabled', false)
                    }
                })
            })
        })
    </script>
</body>
</html>