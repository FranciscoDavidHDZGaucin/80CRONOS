<?php
///****adi_show_table_gerentes
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_show_table_gerentes.php
 	Fecha  Creacion : 19/11/2016  
	Descripcion  : 
             Script para  mostrar  tabla  de los  adicionales  solicitados por 
  *         el gerente
	Modificado  Fecha  : 
  *         23/11/2016    Se  Elimino  el  parametro  Autorizar dado  a que los gerentes 
  *                        No  necesitan  autorizacion.s
*/
///***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos

$nombe_usuario = filter_input(INPUT_POST, 'nombre_usu');

$cadena_for_qery  = "SELECT * FROM adicionales  where  nombre_usu ='".$nombe_usuario."' ";
//$ejec_qery_get_tb = mysqli_query($conecata1, $cadena_for_qery);
$obj_mysql =  new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 
 
$ejec_qery_get_tb = $obj_mysql->query($cadena_for_qery);

echo  '<div  class="table-responsive">';
echo '<table class="table">';
               echo '<tr>';
                     echo  '<th>ID</th>';
                     echo  '<th>Almacen</th>'; 
                    /// echo  '<th>Codigo Producto</th>';
                     echo  '<th>Nombre Producto</th>';
                     echo  '<th>F Solicitud</th>';
                     echo  '<th>Reqerimiento</th>';
                     echo  '<th class="text-center">Precio Soli P/Venta</th>';
                     echo  '<th class="text-center">Cantidad Req</th>';
                     //echo  '<th>Autorizado</th>';
                     echo  '<th class="text-center">Proyeccion Almacen por Mes</th>';
                     ////*****Elementos Obtenidos del  planeador 
                     echo  '<th>Fecha Compromiso</th>';
                     echo  '<th>Fech Real Entrega</th>';
                     echo '<th>Comentarios</th>';
                     echo  '<th>Fe Real de Entrega</th>';
                     echo '<th></th>';
                     
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
                     ///echo  '<th>'.$row['codigo_pro'].'</th>';
                     echo  '<th>'."#".$row['codigo_pro']."---".$row['nom_pro'].'</th>';
                     ///****Fecha  Solicitud 
                     $date_fsol = new DateTime($row['fecha_sol']);
                     echo  '<th class ="text-success" >'.$date_fsol->format('d/m/Y').'</th>';
                     ////****Fecha 
                     $date_rq =  new DateTime($row['fecha_rq']);
                     echo  '<th class="text-info">'.$date_rq->format('d/m/Y').'</th>';
                     echo  '<th class="text-center" >'.$row['precio_sol_pv'].'</th>';
                     echo  '<th class="text-center" >'.$row['cant_req'].'</th>';
                     /*****Cambio Autorizacion
                     if ($row['auto']==0)
                     {
                        echo  '<th class="text-danger" >Pendiente</th>';  
                     }else{
                        echo  '<th class="text-info" >Autorizado</th>'; 
                     }*/
                     ////*******************************
                     echo  '<th class="text-center">'.$row['proyec_for_mes'].'</th>';
                     ///***Generamos  el  Btn  para Eliminara '<input id="elem_adis" hidden value='.$row['id'].
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
                     echo '<th id ='.$row['id'].' class="btn_del_adi  btn  btn-danger" ><span style="font-size:25px"class="glyphicon glyphicon-trash"></span></th>  ';
          echo  '</tr>';
                     
       }

    echo  '</tbody>';

echo '</table> ';
echo  '</div>';

?>
