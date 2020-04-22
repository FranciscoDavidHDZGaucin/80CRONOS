<?php
/////***** coti_addCabCotiz.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_addCabCotiz.php 
 	Fecha  Creacion :  31/05/2017
	Descripcion  : Generamos  Encabezado 
 * 
 *    Modificaciones :
 *                   24/07/2017    Utilizaos  $TyVisor  para  poder  controlar
 *                                 el tipo de desplegado de  agregado  de  cotizaciones
 *                             Entiendase  que   $TyVisor => 0 Lo   Utilizamos  para  que el Modulo FUncionen para agregar una  Cotizacion Nueva (De Forma  Normal)
 *                              Entiendase  que   $TyVisor => 1 Lo   Utilizamos  para  Habilitar la Opcion de Modificaion  Si la COtizacion FUe Regresada
 *                              Entiendase  que   $TyVisor => 2 Lo   Utilizamos  solo  como un visor.
 * 

  */
require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');

$estQery =true; 
/////*****Obtenemos  el   Folio 
$fl = filter_input(INPUT_POST, 'folio');
///****Obtenemos el n_agente
$n_agente = filter_input(INPUT_POST, 'nAge');
///***Comentarios 
$strngComent  = filter_input(INPUT_POST, 'txtComent');
///* Zonas 
$estZona  = filter_input(INPUT_POST, 'ZONA');
////****Obtenemos el  estado  de la  Zona ,opczona=%s
if($estZona=='true'){ $typeZona =1; }else{$typeZona =0;}
////***Obtenemos el  Tipo  de  Despligue 
$TypeDespliegue = filter_input(INPUT_POST, 'typDes');

///*****Agregar Normalmente  Una Cotizacion 
if($TypeDespliegue == 0){
    
    $strADDcAB = sprintf("INSERT  INTO coti_encabeca_cotizacion  SET folio =%s,typeusu =1 ,cve_agente=%s, com_agent=%s,opczona=%s ", 
     GetSQLValueString($fl, "int"),GetSQLValueString($n_agente, "int"),GetSQLValueString($strngComent, "text"),GetSQLValueString($typeZona, "int"));

    $qerADDcAB= mysqli_query($conecta1, $strADDcAB);

    IF(!$qerADDcAB){

        $estQery =false; 

    }else{
       $anioactual = date("Y"); 
       ////***Numero  Concecutivo 
       $numCon;
      ///***Obtenemos  el  Numero  Consecutivo Folio  
       $productos_string=sprintf("SELECT count(con_num)  as mayor FROM coti_folio_cotizaciones  where  num_agente =%s  and  anio=%s",
                         GetSQLValueString($n_agente, "int"),
                         GetSQLValueString($anioactual, "int"));
         ///***Realizamos la  Consulta
      $qery_folio=  mysqli_query($conecta1,$productos_string) ; 
      ///***Realizamos el  Fetch 
      $fecth_folio = mysqli_fetch_array($qery_folio); 

      ///***Si el  Resultado  es  null  o  Empty 
     if($fecth_folio['mayor']== "" ||$fecth_folio['mayor']== null ){  $numCon =1;}else { $numCon = $fecth_folio['mayor']+1;}

      $strADDcAB = sprintf("INSERT  INTO coti_folio_cotizaciones  SET folio =%s, num_agente=%s, anio=%s,con_num=%s  ", 
     GetSQLValueString($fl, "int"),GetSQLValueString($n_agente, "int"),GetSQLValueString($anioactual, "int"),GetSQLValueString($numCon, "int"));

    $qerAddFolio= mysqli_query($conecta1, $strADDcAB);  


    }
}
////***Modificar encabezado Update 
if($TypeDespliegue == 1){
    
    $strADDcAB = sprintf("update   coti_encabeca_cotizacion  SET estatus_Ic=0,estatus_Dc=0 ,typeusu =1, cve_agente=%s, com_agent=%s,opczona=%s where folio =%s ", 
     GetSQLValueString($n_agente, "int"),GetSQLValueString($strngComent, "text"),GetSQLValueString($typeZona, "int"),GetSQLValueString($fl, "int"));

    $qerADDcAB= mysqli_query($conecta1, $strADDcAB);

}



$arreresl = array(  "EstQery" =>$estQery );
  ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arreresl); 

?>