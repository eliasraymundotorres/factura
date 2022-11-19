<?php 

include_once ('../apifacturacion/ado/conexion.php');

class Unidad
{
	public function listar()
	{
		$sql = "SELECT * from unidad ";


			global $cnx;
			$pre = $cnx->prepare($sql);
			$pre->execute();
			return $pre;
	}
}


 ?>