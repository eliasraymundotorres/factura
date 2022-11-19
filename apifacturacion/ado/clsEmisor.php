<?php
require_once("conexion.php");

class clsEmisor{

	function consultarListaEmisores(){
		$sql = "SELECT * FROM emisor";
		global $cnx;
		return $cnx->query($sql);
	}

	function obtenerEmisor($idemisor){
		$sql = "SELECT * FROM emisor WHERE id=:idemisor ";
		global $cnx;
		$parametros = array(':idemisor'=>$idemisor);
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}
	public function editarAncho($ancho)
	{
		try {
			$sql = "UPDATE emisor set voucher=:ancho ";
			global $cnx;
			$parametros = array(':ancho'=>$ancho);
			$pre = $cnx->prepare($sql);
			$pre->execute($parametros);
			return $pre;
		} catch(PDOException $e){
			return $e->getMessage();
		}
	}
	public function actualizarCertificado($file)
	{
		try {
			$sql = "UPDATE emisor set certificado=:files ";
			global $cnx;
			$parametros = array(':files'=>$file);
			$pre = $cnx->prepare($sql);
			$pre->execute($parametros);
			return $pre;
		} catch(PDOException $e){
			return $e->getMessage();
		}
	}
	public function guardarClaveCertificado($clave)
	{
		try {
			$sql = "UPDATE emisor set clave_certificado=:clave ";
			global $cnx;
			$parametros = array(':clave'=>$clave);
			$pre = $cnx->prepare($sql);
			$pre->execute($parametros);
			return $pre;
		} catch(PDOException $e){
			return $e->getMessage();
		}
	}
	public function guardarUsuarioSecundario($usuario,$clave)
	{
		try {
			$sql = "UPDATE emisor set usuario_sol=:usuario, clave_sol=:clave ";
			global $cnx;
			$parametros = array(':usuario'=>$usuario,':clave'=>$clave);
			$pre = $cnx->prepare($sql);
			$pre->execute($parametros);
			return $pre;
		} catch(PDOException $e){
			return $e->getMessage();
		}
	}
	public function actualizarServidor($servidor)
	{
		try {
			$sql = "UPDATE emisor set servidor=:servidor ";
			global $cnx;
			$parametros = array(':servidor'=>$servidor);
			$pre = $cnx->prepare($sql);
			$pre->execute($parametros);
			return $pre;
		} catch(PDOException $e){
			return $e->getMessage();
		}
	}

}


?>