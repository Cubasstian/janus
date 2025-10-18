<?php require('views/header.php');?>

<link rel="stylesheet" href="dist/css/proceso-moderno.css">

<style>
/* Estilos específicos para DataTables en expedircdp */
.dataTables_wrapper .dataTables_length select {
    border: 1px solid var(--color-border-light);
    border-radius: var(--border-radius-input);
    padding: 0.375rem 0.75rem;
    background-color: var(--color-text-light);
    font-size: 0.875rem;
    color: var(--color-text-dark);
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.dataTables_wrapper .dataTables_length select:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 0.2rem rgba(81, 135, 17, 0.25);
    outline: 0;
}

.dataTables_wrapper .dataTables_filter input {
    border: 1px solid var(--color-border-light);
    border-radius: var(--border-radius-input);
    padding: 0.375rem 0.75rem;
    background-color: var(--color-text-light);
    font-size: 0.875rem;
    color: var(--color-text-dark);
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    margin-left: 0.5rem;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 0.2rem rgba(81, 135, 17, 0.25);
    outline: 0;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--color-primary);
    border-color: var(--color-primary);
    color: var(--color-text-light);
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
    background: var(--color-danger);
    color: var(--color-text-light);
    border-radius: 50%;
    font-size: 0.65rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid var(--color-text-light);
    box-shadow: var(--shadow-sm);
}

.export-btn {
    border-radius: var(--border-radius-input);
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
}

.export-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-file-invoice-dollar text-primary mr-2"></i>
                        Expedir CDP
                        <span>
                            <small class="badge badge-secondary badge-pill ml-2" id="conteo_total"></small>
                        </span>
                        <button type="button" class="btn btn-outline-primary export-btn ml-3" id="exportar" title="Exportar a Excel" data-toggle="tooltip">
                            <i class="fas fa-file-download mr-1"></i>
                            Exportar
                        </button>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Expedir CDP</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid px-4">
            <!-- PANEL DE FILTROS ADAPTADO DE EEP -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
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
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header card-header-clean">
                            <h3>
                                <i class="fas fa-receipt"></i>
                                Solicitudes pendientes de CDP
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover modern-table mb-0" style="width: 100%; min-width: 800px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%;"><i class="fas fa-hashtag mr-1 text-primary"></i>Proceso</th>
                                            <th style="width: 25%;"><i class="fas fa-user mr-1 text-success"></i>Nombre</th>
                                            <th style="width: 15%;"><i class="fas fa-id-card mr-1 text-info"></i>Cédula</th>
                                            <th style="width: 15%;"><i class="fas fa-building mr-1 text-secondary"></i>Gerencia</th>
                                            <th class="text-center" style="width: 15%;"><i class="fas fa-clock mr-1 text-warning"></i>Días</th>                            
                                            <th class="text-center" style="width: 15%;"><i class="fas fa-cogs mr-1 text-secondary"></i>Opciones</th>
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

    <div class="modal fade" id="modalAceptar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalAceptarTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioAceptar">
                        <div class="form-group">
                            <label for="cdp_numero">Número (*)</label>
                            <input type="text" class="form-control" name="cdp_numero" required="required">
                        </div>
                        <div class="form-group">
                            <label for="cdp_valor" id="mascara">Valor (*)</label>
                            <input type="number" class="form-control" name="cdp_valor" id="cdp_valor" required="required">
                            <small class="text-muted" id="labelPresupuesto"></small>
                        </div>
                        <div class="form-group">
                            <label for="cdp_fecha">Fecha (*)</label>
                            <input type="date" class="form-control" name="cdp_fecha" required="required">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" form="formularioAceptar">Guardar</button>
                </div>
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
    
    var idS = 0
    function init(info){
        //Cargar registros
        cargarRegistros({criterio: 'todas', estado: 4}, function(){
            console.log('Cargo...')
            // Cargar gerencias para el filtro
            cargarGerenciasParaFiltro();
        })
        
        // Configurar eventos de filtros
        configurarEventosFiltros();

        //Formatear input
        $('#cdp_valor').on('keyup', function(r){
            $('#mascara').text('Valor: $' + currency($('#cdp_valor').val(),0))
        })

        $('#formularioAceptar').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            datos.estado = 5
            enviarPeticion('solicitudes', 'setEstado', {info: datos, id: idS}, function(r){
                $('#modalAceptar').modal('hide')
                $(`#${idS}`).hide('slow')
            })
        })

        //Exportar en formato excel
        $('#exportar').on('click', function(){
            url = `proceso/expedircdpExportar/`
            window.open(url, '_blank')
        })
        
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip()
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
            fila += `<tr class="table-row-modern" id=${registro.id}>
                        <td class="align-middle">
                            <span class="process-id">#${registro.idProceso}</span>
                        </td>
                        <td class="align-middle">
                            <div class="user-info">
                                <i class="fas fa-user-circle mr-2"></i>
                                <span>${registro.ps}</span>
                            </div>
                        </td>
                        <td class="align-middle">
                            <span>${registro.cedula}</span>
                        </td>
                        <td class="align-middle">
                            <span class="text-muted">${registro.gerencia || 'Sin asignar'}</span>
                        </td>
                        <td class="text-center align-middle">
                            <span class="badge badge-${colorBadge} badge-pill px-3">
                                <i class="fas fa-calendar-day mr-1"></i>
                                ${registro.tiempo} días
                            </span>
                        </td>
                        <td class="text-center align-middle">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-info btn-action" onClick="mostrarDetalle(${registro.id},'ninguno')" title="Ver detalle" data-toggle="tooltip">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button type="button" class="btn btn-outline-success btn-action" onClick="aceptar(${registro.idProceso},${registro.id},${registro.presupuesto})" title="Generar CDP" data-toggle="tooltip">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-action" onClick="mostrarHistorico(${registro.idProceso},${registro.id})" title="Ver histórico" data-toggle="tooltip">
                                    <i class="fas fa-history"></i>
                                </button>
                            </div>
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

    function aceptar(idProceso, idSolicitud, presupuesto){
        idS = idSolicitud
        $('#modalAceptarTitulo').text(`Información CDP para el proceso #${idProceso}`)        
        $("#formularioAceptar").trigger("reset");
        $('#labelPresupuesto').text(`Presupuesto $${currency(presupuesto,0)}`)
        $('#modalAceptar').modal('show')
    }
</script>
</body>
</html>
