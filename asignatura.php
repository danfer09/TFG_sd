<!--COMPROBAR QUE EL USUARIO ESTA LOGEADO -->

<html>
<head>
	<title>AExamen Asignatura</title>
	<!--css propio -->
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<!--css externos-->
	<link rel="stylesheet" type="text/css" href="css/w3.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/slick-team-slider.css" />
  	<link rel="stylesheet" type="text/css" href="css/style.css">
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
	<div class="header" id="header"></div>
	<div class="container">
		<?php 
			/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
			if (session_status() == PHP_SESSION_NONE) {
			    session_start();
			}
			//Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
			$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
			/*En caso de no este logeado redirigimos a index.php, en caso contrario le damos la bienvenida*/
			if (!$logeado) {
				header('Location: index.php');
			}
			//include "funcionesServidor.php";
			include "asignaturaProcesamiento.php";
			$idAsignatura=$_GET['id'];
			$idProfesor=$_SESSION['id'];
			$nombreAsig=$_GET["nombre"];
			
			echo '<h1>Asignatura: '. $_GET["nombre"]. '</h1>';
			?>
			<div id="portfolio">
			    <div class="container">

			      <div class="row" id="portfolio-wrapper">
			      	<div class="col-xl-3 col-lg-4 col-md-6 col-sm-3 portfolio-item filter-app">
			          <h3>PREGUNTAS</h3>
			          <a <?php echo 'href="preguntas.php?nombreAsignatura='.$_GET["nombre"].'&idAsignatura='.$_GET["id"].'&autor=todos"' ?>>
			            <img src="img/question-solid.png" alt="">
			            <h3>PREGUNTAS</h3>
			            <div class="details">
			              <h4>PREGUNTAS</h4>
			              <span>Ver preguntas</span>
			            </div>
			          </a>
			        </div>
			      	<div class="col-xl-3 col-lg-4 col-md-6 col-sm-3 portfolio-item filter-app">
			          <h3>EXÁMENES</h3>
			          <a <?php echo 'href="examenes.php?asignatura='.$_GET['siglas'].'&autor=todos"' ?>>
			            <img src="img/examenes-document.png" alt="">
			            <h3>EXÁMENES</h3>
			            <div class="details">
			              <h4>EXÁMENES</h4>
			              <span>Ver exámenes</span>
			            </div>
			          </a>
			        </div>
			<?php
			if(esCoordinador($idAsignatura,$idProfesor)){
				
				?>
					<div class="col-xl-3 col-lg-4 col-md-6 col-sm-3 portfolio-item filter-app">
			          <h3>PROFESORES</h3>
			          <a <?php echo 'href="profesoresDeUnaAsig.php?idAsig='.$_GET['id'].'&nombreAsig='.$nombreAsig.'"' ?>>
			            <img src="img/profesores-users.png" alt="">
			            <h3>PROFESORES</h3>
			            <div class="details">
			              <h4>PROFESORES</h4>
			              <span>Gestionar profesores</span>
			            </div>
			          </a>
			        </div>

			        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-3 portfolio-item filter-app">
			          <h3>PARÁMETROS</h3>
			          <a <?php echo 'href="definirParametrosExam.php?idAsig='.$_GET['id'].'"' ?>>
			            <img src="img/parametros-examen.png" alt="">
			            <h3>PARÁMETROS</h3>
			            <div class="details">
			              <h4>PARÁMETROS DE EXAMEN</h4>
			              <span>Definir parámetros de exámenes</span>
			            </div>
			          </a>
			        </div>
			<?php	
			}
			?>
			      </div>
			    </div>
			  </div>		
	</div>

	<!--Librerias externas-->
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/jquery-3.3.1.slim.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.min.js"></script>
	<script src="js/w3.js"></script>

	<!--Javascripts propios-->
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>

</body>
</html>

