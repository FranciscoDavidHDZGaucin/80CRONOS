<?php


echo 'LECTURA DE UN ARCHIVO XML<br>';


  
  
  ///////////////////////////////////////////////////////////////////
$xml = simplexml_load_file('CFD_PAGOS/test.xml'); 
$ns = $xml->getNamespaces(true);
$xml->registerXPathNamespace('c', $ns['cfdi']);
$xml->registerXPathNamespace('t', $ns['tfd']);
 
 
//EMPIEZO A LEER LA INFORMACION DEL CFDI E IMPRIMIRLA 
foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){ 
      echo 'Version XMl='.$cfdiComprobante['Version']; 
      echo "<br />"; 
      echo $cfdiComprobante['Fecha']; 
      echo "<br />"; 
      echo $cfdiComprobante['Sello']; 
      echo "<br />"; 
      echo $cfdiComprobante['Total']; 
      echo "<br />"; 
      echo $cfdiComprobante['SubTotal']; 
      echo "<br />"; 
      echo $cfdiComprobante['certificado']; 
      echo "<br />"; 
      echo $cfdiComprobante['MetodoPago']; 
      echo "<br />"; 
      echo $cfdiComprobante['NoCertificado']; 
      echo "<br />"; 
      echo $cfdiComprobante['TipoDeComprobante']; 
      echo "<br />"; 
} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){ 
   echo $Emisor['Rfc']; 
   echo "<br />"; 
   echo $Emisor['Nombre']; 
   echo "<br />"; 
} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal){ 
   echo $DomicilioFiscal['pais']; 
   echo "<br />"; 
   echo $DomicilioFiscal['calle']; 
   echo "<br />"; 
   echo $DomicilioFiscal['estado']; 
   echo "<br />"; 
   echo $DomicilioFiscal['colonia']; 
   echo "<br />"; 
   echo $DomicilioFiscal['municipio']; 
   echo "<br />"; 
   echo $DomicilioFiscal['noExterior']; 
   echo "<br />"; 
   echo $DomicilioFiscal['codigoPostal']; 
   echo "<br />"; 
} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:ExpedidoEn') as $ExpedidoEn){ 
   echo $ExpedidoEn['pais']; 
   echo "<br />"; 
   echo $ExpedidoEn['calle']; 
   echo "<br />"; 
   echo $ExpedidoEn['estado']; 
   echo "<br />"; 
   echo $ExpedidoEn['colonia']; 
   echo "<br />"; 
   echo $ExpedidoEn['noExterior']; 
   echo "<br />"; 
   echo $ExpedidoEn['codigoPostal']; 
   echo "<br />"; 
} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){ 
   echo 'RFC Receptor='.$Receptor['Rfc']; 
   echo "<br />"; 
   echo $Receptor['nombre']; 
   echo "<br />"; 
} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $ReceptorDomicilio){ 
   echo $ReceptorDomicilio['pais']; 
   echo "<br />"; 
   echo $ReceptorDomicilio['calle']; 
   echo "<br />"; 
   echo $ReceptorDomicilio['estado']; 
   echo "<br />"; 
   echo $ReceptorDomicilio['colonia']; 
   echo "<br />"; 
   echo $ReceptorDomicilio['municipio']; 
   echo "<br />"; 
   echo $ReceptorDomicilio['noExterior']; 
   echo "<br />"; 
   echo $ReceptorDomicilio['noInterior']; 
   echo "<br />"; 
   echo $ReceptorDomicilio['codigoPostal']; 
   echo "<br />"; 
} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto){ 
   echo "<br />"; 
   echo $Concepto['unidad']; 
   echo "<br />"; 
   echo $Concepto['importe']; 
   echo "<br />"; 
   echo $Concepto['cantidad']; 
   echo "<br />"; 
   echo $Concepto['descripcion']; 
   echo "<br />"; 
   echo $Concepto['valorUnitario']; 
   echo "<br />";   
   echo "<br />"; 
} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){ 
  /*
   echo $Traslado['TasaOCuota']; 
   echo "<br />"; 
   echo $Traslado['Importe']; 
   echo "<br />"; 
   echo $Traslado['Impuesto']; 
   echo "<br />";   
   echo "<br />"; 
  */ 
   $tasa_iva=$Traslado['TasaOCuota']; 
   $importe_iva=$Traslado['Importe']; 
} 
 echo 'TASA IVA=>'.$tasa_iva.'<br>';
 echo 'IMPORTE IVA=>'.$importe_iva.'<br>';
 
//ESTA ULTIMA PARTE ES LA QUE GENERABA EL ERROR
foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
   echo $tfd['selloCFD']; 
   echo "<br />"; 
   echo 'FECHA TIMBRADO=>'.$tfd['FechaTimbrado']; 
   echo "<br />"; 
   echo 'UUID=>'.$tfd['UUID']; 
   echo "<br />"; 
   echo $tfd['noCertificadoSAT']; 
   echo "<br />"; 
   echo 'VERSION=>'.$tfd['Version']; 
   echo "<br />"; 
   echo 'SELLOSAT=>'.$tfd['SelloSAT']; 
} 
  
?>

