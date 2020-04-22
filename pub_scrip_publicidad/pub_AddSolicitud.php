<?php 
 ///***pub_AddSolicitud.php.php 
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_AddSolicitud.php 
 	Fecha  Creacion : 05/05/2017 
	Descripcion  : 
			Archivo  para    Agregar Una  Solicitud de  Publicidad

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
////****Generamos  Cadena  De insercion 
$String_Insert_Cabezera = sprintf("Insert INTO pub_encabeza_publicidad set pub_folio=%s ,pub_fech_cap =%s ,cve_agente=%s ,pub_zona=%s ,cliente=%s ,pub_region =%s ,pub_proveedor =%s,pub_moti_sol=%s ",
 GetSQLValueString($folISol, "int"),
 GetSQLValueString($fechSol, "date"),
 GetSQLValueString($NumAge, "int"),
 GetSQLValueString($ZNa, "text"),
 GetSQLValueString($client, "text"),
 GetSQLValueString($reg, "text"),
 GetSQLValueString($PROV, "text"),
   GetSQLValueString($motvSOL, "text"));
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
    ////**Obtenemos los  Precio  del Producto  
    $strGetPreProd = sprintf("select precio_unitario from  pedidos.pub_catalogo_publicidad   where  codig_prod =%s ",GetSQLValueString( $cve,"text"));
    $qerygetProd = mysqli_query($conecta1, $strGetPreProd);
    $prEU =mysqli_fetch_array($qerygetProd);

    ///**String  Insert  
    $String_prodInsert  = sprintf("Insert INTO pedidos.pub_detalle_publicidad SET  pub_folio =%s,pub_cvepro =%s ,nom_producto =%s ,cantidad_solici=%s,Descripcion_produc=%s,precio_unitario=%s ",
            GetSQLValueString($nf, "int"),
            GetSQLValueString($cve, "text"),
            GetSQLValueString($nom, "text"),
            GetSQLValueString($cant, "int"),
            GetSQLValueString($come, "text"),
            GetSQLValueString($prEU['precio_unitario'], "double"));
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