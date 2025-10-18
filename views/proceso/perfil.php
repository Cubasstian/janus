<?php require('views/header.php');?>

<!-- CSS del Sistema de Tabla Estándar Janus -->
<link rel="stylesheet" href="dist/css/tabla-procesos-estandar.css">

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-user-check text-primary mr-2"></i>
                        Validación de Perfil Profesional
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Validar Perfil</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h3 class="card-title">
                                <i class="fas fa-user-check"></i> Validación de Perfil Profesional
                            </h3>
                            <p class="text-sm mb-0">Procesos en estado 8 pendientes de validación de perfil profesional.</p>
                        </div>
                
                        <div class="card-body">
                            <!-- PANEL DE FILTROS ESTÁNDAR -->
                            <div class="filter-section-standard">
                                <div class="row align-items-end">
                                    <!-- Búsqueda General -->
                                    <div class="col-md-4">
                                        <label class="form-label-standard">Búsqueda General</label>
                                        <input type="text" 
                                               id="filtro-busqueda-estandar" 
                                               class="form-control form-control-standard" 
                                               placeholder="Buscar por ID, gerencia, contratista...">
                                    </div>
                                    
                                    <!-- Filtro por Gerencia -->
                                    <div class="col-md-3">
                                        <label class="form-label-standard">Gerencia</label>
                                        <select id="filtro-gerencia-estandar" 
                                                class="form-control form-control-standard">
                                            <option value="">Todas las gerencias</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Botón Limpiar -->
                                    <div class="col-md-2">
                                        <button type="button" 
                                                id="btn-limpiar-filtros-estandar" 
                                                class="btn btn-filter-clear-standard w-100">
                                            <i class="fas fa-eraser"></i> Limpiar
                                        </button>
                                    </div>
                                    
                                    <!-- Contador -->
                                    <div class="col-md-3 text-right">
                                        <span class="form-label-standard d-block">Registros Encontrados</span>
                                        <span id="contador-registros-estandar" class="badge badge-count-standard">0</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- TABLA ESTÁNDAR -->
                            <div class="table-responsive">
                                <table id="tabla-procesos-estandar" class="table table-standard table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID Proceso</th>
                                            <th>Gerencia</th>
                                            <th>Contratista</th>
                                            <th>Cédula</th>
                                            <th>Fecha Creación</th>
                                            <th>Días Transcurridos</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Los datos se cargarán dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORMULARIO DE VALIDACIÓN -->
            <div class="row mt-4" id="seccion-validacion" style="display: none;">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clipboard-check"></i> Validar Perfil
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="formPerfil">
                                <input type="hidden" name="idProceso" id="perfil_idProceso">
                                
                                <div class="alert alert-warning py-1 px-2 d-none" id="alertPrereqPerfil"></div>
                                
                                <div class="form-group">
                                    <label for="perfil_validado">Resultado</label>
                                    <select class="form-control" id="perfil_validado" name="perfil_validado" required>
                                        <option value="">Seleccione...</option>
                                        <option value="1">Aprobado</option>
                                        <option value="0">No aprobado</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="fecha_validacion_perfil">Fecha validación (opcional)</label>
                                    <input type="date" class="form-control" id="fecha_validacion_perfil" name="fecha_validacion_perfil">
                                </div>
                                
                                <div class="form-group">
                                    <label for="observaciones_perfil">Observaciones</label>
                                    <textarea class="form-control" id="observaciones_perfil" name="observaciones_perfil" rows="3" placeholder="Notas de la validación"></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary" id="btnGuardar" disabled>Guardar validación</button>
                                <small id="ayudaPerfil" class="form-text text-muted" style="display:none;">La validación ya fue registrada.</small>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title"><i class="fa fa-user"></i> Datos del proceso</h5>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-5">Proceso</dt><dd class="col-sm-7" id="lblProceso">---</dd>
                                <dt class="col-sm-5">Gerencia</dt><dd class="col-sm-7" id="lblGerencia">---</dd>
                                <dt class="col-sm-5">Contratista</dt><dd class="col-sm-7" id="lblContratista">---</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require('views/footer.php');?>

<script>
$(document).ready(function() {
    console.log('DOM listo, inicializando perfil...');
    
    // En lugar de configurar, vamos a cargar datos inmediatamente de forma eficiente
    console.log('Sistema estándar debería inicializarse automáticamente');
    
    // Cargar datos inmediatamente sin espera innecesaria
    cargarDatosManualmente();

    // Función optimizada para cargar datos rápidamente
    function cargarDatosManualmente() {
        console.log('Cargando datos de validar perfil...');
        
        // Mostrar indicador de carga más rápido
        if ($('#tabla-procesos-estandar tbody').length) {
            $('#tabla-procesos-estandar tbody').html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando datos...</td></tr>');
        }
        
        // Intentar primero con el filtro del servidor (más eficiente)
        enviarPeticion('solicitudes', 'getSolicitudes', {criterio:'porEstado', estado:8}, function(respuesta) {
            console.log('Respuesta directa estado 8:', respuesta);
            
            if (respuesta.ejecuto && respuesta.data && respuesta.data.length > 0) {
                console.log('Datos encontrados directamente:', respuesta.data.length);
                renderizarDatos(respuesta.data);
            } else {
                console.log('Sin datos con filtro servidor, intentando filtro cliente...');
                // Solo si falla el filtro del servidor, usar filtro cliente
                cargarTodosYFiltrar();
            }
        });
    }
    
    // Función de respaldo para filtrar en cliente (solo si es necesario)
    function cargarTodosYFiltrar() {
        enviarPeticion('solicitudes', 'getSolicitudes', {criterio:'todas'}, function(respuesta) {
            console.log('Respuesta filtro cliente:', respuesta);
            
            if (respuesta.ejecuto && respuesta.data && respuesta.data.length > 0) {
                // Filtrar solo registros en estado 8
                const datosEstado8 = respuesta.data.filter(item => item.estado == 8 || item.estado === '8');
                
                console.log('Registros filtrados estado 8:', datosEstado8.length);
                
                if (datosEstado8.length > 0) {
                    renderizarDatos(datosEstado8);
                } else {
                    mostrarMensajeSinDatos('No hay procesos pendientes de validación de perfil (estado 8)');
                }
            } else {
                mostrarMensajeSinDatos('No hay procesos disponibles');
            }
        });
    }
    
    function mostrarMensajeSinDatos(mensaje) {
        $('#tabla-procesos-estandar tbody').html(`<tr><td colspan="8" class="text-center text-muted">${mensaje}</td></tr>`);
        $('#contador-registros-estandar').text('0');
    }
    
    function renderizarDatos(datos) {
        console.log('Renderizando', datos.length, 'registros');
        
        // Verificar que existe la tabla antes de destruir DataTable
        if (!$('#tabla-procesos-estandar').length) {
            console.error('Tabla no encontrada');
            return;
        }
        
        // Destruir DataTable si existe para poder modificar el HTML
        if ($.fn.DataTable.isDataTable('#tabla-procesos-estandar')) {
            $('#tabla-procesos-estandar').DataTable().destroy();
        }
        
        let html = '';
        datos.forEach(function(item, index) {
            const procesoId = item.idProceso || item.id;
            const gerencia = item.gerencia || 'Sin asignar';
            const contratista = item.contratista_nombre || item.ps || 'Sin asignar';
            const cedula = item.contratista_cedula || item.cedula || 'Sin cédula';
            const fechaCreacion = item.fecha_creacion || 'Sin fecha';
            const tiempo = item.tiempo || 0;
            
            // Color del badge según días
            let colorBadge = 'success';
            if (tiempo > 7) colorBadge = 'danger';
            else if (tiempo > 3) colorBadge = 'warning';
            
            html += `
                <tr>
                    <td class="text-center font-weight-bold">#${procesoId}</td>
                    <td>${gerencia}</td>
                    <td>${contratista}</td>
                    <td class="text-center">${cedula}</td>
                    <td class="text-center">${fechaCreacion}</td>
                    <td class="text-center">
                        <span class="badge badge-${colorBadge}">${tiempo} días</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-warning">Validar Perfil</span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-info btn-sm" onclick="window.seleccionarProceso(${item.id || procesoId})">
                            <i class="fas fa-user-check"></i> Validar
                        </button>
                    </td>
                </tr>
            `;
        });
        
        $('#tabla-procesos-estandar tbody').html(html);
        $('#contador-registros-estandar').text(datos.length);
        
        // Reinicializar DataTable con configuración optimizada
        $('#tabla-procesos-estandar').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "pageLength": 25,
            "order": [[ 0, "desc" ]],
            "processing": false,
            "deferRender": true,
            "stateSave": false
        });
        
        console.log('Tabla poblada con', datos.length, 'registros');
    }

    // Función simple para ver detalle (la real está más abajo)
    function verDetalle(id) {
        console.log('Ver detalle del proceso:', id);
    }
    
    // Variables para el formulario
    var idProcesoSel = 0, detalleProceso = null;
    
    // Configuración del formulario
    function configurarFormulario() {
        $('#formPerfil').on('submit', function(e){
            e.preventDefault()
            if(!idProcesoSel){ toastr.error('Debe seleccionar un proceso'); return }
            var fd = new FormData(this); fd.append('idProceso', idProcesoSel); fd.append('accion', 'validarPerfil')
            enviarPeticion('procesos','updateProceso', fd, function(r){
                if(r.ejecuto){
                    toastr.success('Validación registrada')
                    location.reload();
                    $('#seccion-validacion').fadeOut()
                }else{ mostrarError(r) }
            })
        })
    }
    
    // Función para seleccionar proceso
    function seleccionarProceso(id){
        enviarPeticion('procesos','getProcesos',{criterio:'id', id:id}, function(r){
            if(!(r.ejecuto && r.data && r.data.length)){ toastr.error('No se encontró el proceso'); return }
            var p = r.data[0]; idProcesoSel = p.id
            $('#lblProceso').text(p.id)
            $('#lblGerencia').text(p.gerencia || '')
            $('#lblContratista').text('')
            $('#seccion-validacion').fadeIn()
            $('#formPerfil')[0].reset()
            $('#btnGuardar').prop('disabled', false)
            
            // Resaltar fila seleccionada
            $('.table-row-hover-standard').removeClass('table-active');
            $(`[data-id="${id}"]`).closest('tr').addClass('table-active');
            
            enviarPeticion('procesos','getDetalleProceso',{idProceso:id}, function(det){
                if(det.ejecuto && det.data && det.data.length){
                    detalleProceso = det.data[0]
                    $('#alertPrereqPerfil').addClass('d-none').empty();
                    enviarPeticion('procesos','checkPrerequisitos',{accion:'validarPerfil', idProceso:idProcesoSel}, function(pr){
                        if(pr.ejecuto && (pr.faltantes?.length || pr.pendientes?.length)){
                            var msg=[]; if(pr.faltantes.length) msg.push('Faltan: '+pr.faltantes.join(', ')); if(pr.pendientes.length) msg.push('Pendientes: '+pr.pendientes.join(', '));
                            $('#alertPrereqPerfil').removeClass('d-none').html(msg.join('<br>'));
                        }
                    })
                    if(detalleProceso.contratista_nombre){
                        var ct = detalleProceso.contratista_nombre
                        if(detalleProceso.contratista_cedula){ ct += ' ('+detalleProceso.contratista_cedula+')' }
                        $('#lblContratista').text(ct)
                    }
                    if(detalleProceso.perfil_validado !== null && detalleProceso.perfil_validado !== ''){
                        $('#perfil_validado').val(detalleProceso.perfil_validado)
                        if(detalleProceso.fecha_validacion_perfil){ $('#fecha_validacion_perfil').val(detalleProceso.fecha_validacion_perfil) }
                        if(detalleProceso.observaciones_perfil){ $('#observaciones_perfil').val(detalleProceso.observaciones_perfil) }
                        $('#formPerfil :input').prop('disabled', true)
                        $('#ayudaPerfil').show()
                    }
                }
            })
        })
    }
    
    // Configurar todo cuando el DOM esté listo
    configurarFormulario();

    // Hacer funciones globales para que estén disponibles
    window.verDetalle = verDetalle;
    window.seleccionarProceso = seleccionarProceso;
    window.cargarDatosManualmente = cargarDatosManualmente;
});
</script>
</body>
</html>