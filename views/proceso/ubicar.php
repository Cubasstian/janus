<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Ubicar
                        <span>
                            <small class="badge badge-success text-xs" id="conteo_total"></small>
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Ubicar</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">   
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-sm">
                                    <thead>
                                        <tr>
                                            <th>Proceso</th>
                                            <th>Ubicación</th>
                                            <th>Vacante</th>
                                            <th>Contratista</th>
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
    // Variables globales para mostrarDetalle
    var estados = ['','Ocupar necesidad', 'Gestión documentación', 'Crear tercero', 'Expedir CDP', 'Ficha de requerimiento', 'CIIP', 'Examen preocupacional', 'Validación perfil', 'Recoger validación perfil', 'Minuta', 'Numerar contrato', 'Solicitud de afiliación', 'Afiliar ARL', 'Expedir RP', 'Recoger RP', 'Designar supervisor', 'Acta de inicio', 'Contratado', 'Anulado'];
    var colores = ['secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','secondary','success', 'danger'];

    function init(info){
        //Cargar registros
        cargarRegistros({criterio: 'area', estado: 2}, function(){
            console.log('Cargo...')
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
                            <td>
                                <table class="table table-striped table-sm">
                                    <tr>
                                        <td>Gerencia</td>
                                        <td>${registro.gerencia}</td>
                                    </tr>
                                    <tr>
                                        <td>Dependencia</td>
                                        <td>${registro.dependencia}</td>
                                    </tr>
                                    <tr>
                                        <td>Unidad</td>
                                        <td>${registro.unidad}</td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table class="table table-striped table-sm">
                                    <tr>
                                        <td>ID</td>
                                        <td>${registro.idVacante}</td>
                                    </tr>
                                    <tr>
                                        <td>Profesión</td>
                                        <td>${registro.profesion}</td>
                                    </tr>
                                    <tr>
                                        <td>Honorarios</td>
                                        <td class="text-right">$${currency(registro.honorarios,0)}</td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table class="table table-striped table-sm">
                                    <tr>
                                        <td>Nombre</td>
                                        <td>${registro.ps}</td>
                                    </tr>
                                    <tr>
                                        <td>Cédula</td>
                                        <td>${registro.cedula}</td>
                                    </tr>
                                </table>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-${colorBadge}">
                                    ${registro.tiempo}
                                </span>
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="mostrarDetalle(${registro.id},'ubicar')" title="ver detalle">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="aceptar(${registro.idProceso},${registro.id})" title="Aceptar asignación">
                                                <i class="fas fa-check text-success"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm text-danger" onClick="devolver(${registro.idProceso},${registro.id})" title="Devolver">
                                                <i class="fas fa-fast-backward"></i>
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
            $('#contenido').html(fila)  // Cambié append() por html() para evitar duplicados
            callback()
            $('#conteo_total').text(`Total: ${r.data.length || 0}`)
        })
    }

    function aceptar(idProceso, idSolicitud){
        Swal.fire({
            icon: 'question',
            title: 'Confirmación',
            html: `Esta de acuerdo con la asignación en el proceso #${idProceso}`,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){
                let datos = {
                    info: {
                        estado: 3
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

    function devolver(idProceso, idSolicitud){
        Swal.fire({
            title: `Devolver asiganción proceso #${idProceso}`,
            input: 'textarea',
            inputPlaceholder: 'Escribe el motivo de la devolución',
            inputValidator: (value) => {
                if (!value) {
                    return 'Por favor, escribe un motivo';
                }
            },
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                let datos = {
                    info: {
                        estado: 1,
                        observaciones: result.value
                    },
                    id: idSolicitud
                }
                enviarPeticion('solicitudes', 'setEstado', datos, function(r){
                    //Borrar registro
                    $(`#${idSolicitud}`).hide('slow')
                })
            }
        })
    }
</script>
</body>
</html>
