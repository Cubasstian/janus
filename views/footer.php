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
<!-- jQuery Number -->
<script src="plugins/customd-jquery-number/jquery.number.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Funciones globales -->
<script src="dist/js/funciones.js"></script>

<script type="text/javascript">
	$(function(){
        // Hook centralizado para mostrar mensajes de transición inválida
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
            if(!r || !r.ejecuto || !r.data || !r.data.usuario){
                window.location.href = 'main/login/'
            }else{
                $('#menu_user').text(r.data.usuario.nombre)
                let menu = ''
                
                // Menús principales
                let menuAdmin = `<li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="fas fa-tools"></i>
                        <p>Configuración <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="configuracion/usuarios/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Usuarios</p></a></li>
                        <li class="nav-item"><a href="configuracion/ciudades/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Ciudades</p></a></li>
                        <li class="nav-item"><a href="configuracion/vigencias/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Vigencias</p></a></li>
                        <li class="nav-item"><a href="configuracion/honorarios/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Honorarios</p></a></li>
                    </ul>
                </li>`
                
                let menuImputaciones = `<li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="fas fa-funnel-dollar"></i>
                        <p>Imputaciones <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="imputaciones/gestionar/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Gestionar</p></a></li>
                    </ul>
                </li>`
                
                let menuNecesidades = `<li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="fas fa-star"></i>
                        <p>Necesidades <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="necesidades/gestionar/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Gestionar</p></a></li>
                        <li class="nav-item"><a href="necesidades/listar/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Listar</p></a></li>
                    </ul>
                </li>`
                
                let menuPersonas = `<li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="fas fa-users"></i>
                        <p>Personas <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="personas/gestionar/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Gestionar</p></a></li>
                    </ul>
                </li>`
                
                // Menú de flujo - ORDENADO SEGÚN FLUJO ADMINISTRATIVO REAL
                let menuFlujoAbre = `<li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="fas fa-stream"></i>
                        <p>Flujo <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">`
                
                // Ocupar Necesidad (PRIMER PASO ADMINISTRATIVO - asignar a casilla)
                let menuFlujoNecesidad = `<li class="nav-item"><a href="proceso/ocuparNecesidad/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Ocupar necesidad</p></a></li>`
                
                // Revisar Documentación (SEGUNDO PASO - revisar docs que PS ya subió)
                let menuFlujoDocumentacion = `<li class="nav-item"><a href="proceso/documentacion/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Documentación</p></a></li>`
                
                // Crear Tercero
                let menuFlujoTercero = `<li class="nav-item"><a href="proceso/crearTercero/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Crear Tercero</p></a></li>`
                
                // Expedir CDP
                let menuFlujoCDP = `<li class="nav-item"><a href="proceso/expedircdp/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Expedir CDP</p></a></li>`
                
                // Ficha Requerimiento
                let menuFlujoFR = `<li class="nav-item"><a href="proceso/fr/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Ficha requerimiento</p></a></li>`
                
                // Examen Preocupacional
                let menuFlujoEEP = `<li class="nav-item"><a href="proceso/eep_evaluar/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Examen preocupacional</p></a></li>`
                
                // Validar Perfil (VA ANTES QUE CIIP)
                let menuFlujoPerfil = `<li class="nav-item"><a href="proceso/perfil/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Validar perfil</p></a></li>`
                
                // CIIP (VA DESPUÉS DE VALIDAR PERFIL)
                let menuFlujoCIIP = `<li class="nav-item"><a href="proceso/ciip/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>CIIP</p></a></li>`
                
                // Numerar Contrato
                let menuFlujoNumerar = `<li class="nav-item"><a href="proceso/numerar/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Numerar contrato</p></a></li>`
                
                // Elaborar Minuta
                let menuFlujoMinuta = `<li class="nav-item"><a href="proceso/minuta/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Elaborar minuta</p></a></li>`
                
                // Afiliar ARL
                let menuFlujoARL = `<li class="nav-item"><a href="proceso/arl/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Afiliar ARL</p></a></li>`
                
                // Emitir RP
                let menuFlujoRP = `<li class="nav-item"><a href="proceso/rp/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Emitir RP</p></a></li>`
                
                // Designar Supervisor
                let menuFlujoSupervisor = `<li class="nav-item"><a href="proceso/supervisor/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Designar supervisor</p></a></li>`
                let menuFlujoActaInicio = `<li class="nav-item"><a href="proceso/actaInicio/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Acta de inicio</p></a></li>`
                let menuFlujoResumen = `<li class="nav-item"><a href="proceso/resumen/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Resumen</p></a></li>`
                let menuFlujoDiagnostico = `<li class="nav-item"><a href="proceso/diagnostico/" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Diagnóstico flujo</p></a></li>`
                let menuFlujoCierra = `</ul></li>`
                
                let menuReportes = `<li class="nav-item"><a href="reportes/estadoDetalle/" class="nav-link"><i class="fas fa-chart-bar"></i><p>Reportes</p></a></li>`
                let menuBuscar = `<li class="nav-item"><a href="solicitudes/buscar/" class="nav-link"><i class="fas fa-search"></i><p>Buscar</p></a></li>`
                let menuPS = `<li class="nav-item"><a href="ps/informacion/" class="nav-link"><i class="fas fa-user-edit"></i><p>Información personal</p></a></li>
                             <li class="nav-item"><a href="ps/documentos/" class="nav-link"><i class="fas fa-clipboard-list"></i><p>Documentación</p></a></li>`
                
                // Construir menú según rol - CON ORDEN CORRECTO DEL FLUJO ADMINISTRATIVO
                if(r.data.usuario.rol == 'Administrador'){
                    menu = menuAdmin + menuImputaciones + menuNecesidades + menuPersonas + menuFlujoAbre + menuFlujoNecesidad + menuFlujoDocumentacion + menuFlujoTercero + menuFlujoCDP + menuFlujoFR + menuFlujoEEP + menuFlujoPerfil + menuFlujoCIIP + menuFlujoNumerar + menuFlujoMinuta + menuFlujoARL + menuFlujoRP + menuFlujoSupervisor + menuFlujoActaInicio + menuFlujoResumen + menuFlujoDiagnostico + menuFlujoCierra + menuReportes + menuBuscar
                }else if(r.data.usuario.rol == 'Revisor'){
                    menu = menuFlujoAbre + menuFlujoDocumentacion + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'UGA'){
                    menu = menuNecesidades + menuFlujoAbre + menuFlujoFR + menuFlujoMinuta + menuFlujoSupervisor + menuFlujoActaInicio + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'Financiera'){
                    menu = menuFlujoAbre + menuImputaciones + menuFlujoTercero + menuFlujoCDP + menuFlujoRP + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'SaludOcupacional'){
                    menu = menuFlujoAbre + menuFlujoEEP + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'Secretaria'){
                    menu = menuFlujoAbre + menuFlujoNumerar + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'GestionHumana'){
                    menu = menuFlujoAbre + menuFlujoPerfil + menuFlujoCIIP + menuFlujoARL + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'GAE'){
                    menu = menuFlujoAbre + menuFlujoPerfil + menuFlujoCIIP + menuFlujoARL + menuFlujoSupervisor + menuFlujoCierra + menuBuscar
                }else if(r.data.usuario.rol == 'PS'){
                    menu = menuPS
                    
                    // Redirección automática para usuarios PS
                    let currentPath = window.location.pathname;
                    let isMainPage = currentPath.includes('/main/inicio/') || 
                                   currentPath.includes('/main/') && !currentPath.includes('/ps/') ||
                                   currentPath === '/' || 
                                   currentPath.endsWith('/janus/') ||
                                   currentPath.endsWith('/janus');
                    
                    if (isMainPage) {
                        setTimeout(() => {
                            window.location.href = 'ps/informacion/';
                        }, 500);
                    }
                }
                
                $('#menu').html(menu)
                
                // Limpiar estados antiguos del menú que puedan estar corruptos
                cleanupMenuState();
                
                // Funcionalidades del menú mejoradas
                setupMenuEnhancements()
                
                // Ocultar loading overlay después de que todo esté cargado
                setTimeout(() => {
                    $('#globalLoadingOverlay').fadeOut(400, function(){
                        $(this).remove()
                    })
                }, 300)
                
                $('#salir').on('click', function(){
                    clearMenuState();
                    enviarPeticion('helpers', 'destroySession', {1:1}, function(r){
                        window.location.href = 'main/login/'
                    })
                })
            }
            if(typeof init === 'function'){
                try{ init(r); }catch(e){ console.error('Error ejecutando init()', e); }
            }
        })
    })
    
    // Función para configurar mejoras del menú
    function setupMenuEnhancements() {
        const currentPath = window.location.pathname;
        const menuState = JSON.parse(localStorage.getItem('janus_menu_state') || '{}');
        
        // Asignar IDs dinámicos a menús sin ID
        $('.nav-item.has-treeview').each(function() {
            const $this = $(this);
            if (!$this.attr('id')) {
                const menuId = generateMenuId($this.find('> .nav-link'));
                $this.attr('id', menuId);
            }
        });
        
        // Restaurar menús abiertos (solo el último abierto para comportamiento acordeón)
        const menuStateEntries = Object.entries(menuState);
        if (menuStateEntries.length > 0) {
            // Obtener solo el último menú que estaba abierto para comportamiento acordeón
            const lastOpenMenu = menuStateEntries[menuStateEntries.length - 1];
            const [menuId, state] = lastOpenMenu;
            
            if (state === 'open') {
                const menuItem = $(`#${menuId}`);
                if (menuItem.length) {
                    menuItem.addClass('menu-open');
                    menuItem.find('.nav-treeview').show();
                }
            }
            
            // Limpiar otros menús del estado para mantener solo uno
            const cleanState = {};
            cleanState[lastOpenMenu[0]] = lastOpenMenu[1];
            localStorage.setItem('janus_menu_state', JSON.stringify(cleanState));
        }
        
        // Marcar página activa
        markActiveMenuItem(currentPath);
        
        // Guardar estado cuando se abre/cierra un menú
        $('.nav-item.has-treeview > .nav-link').off('click.menuEnhancement').on('click.menuEnhancement', function(e) {
            const parentItem = $(this).closest('.nav-item');
            const menuId = parentItem.attr('id') || generateMenuId($(this));
            
            // Comportamiento acordeón: cerrar otros menús abiertos
            const isCurrentlyOpen = parentItem.hasClass('menu-open');
            
            if (!isCurrentlyOpen) {
                // Cerrar todos los otros menús antes de abrir este
                $('.nav-item.has-treeview').not(parentItem).each(function() {
                    const $otherMenu = $(this);
                    if ($otherMenu.hasClass('menu-open')) {
                        $otherMenu.removeClass('menu-open');
                        $otherMenu.find('.nav-treeview').slideUp(200);
                        
                        // Limpiar del localStorage
                        const otherMenuId = $otherMenu.attr('id') || generateMenuId($otherMenu.find('> .nav-link'));
                        const currentState = JSON.parse(localStorage.getItem('janus_menu_state') || '{}');
                        delete currentState[otherMenuId];
                        localStorage.setItem('janus_menu_state', JSON.stringify(currentState));
                    }
                });
                
                // Abrir el menú actual con animación
                setTimeout(() => {
                    parentItem.addClass('menu-open');
                    parentItem.find('.nav-treeview').slideDown(200);
                    
                    // Guardar en localStorage
                    const currentState = JSON.parse(localStorage.getItem('janus_menu_state') || '{}');
                    currentState[menuId] = 'open';
                    localStorage.setItem('janus_menu_state', JSON.stringify(currentState));
                }, 50);
            } else {
                // Si ya está abierto, cerrarlo
                parentItem.removeClass('menu-open');
                parentItem.find('.nav-treeview').slideUp(200);
                
                // Remover del localStorage
                const currentState = JSON.parse(localStorage.getItem('janus_menu_state') || '{}');
                delete currentState[menuId];
                localStorage.setItem('janus_menu_state', JSON.stringify(currentState));
            }
            
            // Prevenir el comportamiento por defecto de AdminLTE
            e.preventDefault();
            e.stopPropagation();
        });
    }
    
    // Función para marcar el elemento activo del menú
    function markActiveMenuItem(currentPath) {
        $('.nav-sidebar .nav-link').removeClass('active');
        
        const routeMap = {
            'inicio': 'a[href="main/inicio/"]',
            'usuarios': 'a[href="configuracion/usuarios/"]',
            'ciudades': 'a[href="configuracion/ciudades/"]',
            'vigencias': 'a[href="configuracion/vigencias/"]',
            'honorarios': 'a[href="configuracion/honorarios/"]',
            'imputaciones_gestionar': 'a[href="imputaciones/gestionar/"]',
            'necesidades_gestionar': 'a[href="necesidades/gestionar/"]',
            'necesidades_listar': 'a[href="necesidades/listar/"]',
            'personas_gestionar': 'a[href="personas/gestionar/"]',
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
        
        // Lógica mejorada para determinar la página actual
        let selector = null;
        const pathParts = currentPath.split('/').filter(x => x);
        
        // Casos especiales para páginas con el mismo nombre en diferentes módulos
        if (pathParts.length >= 2) {
            const module = pathParts[pathParts.length - 2]; // penúltimo elemento
            const page = pathParts[pathParts.length - 1];   // último elemento
            
            if (page === 'gestionar') {
                if (module === 'imputaciones') {
                    selector = routeMap['imputaciones_gestionar'];
                } else if (module === 'necesidades') {
                    selector = routeMap['necesidades_gestionar'];
                } else if (module === 'personas') {
                    selector = routeMap['personas_gestionar'];
                }
            } else if (page === 'listar') {
                if (module === 'necesidades') {
                    selector = routeMap['necesidades_listar'];
                }
            }
        }
        
        // Si no se encontró selector específico, usar lógica original
        if (!selector) {
            const currentPage = pathParts.pop() || 'inicio';
            selector = routeMap[currentPage];
        }
        
        // Fallback: buscar por coincidencia en la ruta
        if (!selector) {
            for (const [key, value] of Object.entries(routeMap)) {
                if (currentPath.includes(key.replace('_', '/'))) {
                    selector = value;
                    break;
                }
            }
        }
        
        if (selector) {
            const activeLink = $(selector);
            if (activeLink.length) {
                activeLink.addClass('active');
                
                const parentTreeview = activeLink.closest('.nav-treeview');
                if (parentTreeview.length) {
                    const parentItem = parentTreeview.closest('.nav-item.has-treeview');
                    parentItem.addClass('menu-open');
                    parentTreeview.show();
                    
                    const menuId = generateMenuId(parentItem.find('> .nav-link'));
                    const currentState = JSON.parse(localStorage.getItem('janus_menu_state') || '{}');
                    currentState[menuId] = 'open';
                    localStorage.setItem('janus_menu_state', JSON.stringify(currentState));
                }
            }
        }
    }
    
    function generateMenuId(linkElement) {
        const text = linkElement.find('p').text().trim() || linkElement.text().trim();
        return 'menu_' + text.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
    }
    
    function cleanupMenuState() {
        // Limpiar estados de menús que ya no existen en el DOM
        const currentState = JSON.parse(localStorage.getItem('janus_menu_state') || '{}');
        const validMenuIds = [];
        
        // Recopilar IDs válidos de menús actuales
        $('.nav-item.has-treeview').each(function() {
            const id = $(this).attr('id');
            if (id) {
                validMenuIds.push(id);
            }
        });
        
        // Filtrar estados inválidos
        const cleanState = {};
        Object.keys(currentState).forEach(menuId => {
            if (validMenuIds.includes(menuId)) {
                cleanState[menuId] = currentState[menuId];
            }
        });
        
        // Guardar estado limpio
        localStorage.setItem('janus_menu_state', JSON.stringify(cleanState));
    }
    
    function clearMenuState() {
        localStorage.removeItem('janus_menu_state');
    }
</script>

<!-- Variables globales para estados y colores -->
<script>
// Estados de solicitudes
var estados = [
    '', // 0
    'Necesidad creada', // 1
    'Ocupada', // 2 - Asignada a casilla
    'Documentación revisada', // 3 - Documentos PS validados
    'CIIP validado', // 4 - CIIP aprobado
    'Perfil validado', // 5 - Perfil profesional aprobado
    'Tercero creado', // 6 - Tercero registrado en sistema
    'CDP generado', // 7 - Certificado de disponibilidad presupuestal
    'Documentación completa', // 8 - Toda documentación adjuntada
    'Numerado', // 9 - Contrato numerado
    'Legalizado', // 10 - Contrato legalizado
    'Ejecutando', // 11 - En ejecución
    'Finalizado', // 12 - Proceso completado
    'Archivado' // 13 - Archivado
];

// Colores para badges de estados
var colores = [
    'secondary', // 0 - Sin definir
    'primary', // 1 - Necesidad creada (inicio del proceso)
    'info', // 2 - Ocupada (asignada)
    'info', // 3 - Documentación revisada (en proceso)
    'warning', // 4 - CIIP validado (validación técnica)
    'warning', // 5 - Perfil validado (validación técnica)
    'warning', // 6 - Tercero creado (preparación administrativa)
    'warning', // 7 - CDP generado (preparación presupuestal)
    'warning', // 8 - Documentación completa (preparación final)
    'success', // 9 - Numerado (proceso aprobado)
    'success', // 10 - Legalizado (proceso formalizado)
    'success', // 11 - Ejecutando (activo)
    'dark', // 12 - Finalizado (completado)
    'secondary' // 13 - Archivado (inactivo)
];
</script>

<!-- Error handling para scripts externos -->
<script>
// Suprimir errores de scripts externos de métricas y widgets
window.addEventListener('error', function(event) {
    // Lista de errores externos que queremos suprimir
    const externalErrors = [
        'portalemcalibackend.onrender.com',
        'Failed to load resource: net::ERR_BLOCKED_BY_CLIENT',
        'Failed to load resource: the server responded with a status of 401',
        'Failed to load resource: the server responded with a status of 404',
        'UserWay widget',
        'widget_app_base',
        'clipboard-write is not allowed',
        'message channel closed before a response was received'
    ];
    
    // Verificar si el error es de un script externo
    const isExternalError = externalErrors.some(errorPattern => 
        event.message && event.message.includes(errorPattern) || 
        (event.filename && event.filename.includes(errorPattern))
    );
    
    if (isExternalError) {
        event.preventDefault();
        return true; // Suprimir el error
    }
});

// Suprimir errores de Promise no capturadas de extensiones
window.addEventListener('unhandledrejection', function(event) {
    const errorMessage = event.reason?.message || event.reason || '';
    const externalPromiseErrors = [
        'message channel closed',
        'clipboard-write',
        'portalemcalibackend',
        'metrics'
    ];
    
    const isExternalPromiseError = externalPromiseErrors.some(pattern => 
        errorMessage.includes(pattern)
    );
    
    if (isExternalPromiseError) {
        event.preventDefault();
        return true; // Suprimir el error
    }
});

// Override console.error para filtrar errores externos
const originalConsoleError = console.error;
console.error = function(...args) {
    const message = args.join(' ');
    const externalPatterns = [
        'portalemcalibackend',
        'UserWay widget',
        'clipboard-write',
        'message channel closed',
        'ERR_BLOCKED_BY_CLIENT'
    ];
    
    const isExternalError = externalPatterns.some(pattern => 
        message.includes(pattern)
    );
    
    // Solo mostrar errores que no sean de scripts externos
    if (!isExternalError) {
        originalConsoleError.apply(console, args);
    }
};

// Bloquear peticiones a dominios externos problemáticos
if (typeof fetch !== 'undefined') {
    const originalFetch = fetch;
    window.fetch = function(url, options) {
        if (typeof url === 'string' && url.includes('portalemcalibackend.onrender.com')) {
            // Retornar una promise resuelta para evitar errores
            return Promise.resolve(new Response('{}', {
                status: 200,
                statusText: 'OK',
                headers: { 'Content-Type': 'application/json' }
            }));
        }
        return originalFetch.apply(this, arguments);
    };
}
</script>

<!-- Sistema de Tabla Estándar Janus -->
<script src="dist/js/tabla-procesos-estandar.js"></script>

</body>
</html>