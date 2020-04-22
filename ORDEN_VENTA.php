<?php 
////
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : ORDEN_VENTA.php
 	Fecha  Creacion : 11/04/2019
	Descripcion  : 
  *     
	
	Modificado  Fecha  : 
*/

///require_once( '../CONEC_UNIFICADO/conecction.php');  
////require_once('formato_datos.php');
///require_once('../Connections/conecta1.php');


$conectID = mssql_connect("192.168.101.22","sa","DB@gr0V3rs@");   ///Nuevo Servidor Sql 2016 26-11-2017 
if (!$conectID) {
    die('Erro al conectarse a  MSSQL SAP');
  
}else{
    mssql_select_db('TEST_VERSA20181221',$conectID);	

}

   
////***Nombre del  Procidimiento  a Utilizar (@NUM_OREDEN_VTAS int   ,@NUM_ENTREGA int  )
			
				try{

 			///	$strg_tb_tmp  = "CREATE  TABLE  #SEND_INV1(FACTURA INT  , ENTREGA INT  ,  ORDENCOMPRA INT ) " ;
				
								$NUM_OREDEN_VTAS = 1293 ; 
								$NUM_ENTREGA = 1420173466;
							 $sTOREPRO =   mssql_init("spORDENES_VENTA");
			 				  mssql_query("SET ANSI_NULLS ON");
			 				  mssql_query("SET ANSI_WARNINGS ON");
			 				  mssql_bind($sTOREPRO, '@NUM_OREDEN_VTAS',$NUM_OREDEN_VTAS , SQLINT4);
							  mssql_bind($sTOREPRO, '@NUM_ENTREGA', $NUM_ENTREGA, SQLINT4);
							
								 ///*****Obtenemos Meses que se Actulizaran hooo  Insertaran
				    if(!$qerygetUPDINSERT = mssql_execute($sTOREPRO)) 
					{
						$typeError =  array(
				   						"NUM_ERROR"=> 101 , ///***Error Obtener Determinacion de estatus 
										"ERROR BD" => mssql_get_last_message()  
									)    ;  

							
					}else
					{
						$typeError =  "TODO  BIEN WE :D" ; 

					}




			
				}catch (Exception  $e)
				{
					$typeError =  array(
		   						"NUM_ERROR"=> $e, ///***Error Obtener Determinacion de estatus 
								"ERROR BD" => mssql_get_last_message()  
							)    ;  

				}


		    

$GASTrESULTADO =   Array(
 		///	"Res001" => $CADENAINSERT,  ///Retornamos  Resultado  De INSERCION
          ///  "cadena" =>$CVE_PROD,
             "ERROR"=> $typeError   
     );  
///**Convertimos a  Json  
  $convert_json  =  json_encode($GASTrESULTADO);
 //(/) header('Content-type: application/json');
echo  '<H1>'. $convert_json .'</H1>' ;


ECHO "HOLA WE :T".$algo ;
?>  

