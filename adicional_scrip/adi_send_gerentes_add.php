<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_send_gerentes_add.php 
 	Fecha  Creacion : 17/03/2017  
	Descripcion  :  Escrip creado para Enviar Correo  al Planeador al momento  que  cree un nuevo pedido  adicional 
 * 
  */
///***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
////Agregamos  Modulo Correos
require_once('../correos.php');
////*****
////***Conexion   Sap 
require_once('../conexion_sap/sap.php');
///***Seleccion de la Bd 
 ///mssql_select_db("AGROVERSA");
 //obtener el correo electronico del agente, el cual se encuentra en la tabla de SAP  
 function agente_mail($agente){
        
         $result = array();
      ///buscar mail del cliente en SAP
            $string=  sprintf("Select U_email,Memo from OSLP where SlpCode=%s",
                                    getSQLValueString($agente,"int")); 
            $query=  mssql_query($string);
            $datos=  mssql_fetch_array($query);
            $result['mail_agente']=$datos['U_email'];  //mail actual
            $result['nombre_agente']=$datos['Memo'];
            return $result;
        /////    
 }
 ///****************************************************************************
///***Obtenemos el  correo y nombre  de  Agente 
$elementos_Agente = agente_mail($fetch_adi['cve_usuario']);
   ///***Obtenemos el  Correo  Agente 
   $nom_agente= $elementos_Agente['nombre_agente'];
   ///***Obtenemos el correo 
   $correo_agente =  $elementos_Agente['mail_agente'];
/////****************************************************************
 
////***Obtenemos los datos  
$nombre_usuario = filter_input(INPUT_POST, 'nomUsu');
$tipo_usuario = filter_input(INPUT_POST, 'type_usu');
$codigo_producto = filter_input(INPUT_POST, 'cdg_pro');
$nomb_producto = filter_input(INPUT_POST, 'nomPro');
$fec_sol = filter_input(INPUT_POST, 'fec_sol');
$fec_rq = filter_input(INPUT_POST, 'fec_rq');
$pre_solPV = filter_input(INPUT_POST, 'pre_solPV');
$vet = filter_input(INPUT_POST, 'vet');
$can_rq = filter_input(INPUT_POST, 'can_rq');
$almacen = filter_input(INPUT_POST, 'almacen');
$invt= filter_input(INPUT_POST, 'invt');
$proycc= filter_input(INPUT_POST, 'proycc');
$num_usuario = filter_input(INPUT_POST, 'Num_USU');
$vtotals_por_Bo = filter_input(INPUT_POST, 'VTS_RTES_BODEGA');
$proyecc_totla_BO =  filter_input(INPUT_POST, 'PROYEC_BOD_TLS');

////****Obtenemos el  correo  del  planeador 
$strin_planeador=  "SELECT  email FROM pedidos.usuarios_locales  where  rol =100";
////** Realizamos  Qery 
$qery_planeador = mysqli_query($conecta1, $strin_planeador);
////**Fetch  
$fetch_planeador = mysqli_fetch_array($qery_planeador);
///***Obtenemos el  mail del  correo 
$mail_planeador = $fetch_planeador['email'];

////*****Informacion a Enviar al  Gerente
$STRING_CADENA_Html_001 = "<table><thead><th><h4>Buen Día el : ".$nombre_usuario." a realizado una  Solicitud de Pedido Adicional.</h4></th><thead>";        
$STRING_CADENA_Html_002 = "<tbody>";
$STRING_CADENA_Html_003 = "<tr><td>N# Almacen:".$almacen."</td></tr>";
$STRING_CADENA_Html_004 = "<tr><td>Codigo Producto: ".$codigo_producto."</td><td>Nombre Producto: ".$nomb_producto."</td></tr>";
$STRING_CADENA_Html_005 = "<tr><td>Fecha Solicitud: ".$fec_sol."</td><td>Fecha Requerimiento:".$fec_rq."</td></tr>"; 
$STRING_CADENA_Html_006= "<tr><td>Precio Solicitado Por Venta :".$pre_solPV."</td><td>Cantidad Requerida :".$can_rq."</td></tr>"; 
$TRING_CADENA_Html_007 = "</tbody></table>";
/////****
//******************        
////***Armamos la informacion Html  a Enviar
$strin_Fina_HTML =$STRING_CADENA_Html_001.$STRING_CADENA_Html_002 .$STRING_CADENA_Html_003.$STRING_CADENA_Html_004.$STRING_CADENA_Html_005.$STRING_CADENA_Html_006.$TRING_CADENA_Html_007;

////****Mandamos bmesta@agroversa.com.mx
correos("Solicitud de Adicional", "Pedido Adicional Gerente",$mail_planeador,"",$strin_Fina_HTML );

?>
