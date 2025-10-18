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
                                    <i class="fas fa-calculator"></i>
                                    Gestión de Imputaciones
                                </h3>
                                <button id="botonMostrarModalImputaciones" type="button" class="btn-kit btn-kit-secondary">
                                    <i class="fas fa-plus mr-1"></i>
                                    Crear Imputación
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filtros personalizados -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="form-group-kit">
                                        <label for="vigencia">Filtrar por Vigencia</label>
                                        <select class="input-kit" id="vigencia">
                                            <option value="">Todas las vigencias</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-kit">
                                        <label for="filtroImputacion">Buscar por Imputación</label>
                                        <input type="text" class="input-kit" id="filtroImputacion" placeholder="Ej: 123456">
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
                                            <th>Imputación</th>
                                            <th>Máximo / Comprometido</th>
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

    <!-- Modal para crear/editar imputaciones -->
    <div class="modal fade" id="modalImputaciones" tabindex="-1" aria-labelledby="modalImputacionesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImputacionesTitulo">Gestión de Imputación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioImputaciones">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-kit">
                                    <label for="fk_vigencias">Vigencia</label>
                                    <select class="input-kit" name="fk_vigencias" id="fk_vigencias" required>
                                        <option value="">Seleccione una vigencia</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-kit">
                                    <label for="imputacion">Imputación</label>
                                    <input type="text" class="input-kit" name="imputacion" id="imputacion" required>
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
                    <button type="submit" class="btn-kit btn-kit-primary" id="botonGuardarImputaciones" form="formularioImputaciones">Guardar</button>
                    <button type="submit" class="btn-kit btn-kit-primary" id="botonActualizarImputaciones" form="formularioImputaciones">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
    var id = 0
    var boton = ''
    var tablaDataTable; // Variable global para la tabla
    
    function init(info){
        // Llenar vigencias - corregir el nombre del campo para el filtro
        llenarSelect('vigencias', 'select', {info:{estado: 'Activo'}}, 'vigencia', 'vigencia', 1)
        
        $('#vigencia').on('change', function(){
            let vigencia = $(this).val()
            
            // Destruir DataTable ANTES de limpiar contenido
            if ($.fn.DataTable.isDataTable('#tabla')) {
                $('#tabla').DataTable().destroy();
                tablaDataTable = null;
            }
            
            $('#contenido').empty()
            
            if($(this).val() == ""){
                toastr.error('Debe escoger una vigencia')
            }else{
                cargarRegistros({criterio: 'vigencia', valor: vigencia}, 'crear', function(){
                    // Configurar DataTable después de cargar los datos
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
                            }
                        }
                    });
                    
                    // Configurar filtros personalizados
                    configurarFiltros();
                    
                    // Cargar datos de comprometido
                    enviarPeticion('necesidadesImputaciones', 'getTodas', {vigencia: vigencia}, function(r){
                        try {
                            r.data.map(registro => {
                                let labelElement = document.getElementById('label_' + registro.imputacion);
                                let barraElement = document.getElementById('barra_' + registro.imputacion);
                                
                                if (labelElement && barraElement) {
                                    let porcentaje = (parseFloat(registro.comprometido) / parseFloat(barraElement.dataset.maximo)) * 100;
                                    porcentaje = Math.min(100, Math.max(0, porcentaje));
                                    
                                    labelElement.innerHTML = '$' + currency(registro.comprometido, 0) + ' / $' + currency(barraElement.dataset.maximo, 0);
                                    barraElement.style.width = porcentaje + '%';
                                    barraElement.innerHTML = Math.round(porcentaje) + '%';
                                    
                                    // Cambiar color según el porcentaje usando colores Kit UI
                                    if (porcentaje <= 50) {
                                        barraElement.style.backgroundColor = 'var(--color-success)'; // Verde Kit UI
                                    } else if (porcentaje <= 80) {
                                        barraElement.style.backgroundColor = 'var(--color-warning)'; // Amarillo Kit UI
                                        barraElement.style.color = 'var(--color-text-dark)'; // Texto oscuro para contraste
                                    } else {
                                        barraElement.style.backgroundColor = 'var(--color-danger)'; // Rojo Kit UI
                                        barraElement.style.color = 'var(--color-text-light)'; // Texto blanco
                                    }
                                }
                            })
                        } catch (error) {
                            console.error('Error procesando datos de comprometido:', error);
                        }
                    })
                })
            }
        })

        $('#botonMostrarModalImputaciones').on('click', function(){
            if($('#vigencia').val() == ""){
                toastr.error('Debe seleccionar una vigencia primero')
                return
            }
            $('#formularioImputaciones')[0].reset()
            
            // Llenar el select de vigencias del modal y seleccionar la vigencia actual
            llenarSelectCallback('vigencias', 'select', {info:{estado: 'Activo'}}, 'fk_vigencias', 'vigencia', 1, 'Seleccione...', 'id', function(){
                $('#fk_vigencias').val($('#vigencia').val())
                
                // Obtener el texto de la vigencia seleccionada
                let vigenciaNombre = $('#vigencia option:selected').text()
                $('#modalImputacionesTitulo').text('Nueva imputación vigencia ' + vigenciaNombre)
            })
            
            $('#botonGuardarImputaciones').show()
            $('#botonActualizarImputaciones').hide()
            
            try {
                $('#modalImputaciones').modal('show');
            } catch(e) {
                setTimeout(() => {
                    document.getElementById('modalImputaciones').style.display = 'block';
                    document.body.classList.add('modal-open');
                }, 100);
            }
        })

        $('.btn-submit, .btn-kit-primary').on('click', function(){
            boton = $(this).attr('id')
        })      

        $('#formularioImputaciones').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            if(boton == 'botonGuardarImputaciones'){
                enviarPeticion('imputaciones', 'insert', {info: datos}, function(r){
                    toastr.success('Se creó correctamente')
                    cargarRegistros({criterio: 'vigencia', valor: $('#vigencia').val()}, 'crear', function(){
                        try {
                            $('#modalImputaciones').modal('hide');
                        } catch(e) {
                            document.getElementById('modalImputaciones').style.display = 'none';
                            document.body.classList.remove('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    })
                })
            }else{                
                enviarPeticion('imputaciones', 'update', {info: datos, id: id}, function(r){
                    toastr.success('Se actualizó correctamente')
                    cargarRegistros({criterio: 'vigencia', valor: $('#vigencia').val()}, 'actualizar', function(){
                        try {
                            $('#modalImputaciones').modal('hide');
                        } catch(e) {
                            document.getElementById('modalImputaciones').style.display = 'none';
                            document.body.classList.remove('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    })
                })
            }
        })
    }
    
    function configurarFiltros() {
        // Filtro por imputación
        $('#filtroImputacion').on('keyup', function() {
            const imputacion = this.value;
            if (tablaDataTable && $.fn.DataTable.isDataTable('#tabla')) {
                tablaDataTable.column(0).search(imputacion).draw();
            }
        });
        
        // Limpiar filtros
        $('#limpiarFiltros').on('click', function() {
            $('#filtroImputacion').val('');
            if (tablaDataTable && $.fn.DataTable.isDataTable('#tabla')) {
                tablaDataTable.search('').columns().search('').draw();
            }
        });
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('imputaciones', 'getImputaciones', datos, function(r){
            let fila = ''
            
            // Verificar si hay datos válidos
            if(r && r.ejecuto && r.data && Array.isArray(r.data) && r.data.length > 0) {
                r.data.map(registro => {
                    fila += `<tr id=${registro.id}>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-tag text-primary mr-2"></i>
                                        <span>${registro.imputacion}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress-group">
                                        <span class="progress-text">&nbsp;</span>
                                        <span id="label_${registro.imputacion}" class="float-right font-weight-bold">$${currency(registro.maximo,0)}</span>
                                        <div class="progress mt-2" style="height: 20px;">
                                            <div id="barra_${registro.imputacion}" class="progress-bar" style="width: 0%; background-color: var(--color-success);" data-maximo="${registro.maximo}"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="btn-kit btn-kit-outline-dark" onClick="mostrarModalEditarImputaciones(${registro.id})" title="Editar imputación" data-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>`
                })
            }
            
            // Siempre actualizar el contenido, aunque esté vacío
            if(accion == 'crear'){
                $('#contenido').html(fila)      
            }else{
                if(r && r.data && r.data.length > 0) {
                    $('#'+r.data[0].id).replaceWith(fila)
                }
            }
            
            // Inicializar tooltips solo si hay contenido
            if(fila !== '') {
                $('[data-toggle="tooltip"]').tooltip()
            }
            
            callback()
        })
    }    function mostrarModalEditarImputaciones(idImputaciones){
        id = idImputaciones
        
        // Primero llenar el select de vigencias
        llenarSelectCallback('vigencias', 'select', {info:{estado: 'Activo'}}, 'fk_vigencias', 'vigencia', 1, 'Seleccione...', 'id', function(){
            // Después llenar el formulario con los datos de la imputación
            llenarFormulario('formularioImputaciones', 'imputaciones', 'select', {info:{id: idImputaciones}}, function(r){
                // Obtener el nombre de la vigencia después de que se llene el formulario
                setTimeout(function(){
                    let vigenciaNombre = $('#fk_vigencias option:selected').text()
                    $('#modalImputacionesTitulo').text('Editar Imputación - Vigencia: ' + vigenciaNombre)
                }, 100)
                
                $('#botonGuardarImputaciones').hide()
                $('#botonActualizarImputaciones').show()
                
                try {
                    $('#modalImputaciones').modal('show');
                } catch(e) {
                    setTimeout(() => {
                        document.getElementById('modalImputaciones').style.display = 'block';
                        document.body.classList.add('modal-open');
                    }, 100);
                }
            })
        })
    }
</script>

<!-- Modal para imputaciones -->
<div class="modal fade" id="modalImputaciones" tabindex="-1" role="dialog" aria-labelledby="modalImputacionesTitulo" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImputacionesTitulo">Nueva Imputación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form autocomplete="off" id="formularioImputaciones">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-kit">
                                <label>Vigencia</label>
                                <select name="fk_vigencias" id="fk_vigencias" class="input-kit" required>
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-kit">
                                <label>Imputación</label>
                                <input type="text" name="imputacion" id="imputacion" class="input-kit" placeholder="Ingrese la imputación" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group-kit">
                                <label>Valor Máximo</label>
                                <input type="number" name="maximo" id="maximo" class="input-kit" placeholder="Ingrese el valor máximo" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-kit btn-kit-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="formularioImputaciones" class="btn-kit btn-kit-primary" id="botonGuardarImputaciones">Guardar</button>
                <button type="submit" form="formularioImputaciones" class="btn-kit btn-kit-success" id="botonActualizarImputaciones" style="display: none;">Actualizar</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>