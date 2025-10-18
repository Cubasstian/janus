<?php require('views/header.php');?>

<link rel="stylesheet" href="dist/css/proceso-moderno.css">

<style>
/* Solo estilos específicos para documentacion */
.table-row-modern {
    border-left-color: #6f42c1;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.process-id {
    font-weight: 600;
    color: #6f42c1;
    font-family: 'Courier New', monospace;
}

.user-info {
    display: flex;
    align-items: center;
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

/* DataTables Custom Styling */
.dataTables_wrapper .dataTables_length select {
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 0.375rem 0.75rem;
    background-color: white;
    font-size: 0.875rem;
    color: #495057;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.dataTables_wrapper .dataTables_length select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: 0;
}

.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 0.375rem 0.75rem;
    background-color: white;
    font-size: 0.875rem;
    color: #495057;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    margin-left: 0.5rem;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: 0;
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 1rem;
}

.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0;
    display: flex;
    align-items: center;
}

.dataTables_wrapper .dataTables_info {
    color: #6c757d;
    font-size: 0.875rem;
    padding-top: 0.75rem;
}

.dataTables_wrapper .dataTables_paginate {
    padding-top: 0.75rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 0.375rem 0.75rem;
    margin: 0 2px;
    background: white;
    color: #495057;
    text-decoration: none;
    transition: all 0.15s ease-in-out;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #e9ecef;
    border-color: #adb5bd;
    color: #495057;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #007bff;
    border-color: #007bff;
    color: white;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    color: #6c757d;
    background: #fff;
    border-color: #ddd;
}

.btn-action:hover i {
    color: white !important;
}

.document-badge {
    position: relative;
    display: inline-block;
}

.pending-count {
    position: absolute;
    top: -8px;
    right: -8px;
    min-width: 20px;
    height: 20px;
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    border-radius: 50%;
    font-size: 0.65rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.card-header-modern {
    background: white;
    color: #000;
    border: 2px solid #000;
    border-bottom: 3px solid #000;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.card-header-modern h3 {
    margin: 0;
    font-weight: 700;
    display: flex;
    align-items: center;
}

.card-header-modern .fas {
    margin-right: 0.5rem;
    font-size: 1.2rem;
    color: #6f42c1;
}

.badge-pill {
    padding: 0.5rem 1rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-purple {
    background: linear-gradient(135deg, #6f42c1 0%, #8e2de2 100%);
    color: white;
}

.btn-outline-purple {
    color: #6f42c1;
    border-color: #6f42c1;
}

.btn-outline-purple:hover {
    background-color: #6f42c1;
    border-color: #6f42c1;
    color: white;
}

.text-purple {
    color: #6f42c1 !important;
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-file-check text-purple mr-2"></i>
                        Revisar documentación
                        <span>
                            <small class="badge badge-secondary badge-pill ml-2" id="conteo_total"></small>
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Documentación</li>
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
                <div class="col-12">
                    <div class="card card-kit">
                        <div class="card-header card-header-clean">
                            <h3 class="card-title">
                                <i class="fas fa-clipboard-check"></i>
                                Solicitudes pendientes de documentación
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
        cargarRegistros({criterio: 'todas', estado: 2}, function(res){
            console.log(res)
            // Cargar gerencias para el filtro
            cargarGerenciasParaFiltro();
            
            enviarPeticion('documentos', 'contarPendientes', {procesos: res.join(",")}, function(r){
                r.data.forEach(registro => {
                    $('#dp_'+registro.fk_procesos).text(registro.cantidad)
                })
            })
        })
        
        // Configurar eventos de filtros
        configurarEventosFiltros();
    }

    function cargarRegistros(datos, callback){
        enviarPeticion('solicitudes', 'getSolicitudes', datos, function(r){
            // Guardar datos originales para filtros
            datosOriginales = r.data || [];
            datosFiltrados = [...datosOriginales];
            
            let procesos = [0];
            datosOriginales.forEach(registro => {
                procesos.push(registro.idProceso);
            });
            
            renderizarTabla(datosFiltrados);
            callback(procesos);
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
            fila += `<tr id="${registro.id}">
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
                            <button type="button" class="btn-kit btn-kit-outline-info btn-sm" onClick="mostrarDetalle(${registro.id},'ninguno')" title="Ver detalle" data-toggle="tooltip">
                                <i class="fas fa-search"></i>
                            </button>
                            <div class="document-badge" style="display: inline-block; position: relative;">
                                <button type="button" class="btn-kit btn-kit-outline-warning btn-sm" onClick="verDocumentos(${registro.id})" title="Verificar documentación" data-toggle="tooltip">                                                
                                    <i class="fas fa-tasks"></i>
                                </button>
                                <span class="pending-count" id="dp_${registro.idProceso}">0</span>
                            </div>
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

    function verDocumentos(idSolicitud){        
        sessionStorage.setItem('solicitud', JSON.stringify(idSolicitud))
        url = 'proceso/documentacionDetalle/'
        window.open(url, '_self')
    }
</script>
</body>
</html>
