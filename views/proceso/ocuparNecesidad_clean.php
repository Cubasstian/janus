<?php require('views/header.php');?>

<style>
.modern-table {
    font-size: 0.9rem;
}

.modern-table thead th {
    background: white;
    color: #000;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    border: 2px solid #000;
    border-bottom: 3px solid #000;
}

.table-row-modern {
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.table-row-modern:hover {
    border-left-color: #007bff;
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
            <div class="row">   
                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-header card-header-clean">
                            <h3>
                                <i class="fas fa-clipboard-list"></i>
                                Solicitudes Pendientes
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
    function init(info){
        //Cargar registros
        cargarRegistros({criterio: 'todas', estado: 1}, function(){
            console.log('Cargo...')
        })
    }

    function cargarRegistros(datos, callback){
        enviarPeticion('solicitudes', 'getSolicitudes', datos, function(r){
            let fila = ''
            let colorBadge = ''
            r.data.map(registro => {
                colorBadge = getColor(registro.tiempo)
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
                                    <button type="button" class="btn btn-outline-success btn-action" onClick="asignar(${registro.id})" title="Asignar vacante" data-toggle="tooltip">
                                        <i class="fas fa-user-plus text-success"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-action" onClick="mostrarHistorico(${registro.idProceso},${registro.id})" title="Ver histórico" data-toggle="tooltip">
                                        <i class="fas fa-history text-secondary"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>`
            })
            $('#contenido').html(fila)  // Cambié append() por html() para evitar duplicados
            callback()
            $('#conteo_total').text(`Total: ${r.data.length || 0}`)
            
            // Inicializar tooltips para los botones
            $('[data-toggle="tooltip"]').tooltip()
        })
    }

    function asignar(idSolicitud){
        sessionStorage.setItem('solicitud', JSON.stringify(idSolicitud))
        url = 'proceso/ocuparNecesidadDetalle/'
        window.open(url, '_self')
    }
</script>
</body>
</html>
