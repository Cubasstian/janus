<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
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
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-kit">
                        <div class="card-header card-header-clean">
                            <h3 class="card-title">
                                <i class="fas fa-user"></i>
                                Información del Contratista
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="data-table">
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
                    <div class="card card-kit">
                        <div class="card-header card-header-clean">
                            <h3 class="card-title">
                                <i class="fas fa-clipboard-list"></i>
                                Necesidades Disponibles
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tabla" class="data-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Dependencia</th>
                                            <th>Profesión</th>
                                            <th>Objeto</th>
                                            <th>Alcance</th>
                                            <th>Conocimientos</th>
                                            <th>Honorarios</th>
                                            <th>Presupuesto</th>
                                            <th>Tiempo</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contenidoNecesidades"></tbody>
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
                            <button type="button" class="btn-kit btn-kit-outline-success btn-sm" onClick="mostrarDocumento(${r.data[0].idPS},1,2)" title="Ver hoja de vida">
                                <i class="fas fa-file-import"></i>
                            </button>
                        </td>
                    </tr>`
            $('#contenido').html(fila)
        })

        //Cargar registros de necesidades
        cargarRegistros({criterio: 'libres'}, function(){
            // Verificar si DataTable ya está inicializado
            if ($.fn.DataTable.isDataTable('#tabla')) {
                $('#tabla').DataTable().destroy();
            }
            
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
        enviarPeticion('necesidades', 'getNecesidades', datos, function(r){
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
                            <td>${registro.objeto}</td>
                            <td>${registro.alcance}</td>
                            <td>${registro.conocimientos}</td>
                            <td class="text-right">$${currency(registro.honorarios,0)}</td>
                            <td class="text-right">$${currency(registro.presupuesto,0)}</td>
                            <td>${registro.tiempo}</td>
                            <td>
                                <button type="button" class="btn-kit btn-kit-outline-primary btn-sm" onClick="asignar(${registro.id})" title="Asignar vacante">
                                    <i class="fas fa-user-tag"></i>
                                </button>
                            </td>
                        </tr>`
            })
            $('#contenidoNecesidades').html(fila) // Cambié append() por html() para evitar duplicados
            callback()
        })
    }

    function asignar(idVacante){
        Swal.fire({
            icon: 'question',
            title: 'Confirmar Asignación',
            html: `¿Está seguro de asignar la vacante <strong>#${idVacante}</strong> a <strong>${ps}</strong>?`,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check mr-1"></i>Asignar',
            cancelButtonText: '<i class="fas fa-times mr-1"></i>Cancelar',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if(result.value){
                let datos = {
                    infoN: {
                        info: {
                            estado: 'Ocupada'
                        },
                        id: idVacante
                    },
                    infoP: {
                        info: {
                            fk_Necesidades: idVacante
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
                enviarPeticion('solicitudes', 'ocuparNecesidad', datos, function(r){
                    url = 'proceso/ocuparNecesidad/'
                    window.open(url, '_self')
                })
            }
        })
    }
</script>
</body>
</html>
