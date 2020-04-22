<?php
 ////demandaCero.php 
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : demandaCero.php 
 	Fecha  Creacion : 08/05/2017
	Descripcion  :
  *             Escrip  Prototipo para  Mandar Demanda a  Cero 
  * 07/06/2017  Escrip  Adaptado  para  Llamada  AJAX  para   realizar  la demanda a CERO 
  *            
  */
///***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
/////**AÑO
$YYY = filter_input(INPUT_POST, 'YYY');
////****Mes
$MMM = filter_input(INPUT_POST, 'MMM');

$FechaActua = strtotime(date("Y-m-d H:i:00",time()));
$FechaACopara = strtotime($YYY."-".$MMM."-01  00:00:00");
if($FechaACopara > $FechaActua){
    
    ////*Obtenemos  el  numero de  elementos
$strignDeMAN = sprintf("SELECT * FROM pedidos.pronostico   where  anio=%s  and   mes =%s ",
 GetSQLValueString($YYY, "int"),GetSQLValueString($MMM, "int"));
        
 $qeryCero = mysqli_query($conecta1, $strignDeMAN);

///if(filter_has_var(INPUT_POST, "Update")){
while( $fetchCero = mysqli_fetch_array($qeryCero))
{
   $string_demanda = sprintf( "Update pedidos.pronostico set demanda =0 where id =%s",
 GetSQLValueString($fetchCero['id'],"int"));
   mysqli_query($conecta1,$string_demanda); 
}
    
    
  $result = "Modificacion Realizada ";
}else{
 $result ="Fecha Incorrecta para el Servidor";
}

$AregloR =  array("Re"=>$result ) ;

      $json_resultado = json_encode($AregloR);
   ///****Fin Condicion  
        ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
    echo $json_resultado; 

?>
