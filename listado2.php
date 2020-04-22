<?php

/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : listado2.php 
 	Fecha  Creacion : 13/10/2016 
	Descripcion  : 
	Copia  del  Proyecto   Proyeccion Alias presupuestos 
 *      Nombre  del  Archivo  Origen : listado2.php  
 *      Servidor : .17   
	Modificado  Fecha  : 
*/
////**Inicio De Session 
session_start();
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
///****
require_once('funciones.php');   
/////************Inicio  Codigo Copiado******************************
  mysqli_select_db($conecata1, $database_conecta1);
    
  //  $string_postulados=("Select * from postulado order by id desc, f_alta  desc ");
  //  $sql_postulados=mysqli_query($conecta1,$string_postulados) or die (mysqli_error($conecta1));
    
    if (isset($_REQUEST['eliminar'])){
    
            $id=$_REQUEST['eliminar'];
          

            $eliminarSQL = sprintf("DELETE FROM pronostico Where id=%s",
                          GetSQLValueString($id, "int"));
            $result=  mysqli_query($conecta1,$eliminarSQL) or die (mysqli_error($conecta1));
          
        }
    
    
    
    
  $string_1=sprintf("select pronostico.cve_alma,pronostico.anio, pronostico.zona,pronostico.id, pronostico.cve_age, pronostico.zona,pronostico.cve_alma, pronostico.nom_alma, pronostico.cve_prod, pronostico.nom_prod,pronostico.mes, pronostico.cantidad,pronostico.precio,pronostico.total, config_meses.activo from pronostico
                INNER JOIN config_meses ON pronostico.mes=config_meses.mes INNER JOIN relacion_gerentes ON pronostico.cve_age=relacion_gerentes.cve_age WHERE relacion_gerentes.cve_gte=%s and config_meses.activo=1 order by pronostico.nom_prod, pronostico.mes",
                     ////       GetSQLValueString($_SESSION['usuario_relaciongte'], "int")); 
                          GetSQLValueString($_SESSION['usuario_rol'], "int"));
  
    //Suma del TOTAL
  $string_suma=sprintf("SELECT SUM(pronostico.total) as total from pronostico  INNER JOIN config_meses ON pronostico.mes=config_meses.mes INNER JOIN relacion_gerentes ON pronostico.cve_age=relacion_gerentes.cve_age  WHERE relacion_gerentes.cve_gte=%s and config_meses.activo=1",
                     ////       GetSQLValueString($_SESSION['usuario_relaciongte'], "int")); 
                          GetSQLValueString($_SESSION['usuario_rol'], "int"));
  
     //Suma Cantidad
  $string_sumac=sprintf("SELECT SUM(pronostico.cantidad) as total from pronostico  INNER JOIN config_meses ON pronostico.mes=config_meses.mes INNER JOIN relacion_gerentes ON pronostico.cve_age=relacion_gerentes.cve_age WHERE relacion_gerentes.cve_gte=%s and config_meses.activo=1",
                  ////       GetSQLValueString($_SESSION['usuario_relaciongte'], "int")); 
                          GetSQLValueString($_SESSION['usuario_rol'], "int"));
  
 if (isset($_REQUEST['filtrar'])){
       
  
        //Si se dio clic para Filtrar entrara a este código
          $prod=$_POST['productos'];
          $meses=$_POST['meses'];
          $anio=$_POST['anio'];
          $age=$_POST['agentes'];
         
           $_SESSION['mes']=$meses;
           $_SESSION['producto']=$prod;
          $_SESSION['anio']=$anio;
           $_SESSION['age']=$age;
          
           $enunciadoini=sprintf("select * from vista_proyeccion where cve_gte=%s   ",
                     ////       GetSQLValueString($_SESSION['usuario_relaciongte'], "int")); 
                          GetSQLValueString($_SESSION['usuario_rol'], "int"));
           $enunciadosum=sprintf("select SUM(total) as total from vista_proyeccion where cve_gte=%s   ",
                      ////       GetSQLValueString($_SESSION['usuario_relaciongte'], "int")); 
                          GetSQLValueString($_SESSION['usuario_rol'], "int"));
           $enunciadocant=sprintf("select SUM(cantidad) as total from vista_proyeccion where cve_gte=%s   ",
                      ////       GetSQLValueString($_SESSION['usuario_relaciongte'], "int")); 
                          GetSQLValueString($_SESSION['usuario_rol'], "int"));
           
           if($prod!=""||$meses!=""||$anio!=""||$age!=""){
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
          
          //agente
            if ($age!=""){

            $valor4=GetSQLValueString($age, "int");

            $condision4="cve_age=".$valor4;
            $string=("$condision $comodo $condision2 $comodo2 $condision3 $comodo3 $condision4");
              
             $comodo4=" and ";
        } else{
            $comodo4="";
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
   
   
   $string_meses=("SELECT mes FROM config_meses WHERE activo=1 order by id");
   $query_meses=mysqli_query($conecta1,$string_meses) or die (mysqli_error($conecta1));
   
   
   $string_agentes=sprintf("SELECT * FROM relacion_gerentes WHERE cve_gte=%s order by nom_age",
              ////       GetSQLValueString($_SESSION['usuario_relaciongte'], "int")); 
                          GetSQLValueString($_SESSION['usuario_rol'], "int"));
   $query_agentes=mysqli_query($conecta1,$string_agentes) or die (mysqli_error($conecta1));
   
/////************Fin   Codigo Copiado******************************
 ?>
<div  class="container"> 
 <?php require_once 'submenu_proyeccion.php'  ?>
	<br>		

   <!--Inicio    Form-->
   <div  class="row">
        <form class="form-inline"   name="form1" method="POST" action="listado2.php">
            <select class="form-control" name="agentes" id="agentes"  />
        <option value="">--Agentes Todos --</option>
         <?php

           while ($row4=mysqli_fetch_array($query_agentes))
             {
                 if ($row4['cve_age']==$age){

                  echo '<option selected value="'.$row4['cve_age'].'">'.$row4['nom_age'].'</option>';	
                 }else{
                    echo '<option value="'.$row4['cve_age'].'">'.$row4['nom_age'].'</option>';	
                 }	
             }


         ?>
        
    </select>
            
        <select class="form-control" name="productos" id="productos" >
        <option value="">--Productos Todos --</option>
         <?php

           while ($row2=mysqli_fetch_array($sql_productos))
             {
                 if ($row2['cve_prod']==$cve_prod){

                  echo '<option selected value="'.$row2['cve_prod'].'">'.$row2['nom_prod'].'</option>';	
                 }else{
                    echo '<option value="'.$row2['cve_prod'].'">'.$row2['nom_prod'].'</option>';	
                 }	
             }


         ?>
        
    </select>
         <select  class ="form-control" name="meses" id="meses"  >
        <option value="">--Mes Todos --</option>
         <?php

           while ($row3=mysqli_fetch_array($query_meses))
             {
                 if ($row3['mes']==$cve_mes){

                  echo '<option selected value="'.$row3['mes'].'">'.name_mes($row3['mes']).'</option>';	
                 }else{
                  echo '<option value="'.$row3['mes'].'">'.name_mes($row3['mes']).'</option>';	
                 }	
             }


         ?>
        
    </select>
         <select class="form-control" name="anio" id="anio">
            <option value="2015">2015</option>
            <option value="2016">2016</option>
	    <option value="2017">2017</option>
            
        </select>
      <input class="btn  btn-success"  type="submit" id="filtrar" name="filtrar"  value="Filtrar" />   
     
          <a type="button" class="btn btn-success"  href="exportar.php?q1=<?php echo $string_1; ?>&q2=<?php echo $string_suma; ?>&q3=<?php echo $string_sumac; ?>"> <img src="images/excel.ico"/></a>
       
      </form>   
   </div>
   <!--Fin    Form--> 
   <br> 
   <!---Inicio Tabla----> 
   <div  class="row">
        <table class="table table-condensed">
         <thead>
          <tr>   
            <th>Almacen </th>
            <th>Clave</th>
            <th>Producto</th>
            <th>Agente</th>
            <th>Mes</th>
             <th>Año</th>
           
            <th>Cantidad </th>
           
            <th></th>
          </tr>  
         </thead>
         
         <tfoot>
             <tr>
                 <td></td>
                 <td></td>
                 <td></td>
                  <td></td>
                
                 <td></td>
                 <td>TOTAL=</td>
                  <td><?php echo number_format($totalsumac['total'], 2, '.', ',') ?></td>
              
                 
             </tr>
             
         </tfoot>
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
             <td title="<?php echo $row['zona']?>"> <?php echo $row['cve_age']?> </td>
             <td> <?php echo name_mes($row['mes']) ?> </td>
             <td> <?php echo $row['anio']?> </td>
            
             <td> <?php echo $row['cantidad']?> </td>
            
              <?php if ($_SESSION['der_capturag']==1){ ?>
             <td> <a href="listado2.php?eliminar=<?php echo $row['id']; ?>"><img src="images/eliminar.png" title="Eliminar" onclick="return confirm('¿Esta Seguro de Eliminar?');"/> </a></td>
              <?php }?>
         </tr>  
        <?php } ?>
         </tbody>    
     </table>
   </div>
   <!---Fin Tabla----> 
</div>
<?php  require_once('foot.php');?>  