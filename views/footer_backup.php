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

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<!-- date-range-picker -->
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>

<script type="text/javascript">
    // Función global enviarPeticion con compatibilidad jQuery
    if (typeof enviarPeticion === 'undefined') {
        window.enviarPeticion = function(objeto, metodo, datos, callback) {
            // URL del API
            const url = `api/${objeto}/${metodo}/`;
            
            // Configurar datos para envío
            const requestData = {
                datos: datos || {}
            };
            
            // Realizar petición AJAX con jQuery
            $.ajax({
                url: url,
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(requestData),
                dataType: 'json',
                success: function(response) {
                    if (callback && typeof callback === 'function') {
                        callback(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en enviarPeticion:', error);
                    if (callback && typeof callback === 'function') {
                        callback({
                            ejecuto: false,
                            mensajeError: 'Error de conexión: ' + error
                        });
                    }
                }
            });
        };
    }
    
	$(function(){
        // Hook centralizado para mostrar mensajes de transición inválida si backend devuelve 'Transición inválida'
        window.mostrarError = function(resp){
            if(!resp) return
            let msg = resp.mensajeError || resp.error || 'Operación no completada'
            if(/Transici[óo]n inv[áa]lida/i.test(msg)){
                Swal.fire({icon:'warning', title:'Acción no permitida', text: msg})
            }else{
                toastr.error(msg)
            }
        }
        enviarPeticion('helpers', 'getSession', {1:1}, function(r){
            if(r.data.length == 0){
                window.location.href = 'main/login/'
            }else{
                $('#menu_user').text(r.data.usuario.nombre)
                let menu = ''
                let menuAdmin =             `<li class="nav-item has-treeview" id="menu_configuracion">
                                                <a href="#" class="nav-link">
                                                    <i class="fas fa-tools"></i>
                                                    <p>
                                                        Configuración
                                                        <i class="right fas fa-angle-left"></i>
                                                    </p>
                                                </a>
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
                let menuImputaciones =      `<li class="nav-item has-treeview" id="menu_imputaciones">
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
                                                    <li class="nav-item">
                                                        <a href="imputaciones/listar/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Listar</p>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>`
                let menuNecesidades =       `<li class="nav-item has-treeview">
                                                <a href="#" class="nav-link">
                                                    <i class="fas fa-star"></i>
                                                    <p>
                                                        Necesidades
                                                        <i class="right fas fa-angle-left"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview">
                                                    <li class="nav-item">
                                                        <a href="necesidades/gestionar/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Gestionar</p>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>`
                let menuPersonas =          `<li class="nav-item has-treeview">
                                                <a href="#" class="nav-link">
                                                    <i class="fas fa-users"></i>
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
                let menuFlujoAbre =         `<li class="nav-item has-treeview" id="menu_flujo">
                                                <a href="#" class="nav-link">
                                                    <i class="fas fa-stream"></i>
                                                    <p>
                                                        Flujo
                                                        <i class="right fas fa-angle-left"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview">`
                let menuFlujoCierra =           `</ul>
                                            </li>`
                let menuReportes =          `<li class="nav-item">
                                                <a href="reportes/estadoDetalle/" class="nav-link">
                                                    <i class="fas fa-chart-bar"></i>
                                                    <p>Reportes</p>
                                                </a>
                                            </li>`

<!-- jQuery -->
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
<!-- Chart.js (añadido) -->
<script src="plugins/chart.js/Chart.min.js"></script>
<script src="dist/js/funciones.js"></script>
<script type="text/javascript">
    // Se agregan estados intermedios: PACC confirmado (opcional) y CIIP (certificación) antes de examen / validaciones si se requiere.
    // Numeración: mantenemos los existentes y añadimos CIIP después de Ficha de requerimiento.
    var estados = ['','Ocupar necesidad', 'Gestión documentación', 'Crear tercero', 'Expedir CDP', 'Ficha de requerimiento', 'CIIP', 'Examen preocupacional', 'Validación perfil', 'Recoger validación perfil', 'Minuta', 'Numerar contrato', 'Solicitud de afiliación', 'Afiliar ARL', 'Expedir RP', 'Recoger RP', 'Designar supervisor', 'Acta de inicio', 'Contratado', 'Anulado']
    var colores = ['secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','success', 'danger']
    var estadoDocumentos = ['No cargado','Pendiente revisión', 'Aceptado', 'Rechazado']
    var coloresEstadoDocumentos = ['secondary','warning', 'success', 'danger']
    
    // Función enviarPeticion - implementación si no está definida
    if (typeof enviarPeticion === 'undefined') {
        window.enviarPeticion = function(controlador, metodo, datos, callback) {
            const payload = {
                objeto: controlador,
                metodo: metodo,
                datos: datos
            };
            
            return $.ajax({
                url: 'api/',
                type: 'POST',
                data: payload,
                dataType: 'json',
                success: function(response) {
                    if (typeof callback === 'function') {
                        callback(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en enviarPeticion:', error, xhr);
                    if (typeof callback === 'function') {
                        callback({
                            ejecuto: false,
                            mensajeError: 'Error de conexión: ' + error
                        });
                    }
                }
            });
        };
    }
    
	$(function(){
        // Hook centralizado para mostrar mensajes de transición inválida si backend devuelve 'Transición inválida'
        window.mostrarError = function(resp){
            if(!resp) return
            let msg = resp.mensajeError || resp.error || 'Operación no completada'
            if(/Transici[óo]n inv[áa]lida/i.test(msg)){
                Swal.fire({icon:'warning', title:'Acción no permitida', text: msg})
            }else{
                toastr.error(msg)
            }
        }
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
                let menuFlujoCIIP =                 `<li class="nav-item">
                                                        <a href="proceso/ciip/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>CIIP</p>
                                                        </a>
                                                    </li>`
                let menuFlujoEEP =                  `<li class="nav-item">
                                                        <a href="proceso/eep_evaluar/" class="nav-link">
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
                                                        <a href="proceso/rp/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Emitir RP</p>
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
                let menuFlujoResumen =          `<li class="nav-item">
                                                        <a href="proceso/resumen/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Resumen proceso</p>
                                                        </a>
                                                    </li>`
                let menuFlujoDiagnostico =      `<li class="nav-item">
                                                        <a href="proceso/diagnostico/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Diagnóstico flujo</p>
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
                    // Administrador: Solo Ocupar necesidad como ejecución + acceso total de visualización
                    menu = menuAdmin + menuImputaciones + menuNecesidades + menuPersonas + menuFlujoAbre + menuFlujoNecesidad + menuFlujoDocumentacion + menuFlujoTercero + menuFlujoCDP + menuFlujoFR + menuFlujoCIIP + menuFlujoEEP + menuFlujoPerfil + menuFlujoMinuta + menuFlujoNumerar + menuFlujoARL + menuFlujoRP + menuFlujoSupervisor + menuFlujoActaInicio + menuFlujoResumen + menuFlujoDiagnostico + menuFlujoCierra + menuReportes + menuBuscar
                }else if(r.data.usuario.rol == 'Revisor'){
                    menu = menuFlujoAbre + menuFlujoDocumentacion + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'UGA'){
                    // UGA: Ficha requerimiento, Elaborar minuta, Designar supervisor, Acta inicio, Finalizar
                    menu = menuNecesidades + menuFlujoAbre + menuFlujoFR + menuFlujoMinuta + menuFlujoSupervisor + menuFlujoActaInicio + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'Financiera'){
                    menu = menuFlujoAbre + menuImputaciones + menuFlujoTercero + menuFlujoCDP + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'SaludOcupacional'){
                    // SaludOcupacional: Evaluar examen preocupacional
                    menu = menuFlujoAbre + menuFlujoEEP + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'Secretaria'){
                    // Secretaria: Numerar contrato
                    menu = menuFlujoAbre + menuFlujoNumerar + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'GestionHumana'){
                    // GestionHumana: CIIP, Validar perfil, Afiliar ARL
                    menu = menuFlujoAbre + menuFlujoCIIP + menuFlujoPerfil + menuFlujoARL + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'GAE'){
                    // GAE: CIIP, Validar perfil, Afiliar ARL, Designar supervisor
                    menu = menuFlujoAbre + menuFlujoCIIP + menuFlujoPerfil + menuFlujoARL + menuFlujoSupervisor + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'PS'){
                    menu = menuPS
                    
                    // Redirección automática para usuarios PS
                    // Solo redirigir si estamos en la página principal/dashboard
                    let currentPath = window.location.pathname;
                    let isMainPage = currentPath.includes('/main/inicio/') || 
                                   currentPath.includes('/main/') && !currentPath.includes('/ps/') ||
                                   currentPath === '/' || 
                                   currentPath.endsWith('/janus/') ||
                                   currentPath.endsWith('/janus');
                    
                    if (isMainPage) {
                        setTimeout(() => {
                            window.location.href = 'ps/informacion/';
                        }, 500); // Pequeño delay para que se cargue el menú
                    }
                }
                $('#menu').html(menu)
                
                // Funcionalidades del menú mejoradas
                setupMenuEnhancements()
                
                $('#salir').on('click', function(){
                    // Limpiar estado del menú al cerrar sesión
                    clearMenuState();
                    enviarPeticion('helpers', 'destroySession', {1:1}, function(r){
                        window.location.href = 'main/login/'
                    })
                })
            }
            if(typeof init === 'function'){
                try{ init(r); }catch(e){ console.error('Error ejecutando init()', e); }
            }
            //$(':input[required]').css('box-shadow','1px 1px red')
        })
    })  
    // Global override cache & helpers
    window.__overrideCache = { }; // idProceso -> { ts:Date, overrides:[historicoItems], has:boolean, last:{} }
    window.verificarOverrideGeneric = function(idProceso, badgeSelector, opts){
        opts = opts || {}; var force = !!opts.force; var maxAgeMs = opts.maxAgeMs || 15000;
        if(!idProceso){ if(badgeSelector) $(badgeSelector).addClass('d-none'); return; }
        var now = Date.now(); var c = window.__overrideCache[idProceso];
        if(!force && c && (now - c.ts) < maxAgeMs){ actualizarBadge(badgeSelector, c); return; }
        enviarPeticion('procesos','getHistoricoProceso',{idProceso:idProceso}, function(r){
            if(r.ejecuto){
                var ovs = (r.data||[]).filter(x=>x.override);
                window.__overrideCache[idProceso] = { ts: now, overrides: ovs, has: ovs.length>0, last: ovs.length?ovs[ovs.length-1]:null };
                actualizarBadge(badgeSelector, window.__overrideCache[idProceso]);
            }
        });
        function actualizarBadge(sel, cache){
            if(!sel) return; if(!cache || !cache.has){ $(sel).addClass('d-none'); return; }
            var last = cache.last; var t = 'Override';
            if(last){ t = 'Último override: '+(last.fecha||'')+' por '+(last.usuario||'')+(last.motivo_override? (' | '+last.motivo_override):''); }
            $(sel).removeClass('d-none').attr('title', t);
            if($(sel).data('toggle')==='tooltip'){ try { $(sel).tooltip('dispose').tooltip(); }catch(e){} }
        }
    };
    window.exportarHistoricoProceso = function(idProceso){
        if(!idProceso){ toastr.warning('Seleccione un proceso'); return }
        enviarPeticion('procesos','getHistoricoProceso',{idProceso:idProceso}, function(r){
            if(!r.ejecuto){ mostrarError(r); return }
            var rows = ['fecha;usuario;estado;override;motivo'];
            (r.data||[]).forEach(function(it){
                var line = [it.fecha||'', (it.usuario||'').replace(/;/g,','), (it.estado||''), it.override? 'SI':'', (it.motivo_override||'').replace(/;/g,',')];
                rows.push(line.join(';'));
            });
            var csv = rows.join('\n');
            var b64 = btoa(unescape(encodeURIComponent(csv)));
            var a = document.createElement('a'); a.href='data:text/csv;base64,'+b64; a.download = 'historico_proceso_'+idProceso+'.csv'; document.body.appendChild(a); a.click(); document.body.removeChild(a);
        });
    };
	$(function(){
        // ...existing code building menus & session...
        enviarPeticion('helpers', 'getSession', {1:1}, function(r){
            // ...existing code...
            if(typeof init === 'function'){
                try{ init(r); }catch(e){ console.error('Error ejecutando init()', e); }
            }
        })
    })
    
    // Función para configurar mejoras del menú
    function setupMenuEnhancements() {
        // Obtener la URL actual
        const currentPath = window.location.pathname;
        const currentPage = currentPath.split('/').filter(x => x).pop() || 'inicio';
        
        // Restaurar estado del menú desde localStorage
        const menuState = JSON.parse(localStorage.getItem('janus_menu_state') || '{}');
        
        // Restaurar menús abiertos
        Object.keys(menuState).forEach(menuId => {
            if (menuState[menuId] === 'open') {
                const menuItem = $(`#${menuId}`);
                if (menuItem.length) {
                    menuItem.addClass('menu-open');
                    menuItem.find('.nav-treeview').show();
                }
            }
        });
        
        // Marcar página activa y abrir el menú padre si es necesario
        markActiveMenuItem(currentPath);
        
        // Guardar estado cuando se abre/cierra un menú
        $('.nav-item.has-treeview > .nav-link').on('click', function(e) {
            const parentItem = $(this).closest('.nav-item');
            const menuId = generateMenuId($(this));
            
            setTimeout(() => {
                const isOpen = parentItem.hasClass('menu-open');
                const currentState = JSON.parse(localStorage.getItem('janus_menu_state') || '{}');
                
                if (isOpen) {
                    currentState[menuId] = 'open';
                } else {
                    delete currentState[menuId];
                }
                
                localStorage.setItem('janus_menu_state', JSON.stringify(currentState));
            }, 100);
        });
    }
    
    // Función para marcar el elemento activo del menú
    function markActiveMenuItem(currentPath) {
        // Limpiar estados activos previos
        $('.nav-sidebar .nav-link').removeClass('active');
        
        // Mapeo de rutas a elementos del menú
        const routeMap = {
            'inicio': 'a[href="main/inicio/"]',
            'usuarios': 'a[href="configuracion/usuarios/"]',
            'ciudades': 'a[href="configuracion/ciudades/"]',
            'vigencias': 'a[href="configuracion/vigencias/"]',
            'honorarios': 'a[href="configuracion/honorarios/"]',
            'gestionar': 'a[href="imputaciones/gestionar/"]',
            'listar': 'a[href="imputaciones/listar/"]',
            'necesidades': 'a[href="necesidades/gestionar/"]',
            'personas': 'a[href="personas/gestionar/"]',
            'ocuparNecesidad': 'a[href="proceso/ocuparNecesidad/"]',
            'documentacion': 'a[href="proceso/documentacion/"]',
            'crearTercero': 'a[href="proceso/crearTercero/"]',
            'expedircdp': 'a[href="proceso/expedircdp/"]',
            'fr': 'a[href="proceso/fr/"]',
            'ciip': 'a[href="proceso/ciip/"]',
            'eep_evaluar': 'a[href="proceso/eep_evaluar/"]',
            'eep': 'a[href="proceso/eep_evaluar/"]',
            'perfil': 'a[href="proceso/perfil/"]',
            'minuta': 'a[href="proceso/minuta/"]',
            'numerar': 'a[href="proceso/numerar/"]',
            'arl': 'a[href="proceso/arl/"]',
            'rp': 'a[href="proceso/rp/"]',
            'supervisor': 'a[href="proceso/supervisor/"]',
            'actaInicio': 'a[href="proceso/actaInicio/"]',
            'resumen': 'a[href="proceso/resumen/"]',
            'diagnostico': 'a[href="proceso/diagnostico/"]',
            'buscar': 'a[href="solicitudes/buscar/"]',
            'informacion': 'a[href="ps/informacion/"]',
            'documentos': 'a[href="ps/documentos/"]'
        };
        
        // Buscar coincidencia directa
        const currentPage = currentPath.split('/').filter(x => x).pop() || 'inicio';
        let selector = routeMap[currentPage];
        
        if (!selector) {
            // Buscar por coincidencia parcial de ruta
            for (const [key, value] of Object.entries(routeMap)) {
                if (currentPath.includes(key)) {
                    selector = value;
                    break;
                }
            }
        }
        
        if (selector) {
            const activeLink = $(selector);
            if (activeLink.length) {
                activeLink.addClass('active');
                
                // Si está en un submenú, abrir el menú padre
                const parentTreeview = activeLink.closest('.nav-treeview');
                if (parentTreeview.length) {
                    const parentItem = parentTreeview.closest('.nav-item.has-treeview');
                    parentItem.addClass('menu-open');
                    parentTreeview.show();
                    
                    // Guardar en localStorage
                    const menuId = generateMenuId(parentItem.find('> .nav-link'));
                    const currentState = JSON.parse(localStorage.getItem('janus_menu_state') || '{}');
                    currentState[menuId] = 'open';
                    localStorage.setItem('janus_menu_state', JSON.stringify(currentState));
                }
            }
        }
    }
    
    // Generar ID único para un elemento del menú
    function generateMenuId(linkElement) {
        const text = linkElement.find('p').text().trim() || linkElement.text().trim();
        return 'menu_' + text.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
    }
    
    // Limpiar localStorage del menú cuando se cierra sesión
    function clearMenuState() {
        localStorage.removeItem('janus_menu_state');
    }
    
</script>