	<footer class="main-footer bg-light">
        <div class="row">
            <div class="col-md-6">
                <strong>Copyright &copy; 2025 <a href="http://emcali.com.co/" target="_blank">EMCALI</a>.</strong> All rights reserved.
            </div>            
            <div class="col-md-6 text-right text-muted">
                 Desarrollado por <a href="https://www.emcali.com.co/" target="_blank"><img src="dist/img/loguito.png" alt="Emcali"></a>
            </div>
        </div>
	</footer>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Custom number -->
<script src="plugins/customd-jquery-number/jquery.number.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- moment -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/moment/locale/es.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/funciones.js"></script>
<script type="text/javascript">
    var estados = ['','Ocupar necesidad', 'Gestión documentación', 'Crear tercero', 'Expedir CDP', 'Ficha de requerimiento', 'Examen preocupacional', 'Validación perfil', 'Recoger validación perfil', 'Minuta', 'Númerar contrato', 'Solicitu de aficilación', 'Afiliar ARL', 'Expedir RP', 'Recoger RP', 'Designar supervisor', 'Acta de inicio', 'Contratado', 'Anulado']
    var colores = ['secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','success', 'danger']
    var estadoDocumentos = ['No cargado','Pendiente revisión', 'Aceptado', 'Rechazado']
    var coloresEstadoDocumentos = ['secondary','warning', 'success', 'danger']
	$(function(){
        enviarPeticion('helpers', 'getSession', {1:1}, function(r){
            if(r.data.length == 0){
                window.location.href = 'main/login/'
            }else{
                $('#menu_user').text(r.data.usuario.nombre)
                let menu = ''
                let menuAdmin =             `<li class="nav-item has-treeview">
                                                <a href="#" class="nav-link">
                                                    <i class="fas fa-tools"></i>
                                                    <p>
                                                        Configuración
                                                        <i class="right fas fa-angle-left"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview">
                                                    <li class="nav-item">
                                                        <a href="configuracion/usuarios/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Usuarios</p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="configuracion/ciudades/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Ciudades</p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="configuracion/vigencias/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Vigencias</p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="configuracion/honorarios/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Honorarios</p>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>`
                let menuImputaciones =      `<li class="nav-item has-treeview">
                                                <a href="#" class="nav-link">
                                                    <i class="fas fa-funnel-dollar"></i>
                                                    <p>
                                                        Imputaciones
                                                        <i class="right fas fa-angle-left"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview">
                                                    <li class="nav-item">
                                                        <a href="imputaciones/gestionar/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Gestionar</p>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>`                            
                let menuNecesidades =       `<li class="nav-item has-treeview">
                                                <a href="#" class="nav-link">
                                                    <i class="fab fa-searchengin"></i>
                                                    <p>
                                                        Necesidades
                                                        <i class="right fas fa-angle-left"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview">
                                                    <li class="nav-item">
                                                        <a href="necesidades/menu/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Gestionar</p>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>`
                let menuPersonas =          `<li class="nav-item has-treeview">
                                                <a href="#" class="nav-link">
                                                    <i class="fas fa-user-friends"></i>
                                                    <p>
                                                        Personas
                                                        <i class="right fas fa-angle-left"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview">
                                                    <li class="nav-item">
                                                        <a href="personas/gestionar/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Gestionar</p>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>`
                
                let menuFlujoAbre =         `<li class="nav-item has-treeview">
                                                <a href="#" class="nav-link">
                                                    <i class="fas fa-wrench"></i>
                                                    <p>
                                                        Proceso
                                                        <i class="right fas fa-angle-left"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview">`                
                let menuFlujoNecesidad =            `<li class="nav-item">
                                                        <a href="proceso/ocuparNecesidad/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Ocupar necesidad</p>
                                                        </a>
                                                    </li>`
                let menuFlujoDocumentacion =        `<li class="nav-item">
                                                        <a href="proceso/documentacion/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Documentación</p>
                                                        </a>
                                                    </li>`
                let menuFlujoTercero =              `<li class="nav-item">
                                                        <a href="proceso/crearTercero/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Crear Tercero</p>
                                                        </a>
                                                    </li>`
                let menuFlujoCDP =                  `<li class="nav-item">
                                                        <a href="proceso/expedircdp/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Expedir CDP</p>
                                                        </a>
                                                    </li>`
                let menuFlujoFR =                   `<li class="nav-item">
                                                        <a href="proceso/fr/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Ficha de requerimiento</p>
                                                        </a>
                                                    </li>`
                let menuFlujoEEP =                  `<li class="nav-item">
                                                        <a href="proceso/eep/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Evaluar examen</p>
                                                        </a>
                                                    </li>`
                let menuFlujoPerfil =               `<li class="nav-item">
                                                        <a href="proceso/perfil/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Validar perfil</p>
                                                        </a>
                                                    </li>`
                let menuFlujoNumerar =              `<li class="nav-item">
                                                        <a href="proceso/numerar/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Numerar contrato</p>
                                                        </a>
                                                    </li>`
                let menuFlujoMinuta =               `<li class="nav-item">
                                                        <a href="proceso/minuta/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Elaborar minuta</p>
                                                        </a>
                                                    </li>`
                let menuFlujoARL =                  `<li class="nav-item">
                                                        <a href="proceso/arl/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Afiliar ARL</p>
                                                        </a>
                                                    </li>`
                let menuFlujoRP =                   `<li class="nav-item">
                                                        <a href="proceso/minuta/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Expedir RP</p>
                                                        </a>
                                                    </li>`
                let menuFlujoSupervisor =           `<li class="nav-item">
                                                        <a href="proceso/supervisor/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Designar supervisor</p>
                                                        </a>
                                                    </li>`
                let menuFlujoActaInicio =           `<li class="nav-item">
                                                        <a href="proceso/actaInicio/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Acta de inicio</p>
                                                        </a>
                                                    </li>`
                let menuFlujoCierra =           `</ul>
                                            </li>`
                let menuReportes =          `<li class="nav-item has-treeview">
                                                <a href="#" class="nav-link">
                                                    <i class="fas fa-chart-line"></i>
                                                    <p>
                                                        Reportes
                                                        <i class="right fas fa-angle-left"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview">
                                                    <li class="nav-item">
                                                        <a href="reportes/general/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Dashboard</p>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>`
                let menuBuscar =            `<li class="nav-item">
                                                <a href="solicitudes/buscar/" class="nav-link">
                                                    <i class="fas fa-search"></i>
                                                    <p>Buscar</p>
                                                </a>
                                            </li>`
                let menuPS =                `<li class="nav-item">
                                                <a href="ps/informacion/" class="nav-link">
                                                    <i class="fas fa-user-edit"></i>
                                                    <p>Información personal</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="ps/documentos/" class="nav-link">
                                                    <i class="fas fa-clipboard-list"></i>
                                                    <p>Documentación</p>
                                                </a>
                                            </li>`                
                if(r.data.usuario.rol == 'Administrador'){
                    menu = menuAdmin + menuImputaciones + menuNecesidades + menuPersonas + menuFlujoAbre + menuFlujoNecesidad + menuFlujoDocumentacion + menuFlujoTercero + menuFlujoCDP + menuFlujoFR + menuFlujoEEP + menuFlujoPerfil + menuFlujoNumerar + menuFlujoMinuta + menuFlujoARL + menuFlujoRP + menuFlujoSupervisor + menuFlujoActaInicio + menuFlujoCierra + menuReportes + menuBuscar
                }else if(r.data.usuario.rol == 'Revisor'){
                    menu = menuFlujoAbre + menuFlujoDocumentacion + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'UGA'){
                    menu = menuNecesidades + menuFlujoAbre + menuFlujoFR + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'Financiera'){
                    menu = menuFlujoAbre + menuImputaciones + menuFlujoTercero + menuFlujoExpedirCDP + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'GAE'){
                    menu = menuFlujoAbre + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'PS'){
                    menu = menuPS
                }
                $('#menu').html(menu)
                $('#salir').on('click', function(){
                    enviarPeticion('helpers', 'destroySession', {1:1}, function(r){
                        window.location.href = 'main/login/'
                    })
                })
            }
            init(r)
            //$(':input[required]').css('box-shadow','1px 1px red')
        })
    })  
</script>