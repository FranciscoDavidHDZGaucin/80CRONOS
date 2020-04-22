<?php
////****
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :adi_lista_planeador.php 
 	Fecha  Creacion : 24/11/2016 
	Descripcion  :
              Script  contenedor de   tabla de  control del    planeador 
	Modificado  Fecha  : 
  *         15/03/2017 Se  modifico el  Btn del  dialog para el dialog de comentarios 
  *                     para que  mandaran  a emty los comentarios
  *         16/03/2017 Se Inicia  la configuracion  De Correos de notificaciones   
  *             
  *         18/03/2017 Validar  las opciones  de  envio  de los correos
  *         17/04/2017 Se agrega  Tabla Historial  Adicional
*/
////**Inicio De Session 
session_start();
///****Cabecera Cronos +
require_once 'header_planeador.php';
?>
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script> 
<script>  
$(document).ready(function(){
    
     ////****Variable_estado_auto;
    var EST=0,I_ADI=0;
    ///**Variables  Intervalos
     var inter_pr ,  ctrl_t=0;
     ///****Fecha Compromiso 
     var  fecha_compro="";
     ////**Fecha  Promesa de  entreaga
     var  fecha_entregas="";
  //****Event para  Autorizar Estatus de  Entrega 
     $(document).on("click",".btn_auto_Entrega",function(){
        I_ADI =$(this).attr('I_ADI');
         $('#Modal_Entrega').modal('show');
     });  
  //****Event para  Comentarioss
     $(document).on("click",".btn_comentarios",function(){
        I_ADI =$(this).attr('I_ADI');
         $('#Modal_comentarios').modal('show');
         
         ///****Obtenemos   el comentario
           $.ajax({
                type:'POST',
                url: 'adicional_scrip/adi_get_coment_planeador.php',
                data:{"cve_adicional":I_ADI}, 
                success: function (datos) { 
                 ///  console.log(datos.CO)
                 ///  alert(datos.CO);
                            if(datos.CO != null)
                            {
                                $('#conte_text').text(datos.CO);
                            }    
                     }
             });
     }); 
     
    ///****BTN  FECHA_ COMPRO  
    $(document).on("click",".BTN_FECH_COM",function(){
        I_ADI =$(this).attr('I_ADI');
       if(fecha_compro ==""|| fecha_compro == null)
       {
          alert("Lo Sentimos Fecha en Estado  Nulo ");
       }else{
           ///****Actualizamos la  Fecha  Compromiso 
           $.ajax({
                type:'POST',
                url: 'adicional_scrip/adi_update_fech_compro.php',
                data:{"cve_adicional":I_ADI,"Fech":fecha_compro}, 
                success: function (datos) { 
                        ///  console.log(datos.CO)
                        ///  alert(datos.CO);
                        if(datos.Est==1)
                        {    ///***Enviamos  Correo Cuando se  Modifica el  estatus Fecha compromiso
                            Send_Mai({"cve_adicional":I_ADI})
                            fecha_compro="";
                            Get_table_Adi()  
                             ///****INtervalo para mostra  sierto tiempo el  mensage de  Se guado Correctamente
                                ctrl_t =0;
                                 inter_pr =  setInterval(function(){con_show_mens()},1000);
                                ///****Fin del  Codigo  para el  intervalo
                                $('#mensage_action').html("<h4  class='este_corect'>Fecha Compromiso  Modificada <span class='glyphicon glyphicon-ok'> <span class='glyphicon glyphicon-ok'> </h4>");
                                $('#mensage_action').show();
                        }else{
                            
                           alert("Lo  Sentimos la fecha no fue  Modificada"); 
                        }
                        
                     }
             })
       }
     });
      ///****BTN  FECHA_ COMPRO  
    $(document).on("change",".fech_compro_in",function(){
        fecha_compro=$(this).val();
  
     });
     ////***Fecha Real De  Entrega 
        $(document).on("click",".BTN_FECH_ENTRA",function(){
        I_ADI =$(this).attr('I_ADI');
       if(fecha_entregas ==""|| fecha_entregas == null)
       {
          alert("Lo Sentimos Fecha en Estado  Nulo ");
       }else{
          /// alert(fecha_entregas);
           ///****Actualizamos la  Fecha  Compromiso 
           $.ajax({
                type:'POST',
                url: 'adicional_scrip/adi_update_fech_entrega.php',
                data:{"cve_adicional":I_ADI,"FechEN":fecha_entregas}, 
                success: function (datos) { 
                        ///  console.log(datos.CO)
                        ///  alert(datos.CO);
                        if(datos.Est==1)
                        {
                            fecha_compro="";
                            ///***Enviamos  Correo Cuando se  Modifica el  estatus Fecha  Entrega
                             Send_Mai({"cve_adicional":I_ADI})
                            Get_table_Adi()  
                             ///****INtervalo para mostra  sierto tiempo el  mensage de  Se guado Correctamente
                                ctrl_t =0;
                                 inter_pr =  setInterval(function(){con_show_mens()},1000);
                                ///****Fin del  Codigo  para el  intervalo
                                $('#mensage_action').html("<h4  class='este_corect'>Fecha compromiso de Entrega Modificadas<span class='glyphicon glyphicon-ok'> <span class='glyphicon glyphicon-ok'> </h4>");
                                $('#mensage_action').show();
                        }else{
                            
                           alert("Lo  Sentimos la fecha no fue  Modificada"); 
                        }
                        
                     }
             })
       }
     });
      ///****BTN  FECHA_ COMPRO  
    $(document).on("change",".fec_real_entrega",function(){
        fecha_entregas=$(this).val();
  
     });
     
     
     
  /////***Modificar el Estatus  de Entrega
  $("#btn_update_status_Entrega").click(function(){
      ///alert(EST+" "+I_ADI);
     
       $.ajax({
                type:'POST',
                url: 'adicional_scrip/adi_update_est_entrega.php',
                data:{"est_entregas":EST,"cve_adicional":I_ADI}, 
                success: function (datos) { 
                  /// console.log(datos.RE)
                    Send_Mai({"cve_adicional":I_ADI})
                    Get_table_Adi()
                     }
             });
       
    });  
    
    /////***Modificar el Estatus  de Entrega
  $("#btn_update_comentarios").click(function(){
      /// alert(EST+" "+I_ADI);
     
       $.ajax({
                type:'POST',
                url: 'adicional_scrip/adi_coment_update.php',
                data:{"coments":$('#coment_adi').val(),"cve_adicional":I_ADI}, 
                success: function (datos) { 
                  ///  console.log(datos.RE)
                        $('#conte_text').text("");
                        $('#coment_adi').val("");
                    Get_table_Adi()
                     }
             });
       
    });
 ////****
   $('#btn_auto_YES').click(function(){
        
       EST= $(this).attr('value');
       $('#auto_save').text("Entregado"); 
    });
    $('#btn_auto_NO').click(function(){
          EST= $(this).attr('value');
       $('#auto_save').text("Pendiente"); 
    });
    $('#btn_auto_RECHAZO').click(function(){
          EST= $(this).attr('value');
       $('#auto_save').text("En Transito"); 
    }); 
    $('.close_coment').click(function(){
           $('#conte_text').text("");
                  $('#coment_adi').val("");
                  I_ADI=0;
                 
    });
    ////***Btn   Ge  Only  Id 
     $("#btn_get_id").click(function(){
        ///***Elimiamos elemento  de la  tabla
             var  parent =  $(".tabla_contenedora").parent().get(0);
             $(parent).remove();
         $.ajax({
            type: 'POST',
            url:   'adicional_scrip/adi_show_tb_adicionales_planeador.php', ///'/ejemplo_ajax_001/inser.php',           //'inser.php' , ///'http://localhost/ejemplo_ajax_001/inser.php',
            data: {"IDEN":$('#iden_get').val()}, 
           success: function (datos) { 
               $('#iden_get').val("")
                $("#content").html(datos);
            }
         });
         
         
     });
     $("#btn_adiciona_oper").click(function(){
         
          Get_table_Adi();
     });
     ///****Btn  Historial  
     $("#btn_historial").click(function(){
         
         Get_Historial();
         
     });
     
    ///***Intervalo  para los   mensages
    function  con_show_mens()
    {
        if(ctrl_t == 10)
        {
             clearInterval(inter_pr);
             $('#mensage_action').hide(); 

        } 
        ctrl_t ++ ; 
    }
 ///*****  Funtion para  obtener  la  tabla   
  function Get_table_Adi()
     {
        
          $.ajax({
            type: 'POST',
            url:   'adicional_scrip/adi_show_tb_adicionales_planeador.php', ///'/ejemplo_ajax_001/inser.php',           //'inser.php' , ///'http://localhost/ejemplo_ajax_001/inser.php',
            success: function (datos) { 
                $("#content").html(datos);
            }
         });
         
     }   
     ////******
     ///*****  Funtion para  obtener el Cierre de Mes  
  function Get_CierreMes()
     {
        
          $.ajax({
            type: 'POST',
            url:   'adicional_scrip/adi_update_cierre_mes.php', ///'/ejemplo_ajax_001/inser.php',           //'inser.php' , ///'http://localhost/ejemplo_ajax_001/inser.php',
            success: function (datos) { 
               /// alert("Año :"+datos.YEAR + "  Mes:"+datos.MES +" Dia:"+datos.DAY );
            }
         });
         
     }   
     ////********************************
 ////*********************************************************************
   function  Send_Mai(Info)
   {
       ///** Enviamos  Datos para  Enviar el  Correo
           $.ajax({
                type:'POST',
                url: 'adicional_scrip/adi_send_correos_planeador.php',
                data:Info, 
                success: function (datos) { 
                     ///  console.log(datos.Est);
                     }
             });
       
   }
  /////**********************************
  function   Get_Historial() 
  {
        $.ajax({
                type:'POST',
                url: 'adicional_scrip/adi_show_historial.php',
                success: function (datos) { 
                     ///  console.log(datos.Est);
                      $("#cont_tb_adi_hist").html(datos);
                     }
             });
 
  }
     
     ////******
     Get_table_Adi();
     ///*****
     Get_CierreMes();
///**********************************************************************************************************
/*
 * File:        chromatable.js
 * Version:     1.3.0
 * CVS:         $Id$
 * Description: Make a "sticky" header at the top of the table, so it stays put while the table scrolls
 * Author:      Zachary Siswick
 * Created:     Thursday 19 November 2009 8:53pm 
 * Language:    Javascript
 *
 */
(function($){
    
    $.chromatable = {
        // Default options
        defaults: {
						//specify a pixel dimension, auto, or 100%
            width: "900px", 
						height: "300px",
						scrolling: "yes" 
        }
				
		};
		
		$.fn.chromatable = function(options){
		 
		// Extend default options
		var options = $.extend({}, $.chromatable.defaults, options);

		return this.each(function(){
															
				// Add jQuery methods to the element
				var $this = $(this);
				var $uniqueID = $(this).attr("ID") + ("wrapper");
				
				
				//Add dimentsions from user or default parameters to the DOM elements
				$(this).css('width', options.width).addClass("_scrolling");
				
				$(this).wrap('<div class="scrolling_outer"><div id="'+$uniqueID+'" class="scrolling_inner"></div></div>');
								
				$(".scrolling_outer").css({'position':'relative'});
				$("#"+$uniqueID).css(
																	
					 {'border':'1px solid #CCCCCC',
						'overflow-x':'hidden',
						'overflow-y':'auto',
						'padding-right':'17px'						
						});
				
				$("#"+$uniqueID).css('height', options.height);
				$("#"+$uniqueID).css('width', options.width);
				
				// clone an exact copy of the scrolling table and add to DOM before the original table
				// replace old class with new to differentiate between the two
				$(this).before($(this).clone().attr("id", "").addClass("_thead").css(
																																															 
						{'width' : 'auto',
						 'display' : 'block', 
						 'position':'absolute', 
						 'border':'none', 
						 'border-bottom':'1px solid #CCC',
						 'top':'1px'
							}));
	
				
				// remove all children within the cloned table after the thead element
				$('._thead').children('tbody').remove();
				
				
				$(this).each(function( $this ){
															
					// if the width is auto, we need to remove padding-right on scrolling container	
					
					if (options.width == "100%" || options.width == "auto") {
						
						$("#"+$uniqueID).css({'padding-right':'0px'});
					}
					
				
					if (options.scrolling == "no") {
												
						$("#"+$uniqueID).before('<a href="#" class="expander" style="width:100%;">Expand table</a>');
						
						$("#"+$uniqueID).css({'padding-right':'0px'});
						
						$(".expander").each(
	
							
							function(int){
								
								$(this).attr("ID", int);
								
								$( this ).bind ("click",function(){
																								 
										$("#"+$uniqueID).css({'height':'auto'});
										
										$("#"+$uniqueID+" ._thead").remove();
										
										$(this).remove();
						
									});
								});


						//this is dependant on the jQuery resizable UI plugin
						$("#"+$uniqueID).resizable({ handles: 's' }).css("overflow-y", "hidden");
	
					}
				
				});
				
				
				// Get a relative reference to the "sticky header"
				$curr = $this.prev();
				
				// Copy the cell widths across from the original table
				$("thead:eq(0)>tr th",this).each( function (i) {
																							 
					$("thead:eq(0)>tr th:eq("+i+")", $curr).width( $(this).width());
					
				});

				
				//check to see if the width is set to auto, if not, we don't need to call the resizer function
				if (options.width == "100%" || "auto"){
					
											
						// call the resizer function whenever the table width has been adjusted
						$(window).resize(function(){
																			
									resizer($this);										
						});
					}
				});
				
    };
		
		// private function to temporarily hide the header when the browser is resized
		
		function resizer($this) {
				
				// Need a relative reference to the "sticky header"
				$curr = $this.prev();
				
				$("thead:eq(0)>tr th", $this).each( function (i) {
																														 
					$("thead:eq(0)>tr th:eq("+i+")", $curr).width( $(this).width());
					
				});

  	};
		
})(jQuery);
 $("#tbpla").chromatable({
	width: "100%", // specify 100%, auto, or a fixed pixel amount
				height: "400px",
				scrolling: "yes" // must have the jquery-1.3.2.min.js script installed to use
});
  
///******************************************************************************************************     
});
</script>
<!--
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery.chromatable.js"></script>-->
<style>
thead, tbody { display: block; }

tbody {
    height: 500px;       /* Just for the demo          */
    overflow-y: auto;    /* Trigger vertical scroll    */
   
}
th,td{
        max-width: 10.5vw;
    min-width: 10.5vw;
}
    
</style>
<div class="container"> 
    
    
    <div class="row">
            <!---Botones  para  Mostrar  la  tabla de los   agentes  y los Gerentes s---> 
                <div  class="form-inline"> 
                    <button id="btn_adiciona_oper"    type="button" class="btn  btn-success"data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1 collapse2" >Adicionales  Solicitados</button>
                   <button id="btn_historial"  type="button" class="btn  btn-success"data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2 collapse1" > Historial Adicionales</button>
                </div> 
   </div>
  <div  class="col-lg-12  col-sm-12  col-md-12">
     
      <br>
      
   
        <!---Inicio Panel Adicionales  para  Modificaciones----> 
            <div id="collapse1" class="collapse">
                      <div  class="row">
                        <div  class="col-lg-6  col-sm-6  col-md-6" > <h1>Demanda  Adicional</h1> </div>
                        <div  class="col-lg-6  col-sm-6  col-md-6" > <div hidden id="mensage_action"></div></div>


                    </div> 
                    <div  class="col-lg-12 col-sm-12 col-md-12"> 
                        <div   class="col-lg-3 col-sm-3 col-md-3">
                            <a class ="btn btn-success" href="adicional_scrip/adi_reporte_excel.php" target="_blank">
                                   <span  class="glyphicon glyphicon-floppy-disk"></span>
                                           Generar  Reporte   
                                  </a>
                        </div>
                        <div  class="col-lg-9 col-sm-9 col-md-9">

                            <div  class="form-inline">      
                            <div class="input-group">
                                    <span class="input-group-btn">
                                    <button id="btn_get_id" class="btn btn-default" type="button"  >  <span class="glyphicon glyphicon-repeat">Filtrar ID</span></button>
                                    </span>
                                <input class="form-control"   type="number" id="iden_get" >
                              </div><!-- /input-group -->


                            </div>   

                        </div> 
                    </div> 
                     <!----Contenedor  Tabla  Dinamica -----> 
                    <div  id="content" class="panel-body"> 
                    </div>
            </div> 
       <!---Inicio Panel Adicional Historial---->
            <div id="collapse2" class="collapse">
                        <h4>Historial Adicionales</h4>
                     <!----Contenedor  Tabla  Dinamica -----> 
                    <div  id="cont_tb_adi_hist" class="panel-body"> 
                    </div> 
           </div> 
   <!----------------------------------------------------------------> 
     
  </div> 
  <!---Modal  Para  el  Estatus   De Entraga----->    
     <div  class="modal fade" id="Modal_Entrega" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Estatus de Entrega</h4>
            </div>
            <div class="modal-body">
      
                <div class="form-group"> 
                    <div class="form-inline">
                        <button class="btn  btn-success"  id="btn_auto_YES" value="1"  >Entregado </button>
                        <button class="btn btn-warning"  id="btn_auto_NO" value="0" >Pendiente</button>
                        <button class="btn btn-danger"  id="btn_auto_RECHAZO" value="2" >En Transito</button>
                    </div>   
                    <br>
                    <div  class="form-inline">
                        <label>Estado  De Entrega : </label> 
                        <label    id="auto_save"  style="font-size: 18px ; font-weight: bold; "  > </label>  
                    </div> 
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
             <button type="button" id="btn_update_status_Entrega"  class="btn btn-info" data-dismiss="modal"> <span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button>
            </div>
          </div>

        </div>
      </div>
  <!------------------->    
    <!---Modal  Para  el comentario ----->    
     <div  class="modal fade" id="Modal_comentarios" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
              <button type="button" class="close_coment close " data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Comentarios</h4>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                     
                   
                    <div class="row"> 
                        <textarea class="form-control"  type="text"  id="coment_adi"  ></textarea>
                    </div> 
                     <div class="row" class="well"> 
                         <h5>Comentario  Anterior:</h5>
                         <br>
                         <p id="conte_text"></p>
                        <!-------->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="close_coment" class="close_coment btn btn-default" data-dismiss="modal">Close</button>
             <button type="button" id="btn_update_comentarios"  class="btn btn-info" data-dismiss="modal"> <span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button>
            </div>
          </div>

        </div>
      </div>
  <!-------------------> 
</div> 
<?php require_once 'foot.php';?>