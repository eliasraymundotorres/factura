<?php 
include_once ('../../apifacturacion/ado/conexion.php');

class Sesion
{
	public function Iniciar(string $user)
	{
		$sql = "SELECT * from usuario where user=:user limit 1 ";
		$parametros = array(
					':user'=>$user				
				);

			global $cnx;
			$pre = $cnx->prepare($sql);
			$pre->execute($parametros);
			return $pre;
	}
	public function contrato($hoy)
	{
		$sql = "SELECT * from contrato where fecha_final>=:fecha limit 1 ";
		$parametros = array(
			':fecha'=>$hoy				
		);
			global $cnx;
			$pre = $cnx->prepare($sql);
			$pre->execute($parametros);
			return $pre;
	}
}

 ?>