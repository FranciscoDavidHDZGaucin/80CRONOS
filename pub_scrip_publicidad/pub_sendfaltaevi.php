<?php
///****pub_sendfaltaevi.php  asd
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : desv_senmailAgent.php
 	Fecha  Creacion : 02/08/2017
	Descripcion  :
 *       Escrip  utilizado por el planeador para  enviar  correos solo al  Agente  
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
 ////mssql_select_db("AGROVERSA");
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
////*****Obtenemos el COMENTARIO 
$COMENT= filter_input(INPUT_POST, 'cometPla'); 
 ////***Objetenomos  el  Objeto 
$OBJT = json_decode(filter_input(INPUT_POST,'Obj'));

///****************************************************************************
///***Obtenemos el  correo y nombre  de  Agente 
$elementos_Agente = agente_mail($OBJT->cvagente);
   ///***Obtenemos el  Correo  Agente 
   $nom_agente= $elementos_Agente['nombre_agente'];
   ///***Obtenemos el correo 
   $correo_agente =  $elementos_Agente['mail_agente'];
  
//***Obtenemos el  correo  del  Gerente   
////*****Cadena de  Consulta
$string_mail_agente = sprintf("SELECT  zona,mail   FROM pedidos.relacion_gerentes where cve_age = %s",
 GetSQLValueString($OBJT->cvagente, "int")); 
///****** Hacemos la  consulta
$qery_mail = mysqli_query($conecta1, $string_mail_agente);
////****Convertimos la  consulta  a  un elemento  fetc
$mail_agente_fetch = mysqli_fetch_assoc($qery_mail);
///****Obtenemos  el  Correo  del  Gerente 
$mail_gerente = $mail_agente_fetch['mail'];
////**Obtenemos  el  Nombre del  Gerente 
$nombre_gerente =$mail_agente_fetch['zona'];
/////************Generamos html  para  el Correo 

////*****Informacion a Enviar al  Gerente
$STRING_CADENA_Html_001 = "<table><thead><th><h4>Buen Día ".$nom_agente.".</h4></th><thead>";        
$STRING_CADENA_Html_002 = "<tr><td>Favor de Subir las Evidencias de la Solicitud de Publicidad  con Clave:".$OBJT->cvefolio." </td></tr>";
$STRING_CADENA_Html_003 = "<tbody><tr><td><h4>Con Fecha de Captura ".$OBJT->feCap."</h4></td><td><h4>Con Numero de  Guia".$OBJT->numeroGu."</h4></td></tr>";
$STRING_CADENA_Html_004 = "<tr><td>Comentarios  :".$COMENT."</td></tr></table>"; 
        
/*        "<tr><td>Codigo Producto: ".$fetch_adi['codigo_pro']."</td><td>Nombre Producto: ".$fetch_adi['nom_pro']."</td></tr>";
$STRING_CADENA_Html_005 = "<tr><td>Fecha Solicitud: ".$fetch_adi['fecha_sol']."</td><td>Fecha Requerimiento:".$fetch_adi['fecha_rq']."</td></tr>"; 
$STRING_CADENA_Html_006= "<tr><td>Precio Solicitado Por Venta :".$fetch_adi['precio_sol_pv']."</td><td>Cantidad Requerida :".$fetch_adi['cant_req']."</td></tr>"; 
$TRING_CADENA_Html_007 = 
****/
//******************       
////////*******

////***Armamos la informacion Html  a Enviar
$strin_Fina_HTML =$STRING_CADENA_Html_001.$STRING_CADENA_Html_002 .$STRING_CADENA_Html_003.$STRING_CADENA_Html_004; ///$STRING_CADENA_Html_001.$STRING_CADENA_Html_002 .$STRING_CADENA_Html_003.$STRING_CADENA_Html_004.$STRING_CADENA_Html_005.$STRING_CADENA_Html_006.$TRING_CADENA_Html_007;
$e ="HolA";

///****Validacion par  Enviar   Correo a los Agentes  ////****Mandamos bmesta@agroversa.com.mx
correos("Falta de Evidencia","Falta de Evidencia","egonzalez@agroversa.mx","fhernandez@agroversa.com.mx", $strin_Fina_HTML);

$array_result =  array (
    "Est"=>$strin_Fina_HTML///"Agente:".$correo_agente."  Gerente".$mail_gerente  /// $strin_Fina_HTML
);


$CONVER_JSON = json_encode($array_result);       
header('Content-type: application/json');
echo  $CONVER_JSON;






?>

