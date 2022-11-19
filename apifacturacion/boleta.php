<?php 
require_once("cantidad_en_letras.php");

$emisor = 	array(
			'tipodoc'		=> '6',
			'ruc' 			=> '20602814425', 
			'razon_social'	=> 'TAQINI TECHNOLOGY SAC', 
			'nombre_comercial'	=> 'TAQINI TECHNOLOGY SAC', 
			'direccion'		=> '8 DE OCTUBRE N 123 - CHICLAYO - CHICLAYO - LAMBAYEQUE', 
			'pais'			=> 'PE', 
			'departamento'  => 'LAMBAYEQUE',//LAMBAYEQUE 
			'provincia'		=> 'CHICLAYO',//CHICLAYO 
			'distrito'		=> 'CHICLAYO', //CHICLAYO
			'ubigeo'		=> '140101', //CHICLAYO
			'usuario_sol'	=> 'MODDATOS', //USUARIO SECUNDARIO EMISOR ELECTRONICO
			'clave_sol'		=> 'MODDATOS' //CLAVE DE USUARIO SECUNDARIO EMISOR ELECTRONICO
			);


$cliente = array(
			'tipodoc'		=> '6',//6->ruc, 1-> dni 
			'ruc'			=> '20480631286', 
			'razon_social'  => 'ASOCIACION CENTRO DE ENTRENAMIENTO EN TECNOLOGIAS DE INFORMACION - CETI', 
			'direccion'		=> 'Cal. Francisco Cuneo-Pataz Nro. 270(Frente al Circulo Departamental de Emple)',
			'pais'			=> 'PE', 
			'departamento'  => '140000',//LAMBAYEQUE 
			'provincia'		=> '140100',//CHICLAYO 
			'distrito'		=> '140101', //CHICLAYO
			);	

$comprobante =	array(
			'tipodoc'		=> '01', //01->FACTURA, 03->BOLETA, 07->NC, 08->ND
			'serie'			=> 'B002',
			'correlativo'	=> '321',
			'fecha_emision' => '2020-08-11',
			'moneda'		=> 'PEN', //PEN->SOLES; USD->DOLARES
			'subtotal'		=> 0, //OP. GRAVADAS
			'igv'			=> 0,
			'total'			=> 0,
			'total_texto'	=> ''
		);

$detalle = 
			array(
				array(
					'item' 				=> 1,
					'codigo'			=> '11',
					'descripcion'		=> 'ACEITE CAPRI',
					'cantidad'			=> 1,
					'valor_unitario'	=> 50,
					'precio_unitario'	=> 59,
					'tipo_precio'		=> "01", //ya incluye igv
					'igv'				=> 9,
					'porcentaje_igv'	=> 18, //de 0 a 100
					'valor_total'		=> 50, 
					'importe_total'		=> 59,
					'unidad'			=> 'NIU',//unidad,
					'codigo_afectacion_alt'	=> '10',
					'codigo_afectacion'	=> 1000,
					'nombre_afectacion'	=>'IGV',
					'tipo_afectacion'	=> 'VAT' //GRAVADAS				 
				),
				array(
					'item' 				=> 2,
					'codigo'			=> '22',
					'descripcion'		=> 'AYUDIN',
					'cantidad'			=> 1,
					'valor_unitario'	=> 50,
					'precio_unitario'	=> 59,
					'tipo_precio'		=> "01", //ya incluye igv
					'igv'				=> 9,
					'porcentaje_igv'	=> 18,
					'valor_total'		=> 50,
					'importe_total'		=> 59,
					'unidad'			=> 'NIU',//unidad,
					'codigo_afectacion_alt'	=> '10',
					'codigo_afectacion'	=> 1000,
					'nombre_afectacion'	=>	'IGV',
					'tipo_afectacion'	=> 'VAT' //GRAVADAS			 
				)				
			);

$subtotal = 0;
$igv = 0;
$total = 0; //op_gravadas

foreach ($detalle as $k => $v) {
	$subtotal = $subtotal + $v['valor_total'];
	$igv = $igv + $v['igv'];
	$total = $total + $v['importe_total'];
}

$comprobante['subtotal'] = $subtotal;
$comprobante['igv'] = $igv;
$comprobante['total'] = $total;
$comprobante['total_texto'] = CantidadEnLetra($total);

require_once("xml.php");

$xml = new GeneradorXML();

//RUC DEL EMISOR - TIPO DE COMPROBANTE - SERIE DEL DOCUMENTO - CORRELATIVO
//01-> FACTURA, 03-> BOLETA, 07-> NOTA DE CREDITO, 08-> NOTA DE DEBITO, 09->GUIA DE REMISION
$nombrexml = $emisor['ruc'].'-'.$comprobante['tipodoc'].'-'.$comprobante['serie'].'-'.$comprobante['correlativo'];


$xml->CrearXMLFactura($nombrexml, $emisor, $cliente, $comprobante, $detalle);

require_once("ApiFacturacion.php");

$apiF = new ApiFacturacion();

$apiF->EnviarComprobanteElectronico($emisor,$nombrexml);

?>