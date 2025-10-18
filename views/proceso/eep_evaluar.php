<?php require('views/header.php');?>

<link rel="stylesheet" href="dist/css/proceso-moderno.css">
<style>
.table-row-hover {
    transition: all 0.3s ease;
    cursor: pointer;
}

.table-row-hover:hover {
    background-color: #f8f9fa !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.modern-table {
    font-size: 0.9rem;
}

.modern-table thead th {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    color: #333;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    border: 2px solid #000;
    border-bottom: 3px solid #000;
    position: sticky;
    top: 0;
    z-index: 10;
}

.badge-dias {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.4rem 0.8rem;
}

.btn-evaluar {
    transition: all 0.2s ease;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    background-color: white;
    color: #000;
    border: 2px solid #000;
}

.btn-evaluar:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    background-color: #f8f9fa;
    color: #000;
    border-color: #000;
}

.btn-evaluar:focus,
.btn-evaluar:active {
    background-color: white;
    color: #000;
    border-color: #000;
    box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1);
}

.modal-header-custom {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-bottom: none;
}

.modal-header-custom .modal-title {
    font-weight: 700;
    display: flex;
    align-items: center;
}

.modal-header-custom .btn-close {
    filter: invert(1);
}

.form-modern .form-control {
    border: 2px solid #e3e6f0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.form-modern .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    transform: translateY(-1px);
}

.form-modern .form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.info-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.status-indicator {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 0.5rem;
}

.status-pendiente { background-color: #ffc107; }
.status-evaluado { background-color: #28a745; }
.status-no-apto { background-color: #dc3545; }

.document-panel {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 0.75rem;
    margin-top: 0.5rem;
}

@media (max-width: 768px) {
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .modern-table {
        font-size: 0.8rem;
    }
    
    .modern-table thead th {
        padding: 0.5rem 0.3rem;
        font-size: 0.7rem;
    }
}

.filter-section {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 2px solid #000;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-control-modern {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.2s ease;
    background-color: white;
    height: auto;
}

.form-control-modern:focus {
    border-color: #000;
    box-shadow: 0 0 0 0.1rem rgba(0, 0, 0, 0.1);
    outline: none;
}

.form-control-modern:hover {
    border-color: #adb5bd;
}

.btn-filter-clear {
    border: 1px solid #000;
    background-color: white;
    color: #000;
    font-weight: 600;
    border-radius: 6px;
    padding: 0.5rem;
    transition: all 0.2s ease;
    font-size: 0.85rem;
    height: calc(1.5em + 1rem + 2px);
}

.btn-filter-clear:hover {
    background-color: #f8f9fa;
    border-color: #000;
    color: #000;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-label {
    font-size: 0.75rem;
    margin-bottom: 0.25rem;
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.badge-count {
    font-size: 1.1rem;
    font-weight: 700;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    min-width: 40px;
    text-align: center;
}

/* Ocultar controles de DataTables que no necesitamos */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info {
    display: none;
}

.dataTables_wrapper .dataTables_paginate {
    padding-top: 1rem;
    text-align: center;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 0.375rem 0.75rem;
    margin: 0 2px;
    background: white;
    color: #495057;
    text-decoration: none;
    transition: all 0.2s ease;
    font-weight: 500;
    font-size: 0.875rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f8f9fa;
    border-color: #000;
    color: #000;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #000;
    border-color: #000;
    color: white;
    font-weight: 700;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    color: #6c757d;
    background: #fff;
    border-color: #dee2e6;
    cursor: not-allowed;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
    box-shadow: none;
    background: #fff;
    border-color: #dee2e6;
}

.badge-count {
    font-size: 1.2rem;
    font-weight: 700;
}

.form-label {
    font-size: 0.85rem;
    margin-bottom: 0.3rem;
    font-weight: 600;
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-stethoscope text-success mr-2"></i>
                        Evaluar Examen Preocupacional
                        <span class="badge badge-secondary badge-pill ml-2" id="lblCount">0</span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">EEP Evaluar</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h3 class="card-title d-flex align-items-center">
                                <i class="fas fa-clipboard-list text-success mr-2"></i>
                                <strong>Solicitudes en Evaluación de Examen Preocupacional</strong>
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-success btn-sm" id="btnRefrescarLista">
                                    <i class="fas fa-sync-alt mr-1"></i> Actualizar Lista
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Panel de Filtros y Búsqueda Integrado -->
                            <div class="filter-section">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label for="buscarTexto" class="form-label">
                                                <i class="fas fa-search text-primary mr-1"></i>Buscar
                                            </label>
                                            <input type="text" class="form-control form-control-modern" id="buscarTexto" 
                                                   placeholder="Nombre, cédula, gerencia...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label for="filtroGerencia" class="form-label">
                                                <i class="fas fa-building text-success mr-1"></i>Gerencia
                                            </label>
                                            <select class="form-control form-control-modern" id="filtroGerencia">
                                                <option value="">Todas</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-0">
                                            <label for="filtroDias" class="form-label">
                                                <i class="fas fa-clock text-warning mr-1"></i>Días
                                            </label>
                                            <select class="form-control form-control-modern" id="filtroDias">
                                                <option value="">Todos</option>
                                                <option value="critico">Crítico (>15)</option>
                                                <option value="alerta">Alerta (10-15)</option>
                                                <option value="normal">Normal (<10)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-0">
                                            <label for="filtroEstado" class="form-label">
                                                <i class="fas fa-check-circle text-info mr-1"></i>Estado
                                            </label>
                                            <select class="form-control form-control-modern" id="filtroEstado">
                                                <option value="">Todos</option>
                                                <option value="pendiente">Pendiente</option>
                                                <option value="evaluado">Evaluado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-0">
                                            <label class="form-label d-block">&nbsp;</label>
                                            <div class="d-flex">
                                                <button type="button" class="btn btn-filter-clear flex-fill mr-1" id="btnLimpiarFiltros" title="Limpiar filtros">
                                                    <i class="fas fa-eraser"></i>
                                                </button>
                                                <span class="badge badge-primary badge-count ml-1 d-flex align-items-center" id="lblCount">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover modern-table mb-0" style="width: 100%;" id="tablaEEP">
                                    <thead>
                                        <tr>
                                            <th style="width: 8%;"><i class="fas fa-hashtag mr-1"></i>Proceso</th>
                                            <th style="width: 8%;"><i class="fas fa-file mr-1"></i>Solicitud</th>
                                            <th style="width: 25%;"><i class="fas fa-user mr-1"></i>Nombre Completo</th>
                                            <th style="width: 12%;"><i class="fas fa-id-card mr-1"></i>Cédula</th>
                                            <th style="width: 18%;"><i class="fas fa-building mr-1"></i>Gerencia</th>
                                            <th style="width: 15%;"><i class="fas fa-graduation-cap mr-1"></i>Profesión</th>
                                            <th class="text-center" style="width: 8%;"><i class="fas fa-clock mr-1"></i>Días</th>
                                            <th class="text-center" style="width: 10%;"><i class="fas fa-cogs mr-1"></i>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-light text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Se muestran las solicitudes en estado <strong>"Examen preocupacional"</strong>. 
                            Use los filtros para encontrar solicitudes específicas.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal de Evaluación -->
<div class="modal fade" id="modalEvaluacion" tabindex="-1" aria-labelledby="modalEvaluacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="modalEvaluacionLabel">
                    <i class="fas fa-stethoscope mr-2"></i>
                    Evaluación de Examen Preocupacional
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Información del Proceso -->
                <div class="info-card">
                    <h6 class="mb-3"><i class="fas fa-info-circle text-primary mr-2"></i>Información del Proceso</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Proceso #:</strong> <span id="modalProceso" class="text-primary">-</span></p>
                            <p class="mb-2"><strong>Nombre:</strong> <span id="modalNombre">-</span></p>
                            <p class="mb-2"><strong>Cédula:</strong> <span id="modalCedula">-</span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Gerencia:</strong> <span id="modalGerencia">-</span></p>
                            <p class="mb-2"><strong>Profesión:</strong> <span id="modalProfesion">-</span></p>
                            <p class="mb-2"><strong>Estado:</strong> <span id="modalEstado" class="badge badge-info">-</span></p>
                        </div>
                    </div>
                </div>

                <!-- Estado del Documento -->
                <div class="document-panel" id="documentPanel" style="display: none;">
                    <h6 class="mb-2"><i class="fas fa-file-medical text-success mr-2"></i>Documento del Examen</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span id="docEstado" class="badge badge-secondary">No encontrado</span>
                            <small class="text-muted ml-2" id="docNota">-</small>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnVerDoc" style="display: none;">
                                <i class="fas fa-eye mr-1"></i>Ver Documento
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnIrDocumentacion">
                                <i class="fas fa-folder-open mr-1"></i>Ir a Documentación
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Formulario de Evaluación -->
                <form id="formEvaluacion" class="form-modern mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fechaEvaluacion" class="form-label">
                                    <i class="fas fa-calendar text-primary mr-1"></i>Fecha de Evaluación
                                </label>
                                <input type="date" class="form-control" id="fechaEvaluacion" name="fecha_eep" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="resultadoEvaluacion" class="form-label">
                                    <i class="fas fa-check-circle text-success mr-1"></i>Resultado de la Evaluación
                                </label>
                                <select class="form-control" id="resultadoEvaluacion" name="resultado_eep" required>
                                    <option value="">Seleccione el resultado...</option>
                                    <option value="apto">✅ Apto para el cargo</option>
                                    <option value="no_apto">❌ No apto para el cargo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="observacionesEvaluacion" class="form-label">
                            <i class="fas fa-comments text-info mr-1"></i>Observaciones Médicas
                        </label>
                        <textarea class="form-control" id="observacionesEvaluacion" name="observaciones_eep" 
                                  rows="4" placeholder="Ingrese observaciones adicionales sobre la evaluación médica..."></textarea>
                        <small class="form-text text-muted">
                            Detalle cualquier consideración médica relevante para el proceso de contratación.
                        </small>
                    </div>
                </form>

                <!-- Evaluación Previa -->
                <div id="evaluacionPrevia" class="alert alert-info" style="display: none;">
                    <h6><i class="fas fa-history mr-2"></i>Evaluación Existente</h6>
                    <p class="mb-2"><strong>Resultado previo:</strong> <span id="resultadoPrevio">-</span></p>
                    <p class="mb-2"><strong>Fecha:</strong> <span id="fechaPrevia">-</span></p>
                    <p class="mb-0"><strong>Observaciones:</strong> <span id="observacionesPrevias">-</span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success" id="btnGuardarEvaluacion">
                    <i class="fas fa-save mr-1"></i>Guardar Evaluación
                </button>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
// Variables globales
var dtLista = null;
var procesoActual = null;
var solicitudActual = null;
var documentoEEP = null;
var datosOriginales = []; // Para filtros locales
var gerenciasDisponibles = [];

function init() {
    // Configurar fecha por defecto (hoy)
    $('#fechaEvaluacion').val(new Date().toISOString().split('T')[0]);
    
    // Cargar gerencias primero
    cargarGerencias();
    
    // Cargar lista inicial
    cargarListaEEP();
    
    // Event listeners
    $('#btnRefrescarLista').on('click', cargarListaEEP);
    $('#btnGuardarEvaluacion').on('click', guardarEvaluacion);
    $('#btnLimpiarFiltros').on('click', limpiarFiltros);
    
    // Filtros y búsqueda en tiempo real
    $('#filtroGerencia, #filtroDias, #filtroEstado').on('change', aplicarFiltros);
    $('#buscarTexto').on('input', aplicarFiltros);
    
    // Inicializar modal con Bootstrap 4
    if (typeof bootstrap === 'undefined') {
        // Fallback para Bootstrap 4
        $('#modalEvaluacion').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    }
}

function cargarGerencias() {
    enviarPeticion('gerencias', 'getGerencias', {criterio: 'rol'}, function(r) {
        if (r.ejecuto && r.data) {
            gerenciasDisponibles = r.data;
            const select = $('#filtroGerencia');
            
            // Limpiar opciones existentes excepto la primera
            select.find('option:not(:first)').remove();
            
            // Agregar gerencias
            r.data.forEach(function(gerencia) {
                select.append(`<option value="${gerencia.nombre}">${gerencia.nombre}</option>`);
            });
        }
    });
}

function cargarListaEEP() {
    // Mostrar loading
    if (dtLista) {
        dtLista.destroy();
        dtLista = null;
    }
    
    $('#tablaEEP tbody').html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin mr-2"></i>Cargando...</td></tr>');
    
    // Obtener solicitudes en estado 6 (Examen preocupacional)
    enviarPeticion('solicitudes', 'getSolicitudes', {criterio: 'todas', estado: 6}, function(r) {
        if (r.ejecuto && r.data) {
            datosOriginales = r.data; // Guardar datos originales
            renderTablaEEP(r.data);
            actualizarContador(r.data.length);
        } else {
            // Fallback: mostrar todas las solicitudes
            enviarPeticion('solicitudes', 'getSolicitudes', {criterio: 'todas'}, function(r2) {
                if (r2.ejecuto && r2.data) {
                    toastr.warning('No se encontraron solicitudes en estado EEP. Mostrando todas para referencia.');
                    datosOriginales = r2.data;
                    renderTablaEEP(r2.data);
                    actualizarContador(r2.data.length);
                } else {
                    toastr.error('No se pudo cargar la lista de solicitudes');
                    datosOriginales = [];
                    renderTablaEEP([]);
                    actualizarContador(0);
                }
            });
        }
    });
}

function renderTablaEEP(data) {
    // Destruir DataTable existente si existe
    if (dtLista) {
        dtLista.destroy();
        dtLista = null;
    }
    
    const tbody = $('#tablaEEP tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="8" class="text-center text-muted py-4"><i class="fas fa-inbox mr-2"></i>No hay solicitudes para evaluar</td></tr>');
        // Reinicializar DataTable incluso cuando no hay datos
        initializeDataTable();
        return;
    }
    
    data.forEach(function(item) {
        const diasColor = item.tiempo > 15 ? 'danger' : (item.tiempo > 10 ? 'warning' : 'success');
        
        const row = `
            <tr class="table-row-hover" data-proceso="${item.idProceso}" data-solicitud="${item.id}">
                <td class="text-center font-weight-bold text-primary">#${item.idProceso}</td>
                <td class="text-center">${item.id}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-circle text-muted mr-2"></i>
                        <span>${item.ps || 'Sin nombre'}</span>
                    </div>
                </td>
                <td><span class="font-weight-medium">${item.cedula || '-'}</span></td>
                <td>
                    <span class="badge badge-light border">${item.gerencia || 'Sin gerencia'}</span>
                </td>
                <td>
                    <small class="text-muted">${item.profesion || 'No especificada'}</small>
                </td>
                <td class="text-center">
                    <span class="badge badge-${diasColor} badge-dias">
                        <i class="fas fa-calendar-day mr-1"></i>${item.tiempo || 0} días
                    </span>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-evaluar" 
                            onclick="abrirModalEvaluacion(${item.idProceso}, ${item.id})"
                            title="Evaluar examen preocupacional">
                        Evaluar
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
    
    // Reinicializar DataTable
    initializeDataTable();
}

function initializeDataTable() {
    // Inicializar DataTable
    dtLista = $('#tablaEEP').DataTable({
        pageLength: 15,
        lengthMenu: [15, 25, 50],
        order: [[6, 'desc']], // Ordenar por días descendente
        searching: false, // Desactivar búsqueda de DataTables
        lengthChange: false, // Ocultar selector de registros por página
        info: false, // Ocultar información "Mostrando X de Y registros"
        language: {
            decimal: "",
            emptyTable: "No hay solicitudes para evaluar",
            paginate: {
                first: "Primero",
                last: "Último", 
                next: "Siguiente",
                previous: "Anterior"
            }
        },
        responsive: true,
        dom: 'rt<"d-flex justify-content-center"p>', // Solo tabla y paginación centrada
        columnDefs: [
            { orderable: false, targets: [7] }, // Columna de acciones no ordenable
            { className: "text-center", targets: [0, 1, 6, 7] }
        ]
    });
}

function aplicarFiltros() {
    const filtroGerencia = $('#filtroGerencia').val().toLowerCase();
    const filtroDias = $('#filtroDias').val();
    const filtroEstado = $('#filtroEstado').val();
    const buscarTexto = $('#buscarTexto').val().toLowerCase().trim();
    
    let datosFiltrados = [...datosOriginales];
    
    // Filtro por texto de búsqueda
    if (buscarTexto) {
        datosFiltrados = datosFiltrados.filter(item => {
            const nombre = String(item.ps || '').toLowerCase();
            const cedula = String(item.cedula || '').toLowerCase();
            const gerencia = String(item.gerencia || '').toLowerCase();
            const profesion = String(item.profesion || '').toLowerCase();
            
            return nombre.includes(buscarTexto) || 
                   cedula.includes(buscarTexto) || 
                   gerencia.includes(buscarTexto) ||
                   profesion.includes(buscarTexto);
        });
    }
    
    // Filtro por gerencia
    if (filtroGerencia) {
        datosFiltrados = datosFiltrados.filter(item => 
            String(item.gerencia || '').toLowerCase().includes(filtroGerencia)
        );
    }
    
    // Filtro por días
    if (filtroDias) {
        datosFiltrados = datosFiltrados.filter(item => {
            const dias = parseInt(item.tiempo) || 0;
            switch(filtroDias) {
                case 'critico': return dias > 15;
                case 'alerta': return dias >= 10 && dias <= 15;
                case 'normal': return dias < 10;
                default: return true;
            }
        });
    }
    
    // Filtro por estado de evaluación
    if (filtroEstado) {
        // Necesitamos obtener detalles de procesos para saber si están evaluados
        cargarDetallesParaFiltro(datosFiltrados, filtroEstado);
        return; // La función de callback manejará el renderizado
    }
    
    renderTablaEEP(datosFiltrados);
    actualizarContador(datosFiltrados.length);
}

function cargarDetallesParaFiltro(datos, filtroEstado) {
    if (datos.length === 0) {
        renderTablaEEP([]);
        actualizarContador(0);
        return;
    }
    
    let procesosPendientes = datos.length;
    let resultados = [];
    
    datos.forEach(function(item) {
        enviarPeticion('procesos', 'getProcesoDetalle', {id: item.idProceso}, function(r) {
            procesosPendientes--;
            
            if (r.ejecuto && r.data && r.data.length > 0) {
                const detalle = r.data[0];
                const tieneEvaluacion = detalle.resultado_eep ? true : false;
                
                // Aplicar filtro de estado
                if (filtroEstado === 'pendiente' && !tieneEvaluacion) {
                    resultados.push(item);
                } else if (filtroEstado === 'evaluado' && tieneEvaluacion) {
                    resultados.push(item);
                }
            }
            
            // Cuando se complete el último proceso
            if (procesosPendientes === 0) {
                renderTablaEEP(resultados);
                actualizarContador(resultados.length);
            }
        });
    });
}

function limpiarFiltros() {
    $('#filtroGerencia').val('');
    $('#filtroDias').val('');
    $('#filtroEstado').val('');
    $('#buscarTexto').val('');
    
    renderTablaEEP(datosOriginales);
    actualizarContador(datosOriginales.length);
}

function actualizarContador(cantidad) {
    $('#lblCount').text(cantidad);
}

function actualizarResumen(datos) {
    const total = datos.length;
    const criticos = datos.filter(item => parseInt(item.tiempo) > 15).length;
    const gerenciasUnicas = [...new Set(datos.map(item => item.gerencia).filter(g => g))];
    
    $('#totalRegistros').text(total);
    $('#criticos').text(criticos);
    $('#gerenciasActivas').text(gerenciasUnicas.length);
    
    // Para pendientes de evaluación, necesitamos hacer consultas adicionales
    // Por simplicidad, asumimos que todos en estado 6 están pendientes
    $('#pendientesEval').text(total);
    
    // Actualizar contador en el header
    actualizarContador(total);
}

function abrirModalEvaluacion(idProceso, idSolicitud) {
    procesoActual = idProceso;
    solicitudActual = idSolicitud;
    
    // Limpiar formulario
    $('#formEvaluacion')[0].reset();
    $('#fechaEvaluacion').val(new Date().toISOString().split('T')[0]);
    $('#evaluacionPrevia').hide();
    $('#documentPanel').hide();
    $('#btnGuardarEvaluacion').prop('disabled', false);
    
    // Cargar datos del proceso
    cargarDatosProceso(idProceso, idSolicitud);
    
    // Mostrar modal
    $('#modalEvaluacion').modal('show');
}

function cargarDatosProceso(idProceso, idSolicitud) {
    // Obtener información del proceso
    enviarPeticion('procesos', 'getProcesos', {criterio: 'id', id: idProceso}, function(r) {
        if (r.ejecuto && r.data && r.data.length > 0) {
            const proceso = r.data[0];
            
            // Llenar información básica
            $('#modalProceso').text('#' + proceso.id);
            $('#modalNombre').text(proceso.ps || 'Sin nombre');
            $('#modalCedula').text(proceso.cedula || '-');
            $('#modalGerencia').text(proceso.gerencia || 'Sin gerencia');
            $('#modalProfesion').text(proceso.profesion || 'No especificada');
            
            const estadoTexto = (typeof estados !== 'undefined' && estados[proceso.estado]) 
                ? estados[proceso.estado] 
                : 'Estado ' + proceso.estado;
            $('#modalEstado').text(estadoTexto).removeClass().addClass('badge badge-info');
            
            // Cargar detalle del proceso para evaluación previa
            cargarDetalleEvaluacion(idProceso);
            
            // Cargar documento EEP
            cargarDocumentoEEP(idProceso, proceso.contratista);
        } else {
            toastr.error('No se pudo cargar la información del proceso');
        }
    });
}

function cargarDetalleEvaluacion(idProceso) {
    enviarPeticion('procesos', 'getProcesoDetalle', {id: idProceso}, function(r) {
        if (r.ejecuto && r.data && r.data.length > 0) {
            const detalle = r.data[0];
            
            if (detalle.resultado_eep) {
                // Ya tiene evaluación previa
                $('#resultadoPrevio').text(detalle.resultado_eep === 'apto' ? 'Apto' : 'No apto');
                $('#fechaPrevia').text(detalle.fecha_eep || 'Sin fecha');
                $('#observacionesPrevias').text(detalle.observaciones_eep || 'Sin observaciones');
                
                // Pre-llenar formulario
                if (detalle.fecha_eep) $('#fechaEvaluacion').val(detalle.fecha_eep);
                if (detalle.resultado_eep) $('#resultadoEvaluacion').val(detalle.resultado_eep);
                if (detalle.observaciones_eep) $('#observacionesEvaluacion').val(detalle.observaciones_eep);
                
                $('#evaluacionPrevia').show();
                
                // Cambiar texto del botón
                $('#btnGuardarEvaluacion').html('<i class="fas fa-edit mr-1"></i>Actualizar Evaluación');
            } else {
                $('#btnGuardarEvaluacion').html('<i class="fas fa-save mr-1"></i>Guardar Evaluación');
            }
        }
    });
}

function cargarDocumentoEEP(idProceso, idContratista) {
    // Buscar documento de examen preocupacional
    const tiposDocumento = [
        'Certificados Médicos de Salud Preocupacional',
        'Certificado médico de salud preocupacional', 
        'Examen preocupacional',
        'Examen Preocupacional'
    ];
    
    let documentoEncontrado = false;
    let intentos = 0;
    
    function buscarTipoDocumento(index) {
        if (index >= tiposDocumento.length) {
            // No se encontró documento específico, buscar cualquiera
            buscarCualquierDocumento();
            return;
        }
        
        const nombreTipo = tiposDocumento[index];
        enviarPeticion('documentosTipo', 'select', {info: {nombre: nombreTipo}}, function(r) {
            if (r.ejecuto && r.data && r.data.length > 0) {
                const tipoId = r.data[0].id;
                enviarPeticion('documentos', 'select', {
                    info: {
                        contratista: idContratista,
                        fk_procesos: idProceso,
                        fk_documentos_tipo: tipoId
                    }
                }, function(r2) {
                    if (r2.ejecuto && r2.data && r2.data.length > 0) {
                        mostrarDocumento(r2.data[0], 'Documento específico encontrado');
                        documentoEncontrado = true;
                    } else {
                        buscarTipoDocumento(index + 1);
                    }
                });
            } else {
                buscarTipoDocumento(index + 1);
            }
        });
    }
    
    function buscarCualquierDocumento() {
        if (documentoEncontrado) return;
        
        enviarPeticion('documentos', 'select', {
            info: {contratista: idContratista, fk_procesos: idProceso},
            orden: 'id DESC'
        }, function(r) {
            if (r.ejecuto && r.data && r.data.length > 0) {
                mostrarDocumento(r.data[0], 'Documento genérico encontrado');
            } else {
                mostrarSinDocumento();
            }
        });
    }
    
    buscarTipoDocumento(0);
}

function mostrarDocumento(doc, nota) {
    documentoEEP = doc;
    
    const estadoTexto = (typeof estadoDocumentos !== 'undefined' && estadoDocumentos[doc.estado])
        ? estadoDocumentos[doc.estado]
        : 'Estado ' + doc.estado;
    
    const estadoColor = doc.estado == 2 ? 'success' : (doc.estado == 3 ? 'danger' : 'secondary');
    
    $('#docEstado').text(estadoTexto).removeClass().addClass(`badge badge-${estadoColor}`);
    $('#docNota').text(nota);
    
    // Verificar si existe archivo físico
    enviarPeticion('archivos', 'existDocumento', {id: doc.id}, function(r) {
        if (r.ejecuto) {
            $('#btnVerDoc').show().off('click').on('click', function() {
                downloadDocument(doc.id);
            });
        } else {
            $('#btnVerDoc').hide();
            $('#docNota').text(nota + ' (sin archivo físico)');
        }
    });
    
    $('#btnIrDocumentacion').off('click').on('click', function() {
        irADocumentacion();
    });
    
    $('#documentPanel').show();
}

function mostrarSinDocumento() {
    $('#docEstado').text('No encontrado').removeClass().addClass('badge badge-warning');
    $('#docNota').text('No se encontró documento de examen preocupacional');
    $('#btnVerDoc').hide();
    
    $('#btnIrDocumentacion').off('click').on('click', function() {
        irADocumentacion();
    });
    
    $('#documentPanel').show();
}

function guardarEvaluacion() {
    if (!procesoActual) {
        toastr.error('No hay proceso seleccionado');
        return;
    }
    
    // Validar formulario
    const fecha = $('#fechaEvaluacion').val();
    const resultado = $('#resultadoEvaluacion').val();
    
    if (!fecha || !resultado) {
        toastr.warning('Por favor complete la fecha y el resultado de la evaluación');
        return;
    }
    
    // Confirmar evaluación
    const resultadoTexto = resultado === 'apto' ? 'APTO' : 'NO APTO';
    const icono = resultado === 'apto' ? 'success' : 'warning';
    
    Swal.fire({
        title: '¿Confirmar evaluación?',
        html: `Se marcará al candidato como <strong>${resultadoTexto}</strong><br>Esta acción avanzará el proceso al siguiente estado.`,
        icon: icono,
        showCancelButton: true,
        confirmButtonColor: resultado === 'apto' ? '#28a745' : '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, guardar evaluación',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            ejecutarGuardado();
        }
    });
}

function ejecutarGuardado() {
    $('#btnGuardarEvaluacion').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Guardando...');
    
    const datos = {
        idProceso: procesoActual,
        fecha_eep: $('#fechaEvaluacion').val(),
        resultado_eep: $('#resultadoEvaluacion').val(),
        observaciones_eep: $('#observacionesEvaluacion').val()
    };
    
    enviarPeticion('procesos', 'evaluarEEP', datos, function(r) {
        if (r.ejecuto) {
            // Éxito
            Swal.fire({
                icon: 'success',
                title: 'Evaluación guardada',
                text: 'La evaluación se guardó correctamente y el proceso avanzó al siguiente estado.',
                timer: 3000,
                timerProgressBar: true
            }).then(() => {
                $('#modalEvaluacion').modal('hide');
                // Recargar datos originales y aplicar filtros
                cargarListaEEP();
            });
            
            // Actualizar estado del documento si corresponde
            actualizarDocumentoSegunEvaluacion(datos.resultado_eep, datos.observaciones_eep);
            
        } else {
            toastr.error(r.mensajeError || 'Error al guardar la evaluación');
            $('#btnGuardarEvaluacion').prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Guardar Evaluación');
        }
    });
}

function actualizarDocumentoSegunEvaluacion(resultado, observaciones) {
    if (!documentoEEP) return;
    
    const estadoTarget = resultado === 'apto' ? 2 : 3; // 2=Aceptado, 3=Rechazado
    
    if (documentoEEP.estado !== estadoTarget) {
        const obsDoc = resultado === 'apto' 
            ? (observaciones || 'Aceptado tras evaluación EEP')
            : (observaciones || 'Rechazado tras evaluación EEP');
        
        enviarPeticion('documentos', 'setEstado', {
            id: documentoEEP.id,
            estado: estadoTarget,
            observaciones: obsDoc
        }, function(r) {
            if (r.ejecuto) {
                console.log('Documento actualizado automáticamente');
            }
        });
    }
}

function irADocumentacion() {
    try {
        if (solicitudActual && window.sessionStorage) {
            sessionStorage.setItem('solicitud', JSON.stringify(solicitudActual));
            window.location.href = 'proceso/documentacionDetalle/';
        } else {
            window.location.href = 'proceso/documentacion/';
        }
    } catch(e) {
        window.location.href = 'proceso/documentacion/';
    }
}

// Cerrar modal al presionar Escape
$(document).on('keydown', function(e) {
    if (e.key === 'Escape' && $('#modalEvaluacion').hasClass('show')) {
        $('#modalEvaluacion').modal('hide');
    }
});
</script>
</body>
</html>