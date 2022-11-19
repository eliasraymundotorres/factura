<?php 
include_once '../App/clases/Registro.php';


Class ControladorRegistro
{
	public function contrato($datos)
	{
		
		$fi = $datos['fi'];
		$ff = $datos['ff'];
		$fc = $datos['fc'];
		$pm = $datos['pm'];
        $div='';

		$inicio = strtotime($fi);
		$final = strtotime($ff);
		while ($inicio < $final) {
			$mes = date('n/Y',$inicio);
			$op = explode('/',$mes);
			$mesrr = $op[0];
			$aniorr = $op[1];
            $meses = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre');
			$mora = date('Y-m-d',strtotime($this->primDia($mesrr,$aniorr)."+".$fc." days"));
			$div.='
		       <tr>
			 		<td>'.$meses[$mesrr].'</td>
					<td>'.$this->primerDia($mesrr,$aniorr).'</td>
					<td>'.$this->ultimoDia($mesrr,$aniorr).'</td>
					<td><input type="date" class="form-control" value="'.$mora.'"></td>
					<td>S/ '.$pm.'</td>
					<td><button class="btn btn-success">Pagar</button></td>  
			   </tr>	  
			';
			$inicio = strtotime("+1 month", $inicio);
		}

		return $div;

        
	}
	public function primerDia($mes,$anio)
	{
		return date('d/m/Y', mktime(0,0,0, $mes, 1, $anio));
	}
	public function ultimoDia($mes,$anio)
	{
		$day = date("d", mktime(0,0,0, $mes+1, 0, $anio));
 
		return date('d/m/Y', mktime(0,0,0, $mes, $day, $anio));
	}
	public function primDia($mes,$anio)
	{
        return date('Y-m-d', mktime(0,0,0, $mes, 1, $anio));
	}
}


 ?>