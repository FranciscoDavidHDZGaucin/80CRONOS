<?php

///supervisor_plus
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : supervisor_plus.php
 	Fecha  Creacion : 14/10/2016 
	Descripcion  : 
               Copia  del  Archivo  :  supervisor_plus.php del    Proyecto  Proyeccion  
	Modificado  Fecha  : 
*/
////**Inicio De Session 
session_start();
///****Cabecera Cronos 
require_once('header_planeador.php');
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
///*****************************************

    mysqli_select_db($conecata1, $database_conecta1);
    if (isset($_REQUEST['filtrar'])){
       
  
        //Si se dio clic para Filtrar entrara a este código
          $prod=$_POST['productos'];
          $meses=$_POST['meses'];
          $anio=$_POST['anio'];
          $age=$_POST['agentes'];
           $clasifica=$_POST['clasifica'];
         
           $_SESSION['mes']=$meses;
           $_SESSION['producto']=$prod;
          $_SESSION['anio']=$anio;
           $_SESSION['age']=$age;
          
           $enunciadoini="select * from vista_proyeccion2";
                    
           $enunciadosum="select SUM(total) as total from vista_proyeccion2";
                     
           $enunciadocant="select SUM(cantidad) as total from vista_proyeccion2";
                    
           
           if($prod!=""||$meses!=""||$anio!=""||$age!=""||$clasifica!=""){
                $otrocomodo=" where ";
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
          
          //agente   ///Se corrigio y se 
            if ($age!=""){

            $valor4=GetSQLValueString($age, "int");

            $condision4="cve_gte=".$valor4;
            $string=("$condision $comodo $condision2 $comodo2 $condision3 $comodo3 $condision4");
              
             $comodo4=" and ";
            } else{
                $comodo4="";
            }    
        
            
             //Clasificación agregado 30-07-2016
            if ($clasifica!=""){

            $valor5=GetSQLValueString($clasifica, "text");

            $condision5="clasifica=".$valor5;
            $string=("$condision $comodo $condision2 $comodo2 $condision3 $comodo3 $condision4 $comodo4 $condision5");
              
                $comodo5=" and ";
            } else{
                $comodo5="";
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
   
   //Meses a mostrar
   $string_meses=("SELECT distinct(mes)as mes FROM config_meses  order by mes");
   $query_meses=mysqli_query($conecta1,$string_meses) or die (mysqli_error($conecta1));
   
   
   //Años a mostrar
    $string_anios=("SELECT distinct(anio)as anio FROM config_meses   order by anio");
   $query_anios=mysqli_query($conecta1,$string_anios) or die (mysqli_error($conecta1));
   
    //Clasificación
   $string_clasifica=("SELECT distinct(clasifica)as clasifica FROM productos   order by clasifica");
   $query_clasifica=mysqli_query($conecta1,$string_clasifica) or die (mysqli_error($conecta1));
   
   
   $string_agentes="SELECT distinct(cve_gte) as cve_gte, nom_gte FROM relacion_gerentes order by nom_gte";
                     
   
   $query_agentes=mysqli_query($conecta1,$string_agentes) or die (mysqli_error($conecta1));
   
////********************************************
?>
<div class ="container">
    <div  class="row">
          <form class="form-inline"  name="form1" method="POST">
              <div   class="form-group">  
              <select class="form-control" name="clasifica" id="clasifica">
                 <option value="">--Clasificación Todos --</option>
                    <?php

                      while ($row6=mysqli_fetch_array($query_clasifica))
                        {
                            if ($row6['clasifica']==$clasifica){

                             echo '<option selected value="'.$row6['clasifica'].'">'.$row6['clasifica'].'</option>';	
                            }else{
                               echo '<option value="'.$row6['clasifica'].'">'.$row6['clasifica'].'</option>';	
                            }	
                        }


                    ?>
            </select>
              </div> 
              <div  class="form-group"> 
                    <select class="form-control" name="agentes" id="agentes"  >
                    <option value="">--Zonas Todas --</option>
                    <?php

                      while ($row4=mysqli_fetch_array($query_agentes))
                        {
                            if ($row4['cve_gte']==$age){

                             echo '<option selected value="'.$row4['cve_gte'].'">'.$row4['nom_gte'].'</option>';	
                            }else{
                               echo '<option value="'.$row4['cve_gte'].'">'.$row4['nom_gte'].'</option>';	
                            }	
                        }
                   ?>
        
            </select>
              </div>
        <select class="form-control" name="productos" id="productos"  />
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
    <select class="form-control" name="meses" id="meses"  >
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
    <select class="form-control" name="anio" id="anio"  />
        <option value="">--Año Todos --</option>
         <?php

           while ($rowanios=mysqli_fetch_array($query_anios))
             {
                 if ($rowanios['anio']==$anio){
                     echo '<option selected value="'.$rowanios['anio'].'">'.$rowanios['anio'].'</option>';	
                 }else{
                     echo '<option value="'.$rowanios['anio'].'">'.$rowanios['anio'].'</option>';	
                 }	
             }


         ?>
        
    </select>
        
        
      <input class="btn btn-success" type="submit" id="filtrar" name="filtrar"  class="filtro" value="Filtrar" />   
    </form> 
    </div>
    <br> 
    <div  class ="row">
    <a  class="btn btn-success"  href="exportar.php?q1=<?php echo $string_1; ?>&q2=<?php echo $string_suma; ?>&q3=<?php echo $string_sumac; ?>"> <img src="images/excel.ico"/></a>    
    </div > 
    <br>
    <div class="row">
        <table  class="table  table-condensed">
            <thead>
                  <tr>   
                     <th>Almacen </th>
                     <th>Clave</th>
                     <th>Producto</th>
                     <th>Agente</th>
                      <th>n_agente</th>
                     <th>Mes</th>
                      <th>Año</th>
                     <th>Precio</th>
                     <th>Cantidad </th>
                     <th>Demanda </th>
                     <th>Total</th>
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
                        <td></td>
                        <td></td>
                         <td></td>
                        <td>TOTAL=</td>
                         <td><?php echo number_format($totalsumac['total'], 2, '.', ',') ?></td>
                        <td><?php echo "$".number_format($totalsuma['total'], 2, '.', ',') ?></td>

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
                        <td> <?php echo $row['nom_age']?> </td>
                        <td title="<?php echo $row['zona']?>"> <?php echo $row['cve_age']?> </td>
                        <td> <?php echo name_mes($row['mes']) ?> </td>
                        <td> <?php echo $row['anio']?> </td>
                        <td> <?php echo $row['precio']?> </td>
                        <td> <?php echo $row['cantidad']?> </td>
                         <td> <?php echo $row['demanda']?> </td>
                        <td> <?php echo  number_format($row['total'], 2, '.', ',') ?> </td>

                    </tr>  
                <?php } ?>
            <tbody> 
        </table>
    </div> 
    
</div>
<?php  require_once('foot.php');?>  




