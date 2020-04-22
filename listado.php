<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : listado.php 
 	Fecha  Creacion : 11/10/2016 
	Descripcion  : 
	Copia  del  Proyecto   Proyeccion Alias presupuestos 
 *      Nombre  del  Archivo  Origen : listado.php  
 *      Servidor : .17   
	Modificado  Fecha  : 
*/
///****Cabecera Cronos 
require_once('header.php');
///***Conexion  sap
require_once('conexion_sap/sap.php');
/*****Sintetizador de  Datos en el proyecto  pedidos   se  utiliza el   
formtato_datos2.php   pero     se  analiso   y son   identicos  los  archivos 
 por lo que se   dejo el  formato_datos.php  
 *  */
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***Conexion Mysql  
require_once('Connections/conecta1.php');
///****
require_once('funciones.php');   
/////******************************************
   mysqli_select_db($conecata1, $database_conecta1);
    
  //  $string_postulados=("Select * from postulado order by id desc, f_alta  desc ");
  //  $sql_postulados=mysqli_query($conecta1,$string_postulados) or die (mysqli_error($conecta1));
    
    if (isset($_REQUEST['eliminar'])){
    
            $id=$_REQUEST['eliminar'];
          

            $eliminarSQL = sprintf("DELETE FROM pronostico Where id=%s",
                          GetSQLValueString($id, "int"));
            $result=  mysqli_query($conecta1,$eliminarSQL) or die (mysqli_error($conecta1));
          
        }
 
  $string_1=sprintf("select pronostico.cve_alma,pronostico.anio, pronostico.zona, pronostico.id, pronostico.cve_age, pronostico.zona,pronostico.cve_alma, pronostico.nom_alma, pronostico.cve_prod, pronostico.nom_prod,pronostico.mes, pronostico.cantidad,pronostico.precio,pronostico.total, config_meses.activo from pronostico
                INNER JOIN config_meses ON pronostico.mes=config_meses.mes WHERE pronostico.cve_age=%s and config_meses.activo=1 order by pronostico.nom_alma,pronostico.nom_prod, pronostico.mes DESC",
                       GetSQLValueString($_SESSION['usuario_agente'], "int")); 
            $query_1=  mysqli_query($conecta1,$string_1) or die (mysqli_error($conecta1));

            //Suma del TOTAL
            $string_suma=sprintf("SELECT SUM(pronostico.total) as total from pronostico  INNER JOIN config_meses ON pronostico.mes=config_meses.mes WHERE pronostico.cve_age=%s and config_meses.activo=1",
                             GetSQLValueString($_SESSION['usuario_agente'], "int"));
            $query_suma=mysqli_query($conecta1,$string_suma) or die (mysqli_error($conecta1));
            $totalsuma=  mysqli_fetch_assoc($query_suma);
   
             //Suma Cantidad
            $string_sumac=sprintf("SELECT SUM(pronostico.cantidad) as total from pronostico  INNER JOIN config_meses ON pronostico.mes=config_meses.mes WHERE pronostico.cve_age=%s and config_meses.activo=1",
                             GetSQLValueString($_SESSION['usuario_agente'], "int"));
            $query_sumac=mysqli_query($conecta1,$string_sumac) or die (mysqli_error($conecta1));
            $totalsumac=  mysqli_fetch_assoc($query_sumac);
   
   
   if (isset($_REQUEST['filtrar'])){
       
  
        //Si se dio clic para Filtrar entrara a este código
          $prod=$_POST['productos'];
          $meses=$_POST['meses'];
          $anio=$_POST['anio'];
         
           $_SESSION['mes']=$meses;
           $_SESSION['producto']=$prod;
          $_SESSION['anio']=$anio;
          
           $enunciadoini=sprintf("select * from vista_proyeccion where cve_age=%s   ",
                     GetSQLValueString($_SESSION['usuario_agente'], "int")); 
           $enunciadosum=sprintf("select SUM(total) as total from vista_proyeccion where cve_age=%s   ",
                     GetSQLValueString($_SESSION['usuario_agente'], "int")); 
           $enunciadocant=sprintf("select SUM(cantidad) as total from vista_proyeccion where cve_age=%s   ",
                     GetSQLValueString($_SESSION['usuario_agente'], "int")); 
           
           if($prod!=""||$meses!=""||$anio!=""){
                $otrocomodo=" and ";
           }
           
             if ($prod!=""){
                  $valor1=GetSQLValueString($prod, "text");
                  $condision="cve_prod=".$valor1;
                  $string=("$condision");

                
                $comodo=" and ";
                 
                 
             }else{
                   $comodo="";
             }
             
            //Mes
            if ($meses!=""){

                $valor2=GetSQLValueString($meses, "int");

                $condision2="mes=".$valor2;
                $string=("$condision $comodo $condision2 ");

                 
                 $comodo2="and ";
            } else{
                $comodo2="";
            }    
           
            //anio
            if ($anio!=""){

            $valor3=GetSQLValueString($anio, "int");

            $condision3="anio=".$valor3;
            $string=("$condision $comodo $condision2 $comodo2 $condision3");
              
             $comodo3=" and ";
        } else{
            $comodo3="";
        }    
        
        $string_1=$enunciadoini.$otrocomodo.$string;
        $string_suma=$enunciadosum.$otrocomodo.$string;
        $string_sumac=$enunciadocant.$otrocomodo.$string;
        
        $query_1=  mysqli_query($conecta1,$string_1) or die (mysqli_error($conecta1)); 

        //Suma en monto
        $query_suma=mysqli_query($conecta1,$string_suma) or die (mysqli_error($conecta1));
        $totalsuma=  mysqli_fetch_assoc($query_suma);  
         
        //Suma en Volumen
        $query_sumac=mysqli_query($conecta1,$string_sumac) or die (mysqli_error($conecta1));
        $totalsumac=  mysqli_fetch_assoc($query_sumac);
 
   }

   $string_productos=("SELECT DISTINCT(pronostico.cve_prod) as cve_prod, pronostico.nom_prod FROM pronostico
                      INNER JOIN config_meses ON pronostico.mes=config_meses.mes WHERE config_meses.activo=1 order by pronostico.nom_prod, pronostico.mes"); 
   $sql_productos=mysqli_query($conecta1,$string_productos) or die (mysqli_error($conecta1));

   //$string_meses=("SELECT mes FROM config_meses WHERE activo=1 order by id");
   $string_meses=("SELECT distinct(mes)as mes FROM config_meses  order by mes");
   
   $query_meses=mysqli_query($conecta1,$string_meses) or die (mysqli_error($conecta1));

?> 
<div    class="container"> 
      <?php require_once 'submenu_proyeccion.php'  ?>
	<br>		
  
  <div class="row">
    <form class="form-inline" name="form1" method="POST" action="listado.php">
        
        
        <div  class="form-group">
                <select class="form-control" name="productos" id="productos"  >
                     <option value="">--Productos Todos --</option>
                      <?php

                        while ($row2=mysqli_fetch_array($sql_productos))
                          {
                              if ($row2['cve_prod']==$_SESSION['producto']){

                               echo '<option selected value="'.$row2['cve_prod'].'">'.$row2['nom_prod'].'</option>';	
                              }else{
                                 echo '<option value="'.$row2['cve_prod'].'">'.$row2['nom_prod'].'</option>';	
                              }	
                          }


                      ?>
                 </select>
        </div>  
        <div  class ="form-group"> 
                <select class="form-control" name="meses" id="meses" >
                <option value="">--Mes Todos --</option>
                 <?php
                
                   while ($row3=mysqli_fetch_array($query_meses))
                     {
                         if ($row3['mes']==$_SESSION['mes']){

                          echo '<option selected value="'.$row3['mes'].'">'.name_mes($row3['mes']).'</option>';	
                         }else{
                          echo '<option value="'.$row3['mes'].'">'.name_mes($row3['mes']).'</option>';	
                         }	
                     }


                 ?>
                </select>
        </div> 
        <div  class="form-group">     
            <select class="form-control" name="anio" id="anio">
                    <option value="2015">2015</option>
                    <option value="2016">2016</option>
		    <option value="2017">2017</option>
                     <option value="2018">2018</option>
					
            </select>
              <input type="submit" id="filtrar" name="filtrar"  class="btn  btn-success filtro" value="Filtrar" />   
        </div>
        <div  class="form-group">
            <a type ="button" class="btn   btn-success" href="exportar.php?q1=<?php echo $string_1; ?>&q2=<?php echo $string_suma; ?>&q3=<?php echo $string_sumac; ?>"><img src="images/excel.ico"/> </a>
        </div> 
        <!--Contenedor  Tabla--->
        <div  class="row"> 
            <table class="table table-condensed" id="dataTables-listado_pland" >
                <thead>
                 <tr>   
                   <th>Almacen </th>
                   <th>Clave</th>
                   <th>Producto</th>
                   <th>Mes</th>
                   <th>Año</th>
                  
                   <th>Cantidad </th>
                  
                 
                   <th></th>
                 </tr>  
                </thead>
         
       
         <tbody>
        <?php  
        $a=0;
        while ($row=mysqli_fetch_array($query_1)){
          if ($a++ %2){
           echo '<tr class="alt">';
          }else{
           echo '<tr>';
            }
        
        ?>     
           
             <td> <?php echo $row['nom_alma']?> </td>
             <td> <?php echo $row['cve_prod']?> </td>
             <td> <?php echo $row['nom_prod']?> </td>
             <td> <?php echo name_mes($row['mes']) ?> </td>
              <td> <?php echo $row['anio']?> </td>
                   
              <td> <?php echo $row['cantidad']?> </td>
              
            
              <?php if ($_SESSION['der_captura']==1){ 
                     $buscar_activo=sprintf("select activo from config_meses where anio=%s and mes=%s",
                                     GetSQLValueString($row['anio'], "int"),
                                     GetSQLValueString($row['mes'], "int"));
                     $sql_buscar=mysqli_query($conecta1,$buscar_activo) or die (mysqli_error($conecta1));
                     $datos_buscar=  mysqli_fetch_assoc($sql_buscar);
                     $activo=$datos_buscar['activo'];
                      if ($activo==1){ ?>
                          
                      <td> <a href="listado.php?eliminar=<?php echo $row['id']; ?>"><img src="images/eliminar.png" title="Eliminar" onclick="return confirm('¿Esta Seguro de Eliminar?');"/> </a></td>
                     
                 
             
              <?php 
                      }
              
              }?>
         </tr>  
        <?php } ?>
         </tbody>    
     </table> 
        </div>
    </form>   
 </div>
</div>
<?php  require_once('foot.php');?>  