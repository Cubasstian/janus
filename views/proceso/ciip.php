<?php require('views/header.php');?>

<link rel="stylesheet" href="dist/css/proceso-moderno.css">
<style>
/* Estilos específicos para CIIP */
.modern-table {
    table-layout: fixed;
    width: 100%;
}

.modern-table thead th {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.table-row-modern td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Prevenir scroll horizontal */
.table-responsive {
    overflow-x: auto;
    margin-bottom: 0;
}

.table-responsive .table {
    margin-bottom: 0;
    min-width: 100%;
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

.card-header-clean {
    background: white;
    color: #000;
    border: 2px solid #000;
    border-bottom: 3px solid #000;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.card-header-clean h3 {
    margin: 0;
    font-weight: 700;
    display: flex;
    align-items: center;
}

.card-header-clean .fas {
    margin-right: 0.5rem;
    font-size: 1.2rem;
    color: #007bff;
}

.align-middle {
    vertical-align: middle !important;
}
</style>
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-building text-primary mr-2"></i>
                        CIIP - Verificación de Planta
                        <span>
                            <small class="badge badge-secondary badge-pill ml-2" id="conteo_total"></small>
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">CIIP</li>
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
                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-header card-header-clean">
                            <h3>
                                <i class="fas fa-clipboard-list"></i>
                                Procesos Pendientes de CIIP
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover modern-table mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-hashtag mr-1 text-primary"></i>Proceso</th>
                                            <th><i class="fas fa-user mr-1 text-success"></i>Nombre</th>
                                            <th><i class="fas fa-id-card mr-1 text-info"></i>Cédula</th>
                                            <th><i class="fas fa-building mr-1 text-secondary"></i>Gerencia</th>
                                            <th class="text-center"><i class="fas fa-clock mr-1 text-warning"></i>Días</th>
                                            <th class="text-center"><i class="fas fa-cogs mr-1 text-secondary"></i>Acciones</th>
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
        cargarRegistros({criterio: 'todas', estado: 6}, function(){
            console.log('Cargo CIIP...')
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
                                <button type="button" class="btn btn-outline-success btn-action" onClick="registrarCIIP(${registro.id})" title="Registrar CIIP" data-toggle="tooltip">
                                    <i class="fas fa-building text-success"></i>
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

    function registrarCIIP(idSolicitud){
        Swal.fire({
            title: 'Registrar CIIP',
            html: `
                <form id="form-ciip" class="text-left">
                    <div class="form-group">
                        <label for="fecha_ciip">Fecha CIIP:</label>
                        <input type="date" id="fecha_ciip" name="fecha_ciip" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="resultado_ciip">Resultado / Código CIIP:</label>
                        <input type="text" id="resultado_ciip" name="resultado_ciip" class="form-control" maxlength="100" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="observaciones_ciip">Observaciones:</label>
                        <textarea id="observaciones_ciip" name="observaciones_ciip" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Guardar CIIP',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745',
            preConfirm: () => {
                const fecha = document.getElementById('fecha_ciip').value;
                const resultado = document.getElementById('resultado_ciip').value;
                
                if (!fecha || !resultado) {
                    Swal.showValidationMessage('Por favor complete los campos obligatorios');
                    return false;
                }
                
                return {
                    fecha_ciip: fecha,
                    resultado_ciip: resultado,
                    observaciones_ciip: document.getElementById('observaciones_ciip').value,
                    idSolicitud: idSolicitud
                };
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                enviarPeticion('solicitudes', 'registrarCIIP', result.value, function(r){
                    if (r.ejecuto) {
                        Swal.fire('¡Éxito!', 'CIIP registrado correctamente', 'success');
                        // Remover el registro de los datos filtrados
                        datosOriginales = datosOriginales.filter(item => item.id !== idSolicitud);
                        datosFiltrados = datosFiltrados.filter(item => item.id !== idSolicitud);
                        renderizarTabla(datosFiltrados);
                        actualizarContadores();
                    } else {
                        Swal.fire('Error', r.mensaje || 'No se pudo registrar el CIIP', 'error');
                    }
                });
            }
        });
    }
</script>
</body>
</html>
