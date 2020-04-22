<?php
///***desv_planea_getDes.php 
/*
 require_once('formato_datos.php');
 require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
 mssql_select_db("AGROVERSA");  

 ///***+Funcion para  Obtener el Precio Unitario 
function  Get_PrecioProd($cve_pro)
{
 ///**+Generamos Cadena
 $str_prod = sprintf("SELECT ItemCode,Price FROM  plataformaproductosl1 where  Currency = 'MXP' and  itemCode =%s",
 GetSQLValueString($cve_pro, "text"));
 ///**Obtenemos  Qery   
 $qerProd =  mssql_query($str_prod); 
 ///**Convertimos  a Fetch    
 $fetchElm =mssql_fetch_array($qerProd);
 ///***Retornamos el Precio
   return  $fetchElm['Price'];
} 
///***Funcion de Ordenamiento  Quick Sort  Para Ordenar elementos 
function simple_quick_sort($arr)
{
    if(count($arr) <= 1){
        return $arr;
    }
    else{
        
        $Obje = $arr[0];
        $pivot =$Obje['Porcent']; /// $arr[0];
        $left = array();
        $right = array();
        for($i = 1; $i < count($arr); $i++)
        {   
            $objI = $arr[$i];
            if($objI['Porcent'] > $pivot){
                $left[] = $arr[$i];
            }
            else{
                $right[] = $objI;///$arr[$i];
            }
        }
        return array_merge(simple_quick_sort($left), array($Obje), simple_quick_sort($right));
    }
}
///***Cadena  para  Hacer la  consulta
$string_get_TbDes = "SELECT cve_prod , nom_agen, demanda, sum(tot_cant) as VentaReal,sum(tot_cant)-demanda AS Variacion ,mes ,anio ,falta_fac2 ,tot_cant,tot_linea FROM pedidos.desv_GetAllVentVSPro  where  falta_fac2 >= '2017-03-01' and falta_fac2 <= '2017-03-30'    group by cve_prod ";
 ////*Realizamso  Peticion  
$qery_des = mysqli_query($conecta1, $string_get_TbDes);

///***Areglo para Alamcenar  la  Variacion   Negativa 
$Are_VarPu_NE = array();
///***Areglo para Alamcenar  la  Variacion   Positivo  
$Are_VarPu_PO = array();
///**Variable TOTALNeg 
$ToltNeg =0; 
//***Variable TOTALPos
$ToltPos =0;
///***Ciclo para  Obtener la  VarPu 
while ( $fetch_desv= mysqli_fetch_array($qery_des)  )
{    
     /// **** Variacion por  Precio  Unitario  sin importar negativos O Positivos
     $VarPu =$fetch_desv['Variacion']*(Get_PrecioProd($fetch_desv['cve_prod'])) ;
     ///***Generamos el  Objeto con Inforamcion Basica  para  despues  Buscar los elementos  Seleccionados
     $objeVar =  array("cve_prod"=>$fetch_desv['cve_prod'] , "nom_agen"=>$fetch_desv['nom_agen'], "VarPu"=> $VarPu ,"Porcent"=>0 );
    ///***Separamos  las  Variacions  si  son Positivos (+)
     if($VarPu>0)
     {
         array_push($Are_VarPu_PO, $objeVar);
         $ToltPos += $VarPu;
     }
     ///***Separamos  las  Variacions  si  son Negativos (-)
     if($VarPu<0)
     {
         array_push($Are_VarPu_NE, $objeVar);
         $ToltNeg += $VarPu;
     }
     
}
///***Agregar Elementos Negativos
$Are_VarPu_NEWhitPor =array();
////***Ciclo paras   Obtener Porcentajes para el  80/20 
foreach ($Are_VarPu_NE  as $ELEM)
{
     $ELEM['Porcent'] = round(($ELEM['VarPu']/$ToltNeg)*100);
     array_push($Are_VarPu_NEWhitPor, $ELEM);
   // echo  $ELEM['Porcent']."<br>"; 
}
///***Agregar Elementos Positivos
$Are_VarPu_POSWhitPor =array();
////***Ciclo paras   Obtener Porcentajes para el  80/20 
foreach ($Are_VarPu_PO  as $ELEM)
{
     $ELEM['Porcent'] = round(($ELEM['VarPu']/$ToltPos)*100);
     array_push($Are_VarPu_POSWhitPor, $ELEM);
   // echo  $ELEM['Porcent']."<br>"; 
}

///**Ordenamos  los Elementos Negativos 
$Are_VarPu_NEORDER = simple_quick_sort($Are_VarPu_NEWhitPor);
///**Ordenamos  los Elementos Positivos
$Are_VarPu_POORDER = simple_quick_sort($Are_VarPu_POSWhitPor);
 ///****Arreglo Solo 80 Negativos
 $Are_only80Ne = array();
///******Obtenemos  los  Elementos  que sumados  Obtengan el  80 % Negativos
foreach($Are_VarPu_NEORDER  as  $ELEM)
{
    $SUM80 += $ELEM['Porcent'] ;
    
    IF($SUM80 <= 81)
    {
      array_push($Are_only80Ne, $ELEM);
    }
              
}
///****Arreglo Solo 80 Posit
 $Are_only80PO = array();
///******Obtenemos  los  Elementos  que sumados  Obtengan el  80 % Positivos
foreach($Are_VarPu_POORDER  as  $ELEM)
{
    $SUM80 += $ELEM['Porcent'] ;
    
    IF($SUM80 <= 81)
    {
      array_push($Are_only80PO, $ELEM);
    }
              
}
///echo json_encode($Are_only80Ne);

echo json_encode($Are_only80PO);
///echo json_encode($sorted); ///implode(",",$sorted)." @sorted<br>";


///echo json_encode($Are_VarPu_NE);
// echo json_encode($Are_VarPu_PO);
/*
echo  $ToltPos; 
echo '<br><p>///////</p>';
echo  $ToltNeg; */

////**Inicio De Session 
///session_start();
///****Cabecera Cronos +
require_once 'header_planeador.php';
?>
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script> 
<!--Escrip  para  validar  Fecha de Req-->
<script src ="scrip_desviaciones/fechas_fail_other.js"></script>
<script>  
$(document).ready(function(){
    
        var tbelemetos001;
        var  Areglo8020  =  new Array();
        
    
      $("#btnGet").click(function(){
          
         tbelemetos001="";
         Areglo8020  =  new Array();

       ///***Validamos Que  la  fecha Req No sea  Null    y el  alamacen 
       if ((ValidarFecha($('#fetInic').val())== false||ValidarFecha($('#fetfin').val())== false)  && InputDate_enable() == true  )
       {
                    alert("Lo Sentimos la Fecha No Tiene El Formato Correcto dd/mm/año ");    
       }else{
           if(FechInicMenorQFin($('#fetInic').val(), $('#fetfin').val()) == true){
               
               ///***Agregamos  Mensages al  Modal
             $("#menModal").html("<h5>Obteniendo  Elementos esto puede tardar espere...</h5>"); 
             $("#contMe").html("<img  src='scrip_desviaciones/loadinggif.gif'>");
               
               ///alert($("#fetInic").val() + $("#fetfin").val() );
               
                        var  fechIn =  $("#fetInic").val() ;
                        var  fechFin =$('#fetfin').val();
                        var  jsonTo ={"Inic":fechIn,"Fin": fechFin  };
                      
                 ///****Obtenemos Obteneos elementos   80/20 
                       $.ajax({
                            type:'POST',
                            url: 'scrip_desviaciones/Get_001_8020.php',
                            data:jsonTo,
                            success: function (dato001) {
                               /// console.log(dato001.Are_VarPu_NE)
                             
                               ////*******Obtener Los  Elementos Postivos(+)*********************************************************************
                               var  GetPos = $.ajax({
                                                type:'POST',
                                                url: 'scrip_desviaciones/Get_PositivosElem_8020.php',
                                                data:dato001,
                                                 success: function (datos003) {
                                                           var  arePos = JSON.parse(datos003.ElPos);
                                                                   ////var     areNeg = JSON.parse(datos002.ElNeg); 
                                                                        for ( var i=0; i < arePos.length ; i ++ )
                                                                         {

                                                                           tbelemetos001 += "<tr><td>"+ arePos[i].nom_agen +"</td><td>"+arePos[i].cve_prod+"</td><td>"+arePos[i].VetR+"</td><td>"+arePos[i].PRO+"</td><td>"+arePos[i].PrePro+"</td><td>"+arePos[i].VaA+"</td><td>"+arePos[i].VarPu+"</td><td>"+arePos[i].Porcent+"</td></tr>";   
                                                                          Areglo8020.push(arePos[i]);
                                                                         }
                                                                         ///  console.log("Post Obtenidos"+Areglo8020.length +tbelemetos001 );
                                                                          /// $("#boTable").html(tbelemetos001);  
                                                                          
                                                 }
                                           });
                                           
                                           
                                             ////*******Obtener Los  Elementos Postivos(-)*********************************************************************
                                               GetPos.then(function(){
                                                                            
                                                                                 $.ajax({
                                                                                type:'POST',
                                                                                url: 'scrip_desviaciones/Get_PositivosElem_8020.php',
                                                                                data:{"Are_VarPu_PO": dato001.Are_VarPu_NE,"ToltPos":dato001.ToltNeg  },
                                                                                success: function (datos002) { 

                                                                                                    var lemetos002;
                                                                                                         var  arePos = JSON.parse(datos002.ElPos);
                                                                                                           ////var     areNeg = JSON.parse(datos002.ElNeg); 
                                                                                                                for ( var i=0; i < arePos.length ; i ++ )
                                                                                                                 {
                                                                                                                  /// console.log("Porcenta"+arePos[i].Porcent);  
                                                                                                                  lemetos002 += "<tr><td>"+ arePos[i].nom_agen +"</td><td>"+arePos[i].cve_prod+"</td><td>"+arePos[i].VetR+"</td><td>"+arePos[i].PRO+"</td><td>"+arePos[i].PrePro+"</td><td>"+arePos[i].VaA+"</td><td>"+arePos[i].VarPu+"</td><td>"+arePos[i].Porcent+"</td></tr>";   
                                                                                                                  Areglo8020.push(arePos[i]);
                                                                                            /// console.log(tbelemetos); 
                                                                                                                }
                                                                                                               ///console.log("N# Elementos:"+Areglo8020.length );
                                                                                                var Final =  tbelemetos001+""+lemetos002 ;                
                                                                                                $("#boTable").html(Final);  
                                                                                        }
                                                                             });
                                     
                                                                             $('#ModalMNs').modal('hide');
                                               
                                               
                                               }); 
                                                                        
                                           
                        ///****************************************
                            }
                         });
                

        
               
          
           }else {
               
               alert("Lo sentimos Fecha Inicio Mayor !!!");
           }
           
          
        }
        
        
        
      });
    
    
    ///****Btn  Enviar Elementos  a  Insertar 
    $("#btnUP").click(function(){
            
            if(Areglo8020.length == 0 ||Areglo8020.length == null ) {
              ///****Ejecutamos  La funcion   
                $('#ModalMNs').modal('show');
                 ///***Agregamos  Mensages al  Modal
             $("#menModal").html("<h5>Error No Existen Elementos para Agregar</h5>"); 
             $("#contMe").html("<img  src='scrip_desviaciones/desv_error.gif'>"); 
                
            }else
            {       
                            ///***Validamos Que  la  fecha Req No sea  Null    y el  alamacen 
                    if ((ValidarFecha($('#fetInic').val())== false||ValidarFecha($('#fetfin').val())== false)  && InputDate_enable() == true  )
                    {
                                 alert("Lo Sentimos la Fecha No Tiene El Formato Correcto dd/mm/año ");    
                    }else{
                        if(FechInicMenorQFin($('#fetInic').val(), $('#fetfin').val()) == true){
                            /////***Agregamos los  Btn  para la  Insercion 
                                             $("#menModal").html("<h5>Envió de  Desviaciones</h5>");
                                             $("#contMe").html('<button hidden type ="button"  class="btnUpEL btn  btn-success">Enviar</button>');
                                             $('#ModalMNs').modal('show');

                        }


                      } 
                
              
            }
            
                           
    });
    ///****Btn   Enviar elementos
         $(document).on("click",".btnUpEL",function(){
        
         var  fechIn =  $("#fetInic").val() ;
             var  fechFin =$('#fetfin').val();
         ///***Enviamos los elementos  a  Insertar  
              $.ajax({
                    type:'POST',
                    url: 'scrip_desviaciones/desv_addDesvi8020.php',
                    data: {"El8020" : JSON.stringify(Areglo8020), "FechIni":fechIn,"FinFech":fechFin },
                     success: function (datos) {
                             
                             if(datos.Est == true && datos.EXIDES == false)
                             {
                                ///***Generamos los encabezados  
                                    $.ajax({
                                          type:'POST',
                                          url: 'scrip_desviaciones/desv_genEnca.php',
                                          data: {"FechIni":fechIn,"FinFech":fechFin },
                                           success: function (datos) {
                                                 if(datos.Est == true )
                                                {
                                                     console.log("Correcto Envio");
                                                 $('#ModalMNs').modal('hide');  
                                                }else {
                                                      $("#menModal").html("<h5>ERROR !!!</h5>");
                                                      $("#contMe").html("<H6>En Generar Id Desviacion !!!!</H6> <BR>  <img  src='scrip_desviaciones/desv_error.gif'>");
                                           
                                 
                                                }
    
                                           }});
                               
                               
                             }else{
                                 
                                   $("#menModal").html("<h5>ERROR !!!</h5>");
                                  $("#contMe").html("<H6>Existen Desviaciones con la fecha Seleccionada</H6> <BR>  <img  src='scrip_desviaciones/desv_error.gif'>");
                                           
                                 
                                 
                             }
                             
                     }
               });
        
        });
        ///***Cierre  de Modal
        $("#btnCloseM").click(function(){
            tbelemetos001="";
            Areglo8020  =  new Array();
            $("#boTable").html(tbelemetos001);  
        
        })
    ///***Ejecutamos Espera
    $(document).ajaxStart(function(){
       /// $("#ModalMNs").show();
       $('#ModalMNs').modal('show');
    }) ;
});
</script>

<div class="container">
    <div class="row">
        <h3>Desviaciones  :  Generador  80/20 
</h3> 
    </div> 
    <br><br>
    <div  class="col-lg-12  col-sm-12">
        <div  class="form-inline">
            <div  class="col-sm-4">
                <label>Fecha de Inicio</label>
                <input   type="date" id="fetInic"  class="form-control">
            </div>
            <div  class="col-sm-1"></div>
            <div  class="col-sm-4">
                    <label>Fecha de Fin</label>
                    <input   type="date" id="fetfin"  class="form-control">
            </div>
            <div  class="col-sm-3">
                <button  type="button" id="btnGet" class="btn btn-info"  >Generar  80/20</button> 
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-sm-4">
                <button  type="button"  class="btn  btn-success" id="btnUP" >Agregar  Desviaciones   <span class="glyphicon glyphicon-plus"></span></button>
            </div> 
        </div>
           
    </div>
    <br></br>
    
    
    <br></br>
    <div id="conTB" class ="col-lg-12"  >
        <table class="table table-hover">
            <thead>
            <th>Nombre Agente</th> 
            <th>Clave Producto</th>
            <th>Venta Real</th>
            <th>Proyeccion</th>
            <th>Precio Produ</th>
            <TH>Variacion</th>
            <th>VarPu</th>
            <th>Por %</th>
            </thead>
            <tbody id="boTable">
                
            </tbody>
            <tbody id="boTable02">
                
            </tbody>
        </table>     
    </div>
</div>

 <!---Modal  Mensages----->    
     <div  class="modal fade" id="ModalMNs" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
               <button id="btnCloseM" type="button" class="close_coment close " data-dismiss="modal">&times;</button>
            <div class="modal-header"  id="menModal">
               
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    
                    <div id="CONTMOD" class="row" class="well">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4" id="contMe"> </div>
                        <div class="col-sm-4"  ></div>
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
          </div>

        </div>
      </div>
  <!-------------------> 

<?php require_once 'foot.php';?>