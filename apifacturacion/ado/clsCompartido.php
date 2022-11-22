<?php
require_once("conexion.php");

class clsCompartido{

 
	public function listarAfectacion()
	{
		try {

		$sql = "SELECT * FROM tipo_afectacion";
		global $cnx;
		
		$pre = $cnx->prepare($sql);
        $pre->execute();
         
         return $pre;
	
		}catch(PDOException $e){
            return $e->getMessage();
        }
	  }
	  public function verificarSerie()
	{
		try {

		$sql = "SELECT * FROM serie";
		global $cnx;
		
		$pre = $cnx->prepare($sql);
        $pre->execute();
         
         return $pre;
	
		}catch(PDOException $e){
            return $e->getMessage();
        }
	  }
	  public function fechaContrato()
	{
		try {

		$sql = "SELECT * FROM contrato";
		global $cnx;
		
		$pre = $cnx->prepare($sql);
        $pre->execute();
         
         return $pre;
	
		}catch(PDOException $e){
            return $e->getMessage();
        }
	  }
	  public function serieTipos()
	{
		try {

		$sql = "SELECT * FROM serie as s join tipo_comprobante as t on s.tipocomp=t.codigo order by t.codigo, s.serie asc ";
		global $cnx;
		
		$pre = $cnx->prepare($sql);
        $pre->execute();
         
         return $pre;
	
		}catch(PDOException $e){
            return $e->getMessage();
        }
	  }
	  public function guardarListaSerie($serie,$tipo)
	  {
        try {
			$sql = "INSERT INTO serie(tipocomp,serie,correlativo) values (:tipo,:serie,:num) ";
			global $cnx;
			$parametros = array(
				':serie'=>$serie,
				':tipo'=>$tipo,
				':num'=>0
			);
			$pre = $cnx->prepare($sql);
			$pre->execute($parametros);
			return $pre;
		}catch(PDOException $e){
			return $e->getMessage();
		}
	  }
	  public function eliminarListaSerie($id)
	  {
		try {
			$sql = "DELETE FROM serie where id=:id ";
			global $cnx;
			$parametros = array(
				':id'=>$id
			);
			$pre = $cnx->prepare($sql);
			$pre->execute($parametros);
			return $pre;
		}catch(PDOException $e){
			return $e->getMessage();
		}
	  }
	  public function actualizarSerieVarios($id,$serie,$numero)
	  {
		try {
			$sql = "UPDATE serie set serie=:serie, correlativo=:numero where id=:id ";
			global $cnx;
			$parametros = array(
				':id'=>$id,
				':serie'=>$serie,
				':numero'=>$numero
			);
			$pre = $cnx->prepare($sql);
			$pre->execute($parametros);
			return $pre;
		}catch(PDOException $e){
			return $e->getMessage();
		}
	  }
   

	function listarMonedas(){
		$sql = "SELECT * FROM moneda";
		global $cnx;
		return $cnx->query($sql);
	}

	function listarProducto($filtro){
		$sql = "SELECT * FROM producto WHERE nombre LIKE :filtro";
		global $cnx;
		$parametros = array(':filtro'=>'%'.$filtro.'%');
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;	
	}

	function obtenerProducto($codigo){
		$sql = "SELECT * FROM producto WHERE codigo=:codigo";
		global $cnx;
		$parametros = array(':codigo'=>$codigo);
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;	
	}

	function listarSerie($tipocomp){
		$sql = "SELECT * FROM serie WHERE tipocomp=:tipocomp";
		global $cnx;
		$parametros = array(':tipocomp'=>$tipocomp);
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}
	function listarSerie1($tipocomp,$tipo){
		$sql = "SELECT * FROM serie WHERE tipocomp=:tipocomp and tipo=:tipo ";
		global $cnx;
		$parametros = array(':tipocomp'=>$tipocomp,':tipo'=>$tipo);
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function obtenerSerie($idserie){
		$sql = "SELECT * FROM serie WHERE id=:idserie";
		global $cnx;
		$parametros = array(':idserie'=>$idserie);
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function actualizarSerie($idserie, $correlativo){
		$sql = "UPDATE serie SET correlativo=:correlativo WHERE id=:idserie";
		global $cnx;
		$parametros = array(':idserie'=>$idserie, ':correlativo'=>$correlativo);
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function obtenerRegistroAfectacion($codigoafectacion){
		$sql = "SELECT * FROM tipo_afectacion WHERE codigo=:codigoafectacion";
		global $cnx;
		$parametros = array(':codigoafectacion'=>$codigoafectacion);
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function listarComprobantes(){
		$sql = "SELECT * FROM tipo_comprobante";
		global $cnx;
		return $cnx->query($sql);
	}

	function listarComprobantes1($tipo,$comp){
		$sql = "SELECT * FROM tipo_comprobante as tc join serie as s on s.tipocomp=tc.codigo WHERE s.tipo=:tipo and s.tipocomp=:comp ";
		global $cnx;
		$parametros = array(':tipo'=>$tipo,':comp'=>$comp);
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function obtenerComprobante($codigo){
		$sql = "SELECT * FROM tipo_comprobante WHERE codigo=?";
		global $cnx;
		$parametros = array($codigo);
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function listarTipoDocumento(){
		$sql = "SELECT * FROM tipo_documento";
		global $cnx;
		return $cnx->query($sql);		
	}

	function listarUnidad(){
		$sql = "SELECT * FROM unidad";
		global $cnx;
		return $cnx->query($sql);		
	}

	function listarTablaParametrica($tipo){
		$sql = "SELECT * FROM tabla_parametrica WHERE tipo=:tipo";
		global $cnx;
		$parametros = array(':tipo'=>$tipo);
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function getRegistroTablaParametrica($tipo,$codigo){
		$sql = "SELECT * FROM tabla_parametrica WHERE tipo=:tipo AND codigo=:codigo";
		global $cnx;
		$parametros = array(':tipo'=>$tipo, ':codigo'=>$codigo);
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function resumenHoy()
	{
		$sql = "SELECT * FROM envio_resumen where fecha_envio=:fecha order by idenvio desc Limit 1 ";
		global $cnx;
		$parametros = array(':fecha'=>date('Y-m-d'));
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function guardarResumen($datos)
	{
		$sql = "INSERT INTO envio_resumen (idemisor,fecha_envio,correlativo,nombrexml,feestado,fecodigoerror,femensajesunat,ticket) 
		                           values(:idemisor,:fecha,:correlativo,:nombrexml,:estado,:codigo,:mensaje,:ticket) ";
		global $cnx;
		$parametros = array(
                 ':idemisor'=>$datos['idemisor'],
                 ':fecha' =>$datos['fecha_envio'],
                 ':correlativo' =>$datos['correlativo'],
                 ':nombrexml'=>$datos['nombrexml'],
                 ':estado' =>$datos['feestado'],
                 ':codigo' =>$datos['fecodigoerror'],
                 ':mensaje' =>$datos['femensajesunat'],
                 ':ticket' =>$datos['ticket']
		  );
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}
	function guardarResumenDetalles($idenvio,$items)
	{
		$sql = "INSERT INTO envio_resumen_detalle (idenvio,idventa,condicion) values (:idenvio,:idventa,:condicion) ";
		global $cnx;
		$pre = $cnx->prepare($sql);

		foreach ($items as $k => $value) {
			$parametros = array(
					':idenvio'=>$idenvio,
					':idventa'=>$value['idventa'],
					':condicion'=>$value['condicion']
			);
		 $pre->execute($parametros);
		}
	}
	function ResumenId()
	{
		$sql = "SELECT * FROM envio_resumen order by idenvio desc Limit 1 ";
		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute();
		return $pre;
	}
	function usuarioVenta($id)
	{
		$sql = "SELECT * FROM usuario where id=:id ";
		global $cnx;
		$parametros = array(':id'=>$id);
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}
	

}

?>