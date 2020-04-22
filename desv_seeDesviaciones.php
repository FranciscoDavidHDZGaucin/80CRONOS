<?php
////***desv_seeDesviaciones.php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : desv_seeDesviaciones.php
 	Fecha  Creacion : 29/07/2017
	Descripcion  : 
 *              Escrip  Diseñado  para Mostrar  las  Desviaciones    para   cada unos de los   agentes  
 *      Modificaion  : 
 *             
 * 
  */

////**Inicio De Session 
session_start();
///****Cabecera Cronos 
require_once('header.php');
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***Conexion Mysql  
require_once('Connections/conecta1.php');
////***Conexion   Sap 
require_once('conexion_sap/sap.php');
///***Seleccion de la Bd 
 mssql_select_db("AGROVERSA"); 
/****
 $EstatusQery  =  "Todo  Bien" ; 

$MesEnCurso =  date("m");
$YearCurso =  date("Y");

if($MesEnCurso ==1)
{
$MesMenos=12;    
}else{
$MesMenos  = $MesEnCurso -2 ; 
} 
$MesAnterior =  $YearCurso."-".$MesMenos."-1"; */
/***Codigo  desabilitado  por Modificacion  27/07/2017*************************************************************************************************/
////*** Obtenemos  la Informacion en  formato JSON  

 $oBJfEHCAS = json_decode(filter_input(INPUT_POST,'FechbS'));
 
 $esplaneador = filter_input(INPUT_GET,'TyPg');
 ///***Se Identifica como planeador 
 if( $esplaneador ==3)
 {
        ///***Obtenemos  el  Encabezado 
  $strCabe  = sprintf("SELECT fech_fin ,fech_Ini FROM pedidos.desv_encabeza_desviacion  where  cve_desvi =%s ",
                     GetSQLValueString($oBJfEHCAS->cveDes, "int"));
  $qeryEncabe = mysqli_query($conecta1, $strCabe);
  
  $fethEnca = mysqli_fetch_array($qeryEncabe);
 ///************************************
 
$strGetDes = sprintf("select * from desv_Vista_MAIN_Desviaciones  where cve_agente =%s and  fech_Ini =%s and fech_fin=%s ",// and desvConts=1",
 GetSQLValueString($oBJfEHCAS->cvagente, "int"), GetSQLValueString($oBJfEHCAS->feIni, "date"), GetSQLValueString($oBJfEHCAS->feFin, "date"));
   
 }else{
   ///***Obtenemos  el  Encabezado 
  $strCabe  = sprintf("SELECT fech_fin ,fech_Ini FROM pedidos.desv_encabeza_desviacion  where  cve_desvi =%s ",
                     GetSQLValueString($oBJfEHCAS->cveDes, "int"));
  $qeryEncabe = mysqli_query($conecta1, $strCabe);
  
  $fethEnca = mysqli_fetch_array($qeryEncabe);
 ///************************************
 
$strGetDes = sprintf("select * from desv_Vista_MAIN_Desviaciones  where cve_agente =%s and  fech_Ini =%s and fech_fin=%s ",// and desvConts=1",
 GetSQLValueString($_SESSION['usuario_agente'], "int"), GetSQLValueString($oBJfEHCAS->feIni, "date"), GetSQLValueString($oBJfEHCAS->feFin, "date"));
   
     
 }    

$qery = mysqli_query($conecta1,$strGetDes );


?> 
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>

<style>

.trNegativo{
    background: rgba(255, 0, 0, 0.46);
}
.trPOSITIVO{
    background: rgb(51, 164, 113);
}
thead, tbody { display: block; }

tbody {
    height: 1000px;       /* Just for the demo          */
    overflow-y: auto;    /* Trigger vertical scroll    */
   
}
th,td{
        max-width: 10.5vw;
    min-width: 10.5vw;
}

</style>

<div  class="container">
  
    <div id="Variblesconten" class="col-lg-12  col-xs-12">
       
        <div  class="col-lg-6 col-xs-6"><h3>Desviaciones  Respecto:</h3></div>
        <div  class="contFecht col-lg-6  col-xs-6">
            <div  class="col-xs-6">
               <strong>Fecha  Inicial :</strong><p><strong id="fechINC"><?php echo $fethEnca['fech_Ini']; ?></strong></p>  
            </div>
            <div  class="col-xs-6">
                   <strong>Fecha Final :</strong><p><strong id="fechFIN"><?php echo $fethEnca['fech_fin']; ?></strong></p>
            </div>
        </div>
     
        
    </div>
    
    
    
    <div  class="col-lg-12  col-xs-12">
        <table id="PrinTable" class="table  table-hover">
            <thead>
                <tr>
                    <th>Clave Prod</th>
                    <th>Nombre</th>
                    <th>Demanda</th>
                    <th>Venta Real</th>
                    <th>Variacion</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="tbdes">
                <?php 
                    while($row = mysqli_fetch_array($qery)){
                        
                        ////****Obtenemos  la  Demanda   select  Get_Proyeccion(150  ,'BIO2005', '2017-03-31') as Demanda
           $string_getDemanda  = sprintf("select  Get_Proyeccion(%s ,%s, %s) as demanda",
                                    GetSQLValueString($_SESSION['usuario_agente'], "int"), 
                                    GetSQLValueString($row['cve_prod'], "text"),
                                    GetSQLValueString($MesAnterior, "date"));
          $qeryGetDemanda=  mysqli_query($conecta1,$string_getDemanda);
           $fethDemanda =  mysqli_fetch_array($qeryGetDemanda);
        //////*********************************
           $string_prod= sprintf("SELECT ItemName FROM plataformaproductosl1 WHERE ItemCode=%s  ", GetSQLValueString($row['cve_prod'], "text"));

                                    $qernomprod = mssql_query($string_prod);
                                    $fetchNoProd =mssql_fetch_array($qernomprod);

             IF($fethDemanda['demanda']==NULL){  $demanda = 0 ;}else {$demanda =$fethDemanda['demanda'];} 
                        
                        
                                                        /////***Agregamos la  Variacion  con su Informacion Correspondiente 
                                if($row['variacion'] > 0)
                                {
                                    ///Variacion Postiva 
                                    $trTable = "<tr class='trPOSITIVO' ><td>".$row['cve_prod'].
                                                "</td><td>".$fetchNoProd['ItemName']."</td><td>".$demanda.
                                                "</td><td>".$row['VentReal']."</td><td>".$row['variacion'].
                                                "</td><td class='TDOPC1'>".$row['ResNive1']."</td>".
                                                "<td class='TDOPC2' id='OPC1'>".$row['ResNivel_2'].
                                                "</td><td class='TDOPC3' id='OPC2'>".$row['ResNivel3']."</td>".
                                                "<td  class='TDOPC3' id='OPC3'>".$row['ResNivel4']."</td></tr>";
                                }else{
                                    $converVariacion  = $row['variacion']*-1 ;
                                    ////**Variacion Negativa 
                                   $trTable    = "<tr class='trNegativo'><td>".$row['cve_prod'].
                                                "</td><td>".$fetchNoProd['ItemName']."</td><td>".$demanda.
                                                "</td><td>".$row['VentReal']."</td><td>".$row['variacion'].
                                                "</td><td class='TDOPC1'>".$row['ResNive1']."</td>".
                                                "<td class='TDOPC2' id='OPC1'>".$row['ResNivel_2'].
                                                "</td><td class='TDOPC3' id='OPC2'>".$row['ResNivel3']."</td>".
                                                "<td  class='TDOPC3' id='OPC3'>".$row['ResNivel4']."</td></tr>";
                                }
                                ECHO $trTable ;
                    }
                ?> 
            </tbody>
            
        </table>
        
        
    </div>
 
    
    
    
     <!---Modal Modificar Elementos   ----->    
     <div  class="modal fade" id="menSaveALL" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
                   <button type="button" id=""  class="close_coment close" data-dismiss="modal">&times;</button>
                   <h4>Atencion !!! </h4>
                   <P><strong id="titlEmeN"></strong></p>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div  class="row" class="well">
                        
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-4" ><button disabled  type="button" id="updateAcept"  data-dismiss="modal" class="btn btn-info"> Aceptar  <span class="glyphicon glyphicon-pencil"></span></button></div>
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-4" ><button   type="button" id="updateCancel"  data-dismiss="modal" class="btn btn-danger"> Cancelar  <span class="glyphicon glyphicon-remove"></span></button></div>
                            <div class="col-sm-1" ></div>
                        
                    </div>
                </div>
             
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