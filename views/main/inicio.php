<?php require('views/header.php');?>

<div class="content-wrapper">
	<section class="content-header">
		<div class="container">
			<div class="row mb-2">
				<div class="col-sm-6">
            		<h1>Inicio</h1>
          		</div>
          		<div class="col-sm-6">
            		<ol class="breadcrumb float-sm-right">
              			<li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
            		</ol>
          		</div>
        	</div>
    	</div>
    </section>

    <section class="content">
    	<div class="container">
    		<div class="row">
                <div class="col-lg-3 col-6">            
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="supSolicitudes">10</h3>
                            <p>Pendientes de ubicar</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-stopwatch"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 id="supDias">45</h3>
                            <p>Días promedio</p>
                        </div>
                        <div class="icon">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">            
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 id="supTU">37</h3>
                            <p>Por vencerse</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3 id="supPromedio">121</h3>
                            <p>En trámite</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tools"></i>
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
		console.log("Cargo!!!")
	}
</script>
</body>
</html>