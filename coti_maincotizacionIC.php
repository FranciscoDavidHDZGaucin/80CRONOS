<?php

/////***coti_maincotizacionIC.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_maincotizacionIC.php
 	Fecha  Creacion : 16/06/2017
	Descripcion  : 
 *              Escrip  Diseñado  para Mostrar los calculos Principales
 *              para   la  Cotizacion    BASADOS EN  EXCEL 
 *      Modificacion : 
 *  
 *          15/07/2017  
 *  
 *                  var  Obj =  new Producto(
                                                                    AregloProd[i].cveProd,
                                                                    AregloProd[i].nomProd,
                                                                    parseFloat(AregloProd[i].cantSol),
                                                                    parseFloat(AregloProd[i].preSol),
                                                                    AregloProd[i].costInv,
                                                                    AregloProd[i].CtoProy,
                                                                    AregloProd[i].boniEst,
                                                                    AregloProd[i].cveProdBoni,
                                                                    AregloProd[i].NomProdBoni,
                                                                    AregloProd[i].boniPorCant,
                                                                    AregloProd[i].boniPreSol,
                                                                    AregloProd[i].VTN,
                                                                    AregloProd[i].LimDc,
                                                                    AregloProd[i].boniPorPre,
                                                                    AregloProd[i].boniPreAp);
              17/07/2017    **********                                                     
                                              
 *              07/08/2017  $TPePg => 1 => Main para Modificar    Inteligencia  COmercial
 *                          $TPePg => 2 => Modo  Historial  para Inteligencia  COmercial 
 *                          $TPePg => 3 => Modo  Historial   para  Direccion Comercial 
 * 
  */
$FOLIOMASTER =filter_input(INPUT_POST,'FOLIO');
///**+Obtenemos  el  Tipo  De Vista  Solicitada  para el  Usuario  
$TPePg = filter_input(INPUT_GET, 'TyPg');
////**Inicio De Session 
session_start();
        
 if($TPePg==2 ||$TPePg==1){
     ///****Cabecera Cronos
        require_once('header_inteligencia.php');
 }
 if($TPePg==3){
        require_once 'header_direccion.php';
 }
require_once('Connections/conecta1.php');
////***Conexion   Sap 
require_once('conexion_sap/sap.php');
///***Seleccion de la Bd 
/// mssql_select_db("AGROVERSA");
///*****        
require_once('formato_datos.php');


$EstApli = 1;
////*****Obtenemos los  Comentarios del  Gestor 

$strGetComent= sprintf("select com_agent from  pedidos.coti_encabeca_cotizacion   where  folio =%s ",GetSQLValueString($FOLIOMASTER, "int"));
$qeryComen = mysqli_query($conecta1, $strGetComent);
$fetComent = mysqli_fetch_array($qeryComen);

/////******
if($EstApli ==1 ){
$strGetELMS =  sprintf("select  * from pedidos.coti_detalle_cotizacion where folio =%s ",
		GetSQLValueString($FOLIOMASTER, "int"));
}
$qery_det_pub = mysqli_query($conecta1,$strGetELMS);

$AregloConvert  =  Array() ;
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
 GetSQLValueString($FOLIOMASTER, "int"));
////**Realizamos el Qery
$qeryCliente = mysqli_query($conecta1, $strGeClient);
////**+Numero de Elementos 
$NuCl = mysqli_num_rows($qeryCliente);
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
////****Areglo de  Objetos  
$ArregloMain = json_encode($AregloConvert); 


/*
$arrayPrecio = array('allelem' =>json_encode($AregloConvert),'CliS' => json_encode($clienteArre),"QEREST"=>$estqerys  );
  ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arrayPrecio); 
*/
?> 
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>

<script src="coti_scrip_cotiza/coti_Arreglomain.js"></script>
<script src="coti_scrip_cotiza/coti_ClassPrd.js"></script>
<script type="text/javascript">
        const  FOLIO =  <?php echo filter_input(INPUT_POST,'FOLIO'); ?>;
        const  PagReturn=  <?php 
                                    if($TPePg==2 ||$TPePg==1){echo "'http://192.168.101.5/sistemas/cronos/coti_estcontizacionJIC.php?TypePg=".$TPePg."'";}
                                    if($TPePg==3){echo "'http://192.168.101.5/sistemas/cronos/coti_estcotizaDC.php?TypePg=2'";}
        
                                ?>;
         /////***Areglo en Bruto 
         var   ArgBrutMain = <?php echo $ArregloMain ; ?>;
        //////****Arreglo Principal
         var  ArglMain = new Array() 
        ///***Varible para  almacenar el  Costo  Total de una  Bonificacion  por Inventario 
        var  tlsCtoBonInven = 0; 
        ///***Varible para  almacenar el  Costo  Total de una  Bonificacion  por Proyectado  
        var  tlsCtoBonPro = 0 ; 
        ////***Pagina de  Retorno 
        
         
         
        /////***Ciclo para Geenrar  Arreglo Completo
        for(var  i in  ArgBrutMain )
        {
            var  Obj =  new Producto(
                                    ArgBrutMain[i].cveProd,
                                    ArgBrutMain[i].nomProd,
                                    parseFloat(ArgBrutMain[i].cantSol),
                                    parseFloat(ArgBrutMain[i].preSol),
                                    ArgBrutMain[i].costInv,
                                    ArgBrutMain[i].CtoProy,
                                    ArgBrutMain[i].boniEst,
                                    ArgBrutMain[i].cveProdBoni,
                                    ArgBrutMain[i].NomProdBoni,
                                    ArgBrutMain[i].boniPorCant,
                                    ArgBrutMain[i].boniPreSol,
                                    ArgBrutMain[i].VTN,
                                    ArgBrutMain[i].LimDc,
                                    ArgBrutMain[i].boniPorPre,
                                    ArgBrutMain[i].boniPreAp);
             ArglMain.push(Obj);  
        }
        ///console.log(FOLIO)
         var GenTable; 
        /////*Objeto  Totales Inventario  
        function ObgTls(OprInVents, OprInCosts,OprInCMG,OprInCMGPor  ) 
       {   this.OprInVents=OprInVents;this.OprInCosts =OprInCosts; this.OprInCMG=OprInCMG ;this.OprInCMGPor=OprInCMGPor  } 

        /////*Objeto  Totales Proyectado   
        function ObgTlsProy(OprPrVents, OprPrCosts,OprPrCMG,OprPrCMGPor  ) 
       {   this.OprPrVents=OprPrVents;this.OprPrCosts =OprPrCosts; this.OprPrCMG=OprPrCMG ;this.OprPrCMGPor=OprPrCMGPor  } 

       
    /////*** ////***Objeto Totales Operacion  Inventario
   function  OPERTtlsInv (ArglM)
   {
       var  OprInVents= 0 ;
       var  OprInCosts= 0 ;
       var  OprInCMG=0 ;
       var  OprInCMGPor=0 ;

        for(var  i  in ArglM ){
        /////***Calculamos  Operacion por   Inventario  Ventas    
         OprInVents += parseFloat( GetFlot2Deci((ArglM[i].cant_sol*ArglM[i].prec_sol)));
         /////****Calculamos  Operacion por Costos
         OprInCosts  += parseFloat(GetFlot2Deci(ArglM[i].cant_sol *ArglM[i].cost_inv));

       }
       ////Calculamos la CMg   
       OprInCMG = parseFloat((OprInVents-OprInCosts).toFixed(2)) ;
       ////Calculamos CMG  % 
       var  resDiv = Math.round(parseFloat( ( (OprInCMG /OprInVents)*100   ).toFixed(2)))

       OprInCMGPor =resDiv ///Math.round(resDiv);

       //////********************

      var  ObjTOTALES  =  new   ObgTls(OprInVents, GetFlot2Deci(OprInCosts),OprInCMG,OprInCMGPor  ) ;           
      return  ObjTOTALES;
   }
  /////****Objeto Totales Operacion   Proyectado 
  function  OPERTtlsProy(ArglM)
   {
       var  OprPrVents= 0 ;
       var  OprPrCosts= 0 ;
       var  OprPrCMG=0 ;
       var  OprPrCMGPor=0 ;

        for(var  i  in ArglM ){
        /////***Calculamos  Operacion por   Inventario  Ventas    
         OprPrVents += parseFloat( GetFlot2Deci((ArglM[i].Vta)));
         /////****Calculamos  Operacion por Costos
         OprPrCosts  += parseFloat(GetFlot2Deci(ArglM[i].CST));

       }
       ////Calculamos la CMg   
       OprPrCMG = parseFloat((OprPrVents-OprPrCosts).toFixed(2)) ;
       ////Calculamos CMG  % 
       var  resDiv = Math.round(parseFloat( ( (OprPrCMG /OprPrVents)*100   ).toFixed(2)))

       OprPrCMGPor =resDiv ///Math.round(resDiv);

       //////********************

      var  ObjTOTALES  =  new   ObgTlsProy(OprPrVents, GetFlot2Deci(OprPrCosts),OprPrCMG,OprPrCMGPor  ) ;           
      return  ObjTOTALES;
   }
  ///*****Funcion para Obtener las  Operaciones  Finales  
  function   Get_OperFinal_InvProy() 
  {
     /////***Obtenemos  los  totales   
      var ObjInvnt = OPERTtlsInv (ArglMain);
      var ObjProy  =  OPERTtlsProy(ArglMain)
     ////***Sumamos los  Costos Respectivamenete 
      var SumCostoProy  =  ObjProy.OprPrCosts + tlsCtoBonPro ;
      var SumCostoInven = tlsCtoBonInven + ObjInvnt.OprInCosts ;
      ////****Obtenemos   cG  Y CMG%  pROYECTADA    
      var cmgfinalProy  =  GetFlot2Deci( (ObjProy.OprPrVents - SumCostoProy))
      var  cmgfinCMGPORpROY  = Math.round(parseFloat( ( (cmgfinalProy /ObjProy.OprPrVents)*100   ).toFixed(2)))
      ////****Obtenemos  CMG   Y  CMG%  Inventario 
      var cmgfinalInv  =  GetFlot2Deci( (ObjInvnt.OprInVents - SumCostoInven))
      var  cmgfinCMGPORInv  = Math.round(parseFloat( ( (cmgfinalInv /ObjInvnt.OprInVents)*100   ).toFixed(2)))
      
      ////***Generamos  Objeto  Final de  Totales   
      var   OprFinlProyectada =  new  ObgTlsProy(ObjProy.OprPrVents,SumCostoProy,cmgfinalProy,cmgfinCMGPORpROY);
      var   OprFinlInventario =  new  ObgTls(ObjInvnt.OprInVents,SumCostoInven,cmgfinalInv,cmgfinCMGPORInv);
      
      return  ObjFINL ={"Inv" :OprFinlInventario ,"Proy":OprFinlProyectada} 
   }
    
 ///****Funcion para Obtener  Un Elemento  FLOTAT  CON DOSS  DeCIAMELES
 function  GetFlot2Deci(NUM)
 {
     var elemFix = (NUM).toFixed(2)

    return   parseFloat(elemFix); 
 }
 ////***Funcion  para Obtener el  Producto con Bonificacion a  Recalcular 
  function   GetObjeWhitBoni(CVEPRO)
  {
    var  Obje = null ; 
    for(var  i in ArglMain )
    {
       if(ArglMain[i].boni_act.localeCompare("true") == 0   && ArglMain[i].cve_prod_bo.localeCompare(CVEPRO) == 0 )
       {
           Obje = ArglMain[i];
           break; 
       }   
     }
   return   Obje  
  }
  ///****Funcion para  Calcular   Las  Bonificaciones
function bonicalculate(OBJE) {
         ////****Obteneos las  variables
         var  cantSol =   OBJE.cant_sol ; // $("#cantSol").val()    ;///Cantidad  Solicitada  Bonificacion
         var  bonixCant = parseFloat(OBJE.cant_bo) ; //    $("#boniCant").val() ;///Bonificacion por Cantidad 
         var   bonixpre = parseFloat(OBJE.boni_por_pre);  // $("#boniPORpre").val();///Bonificacion por Precio 
         var  preBoni = parseFloat(OBJE.boni_prec); //  $("#boniPreci").val();///Precio de la  Bonificacion 

         var cantidad = 0;
        /// $("#boniCantTotal").val(0)

        if( bonixpre !=0 && ( bonixCant ==0.1 ||bonixCant <=0)){
              cantidad =( cantSol*bonixpre)/preBoni;
              //// console.log("Bonifica por Precio  A Aplicar :"+cantidad)
        }

        if( bonixCant !=0 && (  bonixpre==0.1 || bonixpre <= 0)){
              cantidad = (cantSol*bonixCant);
              //// console.log("Bonifica por Cantidad A Aplicar :"+cantidad)
        }
        var  cantRedon = parseFloat(cantidad.toFixed(2));
     return  cantRedon ; 
}
   ////*****
   // Funcion para  Modificar la  Bonificacion a Aplicar 
   function   Update_BoniXupdate(CVEPRO,cant)
  {
    for(var  i in ArglMain )
    {
       if(ArglMain[i].boni_act.localeCompare("true") == 0 && ArglMain[i].cve_prod_bo.localeCompare(CVEPRO) == 0 )
       {
            ArglMain[i].boniAplicar=cant;
           break; 
       }   
     }
 
  }
  ////***Funcion  para   Retonar  la Tabla Operaciones 
  function  Get_Operaciones()
  {
      //////***Mostramos los  Totales  Inventario  
       let Objt = OPERTtlsInv(ArglMain);
       ////***Mostramos   los  Totales Proyectado 
       let  ObjPry  =OPERTtlsProy(ArglMain)
     var  tbtls =    "<tr><td><strong>Operacion Preyectada</strong></td><td  class='qanti'>"+currency(ObjPry.OprPrVents, 2, [',', ",", '.'])+"</td><td class='qanti'>"+
                     currency(ObjPry.OprPrCosts, 2, [',', ",", '.'])+"</td><td class='qanti'>"+currency(ObjPry.OprPrCMG, 2, [',', ",", '.'])+
                     "</td><td class='qanti'>"+ObjPry.OprPrCMGPor+"%</td></tr>"+
                     "<tr><td><strong>Operacion Inventario</strong></td><td class='qanti'>"+currency(Objt.OprInVents, 2, [',', ",", '.'])+"</td><td class='qanti'>"+
                     currency(Objt.OprInCosts, 2, [',', ",", '.'])+"</td><td class='qanti'>"+currency(Objt.OprInCMG, 2, [',', ",", '.'])+
                     "</td><td class='qanti'>"+Objt.OprInCMGPor+"%</td></tr>"
                 
      return tbtls;
      
  }
  ////***Funcion para  Retornar la Tabla de Operaciones  Finales
  function   Get_Tb_FinalesTotales()
  {
      var  ObFINAL = Get_OperFinal_InvProy(); 
        var tbtlsFINAL  = "<tr><td><strong>Operacion Preyectada Final</strong></td><td  class='qanti'>"+currency(ObFINAL.Proy.OprPrVents, 2, [',', ",", '.'])+"</td><td class='qanti'>"+
                     currency(ObFINAL.Proy.OprPrCosts, 2, [',', ",", '.'])+"</td><td class='qanti'>"+currency(ObFINAL.Proy.OprPrCMG, 2, [',', ",", '.'])+
                     "</td><td class='qanti'>"+ObFINAL.Proy.OprPrCMGPor+"%</td></tr>"+
                     "<tr><td><strong>Operacion Inventario Final</strong></td><td class='qanti'>"+currency(ObFINAL.Inv.OprInVents, 2, [',', ",", '.'])+"</td><td class='qanti'>"+
                     currency(ObFINAL.Inv.OprInCosts, 2, [',', ",", '.'])+"</td><td class='qanti'>"+currency(ObFINAL.Inv.OprInCMG, 2, [',', ",", '.'])+
                     "</td><td class='qanti'>"+ObFINAL.Inv.OprInCMGPor+"%</td></tr>"; 
      return   tbtlsFINAL;      
  }
  /////***Funcion  para  Generar la  Tabla  Principal  
  function GenTBPrin()
  {
        var  tableTr ="";
        var  tableBoni ="";
       for(var  i  in ArglMain )
       {
           <?php 
           /////***Agregamos Codigo  CON Inpust  si es  para Modificar
                if($TPePg==1){ ?>
           ///***Generamos Rows con los Productos 
           tableTr += "<tr><td class='ptelems'>"+ArglMain[i].cve_prod+"</td><td class='Destb'>"+ ArglMain[i].nom_prod+"</td><td class='qanti'><input  type='number' class='CSL"+ArglMain[i].cve_prod+" form-control' value='"
                      + ArglMain[i].cant_sol+"'></td><td class='qanti'><input step= 'any'  type='number' class='PSL"+ArglMain[i].cve_prod+" form-control' value='"+ ArglMain[i].prec_sol+"' CVP='"+ArglMain[i].cve_prod+"'  ></td><td  class='casGen'>"+ConverMoney(ArglMain[i].cost_inv)
                      +"</td><td  class='casGen'>"+ConverMoney(ArglMain[i].cost_proy)+"</td><td  class='cmgporInvt'>"+ArglMain[i].CMG_POR_INV+"%</td><td class='casGen'>"+ currency(ArglMain[i].Vta, 2, [',', ",", '.']) +"</td><td  class='casGen'>"+currency(ArglMain[i].CST, 2, [',', ",", '.'])+"</td><td class='casGen'>"+
                             currency(ArglMain[i].CMG, 2, [',', ",", '.'])+"</td><td class='cmgporInvt'>"+ArglMain[i].CMG_POR+"%</td><td class='cmgporInvt'>"+ArglMain[i].limite_dc+"%</td></tr>";
           <?php  } 
                ////**+Quitamos  los Inputs si  Es  para el Historial 
               if($TPePg==2||$TPePg==3){ ?> 
                          tableTr += "<tr><td class='ptelems'>"+ArglMain[i].cve_prod+"</td><td class='Destb'>"+ ArglMain[i].nom_prod+"</td><td class='qanti'>"
                      + ArglMain[i].cant_sol+"</td><td class='qanti'>"+ ArglMain[i].prec_sol+"'</td><td  class='casGen'>"+ConverMoney(ArglMain[i].cost_inv)
                      +"</td><td  class='casGen'>"+ConverMoney(ArglMain[i].cost_proy)+"</td><td  class='cmgporInvt'>"+ArglMain[i].CMG_POR_INV+"%</td><td class='casGen'>"+ currency(ArglMain[i].Vta, 2, [',', ",", '.']) +"</td><td  class='casGen'>"+currency(ArglMain[i].CST, 2, [',', ",", '.'])+"</td><td class='casGen'>"+
                             currency(ArglMain[i].CMG, 2, [',', ",", '.'])+"</td><td class='cmgporInvt'>"+ArglMain[i].CMG_POR+"%</td><td class='cmgporInvt'>"+ArglMain[i].limite_dc+"%</td></tr>";
           
                   
                   
                   
            <?php  } ?>               
            ////****Generar Tabla de  Bonificaciones 
           if(ArglMain[i].boni_act == "true")
           {
               tableBoni+= "<tr><td class='ptelems'>"+ArglMain[i].cve_prod_bo+"</td><td class='Destb'>"+ ArglMain[i].nom_prod_bo+
               "</td><td class='BONIAPL"+ArglMain[i].cve_prod_bo+"  qanti'>"+ArglMain[i].boniAplicar+"</td><td class='qanti'>"+currency(ArglMain[i].bo_cto_inv, 2, [',', ",", '.'])+
               "</td><td class='qanti'>"+currency(ArglMain[i].boCto_proy, 2, [',', ",", '.'])+"</td></tr>";
               ////***Obtenemos los  totales  de los Costos  con Bonificacion
               tlsCtoBonInven += ArglMain[i].bo_cto_inv;  
               tlsCtoBonPro +=  ArglMain[i].boCto_proy; 
           }


        }
        ////***Agregamos  los  costos  a la  tabla
         tableBoni+= "<tr><td  class='ptelems'></td><td class='Destb'></td><td class='qanti'><strong>Totales:</strong></td><td class='qanti'>"+currency(tlsCtoBonInven, 2, [',', ",", '.']) +"</td><td class='qanti'>"+ currency(tlsCtoBonPro, 2, [',', ",", '.'])+"</td></tr>"; 
       
      return   Tab =  { "TablaMain" : tableTr , "TablaBoni" : tableBoni  }; 
      
  }
  
 ////*****Convertir a  Formato  Moneda 
function currency(value, decimals, separators) {
    decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
    separators = separators || ['.', "'", ','];
    var number = (parseFloat(value) || 0).toFixed(decimals);
    if (number.length <= (4 + decimals))
        return number.replace('.', separators[separators.length - 1]);
    var parts = number.split(/[-.]/);
    value = parts[parts.length > 1 ? parts.length - 2 : 0];
    var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
        separators[separators.length - 1] + parts[parts.length - 1] : '');
    var start = value.length - 6;
    var idx = 0;
    while (start > -3) {
        result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start))
            + separators[idx] + result;
        idx = (++idx) % 2;
        start -= 3;
    }
    return (parts.length == 3 ? '-' : '') + result;
}
</script> 
<script type="text/javascript">
$(document).ready(function(){
    $("#BakEst").attr("href",PagReturn);
///******************   **********************************************************************************
        ////**Mandamos  Imprimir a la  Tabla  Contenedor
        var TblMB  =  GenTBPrin();
        $("#tbCont").html(TblMB.TablaMain);
       ////***Tabla  Bonificacion 
        $("#boniapli").html(TblMB.TablaBoni); 
       /////////***Agregamos  la Resultados  Finales 
       $("#idtlsfin").html(Get_Tb_FinalesTotales());
       /////******Agregamos  Resultado de  Operaciones
       $("#idtls").html(Get_Operaciones());
   
////*********************************************************************************************************************************************
        $(document).on("click",".CALU",function(){
         
            var  tableTr ="";
          
           for(var  i  in ArglMain ){
                 ////cANTIDAD sOLICITAD 
                var classCant =  ".CSL"+ArglMain[i].cve_prod;
                ////***Precio  Solicitado 
                var  classPresol = ".PSL"+ArglMain[i].cve_prod;
                
                 ArglMain[i].UpdateProd($(classCant).val(), $(classPresol).val())
                    
                tableTr += "<tr><td class='ptelems'>"+ArglMain[i].ClaveProd()+"</td><td class='Destb'>"+ ArglMain[i].nom_prod+"</td><td class='qanti'><input  type='number' class='CSL"+ArglMain[i].ClaveProd()+" form-control' value='"
                           + ArglMain[i].cant_sol+"'></td><td class='qanti' ><input   type='number' class='PSL"+ArglMain[i].ClaveProd()+" form-control' value='"+ ArglMain[i].prec_sol+"' CVP='"+ArglMain[i].ClaveProd()+"'  ></td><td class='casGen'>"+ConverMoney(ArglMain[i].cost_inv)
                                               +"</td><td class='casGen'>"+ConverMoney(ArglMain[i].cost_proy)+"</td><td  class='cmgporInvt'>"+ArglMain[i].CMG_POR_INV+"%</td><td  class='casGen'>"+currency(ArglMain[i].Vta, 2, [',', ",", '.']) +"</td><td  class='casGen'>"+currency(ArglMain[i].CST, 2, [',', ",", '.'])+"</td><td  class='casGen' >"+
                                                     currency(ArglMain[i].CMG, 2, [',', ",", '.'])+"</td><td  class='cmgporInvt'>"+ArglMain[i].CMG_POR+"%</td><td class='cmgporInvt'>"+ArglMain[i].limite_dc+"%</td></tr>";
          
          }
        /////******Agregamos  Resultado de  Operaciones
        $("#idtls").html(Get_Operaciones());
         /////////***Agregamos  la Resultados  Finales 
       $("#idtlsfin").html(Get_Tb_FinalesTotales());
        $("#tbCont").html(tableTr);
           
      });
 /////*********************************************************************************************************************
  $(document).on("keyup",".BOPRE",function(){
     ///***Desabilitat  Input
     var  genIDcatbo = "#CATBO"+$(this).attr('CVE');
     $(genIDcatbo).attr("disabled",true);
       $(genIDcatbo).val("0.0")
     ////***********************************************************
      var genId ="#BOPRE"+$(this).attr('CVE');
      var  boPre = $(genId).val();
      CalcularBoni("BOPRE",$(this).attr('CVE'),boPre);
      
 
     
 });
 /////*********************************************************************************************************************
 /////*********************************************************************************************************************
  $(document).on("keyup",".CATBO",function(){
     ///***Desabilitat  Input
     var  genIDcatbo = "#BOPRE"+$(this).attr('CVE');
     $(genIDcatbo).attr("disabled",true);
       $(genIDcatbo).val("0.0")
     ////***********************************************************
      var genId ="#CATBO"+$(this).attr('CVE');
      var  boPre = $(genId).val();
      CalcularBoni("CATBO",$(this).attr('CVE'),boPre);
    
 });
 $("#btnEst").click(function(){
     
     $("#nfMen").text("Esta a Punto de modificar el Folio:"+FOLIO ) ;
     $("#btnNote").text("Nota Si Usted Define Cualquier Tipo de Estatus NO Podra Modificar la Cotizacion.");
     $("#btnNote").css("color","red");
     $("#BtnOpcio").attr("hidden",false);
     $("#BackEleTx").attr("hidden",true);
     $("#ModEst").modal("show");
 });
 $(document).on("click",".btnEstatus",function(){
      var  typeEst =  $(this).val(); 
      var  ObFinal  = Get_OperFinal_InvProy() ;   
      ///ObjFINL ={"Inv" :OprFinlInventario ,"Proy":OprFinlProyectada}  JSON.stringify(OBJOPERFINA)
      var  OBJOPERFINA  = {"Inv":JSON.stringify(ObFinal.Inv ),"Proy":JSON.stringify(ObFinal.Proy ) } 
     ////**Obtenemos el  Comentario DEL jEFe inteligencia  
     if(typeEst == 3)
    {
         var   coment =  $("#txtComeJI").val();
         
      if(coment != null  && coment != "" )
      {
           //////****Mandamos  Modificar el  Estatus 
      ////****Agregamos al cliente 
       $.ajax({
                        type:'POST',
                        url: 'coti_scrip_cotiza/coti_UpdEstcot.php',
                        data:{"FL":FOLIO,"TYPEST":typeEst,"COME":coment ,"INFOmain":JSON.stringify(ArglMain) ,"TLSfin":JSON.stringify(OBJOPERFINA) ,"tlsCtoBonInven":tlsCtoBonInven ,"tlsCtoBonPro":tlsCtoBonPro }, 
                        success: function (datos) {
                                if(datos.ERRORES==0)
                                {
                                   ////**Exito  en Update  
                                   window.location.href=PagReturn;
                                    
                                }else {
                                   ///***Error  Update 
                                   $(".btnEstatus").remove();
                                   $("#btnNote").text("Lo Sentimos NO se Realizaron los Cambios  Por favor Inténtelo después");
                                }    
                                    
                                    
                         }
             });
              $("#BackEleTx").attr("hidden",true);
          
      }else {
           $("#btnNote").text("Error No Se Puede Regresar La Cotizacion por comentario vacio");
            $("#btnNote").css("color","red");
      } 
    }else {  ////*Envio  de  Estatus Normal  para los  demas  Estatus    
     //////****Mandamos  Modificar el  Estatus 
      ////****Agregamos al cliente 
       $.ajax({
                        type:'POST',
                        url: 'coti_scrip_cotiza/coti_UpdEstcot.php',
                        data:{"FL":FOLIO,"TYPEST":typeEst ,"INFOmain":JSON.stringify(ArglMain) ,"TLSfin":JSON.stringify(OBJOPERFINA) ,"tlsCtoBonInven":tlsCtoBonInven ,"tlsCtoBonPro":tlsCtoBonPro }, 
                        success: function (datos) {
                                if(datos.ERRORES==0)
                                {
                                   ////**Exito  en Update  
                                   window.location.href=PagReturn;
                                    
                                }else {
                                   ///***Error  Update 
                                   $(".btnEstatus").remove();
                                   $("#btnNote").text("Lo Sentimos NO se Realizaron los Cambios  Por favor Inténtelo después");
                                }    
                                    
                                    
                         }
             });
       $("#BackEleTx").attr("hidden",true);
   }
 })
 
 
 /****Funcion para  Modificar El Objeto En la  Opcion de  Bonificacion  Por Cantidad 
 
 Entiendase que   typeBoni => 0 Bonificacion Por Precio  
                  typeBoni => 1 Bonificacion Por Cantidad  
 
 */
 function   Update_BoniX_PRECIO_CANTIDAD(typeBoni,CVEPRO,VALUE)
 {
  
    var  Obje = null ; 
    for(var  i in ArglMain )
    {
       if(ArglMain[i].boni_act.localeCompare("true") == 0   && ArglMain[i].cve_prod_bo.localeCompare(CVEPRO) == 0 )
       {
          if(typeBoni == 0) { /////*** Modificaos por  Precio   
           ArglMain[i].boni_por_pre =VALUE;
           ArglMain[i].cant_bo = 0.0;
           break;
          }
          if(typeBoni == 1) { ////*** Modificamos  por cantidad  
           ArglMain[i].cant_bo =VALUE ;
           ArglMain[i].boni_por_pre =0;
           break;
          }
            
       }   
     }
  }

 /////*********************************************************************************************************************
 function  CalcularBoni(typeBoni,CVER ,value)
 {
     ///***Generamos la  Posicion para  la Bonificacion a Aplicar
     var classPositApli  = ".BONIAPL"+CVER;
     var  cant= 00; 
   ////***Comaparamos las  Bonificaciones Por Precio 
   if(typeBoni.localeCompare("BOPRE") == 0){
       /// console.log("Bonificaion por Precio : "+value);
        Update_BoniX_PRECIO_CANTIDAD(0,CVER,value)
       cant =  bonicalculate(GetObjeWhitBoni(CVER))
       } 
    ////***Comaparamos las  Bonificaciones Por Precio 
   if(typeBoni.localeCompare("CATBO") == 0){
        ///console.log("Bonificaion por Cantidad : "+value);
        Update_BoniX_PRECIO_CANTIDAD(1,CVER,value)
      cant =  bonicalculate(GetObjeWhitBoni(CVER))
    } 
    Update_BoniXupdate(CVER,cant)  
    $(classPositApli).text(cant)
   
 }
   
  $("#btnBackInput").click(function(){
       $("input").attr("disabled",false);
  }); 
 
 $("#btnClientes").click(function(){
     $("#ModCli").modal("show");
     
 });
 
 $("#regCoti").click(function(){
      $("#nfMen").text("Esta a Punto de Regresar  el Folio:"+FOLIO ) ;
     $("#btnNote").text("Nota Presione Enviar para Regresar la Cotizacion con el Agente par su  Modificacion.");

     $("#BtnOpcio").attr("hidden",true);
     $("#BackEleTx").attr("hidden",false);
     
     
 });
 /////***Funcion para  Convertir a Moneda
    function ConverMoney(value)
    {       
      
    /*
        var  str = value+""; 
         return str.replace(/\D/g, "")
                .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
    */
      return value;
    }
        
       
});
</script>
<style>
    #imgSalida {
    border: 2px solid #73AD21;
    position: relative;
    top: 9px;
    left: 80px;
}

    div#contallImg {
    border: 2px solid #177323;
    border-radius: 25px;
}
 thead, tbody { display: block; }

tbody {
    height: 500px;       /* Just for the demo          */
    overflow-y: auto;    /* Trigger vertical scroll    */
   
}
th,td{
        max-width: 10.5vw;
    min-width: 10.5vw;
}
td.updctprod.btn.btn-success {
    height: 41px;
    min-width: 93px;
  
}
td.btn.btn-danger {
       height: 41px;
    min-width: 93px;
 
} 
td.desbtn.btn.btn-info {
       height: 41px;
    min-width: 93px;
}
.ModTb,.DesTb,ElTb{
    height: 41px;
    min-width: 93px;
}
.ptelems {
    min-width: 120px;
}
.cmgporInvt{
    min-width: 110px;
}
.Destb {
    min-width: 250px;
}

.qanti {
    min-width: 147px;
}
.casGen{
    
   min-width: 147px; 
}
.col-lg-5.col-xs-5 {
    left: 410px;
}
tbody.OPERINV {
    height: 150px;
}
#BakEst {
    /* margin-right: 56px; */
    margin-left: 62px;
    background: #1abc9c;
}
tbody.disablebody {
    max-height: 200px;
}
</style>
    <!--Contenedor  de Informacion Principal-->
    <div   class="CONTMAIN col-lg-12  col-xs-12">
        
       <?php 
        ///***Cadena para Hacer
        $string_get_info =sprintf("SELECT folio , cve_agente , fecha_sol, estatus_Ic ,estatus_Dc FROM pedidos.coti_encabeca_cotizacion  where  folio=%s", 
                        GetSQLValueString($FOLIOMASTER, "int")) ;
        $qery_info = mysqli_query($conecta1, $string_get_info);
       $row = mysqli_fetch_array($qery_info);

        		  ////***Buscamos Nombre  Agente. 
        			$string_getNomAge =  sprintf("select nom_empleado from  pedidos.relacion_gerentes where cve_age = %s", 
        												GetSQLValueString($row['cve_agente'], "int"));
        			$qeryGetNomAge =   mysqli_query($conecta1,$string_getNomAge);
        			$nomfethcAge = mysqli_fetch_array($qeryGetNomAge);
       			
        ?>  
      <div class="row ">
             <div  class="col-sm-5"><h3>Folio:<?php echo $row['folio'];?></h3></div> <div class="col-sm-4"><strong>Agente:<?php echo utf8_encode($nomfethcAge['nom_empleado']);?></strong></div><div  class="col-sm-3"><strong class="posFech">Fecha Solicitud: <?php  $dt = new DateTime($row['fecha_sol']);  echo $dt->format("d/m/Y") ;?><strong></div>
      </div>
      <div class="row ">
                <div class="col-xs-5">  <?php  if($row['estatus_Ic']==0)   { echo  "<strong>Estatus: "."Pendiente"."</strong>" ; }?></div>  
                <?php if($TPePg==1){?>
                <div class="col-xs-5"> 
                    <button  id="btnEst"   type="button" class="btnEst btn btn-success"  value="<?php echo utf8_decode($row['folio']);?>"   >Modificar Estatus</button>
                
                </div>  
                <div class="col-xs-1"> 
                 <button  type='button' class='CALU btn btn-info'><span class="glyphicon glyphicon-usd" > </span> Calcular</button>
                </div>
                <?php  }?> 
                <div class="col-xs-1"> 
                    <a  type='button' id="BakEst"  class='back btn btn-info  btn-lg'> <span  class="glyphicon glyphicon-arrow-left"></span> Regresar</a>
                </div>
         
        
    </div>
        <br>
        <div class="row">
            <div class="col-xs-6">  
                <h6>Comentarios:</h6>
                <p><strong><?php echo $fetComent['com_agent'];?> </strong></p>
            </div>    
            <div class="col-xs-6"> 
                
               <?php
               
                if($NuCl == 0 ||$NuCl == NULL ){
                    echo  "<p><strong> Esta Cotizacion Es Aplicada para  toda la  Zona</strong></p>";
                }
                if($NuCl >= 1 ){
                    echo "<p><strong>Revisar Clientes a  los que se les Aplica Cotizacion</strong></p>";
                    echo  '<button  id="btnClientes"   type="button" class="btnClientes btn btn-success"  value='.$row['folio'].' >Clientes</button>';
                }
               
               
               ?>
            </div>  
        </div>
    <!--FIN Contenedor  de Informacion Principal-->
    <!--Contenedor  Tabla  Principal-->
    <div   class="col-lg-12  col-xs-12">
         
        <table class="table  table-bordered">
            <thead>
                <th class="ptelems"  >PT</th>
                <th class="Destb" >Descripcion</th>
                <th class='qanti'>Q</th>
                <th class='qanti'>Precio Sol</th>
                <th class='casGen'>Cto Inv</th>
                <th class='casGen'>Cto Proy</th>
                <th class="cmgporInvt">CMG %Inv</th>
                <th class='casGen'>Venta</th>
                <th class='casGen'>Costo</th>
                <th class='casGen'>CMG</th>
                <th class="cmgporInvt">CMG%</th>
                <th  class="cmgporInvt">Limite Dc</th>
               
            </thead>
            <tbody   id="tbCont"></tbody>
        </table>  
    </div>
    <!--Contenedor  de  Bonificaciones    y Totales -->
    <div   class="col-lg-12  col-xs-12">
        
    <div   class="CONTMAIN col-lg-6  col-xs-6">
       
        <table class="table  table-bordered" >
            <thead>
                <tr><strong>Bonificaciones</strong> </tr> 
                <tr>   
                     <th  class="ptelems">Pt</th>
                     <th class="Destb">Descripcion</th>
                     <th  class='qanti'>Boni Aplicar</th>
                     <th class='qanti'>Cto Inv</th>
                     <th class='qanti'>Cto Proy</th>
                </tr>
            </thead>
            <tbody id="boniapli" >
            </tbody>
        </table>
        
    </div>
    <div  class="col-lg-1  col-xs-1"></div> 
    <div   class=" col-lg-5  col-xs-5">
         
        <table class="table  table-bordered" >
            <thead><tr>
                 <th class="Destb"></th><th  class='qanti'>Venta</th><th class='qanti'>Costo</th><th  class='qanti'>CMG</th><th class='qanti'>CMG %</th>
                </tr>
            </thead>
            <tbody class="OPERINV" id="idtls" >  
            </tbody>
        </table>
         <table class="table  table-bordered" >
            <thead><tr>
                 <th class="Destb"></th><th  class='qanti'>Venta</th><th class='qanti'>Costo</th><th  class='qanti'>CMG</th><th class='qanti'>CMG %</th>
                </tr>
            </thead>
            <tbody class="OPERINV" id="idtlsfin" >  
            </tbody>
        </table>
        
    </div>    
</div>
<!--************Dialog Estatus********************-->
     <div  class="modal fade" id="ModEst" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
                <button type="button" class="close_coment close" data-dismiss="modal">&times;</button>
                <h5>Modificar  Estatus<h5>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div id="CONTMOD" class="row" class="well">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10"><h4 id="nfMen"></h4><p><strong  id="btnNote"></strong></p></div>
                        <div class="col-sm-1"></div>
                        
                    </div>
                     <div id="BtnOpcio"  class="row" class="well">
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-1" ><button  type="button"  class="btnEstatus  btn btn-info" value="1" >Autorizar</button></div>
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-1" ><button id="regCoti" type="button"  class="btBackReg  btn btn-success" value="3" >Regresar</button></div>
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-1" ><button  type="button"  class="btnEstatus  btn btn-danger" value="2" >Rechazar</button></div>
                    </div>
                    <div  class="row" class="well">
                        
                            <div class="col-sm-4" ></div>
                            <div class="col-sm-1" ><h5 class="MensEl" ></h5> </div>
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-4" ></div>
                        
                    </div>
                    <div hidden  id="BackEleTx" class="row" class="well">
                        <div  class="row" class="well">
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-10" >
                                <strong>Comentarios para el Agente.</strong>
                                <textarea   id="txtComeJI" class="txAreComen form-control" ></textarea>
                            </div>
                            <div class="col-sm-1" ></div>
                        </div>
                        <br>
                         <div  class="row" class="well">
                            <div class="col-sm-10" ></div>
                            <div class="col-sm-1" ><button  type="button"  class="btnEstatus  btn btn-info" value="3" >Enviar</button></div>
                            <div class="col-sm-1" ></div>
                          </div>
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">

            </div>
          </div>
		 </div>
	 </div>                  
    <!--***********************************************-->  
<!--************Dialog Estatus********************-->
     <div  class="modal fade" id="ModCli" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
                <button type="button" class="close_coment close" data-dismiss="modal">&times;</button>
                <h5>Clientes  Cotizacion<h5>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                 
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                          <table class="table table-striped">
                              <thead>
                                  <tr>
                                      <th>Clave Cliente</th>  
                                      <th>Nombre Cliente</th>
                                  </tr> 
                              </thead>
                              <tbody class="disablebody">
                                 <?php  
                                
                                     ///Agregamso la  Lista de los  Clientes  
                                     foreach ( $clienteArre as  $value) {
                                         echo  "<tr><td>".$value['cve_cliente']."</td><td>".$value['nom_cliente']."</td></tr> ";
                                     }
                                 
                                 
                                 ?>  
                              </tbody>
                          </table>
                        </div>
                        <div class="col-sm-1"></div>
                        
                    </div>
                   
                    
                
            </div>
            <div class="modal-footer">
<button  type="button"  class="btn btn-danger" data-dismiss="modal"  >Cerrar</button>
            </div>
          </div>
		 </div>
	 </div>                  
    <!--***********************************************-->  
<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 