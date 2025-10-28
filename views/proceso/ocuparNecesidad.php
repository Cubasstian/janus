<?php require('views/header.php');?>

<link rel="stylesheet" href="dist/css/proceso-moderno.css">

<style>
/* Compact filter card for ocuparNecesidad */
.card.card-kit.card-compact { border-radius: .6rem; }
.card.card-kit.card-compact .card-body { padding: 12px 14px !important; }
.card.card-kit.card-compact .card-header { padding: 8px 14px !important; }
.card.card-kit.card-compact .form-group-kit { margin-bottom: 8px; }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-users text-primary mr-2"></i>
                        Ocupar necesidad
                        <span>
                            <small class="badge badge-secondary badge-pill ml-2" id="conteo_total"></small>
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Pendientes</li>
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
                    <div class="card card-kit card-compact">
                        <div class="card-body">
                            <div class="row align-items-end">
                                <!-- Búsqueda General -->
                                <div class="col-md-4">
                                    <label class="form-label"><small><strong>BÚSQUEDA GENERAL</strong></small></label>
                                    <input type="text" 
                                           id="filtro-busqueda-general" 
                                           class="form-control form-control-sm" 
                                           placeholder="Buscar por proceso, nombre, cédula...">
                                </div>

                                <!-- Filtro por Gerencia -->
                                <div class="col-md-3">
                                    <label class="form-label"><small><strong>GERENCIA</strong></small></label>
                                    <select id="filtro-gerencia" 
                                            class="form-control form-control-sm">
                                        <option value="">Todas las gerencias</option>
                                    </select>
                                </div>

                                <!-- Filtro por Días -->
                                <div class="col-md-2">
                                    <label class="form-label"><small><strong>DÍAS</strong></small></label>
                                    <select id="filtro-dias" 
                                            class="form-control form-control-sm">
                                        <option value="">Todos</option>
                                        <option value="0-3">0-3 días</option>
                                        <option value="4-7">4-7 días</option>
                                        <option value="8-15">8-15 días</option>
                                        <option value="15+">Más de 15 días</option>
                                    </select>
                                </div>

                                <!-- Botón Limpiar -->
                                <div class="col-md-2">
                                    <button type="button" 
                                            id="btn-limpiar-filtros" 
                                            class="btn btn-outline-secondary btn-sm w-100">
                                        <i class="fas fa-eraser"></i> Limpiar
                                    </button>
                                </div>

                                <!-- Contador -->
                                <div class="col-md-1 text-right">
                                    <label class="form-label"><small><strong>TOTAL</strong></small></label>
                                    <div>
                                        <span id="contador-filtrados" class="badge badge-primary badge-pill">0</span>
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
                                <i class="fas fa-clipboard-list"></i>
                                Solicitudes Pendientes
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
                                            <th>Acciones</th>
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
        cargarRegistros({criterio: 'todas', estado: 1}, function(){
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
                            <button type="button" class="btn-kit btn-kit-outline-info btn-sm" onClick="mostrarDetalle(${registro.id},'ninguno')" title="Ver detalle" data-toggle="tooltip">
                                <i class="fas fa-search"></i>
                            </button>
                            <button type="button" class="btn-kit btn-kit-outline-success btn-sm" onClick="asignar(${registro.id})" title="Asignar vacante" data-toggle="tooltip">
                                <i class="fas fa-user-plus"></i>
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

    function asignar(idSolicitud){
        sessionStorage.setItem('solicitud', JSON.stringify(idSolicitud))
        url = 'proceso/ocuparNecesidadDetalle/'
        window.open(url, '_self')
    }
</script>
</body>
</html>
