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

// Helper: generate a stable menu id from a link element or text
function generateMenuId(linkElement) {
    try {
        // linkElement may be a jQuery object; try to extract visible text
        var $el = $(linkElement);
        var text = $el.find('p').clone().children().remove().end().text().trim();
        if(!text) text = $el.text().trim();
        if(!text) text = 'menu';
        return 'menu_' + text.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
    } catch(e) {
        var t = ('' + linkElement).trim() || 'menu';
        return 'menu_' + t.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
    }
}

function transformAdminLTEMenuToJanus(adminLTEMenuHTML, role) {
    const tempDiv = $('<div>').html(adminLTEMenuHTML);
    const janusMenu = $('<ul>').addClass('janus-menu');
    
    // Lista de ítems simples que aparecen al final (Reportes, Buscar, etc.)
    const simpleItems = ['Reportes', 'Buscar', 'Información personal', 'Documentación'];
    
    // Note: Do not add an extra 'Administrar' title — keep configuration/groups as normal menu items

    // --- 2. LÓGICA DE GRUPOS DE MENÚ Y SIMPLES ---
    // Preferred icon map: explicit mappings to the classes we know exist
    // in the project's FontAwesome package. This avoids relying on
    // automatic replacements that may point to Pro styles (fal) that aren't
    // available. Add or tweak entries here for any icon you want outlined.
    const ICON_PREFERRED_MAP = {
        'fa-tools': 'fas fa-tools',
        'fa-funnel-dollar': 'fas fa-funnel-dollar',
        'fa-star': 'far fa-star',
        'fa-users': 'fas fa-users',
        'fa-stream': 'fas fa-stream',
        'fa-chart-bar': 'fas fa-chart-bar',
        'fa-search': 'fas fa-search',
        'fa-user-edit': 'fas fa-user-edit',
        'fa-clipboard-list': 'fas fa-clipboard-list',
        'fa-sign-out-alt': 'fas fa-sign-out-alt',
        'fa-user': 'fas fa-user'
    };
    tempDiv.find('> .nav-item').each(function() {
        const $item = $(this);
        const $link = $item.find('> .nav-link');
        const linkText = $link.find('p').clone().children().remove().end().text().trim();
        
        // Si el ítem es simple (no tiene submenú y no es un grupo), lo manejamos de otra forma.
        if (!simpleItems.includes(linkText) && $item.hasClass('has-treeview')) {
            // Es un GRUPO desplegable (Configuración, Flujo, Imputaciones, Necesidades, Personas)
            const iconClass = $link.find('i.fas, i.far, i.fal').attr('class') || '';
            // Determine preferred icon class using the ICON_PREFERRED_MAP when possible.
            let iconClassPreferred = iconClass;
            try{
                const found = (iconClass.match(/fa-[a-z0-9-]+/i) || [null])[0];
                if(found && ICON_PREFERRED_MAP[found]){
                    iconClassPreferred = ICON_PREFERRED_MAP[found];
                } else {
                    // fallback: prefer 'far' (regular) then 'fas'
                    iconClassPreferred = iconClass.replace(/\b(fal|fas|fa-solid)\b/g, 'far').trim();
                    if(!iconClassPreferred || iconClassPreferred === '') iconClassPreferred = iconClass.replace(/\b(fal|far|fa-solid)\b/g, 'fas').trim();
                }
            }catch(e){ iconClassPreferred = iconClass; }
            const menuId = generateMenuId($link);
            
            // Build a proper group structure: <li><div.menu-group data-menu-id><div.menu-header>...</div><ul.submenu>...</ul></div></li>
            const group = $('<li class="janus-menu-item"></li>');
            const groupInner = $(`<div class="menu-group" data-menu-id="${menuId}"></div>`);

            // Cabecera del Grupo (clickeable)
            const header = $(`<div class="menu-header" data-toggle-id="${menuId}">
                <i class="${iconClassPreferred}"></i>
                <span>${linkText}</span>
                <i class="fas fa-chevron-right dropdown-icon"></i>
            </div>`);

            // Submenú (ul.submenu)
            const submenu = $('<ul class="submenu">');
            $item.find('.nav-treeview > .nav-item > .nav-link').each(function() {
                const $subLink = $(this);
                const subTitle = $subLink.find('p').text().trim();
                const subHref = $subLink.attr('href');

                // Preserve submenu icon if present in original link; use ICON_PREFERRED_MAP
                const subIconClass = $subLink.find('i.fas, i.far, i.fal').attr('class') || '';
                var subIconPreferred = subIconClass;
                try{
                    const sfound = (subIconClass.match(/fa-[a-z0-9-]+/i) || [null])[0];
                    // Do not render simple circle bullets in submenu (they look like bullets)
                    if(sfound === 'fa-circle'){
                        subIconPreferred = ''; // skip adding this icon
                    } else if(sfound && ICON_PREFERRED_MAP[sfound]){
                        subIconPreferred = ICON_PREFERRED_MAP[sfound];
                    } else {
                        subIconPreferred = subIconClass.replace(/\b(fal|fas|fa-solid)\b/g, 'far').trim() || subIconClass;
                    }
                }catch(e){ subIconPreferred = subIconClass; }
                const linkEl = $('<a>').attr('href', subHref);
                if(subIconPreferred) linkEl.append($('<i>').addClass(subIconPreferred));
                linkEl.append(document.createTextNode(subTitle));
                const listItem = $('<li>').append(linkEl);
                submenu.append(listItem);
            });

            groupInner.append(header).append(submenu);
            group.append(groupInner);
            janusMenu.append(group);

        } else if (simpleItems.includes(linkText) || (!$item.hasClass('has-treeview') && $link.length)) {
            // Es un ÍTEM SIMPLE (Reportes, Buscar, o los de rol PS)
            const iconClass = $link.find('i.fas, i.far, i.fal').attr('class') || '';
            let iconClassPreferred = iconClass;
            try{
                const found = (iconClass.match(/fa-[a-z0-9-]+/i) || [null])[0];
                if(found && ICON_PREFERRED_MAP[found]){
                    iconClassPreferred = ICON_PREFERRED_MAP[found];
                } else {
                    iconClassPreferred = iconClass.replace(/\b(fal|fas|fa-solid)\b/g, 'far').trim();
                    if(!iconClassPreferred || iconClassPreferred === '') iconClassPreferred = iconClass.replace(/\b(fal|far|fa-solid)\b/g, 'fas').trim();
                }
            }catch(e){ iconClassPreferred = iconClass; }
            const href = $link.attr('href') || '#';
            
            const listItem = $(`<li class="janus-menu-item">
                <a href="${href}" class="menu-header simple-link">
                    <i class="${iconClassPreferred}"></i>
                    <span>${linkText}</span>
                </a>
            </li>`);
            janusMenu.append(listItem);
        }
    });
    
    // Keep original ordering produced by the AdminLTE menu; do not inject or reorder into a special 'Administrar' title.
    
    return janusMenu.html();
}

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
                // Populate Janus sidebar user info
                $('#sidebar_user').text(r.data.usuario.nombre);
                $('#sidebar_role').text((r.data.usuario.rol || '').toUpperCase());
                // Avatar: use initials if available
                const nombreParts = (r.data.usuario.nombre || 'J').split(' ');
                const initials = (nombreParts[0] ? nombreParts[0].charAt(0) : 'J') + (nombreParts[1] ? nombreParts[1].charAt(0) : '');
                $('#sidebar_avatar').text(initials.toUpperCase());

                // (Header user block removed; sidebar footer will show user info)
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
                
                // Reemplaza la inyección en el viejo #menu por la transformación a Janus
                let janusMenuHTML = transformAdminLTEMenuToJanus(menu, r.data.usuario.rol);
                $('#janus-menu-dynamic').html(janusMenuHTML);

                // Ensure any plain-text inside anchors gets wrapped so CSS can
                // apply inline (text-width) hover. Call here because the menu is
                // generated dynamically after the AJAX call.
                if (typeof wrapJanusMenuText === 'function') wrapJanusMenuText();
                // After wrapping, ensure icons that don't render with 'fal'/'far'
                // fallback to visible variants. Run immediately and also after a
                // short timeout and on window load to account for webfont loading.
                if (typeof fixMissingIcons === 'function'){
                    try{ fixMissingIcons(); }catch(e){}
                    setTimeout(function(){ try{ fixMissingIcons(); }catch(e){} }, 160);
                    // also run after window load in case fonts arrive later
                    $(window).on('load', function(){ try{ fixMissingIcons(); }catch(e){} });
                }

                // Limpiar estados antiguos del menú que puedan estar corruptos (si aplica)
                cleanupMenuState();

                // Funcionalidades del menú mejoradas (nueva estructura)
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
                // Sidebar logout action (in footer)
                $('#salir_sidebar').on('click', function(e){
                    e.preventDefault();
                    clearMenuState();
                    enviarPeticion('helpers', 'destroySession', {1:1}, function(r){
                        window.location.href = 'main/login/'
                    })
                })
                // header user block removed - no handler necessary here
            }
            if(typeof init === 'function'){
                try{ init(r); }catch(e){ console.error('Error ejecutando init()', e); }
            }
        })
    })
    
// --- NUEVAS FUNCIONES DE INTERACTIVIDAD ---

// Función para marcar el elemento activo del menú
function markActiveMenuItem(currentPath) {
    $('.janus-menu a').removeClass('active');
    
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
        'eed': 'a[href="proceso/eep_evaluar/"]',
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
    
    if (pathParts.length >= 2) {
        const module = pathParts[pathParts.length - 2];
        const page = pathParts[pathParts.length - 1];
        
        if (page === 'gestionar') {
            if (module === 'imputaciones') selector = routeMap['imputaciones_gestionar'];
            else if (module === 'necesidades') selector = routeMap['necesidades_gestionar'];
            else if (module === 'personas') selector = routeMap['personas_gestionar'];
        } else if (page === 'listar' && module === 'necesidades') {
            selector = routeMap['necesidades_listar'];
        }
    }
    
    if (!selector) {
        // Special-case: profile page under /main should map to main/perfil, not proceso/perfil
        if (currentPath.indexOf('/main/perfil') !== -1 || currentPath.match(/\/main\/perfil\/?$/)) {
            selector = 'a[href="main/perfil/"]';
        } else {
            const currentPage = pathParts.pop() || 'inicio';
            selector = routeMap[currentPage];
        }
    }
    
    if (!selector) {
        for (const [key, value] of Object.entries(routeMap)) {
            if (currentPath.includes(key.replace('_', '/'))) { selector = value; break; }
        }
    }

    if (selector) {
        const activeLink = $(selector);
        if (activeLink.length) {
            activeLink.addClass('active');
            const parentGroup = activeLink.closest('.menu-group');
            if (parentGroup.length) {
                parentGroup.addClass('menu-open');
                var $submenu = parentGroup.find('.submenu');
                $submenu.css('max-height', '500px');
                try{
                    const menuId = parentGroup.data('menu-id');
                    if(menuId === 'menu_flujo'){
                        var fullH = 0; $submenu.children().each(function(){ fullH += $(this).outerHeight(true); });
                        $submenu[0].style.setProperty('--submenu-full-height', fullH + 'px');
                    }
                }catch(e){ console.error('active menu compute full height failed', e); }
                const menuId = parentGroup.data('menu-id');
                localStorage.setItem('janus_menu_state', JSON.stringify({ lastOpen: menuId }));
            }
        }
    }
}

// Función para configurar mejoras del menú
function setupMenuEnhancements() {
    const currentPath = window.location.pathname;
    const menuState = JSON.parse(localStorage.getItem('janus_menu_state') || '{}');
    
    // 1. Marcar página activa
    markActiveMenuItem(currentPath);
    
    // 2. Restaurar menús abiertos
    const lastOpenMenuId = menuState.lastOpen;
    if (lastOpenMenuId) {
        const menuItem = $(`.menu-group[data-menu-id="${lastOpenMenuId}"]`);
        if (menuItem.length) {
            menuItem.addClass('menu-open');
            var $submenu = menuItem.find('.submenu');
            $submenu.css('max-height', '500px');
            try{
                if(lastOpenMenuId === 'menu_flujo' && $submenu.length){
                    var fullH = 0;
                    $submenu.children().each(function(){ fullH += $(this).outerHeight(true); });
                    $submenu[0].style.setProperty('--submenu-full-height', fullH + 'px');
                }
            }catch(e){ console.error('restore submenu full height failed', e); }
        }
    }
    
    // 3. Manejo de clics (Comportamiento Acordeón)
    $('.menu-group > .menu-header').off('click.menuEnhancement').on('click.menuEnhancement', function(e) {
        const parentGroup = $(this).closest('.menu-group');
        const submenu = parentGroup.find('.submenu');
        const menuId = parentGroup.data('menu-id');
        const isCurrentlyOpen = parentGroup.hasClass('menu-open');
        
        // Cerrar todos los otros menús antes de abrir este (Acordeón)
        $('.menu-group').not(parentGroup).each(function() {
            const $otherMenu = $(this);
            if ($otherMenu.hasClass('menu-open')) {
                $otherMenu.removeClass('menu-open');
                $otherMenu.find('.submenu').css('max-height', '0px');
            }
        });
        
        if (!isCurrentlyOpen) {
            // Abrir
            parentGroup.addClass('menu-open');
            submenu.css('max-height', '500px');
            // If this is the Flujo group, compute the full content height and
            // expose it as a CSS variable so the ::before indicator can span
            // the entire submenu (not just the visible 5 items).
            try{
                if(menuId === 'menu_flujo'){
                    var fullH = 0;
                    if(submenu.length && submenu[0].children.length){
                        // sum heights of child <li> elements to get full content height
                        submenu.children().each(function(){ fullH += $(this).outerHeight(true); });
                    } else {
                        fullH = submenu[0] ? submenu[0].scrollHeight : 0;
                    }
                    // set CSS custom property on the submenu element
                    submenu[0].style.setProperty('--submenu-full-height', fullH + 'px');
                }
            }catch(err){ console.error('compute submenu full height failed', err); }
            // Guardar en localStorage
            localStorage.setItem('janus_menu_state', JSON.stringify({ lastOpen: menuId }));
        } else {
            // Cerrar
            parentGroup.removeClass('menu-open');
            submenu.css('max-height', '0px');
            // remove any computed full height
            try{ submenu[0] && submenu[0].style.removeProperty('--submenu-full-height'); }catch(e){}
            // Remover del localStorage
            localStorage.removeItem('janus_menu_state');
        }
        
        e.preventDefault();
        e.stopPropagation();
    });
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

// Wrap plain text nodes in menu anchors with .menu-text so inline hover CSS works
function wrapJanusMenuText(){
    try{
        $('#janus-menu-dynamic').find('a').each(function(){
            var $a = $(this);
            if ($a.find('.menu-text').length === 0) {
                var textNodes = $a.contents().filter(function(){ return this.nodeType === 3 && $.trim(this.nodeValue).length > 0; });
                if (textNodes.length) {
                    textNodes.wrap('<span class="menu-text"></span>');
                } else {
                    var $p = $a.find('p');
                    if ($p.length && $a.find('.menu-text').length === 0) {
                        $p.wrap('<span class="menu-text"></span>');
                    }
                }
            }
        });
    }catch(e){ console.error('wrapJanusMenuText failed', e); }
}

// Some FA icons don't have 'far' (regular) variants. After we replaced
// 'fas' with 'far' during transform, icons that don't exist become invisible.
// This function detects icons that appear to have no width and switches them
// back to 'fas' to guarantee visibility.
function fixMissingIcons(){
    try{
        // Check all icon <i> elements: if rendered width is essentially zero
        // the chosen family is likely not available (e.g. 'fal' / Pro light).
        // In that case fall back to 'far' then 'fas' to guarantee visibility.
        $('#janus-menu-dynamic').find('i').each(function(){
            var $i = $(this);
            try{
                var w = $i[0].getBoundingClientRect().width;
                if(w < 3){
                    // Try to normalize families: prefer regular then solid
                    if($i.hasClass('fal')){
                        $i.removeClass('fal').addClass('far');
                    }
                    // remeasure
                    w = $i[0].getBoundingClientRect().width;
                    if(w < 3){
                        // ensure at least 'fas' is present
                        $i.removeClass('far').addClass('fas');
                    }
                }
            }catch(e){
                // if measurement fails for some reason, conservatively ensure solid
                if(!$i.hasClass('fas')){ $i.addClass('fas'); }
            }
        });
    }catch(e){ console.error('fixMissingIcons failed', e); }
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

<script>
// Ensure layout variables match actual header height so content isn't pushed awkwardly
(function(){
    function updateHeaderHeight(){
        try{
            var header = document.querySelector('.main-header');
            var h = header ? header.offsetHeight : 0; // if header removed, no extra top gap
            document.documentElement.style.setProperty('--janus-header-height', h + 'px');
            // Also update sidebar/content immediately
            var sidebar = document.querySelector('aside.main-sidebar, .janus-sidebar');
            if(sidebar){ sidebar.style.top = (h) + 'px'; sidebar.style.height = 'calc(100vh - ' + h + 'px)'; sidebar.style.position = 'fixed'; }
            var content = document.querySelector('.content-wrapper');
            if(content){ content.style.marginTop = (h) + 'px'; content.style.marginLeft = '280px'; }
            var footer = document.querySelector('.main-footer');
            if(footer){ footer.style.marginLeft = '280px'; }
        }catch(e){ console.error('updateHeaderHeight failed', e); }
    }
    // Run on ready and on resize (debounced)
    $(document).ready(updateHeaderHeight);
    var resizeTimer;
    $(window).on('resize', function(){ clearTimeout(resizeTimer); resizeTimer = setTimeout(updateHeaderHeight, 120); });
})();
</script>

<script>
// Sidebar user footer submenu toggle
$(function(){
    var $submenu = $('#janus-user-submenu');
    if($submenu.length){
        $submenu.hide();
        // Toggle when clicking the footer container (but ignore clicks inside the submenu links)
        $(document).on('click', '#janus-user-footer', function(e){
            // If the click originated inside the submenu, allow normal link behavior
            if($(e.target).closest('#janus-user-submenu').length) return;
            e.preventDefault();
            $submenu.stop(true, true).slideToggle(150);
        });

        // Close when clicking outside
        $(document).on('click', function(e){
            if(!$(e.target).closest('#janus-user-footer').length){
                if($submenu.is(':visible')) $submenu.stop(true, true).slideUp(120);
            }
        });
    }
    // Ensure submenu links navigate even if other handlers stop default
    $(document).on('click', '#janus-user-submenu a', function(e){
        e.stopPropagation();
        var href = $(this).attr('href');
        if(href && href !== '#'){
            // Force navigation
            window.location.href = href;
        }
        // Allow default in case it's handled normally; return true
        return true;
    });
});
</script>

<!-- Ensure menu text nodes are wrapped so CSS can highlight only the text width -->
<script>
$(function(){
    try{
        // Top-level headers: wrap plain text nodes in a span.menu-text
        $('.menu-group .menu-header').each(function(){
            var $h = $(this);
            if ($h.find('> span').length === 0) {
                var textNodes = $h.contents().filter(function(){ return this.nodeType === 3 && $.trim(this.nodeValue).length > 0; });
                if (textNodes.length) {
                    textNodes.wrap('<span class="menu-text"></span>');
                }
            }
        });

        // Submenu & anchor links: wrap text nodes inside anchors so the hover highlights only text width
        $('#janus-menu-dynamic').find('a').each(function(){
            var $a = $(this);
            // Do not wrap if an element already provides .menu-text or if anchor contains multiple structural elements
            if ($a.find('.menu-text').length === 0) {
                var textNodes = $a.contents().filter(function(){ return this.nodeType === 3 && $.trim(this.nodeValue).length > 0; });
                if (textNodes.length) {
                    textNodes.wrap('<span class="menu-text"></span>');
                } else {
                    // Some links use <p> wrappers — move that content into .menu-text
                    var $p = $a.find('p');
                    if ($p.length && $a.find('.menu-text').length === 0) {
                        $p.wrap('<span class="menu-text"></span>');
                    }
                }
            }
        });
    }catch(e){ console.error('menu-text wrapping failed', e); }
});
</script>

</body>
</html>