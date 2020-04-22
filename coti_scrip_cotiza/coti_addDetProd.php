<?php 
//// coti_addDetProd.php  
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_addDetProd.php 
 	Fecha  Creacion : 17/05/2017  
	Descripcion  :
			Insertamos  el Detalle pedido  
 *              30/05/2017 Se Modifica para  que  Pueda  Inseratar  y Hacer Update
 *               Variable control : 
 *                                  ///***keyOpc => 1  Add    keyOpc => 2 Modificar 
                                        $estUpdateOrDel =  filter_input(INPUT_POST, 'keyOpc');     
  */
require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');

///***keyOpc => 1  Add    keyOpc => 2 Modificar 
$estUpdateOrDel =  filter_input(INPUT_POST, 'keyOpc');
$ObjProdJson  = json_decode(filter_input(INPUT_POST, 'ObjProd' ));


if($estUpdateOrDel==1){
///***Genera  mos  Cadenas  de Insert 
    if($ObjProdJson->{'EnableBoni'}== 'true' )
    {
            ///*****El  producto  Tiene  Bonificaciona 
                    $String_prodInsert  = sprintf("Insert INTO pedidos.coti_detalle_cotizacion  SET  folio =%s,cve_prod =%s ,nom_prod =%s ,cant=%s,prec_prod=%s,boni_act=%s, cve_prodBoni=%s ,boni_porPre=%s,boni_porCant=%s,boni_precio=%s, boni_apli=%s,ventProd=%s",
                GetSQLValueString($ObjProdJson->{'folio'}, "int"),
                GetSQLValueString($ObjProdJson->{'cve_prod'}, "text"),
                GetSQLValueString($ObjProdJson->{'nom_prod'}, "text"),
                GetSQLValueString($ObjProdJson->{'cantsol'}, "double"),
                 GetSQLValueString($ObjProdJson->{'presol'}, "double"),
                GetSQLValueString(1, "int"),
                GetSQLValueString($ObjProdJson->{'cve_prodABoni'}, "text"),
                 GetSQLValueString($ObjProdJson->{'boniporPre'}, "double"),
                            GetSQLValueString($ObjProdJson->{'boniporCant'}, "double"),
                 GetSQLValueString($ObjProdJson->{'boniPre'}, "double"),
                 GetSQLValueString($ObjProdJson->{'boniCatotal'}, "double"),
                 GetSQLValueString($ObjProdJson->{'venta'}, "double")         
             );




    }else{
            //***El Producto  No Tiene Bonificaciona
                    $String_prodInsert  = sprintf("Insert INTO pedidos.coti_detalle_cotizacion  SET  folio =%s,cve_prod =%s ,nom_prod =%s ,cant=%s,prec_prod=%s,boni_act=%s,ventProd=%s",
                GetSQLValueString($ObjProdJson->{'folio'}, "int"),
                GetSQLValueString($ObjProdJson->{'cve_prod'}, "text"),
                GetSQLValueString($ObjProdJson->{'nom_prod'}, "text"),
                GetSQLValueString($ObjProdJson->{'cantsol'}, "double"),
                 GetSQLValueString($ObjProdJson->{'presol'}, "double"),
                GetSQLValueString(0, "int"),
                GetSQLValueString($ObjProdJson->{'venta'}, "double")
                   );


    }

 
$qery_det_pub = mysqli_query($conecta1, $String_prodInsert);

$arrayPrecio = array('restotal' => $String_prodInsert );
}
///****Opcion de  Modificacion 
if($estUpdateOrDel==2)
{
    
    ///***Validamos que no Exista el  Cliente  para el  folio 
$strxispROD = sprintf("SELECT  id  from  coti_detalle_cotizacion  where folio=%s  and  cve_prod=%s ",
        GetSQLValueString($ObjProdJson->{'folio'}, "int"),GetSQLValueString($ObjProdJson->{'cve_prod'}, "text"));

$qerExispROD= mysqli_query($conecta1, $strxispROD);

$fetExispROD = mysqli_fetch_array($qerExispROD);
    
      if($ObjProdJson->{'EnableBoni'}== 'true' )
    {
            ///*****El  producto  Tiene  Bonificaciona 
                    $String_prodInsert  = sprintf("UPDATE  pedidos.coti_detalle_cotizacion  SET  cve_prod =%s ,nom_prod =%s ,cant=%s,prec_prod=%s,boni_act=%s, cve_prodBoni=%s ,boni_porPre=%s,boni_porCant=%s,boni_precio=%s, boni_apli=%s,ventProd=%s   WHERE id=%s",
                
                GetSQLValueString($ObjProdJson->{'cve_prod'}, "text"),
                GetSQLValueString($ObjProdJson->{'nom_prod'}, "text"),
                GetSQLValueString($ObjProdJson->{'cantsol'}, "double"),
                 GetSQLValueString($ObjProdJson->{'presol'}, "double"),
                GetSQLValueString(1, "int"),
                GetSQLValueString($ObjProdJson->{'cve_prodABoni'}, "text"),
                 GetSQLValueString($ObjProdJson->{'boniporPre'}, "double"),
                            GetSQLValueString($ObjProdJson->{'boniporCant'}, "double"),
                 GetSQLValueString($ObjProdJson->{'boniPre'}, "double"),
                 GetSQLValueString($ObjProdJson->{'boniCatotal'}, "double"),
                 GetSQLValueString($ObjProdJson->{'venta'}, "double"),
                GetSQLValueString( $fetExispROD['id'], "int")         
             );




    }else{
            //***El Producto  No Tiene Bonificaciona
                    $String_prodInsert  = sprintf("UPDATE  pedidos.coti_detalle_cotizacion  SET  cve_prod =%s ,nom_prod =%s ,cant=%s,prec_prod=%s,boni_act=%s,ventProd=%s WHERE id=%s ",
              
                GetSQLValueString($ObjProdJson->{'cve_prod'}, "text"),
                GetSQLValueString($ObjProdJson->{'nom_prod'}, "text"),
                GetSQLValueString($ObjProdJson->{'cantsol'}, "double"),
                 GetSQLValueString($ObjProdJson->{'presol'}, "double"),
                GetSQLValueString(0, "int"),
                GetSQLValueString($ObjProdJson->{'venta'}, "double"),
                GetSQLValueString( $fetExispROD['id'], "int"));


    }

    
    $qery_det_pub = mysqli_query($conecta1, $String_prodInsert);
    
}


///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arrayPrecio); 


?>  