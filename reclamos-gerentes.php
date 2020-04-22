<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :reclamos-gerentes.php    
 	Fecha  Creacion : 21/09/2016  
	Descripcion  : 
	Copia  archivo   reclamos-gerentes.php   parte  del  Proyecto  Pedidos
	Modificado  Fecha  : 
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
mssql_select_db("AGROVERSA"); 
 require('correos.php');   //funcion para mandar correos
require('calculodv.php');   //funcion para obtener el email de un usuario en especifico
///****FIN    Librerias  Utilizadas  en Cronos 

 IF (isset($_POST['filtrar'])){
   
     $x1 = $_REQUEST['tipo'];  //Serivio o Producto
     $x2= $_REQUEST['cliente'];
     $x3=$_REQUEST['agente'];
     $x3_1= $_REQUEST['fecha1'];
     $x3_2= $_REQUEST['fecha2'];
     $x4=$_REQUEST['status'];
     $x5=$_REQUEST['producto'];
     
     if ($x1==1){   //identificar a donde va realizar la busqueda  
         $enunciadoini = "SELECT * FROM reclamop_vista ";  //Producto
     }else{
         $enunciadoini = "SELECT * FROM reclamoe_vista ";   //Servicio
     }
     
     $enunciadofin = " order by nom_cte";
     
     
     if($x2!=""||$x3!=""||$x3_1!=""||$x3_2!=""||$x4!=""||$x5!=""){
           $otrocomodo="Where ";
     }
     
      $otrocomodo="Where ";
     
           //Incluir solo los agentes del Gerente Actual
          
           
           $valor1=GetSQLValueString($_SESSION['usuario_rol'], "int");
           $condision="cve_gte=".$valor1;
           $string=("$condision");
            $comodo="AND";
      
       
       //CLIENTE
           if ($x2!=""){
           $x2="%".$x2."%";
           $valor2=GetSQLValueString($x2, "text");
           $condision2="cve_cte=".$valor2;
           $string=("$condision $comodo $condision2");
          $comodo2="AND";
       } else{
           $comodo2="";
       }
       
       //AGENTE
         if ($x3!=""){
          //$x3="%".$x3."%";
           $valor3=GetSQLValueString($x3, "int");
          $condision3="n_agente=".$valor3;
           
           $string=("$condision $comodo $condision2 $comodo2 $condision3");
           $comodo3="and";
       } else{
           $comodo3="";
       }
       
       //FECHAS
          if ($x3_1!=""){
          
           $valor3_1=GetSQLValueString($x3_1, "date");
           $valor3_2=GetSQLValueString($x3_2, "date");
           $condision3_1="date_format(fecha,'%Y-%m-%d')>=".$valor3_1." and "."date_format(fecha,'%Y-%m-%d')<=".$valor3_2;
           $string=("$condision $comodo $condision2 $comodo2 $condision3 $comodo3 $condision3_1 ");
            $comodo3_1="and";
       } else{
           $comodo3_1="";
       }
       
       //STATUS
       if ($x4!=""){
           if($x4 == 0){
               $x4= "0";
           }
           if($x4 == 1){
               $x4=1;
           }
           if($x4 == 2){
               $x4=2;
           }
           $valor4=GetSQLValueString($x4, "int");
           $condision4="procede = ".$valor4;
           $string=("$condision $comodo $condision2 $comodo2 $condision3 $comodo3 $condision3_1 $comodo3_1 $condision4");
           
       } else{
         $comodo4="";
       }
       //Producto
       if ($x5!=""){
          
           $valor5=GetSQLValueString($x5, "text");
           $condision5="cve_prod = ".$valor5;
           $string=("$condision $comodo $condision2 $comodo2 $condision3 $comodo3 $condision3_1 $comodo3_1 $condision4 $comodo4 $condision5");
           
       } else{
         
       }
       
       $consultavista = $enunciadoini.$otrocomodo.$string.$enunciadofin;
      //   echo $consultavista;
       $resconsulta = mysqli_query($conecta1, $consultavista) or die (mysqli_error($conecta1));
       
   }
    //Se obtiene los clientes de la tabla reclamoe y reclamop unicos
   $string_agente=sprintf("select (n_agente), nom_agente, cve_gte from reclamoe_vista where cve_gte=%s union  select (n_agente), nom_agente,cve_gte from reclamop_vista where cve_gte=%s",
                   GetSQLValueString($_SESSION['usuario_rol'], "int"),
                    GetSQLValueString($_SESSION['usuario_rol'], "int"));
   
   $query_agente=  mysqli_query($conecta1, $string_agente)or die(mysqli_error($conecta1));
    
    $string_productos="select distinctrow(cve_prod), nom_prod from reclamop";
    $query_productos=  mysqli_query($conecta1, $string_productos)or die(mysqli_error($conecta1))
 
?> 
<div  class="container"> 
         <form method="post" action="reclamos-gerentes.php">
       <p class="titulo2"> Reclamaciones </p>
 <fieldset class="fieldset">
       <legend>Filtros  <?php //echo $x4; ?></legend>
      <label> Tipo Reclamo </label>
         <select name="tipo">
           
             <option value="1" <?php if ($x1==1){ echo 'selected'; }?>>Producto/Calidad</option>
             <option value="2"  <?php if ($x1==2){ echo 'selected'; }?>>Entrega/Servicio</option>
             
         </select><br><br>
    
      <label> Cliente </label><input type="text" name="cliente"/>
         
     <label> Agente </label><select name="agente">
             <option value="">Todos</option>
            <?php 
                WHILE ($registroc=  mysqli_fetch_array($query_agente)){ 
                    if ($x3==$registroc['n_agente']){
                           echo '<option selected value="'.$registroc['n_agente'].'">'.$registroc['nom_agente'].'</option>';
                    }else{
                           echo '<option value="'.$registroc['n_agente'].'">'.$registroc['nom_agente'].'</option>';
                    }
                  
                    
                }?>

             </select><br><br>
     <label> De: </label><input type="date" name="fecha1"/>
     <label> A: </label><input type="date" name="fecha2"/><br>
     
         <label> Status </label>
         <select name="status">
             <option value="">Elija</option>
             <option value="0">Pendiente</option>
               <option value="1">Procede</option>
                <option value="2">No procede</option>
             
             </select>
         
         
          <label> Producto </label>
         <select name="producto">
             <option value="">Todos</option>
            <?php 
                WHILE ($registro=  mysqli_fetch_array($query_productos)){ 
                    if ($x5==$registro['cve_prod']){
                           echo '<option selected value="'.$registro['cve_prod'].'">'.$registro['nom_prod'].'</option>';
                    }else{
                           echo '<option value="'.$registro['cve_prod'].'">'.$registro['nom_prod'].'</option>';
                    }
                  
                    
                }?>

             </select>
    
     <input input type='submit' value='Filtrar' name="filtrar" /> 

 </fieldset>
  </form>     

<fieldset>
  <legend>Tabla de Reportes</legend>
        <div name="tablareporte">
            <table rules="all" border="1">
                <tr>
                  <th>Folio</th>
                  <th>Cliente</th>
                  <th>Agente</th>
                  <th>Fecha</th>	
                  <th>Motivo</th>
                  <th>Doc</th>
                  <th>status</th>
                  <th>Abierto</th>
                  <th>Etapa </th>
                  
                  <th>Ver</th>
                  
                </tr>
                <?php WHILE ($registro1=  mysqli_fetch_array($resconsulta)){  ?>
                    <tr>
                        <td><?php 
                        //identificar a donde va realizar la busqueda  
                          if ($x1==1){   
                                echo $registro1['id_reclamop'];  //Producto
                         }else{
                                echo $registro1['id_reclamoe'];  //Servicio
                             }
                          ?></td>  
                        
                      <td><?php echo $registro1['nom_cte'];?></td>
                      <td><?php echo $registro1['nom_agente'];?></td>
                      <td><?php echo $registro1['fecha'];?></td>		
                      <td>
                       <?php 
                           switch ($registro1['motivo']){
                               case 0:
                                   echo "Efectividad";
                                   break;
                               case 1:
                                   echo "Anomalía empaque (cajas, envases)";
                                   break;
                               case 2:
                                   echo "Anomalía en etiqueta";
                                   break;
                               case 3:
                                   echo "Derrame";
                                   break;
                               case 4:
                                   echo "Asentamiento/suspensibilidad";
                                   break;
                               case 5:
                                   echo "Precipitado";
                                   break;
                               case 6:
                                   echo "Olor no característivo";
                                   break;
                               case 7:
                                   echo "Falta de contenido neto";
                                   break;
  
                           }
                      
                     
                      
                      ?>
                      </td>
                      <td><?php 
                                      if(is_null($registro1['documento'])){
                                            //  echo $registro['documentacion']; 
                                      }else{
                                          echo '<a href='.$registro1['documento'].' target="_blank">Down</a>';
                                       //  echo $registro['documentacion']; 
                                      }
                            ?></td>
                 <td><?php 
                           switch ($registro1['procede']){
                               case 0:
                                   echo "Pendiente";
                                   break;
                               case 1:
                                   echo "Procede";
                                   break;
                               case 2:
                                   echo "No procede";
                                   break;
                           }
                      
                     
                      
                      ?></td>
                 <td>
                 <?php 
                           switch ($registro1['abierto']){
                               case 0:
                                   echo "Abierto";
                                   break;
                               case 1:
                                   echo "Cerrado";
                                   break;
                           }
                      
                     
                      
                      ?>
                 </td>
                 <td>
                      <progress value="<?php 
                    $leyenda = "0";
                           switch ($registro1['etapa']){
                                case 0:
                                    echo 20;
                                    $leyenda = '20%';
                                   break;
                               case 1:
                                   echo 40;
                                   $leyenda = '40%';
                                   break;

                               case 2:
                                   echo 60;
                                   $leyenda = '60%';
                                   break;
                               case 3:
                                   echo 80;
                                   $leyenda = '80%';
                                   break;
                               case 4:
                                   echo 100;
                                   $leyenda = '100%';
                                   break;
                           }
                      
                      ?>" max="100">
                    </progress><?php echo $leyenda?>
                 </td>
                 <td><a href="<?php if ($x1==1){ echo 'productopromotor_detalle.php?dato_id';  }else{  echo 'entregapromoto_detalle.php?dato_id'; } ?>=<?php  if ($x1==1){ echo $registro1['id_reclamop'];  }else{  echo $registro1['id_reclamoe']; } ;?>" target="_blank" onClick="window.open(this.href, this.target, 'width=1000,height=600,scrollbars=yes'); return false;" ><img src="images/edit.png"/></a></td>
                    </tr>
    
                    <?php } ?>
            </table>  
        </div>
</fieldset>

   
</div>
 <?php require_once('foot.php');?>     
