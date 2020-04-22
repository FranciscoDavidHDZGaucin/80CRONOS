<?php
////****pub_UpdaSolicitud.php  
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_UpdaSolicitud.php
 	Fecha  Creacion : 09/05/2017
	Descripcion  : 
  *             Escrip para  Actualizar Una  Solicitud 
  */
///**Conexion Mysql 
include '../Connections/conecta1.php';
///***Formato  de Datosa
require_once('../formato_datos.php'); 
///***Obtenemos  los  Arreglos  json 		
$JSON_CAB =  filter_input(INPUT_POST, 'ENCA');
$JSON_DET =  filter_input(INPUT_POST, 'DET');


///***Convertimos el Encabe A JSON
$Arreglo_fetch = json_decode($JSON_CAB );
 //**Obtenemos los  Valores  
$NomAge = $Arreglo_fetch->{'AGE'} ; ///Agente 
$fechSol = $Arreglo_fetch->{'FECH'} ;///Fecha
$ZNa= $Arreglo_fetch->{'ZO'} ;///Zona 
$reg= $Arreglo_fetch->{'REG'} ;///Region o Unidad
$client= $Arreglo_fetch->{'CLI'} ;///Cliente
$PROV=$Arreglo_fetch->{'PRO'};//Provedor
$motvSOL= $Arreglo_fetch->{'MOT'};//Motivos de la  Solicitud
$folISol= $Arreglo_fetch->{'FOL'} ;//Numero de Folio
$NumAge= $Arreglo_fetch->{'NUMAGE'} ;//Numero  de  Agente
////***Obtenemos el Id  del  Numero de  Folio 
$stringGetID = sprintf("select id  from pedidos.pub_encabeza_publicidad   where  pub_folio =%s",
 GetSQLValueString($folISol, "int"));
$qery_Getid = mysqli_query($conecta1,$stringGetID);
$fetch_getId = mysqli_fetch_array($qery_Getid);


////****Generamos  Cadena  De insercion 
$String_Insert_Cabezera = sprintf("Update pub_encabeza_publicidad set pub_folio=%s ,pub_fech_cap =%s ,cve_agente=%s ,pub_zona=%s ,cliente=%s ,pub_region =%s ,pub_proveedor =%s,pub_moti_sol=%s  where  id = %s",
 GetSQLValueString($folISol, "int"),
 GetSQLValueString($fechSol, "date"),
 GetSQLValueString($NumAge, "int"),
 GetSQLValueString($ZNa, "text"),
 GetSQLValueString($client, "text"),
 GetSQLValueString($reg, "text"),
 GetSQLValueString($PROV, "text"),
   GetSQLValueString($motvSOL, "text"),
 GetSQLValueString($fetch_getId['id'], "int"));
///****Generamos  Qery  
$qery_InsertEncabeza = mysqli_query($conecta1, $String_Insert_Cabezera) ; 
///***Validar  Qery  Cabeza
if(!$qery_InsertEncabeza)
{   ///***Error insert Consulta 
   $ExitCAB = 0; 
}else{
    ///**Insert Correct
    $ExitCAB = 1; }
 

$Areglo_DET = json_decode($JSON_DET);
    ///*Varible Vaidacion Insert
    $ExDet =1;
foreach ($Areglo_DET  as $ELM)
{
 ///****Obtenemos Los Elementos
    $nf=  $ELM ->{'nf'}; ///Numero  de Folio
    $cve= $ELM ->{'cve'}; //Clave del  Producto 
    $nom= $ELM ->{'nom'}; // Nombre del  Producto 
    $cant=$ELM ->{'cant'} ; //Cantidad  de  Producto 
    $come= $ELM ->{'come'}; //Comentarios
    //*************************************************************************************
    ////***Obtenemos el Id  del  Numero de  Folio 
    $stringGetID = sprintf("select id  from pedidos.pub_detalle_publicidad   where  pub_folio =%s  and pub_cvepro =%s  ",
     GetSQLValueString($nf, "int"),
 GetSQLValueString($cve, "text"));
    $qery_Getid = mysqli_query($conecta1,$stringGetID);
    $fetch_getId = mysqli_fetch_array($qery_Getid);
   ///***Detectamos  sI Es  un Nuevo Elemento Agregado
 if($fetch_getId['id'] == 0 ||$fetch_getId['id'] == NULL )
 {
     ///**String Insert
      ///**String  Insert  
    $String_prodInsert  = sprintf("Insert INTO pedidos.pub_detalle_publicidad SET  pub_folio =%s,pub_cvepro =%s ,nom_producto =%s ,cantidad_solici=%s,Descripcion_produc=%s ",
            GetSQLValueString($nf, "int"),
            GetSQLValueString($cve, "text"),
            GetSQLValueString($nom, "text"),
            GetSQLValueString($cant, "int"),
            GetSQLValueString($come, "text"));
     
 }else {
    ///**String Update  
    $String_prodInsert  = sprintf("Update pedidos.pub_detalle_publicidad SET  pub_folio =%s,pub_cvepro =%s ,nom_producto =%s ,cantidad_solici=%s,Descripcion_produc=%s where  id = %s ",
            GetSQLValueString($nf, "int"),
            GetSQLValueString($cve, "text"),
            GetSQLValueString($nom, "text"),
            GetSQLValueString($cant, "int"),
            GetSQLValueString($come, "text"),
           GetSQLValueString($fetch_getId['id'], "int") );
  
 }
      $qery_det_pub = mysqli_query($conecta1, $String_prodInsert);
    if(!$qery_det_pub){ $ExDet =0;} ///Fail  Insert 
}

 $pub_arreglo  =   Array(
 			"Res001" => $ExitCAB , ///Retornamos  Resultado Insert Cabecera
                         "Res002" => $ExDet ///Retornamos  Resultado  Detalle.
 	);
///**Convertimos a  Json  
  $convert_json  =  json_encode($pub_arreglo);
  header('Content-type: application/json');
echo  $convert_json ;


?> 