<?php
require_once("../ado/clsCompartido.php");
require_once("../ado/clsEmisor.php");
require_once("../ado/clsVenta.php");
require_once("../ado/clsNotaCredito.php");
require_once("../ado/clsNotaDebito.php");
require_once("../ado/clsCliente.php");
require_once("../xml.php");
require_once("../cantidad_en_letras.php");
require_once("../ApiFacturacion.php");

$accion = $_POST['accion'];

controlador($accion);

function controlador($accion){

	$objCompartido = new clsCompartido();
	$objEmisor = new clsEmisor();
	$generadoXML = new GeneradorXML();
	$api = new ApiFacturacion();
	$objVenta = new clsVenta();
	$objNC = new clsNotaCredito();
	$objND = new clsNotaDebito();
	$objCliente = new clsCliente();

	switch ($accion) {

		case 'LISTAR_SERIES':
			session_start();
			$series = $objCompartido->listarSerie1($_POST['tipocomp'],$_SESSION['tipo']);
			$series = $series->fetchAll(PDO::FETCH_NAMED);
			$series = array("series"=>$series);
			echo json_encode($series);			
			break;
		
		case 'OBTENER_CORRELATIVO':
			$serie = $objCompartido->obtenerSerie($_POST['idserie']);
			$serie = $serie->fetch(PDO::FETCH_NAMED);
			$correlativo = $serie['correlativo']+1;
			echo $correlativo;
			break;

		case 'BUSCAR_PRODUCTO':
			$productos = $objCompartido->listarProducto($_POST['filtro']);
			$productos = $productos->fetchAll(PDO::FETCH_NAMED);
			$productos = array("productos"=>$productos);
			echo json_encode($productos);			
			break;
		case 'MUESTRA_BAJAS':
			
			$tipo = $_POST['tipo'];
			$fi = $_POST['fi'];
			$ff = $_POST['ff'];

            $listadoBoletas = $objVenta->listarComprobantePorTipoConsulta($tipo,$fi,$ff);

            $msj = '';
            foreach ($listadoBoletas as $k => $fila) {
            	$msj .= '
                      <tr>
						<td><input type="checkbox" name="documento[]" value="'.$fila['id'].'" onclick="Marcar(this, \''.$fila['id'].'\')" />
						</td>
						<td>'.$fila['id'].'</td>
						<td>'.$fila['fecha_emision'].'</td>
						<td>'.$fila['serie'].'</td>
						<td>'.$fila['correlativo'].'</td>
					</tr>
                     
            	';
            }
            
            echo $msj;

			break;			

		case 'ADD_PRODUCTO':
           // Actualizar producto de acuerdo a la descripcion ==========
             
		  //===============
			// ----- INICIO LOGICA DE CARRITO ----- //

			$producto = $objCompartido->obtenerProducto($_POST['codigo']);
			$producto = $producto->fetch(PDO::FETCH_NAMED);

			if(isset($_POST['precio'])){
				$producto['precio'] = $_POST['precio'];
			}

			session_start();

			if(!isset($_SESSION['carrito'])){
				$_SESSION['carrito'] = array();
			}

			$carrito = $_SESSION['carrito'];

			$item = count($carrito)+1;
			$cantidad = $_POST['cantidad'];
			$existe = false;
			foreach ($carrito as $k => $v) {
				if($v['codigo']==$_POST['codigo']){
					$item = $k;
					$existe = true;
					break;
				}
			}

			if(!$existe){
				$carrito[$item] = array(
						'codigo'=>$producto['codigo'],
						'nombre'=>$_POST['nombres'],
						'precio'=>$producto['precio'],
						'unidad'=>$producto['unidad'],
						'codigoafectacion'=>$producto['codigoafectacion'],
						'cantidad'=>$cantidad
						);

			}else{
				$carrito[$item]['cantidad']+=$cantidad;
			}

			$_SESSION['carrito'] = $carrito;

			//------------------ FIN LOGICA DE CARRITO ---------- //

			//-------------- INICIO DE CALCULO DE TOTALES -------//
			$op_gravadas=0.00;
			$op_exoneradas=0.00;
			$op_inafectas=0.00;
			$igv;
			$igv_porcentaje=0.18;

			foreach ($carrito as $K => $v) {
				if($v['codigoafectacion']=='10'){
					$op_gravadas = $op_gravadas+$v['precio']*$v['cantidad'];
				}

				if($v['codigoafectacion']=='20'){
					$op_exoneradas = $op_exoneradas+$v['precio']*$v['cantidad'];
				}

				if($v['codigoafectacion']=='30'){
					$op_inafectas = $op_inafectas+$v['precio']*$v['cantidad'];
				}												
			}

			$igv = $op_gravadas*$igv_porcentaje;

			$total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv;

			//----- FIN DEL CALCULO DE TOTALES --------//

			//------ INICIO DE LA TABLITA DEL CARRITO ---- //

			$msj = "
			 <div class='table-responsive'>
			   <table class='table table-bordered table-hover'>
				<tr>
				   <th style='width:5%'>ITEM</th>
				   <th style='width:5%'>CANT</th>
				   <th style='width:67%'>PRODUCTO</th>
				   <th style='width:10%'>V/U</th>
				   <th style='width:10%'>SUBT</th>
				   <th style='width:3%'></th>
				</tr>";
			foreach($carrito as $k=>$v){
				$msj .="
				 <tr>
				    <td>".$k."</td>
					<td>".$v['cantidad']."</td>
					<td>".$v['nombre']."</td>
					<td>".$v['precio']."</td>
					<td>".($v['precio']*$v['cantidad'])."</td>
					<td><a href='javascript:CancelarItem(".$k.")'><i class='fa fa-trash' style='color:red; font-size:10px'></i></a></td>
				</tr>";
			}

			$msj .= "
			  <tr>
			     <td colspan='4' align='right'>OP. GRAVADAS</td><td>".$op_gravadas."</td>
			  </tr>
			  <tr>
			    <td colspan='4' align='right'>IGV(18%)</td><td>".$igv."</td>
			  </tr>			
			  <tr>
			    <td colspan='4' align='right'>OP. EXONERADAS</td><td>".$op_exoneradas."</td>
			  </tr>
			  <tr>
			    <td colspan='4' align='right'>OP. INAFECTAS</td><td>".$op_inafectas."</td>
			   </tr>	
			   <tr>
			    <td colspan='4' align='right'>DESCUENTO. % <input class='form-control' onkeyup='descuentos()' style='width: 80px;' name='porcentaje' id='porcentaje' value='0' ></td><td><span id='valorDesc'>0.00</span></td>
			   </tr>						
			  <tr>
			    <td colspan='4' align='right'><b>TOTAL</b></td><td><b>S/<span id='totales'>".number_format($total,2,'.',',')."</span></b></td>
			  </tr>		
			</table>
			</div>
			<script> 
			  $('#totalboton').html(".$total."); 
			   function descuentos()
			   {
				 var porcentaje = $('#porcentaje').val();
				 var newTotal = ".$total."-(porcentaje*".$total."/100);
				  
				 $('#totales').html(number_format(newTotal,2,'.',','));
			   } 
			 </script>
			";

          echo $msj;
			//------------ FIN DE LA TABLITA DEL CARRITO ------//
			break;	

        case 'MOSTRAR_CARRITO':
			
			session_start();

			if(!isset($_SESSION['carrito'])){
				$_SESSION['carrito'] = array();
			}

			$carrito = $_SESSION['carrito'];
//-------------- INICIO DE CALCULO DE TOTALES -------//
			$op_gravadas=0.00;
			$op_exoneradas=0.00;
			$op_inafectas=0.00;
			$igv;
			$igv_porcentaje=0.18;

			foreach ($carrito as $K => $v) {
				if($v['codigoafectacion']=='10'){
					$op_gravadas = $op_gravadas+$v['precio']*$v['cantidad'];
				}

				if($v['codigoafectacion']=='20'){
					$op_exoneradas = $op_exoneradas+$v['precio']*$v['cantidad'];
				}

				if($v['codigoafectacion']=='30'){
					$op_inafectas = $op_inafectas+$v['precio']*$v['cantidad'];
				}												
			}

			$igv = $op_gravadas*$igv_porcentaje;

			$total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv;

			//----- FIN DEL CALCULO DE TOTALES --------//

			//------ INICIO DE LA TABLITA DEL CARRITO ---- //

			$msj = "
			<div class='table-responsive'>
			   <table class='table table-bordered table-hover'>
				<tr>
				   <th style='width:5%'>ITEM</th>
				   <th style='width:5%'>CANT</th>
				   <th style='width:67%'>PRODUCTO</th>
				   <th style='width:10%'>V/U</th>
				   <th style='width:10%'>SUBT</th>
				   <th style='width:3%'></th>
				</tr>";
			foreach($carrito as $k=>$v){
				$msj .="
				 <tr>
				    <td>".$k."</td>
					<td>".$v['cantidad']."</td>
					<td>".$v['nombre']."</td>
					<td>".$v['precio']."</td>
					<td>".($v['precio']*$v['cantidad'])."</td>
					<td><a href='javascript:CancelarItem(".$k.")'><i class='fa fa-trash' style='color:red; font-size:10px'></i></a></td>
				</tr>";
			}

			$msj .= "
			  <tr>
			     <td colspan='4' align='right'>OP. GRAVADAS</td><td>".$op_gravadas."</td>
			  </tr>
			  <tr>
			    <td colspan='4' align='right'>IGV(18%)</td><td>".$igv."</td>
			  </tr>			
			  <tr>
			    <td colspan='4' align='right'>OP. EXONERADAS</td><td>".$op_exoneradas."</td>
			  </tr>
			  <tr>
			    <td colspan='4' align='right'>OP. INAFECTAS</td><td>".$op_inafectas."</td>
			   </tr>
			   <tr>
			    <td colspan='4' align='right'>DESCUENTO. % <input class='form-control' onkeyup='descuentos()' style='width: 80px;' name='porcentaje' id='porcentaje' value='0' ></td><td><span id='valorDesc'>0.00</span></td>
			   </tr>						
			  <tr>
			    <td colspan='4' align='right'><b>TOTAL</b></td><td><b>S/<span id='totales'>".number_format($total,2,'.',',')."</span></b></td>
			  </tr>		
			</table>
			</div>
			<script> 
			  $('#totalboton').html(".$total."); 
			   function descuentos()
			   {
				 var porcentaje = $('#porcentaje').val();
				 var newTotal = ".$total."-(porcentaje*".$total."/100);
				  
				 $('#totales').html(number_format(newTotal,2,'.',','));
			   } 
			 </script>
			";

          echo $msj;
			//------------ FIN DE LA TABLITA DEL CARRITO ------//
			break;
		case 'CANCELAR_CARRITO':
			session_start();
			//session_destroy();
			unset($_SESSION['carrito']);
			break;
		case 'CANCELAR_ITEM':
			session_start();
			//session_destroy();
			unset($_SESSION['carrito'][$_POST['id']]);
			break;
		case 'REENVIAR_FACTURA':
			
			$id = $_POST['id'];

			$venta = $objVenta->obtenerComprobanteId($id);
			$venta = $venta->fetch(PDO::FETCH_NAMED);

			$emisor = $objEmisor->obtenerEmisor($venta['idemisor']);
			$emisor = $emisor->fetch(PDO::FETCH_NAMED);

			$cliente_existe = $objCliente->consultarClienteId($venta['codcliente']);
			$cliente_existe = $cliente_existe->fetch(PDO::FETCH_NAMED);


			$cliente = array(
				'tipodoc'		=> $cliente_existe['tipodoc'],//6->ruc, 1-> dni 
				'ruc'			=> $cliente_existe['nrodoc'], 
				'razon_social'  => $cliente_existe['razon_social'], 
				'direccion'		=> $cliente_existe['direccion'],
				'pais'			=> 'PE',
				'ubigeo'		=> $cliente_existe['ubigeo']
				);	

			$detallado = $objVenta->listarDetallePorVenta($venta['id']);

			$detalle = array();

			foreach ($detallado as $k => $v) {

				$afectacion = $objCompartido->obtenerRegistroAfectacion($v['codigoafectacion']);
				$afectacion = $afectacion->fetch(PDO::FETCH_NAMED);

				$itemx = array(
					'item' 				=> $v['item'],
					'codigo'			=> $v['codigo'],
					'descripcion'		=> $v['nombre'],
					'cantidad'			=> $v['cantidad'],
					'valor_unitario'	=> $v['precio'],
					'precio_unitario'	=> $v['precio_unitario'],
					'tipo_precio'		=> $v['tipo_precio'], //ya incluye igv
					'igv'				=> $v['igv'],
					'porcentaje_igv'	=> $v['porcentaje_igv'],
					'valor_total'		=> $v['valor_total'],
					'importe_total'		=> $v['importe_total'],
					'unidad'			=> $v['unidad'],//unidad,
					'codigo_afectacion_alt'	=> $v['codigoafectacion'],
					'codigo_afectacion'	=> $afectacion['codigo_afectacion'],
					'nombre_afectacion'	=> $afectacion['nombre_afectacion'],
					'tipo_afectacion'	=> $afectacion['tipo_afectacion']			 
				);

				$itemx;

				$detalle[] = $itemx;
			}

			$comprobante =	array(
					'tipodoc'		=> $venta['tipocomp'],
					'idserie'		=> $venta['idserie'],
					'serie'			=> $venta['serie'],
					'correlativo'	=> $venta['correlativo'],
					'fecha_emision' => $venta['fecha_emision'],
					'moneda'		=> $venta['codmoneda'], //PEN->SOLES; USD->DOLARES
					'total_opgravadas'	=> $venta['op_gravadas'],
					'igv'			=> $venta['igv'],
					'total_opexoneradas'	=> $venta['op_exoneradas'],
					'total_opinafectas'	=> $venta['op_inafectas'],
					'total'			=> $venta['total'],
					'total_texto'	=> CantidadEnLetra($venta['total']),
					'codcliente'	=> $venta['codcliente']
				);	



			$nombre = $emisor['ruc'].'-'.$venta['tipocomp'].'-'.$venta['serie'].'-'.$venta['correlativo'];

			$generadoXML->CrearXMLFactura($nombre, $emisor, $cliente, $comprobante, $detalle);

			$envio_sunat = $api->EnviarComprobanteElectronico($emisor,$nombre,"../");

			$mostrar = json_decode($envio_sunat);

			$estado = $mostrar->estado;
			$codigoerror = $mostrar->codigo;
			$mensajesunat = $mostrar->mensaje;

            $objVenta->actualizarDatosFE($venta['id'], $estado, $codigoerror, $mensajesunat);
             if ($estado==1) {
             	$alerta = 'success';
             }
             if ($estado==2) {
             	$alerta = 'warning';
             }
             if ($estado==3) {
             	$alerta = 'danger';
             }
            echo '
                 <div class="alert alert-'.$alerta.' alert-dismissible fade show" role="alert">
					  La respuesta que obtuvimos de SUNAT: <br>"'.$mensajesunat.' "
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					    <span aria-hidden="true">&times;</span>
					  </button>
					</div>
			';

			break;
		case 'COMPROBAR_EMISION':
			
			$id = $_POST['id'];

			$mostrar = $objVenta->comprobarComprobante($id);
			$mostrar = $mostrar->rowCount();


			echo $mostrar;


			break;
		case 'SESION_CARRITO':
			session_start();
			if(isset($_SESSION['carrito']))
			{
				 echo 1;
			} else {
                 echo 0;
			   }

			break;

		case 'GUARDAR_VENTA':
			session_start();

			//logica de ventas
			//--------------------------
			//fin logica de ventas


			//INICIO PROCESO FACTURACION

			//$generadoXML = new Funciones();

			//obtenemos los datos del emisor de la BD
			$idemisor = $_POST['idemisor'];
			$emisor = $objEmisor->obtenerEmisor($idemisor);
			$emisor = $emisor->fetch(PDO::FETCH_NAMED);
            
           
			$cliente = array(
				'tipodoc'		=> $_POST['tipodoc'],//6->ruc, 1-> dni, 0->sin documento 
				'ruc'			=> $_POST['nrodoc'], 
				'razon_social'  => $_POST['razon_social'], 
				'direccion'		=> $_POST['direccion'],
				'pais'			=> 'PE',
				'ubigeo'		=> $_POST['ubigeo']
				);	

			if($_POST['nrodoc']!='00000000') {

				$cliente_existe = $objCliente->consultarCliente($_POST['nrodoc']);

				if($cliente_existe->rowCount()>0){
					$cliente_existe = $cliente_existe->fetch(PDO::FETCH_NAMED);
				}else{
					$objCliente->insertarCliente($cliente);
					$cliente_existe = $objCliente->consultarCliente($_POST['nrodoc']);
					$cliente_existe = $cliente_existe->fetch(PDO::FETCH_NAMED);
				}

		  } else {
		  	    $objCliente->insertarCliente($cliente);
				$cliente_existe = $objCliente->consultarClienteMax();
				$cliente_existe = $cliente_existe->fetch(PDO::FETCH_NAMED);
		  }

			$idcliente = $cliente_existe['id'];

			$carrito = $_SESSION['carrito'];
			$detalle = array();
			$igv_porcentaje = 0.18;



			$op_gravadas=0.00;
			$op_exoneradas=0.00;
			$op_inafectas=0.00;
			$igv = 0;

			foreach ($carrito as $k => $v) {

				$producto = $objCompartido->obtenerProducto($v['codigo']);
				$producto = $producto->fetch(PDO::FETCH_NAMED);

				$afectacion = $objCompartido->obtenerRegistroAfectacion($producto['codigoafectacion']);
				$afectacion = $afectacion->fetch(PDO::FETCH_NAMED);

				$igv_detalle =0;
				$factor_porcentaje = 1;
				if($producto['codigoafectacion']==10){
					$igv_detalle = $v['precio']*$v['cantidad']*$igv_porcentaje;
					$factor_porcentaje = 1+ $igv_porcentaje;
				}

				$itemx = array(
					'item' 				=> $k,
					'codigo'			=> $v['codigo'],
					'descripcion'		=> $v['nombre'],
					'cantidad'			=> $v['cantidad'],
					'valor_unitario'	=> $v['precio'],
					'precio_unitario'	=> $v['precio']*$factor_porcentaje,
					'tipo_precio'		=> $producto['tipo_precio'], //ya incluye igv
					'igv'				=> $igv_detalle,
					'porcentaje_igv'	=> $igv_porcentaje*100,
					'valor_total'		=> $v['precio']*$v['cantidad'],
					'importe_total'		=> $v['precio']*$v['cantidad']*$factor_porcentaje,
					'unidad'			=> $v['unidad'],//unidad,
					'codigo_afectacion_alt'	=> $producto['codigoafectacion'],
					'codigo_afectacion'	=> $afectacion['codigo_afectacion'],
					'nombre_afectacion'	=> $afectacion['nombre_afectacion'],
					'tipo_afectacion'	=> $afectacion['tipo_afectacion']			 
				);

				$itemx;

				$detalle[] = $itemx;

				if($itemx['codigo_afectacion_alt']==10){
					$op_gravadas = $op_gravadas + $itemx['valor_total'];
				}

				if($itemx['codigo_afectacion_alt']==20){
					$op_exoneradas = $op_exoneradas + $itemx['valor_total'];
				}				

				if($itemx['codigo_afectacion_alt']==30){
					$op_inafectas = $op_inafectas + $itemx['valor_total'];
				}

				$igv = $igv + $igv_detalle;				
			}

			$porcentajeDescuento = $_POST['porcentaje']/100;
			$valorDescuento = $op_exoneradas*$porcentajeDescuento;
			$total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv;
			$totalConDescuento = $total - $valorDescuento;

            if(!empty($_POST['dni1']) and !empty($_POST['nombre_paciente']))
			{
				$observaciones = $_POST['dni1'].' '.$_POST['nombre_paciente'];
			}
			else {
				$observaciones = '';
			}

			$idserie = $_POST['idserie'];

			$seriex = $objCompartido->obtenerSerie($idserie);
			$seriex = $seriex->fetch(PDO::FETCH_NAMED);

			$comprobante =	array(
					'tipodoc'		=> $_POST['tipocomp'],
					'idserie'		=> $idserie,
					'serie'			=> $seriex['serie'],
					'correlativo'	=> $seriex['correlativo']+1,
					'fecha_emision' => $_POST['fecha_emision'],
					'moneda'		=> $_POST['moneda'], //PEN->SOLES; USD->DOLARES
					'total_opgravadas'	=> $op_gravadas,
					'igv'			=> $igv,
					'total_opexoneradas'	=> $op_exoneradas,
					'total_opinafectas'	=> $op_inafectas,
					'totalSinDescuento'	=> $total,
					'descPorcentaje'=> $porcentajeDescuento,
					'descValor'		=> $valorDescuento,
					'total'			=> $totalConDescuento,
					'total_texto'	=> CantidadEnLetra($total),
					'codcliente'	=> $idcliente,
					'observaciones' => $observaciones
				);			

			$objCompartido->actualizarSerie($idserie, $comprobante['correlativo']);

			$nombre = $emisor['ruc'].'-'.$comprobante['tipodoc'].'-'.$comprobante['serie'].'-'.$comprobante['correlativo'];



			if($comprobante['tipodoc']=='01' || $comprobante['tipodoc']=='03'){
				$generadoXML->CrearXMLFactura($nombre, $emisor, $cliente, $comprobante, $detalle);
			}else if($comprobante['tipodoc']=='07'){ //nota de credito
				$generadoXML->CrearXMLNotaCredito($nombre, $emisor, $cliente, $comprobante, $detalle);
			}
			
	       $envio_sunat = $api->EnviarComprobanteElectronico($emisor,$nombre,'../');

			$mostrar = json_decode($envio_sunat);

			$estado = $mostrar->estado;
			$codigoerror = $mostrar->codigo;
			$mensajesunat = $mostrar->mensaje;

          

			//FIN FACTURACION ELECTRONICA


			//REGISTRO EN BASE DE DATOS

			$objVenta->insertarVenta($idemisor, $comprobante);
			$venta = $objVenta->obtenerUltimoComprobanteId();
			$venta = $venta->fetch(PDO::FETCH_NAMED);

			$objVenta->insertarDetalle($venta['id'],$detalle);
			$objVenta->actualizarDatosFE($venta['id'], $estado, $codigoerror, $mensajesunat);

			//FIN DE REGISTRO EN BASE DE DATOS
			$msj = '
                 <div class="alert alert-success alert-dismissible fade show" role="alert">
					  <strong>Muy bien!</strong> La venta se realizo correctamente y obtuvimos la respuesta de SUNAT: '.$mensajesunat.'
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					    <span aria-hidden="true">&times;</span>
					  </button>
					</div>
			';
            
			$datos = array(
				'mensaje'=>$msj,
				'id'=>$venta['id']
			);

			unset($_SESSION['carrito']);

			echo json_encode($datos);

			break;

		case 'GUARDAR_NC':
			session_start();

			//logica de nota de credito
			//--------------------------
			//fin logica de nota de credito


			//INICIO PROCESO FACTURACION

			//$generadoXML = new Funciones();

			//obtenemos los datos del emisor de la BD
			$idemisor = $_POST['idemisor'];
			$emisor = $objEmisor->obtenerEmisor($idemisor);
			$emisor = $emisor->fetch(PDO::FETCH_NAMED);


			$cliente = array(
				'tipodoc'		=> $_POST['tipodoc'],//6->ruc, 1-> dni 
				'ruc'			=> $_POST['nrodoc'], 
				'razon_social'  => $_POST['razon_social'], 
				'direccion'		=> $_POST['direccion'],
				'pais'			=> 'PE'
				);	

			$cliente_existe = $objCliente->consultarCliente($_POST['nrodoc']);

			if($cliente_existe->rowCount()>0){
				$cliente_existe = $cliente_existe->fetch(PDO::FETCH_NAMED);
			}else{
				$objCliente->insertarCliente($cliente);
				$cliente_existe = $objCliente->consultarCliente($_POST['nrodoc']);
				$cliente_existe = $cliente_existe->fetch(PDO::FETCH_NAMED);
			}
			$idcliente = $cliente_existe['id'];

			$carrito = $_SESSION['carrito'];
			$detalle = array();
			$igv_porcentaje = 0.18;



			$op_gravadas=0.00;
			$op_exoneradas=0.00;
			$op_inafectas=0.00;
			$igv = 0;

			foreach ($carrito as $k => $v) {

				$producto = $objCompartido->obtenerProducto($v['codigo']);
				$producto = $producto->fetch(PDO::FETCH_NAMED);

				$afectacion = $objCompartido->obtenerRegistroAfectacion($producto['codigoafectacion']);
				$afectacion = $afectacion->fetch(PDO::FETCH_NAMED);

				$igv_detalle =0;
				$factor_porcentaje = 1;
				if($producto['codigoafectacion']==10){
					$igv_detalle = $v['precio']*$v['cantidad']*$igv_porcentaje;
					$factor_porcentaje = 1+ $igv_porcentaje;
				}

				$itemx = array(
					'item' 				=> $k,
					'codigo'			=> $v['codigo'],
					'descripcion'		=> $v['nombre'],
					'cantidad'			=> $v['cantidad'],
					'valor_unitario'	=> $v['precio'],
					'precio_unitario'	=> $v['precio']*$factor_porcentaje,
					'tipo_precio'		=> $producto['tipo_precio'], //ya incluye igv
					'igv'				=> $igv_detalle,
					'porcentaje_igv'	=> $igv_porcentaje*100,
					'valor_total'		=> $v['precio']*$v['cantidad'],
					'importe_total'		=> $v['precio']*$v['cantidad']*$factor_porcentaje,
					'unidad'			=> $v['unidad'],//unidad,
					'codigo_afectacion_alt'	=> $producto['codigoafectacion'],
					'codigo_afectacion'	=> $afectacion['codigo_afectacion'],
					'nombre_afectacion'	=> $afectacion['nombre_afectacion'],
					'tipo_afectacion'	=> $afectacion['tipo_afectacion']

				);

				$itemx;

				$detalle[] = $itemx;

				if($itemx['codigo_afectacion_alt']==10){
					$op_gravadas = $op_gravadas + $itemx['valor_total'];
				}

				if($itemx['codigo_afectacion_alt']==20){
					$op_exoneradas = $op_exoneradas + $itemx['valor_total'];
				}				

				if($itemx['codigo_afectacion_alt']==30){
					$op_inafectas = $op_inafectas + $itemx['valor_total'];
				}

				$igv = $igv + $igv_detalle;				
			}


			$total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv;

			$idserie = $_POST['idserie'];

			$seriex = $objCompartido->obtenerSerie($idserie);
			$seriex = $seriex->fetch(PDO::FETCH_NAMED);

			$motivo = $objCompartido->getRegistroTablaParametrica('C',$_POST['motivo']);
			$motivo = $motivo->fetch(PDO::FETCH_NAMED);

			$comprobante =	array(
					'tipodoc'		=> $_POST['tipocomp'],
					'idserie'		=> $idserie,
					'serie'			=> $seriex['serie'],
					'correlativo'	=> $seriex['correlativo']+1,
					'fecha_emision' => $_POST['fecha_emision'],
					'moneda'		=> $_POST['moneda'], //PEN->SOLES; USD->DOLARES
					'total_opgravadas'	=> $op_gravadas,
					'igv'			=> $igv,
					'total_opexoneradas'	=> $op_exoneradas,
					'total_opinafectas'	=> $op_inafectas,
					'total'			=> $total,
					'total_texto'	=> CantidadEnLetra($total),
					'codcliente'	=> $idcliente,
					'tipodoc_ref'	=> $_POST['tipocomp_ref'],
					'serie_ref'		=> $_POST['serie_ref'],
					'correlativo_ref'=> $_POST['correlativo_ref'],
					'codmotivo'		=> $_POST['motivo'],
					'descripcion'	=> $motivo['descripcion']					
				);			

			$objCompartido->actualizarSerie($idserie, $comprobante['correlativo']);

			$nombre = $emisor['ruc'].'-'.$comprobante['tipodoc'].'-'.$comprobante['serie'].'-'.$comprobante['correlativo'];

			$generadoXML->CrearXMLNotaCredito($nombre, $emisor, $cliente, $comprobante, $detalle);
			
			$envio_sunat = $api->EnviarComprobanteElectronico($emisor,$nombre,"../");

			$mostrar = json_decode($envio_sunat);

			$estado = $mostrar->estado;
			$codigoerror = $mostrar->codigo;
			$mensajesunat = $mostrar->mensaje;
			//FIN FACTURACION ELECTRONICA


			//REGISTRO EN BASE DE DATOS

			$objNC->insertarNotaCredito($idemisor, $comprobante);
			$nc = $objNC->obtenerUltimoComprobanteId();
			$nc = $nc->fetch(PDO::FETCH_NAMED);

			$objNC->insertarDetalleNotaCredito($nc['id'],$detalle);

			//FIN DE REGISTRO EN BASE DE DATOS
			$msj = ' 
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<strong>Muy bien!</strong> La nota de crédito se realizo correctamente y obtuvimos la respuesta de SUNAT: '.$mensajesunat.'
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
			';
			$datos = array(
				'mensaje'=>$msj,
				'id'=>$nc['id']
			);
			//echo "<script>window.open('./apifacturacion/pdfFacturaElectronica.php?id=".$venta['id']."','_blank')</script>";
			unset($_SESSION['carrito']);

			echo json_encode($datos);
			break;


		case 'GUARDAR_ND':
			session_start();

			//logica de nota de credito
			//--------------------------
			//fin logica de nota de credito


			//INICIO PROCESO FACTURACION

			//$generadoXML = new Funciones();

			//obtenemos los datos del emisor de la BD
			$idemisor = $_POST['idemisor'];
			$emisor = $objEmisor->obtenerEmisor($idemisor);
			$emisor = $emisor->fetch(PDO::FETCH_NAMED);


			$cliente = array(
				'tipodoc'		=> $_POST['tipodoc'],//6->ruc, 1-> dni 
				'ruc'			=> $_POST['nrodoc'], 
				'razon_social'  => $_POST['razon_social'], 
				'direccion'		=> $_POST['direccion'],
				'pais'			=> 'PE'
				);	

			$cliente_existe = $objCliente->consultarCliente($_POST['nrodoc']);

			if($cliente_existe->rowCount()>0){
				$cliente_existe = $cliente_existe->fetch(PDO::FETCH_NAMED);
			}else{
				$objCliente->insertarCliente($cliente);
				$cliente_existe = $objCliente->consultarCliente($_POST['nrodoc']);
				$cliente_existe = $cliente_existe->fetch(PDO::FETCH_NAMED);
			}
			$idcliente = $cliente_existe['id'];

			$carrito = $_SESSION['carrito'];
			$detalle = array();
			$igv_porcentaje = 0.18;



			$op_gravadas=0.00;
			$op_exoneradas=0.00;
			$op_inafectas=0.00;
			$igv = 0;

			foreach ($carrito as $k => $v) {

				$producto = $objCompartido->obtenerProducto($v['codigo']);
				$producto = $producto->fetch(PDO::FETCH_NAMED);

				$afectacion = $objCompartido->obtenerRegistroAfectacion($producto['codigoafectacion']);
				$afectacion = $afectacion->fetch(PDO::FETCH_NAMED);

				$igv_detalle =0;
				$factor_porcentaje = 1;
				if($producto['codigoafectacion']==10){
					$igv_detalle = $v['precio']*$v['cantidad']*$igv_porcentaje;
					$factor_porcentaje = 1+ $igv_porcentaje;
				}

				$itemx = array(
					'item' 				=> $k,
					'codigo'			=> $v['codigo'],
					'descripcion'		=> $v['nombre'],
					'cantidad'			=> $v['cantidad'],
					'valor_unitario'	=> $v['precio'],
					'precio_unitario'	=> $v['precio']*$factor_porcentaje,
					'tipo_precio'		=> $producto['tipo_precio'], //ya incluye igv
					'igv'				=> $igv_detalle,
					'porcentaje_igv'	=> $igv_porcentaje*100,
					'valor_total'		=> $v['precio']*$v['cantidad'],
					'importe_total'		=> $v['precio']*$v['cantidad']*$factor_porcentaje,
					'unidad'			=> $v['unidad'],//unidad,
					'codigo_afectacion_alt'	=> $producto['codigoafectacion'],
					'codigo_afectacion'	=> $afectacion['codigo_afectacion'],
					'nombre_afectacion'	=> $afectacion['nombre_afectacion'],
					'tipo_afectacion'	=> $afectacion['tipo_afectacion']

				);

				$itemx;

				$detalle[] = $itemx;

				if($itemx['codigo_afectacion_alt']==10){
					$op_gravadas = $op_gravadas + $itemx['valor_total'];
				}

				if($itemx['codigo_afectacion_alt']==20){
					$op_exoneradas = $op_exoneradas + $itemx['valor_total'];
				}				

				if($itemx['codigo_afectacion_alt']==30){
					$op_inafectas = $op_inafectas + $itemx['valor_total'];
				}

				$igv = $igv + $igv_detalle;				
			}


			$total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv;

			$idserie = $_POST['idserie'];

			$seriex = $objCompartido->obtenerSerie($idserie);
			$seriex = $seriex->fetch(PDO::FETCH_NAMED);

			$motivo = $objCompartido->getRegistroTablaParametrica('D',$_POST['motivo']);
			$motivo = $motivo->fetch(PDO::FETCH_NAMED);

			$comprobante =	array(
					'tipodoc'		=> $_POST['tipocomp'],
					'idserie'		=> $idserie,
					'serie'			=> $seriex['serie'],
					'correlativo'	=> $seriex['correlativo']+1,
					'fecha_emision' => $_POST['fecha_emision'],
					'moneda'		=> $_POST['moneda'], //PEN->SOLES; USD->DOLARES
					'total_opgravadas'	=> $op_gravadas,
					'igv'			=> $igv,
					'total_opexoneradas'	=> $op_exoneradas,
					'total_opinafectas'	=> $op_inafectas,
					'total'			=> $total,
					'total_texto'	=> CantidadEnLetra($total),
					'codcliente'	=> $idcliente,
					'tipodoc_ref'	=> $_POST['tipocomp_ref'],
					'serie_ref'		=> $_POST['serie_ref'],
					'correlativo_ref'=> $_POST['correlativo_ref'],
					'codmotivo'		=> $_POST['motivo'],
					'descripcion'	=> $motivo['descripcion']					
				);			

			$objCompartido->actualizarSerie($idserie, $comprobante['correlativo']);

			$nombre = $emisor['ruc'].'-'.$comprobante['tipodoc'].'-'.$comprobante['serie'].'-'.$comprobante['correlativo'];

			$generadoXML->CrearXMLNotaDebito($nombre, $emisor, $cliente, $comprobante, $detalle);
			
			$envio_sunat = $api->EnviarComprobanteElectronico($emisor,$nombre,"../");

			$mostrar = json_decode($envio_sunat);

			$estado = $mostrar->estado;
			$codigoerror = $mostrar->codigo;
			$mensajesunat = $mostrar->mensaje;
			
			//FIN FACTURACION ELECTRONICA


			//REGISTRO EN BASE DE DATOS

			$objND->insertarNotaDebito($idemisor, $comprobante);
			$nd = $objND->obtenerUltimoComprobanteId();
			$nd = $nd->fetch(PDO::FETCH_NAMED);

			$objND->insertarDetalleNotaDebito($nd['id'],$detalle);

			//FIN DE REGISTRO EN BASE DE DATOS
			echo '
			<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>Muy bien!</strong> La nota de Débito se realizo correctamente y obtuvimos la respuesta de SUNAT: '.$mensajesunat.'
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		    </div>
			';
			//echo "<script>window.open('./apifacturacion/pdfFacturaElectronica.php?id=".$venta['id']."','_blank')</script>";
			unset($_SESSION['carrito']);
			break;

		case "ENVIO_RESUMEN":

			$idemisor = $_POST['idemisor'];
			$emisor = $objEmisor->obtenerEmisor($idemisor);
			$emisor = $emisor->fetch(PDO::FETCH_NAMED);

			$resumen1 = $objCompartido->resumenHoy();
			$resumen = $resumen1->rowCount();
			if ($resumen!=0) {
				$resumen = $resumen1->fetch();
				$num = $resumen['correlativo']+1;
			} else {
				$num = 1;
			}

			$cabecera = array(
						"tipodoc"		=>"RC",
						"serie"			=>date('Ymd'),
						"correlativo"	=>$num,
						"fecha_emision" =>date('Y-m-d'),			
						"fecha_envio"	=>date('Y-m-d')	
				);


			$items = array();

			$ids = $_POST['documento'];
			$i=1;
			foreach($ids as $v){
				$boleta = $objVenta->obtenerComprobanteId($v);
				$boleta = $boleta->fetch(PDO::FETCH_NAMED);

				$items[] = array(
						"item"				=> $i,
						"idventa"			=> $boleta['id'],
						"tipodoc"			=> $boleta['tipocomp'],
						"serie"				=> $boleta['serie'],
						"correlativo"		=> $boleta['correlativo'],
						"condicion"			=> $_POST['condicion'], //1->Registro, 2->Actualiza, 3->Bajas
						"moneda"			=> $boleta['codmoneda'],			
						"importe_total"		=> $boleta['total'],
						"valor_total"		=> $boleta['op_gravadas'],
						"igv_total"			=> $boleta['igv'],
						"codigo_afectacion"	=> "1000",
						"nombre_afectacion"	=> "IGV",
						"tipo_afectacion"	=> "VAT"
					);
				$i++;
			}
			
			$nombrexml = $emisor['ruc'].'-'.$cabecera['tipodoc'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'];

			$generadoXML->CrearXMLResumenDocumentos($emisor, $cabecera, $items, $nombrexml);

			$ticket = $api->EnviarResumenComprobantes($emisor,$nombrexml,"../");
			$error = 'error';

			$comparar = strpos($ticket, $error);

			if ($comparar===false) {
				$result = $api->ConsultarTicket($emisor, $cabecera, $ticket);
				    $mostrar = json_decode($result);

					$estado = $mostrar->estado;
					$codigoerror = $mostrar->codigo;
					$mensajesunat = $mostrar->mensaje;
			} else {

				   $estado = 0;
					$codigoerror = 0;
					$mensajesunat = $ticket;
			}

			if ($estado!=0) {
				//guardar en la tabla resumen
				$datos = array(
                     'idemisor' => $idemisor,
                     'fecha_envio' =>date('Y-m-d'),
                     'correlativo' =>$num,
                     'nombrexml' =>$nombrexml,
                     'feestado' =>$estado,
                     'fecodigoerror' =>$codigoerror,
                     'femensajesunat' =>$mensajesunat,
                     'ticket' =>$ticket

				);
				$objCompartido->guardarResumen($datos);
                $envioResumen = $objCompartido->ResumenId();
                $envioResumen = $envioResumen->fetch();
                $idenvio = $envioResumen['idenvio'];

				$objCompartido->guardarResumenDetalles($idenvio,$items);
				//actualizar la tabla ventas
				$objVenta->actualizarVenta($items,$datos);
			}

			if ($estado!=1) {
				$alerta = 'danger';
			} else {
				$alerta = 'success';
			}
          
          echo '
                 <div class="alert alert-'.$alerta.' alert-dismissible fade show" role="alert">
					  <strong>Muy bien!</strong> El resumen se envió y obtuvimos la respuesta de SUNAT: '.$mensajesunat.'
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					    <span aria-hidden="true">&times;</span>
					  </button>
					</div>
			';


			break;

		case "ENVIO_BAJAS":

			$idemisor = $_POST['idemisor'];
			$emisor = $objEmisor->obtenerEmisor($idemisor);
			$emisor = $emisor->fetch(PDO::FETCH_NAMED);

			$cabecera = array(
						"tipodoc"		=>"RA",
						"serie"			=>date('Ymd'),
						"correlativo"	=>"1",
						"fecha_emision" =>date('Y-m-d'),			
						"fecha_envio"	=>date('Y-m-d')	
				);


			$items = array();

			$ids = $_POST['documento'];
			$i=1;
			foreach($ids as $v){
				$factura = $objVenta->obtenerComprobanteId($v);
				$factura = $factura->fetch(PDO::FETCH_NAMED);

				$items[] = array(
						"item"				=> $i,
						"tipodoc"			=> $factura["tipocomp"],
						"serie"				=> $factura["serie"],
						"correlativo"		=> $factura["correlativo"],
						"motivo"			=> "ERROR EN DOCUMENTO"
					);
				$i++;
			}
			
			$nombrexml = $emisor['ruc'].'-'.$cabecera['tipodoc'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'];

			$generadoXML->CrearXmlBajaDocumentos($emisor, $cabecera, $items, $nombrexml);

			$ticket = $api->EnviarResumenComprobantes($emisor,$nombrexml,"../");

			$api->ConsultarTicket($emisor, $cabecera, $ticket);

			break;
		case 'GETSTATUSCDR':
			
			$id = $_POST['id'];

			$venta = $objVenta->obtenerComprobanteId($id);
			$venta = $venta->fetch(PDO::FETCH_NAMED);
             if($venta['feestado']==1) {
			$emisor = $objEmisor->obtenerEmisor($venta['idemisor']);
			$emisor = $emisor->fetch(PDO::FETCH_NAMED);

			$cliente_existe = $objCliente->consultarClienteId($venta['codcliente']);
			$cliente_existe = $cliente_existe->fetch(PDO::FETCH_NAMED);

			$comprobante =	array(
					'tipodoc'		=> $venta['tipocomp'],
					'idserie'		=> $venta['idserie'],
					'serie'			=> $venta['serie'],
					'correlativo'	=> $venta['correlativo'],
					'fecha_emision' => $venta['fecha_emision'],
					'moneda'		=> $venta['codmoneda'], //PEN->SOLES; USD->DOLARES
					'total_opgravadas'	=> $venta['op_gravadas'],
					'igv'			=> $venta['igv'],
					'total_opexoneradas'	=> $venta['op_exoneradas'],
					'total_opinafectas'	=> $venta['op_inafectas'],
					'total'			=> $venta['total'],
					'total_texto'	=> CantidadEnLetra($venta['total']),
					'codcliente'	=> $venta['codcliente'],
					'ruc'			=> $cliente_existe['nrodoc']
				);	



			$nombre = $emisor['ruc'].'-'.$venta['tipocomp'].'-'.$venta['serie'].'-'.$venta['correlativo'];

            $nombre = 'cdr/R-'.$nombre.'.XML';
            if(!file_exists($nombre)) {
					$envio_cdr = $api->getStatusCDR($emisor,$nombre,$comprobante);

					$mostrar = json_decode($envio_cdr);

					$estado = $mostrar->estado;
					$codigoerror = $mostrar->codigo;
					$mensajesunat = $mostrar->mensaje;


		             if ($estado==1) {
		             	$alerta = 'success';
		             }
		             if ($estado==2) {
		             	$alerta = 'warning';
		             }
		             if ($estado==3) {
		             	$alerta = 'danger';
		             }
		            echo '
		                 <div class="alert alert-'.$alerta.' alert-dismissible fade show" role="alert">
							  La respuesta que obtuvimos de SUNAT: <br>"'.$mensajesunat.' "
							  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
							    <span aria-hidden="true">&times;</span>
							  </button>
							</div>
					';
			} else {
				echo 0;
			}
		} else {
			echo 0;
		}

			break;
		case 'CDR':
			$id = $_POST['id'];

			$venta = $objVenta->obtenerComprobanteId($id);
			$venta = $venta->fetch(PDO::FETCH_NAMED);

			$emisor = $objEmisor->obtenerEmisor($venta['idemisor']);
			$emisor = $emisor->fetch(PDO::FETCH_NAMED);


			$nombre1 = $emisor['ruc'].'-'.$venta['tipocomp'].'-'.$venta['serie'].'-'.$venta['correlativo'];

            $nombre = 'cdr/R-'.$nombre1.'.XML';
            if(file_exists($nombre)) {
				echo 1;
			} else {
				echo 0;
			}
            

			break;
		case 'LISTAR_EDITAR_SERIES':
			$msj = '';
			$mostrar = $objCompartido->serieTipos();
                 foreach ($mostrar as $serie) {
                    $msj .= '
                      <tr>
                        <td>'.$serie['descripcion'].'</td>
                        <td><input class="form-control" type="text" name="serieEdit" id="serieEdit'.$serie['id'].'" value="'.$serie['serie'].'"></td>
                        <td><input class="form-control" type="number" name="num" id="num'.$serie['id'].'" value="'.$serie['correlativo'].'"></td>
                        <td>
                          <button class="btn btn-warning" onclick="edit('.$serie['id'].')"><i class="fa fa-edit"></i> </button> 
                          <button class="btn btn-danger" onclick="deleteSeries('.$serie['id'].')"><i class="fa fa-trash"></i> </button> 
                        </td>
                       </tr>
                    ';
                 }
			 echo $msj;

			break;
		case 'EDITAR_SERIES_GUARDAR':			
			$objCompartido->actualizarSerieVarios($_POST['id'],$_POST['serie'],$_POST['numero']);
			break;
		case 'AGREGAR_SERIES_LISTA':
			$objCompartido->guardarListaSerie($_POST['serie'],$_POST['tipo']);
			break;
		case 'ELIMINAR_SERIES_LISTA':
			$objCompartido->eliminarListaSerie($_POST['id']);
			break;
		case 'ANCHO':
			$ancho = $objEmisor->consultarListaEmisores();
			$ancho = $ancho->fetch();

			echo $ancho['voucher'];
			break;
		case 'EDITAR_ANCHO':
			$objEmisor->editarAncho($_POST['ancho']);
			break;
		case 'VERIFICAR_CERTIFICADO':
			$emisor = $objEmisor->consultarListaEmisores();
			$emisor = $emisor->fetch();

			$certificado = '../../apifacturacion/'.$emisor['certificado'];
             
			if (file_exists($certificado) && !empty($emisor['certificado'])) {
               $msj = '
			   <div class="alert alert-warning alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h5><i class="icon fas fa-check"></i> Alerta!</h5>
					Existe un certificado digital instalado. Si desea puede reemplazar.
			   </div>  
			   ';
			} else {
				$msj = '
				<div class="alert alert-info alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h5><i class="icon fas fa-info"></i> Alerta!</h5>
					Aun no cuenta con el certificado digital para emitir!
				</div>
				';
			}

			echo $msj;

			break;
		case 'GUARDAR_CLAVE_DIGITAL':
			$objEmisor->guardarClaveCertificado($_POST['clave']);
			break;
		case 'MOSTRAR_CLAVE_DIGITAL':
			$emisor = $objEmisor->consultarListaEmisores();
			$emisor = $emisor->fetch();

			echo $emisor['clave_certificado'];
			break;
		case 'MOSTRAR_USUARIO_SECUNDARIO':
			
			$emisor = $objEmisor->consultarListaEmisores();
			$emisor = $emisor->fetch();

			echo json_encode($emisor);

			break;
		case 'GUARDAR_USUARIO_SECUNDARIO':
			$objEmisor->guardarUsuarioSecundario($_POST['usuario'],$_POST['clave']);
			break;
		
		case 'VERIFICAR_SERVIDOR':
			$emisor = $objEmisor->consultarListaEmisores();
			$emisor = $emisor->fetch();

			echo $emisor['servidor'];
			break;
		case 'ACTUALIZAR_SERVIDOR':
			$objEmisor->actualizarServidor($_POST['servidor']);
			break;
		
		default:
			# code...
			break;
	}

}

?>