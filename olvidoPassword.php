<?php
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	$error_campoVacio = isset($_SESSION['error_campoVacio'])? $_SESSION['error_campoVacio']: false;
	$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;
	$error_usuario_no_existente = isset($_SESSION['error_usuario_no_existente'])? $_SESSION['error_usuario_no_existente']: false;
	if($error_campoVacio){
		echo "Error, introduzca un email";
		$error_campoVacio=false;
	}
	elseif($error_BBDD) {
		echo "Error al conectar con la base de datos";
		$error_BBDD=false;
	}
	elseif($error_usuario_no_existente){
		echo"Error, el correo electrónico no coincide con ninguno ya registrado";
		$error_usuario_existente=false;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<!--css propio -->
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<!--css externos-->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<meta charset="UTF-8">
</head>
<body>
	<div class="container">
		<h1>Reestablecer contraseña</h1>

		<p>Introduce tu correo electrónico para poder reestablecer la contraseña mediante el correo que te enviaremos: </p>

		<form action="olvidoPasswordProcesamiento.php" id="formulario_olvido" method="post">
			Email:<br>
			<input type="text" name="email" id="email" required>
			<br><br>
			<input type="submit" value="Enviar" id="olvido" name="olvido">
		</form> 
		<br>
		<p><a href="loginFormulario.php">Iniciar sesión</a></p>
		<p><a href="registrarseFormulario.php">Registrarse</a></p>

	</div>


	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/w3.js"></script>


</body>
</html>
