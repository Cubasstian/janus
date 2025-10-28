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
                        Ficha de requerimiento
                        <span>
                            <small class="badge badge-success text-xs" id="conteo_total"></small>
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">FR</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- PANEL DE FILTROS ADAPTADO DE EEP -->
            <div class="row mb-3">
                <div class="col-12">
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
                    <div class="card shadow-lg card-kit" style="border: 2px solid #000; border-radius: 12px;">
                        <div class="card-header card-header-clean">
                            <h3 class="card-title">
                                <i class="fas fa-file-alt mr-2"></i>Ficha de Requerimiento - Gestión de Documentos
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table id="tablaSolicitudes" class="data-table" style="width: 100%; min-width: 800px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%;">Proceso</th>
                                            <th style="width: 25%;">Nombre</th>
                                            <th style="width: 15%;">Cédula</th>
                                            <th style="width: 15%;">Gerencia</th>
                                            <th class="text-center" style="width: 15%;">Días</th>
                                            <th class="text-center" style="width: 15%;">Opciones</th>
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

    <div class="modal fade" id="modalFR">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalFRTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioFR">
                        <div class="form-group">
                            <label for="consecutivo_fr">Consecutivo FR (*)</label>
                            <input type="number" class="form-control" name="consecutivo_fr" id="consecutivo_fr" required="required">
                        </div>
                        <div class="form-group">
                            <label for="consecutivo_ip">Consecutivo IP (*)</label>
                            <input type="number" class="form-control" name="consecutivo_ip" id="consecutivo_ip" required="required">
                        </div>
                        <div class="form-group">
                            <label for="solped">SOLPED (*)</label>
                            <input type="number" class="form-control" name="solped" id="solped" required="required">
                        </div>
                        <div class="form-group">
                            <label for="plazo">Plazo (*)</label>
                            <input type="text" class="form-control" name="plazo" id="plazo" required="required">
                        </div>
                        <div class="form-group">
                            <label for="forma_pago">Forma pago (*)</label>
                            <input type="text" class="form-control" name="forma_pago" id="forma_pago" required="required">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" form="formularioFR">Generar</button>
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
    
    var idP = 0
    function init(info){
        //Cargar registros
        cargarRegistros({criterio: 'todas', estado: 5}, function(){
            console.log('Cargo...')
            // Cargar gerencias para el filtro
            cargarGerenciasParaFiltro();
        })
        
        // Configurar eventos de filtros
        configurarEventosFiltros();

        //Guardar y generar PDF
        $('#formularioFR').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            enviarPeticion('procesos', 'update', {info: datos, id: idP}, function(r){
                url = `proceso/fr_generar/${idP}`
                window.open(url, '_blank')
            })
        })
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
                                <i class="fas fa-user-circle text-muted mr-2"></i>
                                <span class="font-weight-medium">${registro.ps}</span>
                            </div>
                        </td>
                        <td class="align-middle">
                            <span class="text-muted">${registro.cedula}</span>
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
                                    <i class="fas fa-search text-info"></i>
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-action" onClick="generar(${registro.idProceso})" title="Generar FR" data-toggle="tooltip">
                                    <i class="fas fa-file-pdf text-warning"></i>
                                </button>
                                <button type="button" class="btn btn-outline-success btn-action" onClick="aceptar(${registro.idProceso},${registro.id})" title="Pasar a generar CDP" data-toggle="tooltip">
                                    <i class="fas fa-check text-success"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-action" onClick="mostrarHistorico(${registro.idProceso},${registro.id})" title="Ver histórico" data-toggle="tooltip">
                                    <i class="fas fa-history text-secondary"></i>
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

    function generar(idProceso){
        idP = idProceso
        llenarFormulario('formularioFR', 'procesos', 'select', {info:{id: idProceso}}, function(r){
            $('#modalFRTitulo').text(`Información FR para el proceso #${idProceso}`)
            $('#modalFR').modal('show')
        })
    }

    function aceptar(idProceso, idSolicitud){
        Swal.fire({
            icon: 'question',
            title: 'Confirmación',
            html: `Esta seguro de haber generado la ficha de requerimiento para el proceso #${idProceso}`,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){
                let datos = {
                    info: {
                        estado: 6
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
