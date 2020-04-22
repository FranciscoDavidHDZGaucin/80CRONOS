<?php
 ////****pub_makepaquechassis.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_makepaquechassis.php 
 	Fecha  Creacion :08/08/2017
	Descripcion  : 
 *             Escrip Diseñado  para  Autorizar  Solicitudes  de Publicidad
 *              
 *              Se Crea la Variable typesee => 1 = > Visor  de Pedidos  Pendientes  y  por  Autorizar  
 *              Se Crea la Variable typesee => 2 = > Para   Activar la  opcion de generador  de  Paquete 
 *               Se Crea la Variable typesee => 3 = > Para Mostrar  las solicitudes  de publicidad  pendientes
 *                                                    para  asignar  fecha de  Recivido.
 * 
  */

////**Inicio De Session 
	session_start();
  require_once 'header_asisIC.php';
require_once('Connections/conecta1.php');
///*****        
require_once('formato_datos.php');
///**+Establecemos   Update  a  Estado  1  
$_SESSION['UbdatePub'] =1 ;
////****Obtenemos el  TIPO DE  DESPLIEGUE 
$typesee = filter_input(INPUT_POST, 'typesee');         
        
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
        margin-top: 35px;
}
button.btn.btn-sucess.buscar {
    background-color: #33a471;
}
button#btnEst {
    margin-bottom: 18px;
}

</style>
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!--Escrip  para  validar  Fecha de Req-->
<script src ="adicional_scrip/fechas_fail_other.js"></script>
<script type="text/javascript" src="pub_scrip_publicidad/pub_func_publicidad.js"></script>
<script type="text/javascript">
   
   $(document).ready(function(){
       var  Nf ;
       //////**** Variable   Folio Variable 
       var    nfolioVar;
       ////**Variable  Global Paquete Objeto  Json 
       var    ObjGen ;
       ////**Variable  Fecha  recepcion 
       var   fechREP;
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
                    $('#ModEst').modal('show');  
                  nfolioVar =  $(this).attr('value');
	});
        /////**Btn  Send Estatus 
        $(document).on("click",".btnEstatus",function(){
            
                        
                         
                          $.ajax({
                                    type:'POST',
                                    url: 'pub_scrip_publicidad/pub_addautoJIC.php',
                                    data:{"EstAu":$(this).attr('value'), "nFol":nfolioVar}, 
                                    success: function (datos) { 
                                                    console.log(datos);
                                                        $('#ModEst').modal('toggle');
                                 }
                           });
        });
       
    ///*****Selec   tipe de  envio 
    $(".typesend").change(function(){
       ///***Obtenemos  el  Identificador del   select  as
        var  Idselect = "#typesend"+ $(this).attr("fnlse") +" option:selected";
      
      if($(Idselect).val() == 3 )
      {     
          ///**Habilitamos el Input Texto
          var  divconotro = "#contOthersen"+ $(this).attr("fnlse"); 
          $(divconotro).attr("hidden",false);
          ///**
          var inputnumguia  = "#numgui"+$(this).attr("fnlse");
           $(inputnumguia).attr("disabled",true); 
          $(inputnumguia).val("777");
          
      }else{
          ///**Desabilitamos  Input Texto 
          var  divconotro = "#contOthersen"+ $(this).attr("fnlse"); 
          $(divconotro).attr("hidden",true);
          ///**
          var inputnumguia  = "#numgui"+$(this).attr("fnlse");
           $(inputnumguia).attr("disabled",false); 
          $(inputnumguia).val("");
      }
        
    });
    ///***************************************sd
    $(".terPaq").click(function(){
        ////***Obtenemos la  Informacion
         var  flbtn  =  $(this).attr('value');
       ///**Creamos los  Identifiacadores   
         var  Idselect = "#typesend"+flbtn +" option:selected"; 
         var inputnumguia  = "#numgui"+flbtn;
         var  txtOtro = "#othersend"+flbtn;
         var  fechenvio  = "#fech"+flbtn;
         
         ///***Validamos Que  la  fecha Req No sea  Null    y el  alamacen 
       if (ValidarFecha($(fechenvio).val())== false && InputDate_enable() == true  )
       {
       alert("Lo Sentimos la Fecha No Tiene El Formato Correcto dd/mm/año ");    
       }else{
           
            if($(fechenvio).val() === null ||$(inputnumguia).val() == "" || ($(Idselect).val() == 3 && $(txtOtro).val()=="") ) 
            {
                alert("Imposible  Guardar Se Detectaron Parametros Vacios"); 
            }else{  
                 ////****Obtenemos los  valores  
                 var  Paquete = {
                     "fl" :flbtn,
                     "typeSend": $(Idselect).val(), 
                     "num_guia": $(inputnumguia).val(),
                     "txtOtro" :$(txtOtro).val(),
                     "fech_envi" : convertoTODB($(fechenvio).val()) 
                 };
                ObjGen =Paquete;
                 $("#titlefl").text(Paquete.fl);
                 $("#NumGuia").text(Paquete.num_guia);
                 $("#fechaEnvi").text(Paquete.fech_envi);
                 $("#ModEst").modal("show"); 

            }
      }  
        
        
         
    });
    /////**Btn  Par Generar el  Paquete 
    $(".btnEstatus").click(function(){
        $.ajax({
                type:'POST',
                url: 'pub_scrip_publicidad/pub_genpaquete.php',
                data:{"OPC":1, "PAQUE" :JSON.stringify(ObjGen) } , 
                success: function (datos) { 
                    if(datos.RES==1)
                    {
                       location.reload(); 
                    }else{
                      $("#contenedorInfodi").empty();
                      $("#contenedorInfodi").html("<strong>Existe Un problema con la Conecxion no se realizo el Paquete</strong>");
                    } 
                    
                    
                    
                }
             });
    });
    ///***Btn Fecha 
    $(".recibtnfech").click(function(){
         var  flbtn  =  $(this).attr('value');
        var  fechre  = "#REfech"+flbtn;
         
         ///***Validamos Que  la  fecha Req No sea  Null    y el  alamacen 
       if (ValidarFecha($(fechre).val())== false && InputDate_enable() == true  )
       {
       alert("Lo Sentimos la Fecha No Tiene El Formato Correcto dd/mm/año ");    
       }else{
           
            if($(fechre).val() === null  ) 
            {
                alert("Imposible  Guardar Se Detectaron Parametros Vacios"); 
            }else{  
                 ////****Obtenemos los  valores  
                 fechREP ={ "fl":flbtn ,"fech_RE":convertoTODB($(fechre).val())};
                $("#titlefl").text(flbtn);
                $("#fetchrecidia").text($(fechre).val());
                $("#ModEst").modal("show"); 

            }
      } 
    });
    ////***Btn  Enviar la  Fecha 
    $(".btnfecharec").click(function(){
        $.ajax({
                type:'POST',
                url: 'pub_scrip_publicidad/pub_genpaquete.php',
                data:{"OPC":2 ,"PAQUE":JSON.stringify(fechREP) } , 
                success: function (datos) { 
                    if(datos.RES==1)
                    {
                       location.reload(); 
                    }else{
                      $("#contenedorInfodi").empty();
                      $("#contenedorInfodi").html("<strong>Existe Un problema con la Conecxion no se realizo el Paquete</strong>");
                    } 
                    
                    
                    
                }
             });
    });
       
       
   });
   
 </script>
 <div  class="container">
   
        <?php 
        //////***Visualizamos  el  Estatus  de las  Publicidades    
        IF($typesee == 1){?> 
     
       <div class="row"><h2>Estatus  Publicidad</h2></div>
        
        <?php  }
        ////**+Visualizamos  el  Generador de  Paquetes  
        IF($typesee==2){ ?> 
           <div class="row"><h2>Generar Paquetes Publicidad</h2></div>
       
       <?php } ?>  
        <?php  
        ////**+Visualizamos  paquetes con falta de Fecha de  Recibido  
        IF($typesee==3){ ?> 
           <div class="row"><h2> Paquetes Pendientes Fecha  </h2></div>
       
       <?php } ?>   
         
         
     
    <div class="col-sm-12">
        <?php 
        //////***Visualizamos  el  Estatus  de las  Publicidades    
        IF($typesee == 1){ 
        ///***Cadena para Hacer
        $string_get_info = "SELECT pub_folio,pub_fech_cap,cliente,auto_JINC,cve_agente  FROM pedidos.pub_encabeza_publicidad  where  auto_JINC = 0 ";
        $qery_info = mysqli_query($conecta1, $string_get_info);
        }
        ////**+Visualizamos  el  Generador de  Paquetes  
        IF($typesee==2){
           ///***Cadena para Hacer
        $string_get_info = "SELECT pub_folio,pub_fech_cap,cliente,auto_JINC,cve_agente  FROM pedidos.pub_encabeza_publicidad  where  auto_JINC = 1 and typetosend =0 ";
        $qery_info = mysqli_query($conecta1, $string_get_info);  
            
        }
        ////**+Visualizamos  paquetes con falta de Fecha de  Recibido  
        IF($typesee==3){ 
        ///***Cadena para Hacer
        $string_get_info = "SELECT pub_folio,pub_fech_cap,cliente,auto_JINC,cve_agente  FROM pedidos.pub_encabeza_publicidad  where typetosend !=0 and (fech_rec is null OR fech_rec ='0000-00-00'  )";
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
             <div  class="col-sm-5"><h3>Folio:<?php echo $row['pub_folio'];?></h3></div> <div class="col-sm-4"><?php echo "<h4>Total : "."$".number_format($getTotalSol['TotalSol'], 2, ',', ' ') ."</h4>"; ?></div><div  class="col-sm-3"><h4 class="posFech">Fecha Solicitud <?php echo $row['pub_fech_cap'];?></h4></div>
             </div>
             <div class="row infoS">
                 <div class="col-sm-5"><h5>Agente:</h5><h6><?php echo utf8_encode($nomfethcAge['nom_empleado']);?></h6></div>
                         <div class="col-sm-4"> 
                         <?php  
                          IF($typesee == 1){ 
                            if($row['auto_JINC']==0)   
                            { echo  "<h5>Estatus: "."Pendiente"."</h5>" ; }
                            if($row['auto_JINC']==2)   
                            { echo  "<h5>Estatus: "."Rechazada"."</h5>" ; }
                          }
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
                   <?php  
                    ////**+Visualizamos  el  Generador de  Paquetes  
                    IF($typesee==2){ ?> 
                    <button type="button" class="btn btn-info buscar" data-toggle="collapse" data-target="#demo<?php echo $row['pub_folio'];  ?>">Generar Paquete</button>
                    <?php  }?>   
                </div>
              </div>
              <div id="demo<?php echo $row['pub_folio'];  ?>" class="collapse row">
                  <div  class="col-sm-12">
                      <div class="col-sm-6">
                          <div  class="form-group">
                              <label><strong>Seleccione Tipo de Envio</strong></label>
                              <select  id="typesend<?php echo $row['pub_folio'];  ?>"  fnlse ="<?php echo $row['pub_folio'];?>"  class="typesend  form-control"  >
                                  <option selected  value="1">Tres Guerras</option>
                                  <option value="2"> Estafeta</option>
                                  <option value="3">Otro</option>
                              </select>
                          </div> 
                          <div hidden  id="contOthersen<?php echo $row['pub_folio'];?>" class="contOthersen form-group">
                              <label><strong>Otro Tipo de Envio</strong></label>
                              <input  id="othersend<?php echo $row['pub_folio'];?>" class="othersend form-control" type="text">    
                          </div> 
                          
                      </div>
                      <div class="col-sm-6">
                          <div class="form-group">
                              <label><strong>Numero de Guia</strong></label>
                              <input id="numgui<?php echo $row['pub_folio'];?>" type="text" class="form-control"  >
                          </div> 
                         <div class="form-group">
                              <label><strong>Fecha De Envio</strong></label>
                              <input id="fech<?php echo $row['pub_folio'];?>" type="date" class="form-control"  >
                          </div>    
                          <button  type="button"  class="terPaq btn btn-success btn-block" value="<?php echo $row['pub_folio'];?>"  >Terminar  Paquete</button>
                      </div> 
                  </div>
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
                <?php  
                IF($typesee==2){ ?> 
                        <h5>Generar Paquete <strong id="titlefl"></strong></h5>
            
               <?php } ////**+Visualizamos  paquetes con falta de Fecha de  Recibido  
                IF($typesee==3){ ?> 
                          <h5>Asignar Fecha de Recibido  <strong id="titlefl"></strong></h5>          
               <?php } ?>
                
                
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div id="contenedorInfodi" class="row" class="well">
                       
                        <?php  
                        IF($typesee==2){ ?> 
                             <div class="col-sm-6">
                                <label>Numero de  Guia :<strong id="NumGuia"></strong></label>   
                            </div>
                            <div class="col-sm-6">
                                <label>Fecha de  Envio: <strong id="fechaEnvi"></strong></label>
                            </div>
                       <?php } ////**+Visualizamos  paquetes con falta de Fecha de  Recibido  
                        IF($typesee==3){ ?> 
                        <h4>Fecha : <strong id="fetchrecidia"></strong></h4>
                       <?php } ?>   
                    </div>
                     <div  class="row" class="well">
                        
                            <div class="col-sm-4" ></div>
                            <div class="col-sm-1" > 
                              
                                <?php  
                                IF($typesee==2){ ?> 
                                <button  type="button"  class="btnEstatus  btn btn-info" >Guardar</button>

                               <?php } ////**+Visualizamos  paquetes con falta de Fecha de  Recibido  
                                IF($typesee==3){ ?> 
                                     <button  type="button"  class="btnfecharec  btn btn-info" >Guardar</button>          
                               <?php } ?>
                            </div>
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-1" ><button  type="button"  class="  btn btn-danger" data-dismiss="modal">Cancelar</button></div>
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