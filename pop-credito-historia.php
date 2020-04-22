<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pop-credito-historial.php 
 	Fecha  Creacion : 21/09/2016
	Descripcion  : 
	Copia  archivo  pop-credito-historial.php   parte  del  Proyecto  Pedidos
	Modificado  Fecha  : 
*/
///***Inicio Checamos que el  Usuario  siga  Logeado  
session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}   
///***Fin  Checamos que el  Usuario  siga  Logeado
///****Agregamos librerias
require_once('formato_datos.php');
require_once('Connections/conecta1.php');   
///***Conexion  sap
require_once('conexion_sap/sap.php');
///**Uso de  la Base  de Datos
////mssql_select_db("AGROVERSA");
///****
$id=$_REQUEST['id'];    //Folio del registro

    
$consulta=sprintf("SELECT * FROM encabezado_x_zona WHERE  id=%s",
              GetSQLValueString($id, "int"));
   
   
$sql_consulta=mysqli_query($conecta1,$consulta) or die (mysqli_error($conecta1));     
$datos=  mysqli_fetch_assoc($sql_consulta);


//Funcion para validar si el Pedido se bloquea por credito
 function razonescxc($cardcode,$monto,$dxcx,$tipoventa){
    
      $querys=("SELECT *  FROM saldos_facturas WHERE CardCode='$cardcode'");  //se crea la vista en sql saldos_facturas 02-10-2014
      $tablas = mssql_query($querys);
     
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
       $razon3="dias de credito excedidos, plazo capturado ".$diasc." días cliente".$dato4;
   
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
    
    while ($row2=mssql_fetch_array($tablas)){
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
  
   
    $clavecliente="<p> Cliente : ".$cardcode."</p>";
    $nombrecliente="<p> Nombre Cliente : ".$dato5."</p>";
    $montopedido="<p>Total Pedido: ".number_format($total, 2, '.', ',')."</p>";
    $plazo="<p>Venta a ".$dxcx."  ". $leyendaventa."</p>";
    $saldo="Saldo: ".$saldo_actual."<br>";
    $saldonuevo="Saldo + Pedido: ".number_format($balance, 2, '.', ',')." Crédito :".number_format($dato2, 2, '.', ',')."<br>";
    
     $tablafacturas='<table border="1"> <thead> <tr> <th>Factura:</th><th>Monto Original:</th><th>Saldo:</th><th>Fecha Vence:</th><th>Vencido:</th></tr></thead><tbody>';
     $tablafacturas.=$tabla1;
     $tablafacturas.='</tbody> </table>';
        
     $tablaantiguedad="<p>Antigüedad Saldos días</p>";
     $tablaantiguedad.='<table border="1"> <thead> <tr> <th>1- 30</th><th>31-60</th><th>61-90</th><th>91-120</th><th>+ 120</th></tr></thead><tbody>';
     $tablaantiguedad.=$tabla2;
     $tablaantiguedad.='</tbody> </table>';
    
    return array ($razones,$tablafacturas,$tablaantiguedad,$clavecliente,$nombrecliente,$montopedido,$plazo,$saldo,$saldonuevo);
 }    





function respuesta($r){
    
    switch ($r) {
        case 1:
            $leyenda="Autoriza";

            break;
         case 2:
             $leyenda="Solicita Autorización";

            break;
         case 3:
             $leyenda="No Recomienda";

            break;

        
    }
    
   
    return $leyenda;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Detalle Cartera Vencida</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="select3/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Loading Flat UI -->
    <link href="select3/dist/css/flat-ui.css" rel="stylesheet">
 
    <link href="select2/gh-pages.css" rel="stylesheet">
    <link href="select2/select2.css" rel="stylesheet">
      
      
    <link rel="shortcut icon" href="select3/dist/img/favicon.ico">

    <style>
    body {
        background: none repeat scroll 0% 0% #ffe58b;
		
     }

    </style>

  </head>

  <body onunload="window.opener.location = window.opener.location;">

     <h2><center>Datos del Cliente:  </center></h2>    
     <?php   list($razones,$tablafacturas,$tablaantiguedad,$clavecliente,$nombrecliente,$montopedido,$plazo,$saldo,$saldonuevo)= razonescxc($datos['cve_cte'], $datos['total_mxp'], $datos['plazo'], $datos['tipo_venta']);
             echo $clavecliente;
             echo $nombrecliente;
             echo $montopedido;
             echo $saldo;
             echo $saldonuevo;
             echo $razones;
             echo $tablafacturas;
             echo $tablaantiguedad;?>
     
       <h2><center>Historial de Comentarios  </center></h2>    
     
    <h3>Gestor: <?php echo utf8_encode($datos['nombre_gestor']).", ".respuesta($datos['vbo_gestor']); ?></h3><p><?php echo $datos['comentario_gestor']; ?></p>
    <h3>Gerente Zona: <?php echo utf8_encode($datos['zona']).", ".respuesta($datos['vbo_gerente']); ?></h3><p><?php echo $datos['comentario_gerente']; ?></p>
    <h3>Administración: <?php echo respuesta($datos['vbo_jefecyc']); ?></h3><p><?php echo $datos['comentario_jefecyc']; ?></p>
    <h3>Dir Comercial: <?php echo respuesta($datos['vbo_dircom']); ?></h3><p><?php echo $datos['comentario_dircom']; ?></p>
    <h3>Dir General: <?php echo respuesta($datos['vbo_dirgral']); ?></h3><p><?php echo $datos['comentario_dirgral']; ?></p> 
   
    <input type="button" name="cerrar" value="Cerrar" onclick="window.close()">
 
    
    <script src="select3/dist/js/vendor/jquery.min.js"></script>      
    <script src="select3/dist/js/flat-ui.min.js"></script>        
    <script src="select3/assets/js/application.js"></script>
    
    
    <script src="select2/buscar-cool.js"></script>   
    <script type="text/javascript" src="select2/jquery.min.1.10.2.js"></script>
    <script src="select2/select2.js"></script>   
    <!--<script src="select2/jasny-bootstrap.min.js"></script>-->
  </body>
</html>      


