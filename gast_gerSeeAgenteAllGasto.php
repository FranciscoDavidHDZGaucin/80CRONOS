
<?php
/////****gast_gerSeeAgenteAllGasto.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : gast_gerSeeAgenteAllGasto.php
 	Fecha  Creacion : 15/11/2017
	Descripcion  : 
 *             Escrip Para Mostrar  El Estatus de  los   gastos De los AGENTES
  */

////**Inicio De Session 
session_start();
///****Cabecera Cronos
require_once('header.php');

require_once('Connections/conecta1.php');
require_once('formato_datos.php');
/////***********************************************************************
$str_getAllGasto = sprintf("select id,nom_gto,agente,  (if(agente >= 400 && agente <=450,(SELECT nombre FROM pedidos.usuarios_locales where  rol = agente ) , (IF(pedidos.poliza.agente >= 1 AND pedidos.poliza.agente <= 6,(SELECT pedidos.relacion_gerentes.zona FROM pedidos.relacion_gerentes WHERE pedidos.relacion_gerentes.cve_gte = pedidos.poliza.agente GROUP BY pedidos.relacion_gerentes.cve_gte),(SELECT nom_empleado FROM pedidos.relacion_gerentes where cve_age = pedidos.poliza.agente)        ))))   as nom_agente
,factura,fecha,subtot,iva,total,nom_pdf,f_pago,pago,vbo_gerente    from pedidos.poliza where  agente in  (SELECT cve_age FROM pedidos.relacion_gerentes  where  cve_gte = %s)||
 agente in  (SELECT rol FROM pedidos.usuarios_locales where  extra2 =%s )",
                    GetSQLValueString($_SESSION["usuario_agente"], "int"), GetSQLValueString($_SESSION["usuario_agente"], "int") );
///****Generamos  Qery  
$qery_Insertgasto = mysqli_query($conecta1, $str_getAllGasto) ; 
///***Validar  Qery  Cabeza
if(!$qery_Insertgasto)
{   ///***Error insert Consulta 
   $ExitGAS = 0; 
}else{
    ///**Insert Correct
    $ExitGAS = 1; }
//*****************************************************************
     $AreglObjetos =  Array();
while ($rowPoliza = mysqli_fetch_array($qery_Insertgasto)) {
    
     $OBJETO = array(          "cve_gasto"=>$rowPoliza['id'],
                               "nom_agente"=>  $rowPoliza['nom_agente'],
                               "concepto" => $rowPoliza['nom_gto'],
                               "fecha" => $rowPoliza['fecha'],
                               "folio" => $rowPoliza['factura'],
                               "subtotal" => $rowPoliza['subtot'],
                               "iva" => $rowPoliza['iva'],
                               "total"=> $rowPoliza['total'],
                               "pago" => $rowPoliza['pago'],
                               "f_pago" => $rowPoliza['f_pago'],
                               "PDF" => $rowPoliza['nom_pdf'], 
                               "vbo" => $rowPoliza['vbo_gerente']
             );   
              
              array_push($AreglObjetos, $OBJETO);
    
}
    
    
    
    
////****Areglo de  Objetos  
$ArregloMain = json_encode($AreglObjetos);


?> 
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="script_gastos/fechas_fail_other.js"></script>
<script type="text/javascript">
    var  NUMAG = <?PHP echo $_SESSION["usuario_agente"];  ?>;
    var  ObjMain  = <?PHP echo $ArregloMain;  ?>;
    var  tosendInfo =  null;
    var otherInfo = <?php echo  $_SESSION['ConteCuent']; ?>;
    ////****Varible de Control Estatus
    var  EstaCVEgasto =null; 
   /////****Generador   de tabla  Html 
    function  GenTablaGastos(ObtMains)
   {
       ////***Variable COntenedora HTML
      var  finaHTML ="" ;
     for(var i in ObjMain ){
                 finaHTML += GenRowTable(ObjMain[i])
     }  
       
     return  finaHTML;   
   }
      
        
   function  GenRowTable(Obtjs)
   {
       var htmltable = '<tr>'+
                        '<td>'+Obtjs.nom_agente+'</td>'+
                       '<td>'+Obtjs.concepto+'</td>'+
                       '<td>'+Obtjs.fecha+'</td>'+
                       '<td>'+Obtjs.folio+'</td>'+
                       '<td>'+Obtjs.subtotal+'</td>'+
                       '<td>'+Obtjs.iva+'</td>'+
                       '<td>'+Obtjs.total+'</td>'+
                       '<td>'+Obtjs.pago+'</td>'+
                       '<td>'+Obtjs.f_pago+'</td>'+
                        GetPdfExist(Obtjs.PDF)+
                        '<td id="td'+Obtjs.cve_gasto+'"  >'+DefinirEst(Obtjs.vbo,Obtjs.cve_gasto)+'</td>'+ 
                        
                       '</tr>';
      return   htmltable;
   }
   function  GetPdfExist(Pdf) 
   {
       var   cadenaHtml =""; 
            if(Pdf== null)
            {
               cadenaHtml =  '<td></td>';
            } else{
                
               cadenaHtml =  '<td><a href="CFD_PAGOS/'+Pdf+'" target="_blank"><image  width=24px  heigth=24px src="images/PDF.svg"></a></td>'
            }  
            return  cadenaHtml;
   }
   function  DefinirEst(EST,cveGasto){
          var  htmlrESULT ="";    
           if(EST == 0)
           {
              htmlrESULT = "<button   type='button' value="+cveGasto+"  class='btnEstatusTB btn btn-danger'>Pendiente</button>";  
           }
           if(EST == 1)
           {
               htmlrESULT = "<image  src='images/thumbup.png' >";
           }
           if(EST == 2)
           {
               htmlrESULT = "<image  src='images/thumdown.png' >";
           }
     return   htmlrESULT; 
   }
   function  GetFactura(CVE_GASTO)
   {
       ////***Variable COntenedora HTML
      var  finaoBT =null  ;
            for(var i in ObjMain ){
                     if(ObjMain[i].cve_gasto ==CVE_GASTO )
                     {
                       finaoBT =ObjMain[i].folio;
                       break;
                     }
            }  
      return  finaoBT;  
   }
      
</script> 
<script type="text/javascript">
$(document).ready(function(){
  $("#onlyfor").remove();
	
     
   $("#CONTbODY").html(GenTablaGastos(ObjMain))
   ////****bTN  para  Estatus dentro de la tabla
   $(document).on("click",".btnEstatusTB",function(){
        EstaCVEgasto =  $(this).attr('value');
        var FOLIO =GetFactura(EstaCVEgasto) ;
      console.log(FOLIO)
        $("#folresul").html(FOLIO);
        $("#eSTmODAL").modal('show');
    });
    ////***Btn  para Definir  Estatus Final  Gasto 
   $(document).on("click",".updestbtn",function(){
                ///***Mostramos Modal  
        var  SelectNewEST =  $(this).attr('value');
         
         $.ajax({
                   type:'POST',
                   url: 'script_gastos/gast_UpdEstGast.php',
                   data:{"FL":EstaCVEgasto,"EST":SelectNewEST},
                   success: function (inserdb) {
                        if(inserdb.ERRORES==0)
                        {
                            var   IdTd = "#td"+EstaCVEgasto;
                            $(IdTd).empty();
                            $(IdTd).html(DefinirEst(SelectNewEST,EstaCVEgasto));
                            $("#eSTmODAL").modal('toggle');
                          
                        }else{
                           $("#MensEl").txt("Error Al modificar  Estatus de Folio") 
                        }  

                 }});
         
         
         
         
         
    });    
    
 
    
    
    
});
</script>
</div>
<style>
    
.dumdivstyle.jumbotron {
    /*background: #000;*/
    height: 400px;
   background-image:url('images/IMGJUMBO.jpg');
   position: relative;
   top:-17px;
   box-shadow: -2px 11px 10px #999; 
}
.ConFrm {
    background: #fff;
    height: 500px;
    position: relative;
    top: -250px;
}
.ContInputs{
     background: #fffdfde6;
     height:auto;/*500px;*/
     top :50px;
     border-radius: 24px;
     box-shadow: -5px 7px 57px #999
    
}


.ldtrans{
    
    opacity: 0.5;
}
.contTITLE{
    position :absolute;
    top:36px;
    left:-217px
}
.titlemain{
    font-size: 50px; 
    color:white;
}
.contnfileFinal {
   margin-top: 50px;
  margin-bottom: 50px;
}
.btn-file {
  position: relative;
  overflow: hidden;
  }
.btn-file input[type=file] {   
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
    background-image:url('images/uppercase.png');
}
/*.txtcoment{
    overflow: hidden;
}*/
.moneyapli{
  -moz-appearance: textfield;
  text-align: end;   
}
.errost.modal-header {
    background-color: #f00c;
    box-shadow: 5px 2px 4px;
}
.saveelem.modal-header { 
        background-color: #1db46fcc;
        box-shadow: 5px 4px 4px;
}

.titleerr {
    font-size: xx-large;
    color: white;
}
</style>
<div class="dumdivstyle  jumbotron">
  <!-- <image  src="image</IMGJUMBO.jpg"  >--> 
    <form  id="mainform">  
        <div class="contTITLE  col-lg-12 col-sm-12  col-xs-12">
                <strong class="titlemain">Estado de Gastos</strong>
        </div> 
        <!--*********************************************************************-->
        <?php   
                   ////****Mostramos  si Solo  Es un Agente   o Desarollo 
                    if($_SESSION["usuario_agente"]==1 ||
                       $_SESSION["usuario_agente"]==2 ||
                       $_SESSION["usuario_agente"]==3 ||
                       $_SESSION["usuario_agente"]==4 ||
                       $_SESSION["usuario_agente"]==5 ||
                       $_SESSION["usuario_agente"]==5){
                  ?>   
        <div  class="busqInfo row"> 
        <div class="  col-lg-12 col-sm-12  col-xs-12" >
        <div class="ldtrans col-lg-1 col-sm-12 col-xs-12" ></div>
        <div class="ContInputs  col-lg-8 col-sm-12 col-xs-12" >
         <!--**********Inicio Contenedor Informacion dentro del Recuadro  blanco**********************-->
            <ul class="nav nav-tabs">
                <li ><a  href="gast_seegastAll.php">Mis Gastos</a></li>
                <li class="active"><a  href="gast_gerSeeAgenteAllGasto.php ">Gastos Vendedores</a></li>
            </ul> 
         <!--**********Fin Contenedor Informacion dentro del Recuadro  blanco**********************-->
        </div>
        <!--**********Fin Contenedor Informacion dentro del Recuadro  blanco**********************-->
        </div>
        <div class="ldtrans col-lg-1 col-sm-12 col-xs-12" ></div>
        </div> 
        <br>
      
        <br> 
        <?php   }/// ?>
        <!--*********************************************************************-->
        <div class="  col-lg-12 col-sm-12  col-xs-12" >
        <div class="ldtrans col-lg-1 col-sm-12 col-xs-12" ></div>
        <div class="  col-lg-8 col-sm-12 col-xs-12" >
         <!--**********Inicio Contenedor Informacion dentro del Recuadro  blanco**********************-->
         <br>
          <div class="ContInputs table-responsive">
         <table class="ContInputs table table-responsive table-hover" id="dataTables-gastos">
             <thead>
                 <tr>
                    <th>Nombre Agente</th>
                    <th>Concepto</th>
                    <th>Fecha</th>
                    <th>Factura</th>
                    <th>SubTotal</th>
                    <th>Iva</th>
                    <th>Total</th>
                    <th>Pagado</th>
                    <th >F_Pago</th>
                    <th>PDF</th>
                    <th>Autorizar</th>
                 </tr> 
             </thead>
                    <tbody id="CONTbODY">
                        <?php                         
                         /*   while ($rowPoliza = mysqli_fetch_array($qery_Insertgasto)) {


                            echo   '<tr>';
                            echo       '<td>'.$rowPoliza['nom_agente'].'</td>';
                            echo       '<td>'.$rowPoliza['nom_gto'].'</td>';
                            echo       '<td>'.$rowPoliza['fecha'].'</td>';
                            echo       '<td>'.$rowPoliza['factura'].'</td>';
                            echo       '<td>'.$rowPoliza['subtot'].'</td>';
                            echo       '<td>'.$rowPoliza['iva'].'</td>';
                            echo       '<td>'.$rowPoliza['total'].'</td>';
                            echo       '<td>'.$rowPoliza['pago'].'</td>';
                            echo       '<td>'.$rowPoliza['f_pago'].'</td>';
                            echo       '<td>'.$rowPoliza['nom_pdf'].'</td>';
                            echo       '</tr>';


                            }*/
                          ?>           
                        
                    </tbody>
         </table>
          </div>
         
         
        </div>
        <!--**********Fin Contenedor Informacion dentro del Recuadro  blanco**********************-->
        </div>
        <div class="ldtrans col-lg-1 col-sm-12 col-xs-12" ></div>
    </form>
    
    <!---Modal  Mensages----->    
     <div  class="modal fade" id="ModalMNs" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div id="cabecssMy" class="errost modal-header"  >
                <button type="button" id=""  class="close_coment close" data-dismiss="modal">&times;</button>
                <h5  id="titleInformaci"  class="titleerr" ><h5>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div id="CONTMOD" class="row" class="well">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10"><strong id="nfMen">  </strong></div>
                        <div class="col-sm-1"></div>
                        
                    </div>
                     <div    class="row" class="well">
                        
                            <div class="col-sm-4" ></div>
                            <div class="col-sm-1" id="contSaveInfo" hidden ><button  type="button" id="btnSave"  class="btn btn-success"> Guardar</button></div>
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-1" id="contCancela"  ><button  type="button" data-dismiss="modal" class="btn  btn-danger">  Cancelar</button></div>
                            <div class="col-sm-4" ></div>
                        
                    </div>
                    <div  class="row" class="well">
                        
                            <div class="col-sm-4" ></div>
                            <div class="col-sm-1" ></div>
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
    <!--************Dialog Estatus********************-->
     <div  class="modal fade" id="eSTmODAL" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
                <button type="button" class="close_coment close" data-dismiss="modal">&times;</button>
                <h5>Modificar  Estatus<h5>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div id="CONTMOD" class="row" class="well">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-1"><strong>Folio:</strong></div>
                        <div class="col-sm-8" id="folresul"></div>
                        <div class="col-sm-2"></div>
                        
                    </div>
                     <div  class="row" class="well">
                        
                            <div class="col-sm-4" ></div>
                            <div class="col-sm-1" > <button  type="button"  class="updestbtn  btn btn-info" value="1" >Autorizar</button> </div>
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-1" ><button  type="button"  class="updestbtn  btn btn-danger" value="2" >Rechazar</button></div>
        				 </div>
                    <div  class="row" class="well">
                        
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-8" ><h5 class="MensEl" >Nota Si Usted Define Cualquier Tipo de Estatus NO Podra Modificar</h5> </div>
                            <div class="col-sm-2" ></div>
                        
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
</div>

<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 



