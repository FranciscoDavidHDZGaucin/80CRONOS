<?php
/////*****gast_popUpdGasstoCont.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : gast_popUpdGasstoCont.php 
 	Fecha  Creacion : 16/11/2017
	Descripcion  : 
 *          Pop Para  Modificar  El Gasto Por parte del   departamento  de Contabilidad 
 *      
  */ 


session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
require_once('formato_datos.php');
require_once('Connections/conecta1.php');  
 

$cve_GASTO = filter_input(INPUT_GET, 'CVEGAST');
$STR = sprintf("SELECT * FROM pedidos.poliza  where id=%s", GetSQLValueString($cve_GASTO, "int") );

///****Generamos  Qery  
$qeryGasto = mysqli_query($conecta1, $STR); 
$ObjtGasto =mysqli_fetch_array($qeryGasto); 

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Detalle Certeza</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


<!-- Loading Bootstrap -->
<link href="select3/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Loading Flat UI -->
<link href="select3/dist/css/flat-ui.css" rel="stylesheet">
<link href="select2/gh-pages.css" rel="stylesheet">
<link href="select2/select2.css" rel="stylesheet">
<link rel="shortcut icon" href="select3/dist/img/favicon.ico">
      
    <link rel="shortcut icon" href="select3/dist/img/favicon.ico">

    
  </head>
  <!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="script_gastos/fechas_fail_other.js"></script>

<script type="text/javascript">
  var  GASTOCVE = <?php  echo $cve_GASTO;   ?>; 

</script>
<script type="text/javascript">
$(document).ready(function(){
  
        
    ///****Function Get Elementos  Form  
   function   GetInformacion(){
       var InforToSend = {
          ////****Obtenemos  Informacion del  Gasto 
           "cve_gasto":GASTOCVE,
           "subtotl" :parseFloat($("#subTtal").val().replace(",","")),
           "pagoIva" : parseFloat($("#pagIva").val().replace(",","")),
           "comen": IsEmpty($("#txtcoment").val()) ,
           "totalPa" : parseFloat($("#totlP").val().replace(",","")),
           "fechPago":GetFecha()    

       }

     return   InforToSend ;
   }

function   TypeErrorCapt(InformJSON)
  {
       ///***Json de Respuesta
       var   JsonResult = {
                         ///***Variable   Determina Si los  Elemntos  capturados  todos  son True 
                            "AllEle":false,
                            "typeErro" : new  Array(),
                            "htmlerror" : ""
                        }
                        
      
       if(InformJSON.subtotl == false ){
            JsonResult.typeErro.push("Erro No Agrego el Sub Total");
             JsonResult.htmlerror += "*Erro No Agrego el Sub Total<br>" 
       }
       if(InformJSON.total == false ){
           JsonResult.typeErro.push("Erro No Agrego el Total");
           JsonResult.htmlerror += "*Erro No Agrego el Total<br>" 
       }
       if(InformJSON.comen == false  ){
           JsonResult.typeErro.push("Erro No Agrego el IVA");
           JsonResult.htmlerror += "*Erro No Agrego Comentario" 
       }
     
       if(InformJSON.subtotl != false &&
          InformJSON.total != false &&
          
          InformJSON.comen != false )
        {
           JsonResult.AllEle = true;
        }
       
     return   JsonResult;
  }

   ///Validadar Fecha 
  function GetFecha(){
    var  respu =  false;

    if(InputDate_enable()== true)
    {
        if(ValidarFecha($("#fechpago").val())===true)
          {
            respu =  convertoTODB($("#fechpago").val());
          }

    }else {

        respu =$("#fechpago").val(); 

    }

    return  respu;      
 }  
  ///***rEVISAMOS QUE NO SEA  EMPTY  LOS COMENTARIOS 
    function  IsEmpty(value)
    {
        var  result= value; 
     if(value=="" || value == null || value.length == 0)
     {
        result =false; 
     }    
        return   result;
    }
  ///**Btn   Update  Gasto  INFORMACION  
    $("#btnUdate").click(function(){

        var   jsoValue = GetInformacion();
        $.ajax({
                    type:'POST',
                    url: 'script_gastos/gast_UpdateGastCONT.php',
                    data:{"INFOj":JSON.stringify(jsoValue)}, 
                    success: function (inserdb) {
                                        
                          if(inserdb.Res001 ==1 )
                          {
                             window.close();
                          
                          }else {

                            alert(inserdb.Error);
                          }              
                }});


    });
////************************************************************************

$("#subTtal").on({
                "focus": function (event) {
                    $(event.target).select();
                },
                "keyup": function (event) {
                    $(event.target).val(function (index, value ) {
                        return value.replace(/\D/g, "")
                                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
                    });
                }
 });
$("#pagIva").on({
                "focus": function (event) {
                    $(event.target).select();
                },
                "keyup": function (event) {
                    $(event.target).val(function (index, value ) {
                        return value.replace(/\D/g, "")
                                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
                    });
                }
 });





////***********************************************************************

});
</script>
  <body>
      <div class="container">   
          <div  class="row">
             
              <div  class="col-lg-4   col-sm-4  col-xs-12">
             </div>
             <div  class="col-lg-4   col-sm-4  col-xs-12">
                  <strong><?php  ECHO $ObjtGasto['nom_age'];    ?></strong> 
             </div>
             <div  class="col-lg-4   col-sm-4  col-xs-12">
             </div>
          </div>
          <div  class="col-lg-12   col-sm-12  col-xs-12">
             
             <div  class="col-lg-4   col-sm-4  col-xs-12">
             </div>
             <div  class="col-lg-4   col-sm-4  col-xs-12">
                  <strong><?php  ECHO $ObjtGasto['nom_gto'];    ?></strong> 
             </div>
             <div  class="col-lg-4   col-sm-4  col-xs-12">
             </div>
          </div>
         <!--***************************************************************************************--> 
         <div  class="col-lg-12   col-sm-12  col-xs-12">
            <div  class="col-lg-2   col-sm-2  col-xs-12">      
                <strong>Factura:</strong>      
            </div>
             <div  class="col-lg-4   col-sm-4  col-xs-12">
                 <input  type="text" disabled  class="form-control" value="<?php echo $ObjtGasto['factura'];  ?>" >
                 
             </div>
            <div  class="col-lg-1   col-sm-1  col-xs-12"></div>
            <div  class="col-lg-5   col-sm-5  col-xs-12">
                <strong>Tasa Iva </strong> 
                <input   type="number" disabled   value="<?php  echo $ObjtGasto['tasa_iva'];   ?>"  id="tasIva" class="form-control">
            </div>
        </div>
        <!--***************************************************************************************-->
        <!--***************************************************************************************--> 
         <div  class="col-lg-12   col-sm-12  col-xs-12">
             <div  class="col-lg-2   col-sm-2  col-xs-12">
                 <strong>Fecha</strong>
             </div>
             <div  class="col-lg-4   col-sm-4  col-xs-12">
                 <input  value="<?php echo $ObjtGasto['fecha'];  ?>"   type="date" disabled=""  class="form-control"> 
             </div>
            <div  class="col-lg-1   col-sm-1  col-xs-12"></div>
            <div  class="col-lg-5   col-sm-5  col-xs-12">
                <strong>Sub Total</strong>
                <input  id="subTtal" step='any' type="text"  value="<?php echo $ObjtGasto['subtot'];  ?>"   class="form-control" required> 
            </div>
        </div>
        <!--***************************************************************************************-->
      
          <!--***************************************************************************************--> 
         <div  class="col-lg-12   col-sm-12  col-xs-12">
             <div  class="col-lg-2   col-sm-2  col-xs-12">
                 <strong>Sub Total</strong>
             </div>
             <div  class="col-lg-4   col-sm-4  col-xs-12">
                 <input  value="<?php echo $ObjtGasto['subtot'];  ?>"   type="number" disabled=""  class="form-control"> 
             </div>
            <div  class="col-lg-1   col-sm-1  col-xs-12"></div>
            <div  class="col-lg-5   col-sm-5  col-xs-12">
                <strong>Pagos Iva$</strong>
                <input  id="pagIva"  step='any' type="text"   value="<?php echo $ObjtGasto['iva'];  ?>"  type="number"  class="form-control" required> 
            </div>
        </div>
        <!--***************************************************************************************-->
      
        <!--***************************************************************************************--> 
         <div  class="col-lg-12   col-sm-12  col-xs-12">
             <div  class="col-lg-2   col-sm-2  col-xs-12">
                 <strong>Iva</strong>
                 
             </div>
             <div  class="col-lg-4   col-sm-4  col-xs-12">
                 <input       value="<?php echo $ObjtGasto['iva'];  ?>"   type="number" disabled  class="form-control"> 
                
             
             </div>
            <div  class="col-lg-1   col-sm-1  col-xs-12"></div>
            <div  class="col-lg-5   col-sm-5  col-xs-12">
                <strong>Comentarios</strong>
                <textarea  id="txtcoment"  type="text"  class="form-control" required></textarea> 
            </div>
        </div>
        <!--***************************************************************************************-->
      <!--***************************************************************************************--> 
         <div  class="col-lg-12   col-sm-12  col-xs-12">
             <div  class="col-lg-3   col-sm-3  col-xs-12">
               
             </div>
             <div  class="col-lg-2   col-sm-2  col-xs-12">
             </div>
            <div  class="col-lg-1   col-sm-1  col-xs-12"></div>
            <div  class="col-lg-5   col-sm-5  col-xs-12">
             </div>
        </div>
        <!--***************************************************************************************-->
       <!--***************************************************************************************--> 
         <div  class="col-lg-12   col-sm-12  col-xs-12">
             <div  class="col-lg-2   col-sm-2  col-xs-12">
                 <strong>Total</strong>
             </div>
             <div  class="col-lg-4   col-sm-4  col-xs-12">
                 <input id="totlP"  value="<?php echo $ObjtGasto['total'];  ?>"   type="int"   class="form-control"> 
             </div>
            <div  class="col-lg-1   col-sm-1  col-xs-12"></div>
            <div  class="col-lg-5   col-sm-5  col-xs-12">
                <strong>Fecha A Pagar </strong>
                  <input  id="fechpago"  type="date" value ="<?php echo $ObjtGasto['f_pago'];  ?>"  class="form-control"  required> 
             </div>
        </div>
        <!--***************************************************************************************-->
        <br><br>
      <div  class="col-lg-12   col-sm-12  col-xs-12">
             
             <div  class="col-lg-4   col-sm-4  col-xs-12">
             </div>
             <div  class="col-lg-4   col-sm-4  col-xs-12">
              <br> 
                  <button  id="btnUdate"  type="button"  class="form-control">Modificar</button>
             </div>
             <div  class="col-lg-4   col-sm-4  col-xs-12">
             </div>
          </div>
          
      
      </div> <!-- /.Canvas -->
  
  </body>
</html>      
