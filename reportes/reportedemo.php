<?php
/*
   Elaborado por: Intrasoft
   Autor: Elias Raymundo Torres
 */

require('../fpdf/fpdf.php');
include_once '../apifacturacion/ado/clsEmisor.php';
require_once("../phpqrcode/qrlib.php");
require_once('../apifacturacion/monto_letras.php');

//DATOS DE LA EMPRESA

$objEmisor = new clsEmisor();


$contador = 1;

$emisor = $objEmisor->consultarListaEmisores();
$mostrar = $emisor->fetch(PDO::FETCH_NAMED);


$fecha_hoy = date('Y-m-d');
$ruc = $mostrar['ruc'];
$razon_social = $mostrar['razon_social']; 
$direccion = $mostrar['direccion'];
$pais = $mostrar['pais'];
$departamento = $mostrar['departamento'];
$provincia = $mostrar['provincia'];
$distrito = $mostrar['distrito'];


$fecha_otro = date("d/m/Y", strtotime('2022-08-10'));


define('RUC', $ruc);
define('EMPRESA_COMERCIAL', $razon_social);
define('DIRECCION', $direccion);
//define('LOGO', $resultadoempresa['logo']);
define('UBIGEO', $distrito.'-'.$provincia.'-'.$departamento);



//COMPROBANTE
class PDF extends FPDF
{
    // Cabecera de página

    /*
    function Header()
    {
        
        
        // Arial bold 15
        $this->SetFont('Arial','B',8);
        // Movernos a la derecha
        // $this->Cell(80);
        // Título
        $this->SetXY(70, 15);
        $this->Cell(50,3,utf8_decode(NOMBRE_EMPRESA),0,1,'C');
        $this->SetXY(70, 20);
        $this->Cell(50,3,utf8_decode(DIRECCION),0,1,'C');    
        $this->SetXY(70, 25);
        $this->Cell(50,3,utf8_decode(TEL) ,0,1,'C');
        // Salto de línea
        $this->Ln(20);
        //imagen 
        $this->Image('../'.(LOGO),10,10,-800,-860);
    
    
    } */

    // Pie de página
    /*function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        //color
        $this->SetTextColor(0,0,0);  // Establece el color del texto (en este caso es blanco)
        // Número de página
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }*/
var $widths;
var $aligns;

function SetWidths($w)
{
    //Establecer la matriz de anchos de columna
    $this->widths=$w;
}

function SetAligns($a)
{
    //Establecer la matriz de alineaciones de columnas
    $this->aligns=$a;
}

function Row($data)
{   
    $this->SetDrawColor(255, 255, 255);
    $this->SetMargins(1,0,1);
    //Calcular la altura de la fila
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=3*$nb;
    //Emita un salto de página primero si es necesario
    $this->CheckPageBreak($h);
    //Dibuja las celdas de la fila
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Guardar la posición actual
        $x=$this->GetX();
        $y=$this->GetY();
        //Dibuja el borde
        $this->Rect($x,$y,$w,$h);

        //Imprime el texto
        $this->MultiCell($w,3,$data[$i],0,$a);
        //Pon la posición a la derecha de la celda.
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

  function NbLines($w,$txt)
{
    //Calcula el número de líneas que tomará una MultiCell de ancho w
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

}

$ancho = $mostrar['voucher'];
$pdf = new PDF('P','mm',array($ancho,155+($contador*5)));
$pdf->AliasNbPages();
$pdf->AddPage();
#Establecemos los márgenes izquierda, arriba y derecha:
$pdf->SetMargins(0, 0 , 0);
    /* CABECERA */
    $pdf->SetFont('Times','b',9);
   // $pdf->SetXY(25, 5);
    $pdf->MultiCell($ancho*3/4,4, utf8_decode(EMPRESA_COMERCIAL),0,'C');
    //$pdf->SetXY(25, 10);
  //  $pdf->Cell($ancho,3,"EMPRESA:",0,1,'C');
   // $pdf->SetXY(25, 13);
    $pdf->SetFont('Courier','b',6);
    $pdf->Cell($ancho,3, 'RUC:'.utf8_decode(RUC) ,0,1,'C');
  //  $pdf->SetXY(25, 16);
    $pdf->MultiCell($ancho,3, utf8_decode(DIRECCION) ,0,'C');
   // $pdf->SetXY(25, 19);
    $pdf->Cell($ancho,3,'Ubigeo.:'.utf8_decode(UBIGEO) ,0,1,'C');

    /* INFO DE LA EMPRESA */

    /* DATOS DE LA FACTURA */ 
    
   
   // $pdf->SetXY(1, 28);
    $pdf->SetTextColor(255,255,255);  
    $pdf->Cell($ancho,0.5,'',0,1,'L',true);

    $pdf->SetTextColor(0); 
    $pdf->SetFont('Arial','B',7);
    //$pdf->SetXY(23, 28);
    $pdf->Cell($ancho,4,utf8_decode('BOLETA ELECTRÓNICA'),0,1,'C');
    //$pdf->SetXY(23, 31);
    $pdf->Cell($ancho,4,'B001 - 45',0,1,'C');

   // $pdf->SetXY(1, 38);
    $pdf->SetTextColor(255,255,255);  
    $pdf->Cell($ancho,0.5,'',0,1,'L',true);

    $pdf->SetTextColor(0); 
    $pdf->SetFont('Arial','',7);

   // $pdf->SetXY(1, 37);
    $pdf->Cell($ancho/4,5,'Fecha: ',0,0,'L');
    //$pdf->SetXY(15, 37);
    $pdf->Cell($ancho/4,5,$fecha_otro,0,0,'L');

   // $pdf->SetXY(36, 37);
    $pdf->Cell($ancho/4,5,'Moneda:      SOLES',0,1,'L');

    //$pdf->SetXY(1, 41);
    $pdf->Cell($ancho/4,5,'Ingreso: ',0,0,'L');
    //$pdf->SetXY(15, 41);
    $pdf->Cell($ancho/4,5,'CONTADO',0,0,'L');

    //$pdf->SetXY(36, 41);
    $pdf->Cell($ancho/4,5,'Pago:',0,0,'L');
    //$pdf->SetXY(46, 41);
    $pdf->Cell($ancho/4,5,'EFECTIVO',0,1,'L');

  // verificar si existe cliente

    
        
        $cliente_n_a = 'ELIAS RAYMUNDO TORRES';
        $dni = 42369852;
        $direccion = 'Jr. Huanuco 896';
      $pdf->Cell($ancho/4,3,utf8_decode('Dni/Ruc:'),0,0,'L');
      $pdf->Cell($ancho*3/4,3,utf8_decode($dni),0,1,'L');
      $pdf->Cell($ancho/4,3,utf8_decode('Cliente:'),0,0,'L');
      $pdf->Cell($ancho*3/4,3,utf8_decode($cliente_n_a),0,1,'L');
      $pdf->Cell($ancho/4,3,utf8_decode('Direccion:'),0,0,'L');
      $pdf->Cell($ancho*3/4,3,utf8_decode($direccion),0,1,'L');
     
    $pdf->Ln(3);
   //$pdf->SetXY(1, 54);
    $pdf->SetTextColor(255,255,255);  
    $pdf->Cell($ancho,0.5,'',0,1,'L',true); //linea para iniciar descripcion

    $pdf->Ln();
   // $pdf->SetXY(1, 56);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',6);
    
    $pdf->SetWidths(array($ancho*0.15,$ancho*0.4,$ancho*0.20,$ancho*0.25));
    $pdf->Row(array('C',utf8_decode('DESCRIPCIÓN'),'P.UNIT', 'IMPORTE'));
  
    
    //DETALLES FACTURACIÓN 
    $pdf->Cell($ancho,0.1,'',0,1,'L',true);
     

     
         $descripcion = 'Producto detalles XXXXXXX';
        // $descuento = 0;
       // $igv = $value['monto_impuesto'];
        $cantidad = 1;
        $preciou = 110;
        $total = 110;
        $pdf->setFont('Courier','',7);

        $pdf->SetWidths(array($ancho*0.15,$ancho*0.4,$ancho*0.20,$ancho*0.25));
        $pdf->Row(array($cantidad,utf8_decode($descripcion),$preciou, number_format($total,2,'.',',')));
       
         $pdf->Cell($ancho,0.1,'',0,1,'L',true);   
        
       
     
    
    $gravadas=number_format(0,2,'.',',');
    $igv=number_format(0,2,'.',',');
    $exoneradas=number_format(110,2,'.',',');
    $inafectas=number_format(0,2,'.',',');
    $total = number_format(110,2,'.',',');
    //$pdf->Output();
    $pdf->setFont('Courier','',6);
    
    $pdf->SetWidths(array(47,13));
    $pdf->Row(array($pdf->cell($ancho*0.75,3,utf8_decode('Op Gravadas S/'),0,0,'R'),$pdf->cell($ancho*0.25,3,$gravadas,0,0,'L'))); 

    $pdf->SetWidths(array(47,13));
    $pdf->Row(array($pdf->cell($ancho*0.75,3,utf8_decode('Igv S/'),0,0,'R'),$pdf->cell($ancho*0.25,3,$igv,0,0,'L'))); 
       
    $pdf->SetWidths(array(47,13));
    $pdf->Row(array($pdf->cell($ancho*0.75,3,utf8_decode('Op Exoneradas S/'),0,0,'R'),$pdf->cell($ancho*0.25,3,$exoneradas,0,0,'L')));

    $pdf->SetWidths(array(47,13));
    $pdf->Row(array($pdf->cell($ancho*0.75,3,utf8_decode('Op Inafectas S/'),0,0,'R'),$pdf->cell($ancho*0.25,3,$inafectas,0,0,'L'))); 

    $pdf->SetWidths(array(47,13));
    $pdf->Row(array($pdf->cell($ancho*0.75,3,utf8_decode('TOTAL S/'),0,0,'R'),$pdf->cell($ancho*0.25,3,$total,0,0,'L')));

 
    $pdf->Cell($ancho,0.5,'',0,1,'L',true); // linea despues de la descripcion

     $pdf->setFont('Arial','',6);
    $total_letras = convertir(110);
    $pdf->cell($ancho,3,utf8_decode($total_letras),0,1,'C');
   
    $pdf->cell($ancho,3,utf8_decode('Representación Impresa FACTURA ELECTRÓNICA'),0,1,'C');

    $resumen = 'compruebe en https://intrasoftperu.com/factura/1';
    $pdf->SetFont('Arial','',6);
    $pdf->Cell($ancho,3,$resumen,0,1,'C');

    $pdf->SetFont('Arial','B',7);
    
    $pdf->Cell($ancho,3,'Autorizado ',0,1,'C');
    
    $pdf->SetFont('Arial','',7);
   // $resumen=$verToken['qr'];
    //$pdf->MultiCell($ancho,3,'Resumen: ',0,'L');
     $pdf->Cell($ancho/4,3,'Cajero: ',0,0,'L');
    $pdf->Cell($ancho*3/4,3,'Vendedor ',0,1,'L');
    $pdf->Cell($ancho/4,3,'Hora: ',0,0,'L');
    $pdf->Cell($ancho*3/4,3,date('h:i A'),0,1,'L');


    //$nombrexml = $cliente['nrodoc'].$cliente['tipodoc'].$venta['serie'].$venta['correlativo'].$mostrar['ruc'];//(*) agregado

    //$text_qr = $ruc." | ".$tipo_documento." | ".$serie." | ".$correlativo." | ".$igv." | ".$total." | ".$fecha." | ".$tipodoccliente." | ".$nro_doc_cliente;
    $ruta_qr = 'codigoqr.png';

    //QRcode::png($text_qr, $ruta_qr, 'Q',15, 0);
    
    
    $pdf->Image($ruta_qr,$ancho/2-15,$pdf->GetY(),30,30);
    
   // $pdf->Output('F','reporte.pdf'); // guardar el archivo en pdf en una carpeta
    

$pdf->Output();
?>