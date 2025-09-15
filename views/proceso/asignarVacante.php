<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Pendientes por vacante
                        <span>
                            <small class="badge badge-success text-xs" id="conteo_total"></small>
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
        <div class="container">
            <div class="row">   
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-sm">
                                    <thead>
                                        <tr>
                                            <th>Proceso</th>
                                            <th>Nombre</th>
                                            <th>Cédula</th>
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
                fila += `<tr id=${registro.id}>
                            <td>${registro.idProceso}</td>
                            <td>${registro.ps}</td>
                            <td>${registro.cedula}</td>
                            <td class="text-center">
                                <span class="badge badge-${colorBadge}">
                                    ${registro.tiempo}
                                </span>
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-default" onClick="mostrarDetalle(${registro.id},'ninguno')" title="ver detalle">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </td>
                                        <td>
                                            ${botonAsignar}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default" onClick="mostrarHistorico(${registro.idProceso},${registro.id})" title="Historico">
                                                <i class="fas fa-history"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>`
            })
            $('#contenido').append(fila)
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