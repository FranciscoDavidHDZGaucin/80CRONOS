<?php
///***pub_gensol_agente.php  
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_gensol_agente.php  
 	Fecha  Creacion : 04/05/2017 
	Descripcion  : 
 *       Escrip  para Generar  la  Solicitud 
 *      
 *       Modificaciones : 
 *                  09/05/2017  Se  Modifico el  Escrip para  Tener  la  Opcion de Modificar  y  Agregar  Soliciud 
 * 
  */
////**Inicio De Session 
	session_start();
///****Cabecera Cronos
require_once('header.php');
require_once('Connections/conecta1.php');
///*****        
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***Obtenemos  los elementos  Seleccionados 
$Json_Informacion  = filter_input(INPUT_POST, 'SenInfo');
///****Convertios  el  Json en  Areglo 
////**** {"ArCveProd":CveProdCON ,"CanTVal":CanTVAL}
$JSONgEN  = json_decode($Json_Informacion,TRUE);
///***Obtenemos los   Dos  Areglos
//***Obtenemos el JSON de Articulos
$JSONArre_CveProd = json_encode($JSONgEN['ArCveProd'],true);
///***Convertimos  a  Arreglo 
$Arre_CveProd =json_decode($JSONArre_CveProd);
//***Obtenemos el JSON de Cantidad
$JSONArre_Cant = json_encode($JSONgEN['CanTVal'],true);
///***Convertimos  a  Arreglo 
$Arre_Cant =json_decode($JSONArre_Cant);                 
///echo  $Json_Informacion;
///$CveProd = json_decode($JSONgEN[0]);

if($_SESSION['UbdatePub'] ==1)
{   
 ////**** Cadena para  Obtener la cabe  a  modificar
    $strign_getFolio   = sprintf("SELECT *  FROM pedidos.pub_encabeza_publicidad  where  pub_folio =%s",
 GetSQLValueString($_SESSION['NFuPDATE'], "int"));
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
}else{
    ///***Obtenemos el nombre del Agente 
$string_get_nomAg = sprintf("SELECT nom_empleado FROM pedidos.relacion_gerentes where   cve_age =%s",
 GetSQLValueString($_SESSION["usuario_agente"], "int"));
$qery_get_Nom = mysqli_query($conecta1, $string_get_nomAg);
$fethNombre = mysqli_fetch_array($qery_get_Nom);
///****
$max_id = mysqli_query($conecta1, "SELECT MAX(id) AS id FROM  pub_encabeza_publicidad") ;
//**feth 
$fetch_Max_id = mysqli_fetch_array($max_id);
///***Generamos Folio 
   $FOLIOSOL =  $_SESSION["usuario_agente"].date('Y').$fetch_Max_id['id'];
    $NUAg = $_SESSION["usuario_agente"] ;
     $AGE = $fethNombre['nom_empleado'] ;
     $FECH = date('Y-m-d');
     $ZO =  $_SESSION["Zona"]; 
     $REGOn  =  $_SESSION["usuario_nombre"] ;
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
<script  type="text/javascript">
  var   ArCveProd = <?php echo json_encode($JSONgEN['ArCveProd'],true);?>;
  var   CanTVal  =  <?php echo json_encode($JSONgEN['CanTVal'],true);?> ;
  
</script> 
<script type="text/javascript" src="pub_scrip_publicidad/pub_func_publicidad.js"></script>
<script type="text/javascript">
   
  
    $(document).ready(function(){
         
        ///**Btn para generar la Nueva Solicitud
        $("#btnNew").click(function(event){
            
              ///***Validamos  EMTY 
              var  IsEM =IsEmptySol(); 
              if(IsEM.PRO == false && IsEM.CPR==false )
              {
                ///***Generamos Json 
                var  Parametros = {"ENCA":JSON.stringify(Get_Encabeza_Pub()) ,"DET": JSON.stringify(Get_Detalle_Pub()) } ; 
                 $.ajax({
                        type:'POST',
                        url: 'pub_scrip_publicidad/pub_AddSolicitud.php',
                        data: Parametros,
                        success: function(datos)
                        {
                          if(datos.Res001 ==1 && datos.Res002 ==1)  
                          {         
                              ///***    background-color: #62b9f5;
                             $('.modal-header').css("background-color","#62b9f5"); 
                                $('#CONTMOD').html("<h5>Exito</h5><br><h6>Se ha  Generado Correctamente la Solicitud</h6>");
                                $('#close_coment').prop("disable",true);
                                $('.modal-footer').html('<a type="button" href ="index.php"  class="returnSELEC close_coment btn btn-info" >Regresar</a>');
                                    
                          }else
                          { 
                              //***background-color: #ff313d;
                              $('.modal-header').css("background-color","#ff313d"); 
                              $('#CONTMOD').html("<h5>Error !!</h5><br><h6>Lo Sentimos Existen Algunos Problemas en Plataforma<br>NO SE GENERO LA SOLICITUD</h6>");
                                $('#close_coment').prop("disable",true);
                                $('.modal-footer').html('<a type="button" href ="index.php"  class="returnSELEC close_coment btn btn-danger" >Regresar</a>');
                                
                              
                          }

                            $('#ModalMNs').modal('show');
                        }

                    }); 
              }else {
                  $('.modal-header').css("background-color","#ff313d"); 
                  if(IsEM.PRO == true && IsEM.CPR==true)
                  {
                       $('#CONTMOD').html("<h5>Error !!!</h5><br><h6>Lo Sentimos No se Puede  Generar  la  Solicitud <br>No Existen  Productos y Existen Campos  Vacíos.</h6>");
                       $('#close_coment').prop("disable",true);
                       $('.modal-footer').html('<a type="button" href ="pup_selecprod_agente.php"  class="returnSELEC close_coment btn btn-danger" >Close</a>');
                       
                }else {
                        if(IsEM.PRO){ 
                        $('#CONTMOD').html("<h6>Error !!! <br> No Se Puede Generar Solicitud !!<br>No Existen  Productos  </h6>");}
                        
                        $('#close_coment').prop("disable",true);
                        $('.modal-footer').html('<a type="button" href ="pup_selecprod_agente.php"  class="returnSELEC close_coment btn btn-danger" >Close</a>');
                       
                        if(IsEM.CPR){
                            
                           $('#CONTMOD').html("<h6>Existen Campos Vacios</h6>");} 
                               $('.modal-footer').html('<button type="button" id="close_coment" class="close_coment btn btn-default" data-dismiss="modal">Close</button>')
                            $('.returnSELEC').prop("disable",true);
                       
                   }
                  $('#ModalMNs').modal('show');
              }
          
    

            

        });
        ///**Btn para realizar el  Update de la Nueva  Solicitud 
        $("#btnUpdate").click(function(event){
            
              ///***Validamos  EMTY 
              var  IsEM =IsEmptySol(); 
              if(IsEM.PRO == false && IsEM.CPR==false )
              {
                ///***Generamos Json 
                var  Parametros = {"ENCA":JSON.stringify(Get_Encabeza_Pub()) ,"DET": JSON.stringify(Get_Detalle_Pub()) } ; 
                 $.ajax({
                        type:'POST',
                        url: 'pub_scrip_publicidad/pub_UpdaSolicitud.php',
                        data: Parametros,
                        success: function(datos)
                        {
                          if(datos.Res001 ==1 && datos.Res002 ==1)  
                          {         
                              ///***    background-color: #62b9f5;
                             $('.modal-header').css("background-color","#62b9f5"); 
                                $('#CONTMOD').html("<h5>Exito</h5><br><h6>Se ha Actualizado  Correctamente la Solicitud</h6>");
                                $('#close_coment').prop("disable",true);
                                $('.modal-footer').html('<a type="button" href ="index.php"  class="returnSELEC close_coment btn btn-info" >Regresar</a>');
                                    
                          }else
                          { 
                              //***background-color: #ff313d;
                              $('.modal-header').css("background-color","#ff313d"); 
                              $('#CONTMOD').html("<h5>Error !!</h5><br><h6>Lo Sentimos Existen Algunos Problemas en Plataforma<br>NO SE ACTUALIZO LA SOLICITUD</h6>");
                                $('#close_coment').prop("disable",true);
                                $('.modal-footer').html('<a type="button" href ="index.php"  class="returnSELEC close_coment btn btn-danger" >Regresar</a>');
                                
                              
                          }

                            $('#ModalMNs').modal('show');
                        }

                    }); 
              }else {
                  $('.modal-header').css("background-color","#ff313d"); 
                  if(IsEM.PRO == true && IsEM.CPR==true)
                  {
                       $('#CONTMOD').html("<h5>Error !!!</h5><br><h6>Lo Sentimos No se Puede  Actualizar  la  Solicitud. <br>No Existen  Productos y Existen Campos  Vacíos.</h6>");
                       $('#close_coment').prop("disable",true);
                       $('.modal-footer').html('<a type="button" href ="pup_selecprod_agente.php"  class="returnSELEC close_coment btn btn-danger" >Close</a>');
                       
                }else {
                        if(IsEM.PRO){ 
                        $('#CONTMOD').html("<h6>Error !!! <br> No Se Puede Actualizar Solicitud !!<br>No Existen  Productos  </h6>");}
                        
                        $('#close_coment').prop("disable",true);
                        $('.modal-footer').html('<a type="button" href ="pup_selecprod_agente.php"  class="returnSELEC close_coment btn btn-danger" >Close</a>');
                       
                        if(IsEM.CPR){
                            
                           $('#CONTMOD').html("<h6>Existen Campos Vacios</h6>");} 
                               $('.modal-footer').html('<button type="button" id="close_coment" class="close_coment btn btn-default" data-dismiss="modal">Close</button>')
                            $('.returnSELEC').prop("disable",true);
                       
                   }
                  $('#ModalMNs').modal('show');
              }
          
    

            

        });
        
        
         ///*****Codfigo  para  la Posicion del  Btn 
        var elemento = $("btnNew");
            $(window).scroll(function() {
                if ( $(window).scrollTop() >= ( window.innerHeight - elemento.height() ) ) {
                    element.addClass("fixed");
                } else {
                    element.removeClass("fixed");
                }
            });
    });
</script>
<div  class="container">
    <?php 
       IF(isset($_POST['btnSAVE']))
       {
           echo   "Hola" ; 
       }
           
    
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
                    <input class="form-control" type="text" id="client" value="<?php  if($_SESSION['UbdatePub'] ==1){ echo  $CLI;} ?>" > 
                </div>
            </div>
            <div class=" col-sm-6">
                <div class ="form-group">
                    <h6>Proveedor</h6>
                    <input class="form-control" type="text" id="PROV"  value="<?php if($_SESSION['UbdatePub'] ==1){ echo  $provse; }?>" > 
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
                <th></th>
                
           </thead>
           <tbody>
               <?php  
                  for($i= 0 ; $i<count($Arre_Cant); $i++){
                    $string_get_prod = sprintf("SELECT articulo  FROM  pedidos.pub_catalogo_publicidad  where codig_prod =%s",
                    GetSQLValueString($Arre_CveProd[$i], "text"));
                    $qery_prod = mysqli_query($conecta1,$string_get_prod);   
                    $fetch = mysqli_fetch_array($qery_prod);
                    echo '<tr>';
                                echo  '<td><input hidden class="nomPr'.$Arre_CveProd[$i].'" type="text" value="'.$fetch['articulo'].'"  >'.$fetch['articulo'].' </td>';
                              if($_SESSION['UbdatePub'] ==1){ 
                                
                                    $strign_getComent = sprintf("select  Descripcion_produc from pedidos.pub_detalle_publicidad  where  pub_folio =%s   and  pub_cvepro =%s",
                                                        GetSQLValueString($FOLIOSOL, "int"), GetSQLValueString($Arre_CveProd[$i], "text"));
                                   $qery_getComent  = mysqli_query($conecta1, $strign_getComent);
                                   $fetc_getComent = mysqli_fetch_array($qery_getComent);
                                  echo  '<td><input class="CoMe'.$Arre_CveProd[$i].' form-control" type="text" value="'.$fetc_getComent['Descripcion_produc'].'"></td>';
                              }else {
                                echo  '<td><input class="CoMe'.$Arre_CveProd[$i].' form-control" type="text"></td>';
                                
                              }
                                echo   '<td>'.$Arre_Cant[$i].'</td>';
                    echo '</tr>';
                  }
               ?> 
           </tbody>
        </table>
    </div>
    <br>
    <br>
    <!---Comentarios   y  Btns ---->
    <div class="CtFin col-lg-12  col-sm-12 panel   panel-primary"> 
     
            <div class="CtFin panel-heading">Motivo de la solicitud / Compromiso de Venta</div>
            <div class="panel-body"><textarea id="motvSOL" class="form-control"><?php if($_SESSION['UbdatePub'] ==1){ echo  $COM_A;} ?></textarea></div>
        
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

</div>
<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 


