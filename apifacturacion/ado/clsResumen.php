<?php
require_once("conexion.php");

class clsResumen{

	function insertarDetalle($idenvio,$detalle){
		$sql = "INSERT INTO envio_resumen_detalle(iddetalle, idenvio, idventa, condicion)
			VALUES (NULL, :idenvio, :idventa, :condicion)";
	
			global $cnx;
			$pre = $cnx->prepare($sql);

			foreach($detalle as $k=>$v){
				$parametros = array(
					':idenvio'		=>$idenvio,
					':idventa'		=>$v['idventa'],
					':condicion'	=>$v['condicion']
					);
				$pre->execute($parametros);
			}
	}

	function insertarResumen($idemisor, $resumen){
		$sql = "INSERT INTO envio_resumen(idenvio, idemisor, fecha_envio, correlativo, resumen,baja, nombrexml, feestado)
				VALUES (NULL, :idemisor, :fecha_envio, :correlativo, :resumen, :baja, :nombrexml, :feestado)";
		$parametros = array(
					':idemisor'		=> $idemisor,
					':fecha_envio'=>$resumen['fecha_envio'],
					':correlativo'=>$resumen['correlativo'],
					':resumen' =>$resumen['resumen'],
					':baja'   =>$resumen['baja'],
					':nombrexml' =>$resumen['nombrexml'],
					':feestado'=>$venta['feestado']					
				);
	}

	function actualizarDatosFE($idresumen, $feestado, $codigoerror, $mensajesunat, $ticket){
		$sql = "UPDATE envio_resumen SET feestado=:feestado, fecodigoerror=:fecodigoerror, femensajesunat=:femensajesunat, ticket= :ticket WHERE idenvio=:idresumen";
		global $cnx;
		$parametros = array(
						':feestado'=>$feestado, 
						':fecodigoerror'=>$fecodigoerror, 
						':femensajesunat'=>$femensajesunat,
						':ticket'=>$ticket, 
						':idresumen'=>$idresumen
					);
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function listarResumenes(){
		$sql = "SELECT * FROM envio_resumen";
		global $cnx;
		return $cnx->query($sql);		
	}

}

?>