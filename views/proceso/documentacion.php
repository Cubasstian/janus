<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Revisar documentación
                        <span>
                            <small class="badge badge-success text-xs" id="conteo_total"></small>
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
        cargarRegistros({criterio: 'todas', estado: 2}, function(res){
            console.log(res)
            enviarPeticion('documentos', 'contarPendientes', {procesos: res.join(",")}, function(r){
                r.data.forEach(registro => {
                    $('#dp_'+registro.fk_procesos).text(registro.cantidad)
                })
            })
        })
    }

    function cargarRegistros(datos, callback){
        enviarPeticion('solicitudes', 'getSolicitudes', datos, function(r){
            let procesos = [0]
            let fila = ''
            let colorBadge = ''            
            r.data.map(registro => {
                procesos.push(registro.idProceso)
                colorBadge = getColor(registro.tiempo)
                fila += `<tr id="${registro.id}">
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
                                            <button type="button" class="btn btn-default btn-sm" onClick="mostrarDetalle(${registro.id},'ninguno')" title="ver detalle">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <div style="position: relative;display: inline-block;">
                                                <button type="button" class="btn btn-default btn-sm" onClick="verDocumentos(${registro.id})" title="Verificar documentación">                                                
                                                    <i class="fas fa-tasks text-green"></i>
                                                </button>
                                                <span class="badge bg-warning" style="position: absolute;top: -5px;right: -5px;font-size: 0.65rem; padding: 4px 6px; border-radius: 50%;" id="dp_${registro.idProceso}">0</span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="mostrarHistorico(${registro.idProceso},${registro.id})" title="Historico">
                                                <i class="fas fa-history"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>`
            })
            $('#contenido').append(fila)
            callback(procesos)
            $('#conteo_total').text(`Total: ${r.data.length || 0}`)
        })
    }

    function verDocumentos(idSolicitud){        
        sessionStorage.setItem('solicitud', JSON.stringify(idSolicitud))
        url = 'proceso/documentacionDetalle/'
        window.open(url, '_self')
    }
</script>
</body>
</html>