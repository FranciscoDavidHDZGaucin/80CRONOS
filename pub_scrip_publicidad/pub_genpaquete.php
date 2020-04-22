<?php
////****pub_genpaquete.php
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_genpaquete.php
 	Fecha  Creacion : 09/08/2017
	Descripcion  : 
  *             Escrip para  Agregar la Informacion de Envio 
  *             Agregamos la opcion  
  *                 OPC => 1 => Agregado  De informacion  de  Envio
  *                  OPC => 2 => Agregado  de  Fecha  de  Recibido
  */
///**Conexion Mysql 
include '../Connections/conecta1.php';
///***Formato  de Datosa
require_once('../formato_datos.php');

////Obtenemos  el  Tipo de  Insercion  
$OPCupdate = filter_input(INPUT_POST,'OPC');

IF($OPCupdate==1){
    ///***Obtenemos  los  Arreglos  json 		
    $JSON_CAB =  filter_input(INPUT_POST, 'PAQUE');

    ///***Convertimos el Encabe A JSON
    $Arreglo_fetch = json_decode($JSON_CAB );
     //**Obtenemos los  Valores  
    $FOLIO = $Arreglo_fetch->{'fl'} ; /// 
    $TYPESEND = $Arreglo_fetch->{'typeSend'} ;///
    $NUMGuia= $Arreglo_fetch->{'num_guia'} ;/// 
    $textOtro= $Arreglo_fetch->{'txtOtro'} ;///
    $fecha= $Arreglo_fetch->{'fech_envi'} ;///
    ////***Obtenemos el Id  del  Numero de  Folio 
    $stringGetID = sprintf("select id  from pedidos.pub_encabeza_publicidad   where  pub_folio =%s",
     GetSQLValueString($FOLIO, "int"));
    $qery_Getid = mysqli_query($conecta1,$stringGetID);
    $fetch_getId = mysqli_fetch_array($qery_Getid);

    IF($TYPESEND=="3"){

        ////****Generamos  Cadena  De insercion retremex 015541624112
     $String_Insert_Cabezera = sprintf("Update pub_encabeza_publicidad set typetosend= %s,otro= %s,num_guia= %s,fech_envio= %s   where  id = %s",
     GetSQLValueString($TYPESEND, "int"),
     GetSQLValueString($textOtro, "text"),
     GetSQLValueString($NUMGuia, "text"),
     GetSQLValueString($fecha, "date"),
     GetSQLValueString($fetch_getId['id'], "int"));


    }
    IF($TYPESEND=="1"||$TYPESEND=="2"){
    ////****Generamos  Cadena  De insercion retremex 015541624112
     $String_Insert_Cabezera = sprintf("Update pub_encabeza_publicidad set typetosend= %s,num_guia= %s,fech_envio= %s   where  id = %s",
     GetSQLValueString($TYPESEND, "int"),
     GetSQLValueString($NUMGuia, "text"),
     GetSQLValueString($fecha, "date"),
     GetSQLValueString($fetch_getId['id'], "int"));

    }

    ///****Generamos  Qery  
    $qery_InsertEncabeza = mysqli_query($conecta1, $String_Insert_Cabezera) ;
}
IF($OPCupdate==2){
     ///***Obtenemos  los  Arreglos  json 		
    $JSON_CAB =  filter_input(INPUT_POST, 'PAQUE');

    ///***Convertimos el Encabe A JSON
    $Arreglo_fetch = json_decode($JSON_CAB );
     //**Obtenemos los  Valores  
    $FOLIO = $Arreglo_fetch->{'fl'} ; /// 
    $fecha= $Arreglo_fetch->{'fech_RE'} ;///
    ////***Obtenemos el Id  del  Numero de  Folio 
    $stringGetID = sprintf("select id  from pedidos.pub_encabeza_publicidad   where  pub_folio =%s",
     GetSQLValueString($FOLIO, "int"));
    $qery_Getid = mysqli_query($conecta1,$stringGetID);
    $fetch_getId = mysqli_fetch_array($qery_Getid);

    $String_Insert_Cabezera = sprintf("Update pub_encabeza_publicidad set fech_rec=%s   where  id = %s",
     GetSQLValueString($fecha, "date"),
     GetSQLValueString($fetch_getId['id'], "int"));
    ///****Generamos  Qery  
    $qery_InsertEncabeza = mysqli_query($conecta1, $String_Insert_Cabezera) ;
    
}



///***Validar  Qery  Cabeza
if(!$qery_InsertEncabeza)
{   ///***Error insert Consulta 
   $ExitCAB = 0; 
}else{
    ///**Insert Correct
    $ExitCAB = 1; }
 

 $pub_arreglo  =   Array(
 			"RES" => $ExitCAB ///
         
 	);
///**Convertimos a  Json  
  $convert_json  =  json_encode($pub_arreglo);
  header('Content-type: application/json');
echo  $convert_json ;


?> 

