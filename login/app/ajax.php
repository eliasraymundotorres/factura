<?php 

include_once ('../../apifacturacion/ado/Sesion.php');

$objSesion = new Sesion();


$usuario = $_POST['username'];
$clave = $_POST['pass'];

$hoy = date('Y-m-d');

$verificar = $objSesion->contrato($hoy);

if($verificar->rowCount()>0) {
	$row = $verificar->fetch();
	$fecha_fin = $row['fecha_final'];
 
	$dias = dias_pasados($hoy,$fecha_fin);

     
		$user = $objSesion->Iniciar($usuario);
		if ($user->rowCount()==1) {
			$mostrar = $user->fetch();
			$claveUser = $mostrar['clave'];

			if ($claveUser==$clave) {
				@session_start();
				$_SESSION['user'] = $mostrar['user'];
				$_SESSION['id'] = $mostrar['id'];
				$_SESSION['nombre'] = $mostrar['nombre'];
			  if($dias>10) {
				echo 1;
			  } else {
				echo 3;
			  }

			} else {
				echo 0;
			}

		}

} else {
	echo 2;
}



function dias_pasados($fecha_inicial,$fecha_final)
{
		$dias = (strtotime($fecha_inicial)-strtotime($fecha_final))/86400;
		$dias = abs($dias); $dias = floor($dias);
		return $dias;
}






 ?>