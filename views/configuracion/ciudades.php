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
                                    <i class="fas fa-city"></i>
                                    Gestión de Ciudades
                                </h3>
                                <button id="botonMostrarModalCiudades" type="button" class="btn-kit btn-kit-secondary">
                                    <i class="fas fa-plus mr-1"></i>
                                    Crear Ciudad
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
                                        <label for="filtroNombre">Buscar por Nombre</label>
                                        <input type="text" class="input-kit" id="filtroNombre" placeholder="Ej: Cali">
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
                                            <th>Nombre</th>                                            
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

    <!-- Modal para crear/editar ciudades -->
    <div class="modal fade" id="modalCiudades" tabindex="-1" aria-labelledby="modalCiudadesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCiudadesTitulo">Gestión de Ciudad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioCiudades">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-kit">
                                    <label for="nombre">Nombre de la Ciudad</label>
                                    <input type="text" class="input-kit" name="nombre" id="nombre" required>
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
                    <button type="submit" class="btn-kit btn-kit-primary" id="botonGuardarCiudades" form="formularioCiudades">Guardar</button>
                    <button type="submit" class="btn-kit btn-kit-primary" id="botonActualizarCiudades" form="formularioCiudades">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
    var id
    var boton
    var tablaDataTable; // Variable global para la tabla
    
    function init(info){
        //Cargar registro
        cargarRegistros({info:{1:1}, nodefault:1}, 'crear', function(){
            // Verificar si DataTable ya existe y destruirlo
            if ($.fn.DataTable.isDataTable('#tabla')) {
                $('#tabla').DataTable().destroy();
            }
            
            tablaDataTable = $("#tabla").DataTable({
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
            });
            
            // Configurar filtros personalizados
            configurarFiltros();
        })  

        $('#botonMostrarModalCiudades').on('click', function(){
            $('#formularioCiudades')[0].reset()
            $('#modalCiudadesTitulo').text('Nueva Ciudad')
            $('#botonGuardarCiudades').show()
            $('#botonActualizarCiudades').hide()
            
            // Múltiples intentos para mostrar el modal
            try {
                $('#modalCiudades').modal('show');
            } catch(e) {
                console.log('Error con modal bootstrap, intentando método alternativo');
                setTimeout(() => {
                    document.getElementById('modalCiudades').style.display = 'block';
                    document.body.classList.add('modal-open');
                }, 100);
            }
        })

        $('.btn-submit, .btn-kit-primary').on('click', function(){
            boton = $(this).attr('id')
        })      

        $('#formularioCiudades').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            if(boton == 'botonGuardarCiudades'){
                enviarPeticion('ciudades', 'insert', {info: datos}, function(r){
                    toastr.success('Se creó correctamente')
                    cargarRegistros({info: {id: r.insertId}}, 'crear', function(){
                        try {
                            $('#modalCiudades').modal('hide');
                        } catch(e) {
                            document.getElementById('modalCiudades').style.display = 'none';
                            document.body.classList.remove('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    })
                })
            }else{                
                enviarPeticion('ciudades', 'update', {info: datos, id: id}, function(r){
                    toastr.success('Se actualizó correctamente')
                    cargarRegistros({info: {id: id}}, 'actualizar', function(){
                        try {
                            $('#modalCiudades').modal('hide');
                        } catch(e) {
                            document.getElementById('modalCiudades').style.display = 'none';
                            document.body.classList.remove('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    })
                })
            }
        })
    }
    
    function configurarFiltros() {
        // Filtro por estado
        $('#filtroEstado').on('change', function() {
            const estado = this.value;
            if (tablaDataTable) {
                tablaDataTable.column(1).search(estado).draw();
            }
        });
        
        // Filtro por nombre
        $('#filtroNombre').on('keyup', function() {
            const nombre = this.value;
            if (tablaDataTable) {
                tablaDataTable.column(0).search(nombre).draw();
            }
        });
        
        // Limpiar filtros
        $('#limpiarFiltros').on('click', function() {
            $('#filtroEstado').val('');
            $('#filtroNombre').val('');
            if (tablaDataTable) {
                tablaDataTable.search('').columns().search('').draw();
            }
        });
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('ciudades', 'select', datos, function(r){
            let fila = ''
            let colores = {
                'Activo': 'success',
                'Cancelado': 'danger'
            }
            r.data.map(registro => {
                fila += `<tr id=${registro.id}>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-city text-primary mr-2"></i>
                                    <span>${registro.nombre}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-${colores[registro.estado]} badge-pill px-3">
                                    <i class="fas fa-${registro.estado === 'Activo' ? 'check' : 'times'} mr-1"></i>
                                    ${registro.estado}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn-kit btn-kit-outline-dark" onClick="mostrarModalEditarCiudades(${registro.id})" title="Editar ciudad" data-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>`
            })            
            if(accion == 'crear'){
                $('#contenido').html(fila)      
            }else{
                $('#'+r.data[0].id).replaceWith(fila)
            }
            
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip()
            
            callback()
        })
    }

    function mostrarModalEditarCiudades(idCiudades){
        id = idCiudades
        llenarFormulario('formularioCiudades', 'ciudades', 'select', {info:{id: idCiudades}}, function(r){
            $('#modalCiudadesTitulo').text('Editar Ciudad')
            $('#botonGuardarCiudades').hide()
            $('#botonActualizarCiudades').show()
            
            try {
                $('#modalCiudades').modal('show');
            } catch(e) {
                console.log('Error con modal bootstrap, intentando método alternativo');
                setTimeout(() => {
                    document.getElementById('modalCiudades').style.display = 'block';
                    document.body.classList.add('modal-open');
                }, 100);
            }
        })
    }
</script>
</body>
</html>