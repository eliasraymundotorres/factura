<?php
require_once("conexion.php");

class clsVenta{

	function insertarDetalle($idventa,$detalle){
		$sql = "INSERT INTO detalle(id,idventa, item, idproducto, cantidad, valor_unitario, precio_unitario, igv, porcentaje_igv, valor_total, importe_total,descripcion)
			VALUES (NULL, :idventa, :item, :idproducto, :cantidad, :valor_unitario, :precio_unitario, :igv, :porcentaje_igv, :valor_total, :importe_total,:descripcion)";
	
			global $cnx;
			$pre = $cnx->prepare($sql);

			foreach($detalle as $k=>$v){
				$parametros = array(
					':idventa'		=>$idventa,
					':item'			=>$v['item'],
					':idproducto'	=>$v['codigo'],
					':cantidad'		=>$v['cantidad'],
					':valor_unitario'=>$v['valor_unitario'],
					':precio_unitario'=>$v['precio_unitario'],
					':igv'			=>$v['igv'],
					':porcentaje_igv'=>$v['porcentaje_igv'],
					':valor_total'	=> $v['valor_total'],
					':importe_total'=> $v['importe_total'],
					':descripcion' =>$v['descripcion']
					);
				$pre->execute($parametros);
			}
	}

	function insertarVenta($idemisor, $venta){
		$sql = "INSERT INTO venta(id, idemisor, tipocomp, idserie, serie, correlativo, fecha_emision, codmoneda, op_gravadas, op_exoneradas, op_inafectas, igv, descuento, total, codcliente, observaciones)
				VALUES (NULL, :idemisor, :tipocomp, :idserie, :serie, :correlativo, :fecha_emision, :codmoneda, :op_gravadas, :op_exoneradas, :op_inafectas, :igv, :descuento, :total, :codcliente, :observaciones)";
		$parametros = array(
					':idemisor'=>$idemisor,
					':tipocomp'=>$venta['tipodoc'],
					':idserie' =>$venta['idserie'],
					':serie'   =>$venta['serie'],
					':correlativo' =>$venta['correlativo'],
					':fecha_emision'=>$venta['fecha_emision'],
					':codmoneda'  => $venta['moneda'],
					':op_gravadas'=>$venta['total_opgravadas'],
					':op_exoneradas'=>$venta['total_opexoneradas'],
					':op_inafectas' =>$venta['total_opinafectas'],
					':igv'			=>$venta['igv'],
					':descuento'	=>$venta['descValor'],
					':total'		=>$venta['total'],
					':codcliente'	=>$venta['codcliente'],
					':observaciones'=>$venta['observaciones']			
				);

			global $cnx;
			$pre = $cnx->prepare($sql);
			$pre->execute($parametros);
			return $pre;
	}

	function actualizarDatosFE($idventa, $estado, $codigoerror, $mensajesunat){
		$sql = "UPDATE venta SET feestado=:feestado, fecodigoerror=:fecodigoerror, femensajesunat=:femensajesunat WHERE id=:idventa";
		global $cnx;
		$parametros = array(
						':feestado'=>$estado, 
						':fecodigoerror'=>$codigoerror, 
						':femensajesunat'=>$mensajesunat, 
						':idventa'=>$idventa
					);
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function listarComprobante(){
		$sql = "SELECT * FROM venta";
		global $cnx;
		return $cnx->query($sql);		
	}

	function listarComprobantePorTipo($tipo_comp){
		$sql = "SELECT * FROM venta WHERE tipocomp=:tipo and feestado<>:estado ";

		$parametros = array(':tipo'=>$tipo_comp,':estado'=>1);
		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;		
	}
	function listarComprobantePorTipoConsulta($tipo_comp,$fi,$ff){
		try {
			$sql = "SELECT * FROM venta WHERE tipocomp=:tipo and fecha_emision between :fi and :ff ";

			$parametros = array(':tipo'=>$tipo_comp,':fi'=>$fi,':ff'=>$ff);
			global $cnx;
			$pre = $cnx->prepare($sql);
			$pre->execute($parametros);
			return $pre;		
	  }catch(PDOException $e){
      return $e->getMessage();
	  }
	}
	function reportePorEstado($estado,$fi,$ff)
	{
		try {
			$sql = "SELECT SUM(total) as monto FROM venta WHERE feestado=:estado and fecha_emision between :fi and :ff ";

			$parametros = array(':estado'=>$estado,':fi'=>$fi,':ff'=>$ff);
			global $cnx;
			$pre = $cnx->prepare($sql);
			$pre->execute($parametros);
			return $pre;		
	  }catch(PDOException $e){
      return $e->getMessage();
	  }
	}
	function reporteCantidadEnvio($estado,$fi,$ff)
	{
		try {
			$sql = "SELECT *FROM venta WHERE feestado=:estado and fecha_emision between :fi and :ff ";

			$parametros = array(':estado'=>$estado,':fi'=>$fi,':ff'=>$ff);
			global $cnx;
			$pre = $cnx->prepare($sql);
			$pre->execute($parametros);
			return $pre;		
	  }catch(PDOException $e){
      return $e->getMessage();
	  }
	}

	function obtenerUltimoComprobanteId(){
		$sql = "SELECT * FROM venta ORDER BY id DESC LIMIT 1";
		global $cnx;
		return $cnx->query($sql);		
	}

	function obtenerComprobanteId($id){
		$sql = "SELECT * FROM venta WHERE id=?";
		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute(array($id));
		return $pre;		
		
	}

	function listarDetallePorVenta($idventa){
		$sql = "SELECT * FROM detalle as d, producto as p WHERE d.idproducto=p.codigo and  d.idventa=?";
		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute(array($idventa));
		return $pre;		
	}

	function mostrarVentas()
	{
		$sql = "SELECT v.id, v.tipocomp, v.fecha_emision,c.nrodoc,c.razon_social, v.serie, v.correlativo, v.codmoneda,v.total, v.feestado from venta as v join cliente as c on v.codcliente=c.id  ";
		
		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute();
		return $pre;
	}
	
	function mostrarVentasPorFecha($fi,$ff)
	{
		$sql = "SELECT v.id, v.fecha_emision,c.nrodoc,c.razon_social, v.serie, v.correlativo, v.codmoneda,v.total, v.feestado from venta as v join cliente as c on v.codcliente=c.id WHERE v.fecha_emision between :fi and :ff ";
		$parametros = array(':fi'=>$fi,':ff'=>$ff);
		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function comprobarComprobante($id)
	{
		$sql = "SELECT * FROM venta WHERE id=:id and feestado=:estado ";
        
        $parametros = array(':id'=>$id,':estado'=>1);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}
	function actualizarVenta($items,$datos)
	{
		$sql = "UPDATE venta set feestado=:estado, fecodigoerror=:codigo, femensajesunat=:mensaje where id=:id ";
        global $cnx;
		$pre = $cnx->prepare($sql);
      foreach ($items as $k => $value) {
   
        $parametros = array(
        	            ':id' =>$value['idventa'],
        	            ':estado'=>$datos['feestado'],
        	            ':codigo'=>$datos['fecodigoerror'],
        	            ':mensaje'=>$datos['femensajesunat']
        	        );
           $pre->execute($parametros);
	     }	
		
		
	}


}

?>