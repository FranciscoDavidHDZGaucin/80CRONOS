<?php
////*****pub_eviestagente.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_eviestagente.php
 	Fecha  Creacion : 05/05/2017
	Descripcion  : 
 *              Escrip para Agregar las Evidencias  de la publicidad 
 * 
 *        Modificaciones : 
 *            
  */
////**Inicio De Session 
	session_start();
///****Cabecera Cronos
require_once('header.php');
require_once('Connections/conecta1.php');
///*****        
require_once('formato_datos.php');
///**+Establecemos   Update  a  Estado  1  
$_SESSION['UbdatePub'] =1

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

.dropzone.dz-clickable {
    cursor: pointer;
}
.dropzone {
    border: 2px dashed #0087F7;
    border-radius: 5px;
    background: white;
}
.dropzone {
    min-height: 150px;
    border: 2px solid rgba(0, 0, 0, 0.3);
    background: white;
    padding: 54px 54px;
}
.dropzone, .dropzone * {
    box-sizing: border-box;
}


</style>
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="pub_scrip_publicidad/pub_func_publicidad.js"></script>
<script type="text/javascript">

    function  TrueFormat(Areformat)
    {   
        let respu = true ; 
        for(var i  in Areformat  ){
               if( Areformat[i].ARCH.localeCompare("pdf") == 0|| 
                       Areformat[i].ARCH.localeCompare("jpg") == 0||
                       Areformat[i].ARCH.localeCompare("jpeg") == 0 ||
                       Areformat[i].ARCH.localeCompare("jpe") == 0 ||
                       Areformat[i].ARCH.localeCompare("jfif") == 0 ||
                         Areformat[i].ARCH.localeCompare("png") == 0 ||
                        Areformat[i].ARCH.localeCompare("PNG") == 0  )
                {
                  respu = true;    
                }else{
                    respu = false;
                    
                }
                
           
        }
        
        return  respu ;
        
    }


</script> 
<script type="text/javascript">
 $(document).ready(function(){
       var  Nf ;
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
        
        
    });
   /////*****Btn   Para  Leer  los  Archivos ************************** 
   $(".readfile").click(function(){
       ///******Obtenemos el Archivo
            var   ideti = "arch"+$(this).attr('value');
            var archi =  document.getElementById(ideti);
            var   fl = $(this).attr('value');
            ///  var   file = archi.files;
            var   datos =  new  FormData();
            var   formatFiles =  new Array();
            for(var i =0 ;i< archi.files.length ;i++  )
            {
                var file  = archi.files[i];
                datos.append("ARCH"+i,file );
                var  tipoArchivo  = {"id":i ,"ARCH": file.type.split('/').pop() }; 
                formatFiles.push(tipoArchivo);
            }
           if( TrueFormat(formatFiles)==true && archi.files.length >0 ){
                $.ajax({
                    type:'POST',
                    url: 'pub_scrip_publicidad/pub_addevitbevidencia.php',
                    data:{"fl":fl, "DET":JSON.stringify(formatFiles)}, 
                    success: function (inserdb) { 
                         if(inserdb.Res002 ==  0)
                         {
                              alert("Lo Sentimos Ocurrio  Un Error Intente mas tarde !!!");
                         }else{
                            datos.append("fl",fl);
                             Send_File(datos)
                         }   

                 }});
           }else {
               alert("Error Existen Archivos que No Cumplen con el  tipo de Archivo. El Archivo Debe ser  ,.Pdf , .jpg , .png ")
           }
       
   });
   ///****************************************************
     ////****Funcion para enviar  elementos
   function  Send_File(datos,fl)
   {
            ///*********************************************************************+
             $.ajax({
                       url: "pub_scrip_publicidad/pub_upevidenciapaquete.php",
                       type: "POST",
                       data:datos ,
                      contentType:false, //Debe estar en false para que pase el objeto sin procesar
                      processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
                      cache:false, //Para que el formulario no guarde cache
                     success: function (datos) {
                        /// console.log(datos.msg +"*******"+ datos.ruta)
                         if(datos.estado == 0)
                         {
                          
                             alert("El Archivo Debe ser  ,.Pdf , .jpg , .png ");
                             
                         }else{
                           /* $("#mensERROR").text("Exito !!! ");
                             $("#msn001").text("Se Guardo Correctamente la Imagen");
                             $("#ModalMNs").modal("show");
                          */
                         alert("Se Guardaron los  Archivos Correctamente");
                            /*Forzamos la recarga*/
                            location.reload(true); 
                         }
            
                     } 
             });
            ///**************************************************
  }
  ////****Function a 
  
   ////********************************
   });   
 </script>
 <div  class="container">
     <div class="row"><h2>Carga de Evidencias</h2></div>
     
     <div class="col-sm-12">
        <?php 
        ///***Cadena para Hacer
        $string_get_info =sprintf("SELECT pub_folio,pub_fech_cap,cliente,auto_JINC  FROM pedidos.pub_encabeza_publicidad  where  est_evidecia= 0 and   auto_JINC = 1  and cve_agente =%s",
             GetSQLValueString($_SESSION["usuario_agente"], "int"));
        ///***Generamos   Qery 
        $qery_info = mysqli_query($conecta1, $string_get_info);
        
        while($row = mysqli_fetch_array($qery_info)){
        ?>
         <div class="brdD row">
             <div class="row infoS">
             <div  class="col-sm-5"><h3>Folio:<?php echo $row['pub_folio'];?></h3></div> <div class="col-sm-4"></div><div  class="col-sm-3"><h4 class="posFech">Fecha Solicitud <?php echo $row['pub_fech_cap'];?></h4></div>
             </div>
             <div class="row infoS">
                 <div class="col-sm-5"><h5>Cliente:</h5><h6><?php echo $row['cliente'];?></h6></div>
                 <div class="col-sm-4">
                     <input type="file" multiple  id="arch<?php echo $row['pub_folio'];?>"   >
                 </div> 
                 <form  action ="pub_see_updpublicidad.php"  method="POST"> 
                     <div class="col-sm-1"><input hidden type="int" name="nf" value="<?php echo $row['pub_folio'];?>" ><button type="submit" class="btn btn-info buscar"><span class="glyphicon glyphicon-zoom-in"></span></button></div>
                     
                 </form>
               <div class="col-sm-1" >
                 
                    <button  value="<?php echo $row['pub_folio'];  ?>"    type="button" class="readfile btn btn-info buscar" data-toggle="collapse" data-target="#demo">Subir Evidencia</button>
                     
                </div>
                 
             </div>
             
             
             
         </div> 
         <br>
         <?php }?>      
         
     </div>
     <!---Modal  Mensages----->    
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
  <!-------------------> 
 </div>
<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 





