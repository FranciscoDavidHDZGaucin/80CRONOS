<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :Nuevo.php  
 	Fecha  Creacion : 21/09/2016     
	Descripcion  : 
	Copia  archivo  Nuevo.php    parte  del  Proyecto  Pedidos
	Modificado  Fecha  : 
*/
///****Inicio   Librerias  Utilizadas  en Cronos
///****Cabecera Cronos 
require_once('header_asisdir.php');
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
mssql_select_db("AGROVERSA"); 
///****FIN    Librerias  Utilizadas  en Cronos 
mysqli_select_db($conecta1,$database_conecta1);

 //Para mostrar el agente elegido en el combo
$q_agentes=("select cve_age as n_agente, nom_age as agente from relacion_gerentes   order by agente");
				
$q_agentes_go=mysqli_query($conecta1,$q_agentes) or die (mysqli_error($conecta1));	
if(isset($_POST["consulta"]))
   {

///require_once('Connections/conecta2.php');
///require_once('formato_datos2.php'); 

$wk1 = $_POST['sem1'];
$wk2= $_POST['sem2'];
$agents= $_POST['agentes'];
$asuntr= $_POST['asuntoR'];



if(isset($wk1) and isset($wk2) and empty($agents) and empty($asuntr))
{   
 

        $resultad = sprintf("SELECT * 
                            FROM  `Captura` AS c
                            INNER JOIN relacion_gerentes AS r ON c.NoAgent = r.cve_age and Week BETWEEN '$wk1' AND '$wk2' where r.cve_gte=%s order by FechaG",
        GetSQLValueString($_SESSION['usuario_rol'],"int"));  
        //$sqlx = mysqli_query($conecta1, $resultad) or die (mysqli_error($conecta1));
        $sql_resultad=  mysqli_query($conecta1, $resultad) or die (mysqli_error($conecta1));
}
elseif(isset($_POST['sem1']) and isset($_POST['sem2']) and isset($_POST['asuntoR']) and empty($_POST['agentes']) )
{   
     
        $resultad = sprintf("SELECT * 
                             FROM  `Captura` AS c
                             INNER JOIN relacion_gerentes AS r ON c.NoAgent = r.cve_age and Week BETWEEN '$wk1' AND '$wk2' and Asunto='$asuntr' where r.cve_gte=%s order by FechaG",
        GetSQLValueString($_SESSION['usuario_rol'],"int"));  
        //$sqlx = mysqli_query($conecta1, $resultad) or die (mysqli_error($conecta1));
        $sql_resultad=  mysqli_query($conecta1, $resultad) or die (mysqli_error($conecta1));
}
elseif(isset($_POST['sem1']) and isset($_POST['sem2'])  and isset($_POST['agentes'])and empty($_POST['asuntoR']) )
{   
    
        $resultad = sprintf("SELECT * 
                             FROM  `Captura` AS c
                             INNER JOIN relacion_gerentes AS r ON c.NoAgent = r.cve_age and Week BETWEEN '$wk1' AND '$wk2' and SubAgent= '$agents' where r.cve_gte=%s order by FechaG",
        GetSQLValueString($_SESSION['usuario_rol'],"int"));  
        //$sqlx = mysqli_query($conecta1, $resultad) or die (mysqli_error($conecta1));
        $sql_resultad=  mysqli_query($conecta1, $resultad) or die (mysqli_error($conecta1));
}
 else {
    
 
        $resultad2 = "SELECT * 
                      FROM  Captura where  SubAgent= '$agents' and Asunto='$asuntr' and Week BETWEEN '$wk1' AND '$wk2'";  
        $sql_resultad = mysqli_query($conecta1, $resultad2) or die (mysqli_error($conecta1));;    
    }

}
?> 
<div  class="container"> 
   <div class="table-responsive">
    <table  class="table table-responsive table-bordered">
      
       <form action="reportes_asisdirgv.php" method="post" target="_self">
        <tbody>
        <td><b>Rango de Semanas</b><br><input type="week" name="sem1"  style="width:159px" value="<?php echo $wk1; ?>"> al <input type="week" name="sem2" style="width:159px" value="<?php echo $wk2; ?>">&nbsp;</td>
           <td ><b>Agentes</b><br><select name="agentes" id="agentes" style="width:220px"> 
	                   <option value="">--TODOS--</option>
			  <?php
			      while ($row=mysqli_fetch_array($q_agentes_go))
								{
								if ($row['agente']==$agents){

								 echo '<option selected value="'.$row['agente'].'">'.$row['agente'].'</option>';	
								}else{
									echo '<option value="'.$row['agente'].'">'.$row['agente'].'</option>';	
								}	
								}
						?>
						</select></td>
            
                                                <td><b>Asunto</b><br><SELECT name="asuntoR" id='ww' onchange="if(this.value=='5.TRABAJOOFICINA' || this.value=='4.CAPACITACION' || this.value=='7.EXPOS') {document.getElementById('cliente').disabled =true} else {document.getElementById('cliente').disabled = false} " style="width:200px">
                                  <option value="">--TODOS--</option>
                                  <option <?php if($asuntr== '1.VENTAS'){ echo 'selected';} ?>>1.VENTAS</option>
                                    <option <?php if($asuntr== '2.COBRANZA'){ echo 'selected';} ?>>2.COBRANZA</option>
                                     <option <?php if($asuntr== '3.PROMOCION'){ echo 'selected';} ?> >3.PROMOCION</option>
                                     <option <?php if($asuntr== '4.CAPACITACION'){ echo 'selected';} ?>>4.CAPACITACION</option>
                                     <option <?php if($asuntr== '5.TRABAJOOFICINA'){ echo 'selected';} ?>>5.TRABAJOOFICINA</option>
                                      <option <?php if($asuntr== '6.PARCELADEMOSTRATIVA'){ echo 'selected';} ?>>6.PARCELADEMOSTRATIVA</option>
                                         <option <?php if($asuntr== '7.EXPOS'){ echo 'selected';} ?>>7.EXPOS</option>
                                         <option <?php if($asuntr== '8.REUNIONESTRABAJO'){ echo 'selected';} ?>>8.REUNIONESTRABAJO</option>
                                          <option <?php if($asuntr== '9.MONITOREO'){ echo 'selected';} ?>>9.MONITOREO</option></select>&nbsp;&nbsp; </td>
                <td><input type="submit" name="consulta" value="Filtrar" /> </td>
  
            
        </tbody>
        </form>
        
    </table>
    </div>  
             
          <br>
        <table  class="table table-responsive table-bordered">
            <tr> 
                <thead>
                <th>Fecha</th>
                <th>Ciudad</th>    
                <th>Cliente</th>
                <th>Asunto</th>
                <th>Resultado</th>
                <th>Venta</th>
                <th>Objecion Venta</th>
                 <th>Agente</th>
                </thead>
            </tr>  
            <tbody>
                <?php while($row = mysqli_fetch_array($sql_resultad)) { ?>
                <tr>
                    <td width="1070"><?php echo $row['FechaG']; ?></td>
                    <td width="70"><?php echo $row['Ciudad']; ?></td>
                    <td width="370"><?php echo $row['Cliente']; ?></td>
                    <td width="170"><?php echo $row['Asunto']; ?></td>
                    <td width="470"><?php echo $row['Resultado']; ?></td>
                    <td width="270"><?php echo $row['Venta']; ?></td>
                    <td width="370"><?php echo $row['ObjVenta']; ?></td>
                    <td width="370"><?php echo $row['SubAgent']; ?></td>
                   
                </tr>
               
                <?php } ?>
            </tbody>
           
            
        </table>    
             
</div>
 <?php require_once('foot.php');?>     