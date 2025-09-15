<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <span id="titulo"></span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="necesidades/menu/">Menú</a></li>
                        <li class="breadcrumb-item"><a href="necesidades/listar/">Listar</a></li>
                        <li class="breadcrumb-item active">Gestionar</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <form id="formularioNecesidades">
                        <div class="card card-outline card-success">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="pacc">PACC(*)</label>
                                            <input type="text" class="form-control" name="pacc" id="pacc" required="required">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="dependencia">Dependencia</label>
                                            <select class="form-control" id="dependencia" required="required"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fk_dependencias">Unidad</label>
                                            <select class="form-control" name="fk_dependencias" id="fk_dependencias" required="required"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="definicion_tecnica">Definición técnica(*)</label>
                                            <input type="text" class="form-control" name="definicion_tecnica" id="definicion_tecnica" required="required">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="profesion">Profesión(*)</label>
                                            <input type="text" class="form-control" name="profesion" id="profesion" required="required">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="grado">Grado(*)</label>
                                            <select class="form-control" name="grado" id="grado" required="required">
                                                <option value="">Seleccione...</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nivel">Nivel(*)</label>
                                            <select class="form-control" name="nivel" id="nivel" required="required">
                                                <option value="">Seleccione...</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="justificacion">Justificación(*)</label>
                                            <textarea class="form-control" rows="4" name="justificacion" id="justificacion" required="required"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="objeto">Objeto(*)</label>
                                            <textarea class="form-control" rows="4" name="objeto" id="objeto" required="required"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="alcance">Alcance(*)</label>
                                            <textarea class="form-control" rows="5" name="alcance" id="alcance" required="required"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="conocimientos">Conocimientos</label>
                                            <textarea class="form-control" rows="5" name="conocimientos" id="conocimientos"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="honorarios" id="mascaraH">Honorarios/mes</label>
                                            <input type="number" class="form-control" name="honorarios" id="honorarios" required="required">
                                            <small class="text-muted" id="labelMaximo"></small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="presupuesto" id="mascaraP">Presupuesto</label>
                                            <input type="number" class="form-control" name="presupuesto" id="presupuesto" required="required">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-6">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Imputaciones</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-success" id="botonAbrirModalImputaciones">
                                    Asociar imputación
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">                                
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Imputación</th>
                                        <th>Valor</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody id="listadoImputaciones"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-right">
                    (*) Obligatorios
                </div>
            </div>
            <div class="row">
                <div class="col-4"></div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block btn-lg mb-5 btn-submit" id="botonGuardar" form="formularioNecesidades">Guardar</button>
                    <button type="submit" class="btn btn-secondary btn-block btn-lg mb-5 btn-submit" id="botonActualizar" form="formularioNecesidades" style="display: none;">Actualizar</button>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalImputaciones">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Asociar imputación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioImputaciones">
                        <div class="form-group">
                            <label for="imputacion">Imputación</label>
                            <select class="form-control" name="imputacion" id="imputacion" required="required"></select>
                        </div>
                        <div class="row">
                            <div class="col">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Máximo</th>
                                            <th>Comprometido</th>
                                            <th>Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detalleImputacion"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="valor" id="mascaraI">Valor</label>
                            <input type="number" class="form-control" name="valor" id="valor" required="required">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" form="formularioImputaciones">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
    var id = 0
    var boton = ''
    var variables = {}
    var valoresImputaciones = []
    var imputaciones = []
    var imputacion = ''
    var saldo = 0
    function init(info){
        variables = JSON.parse(sessionStorage.getItem('variables'))
        $('#titulo').text(`Crear necesidad gerencia ${variables.gerencia.nombre} vigencia ${variables.vigencia.nombre}`)

        //llenar dependencias
        llenarSelect('dependencias', 'getDep', {gerencia:variables.gerencia.id}, 'dependencia', 'dependencia', 1, 'Seleccione...', 'dependencia')
        $('#dependencia').on('change', function(){
            llenarSelect('dependencias', 'getUnidades', {gerencia:variables.gerencia.id, dep:$(this).val()}, 'fk_dependencias', 'unidad', 1)
        })

        //Mostrar el máximo de honorarios
        $('#nivel').on('change', function(){
            enviarPeticion('topeHonorarios', 'select', {info: {fk_vigencias: variables.vigencia.id, grado: $('#grado').val(), nivel: $(this).val()}}, function(r){                
                $('#labelMaximo').text(`Máximo $${currency(r.data[0].maximo,0)}`)
            })
        })

        //Formatear input
        $('#honorarios').on('keyup', function(r){
            $('#mascaraH').text('Honorarios/mes: $' + currency($('#honorarios').val(),0))
        })
        $('#presupuesto').on('keyup', function(r){
            $('#mascaraP').text('Presupuesto: $' + currency($('#presupuesto').val(),0))
        })
        $('#valor').on('keyup', function(r){
            $('#mascaraI').text('Valor: $' + currency($('#valor').val(),0))
        })

        //Lógica de imputaciones
        //lleno con las imputaciones que aplican a la gerencia
        enviarPeticion('imputaciones', 'getImputacionesAplican', {vigencia: variables.vigencia.id, gerencia: variables.gerencia.id}, function(r){
            valoresImputaciones = r.data
            $('#imputacion').html(`<option value=''>Seleccione...</option>`)
            r.data.forEach((registro) => {
                $('#imputacion').append(`<option value="${registro.imputacion}">${registro.imputacion}</option>`)
            })
        })

        //Se abre la modal
        $('#botonAbrirModalImputaciones').on('click', function(){
            $('#formularioImputaciones')[0].reset()
            $('#modalImputaciones').modal('show')
        })

        //Logica para no sobrepasar el valor de la imputación
        $('#imputacion').on('change', function(){
            imputacion = valoresImputaciones.find(item => item.imputacion === $(this).val());
            enviarPeticion('necesidadesImputaciones', 'getComprometido', {vigencia:variables.vigencia.id, imputacion: $(this).val()}, function(r){
                let comprometido = r.data[0].comprometido || 0;
                saldo = imputacion.maximo - r.data[0].comprometido
                let fila = `<tr>
                                <td class="text-right">$${currency(imputacion.maximo,0)}</td>
                                <td class="text-right">$${currency(comprometido,0)}</td>
                                <td class="text-right">$${currency(saldo,0)}</td>
                            </tr>`
                $('#detalleImputacion').html(fila)
            })
        })

        $('#formularioImputaciones').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            //primero valido que no supere el saldo de la imputación
            if(parseInt(datos.valor) > saldo){
                toastr.error(`El valor no puede superar el saldo de la imputación que es $${currency(saldo,0)}`)
            }else{
                //primero busco si ya esta
                if(imputaciones.some(item => item.imputacion === datos.imputacion)){
                    toastr.error("Escoja una imputación diferente")
                }else{
                    imputaciones.push(datos)
                    let fila = `<tr id="${datos.imputacion}">
                                    <td>${datos.imputacion}</td>
                                    <td class="text-right">$${currency(datos.valor,0)}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" onClick="eliminarImputacion('${datos.imputacion}')" title="Eliminar">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>`
                    $('#listadoImputaciones').append(fila)
                    $('#modalImputaciones').modal('hide')
                }
            }
        })

        $('.btn-submit').on('click', function(){
            boton = $(this).attr('id')
        })

        $('#formularioNecesidades').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            datos.fk_vigencias = variables.vigencia.id
            //se valida que presupuesto sea mayor que honorarios
            if(parseInt(datos.honorarios) > parseInt(datos.presupuesto)){
                toastr.error('El presupuesto debe ser mayor a los honorarios')
            }else if(parseInt(datos.presupuesto)/parseInt(datos.honorarios) > 12){
                toastr.error('El presupuesto no debe superar 12 veces los honorarios')
            }else{
                //Se calcula el máximo de honorarios
                enviarPeticion('topeHonorarios', 'select', {info: {fk_vigencias: variables.vigencia.id, grado: datos.grado, nivel: datos.nivel}}, function(r){
                    if(parseInt(datos.honorarios) > r.data[0].maximo){
                        toastr.error(`Los honorarios no deben superar $${currency(r.data[0].maximo,0)}`)
                    }else{
                        //Antes de guardar verifico que sumen los valores en ambos lados
                        const suma = imputaciones.reduce((suma, item) => suma + parseInt(item.valor), 0)
                        if($('#presupuesto').val() != suma){
                            toastr.error('El presupuesto y el valor de las imputaciones no da igual')
                        }else{
                            if(boton == 'botonGuardar'){
                                enviarPeticion('necesidades', 'crear', {info: datos, imputaciones: imputaciones}, function(r){
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Confimación',
                                        text: `Se creo correctamente la necesidad con código ${r.insertId}`
                                    }).then((result) =>{
                                        window.location.href = 'necesidades/listar/'
                                    })
                                })
                            }else{                
                                enviarPeticion('vacantes', 'update', {info: datos, id: id}, function(r){
                                    toastr.success('Se actualizó correctamente')
                                    cargarRegistros({criterio: 'id', valor: id}, 'actualizar', function(){
                                        $('#modalVacantes').modal('hide')
                                        sumaActual()
                                    })
                                })
                            }
                        }
                        
                    }
                })
            }
        })
    }

    function eliminarImputacion(idImputacion){
        imputaciones = imputaciones.filter(item => item.imputacion !== idImputacion);
        let safeSelector = '#' + escapeJquerySelector(idImputacion);
        $(safeSelector).remove()
    }

    function escapeJquerySelector(id) {
        return id.replace(/([:.\\[\],])/g, '\\$1');
    }
</script>
</body>
</html>