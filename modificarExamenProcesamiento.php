<?php	

	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	$nombreExamen = isset($_POST['nombreExamen'])? $_POST['nombreExamen']: null;
	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	if($funcion == "guardarModificarExamen")
		guardarModificarExamen($nombreExamen);

	function getExamen($idExamen){
		

		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT * FROM `examenes` WHERE id=".$idExamen;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: crearExamen.php');
		}
		mysqli_close($db);
		return $fila;
	}

	function guardarModificarExamen ($nombreExamen) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

		$puntosPregunta = $_SESSION[$_SESSION['nombreAsignatura']];
		$date = date('Y-m-d H:i:s', time());
		$nombreExamen=$_SESSION['nombreExamenEditar'];
		$preguntasJsonArray=$_SESSION[$nombreExamen];
		$idExamen=$_SESSION['idExamen'];
		
		$sqlExamen="UPDATE `examenes` SET `titulo`=".$nombreExamen." ,`fecha_modificado`=".$date.",`ultimo_modificador`=".$_SESSION['id'].",`puntosPregunta`=".$preguntasJsonArray." WHERE id=".$idExamen;
		
		if (mysqli_query($db,$sqlExamen)) {
			//echo "Nuevo examen añadido";
			$numTemas = getNumTemas($_SESSION['idAsignatura']);
			$arrayPuntosTema =cargaPuntosTema($_SESSION['idAsignatura']);
			$jsonPuntosTema = json_decode($arrayPuntosTema,true);
			$preguntasSesion = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
		
			$sqlDelete= "DELETE FROM `exam_preg` WHERE id_examen=".$idExamen;
			if (mysqli_query($db,$sqlDelete)) {
				for ($i = 1; $i <= $numTemas; $i++) {
					$preguntasTema = isset($preguntasSesion['preguntas']['tema'.$i])? $preguntasSesion['preguntas']['tema'.$i]: null;
					if ($preguntasTema) {
						foreach ($preguntasTema as $pregunta) {
							$sqlExam_Preg = "INSERT INTO exam_preg (`id_examen`, `id_pregunta`, `id`) VALUES (".$idExamen.",".$pregunta['id'].",'')";
							mysqli_query($db,$sqlExam_Preg);
						}
					}
				}	
			}
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		$mensaje = array();
		$mensaje['Message'] = "Examen guardado";
		echo json_encode($mensaje);	
	}

?>