<?php

/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_getclisee.php
 	Fecha  Creacion :  25/07/2017
	Descripcion  : Script  para Modificar los  estatus DC
 * 

  */
require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');
///*****Formato de  Datos          
require_once('../conexion_sap/sap.php');
///mssql_select_db("AGROVERSA");
////*Obtenemos el  Folio 
$FOLIO  = filter_input(INPUT_POST,'FL' );
////***Cadena para Obtener   la Informacion
$strGetCVECliente = sprintf("SELECT cve_cliente FROM pedidos.coti_asig_cliente where  folio_coti =%s",
                        GetSQLValueString($FOLIO, "int"));
////***Qery para  hacer el  llamado Mysql 
$qerrugetcli  = mysqli_query($conecta1,$strGetCVECliente);
////*Arreglo Final 
$CLIENALL =  Array(); 

while ($row = mysqli_fetch_array($qerrugetcli)) {
      ////****Consulta   para obtener los  clientes
            $querycliente=sprintf("SELECT CardName FROM clientes_cronos WHERE CardCode=%s ",
             GetSQLValueString($row['cve_cliente'], "text"));
           $cliente = mssql_query($querycliente);
           if(!$cliente)
           {
              $CLIENALL ="ERROR"; 
           }else {
                    /////****Ciclo para Agreegar  los  clientes   mssql_fetch_array
                      $cgetrow = mssql_fetch_array($cliente);
                        ////**Areglo base para almacenar  a  los  clientes  
                        $ClI =  array("CardCode"=>$row['cve_cliente'] ,"CardName"=>$cgetrow['CardName'] );  
                        ///**Agregamos  el cliente  al Areglo final
                        array_push($CLIENALL, $ClI); 
                  
           }

}
$arreresl = array(  "RES" =>$strGetCVECliente, "Cli"=>json_encode($CLIENALL) );
///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arreresl); 
?>