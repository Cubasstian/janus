<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Asignar vacante
                        <span>
                            <small class="badge badge-success text-xs" id="conteo_total"></small>
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="proceso/asignarVacante/">Pendientes</a></li>
                        <li class="breadcrumb-item active">Asignación</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="card card-outline card-success">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Proceso</th>
                                            <th>Nombre</th>
                                            <th>Cédula</th>
                                            <th>Teléfono</th>
                                            <th>Hoja de vida</th>
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

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tabla" class="table table-bordered text-xs">
                                    <thead>
                                        <tr class="text-center">
                                            <th>ID</th>
                                            <th>Dependencia</th>
                                            <th>Profesión</th>
                                            <th>Experiencia</th>
                                            <th>Objeto</th>
                                            <th>Alcance</th>
                                            <th>Conocimientos</th>
                                            <th>Honorarios</th>
                                            <th>Presupuesto</th>
                                            <th>Tiempo</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contenidoVacantes"></tbody>
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
    var idP = 0
	var idS = 0
    var ps = ''
    function init(info){
    	idS = JSON.parse(sessionStorage.getItem('solicitud'))

    	//Consulto los datos de la solicitud
        enviarPeticion('solicitudes', 'getSolicitudes', {criterio: 'id', id: idS}, function(r){
            idP = r.data[0].idProceso
            ps = r.data[0].ps
            fila = `<tr>
                        <td>${r.data[0].idProceso}</td>
                        <td>${r.data[0].ps}</td>
                        <td>${r.data[0].cedula}</td>
                        <td>${r.data[0].telefono}</td>
                        <td>
                            <button type="button" class="btn btn-default" onClick="mostrarDocumento(${r.data[0].idPS},1,2)" title="Ver hoja de vida">
                                <i class="fas fa-file-import"></i>
                            </button>
                        </td>
                    </tr>`
            $('#contenido').html(fila)
        })

        //Cargar registros de vacantes
        cargarRegistros({criterio: 'libres'}, function(){
            $("#tabla").DataTable({
                "lengthMenu": [ 50, 100, 200 ],
                "pageLength": 50,
                "language":{
                    "decimal":        "",
                    "emptyTable":     "Sin datos para mostrar",
                    "info":           "Mostrando _START_ al _END_ de _TOTAL_ registros",
                    "infoEmpty":      "Mostrando 0 to 0 of 0 entries",
                    "infoFiltered":   "(Filtrado de _MAX_ total registros)",
                    "infoPostFix":    "",
                    "thousands":      ".",
                    "lengthMenu":     "Mostrar _MENU_ registros",
                    "loadingRecords": "Cargando...",
                    "processing":     "Procesando...",
                    "search":         "Buscar:",
                    "zeroRecords":    "Ningún registro encontrado",
                    "paginate": {
                        "first":      "Primero",
                        "last":       "Último",
                        "next":       "Sig",
                        "previous":   "Ant"
                    },
                    "aria": {
                        "sortAscending":  ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    }
                }
            })
        })
    }

    function cargarRegistros(datos, callback){
        enviarPeticion('vacantes', 'getVacantes', datos, function(r){
            let fila = ''
            r.data.map(registro => {
                fila += `<tr id=${registro.id}>
                            <td>${registro.id}</td>
                            <td>
                                ${registro.gerencia}<br>
                                ${registro.dependencia}<br>
                                ${registro.unidad}
                            </td>
                            <td>${registro.profesion}</td>
                            <td>${registro.experiencia} meses</td>
                            <td>${registro.objeto}</td>
                            <td>${registro.alcance}</td>
                            <td>${registro.conocimientos}</td>
                            <td class="text-right">$${currency(registro.honorarios,0)}</td>
                            <td class="text-right">$${currency(registro.presupuesto,0)}</td>
                            <td>${registro.tiempo}</td>
                            <td>
                                <button class="btn btn-default" onClick="asignar(${registro.id})" title="Asignar">
                                    <i class="fas fa-user-tag"></i>
                                </button>
                            </td>
                        </tr>`
            })
            $('#contenidoVacantes').append(fila)
            callback()
        })
    }

    function asignar(idVacante){
        Swal.fire({
            icon: 'question',
            title: 'Confirmación',
            html: `Esta seguro de asignar la vacante con ID #${idVacante} a ${ps}`,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){
                let datos = {
                    infoV: {
                        info: {
                            estado: 'Ocupada'
                        },
                        id: idVacante
                    },
                    infoP: {
                        info: {
                            fk_vacantes: idVacante
                        },
                        id: idP
                    },
                    infoS: {
                        info: {
                            estado: 2
                        },
                        id: idS
                    }
                }
                enviarPeticion('solicitudes', 'ocuparVacante', datos, function(r){
                    url = 'proceso/asignarVacante/'
                    window.open(url, '_self')
                })
            }
        })
    }
</script>
</body>
</html>