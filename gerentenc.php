<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :gerentenc.php  
 	Fecha  Creacion :20/09/2016 
	Descripcion  : 
	Copia  del  archivo   gerentenc.php  perteneciente  al  Proyecto   Pedidos 
	Nota   para  este script   utiliza   los   siguientes  archivos .
 *      ****correos.php   
 *      ****calculodv.php 
 *      Modificado  Fecha  : 
*/
///****Inicio   Librerias  Utilizadas  en Cronos
///****Cabecera Cronos 
require_once('header_gerentes.php');
///***Conexion  sap
require_once('conexion_sap/sap.php');
/*****Sintetizador de  Datos en el proyecto  pedidos   se  utiliza el   
formtato_datos2.php   pero     se  analiso   y son   identicos  los  archivos 
 por lo que se   dejo el  formato_datos.php  
 *  */
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***Conexion Mysql  
require_once('Connections/conecta1.php');
///**Uso de  la Base  de Datos
///mssql_select_db("AGROVERSA"); 
///*****
 require('correos.php');   //funcion para mandar correos
////******
require('calculodv.php');   //funcion para obtener el email de un usuario en especifico
///****FIN  Librerias  Utilizadas  en Cronos 

//Conexion PDO para que funcione la funcion avisovta
function dbConnect (){
    $conn = null;
    $host = 'localhost';
    $db =   'pedidos';
    $user = 'root';
    $pwd =  'avsa0543';
    try {
        $conn = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pwd);
        //echo 'Connected succesfully.<br>';
    }
    catch (PDOException $e) {
        echo '<p>Cannot connect to database !!</p>';
        exit;
    }
    return $conn;
 }
function documento($prospecto,$documento){   
    $busqueda=sprintf("SELECT count(id_e) as contador FROM entrega WHERE id_p=%s and id_d=%s",
             GetSQLValueString($prospecto, "int"),
                GetSQLValueString($documento, "int"));
     $conn = dbConnect();     
    // Extract the values from $result 
  $stmt = $conn->prepare($busqueda);
  $stmt->execute();
  $datos = $stmt->fetch();
  $registros= $datos[0];

    return $registros;    
} 
?> 
<div  class="container"> 
   <div class="table-responsive">
      <table  class="table table-responsive table-bordered">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Agente</th>
                <th>Moneda</th>
                <th>Total</th>
                 <th>Dv</th>
                <th>Concepto</th>
                <th>$Desc</th>
                <th>%Desc </th>
                <th colspan="2">Autorizar</th>
            </tr> 
        </thead>   
        <tbody>
            <?php 
                $str_consulta=sprintf("SELECT * FROM notas where autoriza3=0 and cve_gte=%s and status<>'R'  and isnull(nc_sap)  Order by cve_cte",
                               GetSQLValueString($_SESSION['usuario_rol'], "int")); 
                
                $q_consulta=  mysqli_query($conecta1, $str_consulta) or die (mysqli_error($conecta1));
                                
             //   echo $str_consulta;
            while ($reg = mysqli_fetch_array($q_consulta)) { 
                 ?>   
            <tr>
              <td><a href="popnc.php?id=<?php echo $reg['id']; ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=430,height=500,scrollbars=yes'); return false;"><?php echo $reg['cliente']; ?></a></td>
                <td><?php echo $reg['agente']; ?></td>
                <td><?php echo $reg['moneda']; ?></td>
                <td><?php echo number_format($reg['total_fac'], 2, '.', ','); ?></td>
               <td title="Días Vencido"><?php echo $reg['n_diasv2']; ?></td>
                <td><?php echo $reg['nombre']; ?></td>
                <td><?php echo number_format($reg['importe_desc'], 2, '.', ','); ?></td>
                <td><?php echo $reg['porce_desc']; ?></td>
               <td><a href="poprespuesta.php?id=<?php echo $reg['id'];?>&respuesta=1&usuario=3" onClick="window.open(this.href, this.target, 'width=430,height=250,scrollbars=yes'); return false;">SI </a></td>
                <td><a href="poprespuesta.php?id=<?php echo $reg['id'];?>&respuesta=2&usuario=3" onClick="window.open(this.href, this.target, 'width=430,height=250,scrollbars=yes'); return false;">NO </a></td>
            </tr>
            <?php } ?>    
        </tbody>
    </table>
    </div>    
</div>
 <?php require_once('foot.php');?>     