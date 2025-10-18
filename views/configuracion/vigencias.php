<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <!-- Header limpio sin títulos ni breadcrumbs -->
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-kit">
                        <div class="card-header card-header-clean">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-calendar-check"></i>
                                    Gestión de Vigencias
                                </h3>
                                <button id="botonMostrarModalVigencias" type="button" class="btn-kit btn-kit-secondary">
                                    <i class="fas fa-plus mr-1"></i>
                                    Crear Vigencia
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filtros personalizados -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="form-group-kit">
                                        <label for="filtroEstado">Filtrar por Estado</label>
                                        <select class="input-kit" id="filtroEstado">
                                            <option value="">Todos los estados</option>
                                            <option value="Activo">Activo</option>
                                            <option value="Cancelado">Cancelado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-kit">
                                        <label for="filtroVigencia">Buscar por Vigencia</label>
                                        <input type="text" class="input-kit" id="filtroVigencia" placeholder="Ej: 2024">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-kit">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="button" class="btn-kit btn-kit-secondary w-100" id="limpiarFiltros">
                                                Limpiar Filtros
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table id="tabla" class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Vigencia</th>                                            
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

    <!-- Modal para crear/editar vigencias -->
    <div class="modal fade" id="modalVigencias" tabindex="-1" aria-labelledby="modalVigenciasLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVigenciasTitulo">Gestión de Vigencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioVigencias">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-kit">
                                    <label for="vigencia">Vigencia</label>
                                    <input type="text" class="input-kit" name="vigencia" id="vigencia" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-kit">
                                    <label for="estado">Estado</label>
                                    <select class="input-kit" name="estado" id="estado" required>
                                        <option value="">Seleccione un estado</option>
                                        <option value="Activo">Activo</option>
                                        <option value="Cancelado">Cancelado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-kit btn-kit-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-kit btn-kit-primary" id="botonGuardarVigencias" form="formularioVigencias">Guardar</button>
                    <button type="submit" class="btn-kit btn-kit-primary" id="botonActualizarVigencias" form="formularioVigencias">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
    var id
    var boton
    function init(info){
        //Cargar registro
        cargarRegistros({info:{1:1}, nodefault:1}, 'crear', function(){
            // Verificar si DataTable ya existe y destruirlo
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

        $('#botonMostrarModalVigencias').on('click', function(){
            $('#formularioVigencias')[0].reset()
            $('#modalVigenciasTitulo').text('Nueva Vigencias')
            $('#botonGuardarVigencias').show()
            $('#botonActualizarVigencias').hide()
            $('#modalVigencias').modal('show')
        })

        $('.btn-submit').on('click', function(){
            boton = $(this).attr('id')
        })      

        $('#formularioVigencias').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            if(boton == 'botonGuardarVigencias'){
                enviarPeticion('vigencias', 'insert', {info: datos}, function(r){
                    toastr.success('Se creo correctamente')
                    cargarRegistros({info: {id: r.insertId}}, 'crear', function(){
                        $('#modalVigencias').modal('hide')
                    })
                })
            }else{                
                enviarPeticion('vigencias', 'update', {info: datos, id: id}, function(r){
                    toastr.success('Se actualizó correctamente')
                    cargarRegistros({info: {id: id}}, 'actualizar', function(){
                        $('#modalVigencias').modal('hide')
                    })
                })
            }
        })
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('vigencias', 'select', datos, function(r){
            let fila = ''
            let colores = {
                'Activo': 'success',
                'Cancelado': 'danger'
            }
            r.data.map(registro => {
                fila += `<tr class="table-row-modern" id=${registro.id}>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-day text-muted mr-2"></i>
                                    <span>${registro.vigencia}</span>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge badge-${colores[registro.estado]} badge-pill px-3">
                                    <i class="fas fa-${registro.estado === 'Activo' ? 'check' : 'times'} mr-1"></i>
                                    ${registro.estado}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <button class="btn btn-outline-dark btn-action" onClick="mostrarModalEditarVigencias(${registro.id})" title="Editar vigencia" data-toggle="tooltip">
                                    <i class="fas fa-edit text-dark"></i>
                                </button>
                            </td>
                        </tr>`
            })            
            if(accion == 'crear'){
                $('#contenido').html(fila)  // Cambié append() por html() para evitar duplicados    
            }else{
                $('#'+r.data[0].id).replaceWith(fila)
            }
            
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip()
            
            callback()
        })
    }

    function mostrarModalEditarVigencias(idVigencias){
        id = idVigencias
        llenarFormulario('formularioVigencias', 'vigencias', 'select', {info:{id: idVigencias}}, function(r){
            $('#modalVigenciasTitulo').text('Editar Vigencias')
            $('#botonGuardarVigencias').hide()
            $('#botonActualizarVigencias').show()
            $('#modalVigencias').modal('show')
        })
    }
</script>
</body>
</html>