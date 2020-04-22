<?php
////****desv_estseePlan.php  

/*  desv_estseePlan.php?TypePg=1 
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : desv_estseePlan.php
 	Fecha  Creacion :31/07/2017
	Descripcion  : 
 *             Escrip Diseñado  para Revisar  Estatus de  Desviaciones  Planeador 
 * 
 *              Definimos La Variable de Tipo de Busqueda BuType 
 *              La cual  se entiende como:
 *                  BuType => 1 => Busqueda por Clave de Desviacion 
 *                  BuType => 2 => Busqueda por Nombre de Agente 
 *                  BuType => 3 => Busqueda por Fecha de  Evaluacion Inicio 
 *                  BuType => 4 => Busqueda por Fecha de  Evaluacion Final 
 *       
  */

////**Inicio De Session 
	 session_start ();
   $MM_restrictGoTo = "index_inteligencia.php";
   if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
///****Cabecera Cronos +
require_once 'header_planeador.php';
require_once('Connections/conecta1.php');
///*****        
require_once('formato_datos.php');
require_once('conexion_sap/sap.php');
mssql_select_db("AGROVERSA");
        
$TypPeg = filter_input(INPUT_GET, 'TypePg');          
      
if($TypPeg==1){
///***Cadena para Hacer
            // $string_get_info = "SELECT folio , cve_agente , fecha_sol, estatus_Ic ,estatus_Dc FROM pedidos.coti_encabeca_cotizacion  where    estatus_Ic = 0  and cve_agente =".$idagente  ;
        $string_get_info = "SELECT cve_desvi,cve_agente,fech_Ini,fech_fin,estas_ans FROM pedidos.desv_encabeza_desviacion  where  estas_ans=0  "  ;
}       
if($TypPeg==2){
   ///***Cadena para Hacer
            // $string_get_info = "SELECT folio , cve_agente , fecha_sol, estatus_Ic ,estatus_Dc FROM pedidos.coti_encabeca_cotizacion  where    estatus_Ic = 0  and cve_agente =".$idagente  ;
        $string_get_info = "SELECT cve_desvi,cve_agente,fech_Ini,fech_fin,estas_ans FROM pedidos.desv_encabeza_desviacion  where  estas_ans=1  "  ; 
    
}        
        
        $qery_info = mysqli_query($conecta1, $string_get_info);
        $AreglObjetos =  Array();
        while($row = mysqli_fetch_array($qery_info)){
            
            
                ////***Buscamos Nombre  Agente. SELECT  T0.[SlpName]  FROM OSLP T0 where T0.[SlpCode] =%s  
        			$string_getNomAge =  sprintf("SELECT  T0.[SlpName]as Nombre  FROM OSLP T0 where T0.[SlpCode] =%s", 
        												GetSQLValueString($row['cve_agente'], "int"));
        			$qeryGetNomAge = mssql_query($string_getNomAge);
        			$nomfethcAge = mssql_fetch_array($qeryGetNomAge);
                               
              $OBJETO = array(
                               cve_DES => $row['cve_desvi'],
                               cve_AGE => $row['cve_agente'],
                                fe_ini => $row['fech_Ini'],
                                fe_fin => $row['fech_fin'],
                                estas_ans => $row['estas_ans'],
                                nomAgen => $nomfethcAge['Nombre']
                                
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
<script type="text/javascript">
      var   ArregloMain  = <?php  echo $ArregloMain; ?>;
      var   ELMbUS =1;
      var strinicioBus  ="<div id='ConteResBus' ><div id='objbusq"+ELMbUS+"'></div></div>";
      ///****Variable  Temporal Envio de  Correos 
      var   tempMail = null  ; 
      /////***Funcion  para  Buscar  los  elementos 
      function   BroswerElems (Bustype,elem)
      {         
          var  htmlObjetos ="";  
        for(var  i in ArregloMain  ) { 
                    switch(Bustype){
                        case  "1":  ///BuType => 1 => Busqueda por Clave de Desviacion 
                             if(ArregloMain[i].cve_DES ==elem )
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
                              var  fechaelm =  new  Date(elem); 
                              var    arreglofech  =  new  Date(ArregloMain[i].fe_ini);
                               if(fechaelm.getMonth()==arreglofech.getMonth() )
                                {
                                     ///*Mandamos  Mostrar  el  elemento
                                     console.log(PutHtmlElem(ArregloMain[i]));
                                    htmlObjetos +=  PutHtmlElem(ArregloMain[i])
                               } 
                              
                              
                        break;
                        case  "4":
                              var  fechaelm =  new  Date(elem); 
                              var    arreglofech  =  new  Date(ArregloMain[i].fe_fin);
                               if(fechaelm.getMonth()==arreglofech.getMonth() )
                                {
                                     ///*Mandamos  Mostrar  el  elemento
                                     console.log(ArregloMain[i]);
                                    htmlObjetos +=  PutHtmlElem(ArregloMain[i])
                               } 
                              
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
                ///Condicion para  Mostrar  la  bARRA DE eSTATUS 
               if(Objeto.estas_ans==0)   { estatusElemnto= "<div class='opcEstPENDIENTE'> ";  estatusElemnto +=  "<strong>Estatus: Pendiente </strong> </div>" ; }
               ////*Generamos Json  de  Envioa 
               var   jsontoSend  =JSON.stringify( {"cvagente":Objeto.cve_AGE ,"cveDes":Objeto.cve_desvi,"feIni":Objeto.fe_ini,"feFin":Objeto.fe_fin});
            strHtml =  '<div class="col-sm-12">'+
                                    '<div class="brdD row">'+
                                        estatusElemnto+
                                     
                                            '<div class="row infoS">'+
                                                   '<div  class="col-sm-5"><h3>Clave Desviacion:'+ Objeto.cve_DES+'</h3></div><div  class="col-sm-3"><strong class="posFech">Fecha Inicio Evaluacion:'+Objeto.fe_ini +'<strong></div>'+
                                                   '<div class="col-sm-1"></div><div  class="col-sm-3"><strong class="posFech">Fecha Fin Evaluacion:'+Objeto.fe_fin+'</strong></div>'
                                            +'</div>'
                                            +'<div class="row infoS">'+
                                                   '<div class="col-sm-5"><div class="col-xs-5">'+
                                                    '<button  type="button"  class="sendmail btn btn-info"  id="sendMail'+Objeto.cve_DES+'"  infopri='+jsontoSend+'> <span class="glyphicon glyphicon-envelope"></span></button>'+      
                                                   '</div><div class="col-xs-7"></div></div>'+
                                                   '<div class="col-sm-5">'+
                                                      '<strong>Nombre Agente:'+Objeto.nomAgen+'</strong>'+
                                                      '<form  action ="desv_seeDesviaciones.php?TyPg=3"  method="POST">'+ 
                                                   '<input hidden type="text" name="FechbS" value='+jsontoSend+'><button type="submit" class="btn btn-info buscar"><span class="glyphicon glyphicon-zoom-in"></span></button>'+
                                                 '</form>'  
                                                 +'</div>'+
                                                '<div class="col-sm-1" ></div>'  
                                            +'</div>'+
                                     '</div>'
                            +'</div>'; 
                            
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
$(document).on("click",".btnsend",function (){
   
      console.log("jk");
   $.ajax({
                type:'POST',
                url: 'scrip_desviaciones/desv_senmailAgent.php',
                data:{"cometPla":$("#txtComeJI").val(),"Obj":JSON.stringify(tempMail)}, 
                success: function (datos) {
                    $("#ModEst").modal("hidden");
                  $("#txtComeJI").val(""); 
                      
             }});


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
    
});
</script>
 <div ID="MAINCON"  class="container">

        <?php 
        ///***Opcion para Update
        if($TypPeg==1){
        echo       '<div class="row"><h2>Estado  Desviaciones </h2></div>';}
         if($TypPeg==2){
          echo       '<div class="row"><h2>Historial de Desviaciones</h2></div>';}
        
      ?> 
     
   <!-- Inicio  Filtro  Busqueda  Desviaciones-->  
   <div class="col-sm-12">
       <div   id='Fil'  class='brdD row'>
                <div class="row infoS">
                    <div  class="col-sm-5"><h6>Busque Desviaciones</h6>
                                </div><div  class="col-sm-3"><strong class="posFech">Fecha Inicio Evaluacion:<strong>
                                  <select id="fechInic" butype=3 class="broswer form-control" >
                                    <?php
                                     ////*Ejecutamos la  COnsulta 
                                $qefetIni = mysqli_query($conecta1, "SELECT  fech_Ini,fech_fin from  pedidos.desv_encabeza_desviacion  where  estas_ans=0  group by  fech_Ini");      

                                      while($row= mysqli_fetch_array($qefetIni)) 
                                      {
                                          
                                      echo  "<option  value=".$row['fech_Ini'].">".$row['fech_Ini']."</option>"; 

                                      }
                                    ?> 
                               </select>
                                </div>
                                <div class="col-sm-1"></div>
                                <div  class="col-sm-3"><strong class="posFech">Fecha Fin Evaluacion:</strong>
                                  <select id="fechFin" BuType=4 class="broswer form-control" >
                                    <?php
                                     ////*Ejecutamos la  COnsulta 
                                $qefetIni = mysqli_query($conecta1, "SELECT  fech_Ini,fech_fin from  pedidos.desv_encabeza_desviacion  where  estas_ans=0  group by  fech_Ini");      

                                      while($row= mysqli_fetch_array($qefetIni)) 
                                      {
                                          
                                      echo  "<option  value=".$row['fech_fin'].">".$row['fech_fin']."</option>"; 

                                      }
                                    ?> 
                               </select>
                                </div>
                                                
                             
             </div>
             <div class="row infoS">
               
                 <div class="col-sm-5">
                      <div class="col-xs-5"> 
                          
                          
                          <strong>Clave</strong>
                          <select  id="cve_DES" BuType=1 class="broswer form-control">
                              <?php
                               ////*Ejecutamos la  COnsulta 
                          $qerGetcNom = mysqli_query($conecta1, "SELECT cve_desvi,cve_agente  from  pedidos.desv_encabeza_desviacion  where  estas_ans=0");      
                          
                                while($row= mysqli_fetch_array($qerGetcNom)) 
                                {
                                echo  "<option  value=".$row['cve_desvi'].">".$row['cve_desvi']."</option>"; 
                                    
                                }
                              ?> 
                          </select>
                      </div>  
                     <div class="col-xs-7">
                         <strong>Nombre de Agente</strong>
                         <select id="selecAgen" BuType=2 class="broswer form-control" >
                              <?php
                               ////*Ejecutamos la  COnsulta 
                          $qerGetcNom = mysqli_query($conecta1, "SELECT cve_desvi,cve_agente  from  pedidos.desv_encabeza_desviacion  where  estas_ans=0");      
                          
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
                            
                </div> 
                 
            </div> 
        </div>
   </div>
   <div id="contbusq"></div> 
   <!--dIV Inicio  CONTENEDOR Remove-->
  <?php echo   "<div  class='ContTOREMOVE'>"; ?> 
   <!-- Fin   Filtro  Busqueda  Desviaciones-->  
       <?php 
        ///***Opcion para Update
        if($TypPeg==1){
  
      echo      '<div class=" col-sm-12">';
        ///***Cadena para Hacer
            // $string_get_info = "SELECT folio , cve_agente , fecha_sol, estatus_Ic ,estatus_Dc FROM pedidos.coti_encabeca_cotizacion  where    estatus_Ic = 0  and cve_agente =".$idagente  ;
        $string_get_info = "SELECT cve_desvi,cve_agente,fech_Ini,fech_fin,estas_ans FROM pedidos.desv_encabeza_desviacion  where  estas_ans=0  "  ;
        $qery_info = mysqli_query($conecta1, $string_get_info);
        }
        ///***Opcion para Historial
        if($TypPeg==2){
      
    
      echo      '<div class="col-sm-12">';
        ///***Cadena para Hacer
        $string_get_info = "SELECT cve_desvi,cve_agente,fech_Ini,fech_fin,estas_ans FROM pedidos.desv_encabeza_desviacion  where estas_ans != 0 " ;
        $qery_info = mysqli_query($conecta1, $string_get_info);
        }



        while($row = mysqli_fetch_array($qery_info)){

        		  ////***Buscamos Nombre  Agente. 
        			$string_getNomAge =  sprintf("select nom_empleado from  pedidos.relacion_gerentes where cve_age = %s", 
        												GetSQLValueString($row['cve_agente'], "int"));
        			$qeryGetNomAge =   mysqli_query($conecta1,$string_getNomAge);
        			$nomfethcAge = mysqli_fetch_array($qeryGetNomAge);
       			
        ?>
         <div class="brdD row">
             
                  <?php ///Fin Condicion para Modificar
                                     //***Inicio Funcion de Historial 
                          if($TypPeg==2){
                              if($row['estas_ans']==1)   { 
                                        echo "<div class='opcEstAUTO'> ";
                                        echo  "<strong>Estatus: Contestado </strong>";
                              }
                                       
                          }  
                          
                          ?> 
                          <?php 
                          /*
                                Estatus  de  Autorizacion de  Inteligencia  Comercial 
                                0 => Pendiente
                           
                           *                            */
                                    if($row['estas_ans']==0)   { echo "<div class='opcEstPENDIENTE'> ";  echo  "<strong>Estatus: "."Pendiente"."</strong>" ; }
                           
                          
                          ?>
                 </div> 
             <div class="row infoS">
                 <div  class="col-sm-5"><h3>Clave Desviacion:<?php echo $row['cve_desvi'];?></h3></div><div  class="col-sm-3"><strong class="posFech">Fecha Inicio Evaluacion: <?php  $dt = new DateTime($row['fech_Ini']);  echo $dt->format("d/m/Y") ;?><strong></div>
                             <div class="col-sm-1"></div><div  class="col-sm-3"><strong class="posFech">Fecha Fin Evaluacion: <?php  $dt = new DateTime($row['fech_fin']);  echo $dt->format("d/m/Y") ;?></strong></div>
                                                
                             
             </div>
             <div class="row infoS">
               
                 <div class="col-sm-5">
                      <div class="col-xs-5">  
                          <button  type="button"  class="sendmail btn btn-info"  id="sendMail<?php echo $row['cve_desvi']; ?>"  infopri='<?php echo json_encode(array("cvagente"=>$row['cve_agente']  ,"cveDes"=>$row['cve_desvi'],feIni=>$row['fech_Ini'],"feFin"=>$row['fech_fin']));?>'> <span class="glyphicon glyphicon-envelope"></span></button>      
                      </div>  
                     <div class="col-xs-7">
                        
                     </div>   
                 </div>
                <div class="col-sm-5"> 
                     <?php   ////***Buscamos Nombre  Agente. SELECT  T0.[SlpName]  FROM OSLP T0 where T0.[SlpCode] =%s  
        			$string_getNomAge =  sprintf("SELECT  T0.[SlpName]as Nombre  FROM OSLP T0 where T0.[SlpCode] =%s", 
        												GetSQLValueString($row['cve_agente'], "int"));
        			$qeryGetNomAge = mssql_query($string_getNomAge);
        			$nomfethcAge = mssql_fetch_array($qeryGetNomAge);
                                echo  "<strong>Nombre Agente:".$nomfethcAge['Nombre']."</strong>";  
                                
                      ?> 
                </div> 
                 
               <?php  
               ///***Validacion  Btn  Visor de Cotizaciones 
              // if($TypPeg==2){?>
                <!-- <form  action ="desv_seeDesviaciones.php?<?php// echo "TyPg=2";  ?> "  method="POST"> 
                        <div class="col-sm-1">
                            <input hidden type="text" name="FechbS" value='<?php// echo json_encode(array("cveDes"=>$row['cve_desvi'],"feIni"=>$row['fech_Ini'],"feFin"=>$row['fech_fin']));?>' ><button type="submit" class="btn btn-info buscar"><span class="glyphicon glyphicon-zoom-in"></span></button>
                        </div>

                    </form> -->
               <?php // }///Fin  Validacion  Btn   Update    
               ///***Validacion Opcion solo  para Modificacion 
              ///  if($TypPeg==1){   ?> 
                
                <!--Btn Para   ----> 
                <div class="col-sm-1" >
                  <form  action ="desv_seeDesviaciones.php?TyPg=3"  method="POST"> 
                             <!--  <div class="col-sm-1"> -->
                             <input hidden type="text" name="FechbS" value='<?php echo json_encode(array("cvagente"=>$row['cve_agente']  ,"cveDes"=>$row['cve_desvi'],feIni=>$row['fech_Ini'],"feFin"=>$row['fech_fin']));?>' ><button type="submit" class="btn btn-info buscar"><span class="glyphicon glyphicon-zoom-in"></span></button>
                             <!--  </div>-->
                           </form>
                </div>
               <?php // }?> 
                 
             </div>
           
         </div> 
         <br>
         <?php }?>      
          <!--dIV fIN CONTENEDOR Remove-->
             <?php  echo  '</div>';?> 
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


 </div>
<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 