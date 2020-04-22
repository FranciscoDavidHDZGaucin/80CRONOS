<?php
/////gast_UpdEstGast.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : gast_UpdEstGast.php 
 	Fecha  Creacion :  15/11/2017
	Descripcion  : Script  para Modificar los  estatus
 * 

  */
require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');
/**Varible Control  de  Errores 
    ////Estados !!! 
 *  $ERRORsCRIPT = 0 => Entiendase  => Sin Ningun ERROR  DURENTE LA EJECUCION Del Escript  fecha_auto_Ic=Now()
 *  $ERRORsCRIPT = 1  => Entiendase => Error en Obtener Agregando el  Update SELECT vbo_gerente , fech_vbo_geren FROM pedidos.poliza;
 *    
 */
$ERRORsCRIPT = 0;  
//****Obtenemos la  Informacion   
$FOLIO  = filter_input(INPUT_POST,'FL' );
$EST  =  filter_input(INPUT_POST, 'EST');
$fecha_auto_Ic= date("Y-m-d H:i:s");
         
        /////**+Obtenemos los Id    
          $fetID = mysqli_fetch_array($qeryGetid);
          ////**Generamos la  cadena 
          $strUpEl = sprintf("UPDATE poliza set vbo_gerente=%s ,fech_vbo_geren=%s  where id=%s",
                                  GetSQLValueString($EST, "int"),
                                  GetSQLValueString($fecha_auto_Ic, "date"),
                                  GetSQLValueString($FOLIO, "int")
                     );
           ////***Ejecutamos el Update 
           if(!mysqli_query($conecta1, $strUpEl))
           {
               $ERRORsCRIPT = 1 ;///eRROR Agregando el  Update 
         
           }
  



$arreresl = array(  "RES" =>"HOLA Escript :D !!!", "ERRORES"=>$ERRORsCRIPT );
  ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arreresl); 
?>

