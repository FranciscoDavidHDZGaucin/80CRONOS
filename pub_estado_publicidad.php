<?php
////*pub_estado_publicidad.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_estado_publicidad.php 
 	Fecha  Creacion : 05/05/2017
	Descripcion  : 
 *              Escrip para para mostrar el Estatus de  Todas las  Solicitudes 
 * 
 *        Modificaciones : 
 *              08/05/2017    Se  Agrega  la  variable    UbdatePub encargada  de controlar cuando se  realiza  una modificacion a
 *                            una solicitud del  pedido. 
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
</style>
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>

<script type="text/javascript" src="pub_scrip_publicidad/pub_func_publicidad.js"></script>
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
        
        
    })
       
       
   });
   
 </script>
 <div  class="container">
     <div class="row"><h2>Estado  Solicitudes</h2></div>
     <div class="col-sm-12">
        <?php 
        ///***Cadena para Hacer
        $string_get_info =sprintf("SELECT pub_folio,pub_fech_cap,cliente,auto_JINC  FROM pedidos.pub_encabeza_publicidad  where  auto_JINC = 0  and cve_agente =%s",
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
                         <div class="col-sm-4"><h5>Estatus:</h5><h6>
                          <?php 
                            if($row['auto_JINC']==0)
                            {
                                echo  "Pendiente";
                            }
                          ?>
                         </h6></div> 
                 <form  action ="pub_see_updpublicidad.php"  method="POST"> 
                     <div class="col-sm-1"><input hidden type="int" name="nf" value="<?php echo $row['pub_folio'];?>" ><button type="submit" class="btn btn-info buscar"><span class="glyphicon glyphicon-zoom-in"></span></button></div>
                     
                 </form>
                  <?php if($row['auto_JINC']==0)
                            { ?> 
                 <form  action ="pup_selecprod_agente.php"  method="POST"> 
                     <div class="col-sm-1"><input hidden type="int" name="updateSol" value="<?php echo $row['pub_folio'];?>" ><button type="submit" class="btn btn-sucess buscar"><span class="glyphicon glyphicon-edit"></span></button></div>
                 </form>
                <!--Btn Para  Eliminar ----> 
                <div class="col-sm-1" > <button class="deletEL btn btn-danger buscar" type="button" ELEtoEli ="<?php echo $row['pub_folio'];?>" ><span  class="glyphicon glyphicon-trash" ></span></button></div>
                <?php }?> 
                 
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


