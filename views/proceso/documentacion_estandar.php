<?php require('views/header.php');?>

<!-- PÁGINA DOCUMENTACIÓN CON SISTEMA ESTÁNDAR -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt"></i> Gestión de Documentación
                    </h3>
                    <p class="text-sm mb-0">Administración de documentos y archivos del sistema.</p>
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
                                       placeholder="Buscar por nombre, tipo, descripción...">
                            </div>
                            
                            <!-- Filtro por Gerencia -->
                            <div class="col-md-3">
                                <label class="form-label-standard">Gerencia</label>
                                <select id="filtro-gerencia-estandar" 
                                        class="form-control form-control-standard">
                                    <option value="">Todas las gerencias</option>
                                </select>
                            </div>
                            
                            <!-- Filtro por Tipo -->
                            <div class="col-md-2">
                                <label class="form-label-standard">Tipo</label>
                                <select id="filtro-tipo-documento" 
                                        class="form-control form-control-standard">
                                    <option value="">Todos los tipos</option>
                                    <option value="pdf">PDF</option>
                                    <option value="doc">Word</option>
                                    <option value="xls">Excel</option>
                                    <option value="img">Imagen</option>
                                    <option value="otro">Otro</option>
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
                                <span class="form-label-standard d-block">Documentos</span>
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
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Tamaño</th>
                                    <th>Proceso</th>
                                    <th>Fecha Subida</th>
                                    <th>Días</th>
                                    <th>Usuario</th>
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

<script>
// Función requerida para renderizar cada fila de la tabla
function renderizarFilaTablaPersonalizada(item) {
    const diasTranscurridos = TablaEstandarJanus.calcularDiasTranscurridos(item.fecha_subida);
    const badgeDias = TablaEstandarJanus.generarBadgeDias(diasTranscurridos);
    
    // Determinar tipo de archivo e icono
    let tipoIcono = 'fas fa-file';
    let tipoTexto = item.tipo || 'Desconocido';
    let tipoClase = 'badge-secondary';
    
    switch(tipoTexto.toLowerCase()) {
        case 'pdf':
            tipoIcono = 'fas fa-file-pdf';
            tipoClase = 'badge-danger';
            break;
        case 'doc':
        case 'docx':
            tipoIcono = 'fas fa-file-word';
            tipoClase = 'badge-primary';
            tipoTexto = 'Word';
            break;
        case 'xls':
        case 'xlsx':
            tipoIcono = 'fas fa-file-excel';
            tipoClase = 'badge-success';
            tipoTexto = 'Excel';
            break;
        case 'jpg':
        case 'png':
        case 'gif':
            tipoIcono = 'fas fa-file-image';
            tipoClase = 'badge-info';
            tipoTexto = 'Imagen';
            break;
    }
    
    const tipoBadge = `<span class="badge ${tipoClase}"><i class="${tipoIcono} mr-1"></i>${tipoTexto}</span>`;
    
    // Formatear tamaño del archivo
    const tamanoFormateado = formatearTamaño(item.tamaño || 0);
    
    return `
        <tr class="table-row-hover-standard" data-id="${item.id}">
            <td><strong>${item.id}</strong></td>
            <td>
                <div class="text-truncate" style="max-width: 200px;" title="${item.nombre}">
                    <i class="${tipoIcono} mr-2 text-muted"></i>
                    ${item.nombre || '-'}
                </div>
            </td>
            <td>${tipoBadge}</td>
            <td class="text-right font-weight-bold">${tamanoFormateado}</td>
            <td>${item.proceso || '-'}</td>
            <td>${TablaEstandarJanus.formatearFecha(item.fecha_subida)}</td>
            <td>${badgeDias}</td>
            <td>${item.usuario || '-'}</td>
            <td>
                ${TablaEstandarJanus.generarBotonAccion('Descargar', 'btn-success-standard', `descargarArchivo(${item.id})`, 'fas fa-download')}
                ${TablaEstandarJanus.generarBotonAccion('Ver', 'btn-info-standard', `verArchivo(${item.id})`, 'fas fa-eye')}
                ${TablaEstandarJanus.generarBotonAccion('Eliminar', 'btn-danger-standard', `eliminarArchivo(${item.id})`, 'fas fa-trash')}
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
    cargarDatosDocumentacion();
});

// Configurar eventos específicos de Documentación
function configurarEventosPersonalizados() {
    // Evento de filtro por tipo
    $('#filtro-tipo-documento').on('change', function() {
        aplicarFiltrosDocumentacion();
    });
}

// Aplicar filtros específicos de Documentación
function aplicarFiltrosDocumentacion() {
    const textoBusqueda = $('#filtro-busqueda-estandar').val().toLowerCase().trim();
    const gerenciaSeleccionada = $('#filtro-gerencia-estandar').val();
    const tipoSeleccionado = $('#filtro-tipo-documento').val();
    
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
    
    // Filtro por tipo
    if (tipoSeleccionado) {
        datosFiltrados = datosFiltrados.filter(item => {
            return String(item.tipo).toLowerCase().includes(tipoSeleccionado.toLowerCase());
        });
    }
    
    TablaEstandarJanus.renderizarTabla(datosFiltrados);
}

// Sobrescribir función de filtros estándar para incluir filtro de tipo
TablaEstandarJanus.aplicarFiltros = aplicarFiltrosDocumentacion;

// Función para cargar datos específicos de Documentación
function cargarDatosDocumentacion() {
    TablaEstandarJanus.mostrarCarga();
    
    enviarPeticion('documentos', 'obtenerDocumentacion', {}, function(respuesta) {
        if (respuesta.success && respuesta.data) {
            TablaEstandarJanus.cargarDatos(respuesta.data);
        } else {
            console.error('Error al cargar documentación:', respuesta.message);
            TablaEstandarJanus.cargarDatos([]);
        }
    });
}

// Función para formatear tamaño de archivo
function formatearTamaño(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Función para descargar archivo
function descargarArchivo(id) {
    window.open(`controllers/documentos.php?accion=descargar&id=${id}`, '_blank');
}

// Función para ver archivo
function verArchivo(id) {
    window.open(`controllers/documentos.php?accion=ver&id=${id}`, '_blank');
}

// Función para eliminar archivo
function eliminarArchivo(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Está seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                ejecutarEliminacion(id);
            }
        });
    } else {
        if (confirm('¿Está seguro de que desea eliminar este archivo?')) {
            ejecutarEliminacion(id);
        }
    }
}

// Ejecutar eliminación
function ejecutarEliminacion(id) {
    enviarPeticion('documentos', 'eliminarArchivo', {id: id}, function(respuesta) {
        if (respuesta.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Eliminado', 'El archivo ha sido eliminado', 'success');
            } else {
                alert('Archivo eliminado correctamente');
            }
            cargarDatosDocumentacion();
        } else {
            alert('Error al eliminar archivo: ' + (respuesta.message || 'Error desconocido'));
        }
    });
}

// Limpiar filtros incluyendo el filtro de tipo
$('#btn-limpiar-filtros-estandar').on('click', function() {
    $('#filtro-tipo-documento').val('');
});
</script>

<?php require('views/footer.php');?>