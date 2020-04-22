<?php
 ////demandaCero.php 
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : demandaCero.php 
 	Fecha  Creacion : 08/05/2017
	Descripcion  :
  *             Escrip  Prototipo para  Mandar Demanda a  Cero 
  *            
  */
///***Conexion Mysql  
require_once('Connections/conecta1.php');
///***Formato  de Datosa
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos


////*Obtenemos  el  numero de  elementos
///$strignDeMAN =   "SELECT * FROM pedidos.pronostico   where  anio = 2017  and   mes =9 ";
        
 $qeryCero = mysqli_query($conecta1, $strignDeMAN);

///if(filter_has_var(INPUT_POST, "Update")){
while( $fetchCero = mysqli_fetch_array($qeryCero))
{
   $string_demanda = sprintf( "Update pedidos.pronostico set demanda =0 where id =%s",
 GetSQLValueString($fetchCero['id'],"int"));
  //// mysqli_query($conecta1,$string_demanda); 
}
//}
?>
<form action="demandaCero.php" method="POST">
<input  type="submit" name="Update" >Hola</input> 
</form>