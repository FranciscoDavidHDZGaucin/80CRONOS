<?php

/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :funciones.php  
 	Fecha  Creacion : 
	Descripcion  : 
	    Contiene funciones las cuales nos permiten crear notificaciones al momento de crear un pedido
            algunas funciones son para consultar y el resultado nos devuelve una notificacion 
        
	Modificado  Fecha  : 
 *          11/10/2016   Se  Agregaron las  Funciones  Pertenecientes  al 
 *                          Proyecto Proyecciones  Alias   Pronosticos 
 *                          Funciones  Copiadas.
 *                                   *****function zona($agente)
 *                                   *****function name_mes($nmes) 
*/
require_once('correos_array.php');
require_once('formato_datos.php');
require_once('conexion_sap/sap.php');
///mssql_select_db("AGROVERSA");    
 
//Conexion PDO para que funcione la funcion avisovta
function dbConnect (){
    $conn = null;
    $host = 'localhost';
    $db =   'pedidos';
    $user = 'root';
    $pwd =  'avsa0543';
    try {
        $conn = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pwd);
        //echo 'Connected succesfully.<br>';
    }
    catch (PDOException $e) {
        echo '<p>Cannot connect to database !!</p>';
        exit;
    }
    return $conn;
 }
 ///Correo a quienes no son vendedores
function mail_otros($sujeto){
      
      switch ($sujeto) {
          case "director":
                $correo="lromero@agroversa.com.mx";

              break;

          case "jefecxc":
                $correo="jgarcia@agroversa.com.mx";

          break;
        case "auxcxc":
                $correo="badame@agroversa.com.mx";

              break;
	case "asisdircom":
                $correo="ventasmostrador@agroversa.com.mx";

              break;	  
       case "sistemas":
                $correo="egonzalez@agroversa.com.mx";

              break;	
        case "serviciocte":
                $correo="emena@agroversa.com.mx";

              break;	   
      }
      return $correo;
      
  }
  ///correos fijos
function email($usuario){

    
 switch ($usuario){
     
     case "facforanea":
         $correo="flara@agroversa.com.mx";
       break;
     case "facforanea2":
         $correo="facturacion2@agroversa.com.mx";
       break;
     case "faclocal":
         $correo="facturacion2@agroversa.com.mx";
      break;   
     case "facverur":    
         $correo="flara@verur.com.mx";
     break;
  case "credito":
         $correo="jgarcia@agroversa.com.mx";
       break;
   case "credito_aux":
         $correo="badame@agroversa.com.mx";
       break; 
  case "logistica":
         $correo="auxlogistica@agroversa.com.mx";
       break;   
    case "logistica2":
         $correo="auxlogistica2@agroversa.com.mx"; //Victoria vallejo
       break;   
    case "logistica3":
         $correo="grangel@agroversa.com.mx";   //Maria del Socorro
       break;   
    case "logistica_boss":
         $correo="igarcia@agroversa.com.mx";   //Ismael
       break;   
    case "sistemas":
         $correo="egonzalez@agroversa.com.mx";   //Erik
       break; 
    case "sistemas_qv":
         $correo="rdesantiago@agroversa.com.mx";   //Enrique
       break;   
   case "dircom":
         $correo="lromero@agroversa.com.mx";
       break;   
   case "corlocal":
         $correo="dcisneros@agroversa.com.mx";
       break; 
     case "scliente":   //Servicio al cliente
         $correo="emena@agroversa.com.mx";
       break; 
 }   
    return $correo;
} 
  ///Funcion que nos devuelve el folio siguiente para un pedido nuevo
  //Estructura del Folio    n_agente+anio+ultimo_folio
 function folio_pedido($agente){

   $anioactual = date("Y");
  
   
    
   ///Obtener la cantidad de pedidos que ha generado el agente en el aÃ±o actual
   $productos_string=sprintf("SELECT max(numero) as mayor  from folios_pedidos where agente=%s and anio=%s",
                     GetSQLValueString($agente, "int"),
                     GetSQLValueString($anioactual, "int"));
   
   
 //   $productos_string=("SELECT count(n_remision) as cantidad  from encabeza_pedido where n_agente=151 and year(fecha_alta)=2016");
                   
   
    $conn = dbConnect();  
    // Extract the values from $result
  
  $stmt = $conn->prepare($productos_string);
  $stmt->execute();
  $datos = $stmt->fetch();
  $aleatorio= $datos[0] + 1;  //numero de pedidos + 1 seria el folio siguiente
   
   
   
 
   $foliogenerado=$agente.$anioactual.$aleatorio;   //Agente + aÃ±o + nÃºmero aleatorio
  
  
   return $foliogenerado;
   
    
 }
 
 //Se guarda el folio utilizado 
 function folio_pedido_guardar($agente,$folio,$tipo){
  $nchar_age=strlen($agente);
 $anio = date("Y");   
 $nchar_anio=strlen($anio);
 $sum_char=$nchar_age+$nchar_anio;
 
  $consecutivo=  substr($folio,($sum_char),5);   //Regresa el numero quitando el agente y anio
  
  
   $productos_string=sprintf("insert into folios_pedidos set agente=%s, anio=%s, numero=%s, folio=%s, estatus='A', tipo=%s",
                       GetSQLValueString($agente, "int"),
                       GetSQLValueString($anio, "int"),
                       GetSQLValueString($consecutivo, "int"),
                       GetSQLValueString($folio, "int"),
                       GetSQLValueString($tipo, "int"));
  
  $conn = dbConnect();  
  $stmt = $conn->prepare($productos_string);
  $stmt->execute();
  //$datos = $stmt->fetch();
  return $productos_string;
     
 }
 //Se marca el folio cancelado para que no se vuelva a utilizar
 function folio_pedido_cancelar($folio){
     $productos_string=sprintf("update folios_pedidos set estatus='C' where folio=%s",                   
                       GetSQLValueString($folio, "int"));
  
  $conn = dbConnect();  
  $stmt = $conn->prepare($productos_string);
  $stmt->execute();
  return $productos_string;
 }
 
 function folio_convenio($agente){
    
   
   $anioactual = date("Y");
   //$aleatorio=date("s"); 
 ///Obtener la cantidad de pedidos que ha generado el agente en el aÃ±o actual
   $productos_string=sprintf("SELECT count(n_remision) as cantidad  from encabeza_convenio where n_agente=%s and year(fecha_alta)=%s",
                     GetSQLValueString($agente, "int"),
                     GetSQLValueString($anioactual, "int"));
   
     $conn = dbConnect();  
    // Extract the values from $result
  
  $stmt = $conn->prepare($productos_string);
  $stmt->execute();
  $datos = $stmt->fetch();
   
  
   $aleatorio= $datos[0] + 1;  //numero de pedidos + 1 seria el folio siguiente

   $foliogenerado=$agente.$anioactual.$aleatorio;   //Agente + aÃ±o + nÃºmero aleatorio
  
  
   return $foliogenerado;
   
    
 }
   ///Buscar el correo del gerente se pide el cÃ³digo del Agente 
 
//Obtiene el total del pedido con la moneda original del pedido 
 function total_pedido($remision,$agente,$cliente){
     
     $consulta_sql=sprintf("Select  sum(total_prod) as subtotal from detalle_pedido where n_remision=%s and n_agente=%s and cve_cte=%s",
               GetSQLValueString($remision, 'int'),
                         GetSQLValueString($agente, 'int'),
                         GetSQLValueString($cliente, 'text'));   
     $conn = dbConnect();  
    // Extract the values from $result
  
  $stmt = $conn->prepare($consulta_sql);
  $stmt->execute();
  $datos = $stmt->fetch();
  $total= $datos[0]; //numero de pedidos + 1 seria el folio siguiente
   return $total;
 }
 
 
 //Obtiene el Plazo del Pedido 
 function plazo_pedido($remision,$agente,$cliente){
     
     $consulta_sql=sprintf("Select  plazo from encabeza_pedido where n_remision=%s and n_agente=%s and cve_cte=%s",
               GetSQLValueString($remision, 'int'),
                         GetSQLValueString($agente, 'int'),
                         GetSQLValueString($cliente, 'text'));   
     $conn = dbConnect();  
    // Extract the values from $result
  
  $stmt = $conn->prepare($consulta_sql);
  $stmt->execute();
  $datos = $stmt->fetch();
  $total= $datos[0]; //
   return $total;
 }
 
 function gerente_mail($agente){
    
    $productos_string=sprintf("SELECT * FROM relacion_gerentes WHERE cve_age=%s",
                       GetSQLValueString($agente, "int"));
      
      $conn = dbConnect();
      
    // Extract the values from $result
  
    $stmt = $conn->prepare($productos_string);
  $stmt->execute();
  $datos = $stmt->fetch();
  $correo_jefe= $datos[5];
 
    return $correo_jefe;
   
    
 }
  ///Buscar el correo del Gestor de Credito y Cobranza 12/05/2015
 function gestor_mail($agente){
    
    $productos_string=sprintf("SELECT * FROM relacion_gerentes WHERE cve_age=%s",
                       GetSQLValueString($agente, "int"));
      
      $conn = dbConnect();
      
    // Extract the values from $result
  
    $stmt = $conn->prepare($productos_string);
  $stmt->execute();
  $datos = $stmt->fetch();
  $correo_jefe= $datos[6];
 
    return $correo_jefe;
   
    
 }
 //Revisar si hay existencia del producto en los almacenes del agente resultado 1=SI 0=NO
 //15-09-16 se llama desde pedidos-especial_sigue EAGA
 function revisa_existe_prod($codigo,$agente,$cantidad){
   
   
$string_matrizalmacen=sprintf("SELECT almacen FROM matriz_almacen WHERE cve_gte = %s",
         GetSQLValueString($agente,"int"));
  $conn = dbConnect();
    // Create the query
    $sql =$string_matrizalmacen;
    // Create the query and asign the result to a variable call $result
    $result = $conn->query($sql);
    // Extract the values from $result
    $rows = $result->fetchAll();
    
    $ok=0;
    $total=0;
     foreach ($rows as $row) {  
           $dato1=$row['almacen'];
         
           $string_exisencia=sprintf("SELECT OnHand FROM cronos_existencias WHERE WhsCode = %s AND ItemCode=%s",
                             GetSQLValueString($dato1, "text"),
                              GetSQLValueString($codigo, "text"));  
            $query_existencia=mssql_query($string_exisencia);
            $Fetchstring=  mssql_fetch_assoc($query_existencia);
          $total= $total +$Fetchstring['OnHand'];
          
        
         
     }
    if ($total>=$cantidad){
              $ok=1;   //nos indica que si hay existencia suficiente de este producto en los almacenes que le corresponde al agente
    }
    
return $ok;  //regresa 1 o 0        
 }
 //Notificacion x mail cuando los productos se tratan de vender abajo del precio minimo
 function avisovta($cardcode,$rem,$agente,$tipo_age,$destinatario,$destinatario2){
    
     $productos_string=sprintf("SELECT * FROM detalle_pedido WHERE n_remision=%s and n_agente=%s and cve_cte=%s and estatus='A'",
                             GetSQLValueString($rem, "int"),
                             GetSQLValueString($agente, "int"),
                             GetSQLValueString($cardcode, "text"));
     
   //  ECHO $productos_string;
      $conn = dbConnect();
    // Create the query
    $sql =$productos_string;
    // Create the query and asign the result to a variable call $result
    $result = $conn->query($sql);
    // Extract the values from $result
    $rows = $result->fetchAll();
     
     
     //$productos_query=mysql_query($productos_string,$conecta1) or die (mysql_error());
        
     
      // while ($row=mysql_fetch_array($productos_query)){  //iterar cada producto que se encuentre por autorizar
       foreach ($rows as $row) {   
          $dato1=$row['cve_prod'];
          $dato2=$row['precio_condcto'];
          $dato3=$row['nom_cte'];
          $dato4=$row['nom_prod'];

                              //mandar correo de aviso para enterar a Coordinacion de  Ventas y Direccion Comercial
                             //Mandar aviso por mail
                               $destinatario= $destinatario;    //Agente
                              $destinatario2[0]=  gerente_mail($agente);          //Gerente
                               $destinatario2[1]= 'egonzalez@agroversa.com.mx';  
                          //  $destinatario2= 'erikito1981@gmail.com';
                              $fromname="Pedidos de Ventas"; 
                               $subject="Producto por Autorizar CRONOS";
                                   
                               $mensaje="<p> Remision : ".$rem."</p>";
                               $mensaje.="<p> Agente : ".$_SESSION['usuario_nombre']."</p>";
                               $mensaje.="<p> Cliente : ".$cardcode."</p>";
                               $mensaje.="<p> Nombre Cliente : ".$dato3."</p>";
                               $mensaje.="<p> Producto : ".$dato1." ".$dato4."</p>";                   
                               $mensaje.="<p>Precio Venta: ".number_format($dato2, 2, '.', ',')."</p>";
                               $mensaje.="<p>CRONOS 2016</p>";



                               correos($fromname,$subject,$destinatario,$destinatario2,$mensaje);   //Mandar correo

                              
        
      }
         
 }
 
 
 //obtener el correo electronico del agente, el cual se encuentra en la tabla de SAP  
 function agente_mail($agente){
     
      ///buscar mail del cliente en SAP
            $string=  sprintf("Select U_email from OSLP where SlpCode=%s",
                                    getSQLValueString($agente,"int")); 
            $query=  mssql_query($string);
            $datos=  mssql_fetch_array($query);
            $mail=$datos['U_email'];  //mail actual
            return $mail;
        /////    
 }
 
 //Funcion para notificar al agente de producto no Autorizado  pedidos_autoriza_gerentes.php  el valor a buscar es el id de la tabla detalle_pedido para 
 //estar seguro que producto es el que se esta afectando
 function noautoriza_precio($id_detalle_producto){
     
        $productos_string=sprintf("SELECT n_remision,n_agente, nom_age,cve_cte, nom_cte,cve_prod, nom_prod, precio_condcto FROM relacion_gerentes WHERE cve_age=%s",
                          GetSQLValueString($id_detalle_producto, "int"));

         $conn = dbConnect();

       // Extract the values from $result

       $stmt = $conn->prepare($productos_string);
     $stmt->execute();
     $datos = $stmt->fetch();
     $dato1= $datos[0];  //remision
     $dato2= $datos[1];  //n_agente
     $dato3= $datos[2];  //nom_agente
     $dato4= $datos[3];  //Clave cliente
     $dato5= $datos[4];  //nombre cliente
     $dato6= $datos[5];  //clave producto
     $dato7= $datos[6];  //nombre Producto
     $dato8= $datos[7];  //Precio Venta
     
 
     
                             //Mandar aviso por mail
                                    
                               $destinatario= agente_mail($dato2);    //Agente
                              $destinatario2[0]=  gerente_mail($agente);          //Gerente
                               $destinatario2[1]= 'egonzalez@agroversa.com.mx';  
                          //  $destinatario2= 'erikito1981@gmail.com';
                              $fromname="Pedidos de Ventas"; 
                               $subject="Producto No Autorizado CRONOS";
                                   
                               $mensaje="<p> Remision : ".$dato1."</p>";
                               $mensaje.="<p> Agente : ".$dato3."</p>";
                               $mensaje.="<p> Cliente : ".$dato4."</p>";
                               $mensaje.="<p> Nombre Cliente : ".$dato5."</p>";
                               $mensaje.="<p> Producto : ".$dato6." ".$dato7."</p>";                   
                               $mensaje.="<p>Precio Venta: ".number_format($dato8, 2, '.', ',')."</p>";
                               $mensaje.="<p>CRONOS 2016</p>";



                               correos($fromname,$subject,$destinatario,$destinatario2,$mensaje);   //Mandar correo
     
 }
 
 
 
//Funcion para validar si el Pedido se bloquea por credito y las razones 
 function razonescxc($cardcode,$monto,$dxcx,$tipoventa,$destinatario,$destinatario2){
    
     
     
      $query=("SELECT CardCode, CardName, DocNum, DocDate, DocTotal, PaidToDate, DocTotalFC, PaidSumFC, DocCur, SlpCode, DocDueDate, DocStatus, DATEDIFF(day,DocDueDate,getdate()) as dv  FROM OINV WHERE CardCode='$cardcode' and DocStatus='O' and DATEDIFF(day,DocDueDate,getdate())>0");
     $tabla = mssql_query($query);
     $total_vencido= mssql_num_rows($tabla);
      $query_cliente=("SELECT OCRD.CardCode,OCRD.CardName, OCRD.Balance, OCRD.CreditLine, OCRD.Frozenfor, OCTG.Extradays FROM  OCRD INNER JOIN OCTG ON OCRD.GroupNum=OCTG.GroupNum WHERE OCRD.CardCode='$cardcode'");
     $d_cliente=  mssql_query($query_cliente);
     $datos_cliente=  mssql_fetch_array($d_cliente);
     $dato1=$datos_cliente['Balance'];  //Balance actual
     $dato2=$datos_cliente['CreditLine'];
     $dato3=trim($datos_cliente['Frozenfor']);
     $dato4=$datos_cliente['Extradays'];
     $dato5=$datos_cliente['CardName'];
     
     
   $venta=$tipoventa;
   if ($venta==0){
       $leyendaventa="CONTADO";
   }else{
       $leyendaventa="CREDITO";
   }
   
   $total=$monto;
   $diasc=$dxcx;
   $estatus="E";
   $balance=$dato1+$total;
   $razon="SE PUEDE FACTURAR";
     
   
    if ($venta==0 or $dxcx==0){
       $estatus="C";
       $razon1="Bloqueado por ser venta de Contado";
   
   }
       
   if ($balance>$dato2){
       $estatus="C";
       $razon2="Limite excedido";
   
   }  
   
   if ($diasc>$dato4){
       $estatus="C";
       $razon3="dias de credito excedidos, plazo capturado ".$diasc." dÃ­as cliente Autorizado=".$dato4;
   
   }  
   
   if ($total_vencido){
        $estatus="C";
       $razon4="Facturas Vencidas";
       
   }
   
   if ($dato3=="Y"){
         $estatus="C";
       $razon5="Cliente Bloqueado";
   }
     
   
    $razones=$razon1.", ".$razon2.", ".$razon3.", ".$razon4.", ".$razon5;
    return $razones;
 }    
 //Funcion para validar si el Pedido se bloquea por credito, tambiÃ©n manda correos
 function avisocxc($cardcode,$monto,$dxcx,$tipoventa,$destinatario,$destinatario2,$folio){
    
     $ndestinatario=$destinatario;
     $ndestinatario2=$destinatario2;
     
    //  $query=("SELECT CardCode, CardName, DocNum, DocDate, DocTotal, PaidToDate, DocTotalFC, PaidSumFC, DocCur, SlpCode, DocDueDate, DocStatus, DATEDIFF(day,DocDueDate,getdate()) as dv  FROM OINV WHERE CardCode='$cardcode' and DocStatus='O' and DATEDIFF(day,DocDueDate,getdate())>0");
     $query=("SELECT *  FROM saldos_facturas WHERE CardCode='$cardcode'");  //se crea la vista en sql saldos_facturas 02-10-2014
     $tabla = mssql_query($query);
     $total_vencido= mssql_num_rows($tabla);
      $query_cliente=("SELECT OCRD.CardCode,OCRD.CardName, OCRD.Balance, OCRD.CreditLine, OCRD.Frozenfor, OCTG.Extradays FROM  OCRD INNER JOIN OCTG ON OCRD.GroupNum=OCTG.GroupNum WHERE OCRD.CardCode='$cardcode'");
     $d_cliente=  mssql_query($query_cliente);
     $datos_cliente=  mssql_fetch_array($d_cliente);
     $dato1=$datos_cliente['Balance'];  //Balance actual
     $dato2=$datos_cliente['CreditLine'];
     $dato3=trim($datos_cliente['Frozenfor']);
     $dato4=$datos_cliente['Extradays'];
     $dato5=$datos_cliente['CardName'];
     
     
   $venta=$dxcx;
   if ($venta==0){
       $leyendaventa="CONTADO";
   }else{
       $leyendaventa="CREDITO";
   }
   
   $total=$monto;
   $diasc=$dxcx;
   $estatus="E";
   $balance=$dato1+$total;
   $razon="SE PUEDE FACTURAR";
   
   $saldo_actual=number_format($dato1, 2, '.', ',');  //Total de la factura en formato miles    
     
   
    if ($venta==0){
       $estatus="C";
       $razon1="Bloqueado por ser venta de Contado";
   
   }
       
   if ($balance>$dato2){
       $estatus="C";
       $razon2="Limite excedido";
   
   }  
   
   if ($diasc>$dato4){
       $estatus="C";
       $razon3="dias de credito excedidos, plazo capturado ".$diasc." dÃ­as cliente".$dato4;
   
   }  
   
   if ($total_vencido){
        $estatus="C";
       $razon4="Facturas Vencidas";
       
   }
   
   if ($dato3=="Y"){
         $estatus="C";
       $razon5="Cliente Bloqueado";
   }
   
   
   
    $razones=$razon1.", ".$razon2.", ".$razon3.", ".$razon4.", ".$razon5;
   
   
    $tabla1="";
    $tabla2="";
    $p1=0;
    $p2=0;
    $p3=0;
    $p4=0;
    $p5=0;
    
    while ($row2=mssql_fetch_array($tabla)){
        $reg1=$row2['DocNum'];
        $reg2=$row2['saldo'];
        $reg5=$row2['DocTotal'];  //Monto original de la factura
        $formato_total5=number_format($reg5, 2, '.', ',');  //Total de la factura en formato miles
        $formato_total=number_format($reg2, 2, '.', ',');
        $reg3=date("Y-m-d",strtotime($row2['DocDueDate']));
        $reg4=$row2['dv'];
        
         switch ($reg4) {
          case ($reg4>0 and $reg4<=30):
                        $p1=$reg2+$p1;

                    break;
           case ($reg4>30 and $reg4<=60):
                        $p2=$reg2+$p2;

                    break;      
            case ($reg4>60 and $reg4<=90):
                        $p3=$reg2+$p3;

                    break;      
             case ($reg4>90 and $reg4<=120):
                        $p4=$reg2+$p4;

                    break;          
            case ( $reg4>120):
                        $p5=$reg2+$p5;

                    break;             
                default:
                    break;
            }
         
         $p1f=number_format($p1, 2, '.', ',');  //Total de la factura en formato miles    
         $p2f=number_format($p2, 2, '.', ',');  //Total de la factura en formato miles    
         $p3f=number_format($p3, 2, '.', ',');  //Total de la factura en formato miles    
         $p4f=number_format($p4, 2, '.', ',');  //Total de la factura en formato miles    
         $p5f=number_format($p5, 2, '.', ',');  //Total de la factura en formato miles    
                     
            
            
        $tabla1=$tabla1.' <tr><td>'.$reg1.'</td><td>'.$formato_total5.'</td><td>'.$formato_total.'</td><td>'.$reg3.'</td><td>'.$reg4.'</td></tr>';
        
        
    }
    $tabla2=$tabla2.' <tr><td>'.$p1f.'</td><td>'.$p2f.'</td><td>'.$p3f.'</td><td>'.$p4f.'</td><td>'.$p5f.'</td></tr>';
     $fecha_hoy2=date("Y-m-d H:i:s"); 
   // $destinatario="egonzalez@agroversa.com.mx";
   if ($estatus=="C"){
       //Mandar aviso por mail
       $nfromname="Pedidos de Ventas"; 
        $nsubject="Remision Para Autorizar No.: ".$folio;
        
        $nmensaje="<p> Hora Captura Plataforma : ".$fecha_hoy2."</p>";
        $nmensaje.="<p> Cliente : ".$cardcode."</p>";
        $nmensaje.="<p> Nombre Cliente : ".$dato5."</p>";
        $nmensaje.="<p>Total Pedido: ".number_format($total, 2, '.', ',')."</p>";
        $nmensaje.="<p>Venta a ".$dxcx."  ". $leyendaventa."</p>";
        $nmensaje.="Saldo: ".$saldo_actual."<br>";
        $nmensaje.="Saldo + Pedido: ".number_format($balance, 2, '.', ',')." CrÃ©dito :".number_format($dato2, 2, '.', ',')."<br>";
        $nmensaje.="<strong>".$razon1.", ".$razon2.", ".$razon3.", ".$razon4.", ".$razon5."</strong>";
        $nmensaje.="<p>Facturas Vencidas:</p>";
        $nmensaje.='<table border="1"> <thead> <tr> <th>Factura:</th><th>Monto Original:</th><th>Saldo:</th><th>Fecha Vence:</th><th>Vencido:</th></tr></thead><tbody>';
        $nmensaje.=$tabla1;
        $nmensaje.='</tbody> </table>';
        
        $nmensaje.="<p>AntigÃ¼edad Saldos dÃ­as</p>";
        $nmensaje.='<table border="1"> <thead> <tr> <th>1- 30</th><th>31-60</th><th>61-90</th><th>91-120</th><th>+ 120</th></tr></thead><tbody>';
        $nmensaje.=$tabla2;
        $nmensaje.='</tbody> </table>';
        
        
        $nmensaje.="<p>Cronos 2016</p>";

        correos($nfromname,$nsubject,$destinatario,$destinatario2,$nmensaje);   //Mandar correo
        
        
        
        
        //////////////--------------------------------------Notificar el cliente del estatus de su pedido 23-07-2015
         //  $cve_cte='C001795';
        ///buscar mail del cliente en SAP
            $stringmail_cte=  sprintf("Select E_Mail from OCRD where CardCode=%s",
                                    getSQLValueString($cardcode,"text")); 
            $querymail_cte=  mssql_query($stringmail_cte);
            $datos_mailcliente=  mssql_fetch_array($querymail_cte);
            $mail_cliente=$datos_mailcliente['E_Mail'];  //mail actual
        /////    
                    $usuario2="scliente";
                    $destinatarioc=$mail_cliente;
                    $usuario="sistemas";
                    $destinatario2c[0]=email($usuario);
                    $destinatario2c[1]=email($usuario2);

                     $fromnamec="Pedido Recibido"; 
                     $subjectc="Pedido No.: ".$_SESSION['remision'];
                     $mensaje2c="<p>Apreciable Cliente:  ".$dato5."</p>";
                     $mensaje2c.="<p>Su pedido  se ha recibido</p>";
                     $mensaje2c.="<p>RemisiÃ³n No. ".$_SESSION['remision']." </p>";
                    
                     $mensaje2c.="<p>Estatus: En RevisiÃ³n </p>";
                     $mensaje2c.="<p>Favor de contactar al Representante de Ventas Gracias</p>";
                     
                     if ($cardcode=="C001795"){   //Cliente piloto  07-08-2015
                          correos($fromnamec,$subjectc,$destinatarioc,$destinatario2c,$mensaje2c);   //Mandar correo
                     }
                   
        ///--------------------------------------------------------------
       
   }

    
     
         return $estatus;
        
     
 }
 //notificar que existe al moemnto de crear un pedido nuevo
function notifica_pedidonuevo($folio,$cliente,$nom_cte,$agente,$nom_agente,$monedabien,$total_pedido,$plazo,$mail_agente){
      $today=date("Y-m-d H:i:s"); 
    	
        $productos_string=sprintf("SELECT * FROM detalle_pedido WHERE n_remision=%s and n_agente=%s and cve_cte=%s",
                             GetSQLValueString($folio, "int"),
                             GetSQLValueString($agente, "int"),
                             GetSQLValueString($cliente, "text"));
     
   //  ECHO $productos_string;
      $conn = dbConnect();
    // Create the query
    $sql =$productos_string;
    // Create the query and asign the result to a variable call $result
    $result = $conn->query($sql);
    // Extract the values from $result
    $rows = $result->fetchAll();
     
     
     //$productos_query=mysql_query($productos_string,$conecta1) or die (mysql_error());
        
     
      // while ($row=mysql_fetch_array($productos_query)){  //iterar cada producto que se encuentre por autorizar
       foreach ($rows as $row) {   
          $dato1=$row['cve_prod'];
          $dato2=$row['nom_prod'];
          $dato3=$row['cant_prod'];
          $dato4=$row['precio_prod'];
          $dato5=$row['dcto_prod'];
          $dato6=$row['ieps'];
          $dato7=$row['iva'];
          $dato8=$row['total_prod'];
         
         
          
          $tabla1=$tabla1.' <tr><td>'.$dato1.'</td><td>'.$dato2.'</td><td>'.$dato3.'</td><td>'.$dato4.'</td><td>'.$dato5.'</td><td>'.$dato6.'</td><td>'.$dato7.'</td><td>'.number_format($dato8, 2, '.', ',').'</td></tr>';
          
       }    
      
    //$destinatario_notifica="erikito1981@gmail.com";    //agente
    $destinatario_notifica=$mail_agente;    //agente
     $destinatario2_notifica[0]=email("facforanea");
     $destinatario2_notifica[1]=email("faclocal");
     $destinatario2_notifica[2]=gerente_mail($agente);   //mail del gerente
       $destinatario2_notifica[3]="bmesta@agroversa.com.mx";   //mail planeacion de la demanda  Brenda mesta
        $destinatario2_notifica[4]="egonzalez@agroversa.com.mx";   //mail planeacion de la demanda  Brenda mesta
    $fromname="Pedidos de Ventas CRONOS"; 
    $subject="Remision Creada No.: ".$folio;
    $mensaje="<p> Hora Captura Plataforma : ".$today."</p>";    
    $mensaje.="<p> Cliente : ".$cliente."</p>";
    $mensaje.="<p> Nombre Cliente : ".$nom_cte."</p>";
    $mensaje.="<p> Agente : ".$nom_agente."</p>";
    $mensaje.="<p> Moneda : ".$monedabien."</p>";
    $mensaje.="<p> Monto Total del Pedido : $".number_format($total_pedido, 2, '.', ',')."</p>";
    $mensaje.="<p> Plazo : ".$plazo."</p>";
        $mensaje.='<table border="1"> <thead> <tr> <th>Clave</th><th>Producto</th><th>Cantidad</th><th>Precio</th><th>%Descuento</th><th>%IEPS</th><th>%IVA</th><th>Total</th></tr></thead><tbody>';
        $mensaje.=$tabla1;
        $mensaje.='</tbody> </table>';
    $mensaje.="<p>CRONOS 2016</p>";

          
    
    
    correos($fromname,$subject,$destinatario_notifica,$destinatario2_notifica,$mensaje);   //Mandar correo
    
    
}
//funcion que revisa si un pedido ya se ha facturado alguno de sus productos
function revisa_pedido_xprod_surtido($remision,$agente,$cliente){
    
   ///Obtener la cantidad de pedidos que ha generado el agente en el aÃ±o actual
   $productos_string=sprintf("SELECT cant_prod, cant_falta, n_factura from detalle_pedido where n_remision=%s and n_agente=%s and cve_cte=%s",
                     GetSQLValueString($remision, "int"),
                     GetSQLValueString($agente, "int"),
                     GetSQLValueString($cliente, "text"));
   
   
    $conn = dbConnect();  
    // Extract the values from $result
    // Create the query
    $sql =$productos_string;
    // Create the query and asign the result to a variable call $result
    $result = $conn->query($sql);
    // Extract the values from $result
    $rows = $result->fetchAll();
    $centinela=1;
     foreach ($rows as $row) {  
          $cantidad=$row['cant_prod'];
          $pendiente=$row['cant_falta'];
          $factura=$row['n_factura'];
          
          if($cantidad<>$pendiente){
              //ya se factora cantidad de ese producto
              $centinela=0;
          }
         
     }
    
        //si el valor es 1 nos indica que no se ha facturado nada de los productos 0 si existe algo facturado
    return $centinela;
}




function revisaincremento6($zona,$plazo){
    ///si el resultado el 0 no puede cambiar el incrementa al 6 % 
    if($plazo==0){
        $centinela=0;
    }else{
        switch ($zona) {
            case 'VERUR':  //Verur

                    $centinela=0;
                break;
            case 'LOCAL':  //Local

                    $centinela=0;
                break; 
             case 'MAQUILA':  //maquila

                    $centinela=0;
                break; 

            default:
                $centinela=1;
                break;
        }
        
    }
    return $centinela;
}
////***********

///funcion que nos indica si aplica la restriccion de cantidad solo en cajaa completas  EAGA 18-10-2016

    function revisa_solocajacerrada($zona){
    ///si el resultado el 0=no aplica caja cerrada
     ///1=Si aplica caja cerrada   
   
        switch ($zona) {
            case 'VERUR':  //Verur

                    $centinela=0;
                break;
            case 'LOCAL':  //Local

                    $centinela=0;
                break; 
             case 'MAQUILA':  //maquila

                    $centinela=0;
                break; 

            default:
                $centinela=1;
                break;
        
        
    }
    return $centinela;
}


//



///esta funcion aplica para especificar la zona en el campo de tipo_agente de la tabla encabeza_pedido y se utiliza en elarchivos pedidos-especial_sigue.php
///25-10-2016 EAGA   1=Local  2=Foraneo 3=VERUR, Maquila

    function revisa_zona($zona){
   
     
   
        switch ($zona) {
            case 'VERUR':  //Verur

                    $centinela=3;
                break;
            case 'LOCAL':  //Local

                    $centinela=1;
                break; 
             case 'MAQUILA':  //maquila

                    $centinela=3;
                break; 

            default:
                $centinela=2;
                break;
        
        
    }
    return $centinela;
}


//

///************* Inicio Funciones  Copia  Proyecto  Proyeccion**************************************
 ///Buscar el correo del gerente se pide el cÃ³digo del Agente 
 function zona($agente){
    
    try
    {
      $conn = dbConnect();
     
    // Extract the values from $result
  
    $stmt = $conn->prepare('SELECT * FROM relacion_gerentes WHERE cve_age=:agente');
  $stmt->execute(array(':agente'=>$agente));
  $datos = $stmt->fetch();
  $namezona= $datos[3];
   return $namezona;
    }
    catch(PDOException $e){
        // echo '<p>Error!!</p>';
    }
   
   
    
 }
 
function name_mes($nmes) 
{
 

  
	 switch ($nmes)
	{
	 case 1:
			$nombremes1="ENERO";
			
			
			break;
	 case 2:
			$nombremes1="FEBRERO";
			
			break;
	 case 3:
			$nombremes1="MARZO";
			
			break;
	 case 4:
			$nombremes1="ABRIL";
			
			break;
	case 5:
			$nombremes1="MAYO";
			
			break;		
	case 6:
			$nombremes1="JUNIO";
			
			break;
	case 7:
			$nombremes1="JULIO";
			
			break;
	case 8:
			$nombremes1="AGOSTO";
			
			break;
	case 9:
			$nombremes1="SEPTIEMBRE";
			break;
	case 10:
			$nombremes1="OCTUBRE";
			break;
	case 11:
			$nombremes1="NOVIEMBRE";
			break;
	case 12:
			$nombremes1="DICIEMBRE";
			break;
		

	}

  return $nombremes1;

}

////Funcion para validar si se puede capturar la proyeccion
///Esta funcion tambien existe en el archivo funciones_proyecciones
 function modificar_proyeccion($usuario_proyeccion){
      
      
           $string1=("SELECT capturar, capturarg from configurar limit 1");
                      
      
    $conn = dbConnect();
    
    $stmt = $conn->prepare($string1);
    $stmt->execute();
    $datos = $stmt->fetch();
    $valor= $datos[0];  //Permiso para el Agente
    $valor2= $datos[1];  //Permiso para el Gerente
      
      
    switch ($usuario_proyeccion) {
        case 101:
                 ///Validar para el agente
            if($valor==1){
                //permite accesar
                $accesar=1;
            }else{
                //No permite accesar
                 $accesar=0;
            }
            
            break;

        default:
             ///Validar para el Gerente
                ///Validar para el agente
            if($valor2==1){
                //permite accesar
                $accesar=1;
            }else{
                //No permite accesar
                 $accesar=0;
            }
            
            
            break;
    }
    
    
    return $accesar;
    
  }
//****************************************************************************************************

  
   ///Buscar el standar pack del producto 03-11-2016
 function unidadesxempaque($codigo){
    
    $productos_string=sprintf("select unitxempaque from envios_det_productos_rf where cve_prod=%s order by id asc limit 1",
                       GetSQLValueString($codigo, "int"));
      
      $conn = dbConnect();
      
    // Extract the values from $result
  
    $stmt = $conn->prepare($productos_string);
  $stmt->execute();
  $datos = $stmt->fetch();
  $dato_query= $datos[0];
 
    return $dato_query;
   
    
 }
  
 
 //buscar convenio y obtener los datos por producto  07-11-2016 EAGA
 function convenio_detalle($n_agente,$cve_cte,$cve_prod){
     $string=  sprintf("select precio_representante, moneda_prod, boni_estado, boni_precioporunidad, boni_cantidadporunidad, boni_productoid, boni_precioventa, id_detalle from detalle_convenio where n_agente=%s and cve_cte=%s and cve_prod=%s and estatus='E'",
              
               GetSQLValueString($n_agente, "int"),
               GetSQLValueString($cve_cte, "text"),
               GetSQLValueString($cve_prod, "text"));
     
      $conn = dbConnect();
      
    // Extract the values from $result
  
    $stmt = $conn->prepare($string);
  $stmt->execute();
  $datos = $stmt->fetch();
  $precio_vta= $datos[0]; 
   $moneda= $datos[1]; 
   $boni_estado= $datos[2]; 
   $boni_precioporunidad= $datos[3]; 
   $boni_cantidadporunidad= $datos[4]; 
   $boni_productoid= $datos[5]; 
   $boni_precioventa= $datos[6]; 
   $boni_iddetalle= $datos[7]; 
  
  
   if (is_null($precio_vta)){
       $acceso=0; //No existe convenio para esta consulta
       return array($acceso);
   }else{
          $acceso=1; //existe convenio para esta consulta
       return array($acceso,$precio_vta,$moneda,$boni_estado,$boni_precioporunidad,$boni_cantidadporunidad,$boni_productoid,$boni_precioventa,$boni_iddetalle);
   }
            
     
  //   return $string;
     
 }
 
 function  nombre_producto($cve_prod){
     
      $string=  sprintf("select nom_prod from detalle_pedido  where cve_prod=%s order by fecha_alta desc  limit 1",
               GetSQLValueString($cve_prod, "text"));
     
      $conn = dbConnect();
      
    // Extract the values from $result
  
    $stmt = $conn->prepare($string);
  $stmt->execute();
  $datos = $stmt->fetch();
  $resultado= $datos[0]; 
     
     return $resultado;
 }

 
 function estados_mexico($id_estado){
      $string=  sprintf("select nom_ent from estados_mexico  where id=%s ",
               GetSQLValueString($id_estado, "int"));
     
      $conn = dbConnect();
      
    // Extract the values from $result
  
    $stmt = $conn->prepare($string);
  $stmt->execute();
  $datos = $stmt->fetch();
  $resultado= $datos[0]; 
     
     return $resultado;
     
     
 }
 
 function dir_entregas($id_entrega){
      $string=  sprintf("select calle,colonia,ciudad,cp,estado,pais from dir_entregas  where id=%s ",
               GetSQLValueString($id_entrega, "int"));
     
      $conn = dbConnect();
      
    // Extract the values from $result
  
    $stmt = $conn->prepare($string);
  $stmt->execute();
  $datos = $stmt->fetch();
  $calle= $datos[0]; 
   $colonia= $datos[1]; 
    $ciudad= $datos[2]; 
     $cp= $datos[3]; 
      $estado= estados_mexico($datos[4]); 
      $pais= $datos[5];
     
   return array($calle,$colonia,$ciudad,$cp,$estado,$pais);
     
     
 }
 
 
 function indica_entrega($id_indica){
     
     switch ($id_indica) {
         case 1:
             $resultado="Cliente Recoge";

             break;
          case 2:
             $resultado="Agente Entrega";

             break;

         default:
                $resultado="Logistica";
             break;
     }
     
     return $resultado;
 }
  
 
 function nombre_almacen($almacen){
      $string=  sprintf("select nombre_alma from vista_nombrealmacen  where almacen=%s ",
               GetSQLValueString($almacen, "text"));
     
      $conn = dbConnect();
      
    // Extract the values from $result
  
    $stmt = $conn->prepare($string);
  $stmt->execute();
  $datos = $stmt->fetch();
  $resultado= $datos[0]; 
     
     return $resultado;
     
 }
 
  ///obtencion del CM= Costo MArginal del producto
 function cmg($producto){
      $string=  sprintf("select costo from costos  where cve_articulos=%s ",
               GetSQLValueString($producto, "text"));
     
      $conn = dbConnect();
      
    // Extract the values from $result
  
    $stmt = $conn->prepare($string);
  $stmt->execute();
  $datos = $stmt->fetch();
  $resultado= $datos[0]; 
     
     return $resultado;
     
 }
 
 
 //Se guarda el folio utilizado 
 function entrada($usuario,$ip){
  $usu=$usuario;
       $fecha_hoy2=date("Y-m-d H:i:s");
       $tipo="E";
       $getip=$ip;        
       $cronos=1;
       $insertSQL = sprintf("INSERT INTO log_in_out (usuario, fecha_hora,tipo,ip,cronos) VALUES(%s,%s,%s,%s,%s)",
                     GetSQLValueString($usu, "text"),
                     GetSQLValueString($fecha_hoy2, "date"),
                     GetSQLValueString($tipo, "text"),
		  GetSQLValueString($getip, "text"),
                  GetSQLValueString($cronos, "int"));
     
     
     
  
  $conn = dbConnect();  
  $stmt = $conn->prepare($insertSQL);
  $stmt->execute();
   
   
  //$datos = $stmt->fetch();
  return $insertSQL;
     
 }
 
 //facturas creadas 
 function exsitencia_comprometida_entregas($producto,$almacen){
       $productos_string=sprintf("SELECT sum(cant_prod) as total  from logistica_entregas where cve_prod=%s and whscode=%s and isnull(n_factura)",
                       GetSQLValueString($producto, "text"),
                       GetSQLValueString($almacen, "text"));
        $conn = dbConnect();
    // Extract the values from $result
      $stmt = $conn->prepare($productos_string);
      $stmt->execute();
      $datos = $stmt->fetch();
      $dato= $datos[0];
     return $dato;
  }





 
 //funcion que revisa si un pedido ya se ha facturado alguno de sus productos para revisar si el estatus  es "Emitida", "Facturada" o "Facturada Parcial"
function revisa_pedido_xprod($remision,$agente,$cliente){
    
   ///Obtener la cantidad de pedidos que ha generado el agente en el aÃ±o actual
   $productos_string=sprintf("SELECT cant_prod, cant_falta, n_factura from detalle_pedido where n_remision=%s and n_agente=%s and cve_cte=%s",
                     GetSQLValueString($remision, "int"),
                     GetSQLValueString($agente, "int"),
                     GetSQLValueString($cliente, "text"));
   
   
    $conn = dbConnect();  
    // Extract the values from $result
    // Create the query
    $sql =$productos_string;
    // Create the query and asign the result to a variable call $result
    $result = $conn->query($sql);
    // Extract the values from $result
    $rows = $result->fetchAll();
    $emitida=1;  //Variable que indica que no se ha facturado ningun producto
    $facturada=1;
     foreach ($rows as $row) {  
          $cantidad=$row['cant_prod'];
          $pendiente=$row['cant_falta'];
          $factura=$row['n_factura'];
          
         /// if($cantidad<>$pendiente){
          if($pendiente<$cantidad){
              //ya se factora cantidad de ese producto
              $emitida=0;
              
              if ($pendiente>0){
                  $facturada=0;/// si esto es 0 nos indica que hay cantidad pendiente por surtir Facturada Parcial
                 
              }else{
                  
              }
              
          }else{
              if ($pendiente==$cantidad){
                   $facturada=0;/// si esto es 0 nos indica que hay cantidad pendiente por surtir Facturada Parcial
              }
          }
          
         
     }
    
     if ($facturada==0){
          $leyenda="Facturada Parcial";
     }else{
         $leyenda="Facturada";
     }
     
     if ($emitida==1){
         $leyenda="Emitida";
     }
         
     
     
        //si el valor es 1 nos indica que no se ha facturado nada de los productos 0 si existe algo facturado
    return $leyenda;
}
 

 
?>