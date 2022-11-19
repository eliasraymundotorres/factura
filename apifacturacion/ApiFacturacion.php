<?php
require_once("signature.php");

class ApiFacturacion{

	public function EnviarComprobanteElectronico($emisor,$nombre,$certificado=''){

		//firma del documento
		$objSignature = new Signature();

		$flg_firma = "0";
		$ruta = $nombre.'.XML';

		//Cliente de sanchez
 		$ruta_firma = $certificado.$emisor['certificado'];
		$pass_firma = $emisor['clave_certificado'];
       
		$resp = $objSignature->signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma);

		//print_r($resp);
		$res = json_encode($resp);

		//Generar el .zip

		$zip = new ZipArchive();

		$nombrezip = $nombre.".ZIP";

		if($zip->open($nombrezip,ZIPARCHIVE::CREATE)===true){
			$zip->addFile($ruta, $ruta);
			$zip->close();
		}
        //Enviamos el archivo a sunat
		 // oficial: https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl
		// prueba: https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl 
       if($emisor['servidor']==1) {
		    $ws = ""; // para envios oficial!!!
	   } else {
		    $ws = "https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl"; // para envios de pruebas!!!
	   }
		
		$ruta_archivo = $nombrezip;
		$nombre_archivo = $nombrezip;
		$ruta_archivo_cdr = "cdr/";

		$contenido_del_zip = base64_encode(file_get_contents($ruta_archivo));


		$xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
		                               xmlns:ser="http://service.sunat.gob.pe" 
									   xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
				 <soapenv:Header>
				 	<wsse:Security>
				 		<wsse:UsernameToken>
				 			<wsse:Username>'.$emisor['ruc'].$emisor['usuario_sol'].'</wsse:Username>
				 			<wsse:Password>'.$emisor['clave_sol'].'</wsse:Password>
				 		</wsse:UsernameToken>
				 	</wsse:Security>
				 </soapenv:Header>
				 <soapenv:Body>
				 	<ser:sendBill>
				 		<fileName>'.$nombre_archivo.'</fileName>
				 		<contentFile>'.$contenido_del_zip.'</contentFile>
				 	</ser:sendBill>
				 </soapenv:Body>
				</soapenv:Envelope>';

			$header = array(
						"Content-type: text/xml; charset=\"utf-8\"",
						"Accept: text/xml",
						"Cache-Control: no-cache",
						"Pragma: no-cache",
						"SOAPAction: ",
						"Content-lenght: ".strlen($xml_envio)
					);


			$ch = curl_init();
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
			curl_setopt($ch,CURLOPT_URL,$ws);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
			curl_setopt($ch,CURLOPT_TIMEOUT,30);
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
			curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
			//para ejecutar los procesos de forma local en windows
			//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
			curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");


			$response = curl_exec($ch);

			$httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
			$estadofe = "0";
			if($httpcode == 200){ //200->La comunicación fue satisfactoria
				$doc = new DOMDocument();
				$doc->loadXML($response);

					if(isset($doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue)){
						$cdr = $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue;
						$cdr = base64_decode($cdr);
						
						file_put_contents("R-".$nombrezip, $cdr);

						$zip = new ZipArchive;
						if($zip->open("R-".$nombrezip)===true){
							$zip->extractTo($ruta_archivo_cdr,'R-'.$nombre.'.XML');
							$zip->close();
						}
						$estadofe ="1";
						$msj = "Enviado correctamente";
						$msj = array('estado'=>$estadofe,'codigo'=>0000,'mensaje'=>$msj);
					}else{		
						$estadofe = "2";
						$codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
						$mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
						//$msj = "error ".$codigo.": ".$mensaje; 
						$msj = array('estado'=>$estadofe,'codigo'=>$codigo,'mensaje'=>$mensaje);
					}		

			}else{ //hay problemas comunicacion
					$estadofe = "3";
					$msj = "Problema de conexión: ".$httpcode.curl_error($ch);
					$msj = array('estado'=>$estadofe,'codigo'=>0001,'mensaje'=>$msj);

			}
          
			curl_close($ch);
       
       return  json_encode($msj);

	}
	

public function EnviarResumenComprobantes($emisor,$nombre, $ruta_firma){

		//firma del documento
		$objSignature = new Signature();

		$flg_firma = "0";
		$ruta = $nombre.'.XML';

		$ruta_firma = $ruta_firma.$emisor['certificado'];
		$pass_firma = $emisor['clave_certificado'];

		$resp = $objSignature->signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma);

		//print_r($resp);


		//Generar el .zip

		$zip = new ZipArchive();

		$nombrezip = $nombre.".ZIP";

		if($zip->open($nombrezip,ZIPARCHIVE::CREATE)===true){
			$zip->addFile($ruta, $ruta);
			$zip->close();
		}


		//Enviamos el archivo a sunat

		if($emisor['servidor']==1) {
		    $ws = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl"; // para envios oficial!!!
	   } else {
		    $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService"; // para envios de pruebas!!!
	   }

		$ruta_archivo = $nombrezip;
		$nombre_archivo = $nombrezip;
		$ruta_archivo_cdr = "cdr/";

		$contenido_del_zip = base64_encode(file_get_contents($ruta_archivo));


		$xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
				 <soapenv:Header>
				 	<wsse:Security>
				 		<wsse:UsernameToken>
				 			<wsse:Username>'.$emisor['ruc'].$emisor['usuario_sol'].'</wsse:Username>
				 			<wsse:Password>'.$emisor['clave_sol'].'</wsse:Password>
				 		</wsse:UsernameToken>
				 	</wsse:Security>
				 </soapenv:Header>
				 <soapenv:Body>
				 	<ser:sendSummary>
				 		<fileName>'.$nombre_archivo.'</fileName>
				 		<contentFile>'.$contenido_del_zip.'</contentFile>
				 	</ser:sendSummary>
				 </soapenv:Body>
				</soapenv:Envelope>';


			$header = array(
						"Content-type: text/xml; charset=\"utf-8\"",
						"Accept: text/xml",
						"Cache-Control: no-cache",
						"Pragma: no-cache",
						"SOAPAction: ",
						"Content-lenght: ".strlen($xml_envio)
					);


			$ch = curl_init();
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
			curl_setopt($ch,CURLOPT_URL,$ws);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
			curl_setopt($ch,CURLOPT_TIMEOUT,30);
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
			curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
			//para ejecutar los procesos de forma local en windows
			//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
			curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");


			$response = curl_exec($ch);

			$httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
			$estadofe = "0";

			$ticket = "0";
			if($httpcode == 200){
				$doc = new DOMDocument();
				$doc->loadXML($response);

				if (isset($doc->getElementsByTagName('ticket')->item(0)->nodeValue)) {
	                $ticket = $doc->getElementsByTagName('ticket')->item(0)->nodeValue;
					//echo "TODO OK : ".$ticket;
				}else{		

					$codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
					$mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
					$ticket = "error ".$codigo.": ".$mensaje; 
				}

			}else{
				echo curl_error($ch);
				$ticket = "error: Problema de conexión";
			}

			curl_close($ch);
			
			return $ticket;
	}


function ConsultarTicket($emisor, $cabecera, $ticket){

		$nombre	= $emisor["ruc"]."-".$cabecera["tipodoc"]."-".$cabecera["serie"]."-".$cabecera["correlativo"];
		$nombre_xml	= $nombre.".XML";

		//===============================================================//
		//FIRMADO DEL cpe CON CERTIFICADO DIGITAL
		$objSignature = new Signature();
		$flg_firma = "0";
		$ruta = $nombre_xml;

		$ruta_firma = $emisor['certificado'];
		$pass_firma = $emisor['clave_certificado'];

		//===============================================================//

		if($emisor['servidor']==1) {
		    $ws = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl"; // para envios oficial!!!
	   } else {
		    $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService"; // para envios de pruebas!!!
	   }

		//ALMACENAR EL ARCHIVO EN UN ZIP
		$zip = new ZipArchive();

		$nombrezip = $nombre.".ZIP";

		if($zip->open($nombrezip,ZIPARCHIVE::CREATE)===true){
			$zip->addFile($ruta, $nombre_xml);
			$zip->close();
		}

		//===============================================================//

		//ENVIAR ZIP A SUNAT
		$ruta_archivo = $nombre;
		$nombre_archivo = $nombre;
		$ruta_archivo_cdr = "";

		$contenido_del_zip = base64_encode(file_get_contents($ruta_archivo.'.ZIP'));
		//FIN ZIP

		$xml_envio = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
        <soapenv:Header>
        <wsse:Security>
        <wsse:UsernameToken>
        <wsse:Username>'.$emisor['ruc'].$emisor['usuario_sol'].'</wsse:Username>
        <wsse:Password>'.$emisor['clave_sol'].'</wsse:Password>
        </wsse:UsernameToken>
        </wsse:Security>
        </soapenv:Header>
        <soapenv:Body>
        <ser:getStatus>
        <ticket>' . $ticket . '</ticket>
        </ser:getStatus>
        </soapenv:Body>
        </soapenv:Envelope>';


		$header = array(
					"Content-type: text/xml; charset=\"utf-8\"",
					"Accept: text/xml",
					"Cache-Control: no-cache",
					"Pragma: no-cache",
					"SOAPAction: ",
					"Content-lenght: ".strlen($xml_envio)
				);


		$ch = curl_init();
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
		curl_setopt($ch,CURLOPT_URL,$ws);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
		curl_setopt($ch,CURLOPT_TIMEOUT,30);
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
		curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
		//para ejecutar los procesos de forma local en windows
		//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
		curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");

		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);

		//echo "codigo:".$httpcode;

		if($httpcode == 200){
			$doc = new DOMDocument();
			$doc->loadXML($response);

			if(isset($doc->getElementsByTagName('content')->item(0)->nodeValue)){
				$cdr = $doc->getElementsByTagName('content')->item(0)->nodeValue;
				$cdr = base64_decode($cdr);
				

				file_put_contents("R-".$nombre_archivo.".ZIP", $cdr);

				$zip = new ZipArchive;
				if($zip->open("R-".$nombre_archivo.".ZIP")===true){
					$zip->extractTo($ruta_archivo_cdr,'R-'.$nombre_archivo.'.XML');
					$zip->close();
				}
				//echo "TODO OK";
				$msj = 'Enviado correctamente';
				$msj = array('estado'=>1,'codigo'=>0,'mensaje'=>$msj);
			}else{		
				$estadofe = '2';
				$codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
				$msj = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
				//echo "error ".$codigo.": ".$mensaje; 
				$msj = array('estado'=>$estadofe,'codigo'=>$codigo,'mensaje'=>$msj);

			}

		}else{
			echo curl_error($ch);
			$estadofe = '3';
			$msj = "Problema de conexión";
			$msj = array('estado'=>$estadofe,'codigo'=>0001,'mensaje'=>$msj);
		}

		curl_close($ch);

	  return  json_encode($msj);
	}

   public function getStatusCDR($emisor,$nombre,$comprobante){

		
		//Enviamos el archivo a sunat
		 // oficial: https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl
		// prueba: https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl 


		if($emisor['servidor']==1) {
		    $ws = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl"; // para envios oficial!!!
	   } else {
		    $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService"; // para envios de pruebas!!!
	   }

		$ruta_archivo_cdr = "cdr/";


		$xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
				 <soapenv:Header>
				 	<wsse:Security>
				 		<wsse:UsernameToken>
				 			<wsse:Username>'.$emisor['ruc'].$emisor['usuario_sol'].'</wsse:Username>
				 			<wsse:Password>'.$emisor['clave_sol'].'</wsse:Password>
				 		</wsse:UsernameToken>
				 	</wsse:Security>
				 </soapenv:Header>
				 <soapenv:Body>
				 	<ser:getStatusCdr> 
						 <rucComprobante>'.$emisor['ruc'].'</rucComprobante>
						 <tipoComprobante>'.$comprobante['tipodoc'].'</tipoComprobante>
						 <serieComprobante>'.$comprobante['serie'].'</serieComprobante>
						 <numeroComprobante>'.$comprobante['correlativo'].'</numeroComprobante>
					 </ser:getStatusCdr> 
				 </soapenv:Body>
				</soapenv:Envelope>';

			$header = array(
						"Content-type: text/xml; charset=\"utf-8\"",
						"Accept: text/xml",
						"Cache-Control: no-cache",
						"Pragma: no-cache",
						"SOAPAction: getStatusCdr",
						"Content-lenght: ".strlen($xml_envio)
					);


			$ch = curl_init();
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
			curl_setopt($ch,CURLOPT_URL,$ws);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
			curl_setopt($ch,CURLOPT_TIMEOUT,30);
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
			curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
			//para ejecutar los procesos de forma local en windows
			//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
			curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");


			$response = curl_exec($ch);

			$httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
			$estadofe = "0";
			if($httpcode == 200){ //200->La comunicación fue satisfactoria
				$doc = new DOMDocument();
				$doc->loadXML($response);

					if(isset($doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue)){
						$cdr = $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue;
						$cdr = base64_decode($cdr);
						
						file_put_contents("R-".$nombrezip, $cdr);

						$zip = new ZipArchive;
						if($zip->open("R-".$nombrezip)===true){
							$zip->extractTo($ruta_archivo_cdr,'R-'.$nombre.'.XML');
							$zip->close();
						}
						$estadofe ="1";
						$msj = "Generado correctamente, Puede descargar el CDR";
						$msj = array('estado'=>$estadofe,'codigo'=>0000,'mensaje'=>$msj);
					}else{		
						$estadofe = "2";
						$codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
						$mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
						//$msj = "error ".$codigo.": ".$mensaje; 
						$msj = array('estado'=>$estadofe,'codigo'=>$codigo,'mensaje'=>$mensaje);
					}		

			}else{ //hay problemas comunicacion
					$estadofe = "3";
					$msj = "Problema de conexión: ".$httpcode.curl_error($ch);
					$msj = array('estado'=>$estadofe,'codigo'=>0001,'mensaje'=>$msj);

			}
          
			curl_close($ch);
       
       return  json_encode($msj);

	}


}
?>