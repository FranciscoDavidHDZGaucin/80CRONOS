
<?php 
//// 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_prodsinsave.php
 	Fecha  Creacion : 17/05/2017  
	Descripcion  :
				Escrip  para Obtener los productos  que No se  han generado Encabezado 
	Modificaciones :
 *                      20/06/2017  Se Le agrega  la variable  
 *                                  EstApli => Variable ´para  determinar Si   El escrip Obtendra los Productos 
 *                                              No se leas ha asignado   Cabecera  o  Si  Ya estan siendo  revisados 
 *                                              Por el Jefe de Inteligencia  Comercial 
 *                                              Para eso  entiendase que EstApli es :
 *                                          EstApli => 0 => Consulta Aplicada  para el Modulo de  Agentes
 *                                          EstApli => 1 => Consulta  Aplicada  para el Modulo de Jefe Inteligencia 
 *                                  Se  Agregan las  Funciones Pertinentes para  Obtener   el  Costo de Inventario   y   Costo Proyectado
 *      		
  */
require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');
////***Conexion   Sap 
require_once('../conexion_sap/sap.php');
///***Seleccion de la Bd 
 ///mssql_select_db("AGROVERSA");

$FOLIELEMS  = filter_input(INPUT_POST, 'FL' );
///Variable Agregada el   20/06/2017 
$EstApli =  filter_input(INPUT_POST, 'EST' );
if($EstApli ==0 ){
$strGetELMS =  sprintf("select  * from pedidos.coti_detalle_cotizacion where folio =%s and  cabGen=0 ",
		GetSQLValueString($FOLIELEMS, "int"));
}
if($EstApli ==1 ){
$strGetELMS =  sprintf("select  * from pedidos.coti_detalle_cotizacion where folio =%s ",
		GetSQLValueString($FOLIELEMS, "int"));
}


$qery_det_pub = mysqli_query($conecta1,$strGetELMS);

$AregloConvert  =  Array() ;
////***Se Ejecuta  la consulta  paraMostrar  a los  agentes  sus Productos
if($EstApli ==0 ){
    while  ($fetch_elem  =  mysqli_fetch_array($qery_det_pub)) {

                    ///***Obtenemos el Nobre del  Producto a  Bonificar 
                    if($fetch_elem['boni_act'] ==1 )
                    { 
                            $estatusBoni= "true";
                            $string_prod= sprintf("SELECT ItemName FROM plataformaproductosl1 WHERE ItemCode=%s  ", GetSQLValueString($fetch_elem['cve_prodBoni'], "text"));

                $qernomprod = mssql_query($string_prod);
                            $fetchNoProd =mssql_fetch_array($qernomprod);

                            $ArObje =  Array("cveProd"=>$fetch_elem['cve_prod'],"nomProd"=>$fetch_elem['nom_prod'],"cantSol"=>$fetch_elem['cant'],"preSol"=>$fetch_elem['prec_prod'],"boniEst"=>$estatusBoni,"cveProdBoni"=>$fetch_elem['cve_prodBoni'],"boniPorPre"=>$fetch_elem['boni_porPre'],"boniPorCant"=>$fetch_elem['boni_porCant'],"boniPreSol"=>$fetch_elem['boni_precio'],"boniPreAp"=>$fetch_elem['boni_apli'],"NomProdBoni"=>$fetchNoProd['ItemName'],"VTN"=>$fetch_elem['ventProd']);
            }else{
                    $estatusBoni= "false";
                    $ArObje =  Array("cveProd"=>$fetch_elem['cve_prod'],"nomProd"=>$fetch_elem['nom_prod'],"cantSol"=>$fetch_elem['cant'],"preSol"=>$fetch_elem['prec_prod'],"boniEst"=>$estatusBoni,"cveProdBoni"=>$fetch_elem['cve_prodBoni'],"boniPorPre"=>$fetch_elem['boni_porPre'],"boniPorCant"=>$fetch_elem['boni_porCant'],"boniPreSol"=>$fetch_elem['boni_precio'],"boniPreAp"=>$fetch_elem['boni_apli'],"NomProdBoni"=>"","VTN"=>$fetch_elem['ventProd']);
            }    



            array_push($AregloConvert , $ArObje );
    }
}
///Se Ejecuta 
if($EstApli == 1 ){
        while  ($fetch_elem  =  mysqli_fetch_array($qery_det_pub)) {
                 $estqerys =true;
             //////*****Obtenemos el  Costo  Por Inventario    CtoInv
                $strCotin  = sprintf("SELECT MAx(AvgPrice) AS  costInv  FROM  ExisXLoteyCosPromAlma where Num_Art =%s", GetSQLValueString($fetch_elem['cve_prod'], "text"));
                $qerCosInv  = mssql_query($strCotin);
                $fetCostInv = mssql_fetch_array($qerCosInv);
                /////*****Obtenemos el  Costo  Proyectado
                $strCosProy = sprintf("SELECT costo as CtoProy FROM pedidos.costos  where  cve_articulos =%s", GetSQLValueString($fetch_elem['cve_prod'], "text"));
                $qeryCosPROY = mysqli_query($conecta1,$strCosProy);
                $fetCosProy =   mysqli_fetch_array($qeryCosPROY);
                /////**Obtenemos  El Limite  Dc
                $string_get_cmg_min  = sprintf("SELECT cmg_min  FROM  cmgm_dircom where  cve_producto =%s",GetSQLValueString($fetch_elem['cve_prod'], "text"));
                $qery_asd = mysqli_query($conecta1, $string_get_cmg_min);
                $fetch_cmg_min  = mysqli_fetch_array($qery_asd);
                
               /// if(!$qerCosInv){ $estqerys =false; break;}if(!$qeryCosPROY){ $estqerys =false;  break;}if(!$qery_asd){ $estqerys =false; break;}
                
                
                ///***Obtenemos el Nobre del  Producto a  Bonificar 
                if($fetch_elem['boni_act'] ==1 )
                { 
                                $estatusBoni= "true";
                                $string_prod= sprintf("SELECT ItemName FROM plataformaproductosl1 WHERE ItemCode=%s  ", GetSQLValueString($fetch_elem['cve_prodBoni'], "text"));

                                $qernomprod = mssql_query($string_prod);
                                $fetchNoProd =mssql_fetch_array($qernomprod);
                               
                                
                                $ArObje =  Array("cveProd"=>$fetch_elem['cve_prod'],"nomProd"=>$fetch_elem['nom_prod'],"cantSol"=>$fetch_elem['cant'],"preSol"=>$fetch_elem['prec_prod'],"costInv"=> number_format($fetCostInv['costInv'], 2, '.', ''),"CtoProy"=>number_format($fetCosProy['CtoProy'], 2, '.', '') ,"boniEst"=>$estatusBoni,"cveProdBoni"=>$fetch_elem['cve_prodBoni'],"boniPorPre"=>$fetch_elem['boni_porPre'],"boniPorCant"=>$fetch_elem['boni_porCant'],"boniPreSol"=>$fetch_elem['boni_precio'],"boniPreAp"=>$fetch_elem['boni_apli'],"NomProdBoni"=>$fetchNoProd['ItemName'],"VTN"=>$fetch_elem['ventProd'],"LimDc"=>$fetch_cmg_min['cmg_min']);
                }else{
                        $estatusBoni= "false";
                        $ArObje =  Array("cveProd"=>$fetch_elem['cve_prod'],"nomProd"=>$fetch_elem['nom_prod'],"cantSol"=>$fetch_elem['cant'],"preSol"=>$fetch_elem['prec_prod'],"costInv"=>number_format($fetCostInv['costInv'], 2, '.', ''),"CtoProy"=>number_format($fetCosProy['CtoProy'], 2, '.', ''),"boniEst"=>$estatusBoni,"cveProdBoni"=>$fetch_elem['cve_prodBoni'],"boniPorPre"=>$fetch_elem['boni_porPre'],"boniPorCant"=>$fetch_elem['boni_porCant'],"boniPreSol"=>$fetch_elem['boni_precio'],"boniPreAp"=>$fetch_elem['boni_apli'],"NomProdBoni"=>"","VTN"=>$fetch_elem['ventProd'],"LimDc"=>$fetch_cmg_min['cmg_min']);
                }    



                array_push($AregloConvert , $ArObje );
        }
}

///***Obtenemos los Clientes   Pertenecientes al   folio
$strGeClient = sprintf("SELECT  cve_cliente   FROM pedidos.coti_asig_cliente  where   folio_coti  =%s ",
 GetSQLValueString($FOLIELEMS, "int"));
////**Realizamos el Qery
$qeryCliente = mysqli_query($conecta1, $strGeClient);
///***Ciclo para generar  la  respuesta 
$clienteArre = Array();
while($ELEM = mysqli_fetch_array($qeryCliente))
{
 ////****Consulta   para obtener los  clientes
 $querycliente=sprintf("SELECT CardName FROM clientes_cronos WHERE CardCode=%s",
  GetSQLValueString($ELEM['cve_cliente'] , "text"));
    
 $cliente = mssql_query($querycliente);
    
    $row = mssql_fetch_array($cliente);
    
    $ObjCliente = array("cve_cliente"=>$ELEM['cve_cliente'],"nom_cliente"=>$row['CardName'] );

    array_push($clienteArre, $ObjCliente);
}

$arrayPrecio = array('allelem' =>json_encode($AregloConvert),'CliS' => json_encode($clienteArre),"QEREST"=>$estqerys  );
  ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arrayPrecio); 


?>  