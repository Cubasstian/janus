<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content" style="padding-top: 20px;">
    	<div class="container-fluid">
    		<div class="row">
    			<!-- Columna izquierda - Foto de perfil -->
    			<div class="col-md-3">
    				<div class="card card-kit">
    					<div class="card-header card-header-clean">
    						<h3 class="card-title">
    							<i class="fas fa-camera"></i>
    							Foto de Perfil
    						</h3>
    					</div>
    					<div class="card-body text-center">
    						<!-- Vista previa de la foto -->
    						<div class="mb-3">
    							<div id="avatarContainer" style="position: relative; display: inline-block; width: 180px; height: 180px; margin: 0 auto;">
    								<!-- Spinner de carga mientras se verifica si hay foto -->
    								<div id="avatarLoading" class="rounded-circle d-flex align-items-center justify-content-center" 
    									 style="position: absolute; top: 0; left: 0; width: 180px; height: 180px; background: #f0f0f0; border: 4px solid #518711; z-index: 3;">
    									<i class="fas fa-spinner fa-spin fa-3x text-success"></i>
    								</div>
    								<!-- Foto del usuario -->
    								<img id="previewFoto" 
    									 src="" 
    									 alt="Foto de perfil" 
    									 class="img-fluid rounded-circle" 
    									 style="position: absolute; top: 0; left: 0; width: 180px; height: 180px; object-fit: cover; border: 4px solid #518711; box-shadow: 0 4px 8px rgba(0,0,0,0.1); display: none; z-index: 2;">
    								<!-- Avatar con inicial -->
    								<div id="avatarCircle" 
    									 class="rounded-circle d-flex align-items-center justify-content-center text-white font-weight-bold" 
    									 style="position: absolute; top: 0; left: 0; width: 180px; height: 180px; font-size: 72px; background: linear-gradient(135deg, #28a745, #518711); border: 4px solid #518711; box-shadow: 0 4px 8px rgba(0,0,0,0.1); display: none; z-index: 1;">
    									<span id="avatarLetter">U</span>
    								</div>
    							</div>
    						</div>
    						
    						<!-- Botones -->
    						<div class="mb-2">
    							<label for="inputFoto" class="btn-kit btn-kit-primary btn-block mb-2">
    								<i class="fas fa-upload mr-2"></i>Subir Foto
    							</label>
    							<input type="file" 
    								   id="inputFoto" 
    								   accept="image/jpeg,image/png,image/gif" 
    								   style="display: none;">
    						</div>
    						<button type="button" 
    								id="btnEliminarFoto" 
    								class="btn-kit btn-kit-outline-danger btn-block" 
    								style="display: none;">
    							<i class="fas fa-trash mr-2"></i>Eliminar Foto
    						</button>
    						
    						<p class="text-muted small mt-3">
    							<i class="fas fa-info-circle"></i> 
    							Formatos: JPG, PNG, GIF<br>
    							Tamaño máximo: 2MB
    						</p>
    					</div>
    				</div>
    			</div>
    			
    			<!-- Columna derecha - Formulario -->
    			<div class="col-md-9">
    				<form id="formularioPersonas">
    					<div class="card card-kit">
    						<div class="card-header card-header-clean">
    							<h3 class="card-title">
    								<i class="fas fa-user-edit"></i>
    								Información Personal
    							</h3>
    						</div>
                    		<div class="card-body">                    	
                        		<div class="form-group-kit">
                            		<label for="nombre">Nombre Completo (*)</label>
                            		<input type="text" class="input-kit" name="nombre" id="nombre" required="required">
                        		</div>
		                <div class="row">
		                    <div class="col-sm-6">
		                        <div class="form-group-kit">
		                            <label for="cedula">Cédula</label>
		                            <input type="number" class="input-kit" id="cedula" disabled="disabled">
		                        </div>
		                    </div>
		                    <div class="col-sm-4">
				                <div class="form-group-kit">
		                            <label for="fecha_nacimiento">Fecha de Nacimiento (*)</label>
		                            <input type="date" class="input-kit" name="fecha_nacimiento" id="fecha_nacimiento" required="required">
		                        </div>
		                    </div>
		                    <div class="col-sm-2">
				                <div class="form-group-kit">
		                            <label for="sexo">Sexo (*)</label>
		                            <select class="input-kit" name="sexo" id="sexo" required="required">
                                		<option value="F">Femenino</option>
                                		<option value="M">Masculino</option>
                            		</select>
		                        </div>
		                    </div>
		                </div>
		                <div class="row">
		                    <div class="col-sm-8">
				                <div class="form-group-kit">
		                            <label for="correo">Correo Electrónico (*)</label>
		                            <input type="email" class="input-kit" name="correo" id="correo" required="required">
		                        </div>
		                    </div>
		                    <div class="col-sm-4">
		                    	<div class="form-group-kit">
		                            <label for="etnia">Etnia (*)</label>
		                            <select class="input-kit" name="etnia" id="etnia" required="required">
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
		                        <div class="form-group-kit">
		                            <label for="telefono">Teléfono (*)</label>
		                            <input type="number" class="input-kit" name="telefono" id="telefono" required="required">
		                        </div>
		                    </div>
		                    <div class="col-sm-4">
		                        <div class="form-group-kit">
                                    <label for="fk_ciudades">Ciudad (*)</label>
                                    <select class="input-kit" name="fk_ciudades" id="fk_ciudades" required="required"></select>
                                </div>
		                    </div>
		                    <div class="col-sm-4">
		                        <div class="form-group-kit">
		                            <label for="direccion">Dirección (*)</label>
		                            <input type="text" class="input-kit" name="direccion" id="direccion" required="required">
		                        </div>
		                    </div>
		                </div>
                        <div class="row">
		                    <div class="col-sm-6">
		                        <div class="form-group-kit">
                                    <label for="fk_eps">EPS (*)</label>
                                    <select class="input-kit" name="fk_eps" id="fk_eps" required="required"></select>
                                </div>
		                    </div>
		                    <div class="col-sm-6">
		                        <div class="form-group-kit">
                                    <label for="fk_fondos_pension">Fondo de Pensión (*)</label>
                                    <select class="input-kit" name="fk_fondos_pension" id="fk_fondos_pension" required="required"></select>
                                </div>
		                    </div>
		                </div>
		                
		                <div class="row mt-3">
		                	<div class="col text-right">
		                		<small class="text-muted">(*) Campos obligatorios</small>
		                	</div>
		                </div>
                    	</div>
                    	<div class="card-footer">
                    		<button type="submit" class="btn btn-primary">
                    			<i class="fas fa-save mr-2"></i>Guardar Cambios
                    		</button>
                    	</div>
                	</div>
            	</form>
            </div>
        </div>
    	</div>
    </section>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
	var id = 0
	// Variable para prevenir múltiples inicializaciones
	var isInitialized = false;
	
	function init(info){
		// Prevenir múltiples ejecuciones
		if (isInitialized) {
			return;
		}
		
		// Marcar inmediatamente para prevenir ejecuciones concurrentes
		isInitialized = true;
		
		// Verificar que enviarPeticion existe
		if (typeof enviarPeticion !== 'function') {
			isInitialized = false; // Reset si falla
			setTimeout(function() {
				init(info); // Reintentar después de un delay
			}, 100);
			return;
		}
		
		// Obtener los datos de sesión frescos
		var ajaxRequest = enviarPeticion('helpers', 'getSession', {1:1}, function(sessionData) {
			
			// Verificar la respuesta de la sesión
			if (!sessionData || !sessionData.ejecuto) {
				toastr.error('Error al verificar la sesión');
				isInitialized = false; // Reset para permitir reintento
				setTimeout(() => {
					window.location.href = 'main/login/';
				}, 2000);
				return;
			}
			
			// Verificar si hay datos de usuario
			if (!sessionData.data || sessionData.data.length === 0) {
				toastr.error('Sesión expirada');
				isInitialized = false; // Reset para permitir reintento
				setTimeout(() => {
					window.location.href = 'main/login/';
				}, 2000);
				return;
			}
			
			// Obtener usuario de la sesión
			const usuario = sessionData.data.usuario;
			if (!usuario) {
				toastr.error('Error: No se encontró información del usuario');
				isInitialized = false; // Reset para permitir reintento
				setTimeout(() => {
					window.location.href = 'main/login/';
				}, 2000);
				return;
			}
			
			// Verificar que es un PS
			if (usuario.rol !== 'PS') {
				toastr.warning(`Acceso denegado: Solo usuarios PS pueden acceder. Su rol actual es: ${usuario.rol}`);
				isInitialized = false; // Reset para permitir reintento
				setTimeout(() => {
					window.location.href = 'main/inicio/';
				}, 3000);
				return;
			}
			
			// Todo está bien, inicializar la aplicación
			id = usuario.id;
			
			// Mostrar mensaje de bienvenida SOLO si viene del login
			if(sessionStorage.getItem('mostrarBienvenida') === 'true'){
				const nombreUsuario = sessionStorage.getItem('nombreUsuario') || usuario.nombre;
				toastr.success(`¡Bienvenido ${nombreUsuario}!`);
				// Limpiar flag para que no se muestre en recargas
				sessionStorage.removeItem('mostrarBienvenida');
				sessionStorage.removeItem('nombreUsuario');
			}
			
			// Cargar los datos del formulario
			cargarInformacionPersonal();
			
		});
		
		// Solo agregar .fail() si el objeto Ajax existe
		if (ajaxRequest && typeof ajaxRequest.fail === 'function') {
			ajaxRequest.fail(function(xhr, status, error) {
				console.error('Error AJAX al obtener sesión:', error, xhr);
				toastr.error('Error de conexión al verificar la sesión');
				isInitialized = false; // Reset para permitir reintento
				setTimeout(() => {
					window.location.href = 'main/login/';
				}, 2000);
			});
		}
		
		// Configurar el evento de submit del formulario
		$('#formularioPersonas').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            enviarPeticion('usuarios', 'update', {info: datos, id: id}, function(r){
				if (r && r.ejecuto) {
					toastr.success('Se actualizó correctamente')
				} else {
					toastr.error(r.mensajeError || 'Error al actualizar la información')
				}
            })
        })
        
        // Manejo de subida de foto
        $('#inputFoto').on('change', function(e){
        	const archivo = e.target.files[0]
        	if(!archivo){
        		return
        	}
        	
        	// Validar tipo
        	const tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif']
        	if(!tiposPermitidos.includes(archivo.type)){
        		toastr.error('Solo se permiten archivos JPG, PNG o GIF')
        		$(this).val('')
        		return
        	}
        	
        	// Validar tamaño (2MB)
        	if(archivo.size > 2 * 1024 * 1024){
        		toastr.error('El archivo no debe superar los 2MB')
        		$(this).val('')
        		return
        	}
        	
        	// Mostrar preview
        	const reader = new FileReader()
        	reader.onload = function(e){
        		$('#avatarLoading').removeClass('d-flex').addClass('d-none')
        		$('#avatarCircle').removeClass('d-flex').addClass('d-none')
        		$('#previewFoto').attr('src', e.target.result).removeClass('d-none').css('display', 'block')
        		$('#btnEliminarFoto').show()
        	}
        	reader.readAsDataURL(archivo)
        	
        	// Subir archivo usando la API
        	const formData = new FormData()
        	formData.append('objeto', 'usuarios')
        	formData.append('metodo', 'uploadFoto')
        	formData.append('datos[id]', id)
        	formData.append('foto', archivo)
        	
        	console.log('Subiendo foto para usuario ID:', id)
        	console.log('Nombre archivo:', archivo.name)
        	console.log('Tamaño archivo:', archivo.size)
        	
        	$.ajax({
        		url: '/janus/api/',
        		type: 'POST',
        		data: formData,
        		processData: false,
        		contentType: false,
        		dataType: 'json',
        		success: function(response){
        			console.log('Respuesta del servidor:', response)
        			const r = typeof response === 'string' ? JSON.parse(response) : response
        			if(r.ejecuto){
        				toastr.success('Foto subida correctamente')
        				$('#btnEliminarFoto').show()
        				// Recargar para verificar que se guardó
        				setTimeout(() => cargarFotoPerfil(), 500)
        			} else {
        				toastr.error(r.mensajeError || 'Error al subir la foto')
        				cargarFotoPerfil() // Recargar avatar
        			}
        		},
        		error: function(xhr, status, error){
        			console.error('Error AJAX:', status, error)
        			console.error('Respuesta:', xhr.responseText)
        			toastr.error('Error de conexión al subir la foto')
        			cargarFotoPerfil() // Recargar avatar
        		}
        	})
        	
        	// Limpiar input
        	$(this).val('')
        })
        
        // Eliminar foto
        $('#btnEliminarFoto').on('click', function(){
        	Swal.fire({
        		icon: 'warning',
        		title: '¿Eliminar foto de perfil?',
        		text: 'Esta acción no se puede deshacer',
        		showCancelButton: true,
        		confirmButtonText: '<i class="fas fa-trash mr-1"></i>Eliminar',
        		cancelButtonText: '<i class="fas fa-times mr-1"></i>Cancelar',
        		confirmButtonColor: '#dc3545',
        		cancelButtonColor: '#6c757d'
        	}).then((result) => {
        		if(result.isConfirmed){
        			enviarPeticion('usuarios', 'deleteFoto', {id: id}, function(r){
        				if(r.ejecuto){
        					toastr.success('Foto eliminada correctamente')
        					cargarFotoPerfil() // Recargar avatar con inicial
        				} else {
        					toastr.error(r.mensajeError || 'Error al eliminar la foto')
        				}
        			})
        		}
        	})
        })
	}
	
	function cargarInformacionPersonal() {
		//Llenar selects en cascada
		llenarSelectCallback('ciudades', 'select', {info:{estado: 'activo'}, orden: 'nombre'}, 'fk_ciudades', 'nombre', 1, 'Seleccione...', 'id', function(){
			llenarSelectCallback('eps', 'select', {info:{estado: 'activo'}, orden: 'nombre'}, 'fk_eps', 'nombre', 1, 'Seleccione...', 'id', function(){
				llenarSelectCallback('arl', 'select', {info:{estado: 'activo'}, orden: 'nombre'}, 'fk_arl', 'nombre', 1, 'Seleccione...', 'id', function(){
					llenarSelectCallback('fondosPension', 'select', {info:{estado: 'activo'}, orden: 'nombre'}, 'fk_fondos_pension', 'nombre', 1, 'Seleccione...', 'id', function(){
						llenarFormulario('formularioPersonas', 'usuarios', 'getInfoPersonas', {id: id}, function(r){
							if (r && r.ejecuto) {
								// Cargar foto si existe
								cargarFotoPerfil();
							} else {
								console.error('Error al cargar información personal:', r);
								// No mostrar warning, solo log en consola
							}
						})
					})
				})
			})
		})
	}
	
	function cargarFotoPerfil(){
		console.log('=== cargarFotoPerfil llamado ===')
		enviarPeticion('usuarios', 'getUsuarios', {criterio: 'id', id: id}, function(r){
			console.log('Respuesta getUsuarios:', r)
			if(r.ejecuto && r.data.length > 0){
				const usuario = r.data[0]
				console.log('Usuario:', usuario)
				console.log('Foto:', usuario.foto)
				
				// Ocultar spinner de carga
				$('#avatarLoading').css('display', 'none')
				
				if(usuario.foto && usuario.foto.trim() !== ''){
					// Mostrar foto
					const fotoUrl = 'fotos/' + usuario.foto + '?t=' + new Date().getTime()
					console.log('Mostrando foto:', fotoUrl)
					
					// Ocultar spinner y avatar removiendo clases de Bootstrap y agregando d-none
					$('#avatarLoading').removeClass('d-flex').addClass('d-none')
					$('#avatarCircle').removeClass('d-flex').addClass('d-none')
					
					// Agregar manejadores de carga
					$('#previewFoto')
						.off('load error')
						.on('load', function(){
							console.log('✅ Imagen cargada exitosamente')
							$(this).removeClass('d-none').css('display', 'block')
							console.log('Foto visible ahora')
						})
						.on('error', function(){
							console.error('❌ Error al cargar imagen:', fotoUrl)
							console.log('Mostrando avatar por defecto en su lugar')
							$(this).addClass('d-none')
							const primeraLetra = usuario.nombre ? usuario.nombre.charAt(0).toUpperCase() : 'U'
							$('#avatarLetter').text(primeraLetra)
							$('#avatarCircle').removeClass('d-none').addClass('d-flex')
						})
						.attr('src', fotoUrl)
					
					$('#btnEliminarFoto').show()
				} else {
					// Mostrar avatar con inicial
					console.log('No hay foto, mostrando avatar con inicial')
					const primeraLetra = usuario.nombre ? usuario.nombre.charAt(0).toUpperCase() : 'U'
					$('#avatarLetter').text(primeraLetra)
					$('#previewFoto').addClass('d-none')
					$('#avatarLoading').removeClass('d-flex').addClass('d-none')
					$('#avatarCircle').removeClass('d-none').addClass('d-flex')
					$('#btnEliminarFoto').hide()
				}
			} else {
				// En caso de error, ocultar spinner y mostrar avatar por defecto
				console.log('Error o sin datos, mostrando avatar por defecto')
				$('#avatarLoading').removeClass('d-flex').addClass('d-none')
				$('#avatarCircle').removeClass('d-none').addClass('d-flex')
				$('#btnEliminarFoto').hide()
			}
		})
	}
</script>
</body>
</html>
