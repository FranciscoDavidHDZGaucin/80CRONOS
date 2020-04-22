<?php
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_cre_agente.php 
 	Fecha  Creacion : 08/11/2016
	Descripcion  : 
                Pagina  para  agregar un nuevo   Adicional 
	Modificado  Fecha  : 
  *      ******  08/11/2016    Desarrollo  del  Layout   Html 
  *      ******  09/11/2016    Desarrollo  del  Layout  y  Modelado de Insercion Bd 
  *                            Se implementaron las consultas   para  
  *                            obtener  el  producto   y el  almacen segun  Usuario    
  *      ******  10/11/2016    Se Creo   la carpeta Contenendora  de todos los Scripts  para 
  *                            el  proyecto de  adicionales.  
  *                            Nombre  de la  Carperta : adicional_scrip
  *                            Se creo   peticion  AJax  para  Obtener  la  Proyeccion  del  Producto
  *      ******  11/11/2016    Se  definen las  consultas  para  la  obtencion de  los  productos
  *                             y ademas  se  definen  la consulta  para obtener los  almacenes  predefinidos 
  *                             del  usuario as
  *      ****** 12/11/2016     Inicio de  elaboracion  de consultas   para  obtener  la proyeccion  y las  ventas
  *                            pór   el usuario. Definimos tambien   el  archivo  adi_vent_pro.php
  *                            el  cual  contiene  las consultas  diseñadas. 
  *      ****** 16/11/2016     Se definio el  scrip  que obtiene  la  tabla  de  todos  los  adicionales 
  *                            ademas  que se   termino  de  redefinir  el  script  de insercion del  adicional 
  *                            Nombre de los  Archivos 
  *                                 *****Archivo  para  Agregar  el  adicional  en la  Bd  => adi_add_adicional.php
  *                                 *****Archivo  para Obtener  la  tabla de todos  los adicionales  => adi_show_table.php 
  *                                 *****Archivo  para  Eliminar   un adicional  por medio   de  su  Id => adi_delete_adicionales.php
  *  
  *      ****** 15/03/2017     Se Inicia el  Desarrollo  de Scrip  adi_set_Correo Para el  Envio de  Correos al  momento de 
  *                            generar el  Adicional.
  *      ****** 03/06/2017     Inicio  Modificacion  Checbox para  Denotar Si El Adicional    - Adicional  a proyeccion  copia Gabriela Treviño   -Adiciona P/Mes en curso  
                               Nota  de Dasarrollo :
  *                                                  Para  la captura de values en los  Dos  check box   utilizaos  unicamente 
  *                                                  un checkbox  dado a que podemos   interpretar  que  si   uno  esta en estado  true  el  otro 
  *                                                  inerentemente  esta  en estado   false por lo que solo utilizamos uno de los chekbox para  obtener ele  valor
  *                       
  *                            
 
  *                       
  * 
  *   */
////**Inicio De Session 
session_start();
///****Cabecera Cronos 
require_once('header.php');
///***Conexion Mysql  
require_once('Connections/conecta1.php');
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos

///require('formato_datos2.php');   //para evitar poner la funcion de conversion de tipos de datos utilizamos el 2 ya que es el compatible con mysqli
///****
///****Consuta para Obtener el  Producto   y su  numero
$selec_string = sprintf("SELECT * FROM productos where empresa = 0    order by desc_prod asc");
$res_qery_ = mysqli_query($conecta1, $selec_string) or die (mysqli_error($conecta1));
////*****Consulta  para obtener los almacenes 
 $select_string_almacen=sprintf("select * from almacenes_proyeccion where agente=%s order by nombre_alma",
                GetSQLValueString($_SESSION['usuario_agente'], "int")); 
 $res_qery_almacen=mysqli_query($conecta1,$select_string_almacen) or die (mysqli_error($conecta1));

 

 
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
<script type="text/javascript"> 
$(document).ready(function(){
      var  alma_num="" ;
      ///*****vts_RTES Bodega 
        var vts_RTES_BODEGA =0;
       ///****PROYTOTAL BODEGA 
        var  proyec_bodega_totls = 0;
    ////*****Select  para  los  Productos 
     $("#selc_pro").change(function(){
       ///***Obtenemos el  Value  del  Select   
       var  element =  document.getElementById("selc_pro");
       var  cve_pro = element.options[element.selectedIndex].value; 
       ///***Obtenemos el  Text del  Select
       var  desc_pro = $(this).find(":selected").text();
       
       $("#show_nom_pro").val(desc_pro);
       $("#show_codigo_pro").val(cve_pro);
       $("#selc_almacen").prop( "disabled", false );
       $("#btn_alma").prop("disabled",false);
       //****Limpiamos  la   Proyeccio n
        Clear_Elements();   
     });
     
      ///**Seleccionamos el  almacen 
     $("#selc_almacen").change(function(){
         Clear_Elements() 
        ///****Habilitamos la  fecha de  requirimientos
        $("#fech_req").prop( "disabled", false );
        ///****Obtenemos el  Elemento  Seleccionado 
         var  element =  document.getElementById("selc_almacen");
         alma_num = element.options[element.selectedIndex].value; 
         Get_Ve_Pro_Inven (alma_num);
   
     });
     ////**Evento  Click  para le  BOTOON ALAMACEN *************
     $(document).on("click",".btn_almacen_one",function(){
         Clear_Elements(); 
          alma_num = $(this).attr('value')
      ///***Ejecutamos  las Peticiones  
      Get_Ve_Pro_Inven (alma_num);
        
     });
     
     ///****Funcion para  obtener  la  V  y  Proyeccion ademas  del  Inventa
     function Get_Ve_Pro_Inven (alamacen)
     {
            ///****Validamos   Vacios en  fecha Requerimiento
      if($("#fech_req").val().length ==0 ||  $("#fech_req").val() == null) 
      {
          alert("Error es Necesario la  Fecha de Requerimiento")
      }
      else 
      {   
          ///****Generamos  Json Con Pararametros  Necesarios
        var  json_param  =  {
            "fech_req":$("#fech_req").val() ,
            "cdg_pro":$('#show_codigo_pro').val() ,
            "usuario_agente":$('#usuario_agente').val(), 
            "alma" :alamacen
         } 
        // alert(json_param.alma);
         /// alert(json_param.fech_req +" "+json_param.cdg_pro +" "+json_param.usuario_agente +" "); 
         $.ajax({
           type: 'POST',
           url:   'adicional_scrip/adi_get_proyeccion.php', ///'/ejemplo_ajax_001/inser.php',           //'inser.php' , ///'http://localhost/ejemplo_ajax_001/inser.php',
           data: json_param,
           success: function (datos) {
                 
                    if(datos.DMA=="ER" ){
                            alert("Error Lo  Sentimos No se Puede  Obtener la Proyeccion");
                    }else{
                        var resul =datos.DMA; 
                        if (resul.length==0 ||resul == null ||resul== "" ||resul ==0)
                        {
                            alert("Producto Sin  Proyeccion !!!");
                        }else{    
                            $('#proyec').val(datos.DMA);
                            
                        }   
                    }
                }
           }); 
         ///********************************************************Get  V
         $.ajax({
            type: 'POST',
            url:   'adicional_scrip/adi_vent_pro.php', ///'/ejemplo_ajax_001/inser.php',           //'inser.php' , ///'http://localhost/ejemplo_ajax_001/inser.php',
            data: json_param,
            success: function (datos) {
                   // alert( "Ventas  Totales  por  Bodega :"+datos.VEN_POR_BODEGA +" Devoluciones :" + datos.DEVO_POR_BODEGA+ " Total :" +datos.VTSBODEGA + "  Proyeccion TOtal" +datos.PROYBDTLS)
                    vts_RTES_BODEGA=datos.VTSBODEGA;
                    proyec_bodega_totls=datos.PROYBDTLS;
                    ///alert("Ventas :"+datos.VDI +"  Devoluciones: "+datos.CADEV+"Resta Elementos :"+datos.VT )
                    $("#venta").val(datos.VT);
                   /// alert("Inventario : "+datos.IV); 
                   if(datos.IV == null || datos.IV.length ==0)
                   {
                     $("#inven").val("0");  
                   }else 
                   { $("#inven").val(datos.IV); 
                   
                    }
              }
            }); 
         ///***********************************************************
      }
         
     }
     
     
       ///***Btn Guardar
     $('#btn_save').click(function(){
        ///***Validamos Que  la  fecha Req No sea  Null    y el  alamacen 
       if (ValidarFecha($('#fech_req').val())== false && InputDate_enable() == true  )
       {
       alert("Lo Sentimos la Fecha No Tiene El Formato Correcto dd/mm/año ");    
       }else{
           
       if(alma_num==0||$('#Pre_pVenta').val()==="0"||$('#Pre_pVenta').val()===null||$('#cant_req').val()===null||$('#cant_req').val()==="0" ||$('#show_codigo_pro').val()===null || $('#show_codigo_pro').val()==="" ||$('#fech_req').val().length===0 ||  $('#fech_req').val() === null ) 
       {
           alert("Imposible  Guardar Se Detectaron Parametros Vacios"); 
       }else{ 
            ///****Capturarmos los elementos 
             var param = {
                    "nomUsu":$('#nom_ususario').val() ,
                    "type_usu":1 ,
                    "cdg_pro":$('#show_codigo_pro').val() ,
                    "nomPro":$('#show_nom_pro').val() ,
                    "fec_sol":$('#fech_sol').val() ,
                    "fec_rq": convertoTODB($('#fech_req').val()),
                    "pre_solPV":$('#Pre_pVenta').val() ,
                   "can_rq":$('#cant_req').val(),
                    "almacen":alma_num,
                    "invt":$('#inven').val(),
                    "proycc":$('#proyec').val(),
                    "Num_USU":$('#usuario_agente').val(),
                    "VTS_RTES_BODEGA":vts_RTES_BODEGA ,
                    "PROYEC_BOD_TLS":proyec_bodega_totls,
                    "estPMcurso":$('#adiAproyec').val() 
                 }
                 
            ///****Realizamos la peticion 
            $.ajax({
                      type:'POST',
                      url: 'adicional_scrip/adi_add_adicional.php',
                      data: param,
                      success: function(datos)
                      { 


                          if(datos.RE == 0)
                          {
                              alert("Lo Sentimos Se Produjo Un Error" );

                          }else
                          { 

                              Get_table_Adi();
                             ///***Enviamos  correo  al  Gerente  y al Planeador 
                              Send_Mai(param)
                              
                          }
                          Clear_Elements();
                      }

                  });
       }
       ///****Fin validacion Fecha 
       }
       
       
    });
     ///****
     function Get_table_Adi()
     {
        
          $.ajax({
            type: 'POST',
            url:   'adicional_scrip/adi_show_table.php', ///'/ejemplo_ajax_001/inser.php',           //'inser.php' , ///'http://localhost/ejemplo_ajax_001/inser.php',
            data: {"nombre_usu":$('#nom_ususario').val(),"FOR_TB":1},
            success: function (datos) { 
                $("#cont_tb_adi").html(datos);
            }
         });
         
     }
   
    ///****Evento  for  del element 
     $(document).on("click",".btn_del_adi",function(){
         ///   alert($(this).attr('id'));
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
   ////*****Limpiamos los  Labels 
    function  Clear_Elements() 
    {
         ///****Limpiamos  la   Proyeccio n 
            $('#proyec').val("0");
            $('#inven').val("0");
            $('#venta').val("0");
            $('#cant_req').val("0");
            $('#Pre_pVenta').val("0"); 
            vts_RTES_BODEGA=0;
            proyec_bodega_totls=0;
    }
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
  ///***Obtenemos la  tabla   de  los  adicionales
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
                url: 'adicional_scrip/adi_sen_correos.php',
                data:Info, 
                success: function (datos) { 
                       /// console.log(datos.Est);
                     }
             });
       
   }
     //**** Check  Adicional  adiAproyec
	$("#adiAproyec").change(function(){

		if($("#adiAproyec").is(":checked")){	
			///***Adicional  A Proyectar Habilitado
			$(this).attr('value', 'true');
                        $("#adiMcurso").attr('value', 'false');
                        $("#adiMcurso").prop( "checked", false )
                          
		}else{ 
		       ///****Adicional A  Proyectar Desabilitado 
                       $(this).attr('value', 'true');
                        $("#adiMcurso").attr('value', 'true');
                        $("#adiMcurso").prop( "checked", true )
		}
	});

    //**** Check   adiMcurso
	$("#adiMcurso").change(function(){

		if($("#adiMcurso").is(":checked")){	
			///***Adicional  A Proyectar Habilitado
			$(this).attr('value', 'true');
                        $("#adiAproyec").attr('value', 'false');
                         $("#adiAproyec").prop( "checked", false )
                          
		}else{ 
		       ///****Adicional A  Proyectar Desabilitado 
                       $(this).attr('value', 'true');
                        $("#adiAproyec").attr('value', 'true');
                        $("#adiAproyec").prop( "checked", true )
		}
	});
    
    
});
</script> 
<div  class="container"> 
    <!--Inicio  contenedor  Principal-->
    <div  class="col-lg-12 col-md-12  col-sm-12">
        <!--Conatenedor  de   Title-->
        <div  class="row-fluid"> <h6 id="title">Adicionales</h6> 
            <ul class="nav nav-tabs">
               <li class="active"><a href="#">Agregar Adicional</a></li>
               <li><a href="adi_lista_adicionales_agentes.php">Adicionales Solicitados</a></li>
           </ul>
        </div>     
        <!--Inicio contenedor  Nombre Agente y Seleccion del Producto--> 
        <div  class="col-lg-12 col-md-12  col-sm-12">
            <!--Contenedor   Nombre  del  Agente--> 
            <div  id="cont_nom_agente"  class ="col-lg-6  col-md-6  col-sm-6"> 
                <div  class="form-group"> 
                    <label><h5>Nombre Usuario</h5></label>
                <?php  
                    echo '<h5>'.$_SESSION["usuario_nombre"].'</h5>';
                   echo  '<p hidden id ="cade" >'.$_SESSION["usuario_rol"];///$_SESSION["usuario_agente"].'</p>';
                    echo  '<input id="nom_ususario"   hidden   value="'.$_SESSION["usuario_nombre"].'">';
                    echo  '<input id="usuario_agente"   hidden   value="'.$_SESSION["usuario_agente"].'">';
               ?>
                </div> 
            </div> 
            <!--Contenedor  Select  Producto-->
            <div  id="cont_select_pro"  class="col-lg-6  col-md-6 col-sm-6"> 
                <div  class="form-group"> 
                    <label><h5>Seleccion Producto</h5></label>
                    <select   id="selc_pro" class="form-control">
                        <option>--Producto--</option> 
                        <?php 
                        ////cve_prod, desc_prod
                        while($row = mysqli_fetch_array($res_qery_)) 
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
                        <input required  type="date"  class="form-control" id="fech_req">
                    </div> 
                </div> 
                </div>
                   <!----Seleccion de  Almacen---->
                <div class="form-group">
                    <label>Almacen Donde Requiere  Adicional</label>
                    <br> 
                <?php  
                     ///****Obtenemos  el Numero De filas
                $num_row= mysqli_num_rows($res_qery_almacen);
                ///echo   '<p>'.$num_row.'</p>'; 
                if($num_row==1)
                {
                    $row_alma = mysqli_fetch_array($res_qery_almacen);
                    echo      '<button id="btn_alma" disabled class="btn_almacen_one  btn btn-info" type="button" value='.$row_alma['almacen'].'>'.$row_alma['nombre_alma'].'</button>';
                }else{
                        echo    '<select  disabled id="selc_almacen"  class="form-control" >';
                                 while($row_alma = mysqli_fetch_array($res_qery_almacen))
                                 {
                                     echo '<option   value='.$row_alma['almacen'].' >'.$row_alma['almacen'].'  '.$row_alma['nombre_alma'].'</option>';   
                                 }
                        echo   '</select>';
                }
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
                       
                       <!---Venta---->
                       <label>Venta</label>  
                       <input disabled id="venta"  type="number" class="form-control">
                          
                    </div>
                    <div  class="col-lg-4  col-md-4"> 
                       <!--Inventario--->
                       <div  class="form-group">
                           <label>Inventario</label>
                           <input disabled  id="inven"  type="number" class="form-control"> 
                       </div>
                    </div> 
                    <div  class="col-lg-4  col-md-4">
                          <!---Proyeccion ------>
                        <div  class="form-group" >
                           <label>Proyeccion</label>
                           <input disabled id="proyec" class="form-control"  type="number" >
                          </div>
                        
                    </div> 
                </div> 
         <br>
          <!--      <div  class="form-group">
                    <button id="btn_save"  type="button"  class="btn  btn-info"  >
                        <span class="glyphicon glyphicon-floppy-disk"></span>Guardar
                    </button>
                </div>-->
         <!--ChekBox Grupo-->
            <div class="row">
                <div class="col-sm-10">
                <div  class="row">
                            <div  class="col-sm-6">
                                <label><strong>Adicional a Proyeccion</strong></label>
                            </div>
                            <div  class="col-sm-6">
                                <input checked  value="true" type="checkbox"  id="adiAproyec">
                            </div>  
                </div>
                <div  class="row">
                            <div  class="col-sm-6 ">
                                <label><strong>Adicional P/Mes en curso</strong></label>
                            </div>
                            <div  class="col-sm-6">
                                    <input value="false" type="checkbox"  id="adiMcurso">
                            </div>
                    
                </div>
               </div>
                <div class="col-sm-2" >
                     <div  class="form-group">
                    <button id="btn_save"  type="button"  class="btn  btn-info"  >
                        <span class="glyphicon glyphicon-floppy-disk"></span>Guardar
                    </button>
                    </div>
               </div> 
              </div>
            <!--**********************Fin ChekBox  Opcions***************************************-->
           
            </div> 
        <!--Fin contenedor  Nombre Agente y Seleccion del Producto-->
       </div>
       <br><br> 
       <!----Contenedor  Tabla  Dinamica -----> 
       <div  id="cont_tb_adi" class="row"> 
       </div>
       <br><br> 
    <!--Fin  contenedor  Principal-->
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
</div> 
<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 