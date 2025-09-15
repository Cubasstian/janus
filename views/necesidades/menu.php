<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Menú
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Menú</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="card card-outline card-success">
                        <div class="card-body">
                        	<form id="formularioVacantes">
                        		<div class="form-group">
                                    <label for="gerencia">Gerencia</label>
                                    <select class="form-control" name="gerencia" id="gerencia" required="required"></select>
                                </div>
                                <div class="form-group">
                                    <label for="vigencia">Vigencia</label>
                                    <select class="form-control" name="vigencia" id="vigencia" required="required"></select>
                                </div>
                                <div class="row">
                                	<div class="col-sm-4"></div>
                                	<div class="col-sm-4">
	                                	<button type="submit" class="btn btn-success btn-block" title="Agregar presupuesto">
	                                    	Gestionar
                                		</button>
                                	</div>
                                </div>
                        	</form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
	function init(info){
		//LLenar gerencias
        llenarSelect('gerencias', 'getGerencias', {criterio: 'rol'}, 'gerencia', 'nombre', 1)
		//Llenar vigencias
        llenarSelect('vigencias', 'select', {info:{estado: 'Activo'}}, 'vigencia', 'vigencia', 1)

        $('#formularioVacantes').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            let info = {
                gerencia: {
                    id: datos.gerencia,
                    nombre: $('#gerencia option:selected').text()
                },
                vigencia: {
                    id: datos.vigencia,
                    nombre: $('#vigencia option:selected').text()
                }
            }
            sessionStorage.setItem('variables', JSON.stringify(info))
        	url = 'necesidades/listar/'
        	window.open(url, '_self')
        })
	}
</script>
</body>
</html>