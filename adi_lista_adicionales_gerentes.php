<?php
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_lista_adicionales_gerentes.php 
 	Fecha  Creacion : 19/11/2016
	Descripcion  : 
                Pagina para  mostra  la Tabla  de  Adicionales  de  Gerentes y 
  *             Ademas de Adicionales  de los  agentes  para su  Autorizacion. 
	Modificado  Fecha  : 
  *                 28/11/2016    Se modifico los  paneles para  poder     apreciar   mejor  las 
  *                                 tablas de los  agentes  y los   gerentes   se agregaron  las  funciones   necesarias   para   los
*/
////**Inicio De Session 
session_start();
///****Cabecera Cronos 
require_once('header_gerentes.php');
?>
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script> 
<style> 
    /***Estilo  Titulo*/ 
    #title {
       text-align: center; 
    }

</style>
<script> 
$(document).ready(function(){
    ////****Variable_estado_auto;
    var EST=0,I_ADI=0;
    ////***Definimos  el estado   default  como   pendiente
    $('#auto_save').text("Pendiente");
       ///****
     function Get_table_Adi()
     {
        
          $.ajax({
            type: 'POST',
            url:   'adicional_scrip/adi_show_table_gerentes.php', ///'/ejemplo_ajax_001/inser.php',           //'inser.php' , ///'http://localhost/ejemplo_ajax_001/inser.php',
            data: {"nombre_usu":$('#nom_ususario').val()},
            success: function (datos) { 
                $("#cont_tb_adi").html(datos);
            }
         });
         
     }
     function Get_table_Agente()
     {
         
         $.ajax({
            type: 'POST',
            url:   'adicional_scrip/adi_show_agentes_whit_gerentes.php', ///'/ejemplo_ajax_001/inser.php',           //'inser.php' , ///'http://localhost/ejemplo_ajax_001/inser.php',
            data: {"zona":$('#zona_gerente').val()},
            success: function (datos) { 
                $("#cont_tb_adi_agentes").html(datos);
            }
         });
         
         
         
     }
     ///***Obtenemos la  tabla   de  los  adicionales
     Get_table_Adi();
     ////*Obtenemos  la  tabla  de  los  adicionales  Requeridos por los  agentes 
     Get_table_Agente();
     ///****Evento  for  del element 
     $(document).on("click",".btn_del_adi",function(){
       ///***Peticion  Ajax
             $.ajax({
                type:'POST',
                url: 'adicional_scrip/adi_delete_adicionales.php',
                data:{"ELEM":$(this).attr('id')} 
             });
             ///***Elimiamos elemento  de la  tabla
             var  parent =  $(this).parent().get(0);
             $(parent).remove();
         
     });
     //****Event para  Autorizar Adicionales 
     $(document).on("click",".btn_change_auto",function(){
        I_ADI =$(this).attr('I_ADI');
         $('#Modal_Auto').modal('show');
     });
     
    /////****
  
    $("#btn_update_adicionales").click(function(){
      /// alert(EST+" "+I_ADI);
     
       $.ajax({
                type:'POST',
                url: 'adicional_scrip/adi_update_autoriza_gerente.php',
                data:{"aut":EST,"cve_adicional":I_ADI}, 
                success: function (datos) { 
                  ///  console.log(datos.RE)
              
                  Send_Mai({"cve_adicional":I_ADI})
                    Get_table_Agente()
                     }
             });
       
    });
        //****Event para  Comentarioss
     $(document).on("click",".btn_comentarios",function(){
      var  I_ADI =$(this).attr('I_ADI');
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
         
    $('#btn_auto_YES').click(function(){
        
       EST= $(this).attr('value');
       $('#auto_save').text("Autorizar"); 
    });
    $('#btn_auto_NO').click(function(){
          EST= $(this).attr('value');
       $('#auto_save').text("Pendiente"); 
    });
    $('#btn_auto_RECHAZO').click(function(){
          EST= $(this).attr('value');
       $('#auto_save').text("Rechazado"); 
    });
  /////******************************************
  function  Send_Mai(Info)
   {
       ///** Enviamos  Datos para  Enviar el  Correo
           $.ajax({
                type:'POST',
                url: 'adicional_scrip/adi_sendGerentes_auto.php',
                data:Info, 
                success: function (datos) { 
                     ///alert("Hola :D")
                     }
             });
       
   }
    ///Send_Mai({"cve_adicional":56})
    
});
  

</script>
<div class="container"> 
<?php  
    echo  '<input id="nom_ususario"   hidden   value="'.$_SESSION["descripcion"].'">';
    echo  '<input id="zona_gerente"   hidden   value="'.$_SESSION["zona2"].'">'; 
?> 
   <!--Conatenedor  de   Title-->
        <div  class="row-fluid"> <h6 id="title">Adicionales</h6> 
            <h5 id="title"><?php echo $_SESSION["descripcion"];?></h5>     
            <ul class="nav nav-tabs">
                <li ><a href="adi_cre_gerentes.php">Agregar Adicional</a></li>
               <li class="active"><a href="#">Adicionales Solicitados</a></li>
           </ul>
        </div> 
    <br><br> 
    <!---Botones  para  Mostrar  la  tabla de los   agentes  y los Gerentes s---> 
    <div  class="form-inline"> 
        <button type="button" class="btn  btn-success"data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1" > Mis Adicionales  </button>
       <button type="button" class="btn  btn-success"data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2" > Agentes </button>
    </div> 
    <br><br> 
       <!---Inicio Panel Adicionales Solicitados por los gerentes----> 
            <div id="collapse1" class="collapse">
                <h4>Adicionales  Gerenetes</h4>
                     <!----Contenedor  Tabla  Dinamica -----> 
                    <div  id="cont_tb_adi" class="panel-body"> 
                    </div>
            </div> 
       <!---Inicio Panel Adicional Solitiados  por  los Agentes---->
            <div id="collapse2" class="collapse">
                        <h4>Adicionales  Agentes</h4>
                     <!----Contenedor  Tabla  Dinamica -----> 
                    <div  id="cont_tb_adi_agentes" class="panel-body"> 
                    </div> 
           </div> 
   <!----------------------------------------------------------------> 
    <!--Inciso Modal -->
      <div  class="modal fade" id="Modal_Auto" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Autorizacion  Adicionaless</h4>
            </div>
            <div class="modal-body">
      
                <div class="form-froup"> 
                    <div class="form-inline">
                        <button class="btn  btn-success"  id="btn_auto_YES" value="1"  >Autorizar </button>
                        <button class="btn btn-warning"  id="btn_auto_NO" value="0" >Pendiente</button>
                        <button class="btn btn-danger"  id="btn_auto_RECHAZO" value="2" >Rechazado</button>
                    </div>   
                    <br>
                    <div  class="form-inline">
                        <label>Estado  Adicional : </label> 
                        <label    id="auto_save"  style="font-size: 18px ; font-weight: bold; "  > </label>  
                    </div> 
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
             <button type="button" id="btn_update_adicionales"  class="btn btn-info" data-dismiss="modal"> <span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button>
            </div>
          </div>

        </div>
      </div>
    <!------------------------------------------------------------------->   
  <br><br>  
   <!---Modal  Para  el comentario ----->    
     <div  class="modal fade" id="Modal_comentarios" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Comentarios</h4>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div class="row" class="well"> 
                        <!-- <h5>Comentario  Anterior:</h5> --> 
                         <br>
                         <p id="conte_text"></p>
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
  <!-------------------> 
</div> 
<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 