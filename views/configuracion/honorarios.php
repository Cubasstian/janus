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
                                    <i class="fas fa-money-bill-wave"></i>
                                    Gestión de Tope de Honorarios
                                </h3>
                                <button id="botonMostrarModalHonorarios" type="button" class="btn-kit btn-kit-secondary">
                                    <i class="fas fa-plus mr-1"></i>
                                    Crear Tope
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filtros personalizados -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="form-group-kit">
                                        <label for="filtroVigencia">Filtrar por Vigencia</label>
                                        <select class="input-kit" id="filtroVigencia">
                                            <option value="">Todas las vigencias</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-kit">
                                        <label for="filtroGrado">Filtrar por Grado</label>
                                        <input type="text" class="input-kit" id="filtroGrado" placeholder="Ej: 1">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-kit">
                                        <label for="filtroNivel">Filtrar por Nivel</label>
                                        <input type="text" class="input-kit" id="filtroNivel" placeholder="Ej: A">
                                    </div>
                                </div>
                                <div class="col-md-3">
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
                                            <th>Grado</th>
                                            <th>Nivel</th>
                                            <th>Máximo</th>
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

    <!-- Modal para crear/editar tope honorarios -->
    <div class="modal fade" id="modalHonorarios" tabindex="-1" aria-labelledby="modalHonorariosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHonorariosTitulo">Gestión de Tope de Honorarios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioHonorarios">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-kit">
                                    <label for="fk_vigencias">Vigencia</label>
                                    <select class="input-kit" name="fk_vigencias" id="fk_vigencias" required>
                                        <option value="">Seleccione una vigencia</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-kit">
                                    <label for="grado">Grado</label>
                                    <input type="number" class="input-kit" name="grado" id="grado" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-kit">
                                    <label for="nivel">Nivel</label>
                                    <input type="text" class="input-kit" name="nivel" id="nivel" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-kit">
                                    <label for="maximo">Monto Máximo</label>
                                    <input type="number" class="input-kit" name="maximo" id="maximo" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-kit btn-kit-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-kit btn-kit-primary" id="botonGuardarHonorarios" form="formularioHonorarios">Guardar</button>
                    <button type="submit" class="btn-kit btn-kit-primary" id="botonActualizarHonorarios" form="formularioHonorarios">Actualizar</button>
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
        // Cargar vigencias para el filtro
        cargarVigencias();
        
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

        $('#botonMostrarModalHonorarios').on('click', function(){
            $('#formularioHonorarios')[0].reset()
            $('#modalHonorariosTitulo').text('Nuevo Tope de Honorarios')
            $('#botonGuardarHonorarios').show()
            $('#botonActualizarHonorarios').hide()
            cargarVigenciasModal();
            
            try {
                $('#modalHonorarios').modal('show');
            } catch(e) {
                console.log('Error con modal bootstrap, intentando método alternativo');
                setTimeout(() => {
                    document.getElementById('modalHonorarios').style.display = 'block';
                    document.body.classList.add('modal-open');
                }, 100);
            }
        })

        $('.btn-submit, .btn-kit-primary').on('click', function(){
            boton = $(this).attr('id')
        })      

        $('#formularioHonorarios').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            if(boton == 'botonGuardarHonorarios'){
                enviarPeticion('topeHonorarios', 'insert', {info: datos}, function(r){
                    if(r.ejecuto){
                        toastr.success('Se creó correctamente')
                        cargarRegistros({info: {id: r.insertId}}, 'crear', function(){
                            try {
                                $('#modalHonorarios').modal('hide');
                            } catch(e) {
                                document.getElementById('modalHonorarios').style.display = 'none';
                                document.body.classList.remove('modal-open');
                                $('.modal-backdrop').remove();
                            }
                        })
                    } else {
                        toastr.error(r.mensajeError || 'Error al crear el registro')
                    }
                })
            }else{                
                enviarPeticion('topeHonorarios', 'update', {info: datos, id: id}, function(r){
                    if(r.ejecuto){
                        toastr.success('Se actualizó correctamente')
                        cargarRegistros({info: {id: id}}, 'actualizar', function(){
                            try {
                                $('#modalHonorarios').modal('hide');
                            } catch(e) {
                                document.getElementById('modalHonorarios').style.display = 'none';
                                document.body.classList.remove('modal-open');
                                $('.modal-backdrop').remove();
                            }
                        })
                    } else {
                        toastr.error(r.mensajeError || 'Error al actualizar el registro')
                    }
                })
            }
        })
    }
    
    function cargarVigencias(){
        enviarPeticion('vigencias', 'select', {info: {estado: 'Activo'}}, function(r){
            let opciones = '<option value="">Todas las vigencias</option>';
            r.data.map(vigencia => {
                opciones += `<option value="${vigencia.id}">${vigencia.vigencia}</option>`;
            });
            $('#filtroVigencia').html(opciones);
        });
    }
    
    function cargarVigenciasModal(){
        enviarPeticion('vigencias', 'select', {info: {estado: 'Activo'}}, function(r){
            let opciones = '<option value="">Seleccione una vigencia</option>';
            r.data.map(vigencia => {
                opciones += `<option value="${vigencia.id}">${vigencia.vigencia}</option>`;
            });
            $('#fk_vigencias').html(opciones);
        });
    }
    
    function configurarFiltros() {
        // Filtro por vigencia
        $('#filtroVigencia').on('change', function() {
            const vigencia = this.value;
            if (tablaDataTable) {
                tablaDataTable.column(0).search(vigencia).draw();
            }
        });
        
        // Filtro por grado
        $('#filtroGrado').on('keyup', function() {
            const grado = this.value;
            if (tablaDataTable) {
                tablaDataTable.column(1).search(grado).draw();
            }
        });
        
        // Filtro por nivel
        $('#filtroNivel').on('keyup', function() {
            const nivel = this.value;
            if (tablaDataTable) {
                tablaDataTable.column(2).search(nivel).draw();
            }
        });
        
        // Limpiar filtros
        $('#limpiarFiltros').on('click', function() {
            $('#filtroVigencia').val('');
            $('#filtroGrado').val('');
            $('#filtroNivel').val('');
            if (tablaDataTable) {
                tablaDataTable.search('').columns().search('').draw();
            }
        });
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('topeHonorarios', 'select', datos, function(r){
            console.log('Respuesta del servidor:', r); // Debug para ver qué datos llegan
            
            // Verificar si la consulta fue exitosa
            if(!r.ejecuto){
                console.error('Error en la consulta:', r.mensajeError);
                toastr.error('Error al cargar los datos: ' + r.mensajeError);
                callback();
                return;
            }
            
            // Verificar si hay datos
            if(!r.data || !Array.isArray(r.data)){
                console.log('No hay datos para mostrar');
                $('#contenido').html('<tr><td colspan="5" class="text-center">No hay registros para mostrar</td></tr>');
                callback();
                return;
            }
            
            let fila = ''
            r.data.map(registro => {
                // Formatear el maximo, manejar caso cuando sea null o undefined
                let maximoFormateado = registro.maximo ? new Intl.NumberFormat('es-CO').format(registro.maximo) : 'Sin asignar';
                
                fila += `<tr id=${registro.id}>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar text-primary mr-2"></i>
                                    <span>${registro.vigencia || 'Vigencia ' + registro.fk_vigencias}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-info badge-pill">${registro.grado || 'N/A'}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-secondary badge-pill">${registro.nivel || 'N/A'}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-dollar-sign text-success mr-2"></i>
                                    <span>${maximoFormateado}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <button class="btn-kit btn-kit-outline-dark" onClick="mostrarModalEditarHonorarios(${registro.id})" title="Editar tope" data-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>`
            })            
            
            if(accion == 'crear'){
                $('#contenido').html(fila)      
            }else{
                if(r.data.length > 0){
                    $('#'+r.data[0].id).replaceWith(fila)
                }
            }
            
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip()
            
            callback()
        })
    }

    function mostrarModalEditarHonorarios(idHonorarios){
        id = idHonorarios
        cargarVigenciasModal();
        llenarFormulario('formularioHonorarios', 'topeHonorarios', 'select', {info:{id: idHonorarios}}, function(r){
            $('#modalHonorariosTitulo').text('Editar Tope de Honorarios')
            $('#botonGuardarHonorarios').hide()
            $('#botonActualizarHonorarios').show()
            
            try {
                $('#modalHonorarios').modal('show');
            } catch(e) {
                console.log('Error con modal bootstrap, intentando método alternativo');
                setTimeout(() => {
                    document.getElementById('modalHonorarios').style.display = 'block';
                    document.body.classList.add('modal-open');
                }, 100);
            }
        })
    }
</script>
</body>
</html>