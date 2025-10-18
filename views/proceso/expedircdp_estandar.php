<?php require('views/header.php');?>

<!-- PÁGINA EXPEDIR CDP CON SISTEMA ESTÁNDAR -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice-dollar"></i> Expedición de CDP (Certificado de Disponibilidad Presupuestal)
                    </h3>
                    <p class="text-sm mb-0">Gestión y expedición de certificados de disponibilidad presupuestal.</p>
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
                                       placeholder="Buscar por proceso, necesidad, valor...">
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
                                <label class="form-label-standard">Estado CDP</label>
                                <select id="filtro-estado-cdp" 
                                        class="form-control form-control-standard">
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="expedido">Expedido</option>
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
                                <span class="form-label-standard d-block">CDPs</span>
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
                                    <th>Necesidad</th>
                                    <th>Gerencia</th>
                                    <th>Valor CDP</th>
                                    <th>Estado</th>
                                    <th>Fecha Solicitud</th>
                                    <th>Días Transcurridos</th>
                                    <th>Responsable</th>
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

<!-- MODAL PARA EXPEDICIÓN DE CDP -->
<div class="modal fade" id="modalExpedirCDP" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-invoice-dollar"></i> Expedición de CDP
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formExpedirCDP">
                    <input type="hidden" id="cdp_proceso_id" name="proceso_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label-standard">Número CDP</label>
                                <input type="text" id="cdp_numero" name="numero_cdp" class="form-control form-control-standard" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label-standard">Fecha Expedición</label>
                                <input type="date" id="cdp_fecha" name="fecha_expedicion" class="form-control form-control-standard" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label-standard">Valor CDP</label>
                                <input type="number" id="cdp_valor" name="valor_cdp" class="form-control form-control-standard" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label-standard">Vigencia</label>
                                <select id="cdp_vigencia" name="vigencia" class="form-control form-control-standard" required>
                                    <option value="">Seleccione vigencia</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label-standard">Objeto del Gasto</label>
                        <textarea id="cdp_objeto" name="objeto_gasto" class="form-control form-control-standard" rows="3" placeholder="Descripción del objeto del gasto..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label-standard">Observaciones</label>
                        <textarea id="cdp_observaciones" name="observaciones" class="form-control form-control-standard" rows="2" placeholder="Observaciones adicionales..."></textarea>
                    </div>
                    
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success btn-action-standard">
                            <i class="fas fa-save"></i> Expedir CDP
                        </button>
                    </div>
                </form>
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
        case 'expedido':
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
    
    // Formatear valor CDP
    const valorFormateado = formatearMoneda(item.valor_cdp || 0);
    
    return `
        <tr class="table-row-hover-standard" data-id="${item.id}">
            <td><strong>${item.id}</strong></td>
            <td>${item.necesidad || '-'}</td>
            <td>${item.gerencia || '-'}</td>
            <td class="text-right font-weight-bold text-success">${valorFormateado}</td>
            <td>${estadoBadge}</td>
            <td>${TablaEstandarJanus.formatearFecha(item.fecha_solicitud)}</td>
            <td>${badgeDias}</td>
            <td>${item.responsable || '-'}</td>
            <td>
                ${TablaEstandarJanus.generarBotonAccion('Expedir', 'btn-success-standard', `abrirModalExpedir(${item.id})`, 'fas fa-file-invoice-dollar')}
                ${TablaEstandarJanus.generarBotonAccion('Ver', 'btn-info-standard', `verDetalleCDP(${item.id})`, 'fas fa-eye')}
                ${item.estado === 'expedido' ? TablaEstandarJanus.generarBotonAccion('Descargar', 'btn-primary', `descargarCDP(${item.id})`, 'fas fa-download') : ''}
            </td>
        </tr>
    `;
}

// Configuración personalizada para esta página
$(document).ready(function() {
    TablaEstandarJanus.inicializar({
        rowsPerPage: 25
    });
    
    // Configurar eventos adicionales
    configurarEventosPersonalizados();
    
    // Cargar datos iniciales
    cargarDatosCDP();
    
    // Configurar formulario de expedición
    $('#formExpedirCDP').on('submit', function(e) {
        e.preventDefault();
        expedirCDP();
    });
});

// Configurar eventos específicos de Expedir CDP
function configurarEventosPersonalizados() {
    // Evento de filtro por estado
    $('#filtro-estado-cdp').on('change', function() {
        aplicarFiltrosCDP();
    });
}

// Aplicar filtros específicos de CDP
function aplicarFiltrosCDP() {
    const textoBusqueda = $('#filtro-busqueda-estandar').val().toLowerCase().trim();
    const gerenciaSeleccionada = $('#filtro-gerencia-estandar').val();
    const estadoSeleccionado = $('#filtro-estado-cdp').val();
    
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
TablaEstandarJanus.aplicarFiltros = aplicarFiltrosCDP;

// Función para cargar datos específicos de CDP
function cargarDatosCDP() {
    TablaEstandarJanus.mostrarCarga();
    
    enviarPeticion('procesos', 'obtenerCDPPendientes', {}, function(respuesta) {
        if (respuesta.success && respuesta.data) {
            TablaEstandarJanus.cargarDatos(respuesta.data);
        } else {
            console.error('Error al cargar datos CDP:', respuesta.message);
            TablaEstandarJanus.cargarDatos([]);
        }
    });
}

// Función para formatear moneda
function formatearMoneda(valor) {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
    }).format(valor);
}

// Función para abrir modal de expedición
function abrirModalExpedir(id) {
    $('#cdp_proceso_id').val(id);
    
    // Limpiar formulario
    $('#formExpedirCDP')[0].reset();
    $('#cdp_proceso_id').val(id);
    
    // Cargar datos del proceso
    enviarPeticion('procesos', 'obtenerDetalleCDP', {id: id}, function(respuesta) {
        if (respuesta.success && respuesta.data) {
            const datos = respuesta.data;
            
            // Pre-llenar algunos campos si están disponibles
            if (datos.valor_estimado) {
                $('#cdp_valor').val(datos.valor_estimado);
            }
            
            if (datos.objeto_necesidad) {
                $('#cdp_objeto').val(datos.objeto_necesidad);
            }
            
            // Establecer fecha actual
            $('#cdp_fecha').val(new Date().toISOString().split('T')[0]);
        }
    });
    
    $('#modalExpedirCDP').modal('show');
}

// Función para expedir CDP
function expedirCDP() {
    const formData = new FormData($('#formExpedirCDP')[0]);
    
    enviarPeticion('procesos', 'expedirCDP', Object.fromEntries(formData), function(respuesta) {
        if (respuesta.success) {
            $('#modalExpedirCDP').modal('hide');
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'CDP Expedido',
                    text: 'El CDP se ha expedido correctamente',
                    timer: 2000
                });
            } else {
                alert('CDP expedido correctamente');
            }
            
            // Recargar datos
            cargarDatosCDP();
        } else {
            alert('Error al expedir CDP: ' + (respuesta.message || 'Error desconocido'));
        }
    });
}

// Función para ver detalle de CDP
function verDetalleCDP(id) {
    console.log('Ver detalle de CDP:', id);
    // Implementar según necesidades
}

// Función para descargar CDP
function descargarCDP(id) {
    window.open(`controllers/procesos.php?accion=descargarCDP&id=${id}`, '_blank');
}

// Limpiar filtros incluyendo el filtro de estado
$('#btn-limpiar-filtros-estandar').on('click', function() {
    $('#filtro-estado-cdp').val('');
});
</script>

<?php require('views/footer.php');?>