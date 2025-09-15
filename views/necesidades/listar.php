<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <span id="titulo"></span>
                        <a href="necesidades/gestionar/" class="btn btn-success">
                            Crear
                        </a>
                    </h1>
                </div>
                <div class="col-sm-3" id="panelPresupuesto"></div>
                <div class="col-sm-3">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="vacantes/menu/">Menú</a></li>
                        <li class="breadcrumb-item active">Listar</li>
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
                                <table id="tabla" class="table table-bordered text-xs">
                                    <thead>
                                        <tr class="text-center">
                                            <th>ID</th>
                                            <th>Dependencia</th>
                                            <th>Unidad</th>
                                            <th>Definición técnica</th>                         
                                            <th>Honorarios</th>
                                            <th>Presupuesto</th>
                                            <th>Estado</th>
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

<script type="text/javascript">
    var id = 0
    var boton = ''
    var variables = {}
    function init(info){
        variables = JSON.parse(sessionStorage.getItem('variables'))
        $('#titulo').text(`Necesidades gerencia ${variables.gerencia.nombre} vigencia ${variables.vigencia.nombre}`)

        //Cargar registro
        cargarRegistros({criterio: 'gerenciaVigencia', gerencia: variables.gerencia.id, vigencia: variables.vigencia.id}, 'crear', function(){
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

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('necesidades', 'getNecesidades', datos, function(r){
            let fila = ''
            let botonEditar = ''
            let colores = {
                'Libre': 'secondary',
                'Ocupada': 'success'
            }
            r.data.map(registro => {
                botonEditar = ''
                /*if(registro.estado == 'Libre'){
                    botonEditar = ` <button type="button" class="btn btn-default btn-sm" onClick="mostrarModalEditarVacantes(${registro.id})" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>`
                }*/
                fila += `<tr id=${registro.id}>
                            <td>${registro.id}</td>
                            <td>${registro.dependencia}</td>
                            <td>${registro.unidad}</td>
                            <td>${registro.definicion_tecnica}</td>
                            <td class="text-right">$${currency(registro.honorarios,0)}</td>
                            <td class="text-right">$${currency(registro.presupuesto,0)}</td>
                            <td class="text-center">
                                <span class="badge badge-${colores[registro.estado]}">
                                    ${registro.estado}
                                </span>
                            </td>
                            <td>
                                ${botonEditar}
                            </td>
                        </tr>`
            })            
            if(accion == 'crear'){
                $('#contenido').append(fila)    
            }else{
                $('#'+r.data[0].id).replaceWith(fila)
            }
            callback()
        })
    }
</script>
</body>
</html>