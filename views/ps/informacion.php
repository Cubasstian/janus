<?php require('views/header.php');?>

<div class="content-wrapper">
	<section class="content-header">
		<div class="container">
			<div class="row mb-2">
				<div class="col-sm-6">
            		<h1>Información personal</h1>
          		</div>
          		<div class="col-sm-6">
            		<ol class="breadcrumb float-sm-right">
              			<li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
              			<li class="breadcrumb-item active">Información</li>
            		</ol>
          		</div>
        	</div>
    	</div>
    </section>

    <section class="content">
    	<div class="container">
    		<form id="formularioPersonas">
    			<div class="card card-outline card-success">
                    <div class="card-body">                    	
                        <div class="form-group">
                            <label for="nombre">Nombre(*)</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" required="required">
                        </div>
		                <div class="row">
		                    <div class="col-sm-6">
		                        <div class="form-group">
		                            <label for="cedula">Cédula</label>
		                            <input type="number" class="form-control" id="cedula" disabled="disabled">
		                        </div>
		                    </div>
		                    <div class="col-sm-4">
				                <div class="form-group">
		                            <label for="fecha_nacimiento">Fecha nacimiento(*)</label>
		                            <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" required="required">
		                        </div>
		                    </div>
		                    <div class="col-sm-2">
				                <div class="form-group">
		                            <label for="sexo">Sexo(*)</label>
		                            <select class="form-control" name="sexo" id="sexo" required="required">
                                		<option value="F">Femenino</option>
                                		<option value="M">Masculino</option>
                            		</select>
		                        </div>
		                    </div>
		                </div>
		                <div class="row">
		                    <div class="col-sm-8">
				                <div class="form-group">
		                            <label for="correo">Correo(*)</label>
		                            <input type="email" class="form-control" name="correo" id="correo" required="required">
		                        </div>
		                    </div>
		                    <div class="col-sm-4">
		                    	<div class="form-group">
		                            <label for="sexo">Etnia(*)</label>
		                            <select class="form-control" name="etnia" id="etnia" required="required">
                                		<option value="">Seleccione...</option>
                                        <option value="Afro">Afro</option>                            
                                        <option value="Indigena">Indigena</option>
                                        <option value="Rrom">Rrom</option>
                                        <option value="Mestizo">Mestizo</option>
                                        <option value="Otro">Otro</option>
                                        <option value="Ninguno">Ninguno</option>
                            		</select>
		                        </div>
		                    </div>
		                </div>
		                <div class="row">
		                    <div class="col-sm-4">
		                        <div class="form-group">
		                            <label for="telefono">Teléfono(*)</label>
		                            <input type="numer" class="form-control" name="telefono" id="telefono" required="required">
		                        </div>
		                    </div>
		                    <div class="col-sm-4">
		                        <div class="form-group">
                                    <label for="fk_ciudades">Ciudad(*)</label>
                                    <select class="form-control" name="fk_ciudades" id="fk_ciudades" required="required"></select>
                                </div>
		                    </div>
		                    <div class="col-sm-4">
		                        <div class="form-group">
		                            <label for="direccion">Dirección(*)</label>
		                            <input type="text" class="form-control" name="direccion" id="direccion" required="required">
		                        </div>
		                    </div>
		                </div>
                        <div class="row">
		                    <div class="col-sm-4">
		                        <div class="form-group">
                                    <label for="fk_eps">EPS(*)</label>
                                    <select class="form-control" name="fk_eps" id="fk_eps" required="required"></select>
                                </div>
		                    </div>
		                    <!--div class="col-sm-4">
		                        <div class="form-group">
                                    <label for="fk_arl">ARL</label>
                                    <select class="form-control" name="fk_arl" id="fk_arl" required="required"></select>
                                </div>
		                    </div-->
		                    <div class="col-sm-4">
		                        <div class="form-group">
                                    <label for="fk_fondos_pension">Fondo de pensión(*)</label>
                                    <select class="form-control" name="fk_fondos_pension" id="fk_fondos_pension" required="required"></select>
                                </div>
		                    </div>
		                </div>
                    </div>
                </div>

            </form>
            <div class="row">
                <div class="col text-right">
                    (*) Obligatorios
                </div>
            </div>
            <div class="row">
                <div class="col-4"></div>                
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block btn-lg mb-5" form="formularioPersonas">Guardar</button>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
	var id = 0
	function init(info){
		id = info.data.usuario.id
		//LLenar eps
		llenarSelectCallback('ciudades', 'select', {info:{estado: 'activo'}, orden: 'nombre'}, 'fk_ciudades', 'nombre', 1, 'Seleccione...', 'id', function(){
			llenarSelectCallback('eps', 'select', {info:{estado: 'activo'}, orden: 'nombre'}, 'fk_eps', 'nombre', 1, 'Seleccione...', 'id', function(){
				llenarSelectCallback('arl', 'select', {info:{estado: 'activo'}, orden: 'nombre'}, 'fk_arl', 'nombre', 1, 'Seleccione...', 'id', function(){
					llenarSelectCallback('fondosPension', 'select', {info:{estado: 'activo'}, orden: 'nombre'}, 'fk_fondos_pension', 'nombre', 1, 'Seleccione...', 'id', function(){
						llenarFormulario('formularioPersonas', 'usuarios', 'getInfoPersonas', {id: id}, function(r){

						})
					})
				})
			})
		})
		$('#formularioPersonas').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            enviarPeticion('usuarios', 'update', {info: datos, id: id}, function(r){
                toastr.success('Se actualizó correctamente')
            })
        })
	}
</script>
</body>
</html>