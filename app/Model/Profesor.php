<?php
App::uses('AppModel', 'Model');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class Profesor extends AppModel {
  public $useTable = 'profesores';
  /*Funcion que nos devuelve todos los profesores de la aplicacion
	*
	*Función que nos devuelve todos los profesores de la aplicacion en un array,
	*null en caso de que no haya profesores y false en caso de que haya habido un
	*fallo con la conexion de la BBDD
	*
	* @return  $resultado array con todos los profesores de la aplicacion, null
	* en caso de que no haya profesores y false en caso de que haya habido un
	* fallo con la conexion de la BBDD*/
	public function getProfesoresAdmin() {
		$sql = 'SELECT `nombre`, `apellidos`, `email`, profesores.id as id FROM `profesores`';
    $consulta=$this->query($sql);
		$resultado = [];
		if(count($consulta) > 0){
      $count = 0;
			while (count($consulta) > $count){
				$resultado[] = $consulta[$count]["profesores"];
        $count++;
			}
		} else {
			$resultado = null;
		}
		return $resultado;
	}

  /*Función que dado un profesor lo borra de la aplicacion
	*
	*Función que dado el identificador de un profesor lo borra de la aplicación
	*
	* @param int $id identificador de la profesor
	* @return boolean $funciona true en caso de que se haya borrado correctamente
	* y false en caso contrario*/
	public function borrarProfesor($id) {
		$funciona=false;
		$admin = $_SESSION['administrador'];
		if ($admin) {
			$sql = 'DELETE FROM profesores WHERE id='.$id;
      $consulta=$this->query($sql);
			$funciona = true;
		} else {
			$_SESSION['error_no_poder_borrar'] = true;
		}
		echo $funciona;
	}

  /*Funcion que edita los campos de un profesor
	*
	*Función que dado un nombre, unos apellidos, un email y un identificador de
	*profesor edita los datos de ese profesor
	*
	* @param string $nombre nombre que queremos establecer al profesor
	* @param string $apellidos apellidos que queremos establecer al profesor
	* @param string $email email que queremos establecer al profesor
	* @param int $idProfesor identificador de un profesor
	* @return boolean $funciona true en caso de que se haya editado correctamente
	* y false en caso contrario*/
	public function editarProfesor($nombre, $apellidos, $email, $idProfesor) {
		$admin = $_SESSION['administrador'];
		if ($admin) {
			$sql = "UPDATE `profesores` SET `nombre`='".$nombre."',`apellidos`='".$apellidos."',`email`='".$email."' WHERE id=".$idProfesor;
      $consulta=$this->query($sql);
			$funciona = true;
		}
		echo $funciona;
	}

  /*Función que nos devuelve las asignaturas que coordina y que no coordina
	* un profesor.
	*
	*Funcion que dado un id de un profesor, nos devuelve un array con las asignaturas
	*que coordina y que no coordina un profesor
	*
	* @param int $idProfesor identificador del profesor
	* @return $resultado array con las asignaturas que coordina y
	* que no coordina un profesor */
	public function getAsignaturas($idProfesor) {
		$sql = 'SELECT `nombre`, `siglas`, `id` FROM `asignaturas`';
    $consulta = $this->query($sql);
		$asigNoCoord = [];
		$asigSiCoord = [];
		if(count($consulta) > 0){
      $count = 0;
      $countConsulta = count($consulta);
			while ($countConsulta > $count){
				if($this->esCoordinador($consulta[$count]['asignaturas']['id'], $idProfesor)){
					$asigSiCoord[] = $consulta[$count]['asignaturas'];
        }
				else{
					$asigNoCoord[] = $consulta[$count]['asignaturas'];
        }
        $count++;
			}
		} else {
			$resultado = null;
		}
		$resultado['asigSiCoord']= $asigSiCoord;
		$resultado['asigNoCoord']= $asigNoCoord;
    echo json_encode($resultado);
	}

  /*Función que dado un profesor y una asignatura, nos devuelve si dicho profesores
	* es coordinador o no
	*
	*Función que dado el identificador de un profesor y el de una asignatura nos
	*devuleve si dicho profesor es o no un coordinador de la asignatura
	*
	* @param int $idAsig identificador de la asignatura
	* @param int $idProfesor identificador de la profesor
	* @return boolean $result['coordinador'] true en caso de que la sea coordinador
	* y false en caso contrario*/
	public function esCoordinador($idAsig, $idProfesor){
		$result=false;
		$sql = "SELECT coordinador FROM `prof_asig_coord` WHERE `id_profesor` =".$idProfesor." and `id_asignatura`=".$idAsig;
		$consulta = $this->query($sql);
    if(isset($consulta[0]["prof_asig_coord"]['coordinador'])){
      return $consulta[0]["prof_asig_coord"]['coordinador'];
    }
    else {
      return null;
    }
	}

  /*Función que nos devuelve si cuantos coordinadores hay ademas que el profesor
	* que le pasamos por parametro
	*
	*Funcion que pasandole el id de una asignartura y el id de un profesor nos
	*devuelve 0 si no hay ningun coordinador en esa asignatura salvo el profesor
	*que le pasamos por parametro o mas de 0 en caso de que haya más coordinadores
	*para esta asignatura ademas de el que le pasamos por parametro
	*
	* @param int $idAsig identificador de la asignatura
	* @param int $idProfesor identificador del profesor
	* @return int $resultado numero de profesores que coodinan la asignatura, false
	* en caso de que haya fallado la conexion con la BBDD*/
	public function isAsigWithCoord($idAsig, $idProfesor) {
		$sql = 'SELECT id_asignatura, coordinador, COUNT(coordinador) AS number_coord
				FROM `prof_asig_coord`
				WHERE id_asignatura = '.$idAsig.' AND id_profesor <> '.$idProfesor.'
				GROUP BY coordinador, id_asignatura
				HAVING coordinador = 1';
    $consulta = $this->query($sql);
    echo count($consulta);
	}

  /*Función que dado un profesor define que asignaturas coordina y cuales no.
	*
	*Funcion que dado el identificador de un profesor, un array con los identifiadores
	*de las asignaturas que coordina y otro con las que no coordina define que
	*asignaturas coordina ese profesor
	*
	* @param int $idProf identificador del profesor
	* @param $idAsigSelect identificadores de asignaturas seleccionadas
	* @param $idAsigNoSelect identificadores de asignaturas no seleccionadas
	*/
	public function setCoordinadores($idProf, $idAsigSelect, $idAsigNoSelect){
		$arrayIdAsigSelect = json_decode($idAsigSelect);
		$arrayIdAsigNoSelect = json_decode($idAsigNoSelect);
		for($i=0; $i < count($arrayIdAsigSelect); $i++) {
			$sql= 'SELECT count(`id_profesor`) as `existe` FROM `prof_asig_coord` WHERE `id_asignatura`='.$arrayIdAsigSelect[$i].' and`id_profesor`='.$idProf;
      $consulta = $this->query($sql);
			if($consulta[0][0]['existe']){
				$sql= 'UPDATE `prof_asig_coord` SET `coordinador`=1 WHERE `id_asignatura`='.$arrayIdAsigSelect[$i].' and`id_profesor`='.$idProf;
        $this->query($sql);
			}else{
				$sql = "INSERT INTO `prof_asig_coord`(`id_asignatura`, `id_profesor`, `coordinador`, `id`) VALUES (".$arrayIdAsigSelect[$i].",".$idProf.",1,'')";
        $this->query($sql);
			}

		}
		$arrayIdAsigNoSelect = json_decode($idAsigNoSelect);
		for($i=0; $i < count($arrayIdAsigNoSelect); $i++) {
			$sql= 'SELECT count(`id_profesor`) as `existe` FROM `prof_asig_coord` WHERE `id_asignatura`='.$arrayIdAsigNoSelect[$i].' and`id_profesor`='.$idProf;
      $consulta = $this->query($sql);
			if($consulta[0][0]['existe']){
				$sql= 'UPDATE `prof_asig_coord` SET `coordinador`=0 WHERE `id_asignatura`='.$arrayIdAsigNoSelect[$i].' and`id_profesor`='.$idProf;
        $this->query($sql);
			}
		}
		echo "correct";
	}

  /*Función que invita a un profesor a la aplicación
	*
	*Funcion que dado un email, invita a ese usuario a la aplicacion
	*
	* @param string $email email valido */
	public function invitarProfesor($email) {
		$_SESSION['error_envio_mail'] = false;
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
    $success = $this->smtpmailer($email, $credentials['webMail']['mail'], 'AExamen Web', 'Invitación AExamen', 'invitacion.html', $credentials['webMail']['mail'], $credentials['webMail']['password']);
		if (!$success) {
      $_SESSION['error_envio_mail'] = true;
		}
	}

  /*
	* Función para enviar mail a través de GMail con cuerpo simple
	*/
  public function smtpmailer($to, $from, $fromName, $subject, $body, $googleUser, $googlePassword) {
		global $error;
		$mail = new PHPMailer();  // creamos el objeto
		$mail->IsSMTP(); // activa SMTP
		$mail->SMTPDebug = 0;  // debugeo: 1 = errores y mensajes, 2 = sólo mensajes
		$mail->SMTPAuth = true;  // requerir autenticación
		$mail->SMTPSecure = 'ssl'; // transferencia segura activada OBLIGATORIO para GMail
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465;
		$mail->Username = $googleUser;
		$mail->Password = $googlePassword;
		$mail->SetFrom($from, $fromName);
		$mail->Subject = $subject;
		$mail->AddAddress($to);
		$mail->CharSet = 'ISO-8859';
		$mail->msgHTML(file_get_contents($body), __DIR__);
		if(!$mail->Send()) {
			$error = 'Mail error: '.$mail->ErrorInfo;
			return false;
		} else {
			$error = "\nMessage sent to ".$to."!";
			return true;
		}
	}

  /*Función que dada una asignatura nos devuelve todos los profesores de esta.
	*
	*Funcion que dado el identificador de una asignatura nos devuelve en un array
	*todos los profesores que tiene esa asignatura y null en caso de que no tenga
	*
	* @param int $idAsig identificador de la asignatura
	* @return $resultado array con los profesores de la asignatura, null si la
	* asignatura no tiene profesores y false en caso de que haya un fallo con la
	* BBDD */
	public function profesoresAsignatura($idAsig) {
		$sql = 'SELECT `nombre`, `apellidos`, `email`, profesores.id as id FROM `profesores` INNER JOIN `prof_asig_coord` ON profesores.id=prof_asig_coord.id_profesor WHERE prof_asig_coord.id_asignatura='.$idAsig.' and prof_asig_coord.coordinador = 0';
		$consulta=$this->query($sql);
		$resultado = [];
    $count=0;
    if((count($consulta) < 0)) {
			$resultado = null;
		}
    else{
			while (count($consulta) > $count ){
        $resultado[] = $consulta[$count]['profesores'];
        $count++;
			}
		}
		return $resultado;
	}

  /*Función que borra un profesor de una asignatura
	*
	*Funcion que dado un id de un profesor y el de una asignatura borra dicho
	*profesor de la asignatura
	*
	* @param int $idProfesor identificador de un profesor
	* @param int $idAsig identificador de una asignatura
	* @return boolean $funciona vale true si se borra con exito y false en caso
	* contrario */
	public function borrarProfesorDeAsig($idProfesor, $idAsig){
		$sql = "DELETE FROM `prof_asig_coord` WHERE id_profesor=".$idProfesor." and id_asignatura=".$idAsig;
		$consulta=$this->query($sql);
		return true;
	}

  /*Función que nos devuelve los profesores que no estan en una asignatura
	*
	*Funcion que dado un id de una asignarua y un array con los identificadores de
	*los profesores que hay en la asigntrua nos devuelve un array con los profesores
	*que no estan en la asignatura
	*
	* @param int $idAsig identificador de la asignatura
	* @param $idProfesores array con los  identificadores de los profesores que
	* estan en la asignatura
	* @return $resultado array con los identificadores de los profesores que no
	* estan en la asignatura o false en caso de que haya habido algun error con
	* la conexin con la BBDD */
	public function getProfesoresFueraAsig($idAsig, $idProfesores){
		if ($idProfesores != null) {
			$idProfesores = explode(',', $idProfesores);
		} else {
			$idProfesores = array();
		}

		$idProfesores[] = $_SESSION['id'];
		$ids = implode (",", $idProfesores);
		$sql = 'SELECT nombre, apellidos, email, profesores.id as id
				FROM
				`profesores` LEFT JOIN `prof_asig_coord` ON profesores.id=prof_asig_coord.id_profesor

				 WHERE
				 	profesores.id not in ('.$ids.')
				 	and
				 	(prof_asig_coord.id_asignatura<>'.$idAsig.' OR prof_asig_coord.id_asignatura is null)';
		$consulta=$this->query($sql);
		$resultado = [];
    $count=0;
    if((count($consulta) < 0)) {
			$resultado = null;
		}	else {
			while (count($consulta) > $count ){
				$resultado[] = $consulta[$count]['profesores'];
        $count++;
			}
		}
		return $resultado;
	}

  /*Función que añade un profesor a una asignarura
	*
	*Funcion que dado un id de un profesor y un id de una asignatura, añade ese
	*profesor a esa asignatura
	*
	* @param int $idProfesor identificador del profesor
	* @param int $idAsig identificador de la asignatura*/
	public function aniadirProfesor($idProfesor, $idAsig) {
    $sql ='INSERT INTO `prof_asig_coord`(`id_profesor`, `id_asignatura`, `coordinador`, `id`) VALUES ('.$idProfesor.','.$idAsig.',0,'."''".')';
    $consulta = $this->query($sql);
    return true;
	}

  /*Función que nos devuelve los parametros de un examen de una asignatura.
  *
  *Funcion que dado el id de una asignatura nos devuelve los parametros para
  *un examen de esa asignatura
  *
  * @param int $idAsig identificador de la asignatura
  * @return $fila array con los parametros que tiene definido esa asignatura */
  function selectParametrosAsig($idAsig) {
  	$sql = "SELECT `puntos_tema`, `texto_inicial`, `espaciado_defecto` FROM `asignaturas` WHERE id=".$idAsig;
  	$consulta=$this->query($sql);
    $resultado = [];
    $count=0;
    if((count($consulta) < 0)) {
      $resultado = null;
    }	else {
      while (count($consulta) > $count ){
        $resultado[] = $consulta[$count]['asignaturas'];
        $count++;
      }
    }

  	return $resultado[0];
  }

  /*Función que nos actualiza los parametros para un examen de una asignatura
  *
  *Funcion que dado unos puntos por tema, un espaciado y un texto inicial
  *actualiza esto valores como valores de un examen por defecto de la asignatura
  *que le indicamos con el identificador que tambien le pasamos por parametro
  *
  *
  * @param string $puntos_tema puntos definidos por cada tema con forma json
  * @param string $idAsig identificador de la asignatura
  * @param string $espaciado valor que queremos poner en el espaciado
  * @param strint $textoInicial Texto que queremos mostrar al comienzo del examen
  * @return boolean $success devuleve true si la modificacion se ha realizado con exito y false en caso contrario */
  function updateParametrosAsig($puntos_tema, $idAsig, $espaciado, $textoInicial){
  	//hacemos casting para transformarlos en enteros
  	$espaciadoInt = (int)$espaciado;
  	$idAsigInt = (int)$idAsig;

  	$puntos_tema = "'".$puntos_tema."'";
		$sql = "UPDATE `asignaturas` SET`espaciado_defecto`=".$espaciadoInt.", `texto_inicial`= '".$textoInicial."', `puntos_tema`= ".$puntos_tema." WHERE id=".$idAsigInt;
		$consulta=$this->query($sql);

		//Apuntamos en el log que usuario a modificado los valores por defecto del examen de la asignatura
		$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].', '.$_SESSION['email'].
		        " | ACTION --> Parámetros de la asignatura con id ".$idAsig." modificados".PHP_EOL.
		        "-----------------------------------------------------------------".PHP_EOL;
		file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);
  	echo true;
  }

}
