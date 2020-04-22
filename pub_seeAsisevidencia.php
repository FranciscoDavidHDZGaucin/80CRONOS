<?php
/////****pub_seeAsisevidencia.php  

/*  desv_estseePlan.php?TypePg=1 
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_seeAsisevidencia.php
 	Fecha  Creacion :11/08/2017
	Descripcion  : 
 *             Escrip Diseñado  para  MostRAR EL  hISTORIAL   Y  LAS  SOLICITUDES  DE   PUBLICIDAD  PENDIENTE 
 *                  $TypePg =>1 => Se entiende  para  ver Historial
 *                  $TypePg =>2=>  Se ENTIENDE  PARA   VE LA SOLICITUDES   PENDENTES  PAR A MANDAR UN ARCHIVO       
 *  
 *       
  */

////**Inicio De Session 
	 session_start ();
   $MM_restrictGoTo = "index_inteligencia.php";
   if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}

require_once 'header_asisIC.php';
require_once('Connections/conecta1.php');
///*****        
require_once('formato_datos.php');
require_once('conexion_sap/sap.php');
mssql_select_db("AGROVERSA");
        
$TypPeg = filter_input(INPUT_GET, 'TypePg');          
/*      
if($TypPeg==1){
///***Cadena para Hacer
            // $string_get_info = "SELECT folio , cve_agente , fecha_sol, estatus_Ic ,estatus_Dc FROM pedidos.coti_encabeca_cotizacion  where    estatus_Ic = 0  and cve_agente =".$idagente  ;
        $string_get_info = "SELECT cve_desvi,cve_agente,fech_Ini,fech_fin,estas_ans FROM pedidos.desv_encabeza_desviacion  where  estas_ans=0  "  ;
}       
if($TypPeg==2){*/
   ///***Cadena para Hacer
            // $string_get_info = "SELECT folio , cve_agente , fecha_sol, estatus_Ic ,estatus_Dc FROM pedidos.coti_encabeca_cotizacion  where    estatus_Ic = 0  and cve_agente =".$idagente  ;
        $string_get_info_001 = "SELECT * FROM pedidos.pub_encabeza_publicidad where est_evidecia =1 "  ; 
    
///}        
        
        $qery_info_001 = mysqli_query($conecta1, $string_get_info_001);
        $AreglObjetos =  Array();
        while($row = mysqli_fetch_array($qery_info_001)){
            
            
                ////***Buscamos Nombre  Agente. SELECT  T0.[SlpName]  FROM OSLP T0 where T0.[SlpCode] =%s  
        			$string_getNomAge =  sprintf("SELECT  T0.[SlpName]as Nombre  FROM OSLP T0 where T0.[SlpCode] =%s", 
        												GetSQLValueString($row['cve_agente'], "int"));
        			$qeryGetNomAge = mssql_query($string_getNomAge);
        			$nomfethcAge = mssql_fetch_array($qeryGetNomAge);
                 ///**Obteneos Total de la  Solicitud  
                                            $str_getTotalSol  =  sprintf("select  GetTotalSolicitud (%s) as TotalSol",GetSQLValueString($row['pub_folio'], "int") );
                                            $qeryGet=   mysqli_query($conecta1,$str_getTotalSol );
                                            $getTotalSol = mysqli_fetch_array($qeryGet);
              
                 /////***Obtenemos el  Tipo  de Envio 
                 if($row['typetosend']==1){
                 $Otro = "Tres Guerras";  
                 
                 }
                  if($row['typetosend']==2){
                  $Otro = "Estafeta"; 
                  
                 }
                 if($row['typetosend']==3){
                 $Otro = $row['otro'];     
                 }
                                
                                
                                
                 ////***pub_folio,pub_fech_cap,cliente,auto_JINC,cve_agente              
              $OBJETO = array(
                                cve_folio => $row['pub_folio'],
                                cve_AGE => $row['cve_agente'],
                                fe_captura => $row['pub_fech_cap'],
                                cliente => $row['cliente'],
                                auto_JINC => $row['auto_JINC'],
                                nomAgen => $nomfethcAge['Nombre'],
                                fech_esti_lleg =>$row['fech_envio'],
                                type_tosend => $Otro, 
                                num_guia => $row['num_guia'],
                                fecha_rec=>$row['fech_rec'],
                                totlaes => "$".number_format($getTotalSol['TotalSol'], 2, '.', ',')
                                    
                                
              );   
              
              array_push($AreglObjetos, $OBJETO);
            
        }
////****Areglo de  Objetos  
$ArregloMain = json_encode($AreglObjetos);
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
.opcEstPENDIENTE {
    BACKGROUND: #f2f73b;
    BORDER-RADIUS: 67PX;
    MAX-HEIGHT: 46PX;
    TEXT-ALIGN: center;
}
.opcEstAUTO {
    BACKGROUND: #48c9b0;
    BORDER-RADIUS: 67PX;
    MAX-HEIGHT: 46PX;
    TEXT-ALIGN: center;
}
.opcEstRECHAZADO {
    BACKGROUND: #e74c3c;
    BORDER-RADIUS: 67PX;
    MAX-HEIGHT: 46PX;
    TEXT-ALIGN: center;
}
.opcEstMODIFICAR {
    BACKGROUND:#3498db;
    BORDER-RADIUS: 67PX;
    MAX-HEIGHT: 46PX;
    TEXT-ALIGN: center;
}

</style>
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!--Escrip  para  validar  Fecha de Req-->
<script src ="adicional_scrip/fechas_fail_other.js"></script>
<script type="text/javascript">
      var   ArregloMain  = <?php  echo $ArregloMain; ?>;
      var   ELMbUS =1;
      var strinicioBus  ="<div id='ConteResBus' ><div id='objbusq"+ELMbUS+"'></div></div>";
      ///****Variable  Temporal Envio de  Correos 
      var   tempMail = null  ; 
      /////***Funcion  para  Buscar  los  elementos 
      function   BroswerElems (Bustype,elem,FECH)
      {         
          var  htmlObjetos ="";  
        for(var  i in ArregloMain  ) { 
                    switch(Bustype){
                        case  "1":  ///BuType => 1 => Busqueda por Clave de Desviacion 
                             if(ArregloMain[i].cve_folio ==elem )
                             {
                                 ///*Mandamos  Mostrar  el  elemento
                                 console.log(ArregloMain[i]);
                                htmlObjetos += PutHtmlElem(ArregloMain[i])
                             }

                        break;
                        case  "2":
                                 if(ArregloMain[i].cve_AGE ==elem )
                             {
                                 ///*Mandamos  Mostrar  el  elemento
                                 console.log(ArregloMain[i]);
                                htmlObjetos +=  PutHtmlElem(ArregloMain[i])
                             }
                          break;
                        case  "3": 
                              var    arreglofech  =  new  Date(convertoTODB(ArregloMain[i].fe_captura));
                               if( arreglofech >= elem &&  arreglofech <= FECH )
                                {
                                     ///*Mandamos  Mostrar  el  elemento
                                     console.log(PutHtmlElem(ArregloMain[i]));
                                    htmlObjetos +=  PutHtmlElem(ArregloMain[i])
                               } 
                             
                              
                        break;
                        case  "4":
                             /* var  fechaelm =  new  Date(elem); 
                              var    arreglofech  =  new  Date(ArregloMain[i].fe_fin);
                               if(fechaelm.getMonth()==arreglofech.getMonth() )
                                {
                                     ///*Mandamos  Mostrar  el  elemento
                                     console.log(ArregloMain[i]);
                                    htmlObjetos +=  PutHtmlElem(ArregloMain[i])
                               } 
                              */
                          break;
                    }
      }   
          var  idob = '#objbusq'+ELMbUS;
          $("#ConteResBus").html(htmlObjetos);
       
      }
      ////****Funcion para Generar  Opcion Html 
      function PutHtmlElem(Objeto)
      {        var estatusElemnto ="";
           var   strHtml="";
            var  estushtm="";   
               ////*Generamos Json  de  Envioa 
              /* var   jsontoSend  =JSON.stringify( {"cvagente":Objeto.cve_AGE ,"cveDes":Objeto.cve_desvi,"feIni":Objeto.fe_ini,"feFin":Objeto.fe_fin});*/
               ////***Obtenemos  cadena  de estatus 
            if(Objeto.auto_JINC ==0)   
            { estushtm =  "<h5>Estatus: "+"Pendiente"+"</h5>" ; }
            if(Objeto.auto_JINC==2)   
            { estushtm  ="<h5>Estatus: "+"Rechazada"+"</h5>" ; }
            if(Objeto.auto_JINC==1)   
            { estushtm = "<h5>Estatus: "+"Autorizada"+"</h5>" ; }


            strHtml =  '<div class="col-sm-12">'+
                                    '<div class="brdD row">'+
                                      '<div class="row infoS">'+
                                                   '<div  class="col-sm-5"><h3>Clave Publicida:'+ Objeto.cve_folio+'</h3></div><div  class="col-sm-3"><strong class="posFech">Total:'+Objeto.totlaes +'<strong></div>'+
                                                   '<div class="col-sm-1"></div><div  class="col-sm-3"><strong class="posFech">Fecha Solicitud:'+Objeto.fe_captura+'</strong></div>'
                                            +'</div>'
                                            +'<div class="row infoS">'+
                                                '<div class="col-sm-5"><h5>Agente:</h5><h6>'+Objeto.nomAgen+'</h6> <button type="button" class="dtllpaquete'+ Objeto.cve_folio+'  btn btn-info buscar btn-small" data-toggle="collapse" data-target="#demo'+ Objeto.cve_folio+'">Detalles del Paquete<span class="glyphicon glyphicon-list"></span></button>'
                                                 +'</div>'+
                                                 '<div class="col-sm-4">'+estushtm+'</div>'+
                                                 '<form  action ="pub_seepublicidadwhitpre.php"  method="POST">'+ 
                                                    '<div class="col-sm-1"><input hidden type="int" name="updateSol" value="'+Objeto.cve_folio+'" ><button type="submit" class="btn btn-info buscar"><span class="glyphicon glyphicon-zoom-in"></span></button></div>'+
                                                '</form>'+
                                                '<div class="col-sm-1" ></div>'+
                                              '</div>'+
                                              //<!----------------------------------------------------------->
                                        '<div id="demo'+Objeto.cve_folio+'" class="collapse row">'+
                                               '<div  class="col-sm-12">'+
                                                   '<div class="col-sm-6">'+
                                                       '<div  class="form-group">'+
                                                           '<label><strong>Tipo de Envio</strong></label>'+
                                                           '<input disabled value="'+Objeto.type_tosend+'"  id="othersend'+Objeto.cve_folio+'" class="othersend form-control" type="text">'+    
                                                      '</div>'+
                                                       '<div class="form-group">'+
                                                           '<label><strong>Fecha De Envio</strong></label>'+
                                                           '<input disabled  value="'+Objeto.fech_esti_lleg+'"  id="fech'+Objeto.cve_folio+'" type="date" class="form-control"  >'+
                                                       '</div>'+ 
                                                   '</div>'+
                                                   '<div class="col-sm-6">'+
                                                       '<div class="form-group">'+
                                                           '<label><strong>Numero de Guia</strong></label>'+
                                                           '<input disabled value="'+Objeto.num_guia+'" id="numgui'+Objeto.cve_folio+'" type="text" class="form-control"  >'+
                                                       '</div>'+ 
                                                    ////**********************************
                                                       '<div class="form-group">'+
                                                           '<label><strong>Fecha De Recibido</strong></label>'+
                                                           '<input disabled  value="'+Objeto.fecha_rec+'"  id="fech'+Objeto.cve_folio+'" type="date" class="form-control"  >'+
                                                       '</div>'+  
                                                        '<table class="table table-striped">'+
                                                             '<thead><strong> Evidencia</strong></thead>'+
                                                        '<tbody  id="tbevi'+Objeto.cve_folio+'">'+
                                                        '</tbody>'+
                                                         '</table>'+
                                                   '</div>'+
                                              '</div>'+
                                          '</div>'+
                                      //  <!----------------------------------------------------------> 
                                       '</div>'+
                                        
                                     '</div>'+
                            '</div>'; 
                            
          return  strHtml;
      }


</script>
<script type="text/javascript"> 
$(document).ready(function(){
  
 
$(document).on("click",".sendmail",function (){
   ////****Obtenemos   el Json 
   tempMail = JSON.parse( $(this).attr('infopri'));
  $("#ModEst").modal("show");
    
  
});

  $(".broswer").change(function(){
        var   Idneti = $(this).attr("id");
        var   strIdCorre = "#"+Idneti+" option:selected";
        var   TypeBro =$(this).attr("butype");
        $(".ContTOREMOVE").remove();
        $("#ConteResBus").empty();
        $("#contbusq").html(strinicioBus);
         BroswerElems (TypeBro,$(this).val());
           /// console.log("#"+Idneti);
  });
 ////***Busqueda por  Fechas
  $(document).on("click","#Busdate",function (){
     //////***Validamos   las   Fechas  que no  esten en Emty
      ///***Validamos Que  la  fecha Req No sea  Null    y el  alamacen 
       if ( (ValidarFecha($("#fechInic").val())==false  && ValidarFecha($("#fechFin").val())== false) && InputDate_enable() == true  )
       {
       alert("Lo Sentimos la Fecha No Tiene El Formato Correcto dd/mm/año ");    
       }else{
           
            if($("#fechInic").val() === null ||$("#fechFin").val() == ""   ) 
            {
                alert("Imposible Realizar Busqueda Campos Vacios"); 
            }else{  
                var  fechaIni =  new  Date(convertoTODB($("#fechInic").val())); 
                 var  fechaFin =  new  Date(convertoTODB($("#fechFin").val())); 
                if(fechaIni <= fechaFin)
                {
                  
                   $(".ContTOREMOVE").remove();
                    $("#ConteResBus").empty();
                    $("#contbusq").html(strinicioBus);
                     BroswerElems ("3",fechaIni,fechaFin);
                  
                  
                }else{
                    alert("Error La Fecha de Inicio de Solicitud es Menor que la Fecha fin de Solicitud. ");
                }
            
            
            }
      }  
      
      
  });
  //////***Obtenemos las Evidencias  para  cada  una  de las  soluctudes 
   $(document).on("click",".dtllpaquete",function(){
      var   fl  =   $(this).attr('value');
       $.ajax({
                        type:'POST',
                        url: 'pub_scrip_publicidad/pub_getevidencia.php',
                        data: {"FL": fl},
                        success: function(datos)
                        {
                            var  idtba = "#tbevi"+fl ;
                            $(idtba).html(datos.allelem); 
                            
                        }  

                    }); 
     
  });
 
$(document).on("click",".btnsend",function (){
   
      console.log(tempMail);
   $.ajax({
                type:'POST',
                url: 'pub_scrip_publicidad/pub_sendfaltaevi.php',
                data:{"cometPla":$("#txtComeJI").val(),"Obj":JSON.stringify(tempMail)}, 
                success: function (datos) {
                    console.log(datos.Est)
                    $("#ModEst").modal("hidden");
                  $("#txtComeJI").val(""); 
                      
             }});


});
    
});
</script>
 <div ID="MAINCON"  class="container">
  <?PHP  IF($TypPeg==1){ ?>       
     <div class="row"><h2>Historial Publicidad</h2></div>
        <!-- Inicio  Filtro  Busqueda  Desviaciones-->  
   <div class="col-sm-12">
       <div   id='Fil'  class='brdD row'>
                <div class="row infoS">
                    <div  class="col-sm-5"><h6>Busque Publicidad</h6>
                                </div><div  class="col-sm-3"><strong class="posFech">Fecha Inicio Solicitud:</strong>
                                  <input type="date" id="fechInic" butype=3 class=" form-control" >
                                   
                                </div>
                                <div class="col-sm-1"></div>
                                <div  class="col-sm-3"><strong class="posFech">Fecha Fin Solicitud:</strong>
                                  <input type="date"  id="fechFin" BuType=4 class=" form-control" >
                                   
                                </div>
                                            
                             
             </div>
             <div class="row infoS">
               
                 <div class="col-sm-5">
                      <div class="col-xs-5"> 
                          
                          
                          <strong>Clave</strong>
                          <select  id="cve_DES" BuType=1 class="broswer form-control">
                              <?php
                               ////*Ejecutamos la  COnsulta 
                          $qerGetcNom = mysqli_query($conecta1, "SELECT * FROM pedidos.pub_encabeza_publicidad where est_evidecia =1 ");      
                          
                                while($row= mysqli_fetch_array($qerGetcNom)) 
                                {
                                echo  "<option  value=".$row['pub_folio'].">".$row['pub_folio']."</option>"; 
                                    
                                }
                              ?> 
                          </select>
                      </div>  
                     <div class="col-xs-7">
                         <strong>Nombre de Agente</strong>
                         <select id="selecAgen" BuType=2 class="broswer form-control" >
                              <?php
                               ////*Ejecutamos la  COnsulta 
                          $qerGetcNom = mysqli_query($conecta1, "SELECT distinct cve_agente FROM pedidos.pub_encabeza_publicidad where est_evidecia =1");      
                          
                                while($row= mysqli_fetch_array($qerGetcNom)) 
                                {
                                    ////***Buscamos Nombre  Agente. SELECT  T0.[SlpName]  FROM OSLP T0 where T0.[SlpCode] =%s  
        			$string_getNomAge =  sprintf("SELECT  T0.[SlpName]as Nombre  FROM OSLP T0 where T0.[SlpCode] =%s", 
        												GetSQLValueString($row['cve_agente'], "int"));
        			$qeryGetNomAge = mssql_query($string_getNomAge);
        			$nomfethcAge = mssql_fetch_array($qeryGetNomAge);
                                echo  "<option  value=".$row['cve_agente'].">".$nomfethcAge['Nombre']."</option>"; 
                                    
                                }
                              ?> 
                         </select>
                      </div>   
                 </div>
                <div class="col-sm-5"> 
                    <br>
                     <div  class="col-sm-1"> 
                                    <button  id="Busdate" type ="button" class="btn btn-success"> Buqueda por  Fecha</button>
                                </div>       
                </div> 
                 
            </div> 
        </div>
   </div>
  <?PHP } ?>  
  <?PHP  IF($TypPeg==2){ ?>       
     <div class="row"><h2>Falta de Evidencia  Publicidad</h2></div>      
  <?php }?>      
        
        
 <!-- Fin   Filtro  Busqueda  Publicidad -->  
<!-------------------------------------------------------------------------------------------->
     
   <div id="contbusq"></div> 
   <!--dIV Inicio  CONTENEDOR Remove-->
   <div  class='ContTOREMOVE'>
  
       <?php 
      echo      '<div class="col-sm-12">';
        ///***Cadena para HISTORIAL 
       IF($TypPeg ==1){
        $string_get_info = "SELECT * FROM pedidos.pub_encabeza_publicidad where est_evidecia =1 " ;
        $qery_info = mysqli_query($conecta1, $string_get_info);
       }
      ////***Para ver  Pendientes 
       IF($TypPeg ==2){
        $string_get_info = "SELECT *  FROM pedidos.pub_encabeza_publicidad  where  est_evidecia= 0 and   auto_JINC = 1  " ;
        $qery_info = mysqli_query($conecta1, $string_get_info);
       }
        


            while($row = mysqli_fetch_array($qery_info)){

                                      ////***Buscamos Nombre  Agente. 
                                            $string_getNomAge =  sprintf("select nom_empleado from  pedidos.relacion_gerentes where cve_age = %s", 
                                                                                                                    GetSQLValueString($row['cve_agente'], "int"));
                                            $qeryGetNomAge =   mysqli_query($conecta1,$string_getNomAge);
                                            $nomfethcAge = mysqli_fetch_array($qeryGetNomAge);
                                    ///**Obteneos Total de la  Solicitud  
                                            $str_getTotalSol  =  sprintf("select  GetTotalSolicitud (%s) as TotalSol",GetSQLValueString($row['pub_folio'], "int") );
                                            $qeryGet=   mysqli_query($conecta1,$str_getTotalSol );
                                            $getTotalSol = mysqli_fetch_array($qeryGet);
       			
        ?>
         <div class="brdD row">
              <div class="row infoS">
             <div  class="col-sm-5"><h3>Folio:<?php echo $row['pub_folio'];?></h3></div> <div class="col-sm-4"><?php echo "<h4>Total : "."$".number_format($getTotalSol['TotalSol'], 2, '.', ',') ."</h4>"; ?></div><div  class="col-sm-3"><h4 class="posFech">Fecha Solicitud <?php echo $row['pub_fech_cap'];?></h4></div>
             </div>
             <div class="row infoS">
                 <div class="col-sm-5"><h5>Agente:</h5><h6><?php echo utf8_encode($nomfethcAge['nom_empleado']);?></h6> 
                     <button type="button" value="<?php echo $row['pub_folio'];  ?>" class="dtllpaquete btn btn-info buscar btn-small" data-toggle="collapse" data-target="#demo<?php echo $row['pub_folio'];  ?>">Detalles del Paquete <span class="glyphicon glyphicon-list"></span></button>
                   </div>
                         <div class="col-sm-4"> 
                              <?PHP  IF($TypPeg==2){ ?>       
                            <button  type="button"  class="sendmail btn btn-info"  id="sendMail<?php echo $row['pub_folio']; ?>"  infopri='<?php echo json_encode(array("cvefolio"=>$row['pub_folio']  ,"numeroGu"=>$row['num_guia'] ,"feCap"=>$row['pub_fech_cap'],"cvagente"=>$row['cve_agente']) );?>'> <span class="glyphicon glyphicon-envelope"></span></button>      
                 
                            <?php }?>  
                             
                
                         <?php  
                          ///IF($typesee == 1){ 
                      
                          ///IF($typesee == 1){ 
                            if($row['auto_JINC']==0)   
                            { echo  "<h5>Estatus: "."Pendiente"."</h5>" ; }
                            if($row['auto_JINC']==2)   
                            { echo  "<h5>Estatus: "."Rechazada"."</h5>" ; }
                            if($row['auto_JINC']==1)   
                            { echo  "<h5>Estatus: "."Autorizada"."</h5>" ; }
                 
                          ///}
                          IF($typesee == 3){ ?>
                          <div class="form-group">
                              <label><strong>Fecha De Recibido</strong></label>
                              <div class="input-group  input-group-lg">
                                <input id="REfech<?php echo $row['pub_folio'];?>" type="date" class="form-control"  >
                                <span   type="button"  class="recibtnfech input-group-addon btn  btn-success " value="<?php echo $row['pub_folio'];?>"> 
                                   <span class="glyphicon glyphicon-floppy-disk"></span> 
                                </span>
                             </div>    
                          </div> 
                          
                              
                       <?php   } ?>
                         </div> 
                 <form  action ="pub_seepublicidadwhitpre.php"  method="POST"> 
                     <div class="col-sm-1"><input hidden type="int" name="updateSol" value="<?php echo $row['pub_folio'];?>" ><button type="submit" class="btn btn-info buscar"><span class="glyphicon glyphicon-zoom-in"></span></button></div>
                     
                 </form>
               
                <!--Btn Para   ----> 
                <div class="col-sm-1" >
                      
                </div>
              </div>
           <!----------------------------------------------------------->
           <div id="demo<?php echo $row['pub_folio'];  ?>" class="collapse row">
                  <div  class="col-sm-12">
                      <div class="col-sm-6">
                          <div  class="form-group">
                              <label><strong>Tipo de Envio</strong></label>
                              <input disabled value="<?php if($row['typetosend']==1){ECHO  $Otro = "Tres Guerras";}
                                                           if($row['typetosend']==2){ECHO $Otro = "Estafeta"; }
                                                           if($row['typetosend']==3){ECHO $Otro = $row['otro'];}?>"  id="othersend<?php echo $row['pub_folio'];?>" class="othersend form-control" type="text">    
                         </div>
                          <div class="form-group">
                              <label><strong>Fecha De Envio</strong></label>
                              <input disabled  value="<?php echo $row['fech_envio'];?>"  id="fech<?php echo $row['pub_folio'];?>" type="date" class="form-control"  >
                          </div> 
                      </div>
                      <div class="col-sm-6">
                          <div class="form-group">
                              <label><strong>Numero de Guia</strong></label>
                              <input disabled value="<?php echo $row['num_guia'];?>" id="numgui<?php echo $row['pub_folio'];?>" type="text" class="form-control"  >
                          </div> 
                           <div class="form-group">
                              <label><strong>Fecha De Recibido</strong></label>
                              <input disabled  value="<?php echo $row['fech_rec'];?>"  id="fech<?php echo $row['pub_folio'];?>" type="date" class="form-control"  >
                          </div> 
                          <?PHP  IF($TypPeg==1){ ?> 
                           <table class="table table-striped">
                                <thead><strong> Evidencia</strong></thead>
                           <tbody  id="tbevi<?php echo $row['pub_folio'];  ?>">
                                       
                           </tbody>
                            </table>
                          <?php  }?> 
                      </div>
                     
                  </div>
              </div>
           <!---------------------------------------------------------->      
    </div>
           
         
         <br>
         <?php }?>      
          <!--dIV fIN CONTENEDOR Remove-->
       </div>
     </div>
  <!--************Dialog Estatus********************-->
     <div  class="modal fade" id="ModEst" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
                <button type="button" class="close_coment close" data-dismiss="modal">&times;</button>
                <h5>Enviar Mensage Agente<h5>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div id="CONTMOD" class="row" class="well">
                       <div class="col-sm-1"></div>
                        <div class="col-sm-10"><h4 id="nfMenEs"></h4><p><strong  id="btnNote"></strong></p></div>
                        <div class="col-sm-1"></div>
                        
                        
                    </div>
                  
                    <div  class="row" class="well">
                        
                            <div class="col-sm-4" ></div>
                            <div class="col-sm-1" ><h5 class="MensEl" ></h5> </div>
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-4" ></div>
                        
                    </div>
                     <div   id="BackEleTx" class="row" class="well">
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
                            <div class="col-sm-1" ><button  type="button"  class="btnsend  btn btn-info" value="3" data-dismiss="modal" >Enviar</button></div>
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

<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 
