<!--COMPROBAR QUE EL USUARIO ESTA LOGEADO -->

<html>
<head>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<?php 
			session_start();
			echo "<h1>Preguntas de ". $_GET['nombreAsignatura']. "</h1>";
			include "preguntasProcesamiento.php"
		?>
		<table class="table table-hover" id="tabla_preguntas">
		    <thead>
		      <tr>
		        <th>Titulo</th>
		        <th>Tema</th>
		        <th>Autor</th>
		        <th>Fecha creación</th>
		        <th>Fecha modificiación</th>
		      </tr>
		    </thead>
		    <tbody>
		<?php

			$asignaturas=cargaAsignaturas($_GET['idAsignatura']);

			$error_ningunaPregunta = isset($_SESSION['error_ningunaPregunta'])? $_SESSION['error_ningunaPregunta']: false;
			$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;

			if($error_ningunaPregunta){
				echo 'Esta asignatura no tienen ninguna pregunta';
			}

			else if($error_BBDD){
				echo 'Error con la BBDD, contacte con el administrador';
			}
			else{
				foreach ($asignaturas as $pos => $valor) {
					echo '<tr>';
					echo '<td>'.$valor['titulo'].'</td>';
					echo '<td>'.$valor['tema'].'</td>';
					echo '<td>'.$valor['autor'].'</td>';
					echo '<td>'.$valor['fecha_creacion'].'</td>';
					echo '<td>'.$valor['fecha_modificado'].'</td>';
					echo '</tr>';
				}
			}
		?>
			</tbody>
		</table>
		
	</div>

	<script src="jquery-3.3.1.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script type="text/javascript" src="asignaturasProfesor.js"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://www.w3schools.com/lib/w3.js"></script>

</body>
</html>

