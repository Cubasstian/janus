<?php require('views/header.php');?>

<link rel="stylesheet" href="dist/css/proceso-moderno.css">
    border-left: 3px solid transparent;
}

.table-row-modern:hover {
    border-left-color: #28a745;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.process-id {
    font-weight: 600;
    color: #28a745;
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

.btn-group-sm .btn-action {
    padding: 0.375rem 0.75rem;
}

.badge-pill {
    font-size: 0.75rem;
    font-weight: 500;
}

.card-outline.card-success {
    border-top: 3px solid #28a745;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.table-responsive {
    border-radius: 0 0 0.375rem 0.375rem;
}

.align-middle {
    vertical-align: middle !important;
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
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid px-4">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-user-check text-success mr-2"></i>
                        Pendientes por vacante
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
        <div class="container-fluid px-4">
            <div class="row">   
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header card-header-clean">
                            <h3>
                                <i class="fas fa-briefcase text-success"></i>
                                Asignación de Vacantes
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover modern-table mb-0" style="width: 100%; min-width: 800px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%;"><i class="fas fa-hashtag mr-1 text-primary"></i>Proceso</th>
                                            <th style="width: 30%;"><i class="fas fa-user mr-1 text-success"></i>Nombre</th>
                                            <th style="width: 20%;"><i class="fas fa-id-card mr-1 text-info"></i>Cédula</th>
                                            <th class="text-center" style="width: 15%;"><i class="fas fa-clock mr-1 text-warning"></i>Días</th>
                                            <th class="text-center" style="width: 20%;"><i class="fas fa-cogs mr-1 text-secondary"></i>Acciones</th>
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

    function init(info){
        //Cargar registros
        cargarRegistros({criterio: 'todas', estado: 1}, function(){
            console.log('Cargo...')
        })
    }

    function cargarRegistros(datos, callback){
        enviarPeticion('solicitudes', 'getSolicitudes', datos, function(r){
            let fila = ''
            let botonAsignar = ''
            let colorBadge = ''
            r.data.map(registro => {
                botonAsignar = ''
                if(registro.idVacante == 1){
                    botonAsignar = `<button type="button" class="btn btn-default" onClick="asignar(${registro.id})" title="Asignar vacante">
                                        <i class="fas fa-arrow-alt-circle-right"></i>
                                    </button>`
                }
                colorBadge = getColor(registro.tiempo)
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
                                    ${botonAsignar ? `<button type="button" class="btn btn-outline-primary btn-action" onClick="asignar(${registro.id})" title="Asignar vacante" data-toggle="tooltip">
                                        <i class="fas fa-user-plus"></i>
                                    </button>` : ''}
                                    <button type="button" class="btn btn-outline-secondary btn-action" onClick="mostrarHistorico(${registro.idProceso},${registro.id})" title="Ver histórico" data-toggle="tooltip">
                                        <i class="fas fa-history"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>`
            })
            $('#contenido').html(fila)  // Cambié append() por html() para evitar duplicados
            
            // Initialize tooltips for new elements
            $('[data-toggle="tooltip"]').tooltip()
            
            callback()
            $('#conteo_total').text(`Total: ${r.data.length || 0}`)
        })
    }

    function asignar(idSolicitud){
        sessionStorage.setItem('solicitud', JSON.stringify(idSolicitud))
        url = 'proceso/asignarVacanteDetalle/'
        window.open(url, '_self')
    }
</script>
</body>
</html>
