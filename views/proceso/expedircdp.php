<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Expedir CDP
                        <span>
                            <small class="badge badge-success text-xs" id="conteo_total"></small>
                        </span>
                        <button type="button" class="btn btn-primary" id="exportar" title="Exportar">
                            <i class="fas fa-file-download"></i>
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
    var idS = 0
    function init(info){
        //Cargar registros
        cargarRegistros({criterio: 'todas', estado: 4}, function(){
            console.log('Cargo...')
        })

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
                                            <button type="button" class="btn btn-default btn-sm" onClick="aceptar(${registro.idProceso},${registro.id},${registro.presupuesto})" title="Pasar a generar CDP">
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