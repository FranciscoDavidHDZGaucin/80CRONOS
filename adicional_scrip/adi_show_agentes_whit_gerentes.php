<?php

//// 
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_show_agentes_whit_gerentes.php
 	Fecha  Creacion : 22/11/2016
	Descripcion  : 
             Scrip   diseñado   para obtener    los  adicionales de los  agentes 
  *           que  pertenescan  a  una determinada   Zona. 
  * 
        Modificado  Fecha  : 
  *             23/11/2016   Se Agregaron  Nuevos campos con los  siguientes  Nombres 
  *                             ***Fecha Compromiso
  *                             ***Fecha Real
  *                             ***Comentarios
  *             
*/
///***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos

$zona = filter_input(INPUT_POST, 'zona');

$cadena_for_qery  = "select  * from  pedidos.adicionales where cve_usuario in       (Select DISTINCT  agente  from pedidos.almacenes_proyeccion	 where  agente in (SELECT  cve_age FROM pedidos.relacion_gerentes  where  cve_gte = ".$zona."))order by auto";
//$ejec_qery_get_tb = mysqli_query($conecata1, $cadena_for_qery);
$obj_mysql =  new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 
 
$ejec_qery_get_tb = $obj_mysql->query($cadena_for_qery);

echo  '<div  class="table-responsive">';
echo '<table class="table">';
               echo '<tr>';
                     echo  '<th>ID</th>';
                     echo  '<th>Almacen</th>'; 
                     echo  '<th class="text-center">Agente</th>';
                     echo  '<th>Nombre Producto</th>';
                     echo  '<th>F Solicitud</th>';
                     echo  '<th>Requerimiento</th>';
                     echo  '<th class="text-center">Precio Soli P/Venta</th>';
                     echo  '<th class="text-center">Cantidad Req</th>';
                     echo  '<th>Autorizado</th>';
                     echo  '<th>Proyeccion</th>'; 
                    ////*****Elementos Obtenidos del  planeador 
                     echo  '<th>Fecha Compromiso</th>';
                     echo  '<th>Fech Real Entrega</th>';
                     echo '<th>Comentarios</th>';
                     echo  '<th>Fe Real de Entrega</th>';
                    echo   '<th></th>'; 
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
                     echo  '<th class="text-center">'.$row['nombre_usu'].'</th>';
                     echo  '<th>'."#".$row['codigo_pro']."---".$row['nom_pro'].'</th>';
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
                        echo  '<th  ><button type="button"  id="btn_autoriza"  I_ADI='.$row['id'].' class="btn_change_auto btn  btn-danger"> <span class="glyphicon glyphicon-edit"></span></button></th>';  
                     }else{
                                if($row['auto']==2)
                                {
                                    echo  '<th class="text-danger" >Rechazado</th>'; 
                                }else     
                                {
                                     echo  '<th class="text-info" >Autorizado</th>'; 
                                }
                     }
                     ////*******************************
                     echo  '<th>'.$row['proyeccion'].'</th>';
                        ///****Fecha  Compromiso  
                     echo  '<th>'.$row['fech_compro'].'</th>';
                     ///***Fecha   Real
                     echo  '<th>'.$row['fech_real'].'</th>';
                     ///***Btn  Comentarios
                     echo  '<th><button  type="button" class="btn_comentarios btn btn-info"  I_ADI="'.$row['id'].'"   ><span class="glyphicon glyphicon-edit"></span> </button></th>';
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
                     ///echo '<th id ='.$row['id'].' class="btn_del_adi  btn  btn-danger" ><span style="font-size:25px"class="glyphicon glyphicon-trash"></span></th>  ';
          echo  '</tr>';
                     
       }

    echo  '</tbody>';


echo '</table> ';
echo  '</div>';


?>
