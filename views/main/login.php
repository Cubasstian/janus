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
        <style>
            body.login-page {
                /* Fondo sutil y moderno */
                background: linear-gradient(135deg, #f6f9fc 0%, #eef2f7 100%);
                        position: relative;
                        overflow: hidden;
            }
                            /* Imágenes laterales con opacidad */
                            body.login-page::before,
                            body.login-page::after {
                        content: "";
                                position: fixed;
                                top: 0;
                                width: 45vw;      /* Un poco menor para que se aprecien ambas */
                                height: 100vh;    /* Toda la altura de la pantalla */
                                background-image: url("dist/img/PajaroEMCALIcolor (2).png");
                                background-repeat: no-repeat !important;
                                background-size: contain !important; /* Mantener proporción y que se vea grande */
                                background-position: center; /* Se ajusta por lado abajo */
                                opacity: 0.10; /* Ajusta la opacidad aquí */
                        pointer-events: none;
                        z-index: 0;
                    }
                            body.login-page::before { left: -4vw; background-position: center left; }
                            body.login-page::after { right: -4vw; background-position: center right; }

                    /* Asegurar que la tarjeta quede por encima de las imágenes */
                    .login-box { position: relative; z-index: 1; }
            .login-box .card {
                border: 0;
                box-shadow: 0 10px 25px rgba(0,0,0,.08);
                border-radius: .75rem;
                        overflow: hidden;
                        min-height: 460px; /* más alta */
            }
                    /* Ancho de la tarjeta (un poco menos ancho) */
                    .login-box { width: 420px; max-width: 92vw; }
                    @media (min-width: 1200px){ .login-box { width: 500px; } }
                    .login-card-body { padding: 2rem 2rem; }
                    /* Quitar la línea divisoria del header */
                    .login-box .card-header { border-bottom: 0 !important; }
            .login-brand {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: .5rem 0;
            }
            .login-brand img {
                max-height: 64px;
                width: auto;
                object-fit: contain;
            }
            .login-title {
                text-align: center;
                font-weight: 600;
                margin: .25rem 0 0;
                color: #155724;
            }
            .input-group-text.btn-toggle-pass {
                cursor: pointer;
                background: #fff;
            }
            .btn-success {
                border-radius: .5rem;
            }

                    /* En pantallas pequeñas, ocultar imágenes laterales para mayor claridad */
                    @media (max-width: 991.98px) {
                        body.login-page::before,
                        body.login-page::after { display: none; }
                    }
        </style>
    <!-- Google Font: Source Sans Pro -->
    <!--link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet"-->
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-success">
            <div class="card-header bg-white">
                <div class="login-brand">
                    <img src="dist/img/logo-emcali.webp" alt="Logo EMCALI">
                </div>
                <h5 class="login-title">Acceso a JANUS</h5>
            </div>
            <div class="card-body login-card-body">
                <form id="formulario">
                    <div class="form-group">
                        <label for="inputLogin" class="font-weight-bold mb-1">Usuario</label>
                        <input type="text" class="form-control" id="inputLogin" name="login" placeholder="Usuario" required="required" autocomplete="username" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword" class="font-weight-bold mb-1">Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" placeholder="Contraseña" autocomplete="current-password" id="inputPassword">
                            <div class="input-group-append">
                                <div class="input-group-text btn-toggle-pass" id="togglePassword" title="Ver/Ocultar contraseña">
                                    <span class="fas fa-eye" id="iconEye"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-success btn-block" id="botonEnviar">Ingresar</button>
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
                        // Guardar flag para mostrar mensaje de bienvenida solo al hacer login
                        sessionStorage.setItem('mostrarBienvenida', 'true')
                        sessionStorage.setItem('nombreUsuario', r.data.usuario.nombre)
                        
                        // Verificar el rol del usuario para redirigir apropiadamente
                        if(r.data && r.data.usuario && r.data.usuario.rol === 'PS'){
                            window.location.href = 'ps/informacion/'
                        } else {
                            window.location.href = 'main/inicio/'
                        }
                    }else{
                        toastr.remove()
                        toastr.error(r.mensajeError)
                        $('#botonEnviar').prop('disabled', false)
                    }
                })
            })

            // Ver/Ocultar contraseña
            $('#togglePassword').on('click', function(){
                const $input = $('#inputPassword');
                const $icon = $('#iconEye');
                const esPass = $input.attr('type') === 'password';
                $input.attr('type', esPass ? 'text' : 'password');
                $icon.toggleClass('fa-eye fa-eye-slash');
            })
        })
    </script>
</body>
</html>
