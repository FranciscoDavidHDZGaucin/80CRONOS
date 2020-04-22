<?php
///*** adi_lista_adicionales.php 
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_lista_adicionales.php 
 	Fecha  Creacion : 16/11/2016
	Descripcion  : 
        Pagina  para  mostrar los  adicionales  solicitados por el  usuario
	Modificado  Fecha  : 
*/
////**Inicio De Session 
session_start();
///****Cabecera Cronos 
require_once('header.php');
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
      ////*******************************************************************
     $('.close_coment').click(function(){
           $('#conte_text').text("");   
          // console.log("Apicado");
    });  

       ///****
     function Get_table_Adi()
     {
        
          $.ajax({
            type: 'POST',
            url:   'adicional_scrip/adi_show_table.php', ///'/ejemplo_ajax_001/inser.php',           //'inser.php' , ///'http://localhost/ejemplo_ajax_001/inser.php',
            data: {"nombre_usu":$('#nom_ususario').val(),"FOR_TB":2},
            success: function (datos) { 
                $("#cont_tb_adi").html(datos);
            }
         });
         
     }
     ///***Obtenemos la  tabla   de  los  adicionales
     Get_table_Adi();
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
  
</script>
<div class="container"> 
<?php  echo  '<input id="nom_ususario"   hidden   value="'.$_SESSION["usuario_nombre"].'">'; ?> 
   <!--Conatenedor  de   Title-->
        <div  class="row-fluid"> <h6 id="title">Adicionales</h6> 
            <ul class="nav nav-tabs">
               <li ><a href="adi_cre_agente.php">Agregar Adicional</a></li>
               <li class="active"><a href="#">Adicionales Solicitados</a></li>
           </ul>
        </div> 
    <br><br> 
       <!----Contenedor  Tabla  Dinamica -----> 
       <div  id="cont_tb_adi" class="row"> 
       </div>
  <br><br>               
</div>
 <!---Modal  Para  el comentario ----->    
     <div  class="modal fade" id="Modal_comentarios" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
              <button type="button" class="close_coment close" data-dismiss="modal">&times;</button>
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
              <button type="button"  class="close_coment btn btn-default" data-dismiss="modal">Close</button>
             <!--  <button type="button" id="btn_update_comentarios"  class="btn btn-info" data-dismiss="modal"> <span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button> -->
            </div>
          </div>

        </div>
      </div>
  <!-------------------> 
<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 