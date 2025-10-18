<?php require('views/header.php');?>

<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
            		<!-- Título removido para evitar duplicación -->
          		</div>
          		<div class="col-sm-6">
            		<ol class="breadcrumb float-sm-right">
              			<li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
              			<li class="breadcrumb-item active">Mi Perfil</li>
            		</ol>
          		</div>
        	</div>
    	</div>
    </section>

    <section class="content">
    	<div class="container-fluid">
    		<div class="row">
    			<div class="col-12">
    				<div class="card shadow-lg" style="border: 2px solid #000; border-radius: 15px;">
    					<div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #000; border-radius: 13px 13px 0 0; padding: 20px;">
    						<div class="d-flex align-items-center">
    							<i class="fas fa-badge-check text-primary mr-3" style="font-size: 24px;"></i>
    							<div>
    								<h2 class="mb-1 font-weight-bold">Mi Perfil</h2>
    								<p class="mb-0 text-muted">Información de tu cuenta en el sistema JANUS</p>
    							</div>
    						</div>
    					</div>
    					<div class="card-body" style="padding: 30px;">
    						<div class="row">
    							<!-- Columna izquierda - Avatar y rol -->
    							<div class="col-lg-4 col-md-5 text-center mb-4">
    								<div class="d-flex flex-column align-items-center">
    									<!-- Avatar circular -->
    									<div id="avatarCircle" class="rounded-circle d-flex align-items-center justify-content-center text-white font-weight-bold shadow-lg mb-3" 
    										 style="width: 120px; height: 120px; font-size: 48px; background: linear-gradient(135deg, #f39c12, #e67e22); transition: transform 0.3s ease;">
    										<span id="avatarLetter">A</span>
    									</div>
    									
    									<!-- Nombre y rol -->
    									<h3 class="font-weight-bold mb-2" id="nombreCompleto">USUARIO</h3>
    									<span class="badge badge-primary px-3 py-2 mb-3" id="rolBadge" style="font-size: 14px;">USUARIO</span>
    									<p class="text-muted small text-center" id="rolDescripcion">Descripción del rol en el sistema</p>
    									
    									<!-- Último acceso -->
    									<div class="w-100 bg-light rounded p-3 mt-3" style="border: 1px solid #dee2e6;">
    										<div class="d-flex align-items-center justify-content-center mb-2">
    											<i class="fas fa-clock mr-2 text-muted"></i>
    											<small class="text-muted">Último acceso</small>
    										</div>
    										<p class="font-weight-medium mb-1" id="ultimoAcceso">29/09/2025, 23:00</p>
    										<p class="small text-muted mb-0">Última vez que inició sesión</p>
    									</div>
    								</div>
    							</div>
    							
    							<!-- Columna derecha - Información -->
    							<div class="col-lg-8 col-md-7">
    								<div class="row">
    									<!-- Información personal -->
    									<div class="col-md-6 mb-4">
    										<h4 class="font-weight-semibold pb-2 mb-3" style="border-bottom: 2px solid #dee2e6;">
    											<i class="fas fa-user mr-2 text-primary"></i>Información Personal
    										</h4>
    										
    										<div class="mb-4">
    											<div class="d-flex align-items-center mb-2">
    												<i class="fas fa-user mr-2 text-muted" style="width: 20px;"></i>
    												<small class="text-muted">Nombre completo</small>
    											</div>
    											<p class="font-weight-medium h5 mb-0" id="nombre">-</p>
    										</div>
    										
    										<div class="mb-4">
    											<div class="d-flex align-items-center mb-2">
    												<i class="fas fa-id-card mr-2 text-muted" style="width: 20px;"></i>
    												<small class="text-muted">Número de documento</small>
    											</div>
    											<p class="font-weight-medium h5 mb-0" id="cedula">-</p>
    										</div>
    										
    										<div class="mb-4">
    											<div class="d-flex align-items-center mb-2">
    												<i class="fas fa-at mr-2 text-muted" style="width: 20px;"></i>
    												<small class="text-muted">Usuario registrado</small>
    											</div>
    											<p class="font-weight-medium h5 mb-0" id="login">-</p>
    										</div>
    										
    										<div class="mb-4">
    											<div class="d-flex align-items-center mb-2">
    												<i class="fas fa-shield-alt mr-2 text-muted" style="width: 20px;"></i>
    												<small class="text-muted">Rol asignado</small>
    											</div>
    											<p class="font-weight-medium h5 mb-0" id="rol">-</p>
    										</div>
    									</div>
    									
    									<!-- Información de dependencia -->
    									<div class="col-md-6 mb-4">
    										<h4 class="font-weight-semibold pb-2 mb-3" style="border-bottom: 2px solid #dee2e6;">
    											<i class="fas fa-building mr-2 text-primary"></i>Información de Dependencia
    										</h4>
    										
    										<div class="mb-4">
    											<div class="d-flex align-items-center mb-2">
    												<i class="fas fa-sitemap mr-2 text-muted" style="width: 20px;"></i>
    												<small class="text-muted">Gerencia asignada</small>
    											</div>
    											<p class="font-weight-medium h5 mb-0" id="gerencia">-</p>
    										</div>
    										
    										<!-- Panel de cambio de clave (solo PS) -->
    										<div id="panelClave" style="display: none;">
    											<h5 class="font-weight-semibold mb-3">
    												<i class="fas fa-key mr-2 text-warning"></i>Cambiar Contraseña
    											</h5>
    											<form id="formularioClave">
    												<div class="form-group">
    													<label class="small text-muted">Nueva contraseña</label>
    													<input type="password" class="form-control" name="password" id="password1" required 
    														   style="border: 1px solid #dee2e6; border-radius: 5px;">
    												</div>
    												<div class="form-group">
    													<label class="small text-muted">Confirmar contraseña</label>
    													<input type="password" class="form-control" id="password2" required
    														   style="border: 1px solid #dee2e6; border-radius: 5px;">
    												</div>
    												<button type="submit" class="btn btn-warning btn-sm">
    													<i class="fas fa-save mr-1"></i>Actualizar Contraseña
    												</button>
    											</form>
    										</div>
    									</div>
    								</div>
    								
    								<!-- Footer de ayuda -->
    								<div class="mt-4 pt-4" style="border-top: 1px solid #dee2e6;">
    									<div class="bg-light rounded p-4" style="border: 1px solid #dee2e6;">
    										<div class="row align-items-center">
    											<div class="col-md-8">
    												<h6 class="font-weight-medium mb-1">¿Necesitas ayuda con tu cuenta?</h6>
    												<p class="small text-muted mb-0">Contacta al administrador del sistema para actualizar tu información o recuperar tu contraseña.</p>
    											</div>
    											<div class="col-md-4 text-md-right mt-2 mt-md-0">
    												<i class="fas fa-question-circle text-primary" style="font-size: 48px; opacity: 0.5;"></i>
    											</div>
    										</div>
    									</div>
    								</div>
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
		// Verificar que la información del usuario esté disponible
		if (!info || !info.data || !info.data.usuario) {
			console.error('Error: Información de usuario no disponible en init()');
			toastr.error('Error al cargar información del usuario');
			return;
		}
		
		id = info.data.usuario.id
		const usuario = info.data.usuario;
		
		// Mostrar panel de cambio de clave solo para PS
		if(usuario.rol == 'PS'){
			$('#panelClave').show()
		}

		//Cargar información base
		enviarPeticion('usuarios', 'getUsuarios', {criterio: 'id', id: id}, function(r){
			if (r && r.data && r.data.length > 0) {
				const userData = r.data[0];
				
				// Llenar información básica
				$('#rol').text(userData.rol)
				$('#gerencia').text(userData.gerencia || 'No asignada')
				$('#nombre').text(userData.nombre)
				$('#cedula').text(userData.cedula)
				$('#login').text(userData.login)
				
				// Llenar información del perfil moderno
				$('#nombreCompleto').text(userData.nombre)
				$('#rolBadge').text(userData.rol)
				
				// Generar avatar con primera letra del nombre
				const primeraLetra = userData.nombre ? userData.nombre.charAt(0).toUpperCase() : 'U';
				$('#avatarLetter').text(primeraLetra);
				
				// Color del avatar según el rol
				let colorGradient = 'linear-gradient(135deg, #6c757d, #5a6268)'; // Gris por defecto
				let rolDesc = 'Usuario del sistema';
				
				switch(userData.rol) {
					case 'Administrador':
						colorGradient = 'linear-gradient(135deg, #dc3545, #c82333)';
						rolDesc = 'Acceso completo al sistema, gestión de usuarios y configuraciones';
						break;
					case 'PS':
						colorGradient = 'linear-gradient(135deg, #28a745, #218838)';
						rolDesc = 'Prestador de servicios con acceso a documentación y procesos';
						break;
					case 'Revisor':
						colorGradient = 'linear-gradient(135deg, #007bff, #0056b3)';
						rolDesc = 'Revisor de documentos y procesos del sistema';
						break;
					default:
						colorGradient = 'linear-gradient(135deg, #f39c12, #e67e22)';
						rolDesc = 'Usuario con permisos específicos en el sistema';
				}
				
				$('#avatarCircle').css('background', colorGradient);
				$('#rolDescripcion').text(rolDesc);
				
				// Simular último acceso (podrías obtener esto de la base de datos)
				const now = new Date();
				const fechaFormateada = now.toLocaleDateString('es-ES') + ', ' + now.toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'});
				$('#ultimoAcceso').text(fechaFormateada);
				
			} else {
				toastr.error('Error al cargar datos del usuario');
			}
		})

		//Cambiar clave
        $('#formularioClave').on('submit', function(e){
            e.preventDefault()
            if($('#password1').val() != $('#password2').val()){
                toastr.error("Las contraseñas deben ser iguales")
            }else{                
                enviarPeticion('usuarios', 'setPassword', {info: {password: $('#password1').val()}, id:id}, function(r){
                    toastr.success('Se cambió la contraseña correctamente')
                    $('#password1').val('')
                    $('#password2').val('')
                })
            }
        })
        
        // Efecto hover en el avatar
        $('#avatarCircle').hover(
            function() {
                $(this).css('transform', 'scale(1.05)');
            },
            function() {
                $(this).css('transform', 'scale(1)');
            }
        );
	}
</script>
</body>
</html>
