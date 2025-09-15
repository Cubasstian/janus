<?php require('views/header.php');?>

<div class="content-wrapper">
	<section class="content-header">
		<div class="container">
			<div class="row mb-2">
				<div class="col-sm-6">
            		<h1>Perfil</h1>
          		</div>
          		<div class="col-sm-6">
            		<ol class="breadcrumb float-sm-right">
              			<li class="breadcrumb-item"><a href="main/home/">Inicio</a></li>
              			<li class="breadcrumb-item active">Perfil</li>
            		</ol>
          		</div>
        	</div>
    	</div>
    </section>

    <section class="content">
    	<div class="container">
    		<div class="row">
    			<div class="col">
    				<div class="card">
    					<div class="card-header p-2">
    						<ul class="nav nav-pills">
	    						<li class="nav-item">
	    							<a class="nav-link active" href="#datos" data-toggle="tab">Información</a>
	    						</li>
	                  			<li class="nav-item" id="panelClave" style="display: none;">
	                  				<a class="nav-link" href="#clave" data-toggle="tab">Clave</a>
	                  			</li>
	                  		</ul>
    					</div>
    					<div class="card-body">
    						<div class="tab-content">
    							<div class="active tab-pane" id="datos">
    								<div class="table-responsive">
                    					<table class="table table-bordered">
                    						<tr>
                        						<th style="width:30%">Rol:</th>
                        						<td id="rol"></td>
                      						</tr>
                      						<tr>
                        						<th>Gerencia:</th>
                        						<td id="gerencia"></td>
                      						</tr>
                    						<tr>
                        						<th>Nombre:</th>
                        						<td id="nombre"></td>
                      						</tr>
                      						<tr>
                        						<th>Cédula:</th>
                        						<td id="cedula"></td>
                      						</tr>
                      						<tr>
                        						<th>Login:</th>
                        						<td id="login"></td>
                      						</tr>
                    					</table>
					                </div>
    							</div>
    							<div class="tab-pane" id="clave">
    								<form id="formularioClave">
    									<div class="form-group">
    										<label for="nombres">Clave nueva</label>
                        					<input type="password" class="form-control" name="password" id="password1" required="required">
                    					</div>
                    					<div class="form-group">
                    						<label for="nombres">Repite la clave</label>
                        					<input type="password" class="form-control" id="password2" required="required">
                    					</div>
                    					<div class="form-group text-center">
                            				<button type="submit" class="btn btn-default">
                            					Actualizar
                            				</button>
                        				</div>
    								</form>
    							</div>
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
	function init(info){
		id = info.data.usuario.id
		if(info.data.usuario.rol == 'PS'){
			$('#panelClave').show()
		}

		//Cargar información base
		enviarPeticion('usuarios', 'getUsuarios', {criterio: 'id', id: id}, function(r){
			$('#rol').text(r.data[0].rol)
			$('#gerencia').text(r.data[0].gerencia)
			$('#nombre').text(r.data[0].nombre)
			$('#cedula').text(r.data[0].cedula)
			$('#login').text(r.data[0].login)
		})

		//Cambiar clave
        $('#formularioClave').on('submit', function(e){
            e.preventDefault()
            if($('#password1').val() != $('#password2').val()){
                toastr.error("Las contraseñas deben ser iguales")
            }else{                
                enviarPeticion('usuarios', 'setPassword', {info: {password: $('#password1').val()}, id:id}, function(r){
                    toastr.success('Se cambio la clave correctamente')
                })
            }
        })
	}
</script>
</body>
</html>