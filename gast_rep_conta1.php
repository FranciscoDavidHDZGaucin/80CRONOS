<?php


require_once('header_conta_gastos.php');
//require_once('Connections/conecta1.php');

  require_once('formato_datos.php');
   require_once('funciones.php');
 //mysqli_select_db($conecta1, $database_conecta1);
   require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
 //mssql_select_db("AGROVERSA");     

//CONSULTA PARA MOSTRAR los gastos capturados
$string_poliza=sprintf("SELECT * FROM poliza ORDER BY fecha DESC ");
 
$query_poliza=mysqli_query($conecta1, $string_poliza) or die (mysqli_error($conecta1));


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
  $(document).ready(function(){
       var  cveGasto = null;
       var  numFact02 = null;
            $(document).on("click",".deletGast",function(){
                
                var cve_gas =  $(this).attr('cveGas');
                var  numfact  = $(this).attr('numFac');
                   cveGasto= cve_gas   ;
                   numFact02=numfact;
                   $("#nfMenEs").html("N# Factura :"+numFact02); 
                   $("#btnNote").html("Estas A punto de Eliminar una  Factura !!!!");
                   $("#ModEst").modal("show");
               
                
            });
            $("#btnEstatus").click(function(){
                $.ajax({
                    type:'POST',
                    url: 'script_gastos/gat_delet_gasto.php',
                    data:{"CVE":cveGasto}, 
                    success: function (inserdb) {

                                            console.log(inserdb)              
                                     if(inserdb.Res001 ==  0)
                                     {
                                             $("#btnNote").html("Lo Sentimos Ocurrio  Un Error Intente mas tarde !!!!");                              
           
                                     }else{
                                          $("#btnNote").html("Exito");
                                          var  idremotr = "#trgst"+cveGasto; 
                                          $(idremotr).remove();
                                           $('#ModEst').modal('toggle');
                                          
                                     }   

     }});
               
            });





    });
    
</script> 
<div class="container">
        <div class="page-header">
          <h4>Reporte General de Gastos <?php //echo $instruccion; ?></h4>
        </div>
   
          <form name="forma1" method="POST" action="clientes.php">
              
     
                <div class="table-responsive">
                  <table  class="table table-responsive table-hover" id="dataTables-gastosconta1" >
                      <!--<thead style="position: fixed;  margin-top: -40px; background-color: white;">-->
                      <thead>
                          <tr>
                              <th width="10%">Factura</th>
                              
                              <th width="10%">Concepto</th>
                               <th width="10%">Fecha</th>
                              <th width="10%">Agente</th>
                             
                              <th>Subtotal</th> 
                              <th>Iva</th>
                              <th>Total</th>
                              <th>Pagado</th>
                              <th>Fecha Pago</th>
                              <th>Estatus</th>
                              <th>Modificar</th>
                              <th>Archivos</th>
                              <th> Eliminar</th>
                              
                          </tr>
                      </thead>
                      
                      <tbody>
                          <?php
                          while ($registro1= mysqli_fetch_array($query_poliza)) {
                           ?>   
                           
                              
                              <tr id="trgst<?php echo $registro1['id'];?>" >
                                  <td width="10%"><?php echo $registro1['factura']; ?></td>
                                  <td width="10%"><?php echo $registro1['nom_gto']; ?></td>
                                  <td width="10%"><?php echo $registro1['fecha'];  ?></td>
                                  <td width="10%"><?php

                                      $STRNOMBREbIEN  =sprintf("SELECT nom_age FROM pedidos.vista_poliza_agentes  where   agente =%s",
                                          GetSQLValueString($registro1["agente"], "int") );
                                            $query_nombreagente=mysqli_query($conecta1, $STRNOMBREbIEN) or die (mysqli_error($conecta1));
                                          $nombre_real =  mysqli_fetch_array($query_nombreagente);

                                   echo $nombre_real['nom_age']; 

                                   ?></td>
                                  <td width="10%"><?php echo $registro1['subtot']; ?></td>
                                  <td width="10%"><?php echo $registro1['iva']; ?></td>
                                  <td width="10%"><?php echo $registro1['total']; ?></td>
                                  <td width="10%"><?php echo $registro1['pago']; ?></td>
                                  <td width="10%"><?php echo $registro1['f_pago']; ?></td>
                                  
                                  
                                    
                                  <td><img src="<?php echo icono_estatus($registro1['vbo_gerente']); ?>"/></td>  
                              <td width="10%">
                                
                                   <?php 

                                      if($registro1['vbo_gerente'] ==1 && $registro1['vbo_gerente'] != null  ){ ?>

                                 <a  type='button'   href="gast_popUpdGasstoCont.php?CVEGAST=<?php echo $registro1['id']; ?>" target="_blank"  class='btnEstatusTB btn btn-danger'   onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><span class="glyphicon glyphicon-edit"></span>  </a>        
                                  
                                    <?php  }?>


                                </td>
                               <td width="5%">
                                <?php 

                                      if($registro1['nom_pdf'] != null){

                                          echo '<a href="CFD_PAGOS/'.$registro1['nom_pdf'].'" target="_blank"><image  width=24px  heigth=24px src="images/PDF.svg"></a>';  
                                      }
                                       
                                ?></td>
                               <td width="5%" >
                                   <button cveGas="<?php echo $registro1['id'];  ?>" numFac="<?php echo $registro1['factura'];  ?>" type="button"  class="deletGast btn  btn-danger">   <span  class="glyphicon glyphicon-trash"> </span> </button>
                               </td>
                                  
                                  
                          
                             </tr>  
                              
                              
                          <?php  } ?>
                         
                    
                      </tbody>    
                      
                  </table>
                  
              </div> 
          </form>
    <!--************Dialog Eliminar********************-->
     <div  class="modal fade" id="ModEst" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
                  <button type="button" class="close_coment close" data-dismiss="modal">&times;</button>
                <h5>  Eliminar  Gasto<h5>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div id="CONTMOD" class="row" class="well">
                       <div class="col-sm-1"></div>
                        <div class="col-sm-10"><h4 id="nfMenEs"></h4><p><strong  id="btnNote"></strong></p></div>
                        <div class="col-sm-1"></div>
                        
                        
                    </div>
                     <div  id="BtnOpcio"  class="row" class="well">
                          <div class="col-sm-1" ></div>
                            <div class="col-sm-1" ><button  type="button" id="btnEstatus" class="  btn btn-info" value="1" >Eliminar</button></div>
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-1" ><button  type="button"  class="btnEstatus  btn btn-danger" data-dismiss="modal">Cancelar</button></div>
                   </div>
                    
                </div>
            </div>
            <div class="modal-footer">

            </div>
          </div>
		 </div>
	 </div>                  
          
    <!--***********************************************-->
      </div><!-- /.container -->
      
 <?php require_once('foot.php');?>     