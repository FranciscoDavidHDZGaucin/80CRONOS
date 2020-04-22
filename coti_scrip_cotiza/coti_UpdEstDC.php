<?php

///***coti_UpdEstDC.php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_UpdEstDC.php
 	Fecha  Creacion :  25/07/2017
	Descripcion  : Script  para Modificar los  estatus DC
 * 

  */
require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');
/**Varible Control  de  Errores 
    ////Estados !!! 
 *  $ERRORsCRIPT = 0 => Entiendase  => Sin Ningun ERROR  DURENTE LA EJECUCION Del Escript 
 *  $ERRORsCRIPT = 1  => Entiendase => Error en Obtener  ID
 *  $ERRORsCRIPT = 2  => Entiendase => Error en  Update    
 */
$ERRORsCRIPT = 0;  
//****Obtenemos la  Informacion   
$FOLIO  = filter_input(INPUT_POST,'FL' );
$EST  =  filter_input(INPUT_POST, 'TYPEST');
$fecha_actual = date('Y-m-d HH:mm:ss '); 
if($EST == 1 ||$EST == 2  ){
    
    
    
    ////***Realizamos  la Modificacion del Estatus  
           $strUpEST = sprintf("update coti_encabeca_cotizacion set estatus_DC=%s,   fecha_auto_DC=Now()   where folio=%s ",
                              GetSQLValueString($EST, "int"),
                            ///  GetSQLValueString($fecha_actual, "date"),
                              GetSQLValueString($FOLIO, "int")        
                   );
           $qeryEst = mysqli_query($conecta1, $strUpEST);
}
if($EST == 3  ){
    
    $CmIC = filter_input(INPUT_POST,'COME');
    ////***Realizamos  la Modificacion del Estatus  
           $strUpEST = sprintf("update coti_encabeca_cotizacion set estatus_DC =%s, fecha_auto_DC=Now() , coment_Dc=%s   where folio=%s ",
                              GetSQLValueString($EST, "int"),
                            ///  GetSQLValueString($fecha_actual, "date"),
                       ////*****Agregamos Comentarios  Jefe Inteligencia Comercial         
                              GetSQLValueString($CmIC, "text"),
                              GetSQLValueString($FOLIO, "int")        
                   );
              $qeryEst = mysqli_query($conecta1, $strUpEST);
}

$arreresl = array(  "RES" =>"HOLA Escript :D !!!", "ERRORES"=>$ERRORsCRIPT );
///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arreresl); 
?>
