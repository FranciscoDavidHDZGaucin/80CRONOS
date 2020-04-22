<?php
/////***desv_estAgeDesvi.php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : desv_estAgeDesvi.php
 	Fecha  Creacion :27/07/2017
	Descripcion  : 
 *             Escrip Diseñado  para Revisar  Estatus de  Desviaciones Agentes
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
require_once('header.php');
require_once('Connections/conecta1.php');
///*****        
require_once('formato_datos.php');

        
$TypPeg = filter_input(INPUT_GET, 'TypePg');          
///**Obtenemos el N# del  Agente 
$idagente = $_SESSION["usuario_agente"];

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
     
        <?php 
        ///***Opcion para Update
        if($TypPeg==1){
      echo       '<div class="row"><h2>Estado  Desviaciones </h2></div>';
      echo      '<div class="col-sm-12">';
        ///***Cadena para Hacer
            // $string_get_info = "SELECT folio , cve_agente , fecha_sol, estatus_Ic ,estatus_Dc FROM pedidos.coti_encabeca_cotizacion  where    estatus_Ic = 0  and cve_agente =".$idagente  ;
        $string_get_info = "SELECT cve_desvi,fech_Ini,fech_fin,estas_ans FROM pedidos.desv_encabeza_desviacion  where  estas_ans=0  and  cve_agente =".$idagente  ;
        $qery_info = mysqli_query($conecta1, $string_get_info);
        }
        ///***Opcion para Historial
        if($TypPeg==2){
      
      echo       '<div class="row"><h2>Historial de Desviaciones</h2></div>';
      echo      '<div class="col-sm-12">';
        ///***Cadena para Hacer
        $string_get_info = "SELECT cve_desvi,fech_Ini,fech_fin,estas_ans FROM pedidos.desv_encabeza_desviacion  where  estas_ans != 0 and cve_agente =".$idagente  ;
        $qery_info = mysqli_query($conecta1, $string_get_info);
        }



        while($row = mysqli_fetch_array($qery_info)){

        		  ////***Buscamos Nombre  Agente. 
        			$string_getNomAge =  sprintf("select nom_empleado from  pedidos.relacion_gerentes where cve_age = %s", 
        												GetSQLValueString($row['cve_agente'], "int"));
        			$qeryGetNomAge =   mysqli_query($conecta1,$string_getNomAge);
        			$nomfethcAge = mysqli_fetch_array($qeryGetNomAge);
       			
        ?>
         <div class="brdD row">
             
                  <?php ///Fin Condicion para Modificar
                                     //***Inicio Funcion de Historial 
                          if($TypPeg==2){
                              if($row['estas_ans']==1)   { 
                                        echo "<div class='opcEstAUTO'> ";
                                        echo  "<strong>Estatus: Contestado </strong>";
                              }
                                       
                          }  
                          
                          ?> 
                          <?php 
                          /*
                                Estatus  de  Autorizacion de  Inteligencia  Comercial 
                                0 => Pendiente
                           
                           *                            */
                                    if($row['estas_ans']==0)   { echo "<div class='opcEstPENDIENTE'> ";  echo  "<strong>Estatus: "."Pendiente"."</strong>" ; }
                           
                          
                          ?>
                 </div> 
             <div class="row infoS">
                 <div  class="col-sm-5"><h3>Clave Desviacion:<?php echo $row['cve_desvi'];?></h3></div><div  class="col-sm-3"><strong class="posFech">Fecha Inicio Evaluacion: <?php  $dt = new DateTime($row['fech_Ini']);  echo $dt->format("d/m/Y") ;?><strong></div>
                               <div class="col-sm-1"></div><div  class="col-sm-3"><strong class="posFech">Fecha Fin Evaluacion: <?php  $dt = new DateTime($row['fech_fin']);  echo $dt->format("d/m/Y") ;?><strong></div>
                                                
                             
             </div>
             <div class="row infoS">
               
                 <div class="col-sm-5">
                      <div class="col-xs-5">  
                          
                      </div>  <div class="col-xs-7">
                         
                      </div>   
                 </div>
                <div class="col-sm-5"> 
              
                </div> 
                 
               <?php  
               ///***Validacion  Btn  Visor de Cotizaciones 
               if($TypPeg==2){?>
                 <form  action ="desv_seeDesviaciones.php?<?php echo "TyPg=2";  ?> "  method="POST"> 
                        <div class="col-sm-1">
                            <input hidden type="text" name="FechbS" value='<?php echo json_encode(array("cveDes"=>$row['cve_desvi'],"feIni"=>$row['fech_Ini'],"feFin"=>$row['fech_fin']));?>' ><button type="submit" class="btn btn-info buscar"><span class="glyphicon glyphicon-zoom-in"></span></button>
                        </div>

                    </form>
               <?php }///Fin  Validacion  Btn   Update    
               ///***Validacion Opcion solo  para Modificacion 
                if($TypPeg==1){   ?> 
                 <form  action ="desv_agente.php?<?php echo "TyPg=".$TypPeg;  ?>"  method="POST"> 
                   <!--  <div class="col-sm-1"> -->
                   <input hidden type="text" name="FechbS" value='<?php echo json_encode(array("cveDes"=>$row['cve_desvi'],feIni=>$row['fech_Ini'],"feFin"=>$row['fech_fin']));?>' ><button type="submit" class="btn btn-sucess buscar"><span class="glyphicon glyphicon-edit"></span></button>
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