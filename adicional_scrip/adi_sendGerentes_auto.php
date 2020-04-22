<?php
//////*****adi_sendGerentes_auto.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :adi_sendGerentes_auto.php  
 	Fecha  Creacion : 17/03/2017
	Descripcion  :
 *             Escript  para  mandar correo  cuando 
 *             el gerente   autoriza un pedido  
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
 //mssql_select_db("AGROVERSA");
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

///*****Obtenemos  ID 
$id_adi  = filter_input(INPUT_POST, 'cve_adicional');
////****Generamos  Cade para obtener el adicional 
$string_adi  = sprintf("Select * from pedidos.adicionales   where  id = %s",
 GetSQLValueString($id_adi, "int"));
////***Qery  para  obtener el  pedido  adicional 
$qeryAdi  = mysqli_query($conecta1,$string_adi);
////***Obtenemos  el  Fetch**************************************************
$fetch_adi = mysqli_fetch_array($qeryAdi); 


///***Obtenemos el  correo y nombre  de  Agente 
$elementos_Agente = agente_mail($fetch_adi['cve_usuario']);
   ///***Obtenemos el  Correo  Agente 
   $nom_agente= $elementos_Agente['nombre_agente'];
   ///***Obtenemos el correo 
   $correo_agente =  $elementos_Agente['mail_agente'];
/////****************************************************************
//***Obtenemos el  correo  del  Gerente   
////*****Cadena de  Consulta
$string_mail_agente = sprintf("SELECT  zona  FROM pedidos.relacion_gerentes where cve_age = %s",
 GetSQLValueString($fetch_adi['cve_usuario'], "int")); 
///****** Hacemos la  consulta
$qery_mail = mysqli_query($conecta1, $string_mail_agente);
////****Convertimos la  consulta  a  un elemento  fetc
$mail_agente_fetch = mysqli_fetch_assoc($qery_mail);
////**Obtenemos  el  Nombre del  Gerente 
$nombre_gerente =$mail_agente_fetch['zona'];
 ////****Obtenemos el  correo  del  planeador 
$strin_planeador=  "SELECT  email FROM usuarios_locales  where  rol =100";
////** Realizamos  Qery 
$qery_planeador = mysqli_query($conecta1, $strin_planeador);
////**Fetch  
$fetch_planeador = mysqli_fetch_array($qery_planeador);
///***Obtenemos el  mail del  correo 
$mail_planeador = $fetch_planeador['email'];  
   
////*****Si el Pedido  no esta  Autoirzado  No se envia  en  correo*********************************************************   
if($fetch_adi['auto']==1)
{   
    $est_AUT = "AUTORIZADO";
////*****Informacion a Enviar al  Gerente
$STRING_CADENA_Html_001 = "<table><thead><th><h4>Buen Día ".$nom_agente."  se  ha modificado el estatus del Pedido Adicional .</h4></th><thead>";        
$STRING_CADENA_Html_002 = "<tbody><tr><td><h4>Estado del Pedido: ".$est_AUT."<td><h4>Precio Autorizado Por:".$nombre_gerente."</h4></td></tr>";
$STRING_CADENA_Html_003 = "<tr><td>N# Almacen:".$fetch_adi['almacen']."</td></tr>";
$STRING_CADENA_Html_004 = "<tr><td>Codigo Producto: ".$fetch_adi['codigo_pro']."</td><td>Nombre Producto: ".$fetch_adi['nom_pro']."</td></tr>";
$STRING_CADENA_Html_005 = "<tr><td>Fecha Solicitud: ".$fetch_adi['fecha_sol']."</td><td>Fecha Requerimiento:".$fetch_adi['fecha_rq']."</td></tr>"; 
$STRING_CADENA_Html_006= "<tr><td>Precio Solicitado Por Venta :".$fetch_adi['precio_sol_pv']."</td><td>Cantidad Requerida :".$fetch_adi['cant_req']."</td></tr>"; 
$STRING_CADENA_correo =  "<tr><td>Corre Agente: ".$correo_agente."</td><td>Correo  planeador".$mail_planeador."</td></tr>";
$TRING_CADENA_Html_007 = "</tbody></table>";
/////****
//******************        
////***Armamos la informacion Html  a Enviar
$strin_Fina_HTML =$STRING_CADENA_Html_001.$STRING_CADENA_Html_002 .$STRING_CADENA_Html_003.$STRING_CADENA_Html_004.$STRING_CADENA_Html_005.$STRING_CADENA_Html_006.$TRING_CADENA_Html_007;


////****Mandamos bmesta@agroversa.com.mx
correos("Solicitud de Adicional", "Estus de Autorizacion  Pedido Adicional",$correo_agente,$mail_planeador,$strin_Fina_HTML );
}

$array_result =  array (
    "Est"=>"Hola :D !!" ///"Agente:".$correo_agente."  Gerente".$mail_gerente  /// $strin_Fina_HTML
);


$CONVER_JSON = json_encode($array_result);       
header('Content-type: application/json');
echo  $CONVER_JSON;




?>
