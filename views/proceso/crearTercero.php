<?php require('views/header.php');?>

<link rel="stylesheet" href="dist/css/proceso-moderno.css">
<style>
.table-row-modern {
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.table-row-modern:hover {
    border-left-color: var(--color-primary);
    transform: translateX(2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.process-id {
    font-weight: 600;
    color: #007bff;
    font-family: 'Courier New', monospace;
}

.user-info {
    display: flex;
    align-items: center;
}

.font-weight-medium {
    font-weight: 500;
}

.btn-action {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
    border-width: 1.5px;
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-group-sm .btn-action {
    padding: 0.375rem 0.75rem;
}

.badge-pill {
    font-size: 0.75rem;
    font-weight: 500;
}

.align-middle {
    vertical-align: middle !important;
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Crear tercero
                        <span>
                            <small class="badge badge-success text-xs" id="conteo_total"></small>
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Crear tercero</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Filtros personalizados -->
            <div class="row">
                <div class="col">
                    <div class="card card-kit">
                        <div class="card-body">
                            <div class="row mb-4">
                                <!-- Búsqueda General -->
                                <div class="col-md-4">
                                    <div class="form-group-kit">
                                        <label for="filtro-busqueda-general">Búsqueda General</label>
                                        <input type="text" 
                                               id="filtro-busqueda-general" 
                                               class="input-kit" 
                                               placeholder="Buscar por proceso, nombre, cédula...">
                                    </div>
                                </div>
                                
                                <!-- Filtro por Gerencia -->
                                <div class="col-md-3">
                                    <div class="form-group-kit">
                                        <label for="filtro-gerencia">Filtrar por Gerencia</label>
                                        <select id="filtro-gerencia" 
                                                class="input-kit">
                                            <option value="">Todas las gerencias</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Filtro por Días -->
                                <div class="col-md-2">
                                    <div class="form-group-kit">
                                        <label for="filtro-dias">Filtrar por Días</label>
                                        <select id="filtro-dias" 
                                                class="input-kit">
                                            <option value="">Todos</option>
                                            <option value="0-3">0-3 días</option>
                                            <option value="4-7">4-7 días</option>
                                            <option value="8-15">8-15 días</option>
                                            <option value="15+">Más de 15 días</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Botón Limpiar -->
                                <div class="col-md-3">
                                    <div class="form-group-kit">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="button" 
                                                    id="btn-limpiar-filtros" 
                                                    class="btn-kit btn-kit-secondary w-100">
                                                <i class="fas fa-eraser"></i> Limpiar Filtros
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">   
                <div class="col">
                    <div class="card card-kit">
                        <div class="card-header card-header-clean">
                            <h3 class="card-title">
                                <i class="fas fa-user-plus"></i>
                                Crear Tercero - Gestión de Procesos
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tablaSolicitudes" class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Proceso</th>
                                            <th>Nombre</th>
                                            <th>Cédula</th>
                                            <th>Gerencia</th>
                                            <th>Días</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contenido"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Detalle -->
<div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalDetalleTitulo">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>Detalle de solicitud</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalDetalleContenido" style="max-height: 70vh; overflow-y: auto;">
                <!-- El contenido se carga dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Histórico -->
<div class="modal fade" id="modalHistorico" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalHistoricoTitulo">
                    <i class="fas fa-history mr-2"></i>
                    <span>Histórico del proceso</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-sm text-left">
                    <thead>
                        <tr>
                            <th>Quien</th>
                            <th>Lo pasó a</th>
                            <th>Cuando</th>
                            <th>Observación</th>
                        </tr>
                    </thead>
                    <tbody id="modalHistoricoContenido">
                        <!-- El contenido se carga dinámicamente -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Visor PDF -->
<div class="modal fade" id="modalVisorPDF" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Visor de Documento
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <iframe id="pdfViewer" style="width: 100%; height: 80vh; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script src="dist/js/solicitudes.js"></script>
<script type="text/javascript">
    // Variables globales para mostrarDetalle
    var estados = ['','Ocupar necesidad', 'Gestión documentación', 'Crear tercero', 'Expedir CDP', 'Ficha de requerimiento', 'CIIP', 'Examen preocupacional', 'Validación perfil', 'Recoger validación perfil', 'Minuta', 'Numerar contrato', 'Solicitud de afiliación', 'Afiliar ARL', 'Expedir RP', 'Recoger RP', 'Designar supervisor', 'Acta de inicio', 'Contratado', 'Anulado'];
    var colores = ['secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','success', 'danger'];

    // Variables globales para los filtros
    let datosOriginales = [];
    let datosFiltrados = [];
    
    function init(info){
        //Cargar registros
        cargarRegistros({criterio: 'todas', estado: 3}, function(){
            console.log('Cargo...')
            // Cargar gerencias para el filtro
            cargarGerenciasParaFiltro();
        })
        
        // Configurar eventos de filtros
        configurarEventosFiltros();
    }

    function cargarRegistros(datos, callback){
        enviarPeticion('solicitudes', 'getSolicitudes', datos, function(r){
            // Guardar datos originales para filtros
            datosOriginales = r.data || [];
            datosFiltrados = [...datosOriginales];
            
            renderizarTabla(datosFiltrados);
            callback();
            actualizarContadores();
            
            // Inicializar tooltips para los botones
            $('[data-toggle="tooltip"]').tooltip();
        })
    }
    
    function renderizarTabla(datos) {
        let fila = '';
        let colorBadge = '';
        
        datos.map(registro => {
            colorBadge = getColor(registro.tiempo);
            fila += `<tr id=${registro.id}>
                        <td>
                            <span class="font-weight-bold text-primary">#${registro.idProceso}</span>
                        </td>
                        <td>${registro.ps}</td>
                        <td>${registro.cedula}</td>
                        <td>${registro.gerencia || 'Sin asignar'}</td>
                        <td>
                            <span class="badge badge-${colorBadge}">${registro.tiempo} días</span>
                        </td>
                        <td>
                            <button type="button" class="btn-kit btn-kit-outline-info btn-sm" onClick="mostrarDetalle(${registro.id},'tercero')" title="Ver detalle" data-toggle="tooltip">
                                <i class="fas fa-search"></i>
                            </button>
                            <button type="button" class="btn-kit btn-kit-outline-success btn-sm" onClick="aceptar(${registro.idProceso},${registro.id})" title="Pasar a generar CDP" data-toggle="tooltip">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" class="btn-kit btn-kit-outline-secondary btn-sm" onClick="mostrarHistorico(${registro.idProceso},${registro.id})" title="Ver histórico" data-toggle="tooltip">
                                <i class="fas fa-history"></i>
                            </button>
                        </td>
                    </tr>`;
        });
        
        $('#contenido').html(fila);
    }
    
    // Configurar eventos de filtros
    function configurarEventosFiltros() {
        // Búsqueda en tiempo real
        $('#filtro-busqueda-general').on('input', function() {
            clearTimeout(window.filtroTimeout);
            window.filtroTimeout = setTimeout(aplicarFiltros, 300);
        });
        
        // Filtro por gerencia
        $('#filtro-gerencia').on('change', aplicarFiltros);
        
        // Filtro por días
        $('#filtro-dias').on('change', aplicarFiltros);
        
        // Botón limpiar filtros
        $('#btn-limpiar-filtros').on('click', limpiarFiltros);
    }
    
    // Cargar gerencias para el filtro
    function cargarGerenciasParaFiltro() {
        enviarPeticion('gerencias', 'getGerencias', {criterio: 'rol'}, function(respuesta) {
            if (respuesta.ejecuto && respuesta.data) {
                let opciones = '<option value="">Todas las gerencias</option>';
                respuesta.data.forEach(function(gerencia) {
                    opciones += `<option value="${gerencia.nombre}">${gerencia.nombre}</option>`;
                });
                $('#filtro-gerencia').html(opciones);
            }
        });
    }
    
    // Aplicar filtros
    function aplicarFiltros() {
        const textoBusqueda = $('#filtro-busqueda-general').val().toLowerCase().trim();
        const gerenciaSeleccionada = $('#filtro-gerencia').val();
        const diasSeleccionados = $('#filtro-dias').val();
        
        datosFiltrados = datosOriginales.filter(item => {
            // Filtro por texto de búsqueda
            let coincideTexto = true;
            if (textoBusqueda) {
                coincideTexto = (
                    String(item.idProceso).toLowerCase().includes(textoBusqueda) ||
                    String(item.ps || '').toLowerCase().includes(textoBusqueda) ||
                    String(item.cedula || '').toLowerCase().includes(textoBusqueda) ||
                    String(item.gerencia || '').toLowerCase().includes(textoBusqueda)
                );
            }
            
            // Filtro por gerencia
            let coincideGerencia = true;
            if (gerenciaSeleccionada) {
                coincideGerencia = String(item.gerencia || '') === gerenciaSeleccionada;
            }
            
            // Filtro por días
            let coincideDias = true;
            if (diasSeleccionados) {
                const dias = parseInt(item.tiempo) || 0;
                switch(diasSeleccionados) {
                    case '0-3':
                        coincideDias = dias >= 0 && dias <= 3;
                        break;
                    case '4-7':
                        coincideDias = dias >= 4 && dias <= 7;
                        break;
                    case '8-15':
                        coincideDias = dias >= 8 && dias <= 15;
                        break;
                    case '15+':
                        coincideDias = dias > 15;
                        break;
                }
            }
            
            return coincideTexto && coincideGerencia && coincideDias;
        });
        
        renderizarTabla(datosFiltrados);
        actualizarContadores();
    }
    
    // Limpiar filtros
    function limpiarFiltros() {
        $('#filtro-busqueda-general').val('');
        $('#filtro-gerencia').val('');
        $('#filtro-dias').val('');
        
        datosFiltrados = [...datosOriginales];
        renderizarTabla(datosFiltrados);
        actualizarContadores();
    }
    
    // Actualizar contadores
    function actualizarContadores() {
        $('#conteo_total').text(`Total: ${datosOriginales.length || 0}`);
        $('#contador-filtrados').text(datosFiltrados.length);
    }

    function aceptar(idProceso, idSolicitud){
        Swal.fire({
            icon: 'question',
            title: 'Confirmar Tercero Creado',
            html: `¿Está seguro de haber creado el tercero registrado en el proceso <strong>#${idProceso}</strong>?`,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check mr-1"></i>Confirmar',
            cancelButtonText: '<i class="fas fa-times mr-1"></i>Cancelar',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if(result.value){
                let datos = {
                    info: {
                        estado: 4
                    },
                    id: idSolicitud
                }
                enviarPeticion('solicitudes', 'setEstado', datos, function(r){
                    toastr.success("Se actualizó correctamente")
                    // Remover el registro de los datos originales y filtrados
                    datosOriginales = datosOriginales.filter(item => item.id !== idSolicitud);
                    datosFiltrados = datosFiltrados.filter(item => item.id !== idSolicitud);
                    // Actualizar la tabla y contadores
                    renderizarTabla(datosFiltrados);
                    actualizarContadores();
                })
            }
        })
    }
</script>
</body>
</html>
