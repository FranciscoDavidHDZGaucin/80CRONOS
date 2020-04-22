<?php
////***coti_estus_coti_gerente.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_estus_coti_gerente.php  
 	Fecha  Creacion :03/08/2017
	Descripcion  : 
 *             Escrip Diseñado  para Revisar  Estatus de  Cotizaciones Para  Los aGENTES 
 * 
 *      Modificacion  
 *                  20/07/2017   Se Agrega la  Variable  Entiendase  que  la  Varible  TypePg  
 *                               TypePg   en  Estado  =>  1  =>La  Pagina   Se ejecutara  con opciones de Update   y Estatus
 *                                TypePg  en  Estado  =>  2  =>La  Pagina   Se ejecutara  con opciones de Vista  Nada  de  Modificaciones    
 * 
 *                      04/08/2017   ///****Generamos  Variable  $BtnSee =>  Para  Determinar   el  tipo de  Visualizacion  de  Cotizacion
                           *                                Entiendase  $BtnSee => true => Modificar 
                           *                                Entiendase  $BtnSee => false => Solo Visor  
                           *                            
 *       
  */

////**Inicio De Session 
	 session_start ();
   $MM_restrictGoTo = "index_inteligencia.php";
   if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
///****Cabecera Cronos
require_once('header_gerentes.php');
require_once('Connections/conecta1.php');
///*****        
require_once('formato_datos.php');
///**+Establecemos   Update  a  Estado  1  
$_SESSION['UbdatePub'] =1;
        
$TypPeg = filter_input(INPUT_GET, 'TypePg');          
///**Obtenemos el N# del  Agente 
$idagente = $_SESSION["usuario_rol"];

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
    margin-top: 11px;
    margin-left: 70px;
}
button.btn.btn-sucess {
    background-color: #33a471;
}
button#btnEst {
    margin-bottom: 18px;
   
}
tbody.disablebody {
    max-height: 200px;
}
.opcEstPENDIENTE {
    BACKGROUND: #f2f73b;
    BORDER-RADIUS: 67PX;
    MAX-HEIGHT: 46PX;
    TEXT-ALIGN: center;
}
.opcEstAUTO {
    BACKGROUND: #48c9b0;
    BORDER-RADIUS: 67PX;
    MAX-HEIGHT: 46PX;
    TEXT-ALIGN: center;
}
.opcEstRECHAZADO {
    BACKGROUND: #e74c3c;
    BORDER-RADIUS: 67PX;
    MAX-HEIGHT: 46PX;
    TEXT-ALIGN: center;
}
.opcEstMODIFICAR {
    BACKGROUND:#3498db;
    BORDER-RADIUS: 67PX;
    MAX-HEIGHT: 46PX;
    TEXT-ALIGN: center;
}

</style>
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="coti_scrip_cotiza/coti_Arreglomain.js"></script>
<script src="coti_scrip_cotiza/coti_ClassPrd.js"></script>
<script type="text/javascript" src="pub_scrip_publicidad/pub_func_publicidad.js"></script>

 <div  class="container">
     
     <ul class="nav nav-tabs">
         <li class="active"><a  href="#">Mis Cotizaciones</a></li>
         <li><a  href="coti_estus_coti_agente.php?TypePg=3">Cotizaciones Vendedores</a></li>
         
     </ul> 
     <?php 
        ///***Opcion para Update
        if($TypPeg==1){
      echo       '<div class="row"><h2>Estado  Solicitudes</h2></div>';
      echo      '<div class="col-sm-12">';
        ///***Cadena para Hacer
            // $string_get_info = "SELECT folio , cve_agente , fecha_sol, estatus_Ic ,estatus_Dc FROM pedidos.coti_encabeca_cotizacion  where    estatus_Ic = 0  and cve_agente =".$idagente  ;
        $string_get_info = "SELECT folio , cve_agente , fecha_sol, estatus_Ic ,estatus_Dc as DirecEst,coment_Ic FROM pedidos.coti_encabeca_cotizacion  where   cve_gerente =".$idagente  ;
        $qery_info = mysqli_query($conecta1, $string_get_info);
        }
        ///***Opcion para Historial
        if($TypPeg==2){
      
      echo       '<div class="row"><h2>Historial de Solicitudes</h2></div>';
      echo      '<div class="col-sm-12">';
        ///***Cadena para Hacer
        $string_get_info = "SELECT folio , cve_agente , fecha_sol, estatus_Ic ,estatus_Dc as DirecEst,coment_Ic FROM pedidos.coti_encabeca_cotizacion  where  estatus_Ic != 0 and cve_gerente =".$idagente  ;
        $qery_info = mysqli_query($conecta1, $string_get_info);
        }

        while($row = mysqli_fetch_array($qery_info)){
                 $DCOMER = $row['DirecEst'];
                    
        		 
        ?>
        <div class="brdD row">
                          <?php 
                          /*
                                Estatus  de  Autorizacion de  Inteligencia  Comercial 
                                0 => Pendiente
                           *    1=> Autorizada 
                                2 => Rechazada
                                3=> Regresar a Agente Cotizacion para  Modificar
                           * 
                           *    ///****Generamos  Variable  $BtnSee =>  Para  Determinar   el  tipo de  Visualizacion  de  Cotizacion
                           *                                Entiendase  $BtnSee => true => Modificar 
                           *                                Entiendase  $BtnSee => false => Solo Visor  
                           *                            */
                          $BtnSee =false ; /// Estado  Inicial
                          if($DCOMER==1){ 
                                        echo "<div class='opcEstAUTO'> "; 
                                        echo  "<strong>Estatus: "."Autorizada".$row['estatus_DC']."</strong>" ; 
                            }else{
                               if($row['estatus_Ic']==2 || $DCOMER==2)   
                                { 
                                      echo "<div class='opcEstRECHAZADO'> "; 
                                      echo "<strong>Estatus: "."Rechazada </strong>" ; 
                                }else {
                                    if($row['estatus_Ic']==3 ||$DCOMER==3)
                                     { 
                                                  echo "<div class='opcEstMODIFICAR'> "; 
                                                  echo  "<strong>Estatus: "."Modificar </strong>" ; 
                                                  $BtnSee =true;
                                                  
                                     }else {
                                          if($DCOMER==0 )
                                            {       
                                                   echo "<div class='opcEstPENDIENTE'> ";  
                                                   echo  "<strong>Estatus: Pendiente </strong>" ; 
                                            }
                                     } 
                                }
                            }
                            
                          ?>
                 </div>
             <div class="row infoS">
                 <div  class="col-sm-5"><h3>Folio:<?php echo $row['folio'];?></h3></div> <div class="col-sm-4"></div><div  class="col-sm-3"><strong class="posFech">Fecha Solicitud: <?php  $dt = new DateTime($row['fecha_sol']);  echo $dt->format("d/m/Y") ;?><strong></div>
             </div>
             <div class="row infoS">
               
                 <div class="col-sm-5">
                      <div class="col-xs-5">  
                          
                      </div>  <div class="col-xs-7">
                         
                      </div>   
                 </div>
                <div class="col-sm-5"> 
                     <strong>Comentarios Inteligencia Comercial:</strong>
                          <p><?php  echo  $row['coment_Ic']; ?></p>
                </div> 
                 
               <?php  
               ///***Validacion  Btn  Visor de Cotizaciones 
               if($BtnSee==false){?>
                 <form  action ="coti_addCotiza_gerente.php?<?php echo "TyPg=2";  ?> "  method="POST"> 
                        <div class="col-sm-1">
                            <input hidden type="int" name="FOLIO" value="<?php echo $row['folio'];?>" ><button type="submit" class="btn btn-info buscar"><span class="glyphicon glyphicon-zoom-in"></span></button>
                        </div>

                    </form>
               <?php }///Fin  Validacion  Btn   Update    
               ///***Validacion Opcion solo  para Modificacion 
                if($BtnSee==true){   ?> 
                 <form  action ="coti_addCotiza_gerente.php?<?php echo "TyPg=1";  ?>"  method="POST"> 
                   <!--  <div class="col-sm-1"> -->
                            <input hidden type="int" name="FOLIO" value="<?php echo $row['folio'];?>" ><button type="submit" class="btn btn-sucess buscar"><span class="glyphicon glyphicon-edit"></span></button>
                   <!--  </div>-->
                 </form>
                <!--Btn Para   ----> 
                <div class="col-sm-1" ></div>
               <?php  }?> 
                 
             </div>
         </div> 
         <br>
         <?php }?>      
         
     </div>
     <!---Modal  Mensages-->    
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
  <!--******************************-->
  


 </div>
<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 
