<?php
///////*****coti_estcontizacionJIC.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_estcontizacionJIC.php 
 	Fecha  Creacion :16/06/2017
	Descripcion  : 
 *             Escrip Diseñado  para  Autorizar  Solicitudes  de Cotizacion
 * 
 *      Modificacion  
 *                  20/07/2017   Se Agrega la  Variable  Entiendase  que  la  Varible  TypePg  
 *                               TypePg   en  Estado  =>  1  =>La  Pagina   Se ejecutara  con opciones de Update   y Estatus
 *                                TypePg  en  Estado  =>  2  =>La  Pagina   Se ejecutara  con opciones de Vista  Nada  de  Modificaciones    
 *       
  */

////**Inicio De Session 
	 session_start ();
   $MM_restrictGoTo = "index_inteligencia.php";
   if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
///****Cabecera Cronos
require_once('header_inteligencia.php');
require_once('Connections/conecta1.php');
///*****        
require_once('formato_datos.php');
///**+Establecemos   Update  a  Estado  1  
$_SESSION['UbdatePub'] =1;
        
$TypPeg = filter_input(INPUT_GET, 'TypePg');          

?>
<style>
    .brdD.row {
    border-color: rgba(17, 165, 37, 0.2);
    border-style: groove;
    border-radius: 28px;
}
h4.posFech {
    padding-top: 12px;
}
.row.infoS{
        margin-left: 11px;
}
.buscar{
    margin-top: 11px;
    margin-left: 70px;
}
button.btn.btn-sucess {
    background-color: #33a471;
}
button#btnEst {
    margin-bottom: 18px;
   
}
tbody.disablebody {
    max-height: 200px;
}
</style>
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="coti_scrip_cotiza/coti_Arreglomain.js"></script>
<script src="coti_scrip_cotiza/coti_ClassPrd.js"></script>
<script type="text/javascript" src="pub_scrip_publicidad/pub_func_publicidad.js"></script>
<script type="text/javascript">
       const  PagReturn=  <?php echo "'http://192.168.101.17/sistemas/cronos/coti_index_option.php'"; ?>;
      /////***Areglo en Bruto 
        var   ArgBrutMain ;   
       //////****Arreglo Principal
        var  ArglMain = new Array() 
       ///***Varible para  almacenar el  Costo  Total de una  Bonificacion  por Inventario 
       var  tlsCtoBonInven = 0; 
       ///***Varible para  almacenar el  Costo  Total de una  Bonificacion  por Proyectado  
       var  tlsCtoBonPro = 0 ; 
    
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
 /////***Funcion para  Realizar  el  Calculo  de  la  Cotizacion 
    function  CalcularCoti(ArrgloParse)
    {
         ArgBrutMain = ArrgloParse  ;
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
                                ////****Generar Tabla de  Bonificaciones 
                                if(Obj.boni_act == "true")
                                {////***Obtenemos los  totales  de los Costos  con Bonificacion
                                    tlsCtoBonInven += Obj.bo_cto_inv;  
                                    tlsCtoBonPro +=  Obj.boCto_proy; 
                                }
                                
                                
                           }
                   
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
       var  Nf ;
       //////**** Variable   Folio Variable 
       var    nfolioVar;
    $(".deletEL").click(function(){
        ///***Obtenesmo el Numero de Folio 
       Nf = $(this).attr('ELEtoEli');
        //*Folio: 14235234 nfMen
        $("#nfMen").text("Folio: "+Nf);
        ///***Mostramos Modal  
        $('#ModalMNs').modal('show');  
    });    
    $("#DELYNF").click(function(){
        
            $.ajax({
                type:'POST',
                url: 'pub_scrip_publicidad/pub_delElem.php',
                data:{"NFO":Nf}, 
                success: function (datos) { 
                     if(datos.RES == 1)
                     {
                         location.reload() ; 
                     }
                     if(datos.RES ==0)
                     {
                         $(".MensEl").text("Existen  Problemas  Para Eliminar Intente mas Tarde");
                     }
                      if(datos.RES ==5)
                     {
                         $(".MensEl").text("Error No Existe El Folio");
                     }
                      
             }});
        
        
    })
////**********************************
	$(document).on("click",".btnEst",function(){
                ///***Mostramos Modal  
         
                  nfolioVar =  $(this).attr('value');
               
         $.ajax({
                type:'POST',
                url: 'coti_scrip_cotiza/coti_prodsinsave.php',
                data:{"FL":nfolioVar,"EST":1}, 
                success: function (datos) { 
                   try{
                   
                    CalcularCoti(JSON.parse(datos.allelem));
                    $("#nfMenEs").text("Esta a Punto de modificar el Folio:"+nfolioVar ) ;
                    $("#btnNote").text("Nota Si Usted Define Cualquier Tipo de Estatus NO Podra Modificar la Cotizacion.");
                    $("#btnNote").css("color","red");
                    $('#ModEst').modal('show');
                   }catch(e)
                   {
                       alert("Existe  Un Problema Comuníquese  con el  Administrador");
                   }
                      
             }});
                  
                  
	});
        /////**Btn  Send Estatus 
        $(document).on("click",".btnEstatus",function(){
             var  typeEst =  $(this).val(); 
                var  ObFinal  = Get_OperFinal_InvProy() ;   
                ///ObjFINL ={"Inv" :OprFinlInventario ,"Proy":OprFinlProyectada}  JSON.stringify(OBJOPERFINA)
                var  OBJOPERFINA  = {"Inv":JSON.stringify(ObFinal.Inv ),"Proy":JSON.stringify(ObFinal.Proy ) } 
                var objpru ={"FL":nfolioVar,"TYPEST":typeEst ,"INFOmain":JSON.stringify(ArglMain) ,"TLSfin":JSON.stringify(OBJOPERFINA) ,"tlsCtoBonInven":tlsCtoBonInven ,"tlsCtoBonPro":tlsCtoBonPro };
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
                        data:{"FL":nfolioVar,"TYPEST":typeEst ,"COME":coment ,"INFOmain":JSON.stringify(ArglMain) ,"TLSfin":JSON.stringify(OBJOPERFINA) ,"tlsCtoBonInven":tlsCtoBonInven ,"tlsCtoBonPro":tlsCtoBonPro }, 
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
                                  data:{"FL":nfolioVar,"TYPEST":typeEst ,"INFOmain":JSON.stringify(ArglMain) ,"TLSfin":JSON.stringify(OBJOPERFINA) ,"tlsCtoBonInven":tlsCtoBonInven ,"tlsCtoBonPro":tlsCtoBonPro }, 
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
                       
                       
                      
        });
    ////*********************************************************************
      ////***Funcion para  Retornar la Tabla de Operaciones  Finales
  function   Get_Tb_FinalesTotales()
  {
      var  ObFINAL = Get_OperFinal_InvProy(); 
        var tbtlsFINAL  = "<tr><td><strong>Preyectada </strong></td><td  class='qanti'>"+currency(ObFINAL.Proy.OprPrVents, 2, [',', ",", '.'])+"</td><td class='qanti'>"+
                     currency(ObFINAL.Proy.OprPrCosts, 2, [',', ",", '.'])+"</td><td class='qanti'>"+currency(ObFINAL.Proy.OprPrCMG, 2, [',', ",", '.'])+
                     "</td><td class='qanti'>"+ObFINAL.Proy.OprPrCMGPor+"%</td></tr>"+
                     "<tr><td><strong>Inventario</strong></td><td class='qanti'>"+currency(ObFINAL.Inv.OprInVents, 2, [',', ",", '.'])+"</td><td class='qanti'>"+
                     currency(ObFINAL.Inv.OprInCosts, 2, [',', ",", '.'])+"</td><td class='qanti'>"+currency(ObFINAL.Inv.OprInCMG, 2, [',', ",", '.'])+
                     "</td><td class='qanti'>"+ObFINAL.Inv.OprInCMGPor+"%</td></tr>"; 
      return   tbtlsFINAL;      
  }   
    ///////***************************************************************
       $(document).on("click",".gettls",function(){
        /////***Obtenemos El  Folio  para  Obtener  el ARGLO MAIN A
         var   flo = $(this).attr("fl");
         $.ajax({
                type:'POST',
                url: 'coti_scrip_cotiza/coti_prodsinsave.php',
                data:{"FL":flo,"EST":1}, 
                success: function (datos) { 
                    CalcularCoti(JSON.parse(datos.allelem));
                 /////*********************************
                    var  idtbody = "#tbdy"+flo;
                    $(idtbody).html(Get_Tb_FinalesTotales());     
                    ArglMain =new Array();
             }});
        
        
       });
      //////***Regresar Cotizacion  
     $("#regCoti").click(function(){
      
     $("#btnNote").text("Nota Presione Enviar para Regresar la Cotizacion con el Agente par su  Modificacion.");

     $("#BtnOpcio").attr("hidden",true);
     $("#BackEleTx").attr("hidden",false);
     
     
     });
       
       
       
   });
   
 </script>
 <div  class="container">
     
        <?php 
        ///***Opcion para Update
        if($TypPeg==1){
      echo       '<div class="row"><h2>Estado  Solicitudes</h2></div>';
      echo      '<div class="col-sm-12">';
        ///***Cadena para Hacer
        $string_get_info = "SELECT folio , typeusu ,cve_agente,cve_gerente , fecha_sol, estatus_Ic ,estatus_Dc FROM pedidos.coti_encabeca_cotizacion  where  estatus_Ic = 0   ";
        $qery_info = mysqli_query($conecta1, $string_get_info);
        }
        ///***Opcion para Historial
        if($TypPeg==2){
      
      echo       '<div class="row"><h2>Historial de Solicitudes</h2></div>';
      echo      '<div class="col-sm-12">';
        ///***Cadena para Hacer
        $string_get_info = "SELECT folio ,typeusu ,cve_agente,cve_gerente, fecha_sol, estatus_Ic ,estatus_Dc FROM pedidos.coti_encabeca_cotizacion  where  estatus_Ic != 0 ";
        $qery_info = mysqli_query($conecta1, $string_get_info);
        }



        while($row = mysqli_fetch_array($qery_info)){

        		  ///Validasmos para Obtener  Nombre del  Agente  y Gerente
              if($row['typeusu']==1){
                    ////***Buscamos Nombre  Agente. 
              			$string_getNomAge =  sprintf("select nom_empleado from  pedidos.relacion_gerentes where cve_age = %s", 
              												GetSQLValueString($row['cve_agente'], "int"));
                      $qeryGetNomAge =   mysqli_query($conecta1,$string_getNomAge);
                      $nomfethcAge = mysqli_fetch_array($qeryGetNomAge);
                      $nom_Soli =$nomfethcAge['nom_empleado'];  
              
               } 
               if($row['typeusu']==2){
                    ////***Buscamos Nombre  Agente. 
                    $string_getNomAge =  sprintf("select zona  from  pedidos.relacion_gerentes where cve_gte = %s", 
                                      GetSQLValueString($row['cve_gerente'], "int"));
                      $qeryGetNomAge =   mysqli_query($conecta1,$string_getNomAge);
                      $nomfethcAge = mysqli_fetch_array($qeryGetNomAge);
                      $nom_Soli =$nomfethcAge['zona']; 
               }

        		
        ?>
         <div class="brdD row">
             <div class="row infoS">
                 <div  class="col-sm-5"><h3>Folio:<?php echo $row['folio'];?></h3></div> <div class="col-sm-4"><strong>Agente:<?php echo utf8_encode($nom_Soli );?></strong></div><div  class="col-sm-3"><strong class="posFech">Fecha Solicitud: <?php  $dt = new DateTime($row['fecha_sol']);  echo $dt->format("d/m/Y") ;?><strong></div>
             </div>
             <div class="row infoS">
                 <div class="col-sm-5">
                      <div class="col-xs-5">  
                          <?php ///Fin Condicion para Modificar
                                     //***Inicio Funcion de Historial 
                          if($TypPeg==2){
                              if($row['estatus_Ic']==1)   { 
                                        echo  "<strong>Estatus: Autorizado </strong>";
                              }
                              if($row['estatus_Ic']==2)   { 
                                        echo  "<strong>Estatus: Rechazada </strong>";
                              }
                              if($row['estatus_Ic']==3)   { 
                                        echo  "<strong>Estatus: En Modificacion. </strong>";
                              }         
                          }  
                          
                          ?> 
                          <?php  if($row['estatus_Ic']==0)   { echo  "<strong>Estatus: "."Pendiente"."</strong>" ; }?>
                      </div>  <div class="col-xs-7">
                     <?php   
                     ///***Validacion Opcion solo  para Modificacion 
                     if($TypPeg==1){ ?>
                         <button    type="button" class="btnEst btn btn-sucess "  value="<?php echo $row['folio'];?>"   >Modificar Estatus</button>  
                     <?php }?>
                      </div>   
                 </div>
                <div class="col-sm-5"> 
                    <?php   
                     ///***Validacion Opcion solo  para Modificacion 
                     if($TypPeg==1){ ?> 
                    
                    <table class="table  table-condensed">
                        <thead>
                            <tr><button  type="button"  class="gettls  btn btn-sucess btn-sm" fl="<?php echo $row['folio'];?>"  >Obtener Totales</button></tr> 
                            <tr><th>Operacion Final</th><th>Venta</th><th>Costo</th> <th>CMG</th><th>CMG%</th></tr> 
                        </thead>
                        <tbody id="tbdy<?php echo $row['folio'];?>" >
                              
                        </tbody>
                    </table>
                   <?php }///Fin Condicion para Modificar
                   ///***Inicio Funcion de Historial 
                    if($TypPeg==2){?>
                       <table class="table  table-condensed">
                        <thead>
                             <tr><th>Operacion Final</th><th>Venta</th><th>Costo</th> <th>CMG</th><th>CMG%</th></tr> 
                        </thead>
                        <tbody id="tbdy<?php echo $row['folio'];?>" >
                          <?php 
                              $strGetTotleFIN  = sprintf("Select  tlsOprVt_proy,tlsOprCts_proy,tlsOprCMG_proy,tlsOprCMGpor_proy,tlsOprVt_inv,tlsOprCts_inv,tlsOprCMG_inv,tlsOprCMGpor_inv from  pedidos.coti_encabeca_cotizacion   where  folio =%s ",
                                                         GetSQLValueString($row['folio'], "int")
                                      ); 
                              $qeryGetTLS = mysqli_query($conecta1, $strGetTotleFIN); 
                              if(!$qeryGetTLS)
                              {
                                  echo  '<tr><td>Lo Sentimos  Exiten Problemas  en la  Base de Datos</td></tr>';
                              }else {
                                ////**La convertios  a Fetch 
                                  $fethc = mysqli_fetch_array($qeryGetTLS);
                                  /////***Agregamos el  Renglon  De TOTALES  POR  PROYECTADA
                              ECHO     "<tr><td><strong>Preyectada </strong></td><td  class='qanti'>".number_format($fethc['tlsOprVt_proy'] , 2, '.', ',')."</td><td class='qanti'>".
                                        number_format($fethc['tlsOprCts_proy'] , 2, '.', ',')."</td><td class='qanti'>".number_format($fethc['tlsOprCMG_proy'] , 2, '.', ',').
                                        "</td><td class='qanti'>".$fethc['tlsOprCMGpor_proy']."%</td></tr>".
                                        "<tr><td><strong>Inventario </strong></td><td class='qanti'>".number_format($fethc['tlsOprVt_inv'] , 2, '.', ',')."</td><td class='qanti'>".
                                        number_format($fethc['tlsOprCts_inv'] , 2, '.', ',')."</td><td class='qanti'>".number_format($fethc['tlsOprCMG_inv'] , 2, '.', ',').
                                       "</td><td class='qanti'>".$fethc['tlsOprCMGpor_inv'] ."%</td></tr>";
                                    
                              }  
                          ?>  
                        </tbody>
                    </table>
                       
                       
                  <?php }///Fin Condicion para  }?>
                </div> 
                 
               <?php  
               ///***Validacion  Btn  Visor de Cotizaciones 
               if($TypPeg==2){?>
                 <form  action ="coti_maincotizacionIC.php?<?php echo "TyPg=".$TypPeg;  ?> "  method="POST"> 
                        <div class="col-sm-1">
                            <input hidden type="int" name="FOLIO" value="<?php echo $row['folio'];?>" ><button type="submit" class="btn btn-info buscar"><span class="glyphicon glyphicon-zoom-in"></span></button>
                        </div>

                    </form>
               <?php }///Fin  Validacion  Btn   Update    
               ///***Validacion Opcion solo  para Modificacion 
                if($TypPeg==1){ 
                   if($row['auto_JINC']==0)
                            { ?> 
                 <form  action ="coti_maincotizacionIC.php?<?php echo "TyPg=".$TypPeg;  ?>"  method="POST"> 
                   <!--  <div class="col-sm-1"> -->
                            <input hidden type="int" name="FOLIO" value="<?php echo $row['folio'];?>" ><button type="submit" class="btn btn-sucess buscar"><span class="glyphicon glyphicon-edit"></span></button>
                   <!--  </div>-->
                 </form>
                <!--Btn Para   ----> 
                <div class="col-sm-1" ></div>
               <?php } }?> 
                 
             </div>
         </div> 
         <br>
         <?php }?>      
         
     </div>
     <!---Modal  Mensages-->    
     <div  class="modal fade" id="ModalMNs" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
                <h5>Eliminar  Solicitud<h5>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div id="CONTMOD" class="row" class="well">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4"><h4 id="nfMen"></h4></div>
                        <div class="col-sm-6"></div>
                        
                    </div>
                     <div  class="row" class="well">
                        
                            <div class="col-sm-4" ></div>
                            <div class="col-sm-1" > <button  type="button" id="DELYNF" class="btn btn-danger" >Eliminar</button> </div>
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-1" > <button type="button" id="close_coment" class=" btn btn-info" data-dismiss="modal">Close</button> </div>
                            <div class="col-sm-4" ><H2 id="resEle"></H2></div>
                        
                    </div>
                    <div  class="row" class="well">
                        
                            <div class="col-sm-4" ></div>
                            <div class="col-sm-1" ><h5 class="MensEl" ></h5> </div>
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-4" ></div>
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
             <!--  <button type="button" id="btn_update_comentarios"  class="btn btn-info" data-dismiss="modal"> <span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button> -->
            </div>
          </div>
		 </div>
	 </div>
  <!--******************************-->
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
                        <div class="col-sm-10"><h4 id="nfMenEs"></h4><p><strong  id="btnNote"></strong></p></div>
                        <div class="col-sm-1"></div>
                        
                        
                    </div>
                     <div  id="BtnOpcio"  class="row" class="well">
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



 </div>
<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 








