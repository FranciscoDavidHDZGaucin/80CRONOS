<?php
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : proyeccion_carga_masiva.php  
 	Fecha  Creacion : 13/01/2017
	Descripcion  : 
            Escrip Principal  para la  Carga  masiva  de  Proyecciones 
  *         Solicitado  por Brenda  Mestas con  Base  a  Minuta 29/12/2017 
	Modificado  Fecha  : 
  *        13/01/2017  Inicio   diseño prototipo  para la Carga Masiva   ademas de  la implementacion 
  *                    de  lectura del  archivo   excel a  enviar.  
  *                    COMO   DISEÑO  PRELIMINAR    EJECUTAMOS  EN ESCRIP  EN LA  PLATAFORMA DEL  
  *                     PLANEADOR  PARA LA  PRUEBAS  DE  FUNCIONAMIENTO.
  *        07/06/2017  Se  agregar La Opcion de demanda 0    
  * 
*/
////***Cabecera  Cronos 
require_once('header_planeador.php');


?> 
<!--Inicio  Librerias---> 
    <!--JQuery jquery.min.js" -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script> 
<!-----> 
<style>
    #insert_error {
        background :RED;
    } 
    #insert_correc{
        background: #2ecc71;
    }
    #insert_update{
        background: #f1c40f;
    }
    
</style>
<!--Contenedor  Javascript--> 
<script type="text/javascript" > 
    $(document).ready(function(){
    
    var elemtos_A_Enviar,Num_elem_En;
    
    $("#sen_arch").hide();
       ////**Obtenemos la  fecha Actual
    var  D = new  Date();
    var  yy = D.getFullYear();
    var  mm =D.getMonth()+1;
    $("#YEARD0").val(yy);
    $("#MONTHD0").val(mm);
    $("#APLICARD0").hide();
    
    ///*****Boton para Aplicar   
    $('#actD0').click(function(){
     /////***Comparamos la Fecha Actual Con la  fecha  Obtenidad en  los  Inputs
      var  DiaObtenidaNa =   new   Date() ;
      var  YYY=$("#YEARD0").val();
      var  MM =$("#MONTHD0").val();
     /////**Generamos  Fecha a comparar 
      var  fechaCom = YYY+"-"+MM+"-"+"01";
      var FechaInputCoparar = new  Date(fechaCom);
     if(FechaInputCoparar>DiaObtenidaNa )
     {
         ////***La fecha  es  mayor  
        $("#txtMens").text("Fecha Correcta para Modificar");
        $("#actD0").prop("disabled",true);
         $("#MONTHD0").prop("disabled",true);

        $("#APLICARD0").show();
     }else{
         ///**La fecha  es  Menor  no se Puede MOdificar
          $("#txtMens").text("Error Fecha No es Valida"); 
     } 
    });
    ////*****Btn Ejecutar Cambios
     $("#APLICARD0").click(function(){
     /////***Comparamos la Fecha Actual Con la  fecha  Obtenidad en  los  Inputs
      var  DiaObtenidaNa =   new   Date() ;
      var  YYY=$("#YEARD0").val();
      var  MM =$("#MONTHD0").val();
     /////**Generamos  Fecha a comparar 
      var  fechaCom = YYY+"-"+MM+"-"+"01";
      var FechaInputCoparar = new  Date(fechaCom);
          $.ajax({
                url: "carga_masiva_scrips/demandaCero.php ",
                type: "POST",
                data:{"YYY" :YYY, "MMM":MM } ,
               success: function (datos){
                                $("#txtMens").text(datos.Re);
                               }
                   });
     });
     ////***Btn Cancelar Demanda 0
     $("#close_D0").click(function(){
         
          $("#actD0").prop("disabled",false);
         $("#MONTHD0").prop("disabled",false);
         $("#APLICARD0").hide();
         
     });
     ///***Btn Mostra Dialog Demanda 0 click
    $("#seeDe0").click(function(){
    
        $("#Mdemanda0").modal('show');
       
    
    });
    //////*******************************************
    ///*****Boton  para  Enviar  el  Archivo  a Analizar     
    $('#ana_arch').click(function(){
        Modal_Send_Arch();
    });     
           
  //////******Funcion  Modal Send  Para enviar  Archivos***************************
   function    Modal_Send_Arch()
   {
     ///***Validacion  Archivo 
    if($('#up_elem').get(0).files.length ===0 ){
             ///  $('#cont_mensg').html("<p class='este_error'>Error No sé a  Seleccionado  un archivo</p>");
             ///  $('#cont_mensg').show();
    }
    else{   ///******Inicio  Else
            var archi =  document.getElementById('up_elem');
                var   file = archi.files[0];
                var   datos =  new  FormData();
                datos.append("ARCH",file );
                ///datos.append("UPDATE_EST",1);
                ///datos.append("ID_PAGA",Num_Pag);
                ///7datos.append("key",$('#cve_cliente').val());
                ///var formato = file.type.split('/').pop();
                    
             ///****** Inicio Peticion Ajax ***************************************************************+
             $.ajax({
                 url: "carga_masiva_scrips/lectura_archivo.php",
                 type: "POST",
                 data:datos ,
                 contentType:false, //Debe estar en false para que pase el objeto sin procesar
                 processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
                 cache:false, //Para que el formulario no guarde cache
               success: function (datos) {
                  $("#cont_result").html(datos.HTML);
                   ///console.log (datos.PROYE) ;
                   Num_elem_En =  datos.NElem;
                ////****Peticion para  Validar
                        $.ajax({
                                url: "carga_masiva_scrips/validar_informacion.php",
                                type: "POST",
                                data:{"ELEMENTOS" :datos.PROYE, "NELEM":datos.NElem } ,
                               success: function (datos) {
                                  // console.log(datos.elem_val);
                                     ///***Funcion  Resultado  Validacion 
                                     //***Validamos  Autorizacion 
                                     if(datos.AUTO_PARA_ENVIO==1)
                                     {
                                         ///***Mostramos  Mensage Exito 
                                         $('#Modal_est').modal('show');
                                         $('#title_mensa').text("Archivo  Correcto !!!");
                                         $("#sen_arch").show();
                                         elemtos_A_Enviar = datos.elem_val;
                                         
     
                                     }else{
                                              /// $('#cont_error').html();
                                           ///BACKGROUND: RED;
                                         ///****Obtenemos Numero de Elementos  con Error 
                                         var fail_proyeccion =  datos.Elem_ERRO;
                                          $('#Modal_est').modal('show');
                                         /// $('#title_mensa').text("Lo Sentimos Error!!!");
                                          // console.log(fail_proyeccion[0]);
                                      
                                         var  tr_html = ""; 
                                         var proyec_arre  =   JSON.parse(datos.elem_val);
                                         
                                         
                                        /// console.log(datos.Elem_ERRO[i]);
                                         for(i=0; i<datos.Elem_ERRO.length;i++)
                                         {
                                            var proyeccion_ ; 
                                            ///***Buecamos el elemento con erro  para  Mostrarlo 
                                            for(j=0; j<proyec_arre.length;j++ )
                                            {
                                                var  proyeccion_erro = proyec_arre[j];
                                                if(datos.Elem_ERRO[i]==proyeccion_erro.num_elem)
                                                {
                                                    proyeccion_ = proyec_arre[j];
                                                    //console.log(proyec_arre[j].cve_producto);
                                                }
                                            }
                                            tr_html += "<tr><th>"+proyeccion_.num_elem+"</th><th>"+proyeccion_.cve_almacen+"</th><th>"+proyeccion_.cve_agente+"</th><th>"+proyeccion_.cve_producto+"</th><th>"+proyeccion_.error_mesage+"</th></tr>";
                                                
                                         }
                                         var numero_elementos = "<h5>Numero de Errores : "+fail_proyeccion.length+"</h5>";
                                         var  html_table= "<table class='table table-responsive'><thead><th>N#</th><th>Clave Almacen</th><th>Clave Agente</th><th>Clave Producto</th><th>Error</th></thead><tbody>"+tr_html+"</tbody></table>";
                                        var  html_copleto = numero_elementos+html_table;
                                        $('#cont_error').html(html_copleto);
                                        
                                        } 
                                        
                                        
                                        
                                    
                        }});
                 } 
              });
            ///****** Fin  Peticion Ajax ********************************************
        ///***** Fin Else *************************************************************************
        }
        
   ///****Fin Funcion Modal_Send_Arch  
   }
   ///*********************************************
   ///*****Boton  para  Enviar  A BD     
    $('#sen_arch').click(function(){
                         $("#sen_arch").hide();
                  ////****Peticion para  Validar  elemtos_A_Enviar,Num_elem_En
                        $.ajax({
                                url: "carga_masiva_scrips/enviar_carga.php",
                                type: "POST",
                                data:{"ELEMENTOS" :elemtos_A_Enviar, "NELEM":Num_elem_En } ,
                               success: function (datos) {
                                   /// console.log(datos.ReInsert);
                                     var  tr_html = ""; 
                                         var proyec_arre  = JSON.parse(datos.PROYEC);
                                         /// console.log(datos.Elem_ERRO[i]);
                                         for(i=0; i<proyec_arre.length;i++)
                                         {
                                            var proyeccion_ = proyec_arre[i]; 
                                          var  tipo_qe= proyec_arre[i].error_obj;
                                           if(tipo_qe== 0)
                                           {  
                                                tr_html += "<tr id='insert_error' ><th>"+proyeccion_.num_elem+"</th><th>"+proyeccion_.cve_almacen+"</th><th>"+proyeccion_.cve_agente+"</th><th>"+proyeccion_.cve_producto+"</th><th>"+proyeccion_.error_mesage+"</th></tr>";
                                           }
                                           if(tipo_qe== 2)
                                           {  
                                                    tr_html += "<tr  id ='insert_correc' ><th>"+proyeccion_.num_elem+"</th><th>"+proyeccion_.cve_almacen+"</th><th>"+proyeccion_.cve_agente+"</th><th>"+proyeccion_.cve_producto+"</th><th>"+proyeccion_.error_mesage+"</th></tr>";
                                           }  
                                           if(tipo_qe== 3)
                                           {         
                                               tr_html += "<tr id ='insert_update'><th>"+proyeccion_.num_elem+"</th><th>"+proyeccion_.cve_almacen+"</th><th>"+proyeccion_.cve_agente+"</th><th>"+proyeccion_.cve_producto+"</th><th>"+proyeccion_.error_mesage+"</th></tr>";
                                           } 
                                            ///console.log(proyec_arre[i].tipo_pet);    
                                         }
                                        
                                         var  html_table= "<table class='table table-responsive tabla_contenedora'><thead><th>N#</th><th>Clave Almacen</th><th>Clave Agente</th><th>Clave Producto</th><th>Accion</th></thead><tbody>"+tr_html+"</tbody></table>";
                                        var  html_copleto = html_table;
                                        $('#cont_result').html(html_copleto);
                                   
                                   
                                  
                               }
                           });
                      //  console.log(elemtos_A_Enviar);
                       /// console.log(Num_elem_En);
      
    });
    ////***************************************
    });
///*** Fin  Contenedor  Javascript ***************************++
</script> 
<!--Contenedor Principal--> 
<div  class="col-lg-12  col-sm-12 "> 
    
    <div class="row" >
        <div class="col-sm-3" ><h3> Carga  Masiva </h3> </div>
        <div class="col-sm-6" ></div>
        <div class="col-sm-3" ><button id="seeDe0" type="button" class="btn btn-danger"><strong> Demanda Cero</strong></button></div>
      </div>    
    <!---Contene---> 
    <div  class="row">
        <div  class="col-lg-6 col-sm-6"> 
         <input  type="file" class="form-control" id="up_elem"> 
        </div> 
        <div class="col-lg-6  col-sm-6">
            <button id="ana_arch"  type="button" class="btn btn-success"> Analizar Archivo</button>
            <button hidden id="sen_arch"  type="button" class="btn btn-info"> Enviar Archivo</button>
            
        </div>
      
    </div> 
    <div id ="cont_result" class="row"> 
        
    </div> 
      <!---Modal  Para  el comentario ----->    
     <div  class="modal fade" id="Modal_est" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Informe Archivo</h4>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div class="col-lg-12 col-sm-12 col-md-12" > 
                        <!---->
                        <h4 id="title_mensa"> </h4>
                        <div class="row"  id="cont_error">
                            
                        </div> 
                        <!-------->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="close_coment" class="btn btn-default" data-dismiss="modal">Close</button>
             <!--  <button type="button" id="btn_update_comentarios"  class="btn btn-info" data-dismiss="modal"> <span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button> -->
            </div>
          </div>

        </div>
      </div>
    <!---Modal  Para  el Demanda 0 ----->    
     <div  class="modal fade" id="Mdemanda0" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Generar Demanda Cero</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                   <p><strong>Seleccione Mes para Generar Demanda Cero </strong></p>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-4"><label><strong>AÑO</strong></label> <input id="YEARD0"  type="number" min="2017" max="2050" class="form-control" ></div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-4"><label><strong>MES</strong></label><input id="MONTHD0" type="number" min="1" max="12" class="form-control" ></div>
                    <div class="col-sm-1"></div>
                </div>
                
                <div id="cont2div" class="col-sm-12">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-2"><button type="button" id="actD0" class="btn btn-success" >Aplicar</button>
                        <button hidden type="button" id="APLICARD0" class="btn btn-warning" >Ejecutar</button>
                    </div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-6"><label><strong>Resultado:</strong></label><textarea id="txtMens"  type="text" disabled  class="form-control"></textarea></div>
                    <div class="col-sm-1"></div>
                </div>
                
            </div>
            <div class="modal-footer">
              <button type="button" id="close_D0" class="btn btn-danger" data-dismiss="modal">Close</button>
             <!--  <button type="button" id="btn_update_comentarios"  class="btn btn-info" data-dismiss="modal"> <span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button> -->
            </div>
          </div>

        </div>
      </div>
    

<!--Fin  Contenedor Principal--> 
</div>
<?php  require_once('foot.php'); ?>     
    
    