<?php
///*****coti_UpdEstcot.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_UpdEstcot.php 
 	Fecha  Creacion :  18/07/2017
	Descripcion  : Script  para Modificar los  estatus
 * 

  */
require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');
/**Varible Control  de  Errores 
    ////Estados !!! 
 *  $ERRORsCRIPT = 0 => Entiendase  => Sin Ningun ERROR  DURENTE LA EJECUCION Del Escript  fecha_auto_Ic=Now()
 *  $ERRORsCRIPT = 1  => Entiendase => Error en Obtener  ID
 *  $ERRORsCRIPT = 2  => Entiendase => Error en  Update    
 */
$ERRORsCRIPT = 0;  
//****Obtenemos la  Informacion   
$FOLIO  = filter_input(INPUT_POST,'FL' );
$EST  =  filter_input(INPUT_POST, 'TYPEST');
$ARRmain  =json_decode( filter_input(INPUT_POST, 'INFOmain'));
$TlsFin = json_decode( filter_input(INPUT_POST, 'TLSfin'));
$BoniCTOinvTls =  filter_input(INPUT_POST, 'tlsCtoBonInven');
$BoniCTOproyTls=  filter_input(INPUT_POST, 'tlsCtoBonPro');

////***Modificamos el  Estatus 
$Obj_TOTALES_INV = json_decode($TlsFin->Inv);    
$Obj_TOTALES_PROY = json_decode($TlsFin->Proy); 

if($EST == 1 ||$EST == 2  ){
    ////***Realizamos  la Modificacion del Estatus  
           $strUpEST = sprintf("update coti_encabeca_cotizacion set   fecha_auto_Ic=Now(),estatus_Ic =%s,tlsOprVt_inv=%s,tlsOprCts_inv=%s,tlsOprCMG_inv=%s,tlsOprCMGpor_inv=%s,tlsOprVt_proy=%s,tlsOprCts_proy=%s,tlsOprCMG_proy=%s,tlsOprCMGpor_proy=%s,tlsCtoBonInven=%s,tlsCtoBonPro=%s  where folio=%s ",
                              GetSQLValueString($EST, "int"),
                       ////***Agregamos Los  Totales Finales Inventario  
                              GetSQLValueString($Obj_TOTALES_INV->OprInVents, "double"),
                              GetSQLValueString($Obj_TOTALES_INV->OprInCosts, "double"),
                              GetSQLValueString($Obj_TOTALES_INV->OprInCMG, "double"),
                              GetSQLValueString($Obj_TOTALES_INV->OprInCMGPor, "int"),
                       ////***Agregamos Los  Totales Finales Operacion 
                              GetSQLValueString($Obj_TOTALES_PROY->OprPrVents, "double"),
                              GetSQLValueString($Obj_TOTALES_PROY->OprPrCosts, "double"),
                              GetSQLValueString($Obj_TOTALES_PROY->OprPrCMG, "double"),
                              GetSQLValueString($Obj_TOTALES_PROY->OprPrCMGPor, "int"),
                       ////*****Agregamos los  Totales   de  Bonificacion  Por  Invnetario  Y  Proyectado 
                              GetSQLValueString($BoniCTOinvTls, "double"),
                              GetSQLValueString($BoniCTOproyTls, "double"),
                              GetSQLValueString($FOLIO, "int")        
                   );
}
if($EST == 3  ){
    
    $CmIC = filter_input(INPUT_POST,'COME');
    ////***Realizamos  la Modificacion del Estatus  
           $strUpEST = sprintf("update coti_encabeca_cotizacion set fecha_auto_Ic=Now(),estatus_Ic =%s,tlsOprVt_inv=%s,tlsOprCts_inv=%s,tlsOprCMG_inv=%s,tlsOprCMGpor_inv=%s,tlsOprVt_proy=%s,tlsOprCts_proy=%s,tlsOprCMG_proy=%s,tlsOprCMGpor_proy=%s,tlsCtoBonInven=%s,tlsCtoBonPro=%s,coment_Ic=%s  where folio=%s ",
                              GetSQLValueString($EST, "int"),
                       ////***Agregamos Los  Totales Finales Inventario  
                              GetSQLValueString($Obj_TOTALES_INV->OprInVents, "double"),
                              GetSQLValueString($Obj_TOTALES_INV->OprInCosts, "double"),
                              GetSQLValueString($Obj_TOTALES_INV->OprInCMG, "double"),
                              GetSQLValueString($Obj_TOTALES_INV->OprInCMGPor, "int"),
                       ////***Agregamos Los  Totales Finales Operacion 
                              GetSQLValueString($Obj_TOTALES_PROY->OprPrVents, "double"),
                              GetSQLValueString($Obj_TOTALES_PROY->OprPrCosts, "double"),
                              GetSQLValueString($Obj_TOTALES_PROY->OprPrCMG, "double"),
                              GetSQLValueString($Obj_TOTALES_PROY->OprPrCMGPor, "int"),
                       ////*****Agregamos los  Totales   de  Bonificacion  Por  Invnetario  Y  Proyectado 
                              GetSQLValueString($BoniCTOinvTls, "double"),
                              GetSQLValueString($BoniCTOproyTls, "double"),
                       ////*****Agregamos Comentarios  Jefe Inteligencia Comercial         
                              GetSQLValueString($CmIC, "text"),
                              GetSQLValueString($FOLIO, "int")        
                   );
}

           
 if(!mysqli_query($conecta1, $strUpEST))
{
    $ERRORsCRIPT = 3 ;///eRROR Agregando el  Update Estatus 
 
}else {           

    foreach ( $ARRmain as $obj) {
        ////***Obtenemos El  Id 
        $strGetID  = sprintf("select  id  from  coti_detalle_cotizacion  where  cve_prod=%s and folio=%s",
                                GetSQLValueString($obj->{'cve_prod'}, "text"),
                                GetSQLValueString($FOLIO, "int"));
        $qeryGetid = mysqli_query($conecta1, $strGetID);
        if(!$qeryGetid)
        {
            $ERRORsCRIPT = 1 ;///eRROR en los  Id 
            break;
        }else{
          /////**+Obtenemos los Id    
          $fetID = mysqli_fetch_array($qeryGetid);
          ////**Generamos la  cadena 
          $strUpEl = sprintf("UPDATE coti_detalle_cotizacion set cto_proy=%s ,cto_inv=%s,boctoInv=%s,boctoProy=%s where id=%s",
                                  GetSQLValueString($obj->{'cost_proy'}, "double"),
                                  GetSQLValueString($obj->{'cost_inv'}, "double"),
                                  GetSQLValueString($obj->{'bo_cto_inv'}, "double"),
                                  GetSQLValueString($obj->{'boCto_proy'}, "double"),
                                  GetSQLValueString($fetID['id'], "int")
                     );
           ////***Ejecutamos el Update 
           if(!mysqli_query($conecta1, $strUpEl))
           {
               $ERRORsCRIPT = 2 ;///eRROR Agregando el  Update 
            break;
           }
        }    
    }

}

$arreresl = array(  "RES" =>"HOLA Escript :D !!!", "ERRORES"=>$ERRORsCRIPT );
  ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arreresl); 
?>