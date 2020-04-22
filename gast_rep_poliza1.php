<?php


require_once('header_conta_gastos.php');
//require_once('Connections/conecta1.php');

  require_once('formato_datos.php');
   require_once('funciones.php');
 //mysqli_select_db($conecta1, $database_conecta1);
   require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
      

//CONSULTA PARA MOSTRAR los gastos capturados
$string_agentes=sprintf("SELECT * FROM vista_poliza_agentes ORDER BY nom_age ASC ");
 
$query_agentes=mysqli_query($conecta1, $string_agentes) or die (mysqli_error($conecta1));



IF (isset($_POST['filtrar'])){
    
  /*  echo 'Fecha inicial=> '.$_REQUEST['fecha_ini'].'<br>';
    echo 'Fecha Final=> '.$_REQUEST['fecha_fin'].'<br>';
    echo 'Fecha SAP=> '.$_REQUEST['fecha3'].'<br>';
    echo 'Empleado=> '.$_REQUEST['empleado'].'<br>';
    */
   
    
    
    $fecha_ini= $_REQUEST['fecha_ini'];
    $fecha_sap= $_REQUEST['fecha3'];
   $fecha_fin= trim($_POST['fecha_fin']);
     $empleado=$_REQUEST['empleado'];
     $_SESSION['fecha_start']=$fecha_ini;
     $_SESSION['fecha_end']=$fecha_fin;







	if ($fecha_ini!=""){
		$op1="1";
	}else{
		$op1="0";
	}
		
	if ($empleado!=0){
		$op2="1";
	}else{
		$op2="0";
	}	
    	
	$todo=$op1.$op2;
	//echo $empleado . "<br>";
	echo $todo . "<br>";

	
	switch ($todo) {
		case '00':   //Todos
					$query=("select * from poliza where pago>0");
                                        $suma_iva=sprintf("select sum(iva_pago) as total_iva from poliza where pago>0 ",
				GetSQLValueString($fecha_ini,"date"));
				
				$suma_sub=sprintf("select sum(subtot_pago) as total_sub from poliza where pago>0 ",
				GetSQLValueString($fecha_ini,"date"));
				
				$suma_tot=sprintf("select sum(pago) as total_total from poliza where pago>0 ",
				GetSQLValueString($fecha_ini,"date"));    
                                            
                    
			break;
		case '10':   //Solo Fecha
				$query = sprintf("select * from poliza where pago>0 and  f_pago=%s order by agente desc", 
				GetSQLValueString($fecha_ini,"date"));
				
				
				$suma_iva=sprintf("select sum(iva_pago) as total_iva from poliza where pago>0 and  f_pago=%s",
				GetSQLValueString($fecha_ini,"date"));
				
				$suma_sub=sprintf("select sum(subtot_pago) as total_sub from poliza where pago>0 and  f_pago=%s",
				GetSQLValueString($fecha_ini,"date"));
				
				$suma_tot=sprintf("select sum(pago) as total_total from poliza where pago>0 and  f_pago=%s",
				GetSQLValueString($fecha_ini,"date"));
				
			break;
		case '01':   //Solo Empleado  Utilizavan vista_poliza_generar
				$query = sprintf("select *from poliza where pago>0 and agente=%s order by agente desc",
				GetSQLValueString($empleado,"int"));
				//$listado=mysqli_query($query,$conecta1) or die(mysql_error());
				$suma_iva=sprintf("select sum(iva_pago) as total_iva from poliza where pago>0 and  agente=%s",
				GetSQLValueString($empleado,"int"));
				
				$suma_sub=sprintf("select sum(subtot_pago) as total_sub from poliza where pago>0 and  agente=%s",
				GetSQLValueString($empleado,"int"));
				
				$suma_tot=sprintf("select sum(pago) as total_total from poliza where pago>0 and  agente=%s",
				GetSQLValueString($empleado,"int"));
			break;	
		case '11':   //Fecha y empleado
				$query = sprintf("select * from poliza where pago>0 and agente=%s and f_pago=%s order by agente desc", 
                                        GetSQLValueString($empleado,"int"), 
                                       GetSQLValueString($fecha_ini,"date"));
				
				$suma_iva=sprintf("select sum(iva_pago) as total_iva from vista_poliza_generar where pago>0 and agente=%s and f_pago=%s ",
                                        GetSQLValueString($empleado,"int"), 
                                      GetSQLValueString($fecha_ini,"date"));
				
				$suma_sub=sprintf("select sum(subtot_pago) as total_sub from vista_poliza_generar where pago>0 and agente=%s and f_pago=%s ",
                                        GetSQLValueString($empleado,"int"), 
                                       GetSQLValueString($fecha_ini,"date"));
				
				$suma_tot=sprintf("select sum(pago) as total_total from vista_poliza_generar where pago>0 and agente=%s and  f_pago=%s ",
                                    GetSQLValueString($empleado,"int"), 
                                   GetSQLValueString($fecha_ini,"date"));
			break;
	}
    
   
    
    $_SESSION['query_poliza_sap']=$query;
    $_SESSION['fechapago_poliza_sap']=$fecha_ini;
    $_SESSION['fecha_poliza_sap']=$fecha_sap;
    

    echo  $query;

    $query_poliza=mysqli_query($conecta1, $query) or die (mysqli_error($conecta1));
    /*  echo $_SESSION['query_poliza_sap'].'<br>';
     echo $_SESSION['fechapago_poliza_sap'].'<br>';
     echo $_SESSION['fecha_poliza_sap']; */
    
    ///$sql_consulta=mysqli_query($conecta1,$query) or die (mysql_error());
        $query_iva=mysqli_query($conecta1, $suma_iva) or die (mysql_error());
        $query_sub=mysqli_query($conecta1 ,$suma_sub) or die (mysql_error());
              $query_tot=mysqli_query($conecta1,$suma_tot) or die (mysql_error());
        
        $row_iva = mysqli_fetch_array($query_iva);
        $row_sub = mysqli_fetch_array($query_sub);
        $row_tot = mysqli_fetch_array($query_tot);






} 
///Correo a quienes no son vendedores
function icono_estatus($estatus){
      
      switch ($estatus) {
          case 0:   //Pendiente por autorizar
                $ruta="iconos/time.png";

              break;

          case 1: //Auorizado
                 $ruta="iconos/like.png";

          break;
          case 2:  //Rechazado
                $ruta="iconos/dislike.png";

              break;
	   
      }
      return $ruta;
      
  }

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?> 

<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="script_gastos/fechas_fail_other.js"></script>
<script type="text/javascript">
  
   var infomSen ={"date":null,"emple":0}; 
</script> 
<script type="text/javascript">
$(document).ready(function(){


$("#btnGenXcel").click(function(){

     ///***Validamos Que  la  fecha Req No sea  Null    validarFormatoFecha  
       if (validarFormatoFecha($('#fecha_ini').val())== false )
       {
          alert("Lo Sentimos la Fecha No Tiene El Formato Correcto dd/mm/año ");    
       }else{
          ///var cve_cliente =$("#empleado  option:selected").val() ; 
          infomSen.date = convertoTODB($('#fecha_ini').val());
          infomSen.emple = $("#empleado  option:selected").val() ; 
          
           $.ajax({
                type:'POST',
                url: 'script_gastos/',
                data: { "senbyfiltro" :infomSen}, 
                success: function (datos) { 
                                
                         
                         $("#OBJTS").attr("value",JSON.stringify(infomSen));
                           $("#OBJTS").text(JSON.stringify(infomSen));
                          $("#excfomr").submit();
                     }
                 });
          
          
       }   
      
       
     
     
     
});


});

</script>





<div class="container">
     <form id="excfomr" action ="scrips_logistientragas/"  method="POST"> 
          <textarea hidden  id="OBJTS" type ="text"  name="OBJTS" ></textarea>>
    </form>                                            
    
        <div class="page-header">
          <h4>Obtener Poliza para SAP</h4>
        </div>
         <br><br><br>
              <div class="row">  
      <form id="filtro" name="filtro" method="post" action="gast_ReporteAgenPago.php">      
                        <div class="  col-lg-12 col-sm-12  col-xs-12" >

                                        <div class="col-lg-2 col-sm-12  col-xs-12" >
                                                <strong>Fecha Pago Inicial</strong>
                                                 <input type="date"  class="form-control" value="" name="fecha1" title="Fecha Inicio Pago">
                                        </div>
                                        <div class="col-lg-2 col-sm-12  col-xs-12" >
                                                <strong>Fecha Pago Final</strong>
                                                 <input type="date"  class="form-control"  value=""   name="fecha2" title="Fecha Fin Pago">
                                        </div>
                                        <div class="col-lg-4 col-sm-12 col-xs-12" >
                                                <strong>Seleccione  Empleados</strong>
                                                <select name="empleado" class="form-control">
                                                   <option value="0">Empleados Todos</option>  
                                                   <?php
                                                    while ($row = mysqli_fetch_array($query_agentes)) {
                                                        if ($row['agente'] == $_REQUEST['empleado']) {

                                                            echo '<option selected value="' . $row['agente'] . '">' . $row['agente'] . '-' . utf8_encode($row['nom_age']) . '</option>';
                                                        } else {
                                                            echo '<option value="' . $row['agente'] . '">' . $row['agente'] . '-' . utf8_encode($row['nom_age']) . '</option>';
                                                        }
                                                    }
                                                  ?>
                                              </select>
                                         </div>
                        </div>
                            <br><br><br>
                        <div class="row"> 
                        <input type="submit" class="btn btn-info" name="totalxagente" id="totalxagente"  value="Total x Agente" />
                        </div>
                        <?php   
                            if (isset($_REQUEST['totalxagente'])){

                             echo   $_SESSION['fecha_start']."<br>";
                            echo	 $_SESSION['fecha_end']."<br>";
                            echo 	 $_SESSION['empleado']."<br>"; 

                                ?>     
                        <a href="gast_generadorXaGente.php" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><img src="images/file_pdf.png" title="Imprimir" /></a></td>        





                        <?php } ?>  
          </form> 
              </div>
        <br><br><br><br><br><br>
       <form name="forma1" method="POST" action="gast_rep_poliza1.php">
              
              <div class="  col-lg-12 col-sm-12  col-xs-12" >
                            
                    <div class="col-lg-2 col-sm-12  col-xs-12" >
                            <strong>Fecha Pago</strong>
                             <input type="date"  class="form-control" value="" name="fecha_ini" id="fecha_ini" title="Fecha Inicio Pago" >
                    </div>
                    
                    <div class="col-lg-4 col-sm-12 col-xs-12" >
                            <strong>Seleccione  Empleados</strong>
                            <select name="empleado" id="empleado" class="form-control">
                               <option value="0">Empleados Todos</option>  
                               <?php
                                while ($row = mysqli_fetch_array($query_agentes)) {
                                    if ($row['agente'] == $_REQUEST['empleado']) {

                                        echo '<option selected value="' . $row['agente'] . '">' . $row['agente'] . '-' . utf8_encode($row['nom_age']) . '</option>';
                                    } else {
                                        echo '<option value="' . $row['agente'] . '">' . $row['agente'] . '-' . utf8_encode($row['nom_age']) . '</option>';
                                    }
                                }
                              ?>
                          </select>
                     </div>
                   <div class="  col-lg-6 col-sm-12  col-xs-12" >
                       <div class="  col-lg-12 col-sm-12  col-xs-12" >
                            <strong  align="left">Subtotal: <?php echo number_format($row_sub['total_sub'], 2, '.', ','); ?></strong>
                       </div>
                       <div class="  col-lg-12 col-sm-12  col-xs-12" >
                              <strong align="left">IVA: <?php echo number_format($row_iva['total_iva'], 2, '.', ','); ?></strong>
                       </div>
                       <div class="  col-lg-12 col-sm-12  col-xs-12" >
                           <strong align="left">Total: <?php echo number_format($row_tot['total_total'], 2, '.', ','); ?> </strong>   
                       </div>
                   </div>
                 </div>
                 
                 </div>


              </div>
              <br>
              <div class="row">
                  <div class="  col-lg-4 col-sm-4  col-xs-12" >
                       <input type="submit"  class="btn btn-info  btn-lg" name="filtrar" value="Generar Poliza">  
                  </div>
                <div class="  col-lg-4 col-sm-4  col-xs-12" >
                        
                    <button  title="Genera Excel" type="button"  class="btn btn-success" id="btnGenXcel" >
                         <img src="images/excel.ico"/> 
                    </button>

                </div>
                <div class="  col-lg-4 col-sm-4  col-xs-12" ></div>

              </div>
              
             <table  class="table table-responsive table-hover"  >
                      <!--<thead style="position: fixed;  margin-top: -40px; background-color: white;">-->
                      <thead>
                          <tr>
                             
                             
                              <th>Concepto</th>
                              <th>Fecha_Pago</th>
                              <th>Subtotal</th> 
                              <th>Iva</th>
                              <th>Total</th>
                              <th>Empleado</th>
                              
                              
                              
                          </tr>
                      </thead>
                      
                      <tbody>
                          <?php
                          while ($registro1= mysqli_fetch_array($query_poliza)) {
                           ?>   
                             <tr>
                                   
                                  <td ><?php echo $registro1['nom_gto']; ?></td>
                                  <td ><?php echo $registro1['f_pago'];  ?></td>
                                  <td><?php echo $registro1['subtot']; ?></td>
                                  <td><?php echo $registro1['iva_pago']; ?></td>
                                  <td><?php echo $registro1['pago']; ?></td>
                                  <td><?php echo $registro1['nom_age']; ?></td>
                               
                             </tr>     
                          
                          
                          <?php  
                          
                          }
                          ?>
                    
                      </tbody>    
                      
                  </table>
                  
            
          </form>
      </div><!-- /.container -->
      
 <?php require_once('foot.php');?>     