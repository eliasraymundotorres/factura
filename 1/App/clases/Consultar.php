<?php

Class Consultar
{
	public function emisor()
	{
		try{
          $sql = "SELECT *from emisor where id=:id ";
            $parametros = array(':id'=>1);
            global $cnx;
            $pre = $cnx->prepare($sql);
            $pre->execute($parametros);
            // $ok = $pre->fetch();
            return $pre;
        }catch(PDOException $e){
            return $e->getMessage();
        }
	}
	public function listarComprobantes(){
	  try{
		$sql = "SELECT * FROM tipo_comprobante";
		global $cnx;
		return $cnx->query($sql);

		}catch(PDOException $e){
            return $e->getMessage();
        }
	}
	function listarVenta($idventa){
	  try{

		$sql = "SELECT * FROM venta WHERE id=?";
		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute(array($idventa));
		return $pre;		

		}catch(PDOException $e){
            return $e->getMessage();
        }
	}
	public function Comprobando($datos)
	{
		try{

		$sql = "SELECT * FROM venta WHERE tipocomp=:tipocomp and serie=:serie and correlativo=:numeracion and fecha_emision=:fecha and total=:total and feestado=:estado ";

		$parametros = array(
			':tipocomp'=>$datos['tipocomp'],
			':serie'=>$datos['serie'],
			':numeracion'=>$datos['numeracion'],
			':fecha'=>$datos['fecha_emision'],
			':total'=>$datos['total'],
			':estado'=>1
		);
		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;		

		}catch(PDOException $e){
            return $e->getMessage();
        }
	}
}

?>