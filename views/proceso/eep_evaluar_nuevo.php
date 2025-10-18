<?php require('views/header.php');?>

<!-- PÁGINA EEP EVALUAR CON SISTEMA ESTÁNDAR -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title">
                        <i class="fas fa-user-md"></i> Evaluación de Exámenes Ocupacionales (EEP)
                    </h3>
                </div>
                
                <div class="card-body">
                    <!-- PANEL DE FILTROS ESTÁNDAR -->
                    <div class="filter-section-standard">
                        <div class="row align-items-end">
                            <!-- Búsqueda General -->
                            <div class="col-md-3">
                                <label class="form-label-standard">Búsqueda General</label>
                                <input type="text" 
                                       id="filtro-busqueda-estandar" 
                                       class="form-control form-control-standard" 
                                       placeholder="Buscar por cédula, nombre, proceso...">
                            </div>
                            
                            <!-- Filtro por Gerencia -->
                            <div class="col-md-3">
                                <label class="form-label-standard">Gerencia</label>
                                <select id="filtro-gerencia-estandar" 
                                        class="form-control form-control-standard">
                                    <option value="">Todas las gerencias</option>
                                </select>
                            </div>
                            
                            <!-- Filtro por Estado -->
                            <div class="col-md-2">
                                <label class="form-label-standard">Estado</label>
                                <select id="filtro-estado-eep" 
                                        class="form-control form-control-standard">
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="evaluado">Evaluado</option>
                                    <option value="aprobado">Aprobado</option>
                                    <option value="rechazado">Rechazado</option>
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
                            <div class="col-md-2 text-right">
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
                                    <th>ID</th>
                                    <th>Empleado</th>
                                    <th>Cédula</th>
                                    <th>Proceso</th>
                                    <th>Gerencia</th>
                                    <th>Fecha Solicitud</th>
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
</div>

<!-- MODAL DE EVALUACIÓN -->
<div class="modal fade" id="modalEvaluacionEEP" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-check"></i> Evaluación de Examen Ocupacional
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="contenido-evaluacion">
                    <!-- El contenido se cargará dinámicamente -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Función requerida para renderizar cada fila de la tabla
function renderizarFilaTablaPersonalizada(item) {
    const diasTranscurridos = TablaEstandarJanus.calcularDiasTranscurridos(item.fecha_solicitud);
    const badgeDias = TablaEstandarJanus.generarBadgeDias(diasTranscurridos);
    
    // Determinar clase del estado
    let claseEstado = 'badge-secondary';
    let textoEstado = item.estado || 'Pendiente';
    
    switch(textoEstado.toLowerCase()) {
        case 'pendiente':
            claseEstado = 'badge-warning';
            break;
        case 'evaluado':
            claseEstado = 'badge-info';
            break;
        case 'aprobado':
            claseEstado = 'badge-success';
            break;
        case 'rechazado':
            claseEstado = 'badge-danger';
            break;
    }
    
    const estadoBadge = `<span class="badge ${claseEstado}">${textoEstado}</span>`;
    
    return `
        <tr class="table-row-hover-standard" data-id="${item.id}">
            <td><strong>${item.id}</strong></td>
            <td>${item.nombre_empleado || '-'}</td>
            <td>${item.cedula || '-'}</td>
            <td>${item.proceso || '-'}</td>
            <td>${item.gerencia || '-'}</td>
            <td>${TablaEstandarJanus.formatearFecha(item.fecha_solicitud)}</td>
            <td>${badgeDias}</td>
            <td>${estadoBadge}</td>
            <td>
                ${TablaEstandarJanus.generarBotonAccion('Evaluar', 'btn-success-standard', `abrirModalEvaluacion(${item.id})`, 'fas fa-user-md')}
                ${TablaEstandarJanus.generarBotonAccion('Ver', 'btn-info-standard', `verDetalle(${item.id})`, 'fas fa-eye')}
            </td>
        </tr>
    `;
}

// Configuración personalizada para esta página
$(document).ready(function() {
    // Inicializar sistema con configuración personalizada
    TablaEstandarJanus.inicializar({
        rowsPerPage: 25
    });
    
    // Configurar eventos adicionales
    configurarEventosPersonalizados();
    
    // Cargar datos iniciales
    cargarDatosEEP();
});

// Configurar eventos específicos de EEP
function configurarEventosPersonalizados() {
    // Evento de filtro por estado
    $('#filtro-estado-eep').on('change', function() {
        aplicarFiltrosEEP();
    });
}

// Aplicar filtros específicos de EEP
function aplicarFiltrosEEP() {
    const textoBusqueda = $('#filtro-busqueda-estandar').val().toLowerCase().trim();
    const gerenciaSeleccionada = $('#filtro-gerencia-estandar').val();
    const estadoSeleccionado = $('#filtro-estado-eep').val();
    
    let datosFiltrados = [...TablaEstandarJanus.datosOriginales];
    
    // Filtro por texto de búsqueda
    if (textoBusqueda) {
        datosFiltrados = datosFiltrados.filter(item => {
            return Object.values(item).some(valor => {
                if (valor === null || valor === undefined) return false;
                return String(valor).toLowerCase().includes(textoBusqueda);
            });
        });
    }
    
    // Filtro por gerencia
    if (gerenciaSeleccionada) {
        datosFiltrados = datosFiltrados.filter(item => {
            return String(item.gerencia_id) === String(gerenciaSeleccionada);
        });
    }
    
    // Filtro por estado
    if (estadoSeleccionado) {
        datosFiltrados = datosFiltrados.filter(item => {
            return String(item.estado).toLowerCase() === estadoSeleccionado.toLowerCase();
        });
    }
    
    TablaEstandarJanus.renderizarTabla(datosFiltrados);
}

// Sobrescribir función de filtros estándar para incluir filtro de estado
TablaEstandarJanus.aplicarFiltros = aplicarFiltrosEEP;

// Función para cargar datos específicos de EEP
function cargarDatosEEP() {
    TablaEstandarJanus.mostrarCarga();
    
    enviarPeticion('procesos', 'obtenerEEPPendientes', {}, function(respuesta) {
        if (respuesta.success && respuesta.data) {
            TablaEstandarJanus.cargarDatos(respuesta.data);
        } else {
            console.error('Error al cargar datos EEP:', respuesta.message);
            TablaEstandarJanus.cargarDatos([]);
        }
    });
}

// Función para abrir modal de evaluación
function abrirModalEvaluacion(id) {
    $('#contenido-evaluacion').html(`
        <div class="text-center p-4">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2">Cargando información del examen...</p>
        </div>
    `);
    
    $('#modalEvaluacionEEP').modal('show');
    
    // Cargar contenido del modal
    enviarPeticion('procesos', 'obtenerDetalleEEP', {id: id}, function(respuesta) {
        if (respuesta.success && respuesta.data) {
            renderizarContenidoEvaluacion(respuesta.data);
        } else {
            $('#contenido-evaluacion').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Error al cargar los datos: ${respuesta.message || 'Error desconocido'}
                </div>
            `);
        }
    });
}

// Renderizar contenido de evaluación
function renderizarContenidoEvaluacion(datos) {
    const contenido = `
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-user"></i> Información del Empleado</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Nombre:</strong> ${datos.nombre_empleado || '-'}</p>
                        <p><strong>Cédula:</strong> ${datos.cedula || '-'}</p>
                        <p><strong>Cargo:</strong> ${datos.cargo || '-'}</p>
                        <p><strong>Gerencia:</strong> ${datos.gerencia || '-'}</p>
                        <p><strong>Fecha Solicitud:</strong> ${TablaEstandarJanus.formatearFecha(datos.fecha_solicitud)}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="fas fa-clipboard-list"></i> Proceso y Estado</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Proceso:</strong> ${datos.proceso || '-'}</p>
                        <p><strong>Estado Actual:</strong> ${datos.estado || 'Pendiente'}</p>
                        <p><strong>Observaciones:</strong> ${datos.observaciones || 'Sin observaciones'}</p>
                        <p><strong>Días Transcurridos:</strong> ${TablaEstandarJanus.calcularDiasTranscurridos(datos.fecha_solicitud)} días</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-stethoscope"></i> Realizar Evaluación</h6>
                    </div>
                    <div class="card-body">
                        <form id="formEvaluacionEEP">
                            <input type="hidden" id="evaluar_id" value="${datos.id}">
                            
                            <div class="form-group">
                                <label class="form-label-standard">Resultado de la Evaluación</label>
                                <select id="resultado_evaluacion" class="form-control form-control-standard" required>
                                    <option value="">Seleccione un resultado</option>
                                    <option value="aprobado">Aprobado - Apto para el cargo</option>
                                    <option value="aprobado_restricciones">Aprobado con Restricciones</option>
                                    <option value="rechazado">No Apto para el cargo</option>
                                    <option value="pendiente_examenes">Pendiente - Exámenes adicionales</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label-standard">Observaciones de la Evaluación</label>
                                <textarea id="observaciones_evaluacion" 
                                          class="form-control form-control-standard" 
                                          rows="4" 
                                          placeholder="Ingrese observaciones detalladas sobre la evaluación..."></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label-standard">Recomendaciones Médicas</label>
                                <textarea id="recomendaciones_medicas" 
                                          class="form-control form-control-standard" 
                                          rows="3" 
                                          placeholder="Ingrese recomendaciones médicas si aplica..."></textarea>
                            </div>
                            
                            <div class="text-right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-success btn-action-standard">
                                    <i class="fas fa-save"></i> Guardar Evaluación
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#contenido-evaluacion').html(contenido);
    
    // Configurar evento del formulario
    $('#formEvaluacionEEP').on('submit', function(e) {
        e.preventDefault();
        guardarEvaluacionEEP();
    });
}

// Guardar evaluación
function guardarEvaluacionEEP() {
    const datos = {
        id: $('#evaluar_id').val(),
        resultado: $('#resultado_evaluacion').val(),
        observaciones: $('#observaciones_evaluacion').val(),
        recomendaciones: $('#recomendaciones_medicas').val()
    };
    
    if (!datos.resultado) {
        alert('Por favor seleccione un resultado para la evaluación');
        return;
    }
    
    enviarPeticion('procesos', 'guardarEvaluacionEEP', datos, function(respuesta) {
        if (respuesta.success) {
            $('#modalEvaluacionEEP').modal('hide');
            
            // Mostrar mensaje de éxito
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Evaluación Guardada',
                    text: 'La evaluación se ha guardado correctamente',
                    timer: 2000
                });
            } else {
                alert('Evaluación guardada correctamente');
            }
            
            // Recargar datos
            cargarDatosEEP();
        } else {
            alert('Error al guardar la evaluación: ' + (respuesta.message || 'Error desconocido'));
        }
    });
}

// Función para ver detalle
function verDetalle(id) {
    console.log('Ver detalle de EEP:', id);
    // Implementar según necesidades
}

// Limpiar filtros incluyendo el filtro de estado
$('#btn-limpiar-filtros-estandar').on('click', function() {
    $('#filtro-estado-eep').val('');
});
</script>

<?php require('views/footer.php');?>