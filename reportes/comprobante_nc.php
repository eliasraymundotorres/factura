<?php
require('../fpdf/fpdf.php');
include_once '../apifacturacion/ado/clsEmisor.php';
require_once("../phpqrcode/qrlib.php");
require_once('../apifacturacion/ado/clsVenta.php');
require_once('../apifacturacion/ado/clsEmisor.php');
require_once('../apifacturacion/ado/clsCompartido.php');
require_once('../apifacturacion/monto_letras.php');
require_once('../apifacturacion/ado/clsCliente.php');

$objVenta = new clsVenta();
$objEmisor = new clsEmisor();
$objCompartido = new clsCompartido();

$venta = $objVenta->obtenerComprobanteId($_GET['id']);
$venta = $venta->fetch(PDO::FETCH_NAMED);

$emisor = $objEmisor->obtenerEmisor($venta['idemisor']);
$mostrar = $emisor->fetch(PDO::FETCH_NAMED);

$tipo_comprobante = $objCompartido->obtenerComprobante($venta['tipocomp']);
$tipo_comprobante = $tipo_comprobante->fetch(PDO::FETCH_NAMED);

//(*) agregado ==========
$detalle = $objVenta->listarDetallePorVenta($_GET['id']);//(*) agregado

$Objcliente = new clsCliente();//(*) agregado
$cliente = $Objcliente->consultarClienteId($venta['codcliente']);//(*) agregado
$cliente = $cliente->fetch(PDO::FETCH_NAMED);//(*) agregado
/*
$num_coti = $_GET['valor'];
*/
$color = array(7, 137, 222);

$fecha_hoy = date('Y-m-d');
$ruc = $mostrar['ruc'];
$razon_social = $mostrar['razon_social']; 
$direccion = $mostrar['direccion'];
$pais = $mostrar['pais'];
$departamento = $mostrar['departamento'];
$provincia = $mostrar['provincia'];
$distrito = $mostrar['distrito'];


$fecha_otro = date("d/m/Y", strtotime($venta['fecha_emision']));

define('RAZON_SOCIAL', $razon_social);
define('DIRECCION', $direccion);
define('DEPARTAMENTO', $departamento);
define('PROVINCIA', $provincia);
define('DISTRITO', $distrito);
define('RUC', $ruc);

//==== CLIENTE =====
define('NOMBRE', $cliente['razon_social']);
define('DOCUMENTO', $cliente['nrodoc']);
define('DIREC', $cliente['direccion']);
define('UBIGEO', $cliente['ubigeo']);

//====TIPO DE COMPROBANTE===
define('TIPO_COMPROBANTE', $tipo_comprobante['descripcion']);

//====VENTAS===
define('SERIE', $venta['serie']);
define('CORRELATIVO', $venta['correlativo']);
define('FECHA_EMISION', $fecha_otro);
define('MONEDA', $venta['codmoneda']);
define('IDVENTA', $venta['id']);
define('TIPCOMP', $venta['tipocomp']);

class PDF extends FPDF
{
// Cabecera de página

function Header()
{    
  //PARTE DE LAS IMAGENES 
  $this->Image('../img/logo.png',9,10,-350);

  // PARTE DE LAS DESCRIPCIONES
  $color = array(7, 137, 222);
  
  $this->SetFont('Arial','B',12);  
  $this->SetTextColor($color[0], $color[1], $color[2]);
  $this->SetXY(45, 10);  
  $this->MultiCell(85, 5,utf8_decode(RAZON_SOCIAL),0,'C');
  $this->Ln();
  $this->SetFont('Arial','',7);
  $this->SetTextColor(0);
  /*
  $this->Cell(168, 3,'VENTA DE MADERA',0,1,'C');
  $this->Cell(168, 3,'TORNILLO - CEDRO - COPAIBA - PASHACO - CUMALA',0,1,'C');
  $this->Cell(168, 3,'CASHIMBO - MACHIMBRADO',0,1,'C');
  $this->Cell(168, 3,'TRASLAPADO Y OTROS',0,1,'C');
   */
  $this->Cell(168, 3,utf8_decode(DIRECCION),0,1,'C');
  $this->Cell(168, 3,utf8_decode(DISTRITO.' - '.PROVINCIA.' - '.DEPARTAMENTO),0,1,'C');
  //$this->Cell(168, 3,'RPM: #966260967 / 062-638990',0,1,'C');


  
  $this->SetFillColor(255);  
  $this->SetFont('Arial','B',12);  
  
  $this->SetDrawColor($color[0], $color[1], $color[2]);
  // PARTE DE LA RUC
  $this->RoundedRect(130, 11, 71, 37, 1.5, 'DF');
  $this->SetXY(130, 14);              
  $this->Cell(71, 10,'RUC: '.RUC ,0,1,'C');
  $this->SetXY(130, 23); 
  $this->SetFillColor($color[0], $color[1], $color[2]);
  $this->SetTextColor(255, 255, 255);
  $this->SetFont('Arial','B',10); 
  $this->Cell(71, 10,utf8_decode(TIPO_COMPROBANTE.' DE VENTA ELECTRÓNICA') ,0,1,'C',true);
  $this->SetFillColor(255);  
  $this->SetXY(130, 32);    
  $this->SetTextColor(0);
  $this->SetFont('Arial','B',12);           
  $this->Cell(71, 10,SERIE.'-'.CORRELATIVO,0,1,'C');

  // cuadro para nombre del cliente
  //$this->SetLineWidth(0.1);
  $this->RoundedRect(10, 50, 119, 22, 1.5, 'DF');
  $this->SetXY(10, 50);
  $this->SetFont('Arial','B',7);    
  $this->Cell(25, 5,utf8_decode('CLIENTE'),0,0,'L');
  $this->SetFont('Arial','',7);  
  $this->Cell(50, 5,utf8_decode(': '.NOMBRE),0,1,'L');
  $this->SetFont('Arial','B',7);  
  $this->Cell(25, 5,utf8_decode('DNI/RUC'),0,0,'L');
  $this->SetFont('Arial','',7);  
  $this->Cell(50, 5,utf8_decode(': '.DOCUMENTO),0,1,'L');
  $this->SetFont('Arial','B',7);  
  $this->Cell(25, 5,utf8_decode('DIRECCIÓN'),0,0,'L');
  $this->SetFont('Arial','',7);  
  $this->Cell(50, 5,utf8_decode(': '.DIREC),0,1,'L');
  $this->SetFont('Arial','B',7);  
  $this->Cell(25, 5,utf8_decode('UBIGEO'),0,0,'L');
  $this->SetFont('Arial','',7);  
  $this->Cell(50, 5,utf8_decode(': '.UBIGEO),0,1,'L');

  $this->RoundedRect(130, 50, 71, 22, 1.5, 'DF');
  $this->SetXY(130, 50);
  $this->SetFont('Arial','B',7);    
  $this->Cell(25, 5,utf8_decode('FECHA DE EMISIÓN'),0,0,'L');
  $this->SetFont('Arial','',7);  
  $this->Cell(30, 5,utf8_decode(': '.FECHA_EMISION),0,1,'L');
  $this->SetXY(130, 55);
  $this->SetFont('Arial','B',7);    
  $this->Cell(25, 5,utf8_decode('MONEDA'),0,0,'L');
  $this->SetFont('Arial','',7);  
  $this->Cell(30, 5,utf8_decode(': '.MONEDA),0,1,'L');
  $this->SetXY(130, 60);
  $this->SetFont('Arial','B',7);    
  $this->Cell(25, 5,utf8_decode('COND. PAGO'),0,0,'L');
  $this->SetFont('Arial','',7);  
  $this->Cell(30, 5,utf8_decode(': EFECTIVO'),0,1,'L');
  $this->SetXY(130, 65);
  $this->SetFont('Arial','B',7);    
  $this->Cell(25, 5,utf8_decode('ORD. VENTA'),0,0,'L');
  $this->SetFont('Arial','',7);  
  $this->Cell(30, 5,utf8_decode(': '.IDVENTA.TIPCOMP.CORRELATIVO),0,1,'L');
  
  // detalle de proforma 
  //$this->RoundedRect(10, 78, 190, 8, 1.5, 'DF');
  $this->SetXY(9, 73);
  $this->SetFont('Arial','',9);
  $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
  $this->SetFillColor($color[0], $color[1], $color[2]); // establece el color del fondo de la celda (en este caso es AZUL
  $this->RoundedRect(9, 73, 192, 8, 1.5, 'DF');
  $this->Cell(30,8,'COD.',0,0,'C');
  $this->Cell(10,8,'CANT.',0,0,'L');
  $this->Cell(100,8,'DESCRIPCION',0,0,'L'); 
  $this->Cell(25,8,'P/U',0,0,'C');
  $this->Cell(27,8,'IMPORTE',0,1,'C');
}



// Pie de página
function Footer()
{
  // Posición: a 1,5 cm del final
  $this->SetY(-15);
  // Arial italic 8
  $this->SetFont('Arial','I',8);
  //color
  $this->SetTextColor(0,0,0);  // Establece el color del texto (en este caso es blanco)
  // Número de página
  //$this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
  $this->Cell(0,10,utf8_decode('--- IntrasoftPerú --- '),0,0,'C');
}

/*******************************************************************/

function RoundedRect($x, $y, $w, $h, $r, $style = '')
  {
      $k = $this->k;
      $hp = $this->h;
      if($style=='F')
          $op='f';
      elseif($style=='FD' || $style=='DF')
          $op='B';
      else
          $op='S';
      $MyArc = 4/3 * (sqrt(2) - 1);
      $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
      $xc = $x+$w-$r ;
      $yc = $y+$r;
      $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

      $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
      $xc = $x+$w-$r ;
      $yc = $y+$h-$r;
      $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
      $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
      $xc = $x+$r ;
      $yc = $y+$h-$r;
      $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
      $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
      $xc = $x+$r ;
      $yc = $y+$r;
      $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
      $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
      $this->_out($op);
  }

  function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
  {
      $h = $this->h;
      $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
          $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
  }
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



$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();


$pdf->Ln(2);
$pdf->SetFillColor(255);
$pdf->SetDrawColor($color[0], $color[1], $color[2]);
$pdf->RoundedRect(9, 82, 192, 115, 1.5, 'DF');


//datos mostrados
$n=1;
$precio_tot=0;
$sub_tot_1=0;
$sub_tot=0;

$pdf->SetFont('Arial','',8);  
foreach ($detalle as $k => $value) {
  $pdf->SetTextColor(0,0,0);
/*
  if($n%2==0) {
    $pdf->SetFillColor(243, 243, 243);      
    $pdf->Cell(30,5,$value['item'],0,0,'C',true);
    $pdf->Cell(10,5,$value['cantidad'],0,0,'C',true);  
    $pdf->MultiCell(100,5,utf8_decode($value['descripcion']),0,0,'L',true);
    $pdf->Cell(25,5,number_format($value['valor_unitario'],2,'.',','),0,0,'C',true);
    $pdf->Cell(25,5, number_format($value['valor_total'],2) ,0,1,'R',true);      
  } else {      
    //$pdf->SetFillColor(220, 220, 220);
    $pdf->Cell(30,5,$value['item'],0,0,'C',true);
    $pdf->Cell(10,5,$value['cantidad'],0,0,'C',true);  
    $pdf->MultiCell(100,5,utf8_decode($value['descripcion']),0,'L',true);
    $pdf->SetXY(145,83);
    $pdf->Cell(25,5,number_format($value['valor_unitario'],2,'.',','),0,0,'C',true);
    $pdf->Cell(25,5, number_format($value['valor_total'],2) ,0,1,'R',true);    
  }
  */
  $pdf->SetX(15);
  $pdf->SetWidths(array(30,10,100,25,25));
  $pdf->Row(array($value['item'],$value['cantidad'],utf8_decode($value['descripcion']),number_format($value['valor_unitario'],2,'.',','), number_format($value['valor_total'],2,'.',',')));
       
        // $pdf->Cell($ancho,0.1,'',0,1,'L',true);  
$n++;
}

//mostrar resumen
//------------------CALCULAR MONTO QUE DEBE PAGAR------------------------
$pdf->SetFillColor(255);
$pdf->SetDrawColor($color[0], $color[1], $color[2]);
$pdf->RoundedRect(9, 198, 192, 30, 1.5, 'DF');
$pdf->SetXY(12,202);
$pdf->Cell(30,5,utf8_decode('CUENTA DETRACCIONES:') ,0,1,'L');
$pdf->SetXY(12,206);
$pdf->Cell(30,5,utf8_decode('00-xxx-xxxxx') ,0,1,'L');
//datos de monto totales ===========
// gravadas------
$pdf->SetXY(145,198);
$pdf->Cell(30,5,utf8_decode('Op. Gravadas') ,0,1,'L');
$pdf->SetXY(170,198);
$pdf->Cell(30,5,'S/ '.number_format($venta['op_gravadas'],2,'.',','),0,1,'R');
//------------
// ivg -------
$pdf->SetXY(145,202);
$pdf->Cell(30,5,utf8_decode('IGV') ,0,1,'L');
$pdf->SetXY(170,202);
$pdf->Cell(30,5,'S/ '.number_format($venta['igv'],2,'.',',') ,0,1,'R');
//---------------
// exonerados--------
$pdf->SetXY(145,206);
$pdf->Cell(30,5,utf8_decode('Op. Exonerados') ,0,1,'L');
$pdf->SetXY(170,206);
$pdf->Cell(30,5,'S/ '.number_format($venta['op_exoneradas'],2,'.',',') ,0,1,'R');
//----------------
//Inafectos----------
$pdf->SetXY(145,210);
$pdf->Cell(30,5,utf8_decode('Op. Inafectos') ,0,1,'L');
$pdf->SetXY(170,210);
$pdf->Cell(30,5,'S/ '.number_format($venta['op_inafectas'],2,'.',',') ,0,1,'R');
//---------------
//Descuentos-----------
$pdf->SetXY(145,214);
$pdf->Cell(30,5,utf8_decode('Descuentos') ,0,1,'L');
$pdf->SetXY(170,214);
$pdf->Cell(30,5,utf8_decode('S/ 0.00') ,0,1,'R');
//-----------------
//TOTALES-------------
$pdf->SetXY(145,220);
$pdf->Cell(30,5,utf8_decode('TOTAL') ,0,1,'L');
$pdf->SetXY(170,220);
$pdf->Cell(30,5,'S/ '.number_format($venta['total'],2,'.',',') ,0,1,'R');
//---------------

// monto en letras
$pdf->SetFillColor(255);
//$pdf->SetDrawColor(7, 165, 131);
$pdf->RoundedRect(9, 229, 192, 10, 1.5, 'DF');
$pdf->SetXY(12,232);
$pdf->Cell(30,5,utf8_decode('IMPORTE EN LETRAS:       SON: '.convertir($venta['total'])) ,0,0,'L');

$ruc = $mostrar['ruc'];//(*) agregado
$tipo_documento = $tipo_comprobante['codigo']; //factura
$serie = $venta['serie'];//(*) agregado
$correlativo = $venta['correlativo'];//(*) agregado
$igv = $venta['igv'];//(*) agregado
$total = $venta['total'];//(*) agregado
$fecha = $venta['fecha_emision'];//(*) agregado


$tipodoccliente = $cliente['tipodoc'];//(*) agregado
$nro_doc_cliente = $cliente['nrodoc'];//(*) agregado

$nombrexml = $cliente['nrodoc'].$cliente['tipodoc'].$venta['serie'].$venta['correlativo'].$mostrar['ruc'];//(*) agregado

$text_qr = $ruc." | ".$tipo_documento." | ".$serie." | ".$correlativo." | ".$igv." | ".$total." | ".$fecha." | ".$tipodoccliente." | ".$nro_doc_cliente;
$ruta_qr = '../img/img_qr/'.$nombrexml.'.png';

QRcode::png($text_qr, $ruta_qr, 'Q',15, 0);

// codigo QR
$pdf->SetFillColor(255);
//$pdf->SetDrawColor(7, 165, 131);
$pdf->RoundedRect(9, 240, 49, 38, 1.5, 'DF');
$pdf->Image($ruta_qr,15,241,36,36);
$pdf->RoundedRect(59, 240, 142, 38, 1.5, 'DF');
$pdf->SetXY(100,250);
$pdf->Cell(60,5,utf8_decode('Representación impresa de la FACTURA ELECTRÓNICA') ,0,0,'C');
$pdf->SetXY(100,255);
$pdf->Cell(60,5,utf8_decode('Puede consultar su comprobante en www.intrasoftperu.com') ,0,0,'C');

$pdf->Output();
?>