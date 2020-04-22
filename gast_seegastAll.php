<?php
/////****gast_seegastAll.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : gast_seegastAll.php
 	Fecha  Creacion : 13/11/2017
	Descripcion  : 
 *             Escrip Para Mostrar  El Estatus de  los   gastos
  */

////**Inicio De Session 
session_start();
 ///***Heder  Gerentes
						if($_SESSION["usuario_agente"] ==1 ||
								$_SESSION["usuario_agente"] ==2 ||
							$_SESSION["usuario_agente"] ==3 ||
							$_SESSION["usuario_agente"] ==1 ||
							$_SESSION["usuario_agente"] ==1 ||
							$_SESSION["usuario_agente"] ==1 ){
		
								 require_once('header_gerentes.php');   
							}else {
								if($_SESSION["usuario_agente"] >= 400 && $_SESSION["usuario_agente"] < 499 )
								{
										 require_once('heder_desarrollo.php'); 
								}else {
									 
										require_once('header.php');

 
								}    

							}

require_once('Connections/conecta1.php');
require_once('formato_datos.php');
/////***********************************************************************
$str_getAllGasto = sprintf("select * from poliza where  agente=%s ",
                    GetSQLValueString($_SESSION["usuario_agente"], "int") );
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
   ////***Total de Gastos sin Fecha DepAGO
     $TOTSL_GAST = 0 ; 
     $AreglObjetos =  Array();
while ($rowPoliza = mysqli_fetch_array($qery_Insertgasto)) {
    
     $OBJETO = array(
                               "concepto" => $rowPoliza['nom_gto'],
                               "fecha" => $rowPoliza['fecha'],
                               "folio" => $rowPoliza['factura'],
                               "subtotal" => $rowPoliza['subtot'],
                               "iva" => $rowPoliza['iva'],
                               "total"=> $rowPoliza['total'],
                               "pago" => $rowPoliza['pago'],
                               "f_pago" => $rowPoliza['f_pago'],
                               "Xml" => $rowPoliza['nom_xml'],
                               "PDF" => $rowPoliza['nom_pdf']  
             );   
             if( strcmp($rowPoliza['f_pago'],"0000-00-00")=== 0)
             {
                 $TOTSL_GAST +=  $rowPoliza['total'] ;
             }
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
    var  totls = <?php echo  $TOTSL_GAST; ?>;
   /////****Generador   de tabla  Html 
    function  GenTablaGastos(ObtMains)
   {
       ////***Variable COntenedora HTML
      var  finaHTML ="" ;
     for(var i in ObtMains ){
                 finaHTML += GenRowTable(ObtMains[i])
     }  
       var htmltable = '<tr>'+
                       '<td></td>'+
                       '<td></td>'+
                       '<td></td>'+
                       '<td></td>'+
                       '<td>Total Acomulado</td>'+
                       '<td>'+totls+'</td>'+
                       '<td></td>'+
                       '<td></td>'+
                       '<td></td></tr>';
     return  finaHTML;   
   }
      
        
   function  GenRowTable(Obtjs)
   {
       var htmltable = '<tr>'+
                       '<td>'+Obtjs.concepto+'</td>'+
                       '<td>'+Obtjs.fecha+'</td>'+
                       '<td>'+Obtjs.folio+'</td>'+
                       '<td>'+Obtjs.subtotal+'</td>'+
                       '<td>'+Obtjs.iva+'</td>'+
                       '<td>'+Obtjs.total+'</td>'+
                       '<td>'+Obtjs.pago+'</td>'+
                       '<td>'+Obtjs.f_pago+'</td>'+
                       '<td><a href="CFD_PAGOS/'+Obtjs.Xml+'" download><image  width=24px  heigth=24px src="images/XML.svg"></a></td>'+
                       GetPdfExist(Obtjs.PDF)+ 
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
      ////*****Convertir a  Formato  Moneda 
function currency(value, decimals, separators) {
    decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
    separators = separators || ['.', "'", ','];
    var number = (parseFloat(value) || 0).toFixed(decimals);
    if (number.length <= (4 + decimals))
        return number.replace('.', separators[separators.length - 1]);
    var parts = number.split(/[-.]/);
    value = parts[parts.length > 1 ? parts.length - 2 : 0];
    var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
        separators[separators.length - 1] + parts[parts.length - 1] : '');
    var start = value.length - 6;
    var idx = 0;
    while (start > -3) {
        result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start))
            + separators[idx] + result;
        idx = (++idx) % 2;
        start -= 3;
    }
    return (parts.length == 3 ? '-' : '') + result;
}
</script> 
<script type="text/javascript">
$(document).ready(function(){
  $("#onlyfor").remove();
	
     $("#totalpendinte").html( "$"+currency(totls, 2, [',', ",", '.']))
    $("#CONTbODY").html(GenTablaGastos(ObjMain))  
        

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
                       $_SESSION["usuario_agente"]==6){
                  ?>   
        <div  class="busqInfo row"> 
        <div class="  col-lg-12 col-sm-12  col-xs-12" >
        <div class="ldtrans col-lg-1 col-sm-12 col-xs-12" ></div>
        <div class="ContInputs  col-lg-8 col-sm-12 col-xs-12" >
         <!--**********Inicio Contenedor Informacion dentro del Recuadro  blanco**********************-->
            <ul class="nav nav-tabs">
                <li class="active"><a  href="gast_seegastAll.php">Mis Gastos</a></li>
                <li ><a   href="gast_gerSeeAgenteAllGasto.php ">Gastos Vendedores</a></li>
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
        <div class="ContInputs  col-lg-8 col-sm-12 col-xs-12" >
            <div   class="row"> 
                <strong> Total de Efectivo Pendiente de Pago  <h4 id ="totalpendinte"></h4> </strong>  
            </div> 
         <!--**********Inicio Contenedor Informacion dentro del Recuadro  blanco**********************-->
          <div class="ContInputs table-responsive">
         <table class="ContInputs table table-responsive table-hover" id="dataTables-existencias">
             <thead>
                 <tr>
                    <th>Concepto</th>
                    <th>Fecha</th>
                    <th>Factura</th>
                    <th>SubTotal</th>
                    <th>Iva</th>
                    <th>Total</th>
                    <th>Pagado</th>
                    <th >F_Pago</th>
                    <th>XML</th>
                    <th>PDF</th>
               </tr> 
             </thead>     
                    <tbody id="CONTbODY">
                         
                        
                        
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
    
    
    
</div>
</div>

<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 


