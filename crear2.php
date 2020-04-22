<?php

/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : crear1.php 
 	Fecha  Creacion : 13/10/2016 
	Descripcion  : 
	Copia  del  Proyecto   Proyeccion Alias presupuestos 
 *      Nombre  del  Archivo  Origen : crear1.php  
 *      Servidor : .17   
	Modificado  Fecha  : 
*/
////**Inicio De Session 
session_start();
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
require_once('funciones_proyecciones.php');   
////Agregamos  Modulo Correos
require_once('correos.php');
/////**********Inicio  Codigo  Copiado********************************
mysqli_select_db($conecata1, $database_conecta1);
    
  //  $string_postulados=("Select * from postulado order by id desc, f_alta  desc ");
  //  $sql_postulados=mysqli_query($conecta1,$string_postulados) or die (mysqli_error($conecta1));
 $string=("SELECT * FROM productos  order by desc_prod");
    $mensaje="";
   
   
      
      function mesformato($mes){
          //Mes con formato  1
                 if($mes<10){
                     $mes1='0'.$mes;
                 }else{
                     $mes1=$mes;
                 }
               //
               
          return $mes1;
          
      }
      
      
    ///Agregar datos al Presupuesto
  if (isset($_REQUEST['agregar'])) {
      $string=("SELECT * FROM productos  order by desc_prod");
       $sql3=mysqli_query($conecta1,$string) or die (mysqli_error($conecta1));
      while ($row3=mysqli_fetch_array($sql3))
        {
            
            $totalp1=0;
            $totalp2=0;
            $totalp3=0;

            //Buscar el nombre del producto
            $queryf=sprintf("SELECT desc_prod, precio_prod FROM productos WHERE cve_prod=%s",
                       GetSQLValueString($row3['cve_prod'], "text"));
            
            $sqlf=mysqli_query($conecta1,$queryf) or die (mysqli_error($conecta1));
            $datosf=  mysqli_fetch_assoc($sqlf);
            $nombre_producto=$datosf['desc_prod'];
            $precio_producto=$datosf['precio_prod'];
          
             //Mes con formato  1
                 if($_SESSION['mes1']<10){
                     $mes1='0'.$_SESSION['mes1'];
                 }else{
                     $mes1=$_SESSION['mes1'];
                 }
               //
                  //Mes con formato  2
                 if($_SESSION['mes2']<10){
                     $mes2='0'.$_SESSION['mes2'];
                 }else{
                     $mes2=$_SESSION['mes2'];
                 }
               //
                  //Mes con formato  3
                 if($_SESSION['mes3']<10){
                     $mes3='0'.$_SESSION['mes3'];
                 }else{
                     $mes3=$_SESSION['mes3'];
                 }
               //

              $nombreinputmes1=trim($_SESSION['anio1'].$mes1.$row3['cve_prod']);
              $nombreinputmes2=trim($_SESSION['anio2'].$mes2.$row3['cve_prod']);
              $nombreinputmes3=trim($_SESSION['anio3'].$mes3.$row3['cve_prod']);
            //  echo '<br>'.$_POST[$nombreinputmes1];
            //  echo '<br>'.$datosf['desc_prod'];
               

             // Extrae apartir del nombre de objeto la clave mes y anio del dato
               $extraeclave1=substr($nombreinputmes1,6,10);
               $extraemes1=substr($nombreinputmes1,4,2);
               $extraeanio1=substr($nombreinputmes1,0,4);
               
               $extraeclave2=substr($nombreinputmes2,6,10);
               $extraemes2=substr($nombreinputmes2,4,2);
               $extraeanio2=substr($nombreinputmes2,0,4);
               
               $extraeclave3=substr($nombreinputmes3,6,10);
               $extraemes3=substr($nombreinputmes3,4,2);
               $extraeanio3=substr($nombreinputmes3,0,4);

               ///Totales 
             $totalp1=$_POST[$nombreinputmes1]*$precio_producto;
             $totalp2=$_POST[$nombreinputmes2]*$precio_producto;
             $totalp3=$_POST[$nombreinputmes3]*$precio_producto;

             // echo $totalp1.'<br>';
             ///////////////////////////////////   Mes1
             if ($totalp1>0){
                    echo $nombreinputmes1.':'.$totalp1;
                   $existe=  buscar_datoproy_centi($_SESSION['usuario_agente'], $_SESSION['cve_alma'], $_SESSION['mes1'], $_SESSION['anio1'], $extraeclave1);
                 if ($existe==1){
                         ///update
                           if ($_POST[$nombreinputmes1]==1){
                               ///la cantida 1 nos inica que el valor a actualizar debe ser 0
                               $cero=0;
                                $query1=sprintf("UPDATE  pronostico set cantidad=%s, demanda=%s where cve_age=%s and  cve_alma=%s and mes=%s and anio=%s and cve_prod=%s",
                                            GetSQLValueString($cero, "int"),
                                            GetSQLValueString($cero, "int"),
                                           GetSQLValueString($_SESSION['usuario_agente'], "int"),  
                                           GetSQLValueString($_SESSION['cve_alma'], "text"),  
                                           GetSQLValueString($_SESSION['mes1'], "int"),  
                                          GetSQLValueString($_SESSION['anio1'], "int"),  

                                           GetSQLValueString($extraeclave1, "text"));
                           }else{
                               //Asignarle el valor que trae la variable
                               list($datosp1_cantidad,$datosp1_demanda)=buscar_datoproy($_SESSION['usuario_agente'], $_SESSION['cve_alma'], $_SESSION['mes1'], $_SESSION['anio1'],$extraeclave1);
                               
                               
                               ///Si no no tiene capturado el valor de demanda hay que asigarle el mismo valor que el de cantidad
                               if($datosp1_demanda>0){
                                   $demandanew=$datosp1_demanda;
                               }else{
                                   $demandanew=$_POST[$nombreinputmes1];
                               }
                               
                                 $query1=sprintf("UPDATE  pronostico set cantidad=%s, demanda=%s where cve_age=%s and  cve_alma=%s and mes=%s and anio=%s and cve_prod=%s",
                                            GetSQLValueString($_POST[$nombreinputmes1], "int"),
                                             GetSQLValueString($demandanew, "int"),
                                           GetSQLValueString($_SESSION['usuario_agente'], "int"),  
                                           GetSQLValueString($_SESSION['cve_alma'], "text"),  
                                           GetSQLValueString($_SESSION['mes1'], "int"),  
                                          GetSQLValueString($_SESSION['anio1'], "int"),  

                                           GetSQLValueString($extraeclave1, "text"));
                               
                           }
                     
                 }else{
                       ///insert
                       $query1=sprintf("INSERT INTO  pronostico set cve_alma=%s,nom_alma=%s, cve_age=%s, zona=%s, anio=%s ,mes=%s,cve_prod=%s, nom_prod=%s,cantidad=%s, demanda=%s, precio=%s, total=%s",
                                    GetSQLValueString($_SESSION['cve_alma'], "text"),  
                                    GetSQLValueString($_SESSION['nombre_alma'], "text"),  
                                    GetSQLValueString($_SESSION['usuario_agente'], "int"),  
                                    GetSQLValueString($_SESSION['usuario_nombre'], "text"), 
                                    GetSQLValueString($_SESSION['anio1'], "int"),  
                                    GetSQLValueString($_SESSION['mes1'], "int"),  
                                    GetSQLValueString($extraeclave1, "text"),  
                                   GetSQLValueString($nombre_producto, "text"), 
                                   GetSQLValueString($_POST[$nombreinputmes1], "int"),
                                   GetSQLValueString($_POST[$nombreinputmes1], "int"),
                                   GetSQLValueString($precio_producto, "double"),
                                   GetSQLValueString($totalp1, "double"));  
                 }
                   @mysqli_query($conecta1,$query1) or die (mysqli_error($conecta1));
               //  echo '<br>'.$query1;
              }

               ///////////////////////////////////   Mes2
              if ($totalp2>0){
                   ///nos indica que este producto trae valor
                   echo $nombreinputmes2.':'.$totalp2;
                 $existe2=  buscar_datoproy_centi($_SESSION['usuario_agente'], $_SESSION['cve_alma'], $_SESSION['mes2'], $_SESSION['anio2'], $extraeclave2);
                 if ($existe2==1){
                         ///update
                           if ($_POST[$nombreinputmes2]==1){
                               ///la cantida 1 nos inica que el valor a actualizar debe ser 0
                               $cero=0;
                                $query2=sprintf("UPDATE  pronostico set cantidad=%s, demanda=%s where cve_age=%s and  cve_alma=%s and mes=%s and anio=%s and cve_prod=%s",
                                            GetSQLValueString($cero, "int"),
                                            GetSQLValueString($cero, "int"),
                                           GetSQLValueString($_SESSION['usuario_agente'], "int"),  
                                           GetSQLValueString($_SESSION['cve_alma'], "text"),  
                                           GetSQLValueString($_SESSION['mes2'], "int"),  
                                          GetSQLValueString($_SESSION['anio2'], "int"),  

                                           GetSQLValueString($extraeclave2, "text"));
                           }else{
                               //Asignarle el valor que trae la variable
                                list($datosp2_cantidad,$datosp2_demanda)=buscar_datoproy($_SESSION['usuario_agente'], $_SESSION['cve_alma'], $_SESSION['mes2'], $_SESSION['anio2'],$extraeclave2);
                               
                               
                               ///Si no no tiene capturado el valor de demanda hay que asigarle el mismo valor que el de cantidad
                               if($datosp2_demanda>0){
                                   $demandanew=$datosp2_demanda;
                               }else{
                                   $demandanew=$_POST[$nombreinputmes2];
                               }
                                 $query2=sprintf("UPDATE  pronostico set cantidad=%s,demanda=%s where cve_age=%s and  cve_alma=%s and mes=%s and anio=%s and cve_prod=%s",
                                            GetSQLValueString($_POST[$nombreinputmes2], "int"),
                                              GetSQLValueString($demandanew, "int"),
                                           GetSQLValueString($_SESSION['usuario_agente'], "int"),  
                                           GetSQLValueString($_SESSION['cve_alma'], "text"),  
                                           GetSQLValueString($_SESSION['mes2'], "int"),  
                                          GetSQLValueString($_SESSION['anio2'], "int"),  

                                           GetSQLValueString($extraeclave2, "text"));
                               
                           }
                     
                 }else{
                       ///insert
                       $query2=sprintf("INSERT INTO  pronostico set cve_alma=%s,nom_alma=%s, cve_age=%s, zona=%s, anio=%s ,mes=%s,cve_prod=%s, nom_prod=%s, cantidad=%s,demanda=%s, precio=%s, total=%s",
                                    GetSQLValueString($_SESSION['cve_alma'], "text"),  
                                    GetSQLValueString($_SESSION['nombre_alma'], "text"),  
                                    GetSQLValueString($_SESSION['usuario_agente'], "int"),  
                                    GetSQLValueString($_SESSION['usuario_nombre'], "text"), 
                                    GetSQLValueString($_SESSION['anio2'], "int"),  
                                    GetSQLValueString($_SESSION['mes2'], "int"),  
                                    GetSQLValueString($extraeclave2, "text"),  
                                   GetSQLValueString($nombre_producto, "text"), 
                                   GetSQLValueString($_POST[$nombreinputmes2], "int"),
                                   GetSQLValueString($_POST[$nombreinputmes2], "int"),
                                   GetSQLValueString($precio_producto, "double"),
                                   GetSQLValueString($totalp2, "double"));  
                 }
                  
                  
                @mysqli_query($conecta1,$query2) or die (mysqli_error($conecta1));
                //   echo '<br>'.$query2;
              }

               ///////////////////////////////////   Mes3
              if ($totalp3>0){
                  ///nos indica que este producto trae valor
                   echo $nombreinputmes3.':'.$totalp3;
                 $existe3=  buscar_datoproy_centi($_SESSION['usuario_agente'], $_SESSION['cve_alma'], $_SESSION['mes3'], $_SESSION['anio3'], $extraeclave3);
                 if ($existe3==1){
                         ///update
                           if ($_POST[$nombreinputmes3]==1){
                               ///la cantida 1 nos inica que el valor a actualizar debe ser 0
                               $cero=0;
                                $query3=sprintf("UPDATE  pronostico set cantidad=%s, demanda=%s where cve_age=%s and  cve_alma=%s and mes=%s and anio=%s and cve_prod=%s",
                                            GetSQLValueString($cero, "int"),
                                           GetSQLValueString($cero, "int"),
                                           GetSQLValueString($_SESSION['usuario_agente'], "int"),  
                                           GetSQLValueString($_SESSION['cve_alma'], "text"),  
                                           GetSQLValueString($_SESSION['mes3'], "int"),  
                                          GetSQLValueString($_SESSION['anio3'], "int"),  

                                           GetSQLValueString($extraeclave3, "text"));
                           }else{
                               //Asignarle el valor que trae la variable
                                list($datosp3_cantidad,$datosp3_demanda)=buscar_datoproy($_SESSION['usuario_agente'], $_SESSION['cve_alma'], $_SESSION['mes3'], $_SESSION['anio3'],$extraeclave3);
                               
                               
                               ///Si no no tiene capturado el valor de demanda hay que asigarle el mismo valor que el de cantidad
                               if($datosp3_demanda>0){
                                   $demandanew=$datosp3_demanda;
                               }else{
                                   $demandanew=$_POST[$nombreinputmes3];
                               }
                                 $query3=sprintf("UPDATE  pronostico set cantidad=%s,demanda=%s where cve_age=%s and  cve_alma=%s and mes=%s and anio=%s and cve_prod=%s",
                                            GetSQLValueString($_POST[$nombreinputmes3], "int"),
                                              GetSQLValueString($demandanew, "int"),
                                           GetSQLValueString($_SESSION['usuario_agente'], "int"),  
                                           GetSQLValueString($_SESSION['cve_alma'], "text"),  
                                           GetSQLValueString($_SESSION['mes3'], "int"),  
                                          GetSQLValueString($_SESSION['anio3'], "int"),  

                                           GetSQLValueString($extraeclave3, "text"));
                               
                           }
                     
                 }else{
                       ///insert
                       $query3=sprintf("INSERT INTO  pronostico set cve_alma=%s,nom_alma=%s, cve_age=%s, zona=%s, anio=%s ,mes=%s,cve_prod=%s, nom_prod=%s, cantidad=%s,demanda=%s, precio=%s, total=%s",
                                    GetSQLValueString($_SESSION['cve_alma'], "text"),  
                                    GetSQLValueString($_SESSION['nombre_alma'], "text"),  
                                    GetSQLValueString($_SESSION['usuario_agente'], "int"),  
                                    GetSQLValueString($_SESSION['usuario_nombre'], "text"), 
                                    GetSQLValueString($_SESSION['anio3'], "int"),  
                                    GetSQLValueString($_SESSION['mes3'], "int"),  
                                    GetSQLValueString($extraeclave3, "text"),  
                                   GetSQLValueString($nombre_producto, "text"), 
                                   GetSQLValueString($_POST[$nombreinputmes3], "int"),
                                   GetSQLValueString($_POST[$nombreinputmes3], "int"),
                                   GetSQLValueString($precio_producto, "double"),
                                   GetSQLValueString($totalp3, "double"));  
                 }
                  
                  
                @mysqli_query($conecta1,$query3) or die (mysqli_error($conecta1));
               //     echo '<br>'.$query3;
              }  
            //  echo '<br>'.$query1;
            //  echo ' <HR width=70% align="left">';
             
          //   
               
         
        }
         $mensaje="DATOS GUARDADOS";
            $string_total_mes_1 = sprintf("select  sum(cantidad) as  Mes1  from   pedidos.pronostico    where  cve_age =%s  and   mes =%s    and  anio =%s",
                                GetSQLValueString($_SESSION['usuario_agente'], "text"),
                                GetSQLValueString($_SESSION['mes1'], "int"),
                                GetSQLValueString($_SESSION['anio1'], "int"));
         
             $string_total_mes_2 = sprintf("select  sum(cantidad) as  Mes2  from   pedidos.pronostico    where  cve_age =%s  and   mes =%s    and  anio =%s",
                                GetSQLValueString($_SESSION['usuario_agente'], "text"),
                                GetSQLValueString($_SESSION['mes2'], "int"),
                                GetSQLValueString($_SESSION['anio2'], "int"));
             
            $string_total_mes_3 = sprintf("select  sum(cantidad) as  Mes3  from   pedidos.pronostico    where  cve_age =%s  and   mes =%s    and  anio =%s",
                                GetSQLValueString($_SESSION['usuario_agente'], "text"),
                                GetSQLValueString($_SESSION['mes3'], "int"),
                                GetSQLValueString($_SESSION['anio3'], "int"));
            
            $qery_mes_1 = mysqli_query($conecta1, $string_total_mes_1);
            $fetch_mes_1 = mysqli_fetch_array($qery_mes_1);
            
            $qery_mes_2 = mysqli_query($conecta1, $string_total_mes_2);
            $fetch_mes_2 = mysqli_fetch_array($qery_mes_2);
            
            
            $qery_mes_3 = mysqli_query($conecta1, $string_total_mes_3);
            $fetch_mes_3 = mysqli_fetch_array($qery_mes_3);
            
            ///***Sumatoria de todas las Proyecciones realizadas
            $Tota_suma =  $fetch_mes_1['Mes1'] ."   ".$fetch_mes_2['Mes2']."  ".$fetch_mes_3['Mes3'];
         
         
         ///****Envio de Proyeccion 
        $fech_now =date("d/m/Y  H:i:s"); 
         ////*****Informacion a Enviar al  Gerente
       $STRING_CADENA_Html_001 = "<table><thead><th><h4>Buen Día se ha Generado  Una  Proyeccion.</h4></th><thead>";        
       $STRING_CADENA_Html_002 = "<tbody><tr><td><h4>Nombre:".$_SESSION['usuario_nombre']."</td><td><h4>Fecha en que se guardo la proyección: ".$fech_now."</h4></td></tr>"       ;
       $STRING_CADENA_Html_003 = "<tr><td><h4> N# Mes".$_SESSION['mes1']." : ".number_format($fetch_mes_1['Mes1'], 2, '.', ',') ."</h4></td><td><h4>N# Mes ".$_SESSION['mes2']." : ".number_format($fetch_mes_2['Mes2'], 2, '.', ',') ."</h4></td><td><h4>N# Mes ".$_SESSION['mes3']." : ".number_format($fetch_mes_3['Mes3'], 2, '.', ',')."</h4></td></tr>";
       /*$STRING_CADENA_Html_004 = "<tr><td>Codigo Producto: ".$fetch_adi['codigo_pro']."</td><td>Nombre Producto: ".$fetch_adi['nom_pro']."</td></tr>";
       $STRING_CADENA_Html_005 = "<tr><td>Fecha Solicitud: ".$fetch_adi['fecha_sol']."</td><td>Fecha Requerimiento:".$fetch_adi['fecha_rq']."</td></tr>"; 
       $STRING_CADENA_Html_006= "<tr><td>Precio Solicitado Por Venta :".$fetch_adi['precio_sol_pv']."</td><td>Cantidad Requerida :".$fetch_adi['cant_req']."</td></tr>";*/ 
       $TRING_CADENA_Html_007 = "</tbody></table>";
       /////****
       //******************        
       ////***Armamos la informacion Html  a Enviar
       $strin_Fina_HTML =$STRING_CADENA_Html_001.$STRING_CADENA_Html_002.$STRING_CADENA_Html_003.$TRING_CADENA_Html_007;//$STRING_CADENA_Html_003.$STRING_CADENA_Html_004.$STRING_CADENA_Html_005.$STRING_CADENA_Html_006.$TRING_CADENA_Html_007;

       ////****Mandamos  Correo 
       correos("Proyecciones", "Nueva Proyeccion","bmesta@agroversa.com.mx","fpendones@agroversa.com.mx", $strin_Fina_HTML);
         
  }   
    
 
 $archivo=basename($_SERVER['PHP_SELF']); 
/////**********Fin   Codigo  Copiado********************************
?> 
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script> 
<script type="text/javascript">


function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}

function validar(e) {
    tecla = (document.all)?e.keyCode:e.which;
    if (tecla==8) return true;
    patron = /\d/;
    te = String.fromCharCode(tecla);
    return patron.test(te); 
} 

function validarforma(){
	
	dato2=document.getElementById("clave").value;
	var dato3
	dato3=dato2.length
	 
        if (dato3<=1){
      
        
            alert("Hay que elegir el producto");
           // pagina.productos.focus();
            return false;
        }
		
			return true;
	
    } 

$(document).ready(function(){
    $("form").submit(function(){
        alert("Proyección Guardada.");
    });
});
</script>

<div   class="container">

	 <?php require_once 'submenu_proyeccion.php'  ?>
	<br>		
    <!--Contenedor   Titles--> 
    <div class="row">
      <?php  
        echo '<p class="text-left" ><a class ="btn btn-success" href ="crear1.php"><----Cambiar  Almacen</a></p>   ';
        echo '<p class="text-center"> Almacen : '.$_SESSION['cve_alma'].' '.$_SESSION['nombre_alma'].'  </p> ';
         echo '<p class="text-center">Asesor  : '.$_SESSION['usuario_nombre'].' </p> ';
       ?> 
    </div>
    <div class="row">
    <form class="form-group" name="form1" method="post" action="<?php echo $archivo ?>" onsubmit="return validarforma()"> 
        
          <?php
                  if ($mensaje!=""){
                      echo  '<div class="alert alert-danger">';
                      echo $mensaje.':'.date("F j, Y, g:i a");
                      echo '</div>';
                  }
              
              
              ?>
             
           <input type="submit" id="agregar" name="agregar"  class="btn btn-success" value="Guardar" >   
           <table class="table table-condensed"> <!-- id="dataTables-agregaproyecccion-agente"-->
            <thead>
                    <tr>  
                        <th>Clasifica</th>  
                        <th>Producto</th>
                        <th><?php echo name_mes($_SESSION['mes1'])." ".$_SESSION['anio1']."<br>";?> </th>
                        <th><?php echo name_mes($_SESSION['mes2'])." ".$_SESSION['anio2']."<br>";?></th>
                        <th><?php echo name_mes($_SESSION['mes3'])." ".$_SESSION['anio3']."<br>";?></th>
                    </tr> 
            </thead>
            <tbody> 
                        <?php

                                 echo "<tr><td>". $_SESSION["usuario_agente"] ."</t></tr>";
                                $a=0;
                                if ($_SESSION['tipo']==5){
                                    ///Mostrar solo los productos para maquila
                                    $string=("SELECT * FROM productos  where empresa=1 order by clasifica,desc_prod");
                                }else{
                                    ///Excluir los productos de MAquila
                                      $string=("SELECT * FROM productos  where empresa=0 order by clasifica,desc_prod");
                                }
              
                                $sql2=mysqli_query($conecta1,$string) or die (mysqli_error($conecta1));
                                while ($row2=mysqli_fetch_array($sql2))
                                  {

                                    //Mes con formato  1
                                      if($_SESSION['mes1']<10){
                                          $mes1='0'.$_SESSION['mes1'];
                                      }else{
                                          $mes1=$_SESSION['mes1'];
                                      }
                                    //
                                       //Mes con formato  2
                                      if($_SESSION['mes2']<10){
                                          $mes2='0'.$_SESSION['mes2'];
                                      }else{
                                          $mes2=$_SESSION['mes2'];
                                      }
                                    //
                                       //Mes con formato  3
                                      if($_SESSION['mes3']<10){
                                          $mes3='0'.$_SESSION['mes3'];
                                      }else{
                                          $mes3=$_SESSION['mes3'];
                                      }
                                    //

                                    $nombreinputmes1=$_SESSION['anio1'].$mes1.$row2['cve_prod'];
                                    $nombreinputmes2=$_SESSION['anio2'].$mes2.$row2['cve_prod'];
                                    $nombreinputmes3=$_SESSION['anio3'].$mes3.$row2['cve_prod'];
                                    $hnombreinputmes1='H'.$nombreinputmes1;
                                    $hnombreinputmes2='H'.$nombreinputmes2;
                                    $hnombreinputmes3='H'.$nombreinputmes3; 

                                  // Extrae apartir del nombre de objeto la clave mes y anio del dato
                                    $extraeclave1=substr($nombreinputmes1,6,10);
                                    $extraemes1=substr($nombreinputmes1,4,2);
                                    $extraeanio1=substr($nombreinputmes1,0,4);

                                    ///Actualizar los valores que se tiene actualmente en la base de datos
                                      //Buscar en el pronostico si ya ha capturado datos para los meses activos
                                      $query_p1=sprintf("SELECT cantidad FROM pronostico WHERE cve_alma=%s and cve_age=%s and mes=%s and anio=%s and cve_prod=%s",
                                               GetSQLValueString($_SESSION['cve_alma'], "text"),  
                                               GetSQLValueString($_SESSION['usuario_agente'], "int"),  
                                               GetSQLValueString($_SESSION['mes1'], "int"),  
                                               GetSQLValueString($_SESSION['anio1'], "int"),  
                                               GetSQLValueString($row2['cve_prod'], "text")); 
                                       $sql_p1=mysqli_query($conecta1,$query_p1) or die (mysqli_error($conecta1));
                                       $datos_p1=  mysqli_fetch_assoc($sql_p1);


                                     $query_p1=sprintf("SELECT cantidad FROM pronostico WHERE cve_alma=%s and cve_age=%s and mes=%s and anio=%s and cve_prod=%s",
                                               GetSQLValueString($_SESSION['cve_alma'], "text"),  
                                               GetSQLValueString($_SESSION['usuario_agente'], "int"),  
                                               GetSQLValueString($_SESSION['mes2'], "int"),  
                                               GetSQLValueString($_SESSION['anio2'], "int"),  
                                               GetSQLValueString($row2['cve_prod'], "text")); 
                                       $sql_p1=mysqli_query($conecta1,$query_p1) or die (mysqli_error($conecta1));
                                       $datos_p2=  mysqli_fetch_assoc($sql_p1);

                                     $query_p1=sprintf("SELECT cantidad FROM pronostico WHERE cve_alma=%s and cve_age=%s and mes=%s and anio=%s and cve_prod=%s",
                                               GetSQLValueString($_SESSION['cve_alma'], "text"),  
                                               GetSQLValueString($_SESSION['usuario_agente'], "int"),  
                                               GetSQLValueString($_SESSION['mes3'], "int"),  
                                               GetSQLValueString($_SESSION['anio3'], "int"),  
                                               GetSQLValueString($row2['cve_prod'], "text")); 
                                       $sql_p1=mysqli_query($conecta1,$query_p1) or die (mysqli_error($conecta1));
                                        $datos_p3=  mysqli_fetch_assoc($sql_p1);
                                    ///




                                   if ($a++ %2){
                                     echo '<tr class="alt">';
                                    }else{
                                     echo '<tr>';
                                      }
                                     echo '   <td>'.$row2['clasifica'].'</td>';    
                                  echo '   <td>'.$row2['cve_prod'].' '.$row2['desc_prod'].'</td>';
                                  echo '   <td> <input class ="form-control"  type="text" name="'.trim($nombreinputmes1).'"  size="10" value="'.$datos_p1['cantidad'].'" onkeypress="return validar(event)"/> </td>';
                                  echo'    <td> <input class ="form-control" type="text" name="'.trim($nombreinputmes2).'"  size="10" value="'.$datos_p2['cantidad'].'" onkeypress="return validar(event)"/> </td>';
                                   echo '  <td> <input class ="form-control" type="text" name="'.trim($nombreinputmes3).'"  size="10" value="'.$datos_p3['cantidad'].'" onkeypress="return validar(event)"/> </td>';
                                   echo '  </tr>';        
                                  }
                         ?>
            </tbody>
        </table>   
           <input type="submit" id="agregar" name="agregar"  class="btn btn-success" value="Guardar" /> 
    
    </form>       
       
    </div>

</div>
<?php  require_once('foot.php');?>  