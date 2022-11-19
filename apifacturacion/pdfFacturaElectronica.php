<?php 
/*
   Alumno: ELIAS RAYMUNDO TORRES
   Cambios:  Todo los que contiene (*) son agregados y cambiado por mi persona (saludos!!!!!!!!!).
*/
define('FPDF_FONTPATH','font/'); 
require_once('fpdf/fpdf.php');
require_once("phpqrcode/qrlib.php");
require_once('ado/clsVenta.php');
require_once('ado/clsEmisor.php');
require_once('ado/clsCompartido.php');
require_once('cantidad_en_letras.php');//(*) agregado
require_once('ado/clsCliente.php');//(*) agregado

$objVenta = new clsVenta();
$objEmisor = new clsEmisor();
$objCompartido = new clsCompartido();

$venta = $objVenta->obtenerComprobanteId($_GET['id']);
$venta = $venta->fetch(PDO::FETCH_NAMED);

$emisor = $objEmisor->obtenerEmisor($venta['idemisor']);
$emisor = $emisor->fetch(PDO::FETCH_NAMED);

$tipo_comprobante = $objCompartido->obtenerComprobante($venta['tipocomp']);
$tipo_comprobante = $tipo_comprobante->fetch(PDO::FETCH_NAMED);

//(*) agregado ==========
$detalle = $objVenta->listarDetallePorVenta($_GET['id']);//(*) agregado

$Objcliente = new clsCliente();//(*) agregado
$cliente = $Objcliente->consultarClienteId($venta['codcliente']);//(*) agregado
$cliente = $cliente->fetch(PDO::FETCH_NAMED);//(*) agregado

//======================
$pdf = new FPDF();
$pdf->AddPage('P','A4');
//$pdf->AddPage('P',array(80,200));
$pdf->SetFont('Arial','',12);

$pdf->SetFont('Arial','B',12);

$pdf->Image("logo_empresa.jpg",10,2,55,20);

$pdf->Cell(100);
$pdf->Cell(80,6,$emisor['ruc'],'LRT',1,'C',0);

$pdf->Cell(100);
$pdf->Cell(80,6,$tipo_comprobante['descripcion']." ELECTRONICA",'LR',1,'C',0);

$pdf->Cell(100);
$pdf->Cell(80,6,$venta['serie']." - ".$venta['correlativo'],'BLR',0,'C',0);

$pdf->SetAutoPageBreak('auto',2);

$pdf->SetDisplayMode(75);

$pdf->Ln();

$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,6,"RUC:",0,0,'L',0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(30,6,$cliente['nrodoc'],0,1,'L',0);//(*) agregado

$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,6,"CLIENTE:",0,0,'L',0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(30,6,$cliente['razon_social'],0,1,'L',0);//(*) agregado

$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,6,"DIRECCION:",0,0,'L',0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(30,6,$cliente['direccion'],0,1,'L',0);//(*) agregado

$pdf->Ln(3);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(10,6,"ITEM",1,0,'C',0);
$pdf->Cell(20,6,"CANTIDAD",1,0,'C',0);
$pdf->Cell(100,6,"PRODUCTO",1,0,'C',0);
$pdf->Cell(20,6,"V.U.",1,0,'C',0);
$pdf->Cell(25,6,"SUBTOTAL",1,1,'C',0);

$pdf->SetFont('Arial','',8);

foreach ($detalle as $k => $item) {
	$pdf->Cell(10,6,($k+1),1,0,'C',0);//(*) agregado
	$pdf->Cell(20,6,$item['cantidad'],1,0,'C',0);//(*) agregado
	$pdf->Cell(100,6,utf8_decode($item['nombre']),1,0,'L',0);//(*) agregado
	$pdf->Cell(20,6,$item['valor_unitario'],1,0,'C',0);//(*) agregado
	$pdf->Cell(25,6,$item['valor_total'],1,1,'C',0);//(*) agregado
}


$pdf->Cell(150,6,"OP. GRAVADAS",'',0,'R',0);//(*) agregado
$pdf->Cell(25,6,$venta['op_gravadas'],1,1,'C',0);//(*) agregado
$pdf->Cell(150,6,"OP. INAFECTAS",'',0,'R',0);//(*) agregado
$pdf->Cell(25,6,$venta['op_inafectas'],1,1,'C',0);//(*) agregado
$pdf->Cell(150,6,"OP. EXONERADAS",'',0,'R',0);//(*) agregado
$pdf->Cell(25,6,$venta['op_exoneradas'],1,1,'C',0);//(*) agregado
$pdf->Cell(150,6,"IGV",'',0,'R',0);//(*) agregado
$pdf->Cell(25,6,$venta['igv'],1,1,'C',0);//(*) agregado

$pdf->Cell(150,6,"IMPORTE TOTAL",'T',0,'R',0);//(*) agregado
$pdf->Cell(25,6,$venta['total'],1,1,'C',0);//(*) agregado

$pdf->Cell(175,10,CantidadEnLetra($venta['total']),0,1,'C',0);//(*) agregado

//codigo qr
		/*RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION |TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE |*/
//(*) agregado ========================
$ruc = $emisor['ruc'];//(*) agregado
$tipo_documento = $tipo_comprobante['codigo']; //factura
$serie = $venta['serie'];//(*) agregado
$correlativo = $venta['correlativo'];//(*) agregado
$igv = $venta['igv'];//(*) agregado
$total = $venta['total'];//(*) agregado
$fecha = $venta['fecha_emision'];//(*) agregado


$tipodoccliente = $cliente['tipodoc'];//(*) agregado
$nro_doc_cliente = $cliente['nrodoc'];//(*) agregado

$nombrexml = $cliente['nrodoc'].$cliente['tipodoc'].$venta['serie'].$venta['correlativo'];//(*) agregado

$text_qr = $ruc." | ".$tipo_documento." | ".$serie." | ".$correlativo." | ".$igv." | ".$total." | ".$fecha." | ".$tipodoccliente." | ".$nro_doc_cliente;
$ruta_qr = $nombrexml.'.png';

QRcode::png($text_qr, $ruta_qr, 'Q',15, 0);

$pdf->Image($ruta_qr, 88 , $pdf->GetY(),25,25);

$pdf->Ln(30);
$pdf->Cell(190,6,utf8_decode("Representación impresa de la Factura Electrónica"),0,0,'C',0);


$pdf->Output('I',$nombrexml.'.pdf');
//$pdf->Output('D',$nombrexml.'.pdf');
?>