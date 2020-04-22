<?php

////****adi_show_tb_adicionales_planeador
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_show_tb_adicionales_planeador.php
 	Fecha  Creacion : 24/11/2016 
	Descripcion  : 
                Script para obtener  la  tabla del  planeador
	Modificado  Fecha  : 
*/
///***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos

///***Validamos Si obtener  la  tabla  completa  o por   usuario
$id_adicional = filter_input(INPUT_POST, 'IDEN');
if($id_adicional != 0  )
{
    $cadena_for_qery  = sprintf("SELECT * FROM adicionales where id=%s && auto = 0 ",
        GetSQLValueString($id_adicional, "int"));
}else{

$cadena_for_qery  = "SELECT * FROM pedidos.adicionales  where est_entrega != 1  ORDER  BY fecha_rq desc";
}

//$ejec_qery_get_tb = mysqli_query($conecata1, $cadena_for_qery);
$obj_mysql =  new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 
 
$ejec_qery_get_tb = $obj_mysql->query($cadena_for_qery);


///class="table-responsive"

echo  '<div class="" >';
echo '<table class="tabla_contenedora table  table-striped " id="tbpla" >';
    echo  '<thead>';
               echo '<tr>';
                     echo  '<th>ID</th>';
                     echo  '<th>Almacen</th>'; 
                    echo  '<th>Nombre</th>';
                     echo  '<th>Nombre Producto</th>';
                     echo  '<th>Venta</th>';
                     echo  '<th>Fecha Soli</th>';
                     echo  '<th>Fecha Req</th>';
                     echo  '<th class="text-center">Precio Soli P/Venta</th>';
                     echo  '<th class="text-center">Adicional</th>';
                     echo  '<th>Status Adicional</th>';
                     echo  '<th>Demanda</th>';
                     echo '<th>Inventario</th>';
                     echo  '<th>Venta Total de RTES Bodega</th>';
                     echo  '<th>Proyecciones  Totales por  Bodega</th>';
                     echo  '<th>Venta Cierre Mes</th>';
                     echo  '<th>Comentarios</th>';
                     echo  '<th>Estatus de Entrega</th>';
                     echo  '<th>Fe Compromiso Entrega</th>';
                     echo  '<th>Fe Real de Entrega</th>';
                  
                    
                     
                     
                      
               echo '</tr>';
    echo  '</thead>';
    echo  '<tbody>';
      while($row = mysqli_fetch_array($ejec_qery_get_tb))
       {
         /* $date = new DateTime('2000-01-01');
         echo $date->format('Y-m-d H:i:s');
          */
           echo  '<tr>';
                     echo  '<th>'.$row['id'].'</th>';
                     echo  '<th>'.$row['almacen'].'</th>'; 
                     echo  '<th>'.$row['nombre_usu'].'</th>';
                     echo  '<th>#'.$row['codigo_pro']." ".$row['nom_pro'].'</th>';
                     echo  '<th>'.$row['venta'].'</th>' ;
                     ///****Fecha  Solicitud 
                     $date_fsol = new DateTime($row['fecha_sol']);
                     echo  '<th class ="text-success" >'.$date_fsol->format('d/m/Y').'</th>';
                     ////****Fecha 
                     $date_rq =  new DateTime($row['fecha_rq']);
                     echo  '<th class="text-info">'.$date_rq->format('d/m/Y').'</th>';
                     echo  '<th class="text-center" >'.$row['precio_sol_pv'].'</th>';
                     echo  '<th class="text-center" >'.$row['cant_req'].'</th>';
                     ///*****Cambio Autorizacion
                     if ($row['auto']==0)
                     {
                        echo  '<th class="text-danger" >Pendiente</th>';  
                     }else{
                          if ($row['auto']==2)
                          {
                              echo  '<th class="text-danger" >Rechazado</th>';
                          }else{
                                echo  '<th class="text-info" >Autorizado</th>'; 
                          }
                            
                     
                     }
                     ////*******************************
                     echo  '<th>'.$row['proyeccion'].'</th>';
                     ///***Generamos  el  Btn  para Eliminara '<input id="elem_adis" hidden value='.$row['id'].
                     echo  '<th>'.$row['inventario'].'</th>';
                     ///***Venta Total de RTES Bodega
                     echo  '<th>'.$row['ventotal_por_bodega'].'</th>';
                     ///***Proyecciones  Totales por  Bodesga
                     echo  '<th>'.$row['proyeccion_total_bode'].'</th>';
                    ////Venta Cierre Mes
                     echo  '<th>'.$row['vent_cierre_mes'].'</th>';
                    ///****Comentarios
                     echo  '<th><button  type="button" class="btn_comentarios btn btn-info"  I_ADI="'.$row['id'].'"   ><span class="glyphicon glyphicon-edit"></span> </button></th>';
                    ////****Status Entrega
                       if ($row['est_entrega']==0)
                     {
                        echo  '<th class="text-danger"><button  type="button" class="btn_auto_Entrega btn btn-info" I_ADI="'.$row['id'].'"><span class="glyphicon glyphicon-check"></span></button><BR>Pendiente</th>';  
                     }else{
                          if ($row['est_entrega']==2)
                          {
                              echo  '<th class="text-danger"> <button  type="button" class="btn_auto_Entrega btn btn-info" I_ADI="'.$row['id'].'"><span class="glyphicon glyphicon-check"></span></button><BR>En Transito</th>';
                          }else{
                                echo  '<th class="text-info"><button  type="button" class="btn_auto_Entrega btn btn-info" I_ADI="'.$row['id'].'"><span class="glyphicon glyphicon-check"></span></button><BR>Entregado</th>'; 
                          }
                            
                     
                     }
                     //// echo  '<th><button  type="button" class="btn_auto_Entrega btn btn-info" I_ADI="'.$row['id'].'"><span class="glyphicon glyphicon-check"></span></button></th>';
                   ////***** Fecha  Compromiso   
                      echo  '<th>';
                      
                      echo '<div class="input-group">';
                      echo '<span class="input-group-btn">';
                      echo '<button class="BTN_FECH_COM btn btn-default" type="button"  I_ADI="'.$row['id'].'"> <span class="glyphicon glyphicon-floppy-open"></span></button>';
                      echo '</span>';
                      echo '<input  type="date" class="fech_compro_in form-control" value="'.$row['fech_compro'].'" >';
                      echo '</div><!-- /input-group -->';
                     
                      echo  '</th>';        
                   ////****** Fecha  Real de entrega  
                      echo  '<th>';
                      
                      echo '<div class="input-group">';
                      echo '<span class="input-group-btn">';
                      echo '<button class="BTN_FECH_ENTRA btn btn-default" type="button" I_ADI="'.$row['id'].'"> <span class="glyphicon glyphicon-floppy-open"></span></button>';
                      echo '</span>';
                      echo  '<input  type="date" class="fec_real_entrega form-control" value="'.$row['fech_real'].'" >';
                      echo '</div><!-- /input-group -->';
                      
                      echo  '</th>';  
                    
                    
                    
               echo  '</tr>';
                     
       }

    echo  '</tbody>';


echo '</table> ';
echo  '</div>';



?> 
