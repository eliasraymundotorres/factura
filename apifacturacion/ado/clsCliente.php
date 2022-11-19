<?php
require_once("conexion.php");

class clsCliente{

	function insertarCliente($cliente){

		$sql = "INSERT INTO cliente(id, tipodoc, nrodoc, razon_social, direccion, ubigeo)
				VALUES (NULL, :tipodoc, :nrodoc, :razon_social, :direccion, :ubigeo)";

		$parametros = array(
						':tipodoc'		=>$cliente['tipodoc'],
						':nrodoc' 		=>$cliente['ruc'],
						':razon_social'	=>$cliente['razon_social'],
						':direccion'	=>$cliente['direccion'],
						':ubigeo'		=>$cliente['ubigeo']
						);
		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function consultarCliente($nrodoc){
		$sql = "SELECT * FROM cliente WHERE nrodoc=:nrodoc";

		$parametros = array(':nrodoc'=>$nrodoc);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;	
	}

	function consultarClienteId($id){
		$sql = "SELECT * FROM cliente WHERE id=:id";

		$parametros = array(':id'=>$id);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;	
	}
	function consultarClienteMax(){
		$sql = "SELECT * FROM cliente order by id desc LIMIT 1 ";

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute();
		return $pre;	
	}

}

?>