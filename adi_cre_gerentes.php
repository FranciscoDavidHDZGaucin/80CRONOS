<?php

////
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :adi_cre_gerentes.php 
 	Fecha  Creacion :17/11/2016 
	Descripcion  : 
               Script  para  Agregar   adicionales   solicitados  por los  gerentes
  *            
	Modificado  Fecha  : 
  *             17/03/2017 Se  incia el  agregado  de  Notificacion  mediante  correo al  momento de  crear un adicional  
  *                        el nombre  del  escrip encargado  es  adi_send_gerentes_add.php   
  *                         
*/
////**Inicio De Session 
session_start();
///****Cabecera Cronos 
require_once('header_gerentes.php');
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***Conexion Mysql  
require_once('Connections/conecta1.php');
///****
/////****Objeto de  Conexion*******
$mysqli_PRO =   new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 

///****Consuta para Obtener el  Producto   y su  numero
$selec_string = sprintf("SELECT * FROM productos where empresa = 0    order by desc_prod asc");
$res_qery_ = $mysqli_PRO->query($selec_string);

////*****Consulta  para obtener los almacenes 
 $select_string_almacen=sprintf("Select DISTINCT  almacen as  cve_almacen ,nombre_alma  from pedidos.almacenes_proyeccion	 where  agente in (SELECT  cve_age FROM pedidos.relacion_gerentes  where  cve_gte = %s)",
                GetSQLValueString( $_SESSION["zona2"], "int"));
$res_qery_almacen=$mysqli_PRO->query($select_string_almacen);
 ?>
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script> 
<!--Escrip  para  validar  Fecha de Req-->
<script src ="adicional_scrip/fechas_fail_other.js"></script> 
<style> 
    /***Estilo  Titulo*/ 
    #title {
       text-align: center; 
    }
</style>
<script type ="text/javascript">
 $(document).ready(function(){
         var  alma_num="" ;
     
      $("#selc_pro").change(function(){
       ///***Obtenemos el  Value  del  Select   
       var  element =  document.getElementById("selc_pro");
       var  cve_pro = element.options[element.selectedIndex].value; 
       ///***Obtenemos el  Text del  Select
       var  desc_pro = $(this).find(":selected").text();
       
       $("#show_nom_pro").val(desc_pro);
       $("#show_codigo_pro").val(cve_pro);
       $("#selc_almacen").prop( "disabled", true ); 
        ///****Habilitamos la  fecha de  requirimientos
        $("#fech_req").prop( "disabled", false );
      /// $("#btn_alma").prop("disabled",false);
       //****Limpiamos  la   Proyeccio n
        Clear_Elements();   
     });
     ///**Seleccionamos el  FECHA  Requerimiento   a 
     $("#fech_req").change(function(){
         $("#selc_almacen").prop( "disabled", false ); 
         
         
     });
     
     ///**Seleccionamos el  almacen 
     $("#selc_almacen").change(function(){
        ///****Obtenemos el  Elemento  Seleccionado 
         var  element =  document.getElementById("selc_almacen");
         alma_num = element.options[element.selectedIndex].value; 
         var par = {"cdg_pro":$('#show_codigo_pro').val() ,"alma":alma_num,"fech": $("#fech_req").val()  };
         ////alert(par.fech);
        ////***Realizamos peticion  Ajax 
        $.ajax({
             type:'POST',
             url: 'adicional_scrip/adi_get_inv_proalma.php',
             data:par,
             success:function(datos){
                ///alert("Inventario : "+datos.INV+ "  Proyeccion : "+ datos.PRO)
                     if(datos.INV== null || datos.INV =="")
                     {
                        $('#inven' ).val("0");
                     }else 
                     {
                        $('#inven' ).val(datos.INV);   
                     }   
                     if(datos.PRO ==null ||datos.PRO=="" )
                     {
                         $('#proyec').val("0");
                     }else{
                          $('#proyec').val(datos.PRO);
                     } 
                    
             }
        });
   
     });
   
     ///**** 
   ////*****Limpiamos los  Labels 
    function  Clear_Elements() 
    {
         ///****Limpiamos  la   Proyeccio n 
            $('#proyec').val("0");
            $('#inven').val("0");
           
            $('#cant_req').val("0");
            $('#Pre_pVenta').val("0"); 
    }
        ///***Btn Guardar
     $('#btn_save').click(function(){
        ///***Validamos Que  la  fecha Req No sea  Null    y el  alamacen 
          ///***Validamos Que  la  fecha Req No sea  Null    y el  alamacen 
       if (ValidarFecha($('#fech_req').val())== false && InputDate_enable() == true  )
       {
       alert("Lo Sentimos la Fecha No Tiene El Formato Correcto dd/mm/año ");    
       }else{
           
       if(alma_num==0 || $('#Pre_pVenta').val()==="0"||$('#Pre_pVenta').val()===null||$('#cant_req').val()===null||$('#cant_req').val()==="0" ||$('#show_codigo_pro').val()===null || $('#show_codigo_pro').val()==="" ||$('#fech_req').val().length===0 ||  $('#fech_req').val() === null ) 
       {
           alert("Imposible  Guardar Se Detectaron Parametros Vacios o No se Selecciono el Almacen"); 
       }else{ 
            ///****Capturarmos los elementos 
             var param = {
                    "nomUsu":$('#nom_ususario').val() ,
                    "type_usu":2 ,
                    "cdg_pro":$('#show_codigo_pro').val() ,
                    "nomPro":$('#show_nom_pro').val() ,
                    "fec_sol":$('#fech_sol').val() ,
                    "fec_rq": $('#fech_req').val(),
                    "pre_solPV":$('#Pre_pVenta').val() ,
                   "can_rq":$('#cant_req').val(),
                    "almacen":alma_num,
                    "invt":$('#inven').val(),
                    "proycc":$('#proyec').val(),
                    "Num_USU":$('#usuario_agente').val()
                 }
                 
            ///****Realizamos la peticion 
            $.ajax({
                      type:'POST',
                      url: 'adicional_scrip/adi_add_adicional_gerentes.php',
                      data: param,
                      success: function(datos)
                      { 


                          if(datos.RE == 0)
                          {
                              alert("Lo Sentimos Se Produjo Un Error" );
                             // console.log(datos.cadena ) ;

                          }else
                          { 

                            Get_table_Adi(); ///alert("Exito");
                            ////***Correo  Para  Cuando se Genera Un nuevo pedido
                            Send_Mai(param)
                          }
                          Clear_Elements();
                      }

                  });
       }  
   }
    });
     /////***********************************************
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
      ///***************************************************************
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
     Get_table_Adi()
     ////*******************************************************************
     $('.close_coment').click(function(){
           $('#conte_text').text("");   
          // console.log("Apicado");
    });
     ////*********************************************************************
   function  Send_Mai(Info)
   {
       ///** Enviamos  Datos para  Enviar el  Correo
           $.ajax({
                type:'POST',
                url: 'adicional_scrip/adi_send_gerentes_add.php',
                data:Info, 
                success: function (datos) { 
                       /// console.log(datos.Est);
                     }
             });
   }
    
 });
</script> 
<div  class="container">
        <!--Inicio  contenedor  Principal-->
    <div  class="col-lg-12 col-md-12  col-sm-12">
        <!--Conatenedor  de   Title-->
        <div  class="row-fluid"> <h6 id="title">Adicionales</h6> 
            <ul class="nav nav-tabs">
               <li class="active"><a href="#">Agregar Adicional</a></li>
               <li><a href="adi_lista_adicionales_gerentes.php">Adicionales Solicitados</a></li>
           </ul>
        </div>     
        <!--Inicio contenedor  Nombre Agente y Seleccion del Producto--> 
        <div  class="col-lg-12 col-md-12  col-sm-12">
            <!--Contenedor   Nombre  del  Agente--> 
            <div  id="cont_nom_agente"  class ="col-lg-6  col-md-6  col-sm-6"> 
                <div  class="form-group"> 
                    <label><h5>Nombre Usuario</h5></label>
                <?php  
                   echo '<h5>'.$_SESSION["descripcion"].'</h5>';
                   echo  '<p hidden id ="cade" >'. $_SESSION["zona2"]. '</p>'; ///$_SESSION["usuario_agente"].'</p>';
                  echo  '<input id="nom_ususario"   hidden   value="'.$_SESSION["descripcion"].'">';
                   echo  '<input id="usuario_agente"   hidden   value="'.$_SESSION['id'].'">';
               ?>
                </div> 
            </div> 
            <!--Contenedor  Select  Producto--->
            <div  id="cont_select_pro"  class="col-lg-6  col-md-6 col-sm-6"> 
                <div  class="form-group"> 
                    <label><h5>Seleccion Producto</h5></label>
                    <select   id="selc_pro" class="form-control">
                        <option>--Producto--</option> 
                        <?php 
                        ////cve_prod, desc_prod
                        while($row = $res_qery_->fetch_array(MYSQLI_ASSOC)) 
                          {    
                              echo  '<option  value='.$row['cve_prod'].'>'.utf8_encode($row['desc_prod']).'</option> ';
                          }
                          ?> 
                    </select>
                </div>
            </div> 
        <!--Fin contenedor  Nombre Agente y Seleccion del Producto-->
       </div> 
       <!-----> 
        <div  class="col-lg-12 col-md-12  col-sm-12">
            <!--Contenedor   Nombre  Producto --> 
            <div  id="cont_nom_agente"  class ="col-lg-6  col-md-6  col-sm-6"> 
                 <!--------Contenedor de Fechas--------------------------------------------->
                <div  class="row">
                <div  class="col-lg-6 col-md-6 col-sm-6"> 
                    <!----Fecha de Solicitud----->
                    <div  class="form-group" >
                        <label>Fecha Solicitud</label>
                        <input disabled  type="text" class="form-control" <?php
                        $time = time();
                        echo "value =". date("Y-m-d", $time);  ?>    id="fech_sol"> 
                    </div>
                </div>
                <div  class="col-lg-6 col-md-6 col-sm-6"> 
                    <!----Fecha de  Requerimiento--->
                    <div  class="form-group">
                        <label>Fecha  Requerimiento</label>
                        <input disabled required  type="date"  class="form-control" id="fech_req">
                    </div> 
                </div> 
                </div>
                   <!----Seleccion de  Almacen---->
                <div class="form-group">
                    <label>Almacen Donde Requiere  Adicional</label>
                    <br> 
                <?php  
                     ///****Obtenemos  el Numero De filas
              /*  $num_row= $res_qery_almacen->num_rows;
               echo   '<p>'.$num_row.'</p>'; 
                if($num_row==1)
                {
                    $row_alma =  $res_qery_almacen->fetch_array(MYSQLI_ASSOC);
                    echo      '<button id="btn_alma" disabled class="btn_almacen_one  btn btn-info" type="button" value='.$row_alma['cve_almacen'].'>'.$row_alma['nombre_alma'].'</button>';
                }else{*/
                        echo    '<select  disabled id="selc_almacen"  class="form-control" >';
                                 while($row_alma = $res_qery_almacen->fetch_array(MYSQLI_ASSOC))
                                 {
                                     echo '<option   value='.$row_alma['cve_almacen'].' >'.$row_alma['cve_almacen'].'  '.$row_alma['nombre_alma'].'</option>';   
                                 }
                        echo   '</select>';
                 
                ?>     
                   
                    
                </div>
                 
         <!---------------------------------------------->
         <div  class="row"> 
             <div  class="col-lg-6  col-md-6"> 
             <!---Precio Solicitado P/Venta----> 
                <div class="form-group">
                    <label>Precio Solicitado P/Venta</label>
                    <input  type="number"  id="Pre_pVenta" class="form-control"> 
                </div> 
             </div> 
             <div  class="col-lg-6  col-md-6"> 
              <!---Cantidada  Requerida--->
                <div  class="form-group">
                    <label>Cantidad  Requerida</label>
                    <input   type="number"   id="cant_req"  class ="form-control"> 
                </div> 
             </div> 
         </div>  
         <!-----------------------------------> 
            </div> 
            <!--Codigo  del Producto--->
            <div  id="cont_select_pro"  class="col-lg-6  col-md-6 col-sm-6">
             <div  class="row">
                <div   class="col-lg-9  col-md-9 col-sm-9">
                    <!------Nombre  del  Producto------>
                    <div  class="form-group">
                        <label>Nombre  Producto</label>
                        <input disabled class ="form-control"  id="show_nom_pro"  > 
                    </div>
                </div>
                <div  class="col-lg-3  col-md-3 col-sm-3">
                    <!---Codigo del Producto-->
                    <div class ="form-group"> 
                     <label>Codigo</label>
                     <input disabled class ="form-control"  id ="show_codigo_pro"> 
                    </div>
                </div>
                <!----------------------------------------------------->
                
             </div> 
         <!----------------------------------------------------->
                <div  class="row">
                  
                    
                    <div  class="col-lg-4  col-md-4"> 
                       <!--Inventario--->
                       <div  class="form-group">
                           <label>Inventario</label>
                           <input disabled  id="inven"  type="number" class="form-control"> 
                       </div>
                    </div> 
                    <div  class="col-lg-8  col-md-8">
                          <!---Proyeccion ------>
                        <div  class="form-group" >
                           <label>Proyeccion Almacen por Mes</label>
                           <input disabled id="proyec" class="form-control"  type="number" >
                          </div>
                        
                    </div> 
                </div> 
         <br>
                <div  class="form-group">
                    <button id="btn_save"  type="button"  class="btn  btn-info"  >
                        <span class="glyphicon glyphicon-floppy-disk"></span>Guardar
                    </button>
                </div>
                
             </div> 
        <!--Fin contenedor  Nombre Agente y Seleccion del Producto-->
       </div>
       <br><br> 
       <!----Contenedor  Tabla  Dinamica -----> 
       <div  id="cont_tb_adi" class="row"> 
       </div>
       <br><br> 
    <!--Fin  contenedor  Principal-->
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
              <button type="button" id="close_coment" class="close_coment btn btn-default" data-dismiss="modal">Close</button>
             <!--  <button type="button" id="btn_update_comentarios"  class="btn btn-info" data-dismiss="modal"> <span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button> -->
            </div>
          </div>

        </div>
      </div>
  <!-------------------> 
</div> 
<?php  require_once('foot.php');?>  