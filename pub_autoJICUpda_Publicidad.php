<?php
///***pub_autoJICUpda_Publicidad.php  
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_autoJICUpda_Publicidad.php  
 	Fecha  Creacion : 19/05/2017 
	Descripcion  : 
 *       Escrip  para Modificar  Solicitud  Por parte del  Jefe de Inteligencia  Comercial  
 *      
 *       Modificaciones : 
 *    
  */
////**Inicio De Session 
	session_start();
///****Cabecera Cronos
 require_once 'header_inteligencia.php';
require_once('Connections/conecta1.php');
///*****        
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos


 
 ////**** Cadena para  Obtener la cabe  a  modificar
    $strign_getFolio   = sprintf("SELECT *  FROM pedidos.pub_encabeza_publicidad  where  pub_folio =%s",
 GetSQLValueString($_POST['updateSol'], "int"));
  ///**+ Realizamos el  Qery 
   $qery_GetFolio   = mysqli_query($conecta1, $strign_getFolio);
   $fetch_GetFolio  = mysqli_fetch_array($qery_GetFolio);
   ///****Obtenemos Nombre  Agente 
   ///***Obtenemos el nombre del Agente 
$string_get_nomAg = sprintf("SELECT nom_empleado FROM pedidos.relacion_gerentes where   cve_age =%s",
 GetSQLValueString($fetch_GetFolio['cve_agente'], "int"));
$qery_get_Nom = mysqli_query($conecta1, $string_get_nomAg);
$fethNombre = mysqli_fetch_array($qery_get_Nom);
   
   
   //////****Varaibles  asigandas en  la  posicion de los Inputs  
   
    $FOLIOSOL = $fetch_GetFolio['pub_folio'];  
    $NUAg = $fetch_GetFolio['cve_agente'];    
    $AGE =$fethNombre['nom_empleado'];
    $FECH = $fetch_GetFolio['pub_fech_cap'];
    $ZO = $fetch_GetFolio['pub_zona'];
    $REGOn = $fetch_GetFolio['pub_region'];       
    $CLI = $fetch_GetFolio['cliente'];
    $provse = $fetch_GetFolio['pub_proveedor'];
    $COM_A = $fetch_GetFolio['pub_moti_sol'];
    ///****obtenemos Todos  los productos 
      $strGeTProdFolio  =sprintf("SELECT pub_cvepro,nom_producto ,cantidad_solici,precio_unitario,Descripcion_produc FROM pedidos.pub_detalle_publicidad  where pub_folio=%s", GetSQLValueString($FOLIOSOL, "int")  );
               $qeryProd  =  mysqli_query($conecta1,$strGeTProdFolio );

     

     $qerForja =  mysqli_query($conecta1,$strGeTProdFolio );

     $AregloObj =  array();
   
     while($value =mysqli_fetch_array($qerForja)) {
                  
          $objeto =array( 'cve_prod' => $value['pub_cvepro'],'cant_sol'=>$value['cantidad_solici'] ,'PU' =>$value['precio_unitario'] ,'pretotal'=>"" );

       array_push($AregloObj , $objeto );
     } 


?>
<style> 
    .CabInfo ,.TbCont,.CtFin {
    border-style: solid;
    border-radius: 12px;
    border-color: rgba(27, 165, 59, 0.09);
}
.panel-primary>.panel-heading {
    color: #fff;
    background-color: #25a453;
    border-color: #25a453;
}

*{
  margin:0;
}
header{
  height:170px;
  color:#FFF;
  font-size:20px;
  font-family:Sans-serif;
  background:#009688;
  padding-top:30px;
  padding-left:50px;
}
.cont_pub{
  width:90px;
  height:240px;
  position:absolute;
  right:0px;
  bottom:0px;
}
.botonF1{
  width:60px;
  height:60px;
  border-radius:100%;
  background:#33a471;
  right:0;
  bottom:0;
  /*position:absolute;*/
  margin-right:16px;
  margin-bottom:16px;
  border:none;
  outline:none;
  color:#FFF;
  font-size:36px;
  box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
  transition:.3s;  
}
span{
  transition:.5s;  
}
.botonF1:hover span{
  transform:rotate(360deg);
}
.botonF1:active{
  transform:scale(1.1);
}
/*.btn{
  width:40px;
  height:40px;
  border-radius:100%;
  border:none;
  color:#FFF;
  box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
  font-size:28px;
  outline:none;
  position:absolute;
  right:0;
  bottom:0;
  margin-right:26px;
  transform:scale(0);
}*/
.botonF2{
  background:#2196F3;
  margin-bottom:85px;
  transition:0.5s;
}
.botonF3{
  background:#673AB7;
  margin-bottom:130px;
  transition:0.7s;
}
.botonF4{
  background:#009688;
  margin-bottom:175px;
  transition:0.9s;
}
.botonF5{
  background:#FF5722;
  margin-bottom:220px;
  transition:0.99s;
}
.animacionVer{
  transform:scale(1);
}
#btnNew.fixed{
    position:fixed; 
    /*top:0;*/
}
#btnUpdate.fixed{
    position:fixed;
}
</style> 

<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
    ////***Folio  en Modificacion
     var  masterNf = <?php echo $FOLIOSOL; ?> ;
     ////****Areglo General  para los Productos 
      var AregloObj = <?php  echo  json_encode($AregloObj) ;?> ;
      ////**Subtotal
      var   subTotal =0;
      ///***Objeto Totales
      var  STotales;
    ///***Objeto  para Obtener los Totales 
       function   ObjSub(subTotl) 
       {   
           this.subTotl = subTotl.toFixed(2);
           var  SUB = parseFloat(this.subTotl);
           this.iva  =  (subTotl*.16).toFixed(2) ;
           var  IVA = parseFloat(this.iva);
           var  fintl =(SUB +IVA).toFixed(2) ;
           this.subTotl =subTotl.toFixed(2)
           this.Total =fintl ;
       }
       ///**************************************************************************
</script>
<script type="text/javascript">
   
  
    $(document).ready(function(){
        ////***Funcion para ajustar todos numeros 
         $(".TOTO").val(function (index, value ) {
                                     return value.replace(/\D/g, "")
                                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
         });
       ///**************************************************************************
       ///**Btn para realizar el  Update de la Nueva  Solicitud 
        $("#btnUpdate").click(function(event){
            $("#ModModi").modal("show");
          });
        ///**Btn para realizar el  recalculo de  la  Solicitud 
        $("#btnRecal").click(function(event){
            
             GetnNewValue()
               STotales = new  ObjSub(subTotal);
             console.log(STotales);
            $("#totalsub").val(STotales.subTotl);
            $("#IVA").val(STotales.iva);
            $("#fintotal").val(STotales.Total);
            $(".TOTO").val(function (index, value ) {
                 return value.replace(/\D/g, "")
                .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });

            

        });
        //***Funcion *****
        function GetnNewValue ()
        {   
            subTotal = 0;
            for( var i  in AregloObj )
            {
                
                ////**Generamos las  Clases  para  Obtener los  elementos 
                var  ClsCant = ".CanSol"+AregloObj[i].cve_prod;
                var  ClsPre =".PreSol"+AregloObj[i].cve_prod
                var  pretotal = ".PreTot"+AregloObj[i].cve_prod
                ////**Obtenemos los  nuevos  Values
                var   valCant  =  $(ClsCant).val();
                var   valPre  = $(ClsPre).val();
                ////**Asignamos  los nuevos valores  al  Objeto
                AregloObj[i].cant_sol = valCant;
                AregloObj[i].PU = valPre;
                AregloObj[i].pretotal = parseFloat((valCant* valPre).toFixed(2));
                ///***Mostramos en  la tabla
                console.log(AregloObj[i].pretotal);
                $(pretotal).val((valCant* valPre).toFixed(2));
                ///***Realizamos  la  Sumatoria Total
                subTotal += AregloObj[i].pretotal;
                 ////**Ajustamos el Numero 
                 $(pretotal).val(function (index, value ) {
                 return value.replace(/\D/g, "")
                .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
                });
                
            }
            ///***
            ///console.log(subTotal);
            
            
        }
        ///***Funcion para Obtener el  Sub Total  
        function  GetSubTotal()
        {
            
            
            
        }
      ///**************************************************************************
        $(".TOTO").on({
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
        ///**************************************************************************
	$(document).on("click",".btnEst",function(){
                ///***Mostramos Modal  
                    $('#ModEst').modal('show');  
                  nfolioVar =  $(this).attr('value');
	});
        /////**Btn  Send Estatus 
        $(document).on("click",".btnEstatus",function(){
            
                         console.log("Holi :T!!"+ $(this).attr('value')+" :"+nfolioVar) ;
                         
                          $.ajax({
                                    type:'POST',
                                    url: 'pub_scrip_publicidad/pub_addautoJIC.php',
                                    data:{"EstAu":$(this).attr('value'), "nFol":nfolioVar}, 
                                    success: function (datos) { 
                                                    console.log(datos);
                                                    window.location.href='http://192.168.101.17/sistemas/cronos/pub_autoJICpublicidad.php';
                                 }
                           });
        });
        ///**************************************
        $("#btnApliUpdate").click(function(){
                          
                            $.ajax({
                                    type:'POST',
                                    url: 'pub_scrip_publicidad/pub_updSolJIC.php',
                                    data:{"masNf":masterNf,"Ttls":JSON.stringify(STotales) ,"ProdUp": JSON.stringify( AregloObj)}, 
                                    success: function (datos) { 
                                        
                                        console.log(datos.Res001);
                                        
                                  }
                           });
            
        });
    });
</script>
<div  class="container">
    <?php 
      
    
    if($_SESSION['UbdatePub'] ==1){?> 
    
    <!--Boton de Guardar-->
    <button type="button" id="btnUpdate" class="fixed botonF1" value="">
        <span class="glyphicon glyphicon-edit" ></span>  
    </button>

    <?php }else {?>
    <!--Boton de Guardar-->
    <button  id="btnNew" class="fixed botonF1">
        <span class="glyphicon glyphicon-floppy-disk" ></span>  
    </button>
    <?php }?> 
    <!---Cabezera  Informacion----> 
    <div class="CabInfo col-lg-12 col-sm-12">
        <div class="row"><div class="col-sm-8"><h2>Solicitud Publicidad</h2></div><div class="col-sm-4"><h3>Folio: <?php echo $FOLIOSOL; ?></h3><input hidden   type="int"  id="folISol" value ="<?php echo $FOLIOSOL; ?>"> <input hidden type="int" id="NumAge" value ="<?php echo $NUAg ;?>">  </div></div>  
        <div class="row">
            <div class=" col-lg-6 col-sm-6">
                <div class ="form-group">
                    <h6> Agente
                    </h6>
                    <input disabled class="form-control input-lg" type="text" id="NomAge" value="<?php echo utf8_encode($AGE); ?>"> 
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class ="form-inline">
                    <h6>Fecha</h6>
                    <input disabled class="form-control" type="date" value="<?php echo  $FECH; ?>"  id="fechSol" ></input>
                </div>
            </div>
            <div class="col-lg-2 col-sm-2"></div> 
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class ="form-group">
                    <h6>Zona</h6>
                    <input  disabled class="form-control" type="text"value="<?php echo $ZO ; ?>" id="ZNa"> 
                </div>
            </div>
            <div class=" col-sm-6">
                <div class ="form-group">
                    <h6>Región o unidad</h6>
                    <input disabled class="form-control " type="text" id="reg" value="<?php echo  $REGOn; ?>"> 
                </div>
            </div>
             <div class="col-lg-2 col-sm-2"></div> 
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class ="form-group">
                    <h6>Cliente</h6>
                    <input disabled class="form-control" type="text" id="client" value="<?php  if($_SESSION['UbdatePub'] ==1){ echo  $CLI;} ?>" > 
                </div>
            </div>
            <div class=" col-sm-6">
                <div class ="form-group">
                    <h6>Proveedor</h6>
                    <input disabled  class="form-control" type="text" id="PROV"  value="<?php if($_SESSION['UbdatePub'] ==1){ echo  $provse; }?>" > 
                </div>
            </div>
        </div>
        <div  class="row">
            <h5 class="est_dar">Tiempo de entrega 15 días (No se modifica)</h5> 
        </div>
    </div>
    <br> 
    <!----Contenedor Tabla-->
    <div class="TbCont col-lg-12  col-sm-12"> 
        <table class="table  table-hover" >
            <thead>
                <th>Producto</th>
                <th>Descripcion</th>
                <th>Cantidad</th>
                <th>Pre Uni</th>
                <th>Pre Total</th>
                
           </thead>
           <tbody>
               <?php
                     
              

                  while($row = mysqli_fetch_array( $qeryProd ) ){
                    echo '<tr>'; 
                              ///**Obteneos Total de la  Solicitud  
                                $str_getTotalSol  =  sprintf("select  GetTotalPorProdu (%s,%s) as TotalSol",GetSQLValueString($FOLIOSOL, "int"),GetSQLValueString($row['pub_cvepro'], "text") );
                                $qeryGet=   mysqli_query($conecta1,$str_getTotalSol );
                                $getTotalSol = mysqli_fetch_array($qeryGet);

                              ///echo  '<td><input  type="int" value="'..'" class="btnCatsol form-control"></td';
                              ///**Nombre del  Producto 
                              echo  '<td>'.$row['nom_producto']."</td>";
                              ///**Descripcion del Producto 
                              echo  '<td>'.$row['Descripcion_produc']."</td>";
                              //**Cantidad  Solicitada 
                              echo  '<td><input  type="int" value="'.$row['cantidad_solici'].'" class="CanSol'.$row['pub_cvepro'].' form-control"></td>';
                              ///**Precio Solicitado 
                              echo  '<td><input step="any"  type="int" value="'.$row['precio_unitario'].'" class="PreSol'.$row['pub_cvepro'].'  form-control"></td>';
                              ////**Pre Total 
                              echo  '<td><input  step="any"  disabled type="int" value="'.$getTotalSol['TotalSol'].'" class="PreTot'.$row['pub_cvepro'].'  form-control"></td>';
                                
                    echo '</tr>';
                  }

                 $strGetTotales = "Select   Get_TotalAllProdd(".$FOLIOSOL.") as  SumaTotalProd ,   Total_WhitIVA_Prod (".$FOLIOSOL.") as  Iva  ,  GetTotalSolicitud (".$FOLIOSOL.") as SumaTotal  ";
                $qeryTotales  =  mysqli_query($conecta1, $strGetTotales );
                $ResTotal = mysqli_fetch_array( $qeryTotales );
              
                ///***
                  
               ?>
               <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th>Sub Total</th>
                  <th><input id="totalsub" disabled  type="num" class="TOTO form-control" value="<?php echo $ResTotal['SumaTotalProd'] ;?>"></th>
               </tr>
               <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th>IVA</th>
                  <th><input id="IVA" disabled  type="num" class="TOTO form-control" value="<?php echo $ResTotal['Iva']  ; ?>"></th>
               </tr>
               <tr>
                  <th><button    type="button" class="btnEst btn btn-success"  value="<?php echo $FOLIOSOL; ?>"   >Modificar Estatus</button></th>
                  <th></th>
                  <th><button type="button" class="btn btn-success" id="btnRecal"> Calcular</button></th>
                  <th>Total</th>
                  <th><input id="fintotal" disabled  type="num" class="TOTO form-control" value="<?php echo  $ResTotal['SumaTotal']; ?>"></th>
               </tr> 
           </tbody>
        </table>
    </div>
    <br>
    <br>
    <!---Comentarios   y  Btns ---->
    <div class="CtFin col-lg-12  col-sm-12 panel   panel-primary"> 
     
            <div class="row">Motivo de la solicitud / Compromiso de Venta</div>
            <div   class="panel-body"><textarea disabled id="motvSOL" class="form-control"><?php if($_SESSION['UbdatePub'] ==1){ echo  $COM_A;} ?></textarea></div>
        
    </div> 
    <!--------------------------------------------->
    
    <!---Modal  Mensages----->    
     <div  class="modal fade" id="ModalMNs" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
              
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div id="CONTMOD" class="row" class="well"> 
                        
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
  <!--************Dialog Estatus********************-->
     <div  class="modal fade" id="ModModi" role="dialog">
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
                        <div class="col-sm-10">
                            <strong> Está a Punto de Modificar  una Solicitud  de Publicidad Si quiere continuar presione Aceptar<strong>
                        </div>
                        <div class="col-sm-1"></div>            
                    </div>
                     <div  class="row" class="well">
                        
                            <div class="col-sm-4" ></div>
                            <div class="col-sm-1" > <button id="btnApliUpdate" type="button"  class="btn btn-info"  >Aceptar</button> </div>
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-1" ><button  type="button"  class="btnEstatus  btn btn-danger" data-dismiss="modal" >Cancelar</button></div>
        				 </div>
                    <div  class="row" class="well">
                        
                            <div class="col-sm-4" ></div>
                            <div class="col-sm-1" ><h5  ></h5> </div>
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-4" ></div>
                        
                    </div>
                </div>
            </div>
           
          </div>
		 </div>
	 </div>                  
          
    <!--***********************************************-->
    <!--************Dialog Estatus********************-->
     <div  class="modal fade" id="ModEst" role="dialog">
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
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4"><h4 id="nfMen"></h4></div>
                        <div class="col-sm-6"></div>
                        
                    </div>
                     <div  class="row" class="well">
                        
                            <div class="col-sm-4" ></div>
                            <div class="col-sm-1" > <button  type="button"  class="btnEstatus  btn btn-info" value="1" >Autorizar</button> </div>
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-1" ><button  type="button"  class="btnEstatus  btn btn-danger" value="2" >Rechazar</button></div>
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

            </div>
          </div>
		 </div>
	 </div>                  
          
    <!--***********************************************-->
</div>
<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 


