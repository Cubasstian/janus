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
    
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    
    <style>
        body.login-page {
            background: linear-gradient(135deg, #f6f9fc 0%, #eef2f7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-flex-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100vw;
            min-height: 100vh;
            gap: 24px; /* separación reducida */
            position: relative;
        }
        .login-side-img {
            position: fixed;
            max-width: none;
            width: 48vw;
            min-width: 300px;
            max-height: 96vh;
            opacity: 0.14;
            object-fit: contain;
            pointer-events: none;
            z-index: 0;
            filter: blur(0.3px);
        }
        /* Izquierda: centrada verticalmente y pegada al borde izquierdo */
        .login-side-img.left { top: 50%; transform: translateY(-50%); left: -10vw; }
        /* Derecha: posicionada arriba a la derecha con tamaño similar pero rotada y más pequeña */
        .login-side-img.right {
            top: 4vh;
            right: -10vw; /* alejar más hacia fuera para no tapar la card */
            transform: rotate(-18deg) scale(0.72);
            transform-origin: center center;
            opacity: 0.12;
        }
        @media (max-width: 991.98px) {
            .login-side-img { display: none; }
        }
        .login-box { 
            position: relative; 
            z-index: 3000; /* elevar para garantizar que quede sobre las imágenes */
            width: 440px; /* ancho ajustado para encajar mejor */
            max-width: 92vw;
            margin: 0 auto;
        }
        @media (min-width: 1200px){ 
            .login-box { width: 500px; } 
        }
        .login-box .card {
            border: 0;
            box-shadow: 0 10px 25px rgba(0,0,0,.08);
            border-radius: .75rem;
            overflow: hidden;
            min-height: 460px; /* más alta */
        }
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
            max-height: 84px;
            width: auto;
            object-fit: contain;
        }
        
        .login-title {
            text-align: center;
            font-family: inherit;
            font-weight: 700;
            color: #000000;
            font-size: 1.6rem;
            font-weight: 800;
            margin: .25rem 0 0.5rem; /* Ajustado el margin para consistencia */
        }

    /* JANUS animated letters (no gaps between letters and dots) */
    /* make the title a block so the following content starts on the next line */
    .janus-title { display: block; gap: 0; align-items: baseline; letter-spacing: 0; font-size: 2rem; margin-bottom: 1.25rem; }
    .janus-letter { display:inline-block; color: rgba(0,0,0,0.85); transition: color 0.3s ease, transform 0.3s ease; font-weight:800; font-size:inherit; vertical-align:baseline; }
    .janus-dot { display:inline-block; color: #000000; margin: 0; padding: 0; font-size:inherit; vertical-align:baseline; }
        .janus-letter.active {
            color: #295c1e; /* verde oscuro corporativo */
            transform: translateY(-2px) scale(1.01);
        }

    /* Features list (columna izquierda) */
    .features { margin-top: 0; }
    .features.wrap-right { max-width: 520px; margin-left: auto; }
        .feature-item { display:flex; align-items:flex-start; gap:1rem; margin-bottom:1.25rem; }
        .feature-item .icon { width:48px; height:48px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:18px; color:#1f4b1f; background: rgba(67,185,74,0.06); border: 2px solid rgba(67,185,74,0.12); flex:0 0 48px; }
        .feature-item h5 { margin:0 0 .25rem 0; font-size:1rem; }
        .feature-item p { margin:0; color:#607080; }
        
        .input-group-text.btn-toggle-pass {
            cursor: pointer;
            background: #fff;
        }
        .btn-success {
            border-radius: .5rem;
        }

        /* Forzar caret y outline verde corporativo en los campos de entrada */
        .form-control:focus {
            border-color: #43b94a !important;
            box-shadow: 0 0 0 2px rgba(67,185,74,0.15) !important;
            outline: 2px solid #43b94a !important;
            caret-color: #43b94a !important;
        }
        .input-group-text.btn-toggle-pass:focus {
            border-color: #43b94a !important;
            box-shadow: 0 0 0 2px rgba(67,185,74,0.15) !important;
            outline: 2px solid #43b94a !important;
        }
        
        /* Kit UI para login */
        .card-kit {
            border: none !important;
            border-radius: 16px !important;
            background: #ffffff !important;
            box-shadow: 0 8px 32px rgba(0,0,0,0.06) !important;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .card-kit:hover {
            border-color: #43b94a !important;
            box-shadow: 0 12px 32px rgba(0,0,0,0.08) !important;
        }
        .card-header-clean {
            background: #ffffff !important;
            border-bottom: 0 !important;
        }
        /* Asegurar que el cuerpo de la tarjeta sea blanco */
        .login-card-body { background: #ffffff; }
        .btn-kit {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .btn-kit-dark {
            background: #295c1e !important;
            color: #fff !important;
            border: none !important;
            border-radius: 50px !important;
            font-weight: 700;
            font-size: 1.08rem;
            box-shadow: 0 2px 8px rgba(41,92,30,0.10);
            padding: 12px 0;
            transition: background 0.2s, color 0.2s;
        }
        .btn-kit-dark:hover, .btn-kit-dark:focus {
            background: #43b94a !important;
            color: #fff !important;
        }
        /* Mover ligeramente la tarjeta hacia el centro en pantallas grandes
           para que la distancia entre el texto (izquierda) y la tarjeta sea
           más equilibrada respecto a los bordes. Ajustes responsivos. */
        @media (min-width: 992px) {
            .login-box {
                transform: translateX(-32px);
                transition: transform 0.25s ease;
            }
        }
        @media (min-width: 1400px) {
            .login-box {
                transform: translateX(-56px);
            }
        }
        /* Background birds sizing/positioning inspired by SignInSide.tsx */
        .bg-pajaro-right {
            position: fixed;
            top: 12%;
            right: -2vw;
            width: 820px;
            max-width: 62vw;
            opacity: 0.14;
            z-index: 0;
            object-fit: contain;
            pointer-events: none;
            transform: translateY(-6%);
        }
        .bg-pajaro-left {
            position: fixed;
            top: 52%;
            left: -2vw;
            width: 880px;
            max-width: 66vw;
            opacity: 0.13;
            z-index: 0;
            object-fit: contain;
            pointer-events: none;
            transform: translateY(-50%);
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-flex-container container-fluid">
    <div class="row w-100 align-items-center">
            <div class="col-lg-6 d-none d-lg-block">
                <div class="px-2 features-wrap">
                    <div class="features wrap-right">
                        <h2 class="display-4 font-weight-bold janus-title">
                            <span class="janus-letter" data-letter="J">J</span>
                            <span class="janus-dot">.</span>
                            <span class="janus-letter" data-letter="A">A</span>
                            <span class="janus-dot">.</span>
                            <span class="janus-letter" data-letter="N">N</span>
                            <span class="janus-dot">.</span>
                            <span class="janus-letter" data-letter="U">U</span>
                            <span class="janus-dot">.</span>
                            <span class="janus-letter" data-letter="S">S</span>
                        </h2>
                        <div class="feature-item">
                            <div class="icon"><i class="fas fa-database"></i></div>
                            <div>
                                <h5>Gestión Integral Precontractual</h5>
                                <p>Centraliza todo el proceso precontractual en un solo lugar: desde la solicitud del prestador hasta la generación de documentos y validaciones necesarias.</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="icon"><i class="fas fa-gavel"></i></div>
                            <div>
                                <h5>Revisión y Validación Ágil</h5>
                                <p>Permite a las áreas jurídicas, financieras y administrativas revisar, aprobar o solicitar ajustes de forma rápida, con trazabilidad en cada etapa.</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="icon"><i class="fas fa-folder-open"></i></div>
                            <div>
                                <h5>Control Documental Inteligente</h5>
                                <p>Carga, valida y organiza los documentos de los prestadores de servicios con facilidad, asegurando el cumplimiento de requisitos y evitando reprocesos.</p>
                            </div>
                        </div>


                        <div class="feature-item">
                            <div class="icon"><i class="fas fa-chart-line"></i></div>
                            <div>
                                <h5>Reportes y Trazabilidad</h5>
                                <p>Genera reportes detallados de tiempos, responsables y estados, garantizando control y eficiencia en la gestión precontractual.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-flex justify-content-center align-items-stretch">
                <div class="login-box">
                    <div class="card card-kit">
                        <div class="card-header text-center login-brand card-header-clean" style="background:#fff;">
                            <a href="#">
                                <img src="dist/img/logo-emcali.webp" alt="Logotipo EMCALI" style="display:block;max-height:84px;">
                            </a>
                        </div>

                        <div class="card-body login-card-body">
                            <p class="login-title">Iniciar Sesión</p>

                            <form id="formulario" action="#" method="post">
                                <div class="form-group">
                                    <label for="inputUsuario" class="font-weight-bold mb-1">Usuario o Email</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="login" placeholder="Usuario o Email" autocomplete="username" autofocus id="inputUsuario">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
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

                                <div class="row mt-4">
                                    <div class="col">
                                        <button type="submit" class="btn btn-kit btn-kit-dark btn-block" id="botonEnviar">
                                            <i class="fas fa-sign-in-alt mr-2"></i>Ingresar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Imágenes laterales fixed para efecto de fondo (estilo SignInSide) -->
    <img src="dist/img/PajaroEMCALIcolor.png" alt="Pajaro EMCALI Derecha" class="bg-pajaro-right">
    <img src="dist/img/PajaroEMCALIcolor%20%282%29.png" alt="Pajaro EMCALI Izquierda" class="bg-pajaro-left">
    </div>
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="plugins/toastr/toastr.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="dist/js/funciones.js"></script>
    
    <script type="text/javascript"> 
        (function(){
            // Aplicar fondo minimalista verde (inspirado en SignInSide)
            function applyMinimalistGreenBackground(){
                document.documentElement.style.setProperty('background', `linear-gradient(180deg, #E6EBE6 0%, #F5FFF5 31%, #E3FAE3 78%, #FFFFFF 100%)`, 'important');
                document.documentElement.style.setProperty('background-size', '100% 100%', 'important');
                document.documentElement.style.setProperty('background-attachment', 'fixed', 'important');
                document.documentElement.style.setProperty('background-repeat', 'no-repeat', 'important');
                document.documentElement.style.setProperty('min-height', '100vh', 'important');
                document.body.style.setProperty('background', 'transparent', 'important');
                document.body.style.setProperty('background-color', 'transparent', 'important');
            }

            // Formatear fecha a DD/MM/YY
            function formatearFecha(fecha){
                if(!fecha) return '00/00/00';
                try{
                    var d = new Date(fecha);
                    var dia = String(d.getDate()).padStart(2,'0');
                    var mes = String(d.getMonth()+1).padStart(2,'0');
                    var año = String(d.getFullYear()).slice(-2);
                    return dia + '/' + mes + '/' + año;
                }catch(e){
                    return '00/00/00';
                }
            }

            // Inicializar comportamiento (sin alerta automática)
            applyMinimalistGreenBackground();

            // Animación: resaltar letras de J.A.N.U.S una por segundo
            (function animateJanus(){
                var letters = Array.from(document.querySelectorAll('.janus-letter'));
                if(!letters.length) return;
                var idx = 0;
                function step(){
                    letters.forEach(function(l){ l.classList.remove('active'); });
                    letters[idx].classList.add('active');
                    idx = (idx + 1) % letters.length;
                }
                step();
                setInterval(step, 1000);
            })();

            // Se crea evento para recibir ingreso
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
        })();
    </script>
</body>
</html>