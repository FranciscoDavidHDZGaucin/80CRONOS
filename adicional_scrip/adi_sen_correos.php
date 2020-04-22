<?php
/////adi_sen_correos
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_sen_correos.php   
 	Fecha  Creacion : 15/03/2017
	Descripcion  : 
 *                      Escip diseñado para enviar correo 
 *                              al  Gerente  y Planeador 
 * 
  */
///***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
////Agregamos  Modulo Correos
require_once('../correos.php');
////*****
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
///****Obtenemos Nuevo Estatus Adicional A Proyeccion  || P/Mes  en Curso
$estPM = filter_input(INPUT_POST, 'estPMcurso'); 
if(strcmp($estPM, "true")==0)       
{
    $PMEST = 1; 
}else{
    $PMEST = 0;
}
//***Obtenemos el  correo  del  Agente  
////*****Cadena de  Consulta
$string_mail_agente = sprintf("SELECT  zona,mail   FROM pedidos.relacion_gerentes where cve_age = %s",
 GetSQLValueString($num_usuario, "int")); 
///****** Hacemos la  consulta
$qery_mail = mysqli_query($conecta1, $string_mail_agente);
////****Convertimos la  consulta  a  un elemento  fetc
$mail_agente_fetch = mysqli_fetch_assoc($qery_mail);
///****Obtenemos  el  Correo 
$mail_gerente = $mail_agente_fetch['mail'];
////**Obtenemos  el  Nombre del  Gerente 
$nombre_gerente =$mail_agente_fetch['zona'];

////****Obtenemos el  correo  del  planeador 
$strin_planeador=  "SELECT  email FROM pedidos.usuarios_locales  where  rol =100";
////** Realizamos  Qery 
$qery_planeador = mysqli_query($conecta1, $strin_planeador);
////**Fetch  
$fetch_planeador = mysqli_fetch_array($qery_planeador);
///***Obtenemos el  mail del  correo 
$mail_planeador = $fetch_planeador['email'];


////****Obtenemos el  correo  de andrea duarrte 
$strin_ani_jr=  "SELECT  email FROM pedidos.usuarios_locales  where  rol =96";
////** Realizamos  Qery 
$qery_ani_jr = mysqli_query($conecta1, $strin_ani_jr);
////**Fetch  
$fetch_ani_jr = mysqli_fetch_array($qery_ani_jr);
///***Obtenemos el  mail del  correo 
$mail_ani_jr = $fetch_ani_jr['email'];

////*****Informacion a Enviar al  Gerente
$STRING_CADENA_Html_001 = "<table><thead><th><h4>Buen Día se ha  Generado un Nuevo pedido Adicional.</h4></th><thead>";        
$STRING_CADENA_Html_002 = "<tbody><tr><td><h4>Nombre :".$nombre_usuario."</h4></td></tr>";
$STRING_CADENA_Html_003 = "<tr><td>N# Almacen:".$almacen."</td></tr>";
$STRING_CADENA_Html_004 = "<tr><td>Codigo Producto: ".$codigo_producto."</td><td> Nombre Producto".$nomb_producto."</td></tr>";
$STRING_CADENA_Html_005 = "<tr><td>Fecha Solicitud: ".$fec_sol."</td><td>Fecha Requerida".$fec_rq."</td></tr>"; 
$STRING_CADENA_Html_006= "<tr><td>Precio Solicitado Por Venta :".$pre_solPV."</td><td> Cantidad Requerida:".$can_rq."</td></tr>"; 
 $TRING_CADENA_Html_007 = "</tbody></table>";
/////**********************        
////***Armamos la informacion Html  a Enviar
$strin_Fina_HTML =$STRING_CADENA_Html_001.$STRING_CADENA_Html_002 .$STRING_CADENA_Html_003.$STRING_CADENA_Html_004.$STRING_CADENA_Html_005.$STRING_CADENA_Html_006.$TRING_CADENA_Html_007;
////****Mandamos  Correo 
correos("Solicitud de Adicional", "Solicitud de Autorización para Pedido Adicional",$mail_gerente,"", $strin_Fina_HTML);
////*****Generamos  Cadena  para el  planeador
$STRINGA_CADENA_PLANEADOR_001  ="<table><thead><th><h4>Buen Día se ha Generado un Nuevo pedido Adicional.</h4></th><thead>"; 
$STRINGA_CADENA_PLANEADOR_002  = "<tbody><tr><td><h4>Nombre del  Solicitante :".$nombre_usuario."</h4></td><td><h4> Nombre del  Gerente: ".$nombre_gerente."</h4></td></tr>";  


////***Armado de mensage 
$STRIGN_CADENA_FIN_PLANEADOR = $STRINGA_CADENA_PLANEADOR_001.$STRINGA_CADENA_PLANEADOR_002.$STRING_CADENA_Html_003.$STRING_CADENA_Html_004.$STRING_CADENA_Html_005.$STRING_CADENA_Html_006;
////****Mandamos  Correo planeador 
correos("Solicitud de Adicional", "Nueva  Solicitud  de Pedido Adicional",$mail_planeador,"", $STRIGN_CADENA_FIN_PLANEADOR);
////****Mandamos  Correo a Andrea Duarte solicitud de ticket 20200691
correos("Solicitud de Adicional", "Nueva  Solicitud  de Pedido Adicional",$mail_ani_jr,"", $STRIGN_CADENA_FIN_PLANEADOR);
////***Opcion para Enviar 
if(strcmp($estPM, "true")==0)       
{
 ////***Mandamos   correo  a  Planeadora  de Produccion 
   correos("Pedidos Adicionales", "Nueva  Solicitud  de Pedido Adicional","gtrevino@agroversa.com.mx","", $STRIGN_CADENA_FIN_PLANEADOR);
}


$array_result =  array (
    "Est"=> $strin_Fina_HTML
);
        

$CONVER_JSON = json_encode($array_result);       
header('Content-type: application/json');
echo  $CONVER_JSON;
              
?> 
