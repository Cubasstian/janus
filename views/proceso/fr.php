<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
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
    var idP = 0
    function init(info){
        //Cargar registros
        cargarRegistros({criterio: 'todas', estado: 5}, function(){
            console.log('Cargo...')
        })

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
            let fila = ''
            let colorBadge = ''
            r.data.map(registro => {
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
                                            <button type="button" class="btn btn-default btn-sm" onClick="mostrarDetalle(${registro.id},'ninguno')" title="ver detalle">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="generar(${registro.idProceso})" title="generar FR">
                                                <i class="fas fa-file-pdf"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="aceptar(${registro.idProceso},${registro.id})" title="Pasar a generar CDP">
                                                <i class="fas fa-check text-success"></i>
                                            </button>
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
            callback()
            $('#conteo_total').text(`Total: ${r.data.length || 0}`)
        })
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
                    $(`#${idSolicitud}`).hide('slow')
                })
            }
        })
    }
</script>
</body>
</html>