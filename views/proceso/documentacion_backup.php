<?php require('views/header.php');?>

<link rel="stylesheet" href="dist/css/proceso-moderno.css">
    border-left: 3px solid transparent;
}

.table-row-modern:hover {
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
                        <div class="card-header card-header-modern">
                            <h3>
                                <i class="fas fa-clipboard-check"></i>
                                Solicitudes pendientes de documentación
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
            fila += `<tr class="table-row-modern" id="${registro.id}">
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
                                <div class="document-badge">
                                    <button type="button" class="btn btn-outline-purple btn-action" onClick="verDocumentos(${registro.id})" title="Verificar documentación" data-toggle="tooltip">                                                
                                        <i class="fas fa-tasks"></i>
                                    </button>
                                    <span class="pending-count" id="dp_${registro.idProceso}">0</span>
                                </div>
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

    function verDocumentos(idSolicitud){        
        sessionStorage.setItem('solicitud', JSON.stringify(idSolicitud))
        url = 'proceso/documentacionDetalle/'
        window.open(url, '_self')
    }
</script>
</body>
</html>
