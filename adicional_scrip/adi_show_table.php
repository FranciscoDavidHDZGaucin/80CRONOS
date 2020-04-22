<?php
///**adi_show_table
     /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_show_table.php 
 	Fecha  Creacion : 15/11/2016
	Descripcion  : 
	Script   para   obtener los  adicionales mediante  el  numero 
      * del  agente .
	Modificado  Fecha  : 
      *   **** 01/12/2016   Declaramos   la variable   $TB_FORMATO  la  cual  determina el  tipo de tabla  a mostrar 
      *                 $TB_FORMATO =  1  Mostrar  los  adicionales de los  agentes  
      *                 $TB_FORMATO =  2  Mostrar  la   tabla  ordenada  segun estados.
*/
///***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos

$nombe_usuario = filter_input(INPUT_POST, 'nombre_usu');
$TB_FORMATO = filter_input(INPUT_POST, 'FOR_TB');

if($TB_FORMATO ==1) 
{
   $cadena_for_qery  = "SELECT id,almacen,codigo_pro,nom_pro,fecha_sol,fecha_rq,precio_sol_pv,cant_req,venta,fech_compro,fech_real,precio_sol_pv,cant_req,auto,proyeccion  FROM adicionales  where  nombre_usu ='".$nombe_usuario."' and auto =0 ";
 
}
if($TB_FORMATO ==2) {
  $cadena_for_qery  = "SELECT id,almacen,codigo_pro,nom_pro,fecha_sol,fecha_rq,precio_sol_pv,cant_req,venta,fech_compro,fech_real,precio_sol_pv,cant_req,auto,proyeccion  FROM adicionales  where  nombre_usu ='".$nombe_usuario."' order by auto ";
  
}

//$ejec_qery_get_tb = mysqli_query($conecata1, $cadena_for_qery);
$obj_mysql =  new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 
 
$ejec_qery_get_tb = $obj_mysql->query($cadena_for_qery);

echo '<table class="table  table-striped">';
    echo  '<thead>';
               echo '<tr>';
                     echo  '<th>ID</th>';
                     echo  '<th>Almacen</th>'; 
                     echo  '<th>Codigo Producto</th>';
                     echo  '<th>Nombre Producto</th>';
                     echo  '<th>Fecha Soli</th>';
                     echo  '<th>Fecha Req</th>';
                     echo  '<th>Venta</th>';
                      echo  '<th>Fecha Compromiso</th>';
                      echo  '<th>Fech Real Entrega</th>';
                       echo '<th>Comentarios</th>';
                     echo  '<th class="text-center">Precio Soli P/Venta</th>';
                     echo  '<th class="text-center">Cantidad Req</th>';
                     echo  '<th>Autorizado</th>';
                     echo  '<th>Proyeccion</th>';
                     echo  '<th>Fe Real de Entrega</th>';
                     echo  '<th> </th>';
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
                     echo  '<th>'.$row['codigo_pro'].'</th>';
                     echo  '<th>'.$row['nom_pro'].'</th>';
                     ///****Fecha  Solicitud 
                     $date_fsol = new DateTime($row['fecha_sol']);
                     echo  '<th class ="text-success" >'.$date_fsol->format('d/m/Y').'</th>';
                     ////****Fecha 
                     $date_rq =  new DateTime($row['fecha_rq']);
                     echo  '<th class="text-info">'.$date_rq->format('d/m/Y').'</th>';
              
                     ///***Venta
                     echo  '<th class="text-center">'.$row['venta'].'</th>'; 
                     ///****Fecha  Compromiso  
                     echo  '<th>'.$row['fech_compro'].'</th>';
                     ///***Fecha   Real
                      if ($row['est_entrega']==1)
                      {
                             echo  '<th>'.$row['fech_real'].'</th>';
                      }else
                      {
                           echo  '<th></th>';
                      }
                    ///***Btn  Comentarios
                     echo  '<th><button  type="button" class="btn_comentarios btn btn-info"  I_ADI="'.$row['id'].'"   ><span class="glyphicon glyphicon-edit"></span> </button></th>';
                    /////***Precio Soli P/Venta
                   echo  '<th>'.$row['precio_sol_pv'].'</th>';
                   /////***Cantidad Req
                   echo  '<th>'.$row['cant_req'].'</th>';
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
                     ////****Status Entrega
                       if ($row['est_entrega']==0)
                     {
                        echo  '<th class="text-danger">Pendiente</th>';  
                     }else{
                          if ($row['est_entrega']==2)
                          {
                              echo  '<th class="text-danger">En Transito</th>';
                          }else{
                                echo  '<th class="text-info">Entregado</th>'; 
                          }
                            
                     
                     }
                     ///***Generamos  el  Btn  para Eliminara '<input id="elem_adis" hidden value='.$row['id'].
                   
                     if($row['auto'] == 0)
                     {
                     echo '<th id ='.$row['id'].' class="btn_del_adi  btn  btn-danger" ><span style="font-size:25px"class="glyphicon glyphicon-trash"></span></th>  ';
                     }   
               echo  '</tr>';
                     
       }

    echo  '</tbody>';


echo '</table> ';





?>
