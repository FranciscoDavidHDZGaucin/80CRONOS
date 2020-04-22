<?php
/*
********   INFORMACION ARCHIVO ***************** 
  Nombre  Archivo :  reporte9.php  
  Fecha  Creacion : 21/09/2016
  Descripcion  : 
  Copia  archivo reporte9.php      parte  del  Proyecto  Pedidos
 *      Escript  necesario Js calendario/popcalendar.js
 *      
  Modificado  Fecha  :
 *  ******* 21/09/2016:
 *         Se Modifico    las  conexiones  De :mysql_query($string_xcredito,$conecta1) or die (mysql_error()); 
 *         A:  mysqli_query($conecta1,$string_xcredito) or die (mysql_error($conecta1));  
 *         Se  diseño especial para el  archivo    reporte9.php 
 *         dado a que  no se podian   cargara las graficas  correspondientes  con el archivo  foot.php 
 *         por  lo que a  hora  se implementa el archivo  foot_gerentes.php  para el  correcto  
 *         funcionamiento del archivo  reporte9.php.
 *         Tambien  se  modifico    los   mysql_fecht_array   a   mysqli_fech_array     
 *         se  cambio   el  pop recogedor.php a   cronos-recogedor.php    
 *  *******28/04/2017  SE  Modificaron  las tablas  con  base en la  plataforma  Pedidos 
                        Nobre del Escrip   con el que se aplicaron los cambios :  http://192.168.101.5/sistemas/pedidos/reporte_grafica.php        
                        
 * ******** 06/05/2017  Se    TOMA EN  CUENTA  LA  mAQUILA    WHERE n_agente !=99  SE  QUITO  DE LA  CADENA  
 *              SELECT sum(gtotal)as total FROM grafica_logistica_entregas_null WHERE n_agente !=99 Where fecha_alta>='2017-05-01' and fecha_alta<='2017-05-06'
 *          
 *  */
///****Inicio   Librerias  Utilizadas  en Cronos
///****Cabecera Cronos 

 session_start ();
 
 switch ($_SESSION["usuario_rol"]) {
    case 10:

        require_once 'header_direccion.php';
        break;
     case 69:
         require_once 'header_inteligencia.php';

        break;

    default:
        require_once 'header_gerentes.php';
        break;
}


/*****Sintetizador de  Datos en el proyecto  pedidos   se  utiliza el   
formtato_datos2.php   pero     se  analiso   y son   identicos  los  archivos 
 por lo que se   dejo el  formato_datos.php  
 *  */
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***Conexion Mysql  
require_once('Connections/conecta1.php');

////*************************
require('correos.php');   //funcion para mandar correos
///**************************************
require('buscar_email.php');   //funcion para obtener el email de un usuario en especifico
///*********************************
$suma_xcredito=0;
$suma_xvta=0;
$suma_xpi=0;        
$suma_xe=0;
$suma_xc=0;        
$suma_xmt=0;        
$suma_listas=0;        
///********************************************************
 if(isset($_REQUEST['filtrar'])){
        
        //Se realizo un filtro
        $tipo=$_REQUEST['tipo'];
        $fecha1=$_REQUEST['fecha1'];
        $fecha2=$_REQUEST['fecha2'];
        $producto=$_REQUEST['producto'];
        $agente=$_REQUEST['agente'];
        $folio=$_REQUEST['folio'];
        
      
       
        //Conocer a que tabla se esta dirigiendo
        if ($tipo==1){
            //Pedidos
            $tabla="pedidos1";
            $campo="n_remision";
        }else{
            //Facturas
            $tabla="factura";
            $campo="n_factura";
        }
        
        if ($_SESSION["usuario_rol"]==69 || $_SESSION["usuario_rol"]==10 ){
            //acceso inteligencia o dircomercial
           $esgerente=0;
        }else{
             $esgerente=1;
        }
       // echo 'esgerente='.$esgerente.'<br>';
        
         if($fecha1!=""||$fecha2!="" || $producto!="" || $agente!="" || $folio!="" || $esgerente==1){
                        $otrocomodo=" Where ";
                        $otro2=" and ";
           }else{
                $otrocomodo="";
                $otro2="";
           }
           
         switch ($tipo) {
             case "1":   ///Consulta a Pedidos 
                 
                  $enunciadoini1="SELECT * FROM $tabla WHERE cant_falta>0 ";
                 
                    $string_xcredito1="SELECT sum(gtotal)as total FROM grafica_xcredito";
                    $string_xvta1="SELECT sum(gtotal)as total FROM grafica_xvta";
                    $string_xpi1="SELECT sum(gtotal)as total FROM grafica_xpi";
                    $string_xe1="SELECT sum(gtotal)as total FROM grafica_xe";
                    $string_xc1="SELECT sum(gtotal)as total FROM grafica_xc";
                    $string_xmt1="SELECT sum(gtotal)as total FROM grafica_xmt";
                    
                     $string_xsp1="SELECT sum(gtotal)as total FROM grafica_xsp";
                     $string_xmq1="SELECT sum(gtotal)as total FROM grafica_xmq";
                     $string_xep1="SELECT sum(gtotal)as total FROM grafica_xep";
                     $string_xma1="SELECT sum(gtotal)as total FROM grafica_xma";
                    ///Cadena  para definir  la  sumatoria de las  entregas
                    $string_entregas_sin_filtro = "SELECT sum(gtotal)as total FROM grafica_logistica_entregas_null ";///WHERE n_agente !=99
                    ////**Cadena consulta de Ubicacion 
                    $string_xu ="SELECT sum(gtotal)as total FROM grafica_xu";
                    $string_listas1="SELECT sum(gtotal)as total FROM grafica_listas"; 
             
                 
                  //por Fecha de Folio
                if ($fecha1!=""||$fecha2!=""){

                       $valor1=GetSQLValueString($fecha1, "date");
                       $valor2=GetSQLValueString($fecha2, "date");
                       $condision="fecha_alta>=".$valor1." and fecha_alta<=".$valor2;
                       $string=("$condision");
                        $comodo=" and ";
                   } else{
                       $comodo="";
                   }
                 
                 if ($producto!=""){

                       $valor3=GetSQLValueString($producto, "text");
                     
                       $condision2=" cve_prod=".$valor3;
                       $string=("$condision $comodo $condision2");
                        $comodo2=" and ";
                   } else{
                       $comodo2="";
                   }
                 
                  if ($agente!=0){

                       $valor4=GetSQLValueString($agente, "int");
                     
                       $condision3=" n_agente=".$valor4;
                       $string=("$condision $comodo $condision2 $comodo2 $condision3");
                        $comodo3=" and ";
                   } else{
                       $comodo3="";
                   }
                   
                   
                    if ($folio!=0){

                       $valor5=GetSQLValueString($folio, "int");
                         $campo="n_remision";
                       $condision4=$campo."=".$valor5;
                       $string=("$condision $comodo $condision2 $comodo2 $condision3 $comodo3 $condision4");
                        $comodo4=" and ";
                   } else{
                       $comodo4="";
                   }
                   
                    if ($esgerente==1 ){    ///Si no es inteligencia o Direccion aplica el filtro y que solo muestre los pedidos de la zona

                       $valor6=GetSQLValueString($_SESSION["usuario_rol"], "int");
                         $campo="cve_gte";
                       $condision5=$campo."=".$valor6;
                       $string=("$condision $comodo $condision2 $comodo2 $condision3 $comodo3 $condision4 $comodo4 $condision5");
                        $comodo5=" and ";
                   } else{
                       $comodo5="";
                   }
                   
                   
                   
                $string_filtro = $enunciadoini1.$otro2.$string;
                    
               $string_xcredito=$string_xcredito1.$otrocomodo.$string;
                $string_xvta=$string_xvta1.$otrocomodo.$string;
              $string_xpi=$string_xpi1.$otrocomodo.$string;
                $string_xe=$string_xe1.$otrocomodo.$string;
                $string_xc=$string_xc1.$otrocomodo.$string;
                $string_xmt=$string_xmt1.$otrocomodo.$string;
                    
                 $string_xsp=$string_xsp1.$otrocomodo.$string;
               $string_xmq=$string_xmq1.$otrocomodo.$string;
             $string_xep=$string_xep1.$otrocomodo.$string;
             $string_xma=$string_xma1.$otrocomodo.$string;
                    
                    ///***Generamos  cadena con filtro 
               $string_entrega_con_filtro =$string_entregas_sin_filtro.$otrocomodo.$string;
                    ///**************************
                    ////**Generamos cadena  con  filtro Ubicacion 
                $string_xUbi=$string_xu.$otrocomodo.$string;
               $string_listas=$string_listas1.$otrocomodo.$string;
                    
              ///  echo '<br>'.    $string_listas=$string_listas1.$otrocomodo.$string; 
                    
                      $query_xcredito=mysqli_query($conecta1,$string_xcredito) or die (mysqli_error($conecta1));//mysql_query($string_xcredito,$conecta1) or die (mysql_error()); 
                        $query_xvta=mysqli_query($conecta1,$string_xvta) or die (mysqli_error($conecta1));//mysql_query($string_xvta,$conecta1) or die (mysql_error()); 
                        $query_xpi=mysqli_query($conecta1,$string_xpi) or die (mysqli_error($conecta1));//mysql_query($string_xpi,$conecta1) or die (mysql_error());  
                        $query_xe=mysqli_query($conecta1,$string_xe) or die (mysqli_error($conecta1));//mysql_query($string_xe,$conecta1) or die (mysql_error()); 
                        $query_xc=mysqli_query($conecta1,$string_xc) or die (mysqli_error($conecta1));//mysql_query($string_xc,$conecta1) or die (mysql_error()); 
                        $query_xmt=mysqli_query($conecta1,$string_xmt) or die (mysqli_error($conecta1));///mysql_query($string_xmt,$conecta1) or die (mysql_error());
                        
                          $query_xsp=mysqli_query($conecta1,$string_xsp) or die (mysqli_error($conecta1));///mysql_query($string_xsp,$conecta1) or die (mysql_error());
                          $query_xmq=mysqli_query($conecta1,$string_xmq) or die (mysqli_error($conecta1));//mysql_query($string_xmq,$conecta1) or die (mysql_error());
                          $query_xep=mysqli_query($conecta1,$string_xep) or die (mysqli_error($conecta1));///mysql_query($string_xep,$conecta1) or die (mysql_error());
                          $query_xma=  mysqli_query($conecta1,$string_xma) or die (mysqli_error($conecta1)); ///mysql_query($string_xma,$conecta1) or die (mysql_error());
                        
                         ///*** Realizamos  el  qery de las  entregas 
                        $query_entregas = mysqli_query($conecta1, $string_entrega_con_filtro)or die (mysqli_error($conecta1));
                         
                         ////**Realizamos  ele qery  pata  las  Ubicaciones
                        $qery_xu =mysqli_query($conecta1, $string_xUbi)or die (mysqli_error($conecta1)); ///

                        $query_listas=  mysqli_query($conecta1, $string_listas)or die (mysqli_error($conecta1));  ///mysql_query($string_listas,$conecta1) or die (mysql_error());  

                        $datos_xcredito=  mysqli_fetch_assoc($query_xcredito);
                        $datos_xvta=  mysqli_fetch_assoc($query_xvta);
                        $datos_xpi=  mysqli_fetch_assoc($query_xpi);
                        $datos_xe=  mysqli_fetch_assoc($query_xe);        
                        $datos_xc =  mysqli_fetch_assoc($query_xc);       
                        $datos_xmt=  mysqli_fetch_assoc($query_xmt);
                        
                         $datos_xsp=  mysqli_fetch_assoc($query_xsp);
                         $datos_xmq=  mysqli_fetch_assoc($query_xmq);
                         $datos_xep=  mysqli_fetch_assoc($query_xep);
                         $datos_xma=  mysqli_fetch_assoc($query_xma);
                        
                         ////*** Convertimos  el  resultado  de entregas a  fetch
                         $datos_entregas = mysqli_fetch_array($query_entregas);
                        ///***Convertimos a  Fecth el  resultado del qery xu 
                         $datos_xu = mysqli_fetch_assoc($qery_xu);
                       $datos_listas  =  mysqli_fetch_assoc($query_listas);  
                    
                      $string_filtro=$string_filtro." order by ".$campo.",n_agente";
                    // echo $string_filtro;
                      $query_filtro= mysqli_query($conecta1, $string_filtro) or  die  (mysqli_error($conecta1));    // mysql_query($string_filtro,$conecta1) or die (mysql_error());
                   
              break;
          
             case "2": //Consulta a Pedidos ya Facturados
                 
                   $enunciadoini1="SELECT * FROM $tabla WHERE cant_falta>0 ";
                       //por Fecha de Folio
                if ($fecha1!=""||$fecha2!=""){

                       $valor1=GetSQLValueString($fecha1, "date");
                       $valor2=GetSQLValueString($fecha2, "date");
                       $condision="fecha_alta>=".$valor1." and fecha_alta<=".$valor2;
                       $string=("$condision");
                        $comodo=" and ";
                   } else{
                       $comodo="";
                   }
                 
                 if ($producto!=""){

                       $valor3=GetSQLValueString($producto, "text");
                     
                       $condision2=" cve_prod=".$valor3;
                       $string=("$condision $comodo $condision2");
                        $comodo2=" and ";
                   } else{
                       $comodo2="";
                   }
                 
                  if ($agente!=0){

                       $valor4=GetSQLValueString($agente, "int");
                     
                       $condision3=" n_agente=".$valor4;
                       $string=("$condision $comodo $condision2 $comodo2 $condision3");
                        $comodo3=" and ";
                   } else{
                       $comodo3="";
                   }
                   
                   
                    if ($folio!=0){

                       $valor5=GetSQLValueString($folio, "int");
                       $campo="n_factura";
                       $condision4=$campo."=".$valor5;
                       $string=("$condision $comodo $condision2 $comodo2 $condision3 $comodo3 $condision4");
                        $comodo4=" and ";
                   } else{
                       $comodo4="";
                   }
                   
                    $string_filtro = $enunciadoini1.$otro2.$string;
                    $string_filtro=$string_filtro." order by ".$campo.",n_agente";
                    // echo $string_filtro;
                     $query_filtro=  mysqli_query($conecta1, $string_filtro) or die (mysqli_error($conecta1));  ///mysql_query($string_filtro,$conecta1) or die (mysql_error());
                 
             break;
             
             
         }   
     
      //echo     $string_filtro;
    }    
///*************************************************
$q_agentes=("select distinct(nom_age) as agente, n_agente  from encabeza_pedido order by agente");
$q_agentes_go=mysqli_query($conecta1, $q_agentes)or die (mysqli_error($conecta1));
  
$q_productos=("select distinct(cve_prod) as cve_prod, nom_prod  from detalle_pedido order by nom_prod");
$q_productos_go=mysqli_query($conecta1, $q_productos)or die (mysqli_error($conecta1));  
              
?> 
<script type="text/javascript" src="js/jquery.js"></script> 
  <script language='javascript' src="calendario/popcalendar.js"></script>
  <script language="javascript" type="text/javascript">

    //*** Este Codigo permite Validar que sea un campo Numerico
    function Solo_Numerico(variable){
        Numer=parseInt(variable);
        if (isNaN(Numer)){
            return "";
        }
        return Numer;
    }
    function ValNumero(Control){
        Control.value=Solo_Numerico(Control.value);
    }
    //*** Fin del Codigo para Validar que sea un campo Numerico
   </script> 
   
<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        data: {
            table: document.getElementById('datatable')
        },
        chart: {
            type: 'column'
        },
        title: {
            text: 'Pendientes por Facturar (Beta)'
        },
        yAxis: {
            allowDecimals: true,
            title: {
                text: 'Monto'
            }
        },
        tooltip: {
           
        }
    });
});
///*******
$(document).ready(function(){
    /*
    $("#Hwidth").text($(window).width());
    $("#Hheight").text($(window).height())*/
    
});

</script>
<script src="js/highcharts.js"></script>

<script src="js/modules/data.js"></script>
<script src="js/modules/exporting.js"></script>
   
 <div class="row"> 
     <h3 class="titulo2"> Reportes</h3> 
     <h4 id="Hwidth"> </h4>
     <h4 id="Hheight"> </h4> 
</div> 
    
   <div id="reportes" class="col-xs-12  col-sm-12  col-lg-12" >
       <form name="forma1" id="forma1" action="reportes9.php" method="POST" class="form-group-sm" >
                              
        <div class="form-inline"> 

                <select name="tipo" id="tipo"  class="form-control" >
                    <option value="1">Pendientes</option>
                    <option value="2">Facturadas</option>
                </select>

                 <label for="date">Fecha</label> 
                 <input class="form-control"  type="date" name="fecha1" id="fecha1" placeholder="Fecha Inicial" value="<?php echo $fecha1; ?>" id="dateArrival1" onclick="popUpCalendar(this, forma1.dateArrival1, 'yyyy-mm-dd');"size="10">
                 <input class="form-control" type="date" name="fecha2" id="fecha2" placeholder="Fecha Final" value="<?php echo $fecha2; ?>" id="dateArrival2" onclick="popUpCalendar(this, forma1.dateArrival2, 'yyyy-mm-dd');" size="10">

        </div>        
         <br>
           <div  class="row">
               <div class="form-group"> 
                   <div class="row">
                       <div  class ="col-lg-5">
                            <select class="form-control" name="agente" id="agente">
                                <option value="">Seleccione Agente</option>
                                <?php

                               while ($row=mysqli_fetch_array($q_agentes_go))
                                             {
                                             if ($row['n_agente']==$agente){

                                              echo '<option selected value="'.$row['n_agente'].'">'.$row['agente'].'</option>'; 
                                             }else{
                                                     echo '<option value="'.$row['n_agente'].'">'.$row['agente'].'</option>'; 
                                             }  
                                             }
                             ?>
                            </select>
                      </div>
                   </div>
                   <br>
                   <div class="row">
                       <div class="col-lg-5">
                            <select class="form-control" name="producto" id="producto">
                                <option value="">Seleccione Producto</option>
                                <?php

                                   while ($row=mysqli_fetch_array($q_productos_go))
                                                 {
                                                 if ($row['cve_prod']==$producto){

                                                  echo '<option selected value="'.$row['cve_prod'].'">'.$row['nom_prod'].'</option>'; 
                                                 }else{
                                                         echo '<option value="'.$row['cve_prod'].'">'.$row['nom_prod'].'</option>'; 
                                                 }  
                                                 }
                                 ?>
                            </select>
                       </div>
                   </div>
               </div>
           </div>
           <br>
           <div class="col-xs-12"> 
               <div class="form-group"> 
                   <div class="col-xs-9"> <input class="form-control" type="text" name="folio" id="folio" placeholder="Remision o Factura" onkeyUp="return ValNumero(this);">   </div> 
                   <div class="col-xs-3">    <input class="btn  btn-success" type="submit" name="filtrar" id="filtrar" value="Filtrar">   </div> 
               </div>
           </div> 
      </form>
   </div>

   <br>
   <p><?php //echo $string_entrega_con_filtro; ?></p>
   <?php  //Conocer a que tabla se esta dirigiendo
        if ($tipo==1){?>
   <div id="reportes" class="col-xs-12  col-sm-12  col-lg-12" >
                <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                 <table id="datatable" border="1" style="display:none;">
                      <thead>
                             <tr>
                                   <th></th>
                                   <th>Credito</th>
                                    <th>Precio</th><!--<th>Vta</th>-->
                                    <th>Transito</th><!---<th>Pedido Incompleto</th>-->
                                    <th>Existencia</th>
                                    <th>Orden Compra</th>
                                    <!--Desabilitado  21-03-2017  <th>Material Tecnico</th>--> 
                                    <!--Desabilitado  21-03-2017  <th>Sobre Pedido</th>-->
                                    <th>Maquila</th>
                                    <th>Ventas Industriales</th><!-- Cambio  de Nombre 21-03-2017 <th>Exportaciones</th>--> 
                                    <!--Desabilitado  21-03-2017  <th>Marca Alterna</th>-->
                                    <th>Logistica Entregas</th><!--<th>Entregas</th>-->  
                                    <th>Pedidos Emitidos</th><!--<th>Listas</th>-->
                                    <th>Ubicacion</th>
                             </tr>
                     </thead>
                     <tbody>
                             <tr>
                                     <th>$ Monto</th>
                                     <td><?php echo $datos_xcredito['total']; ?></td>
                                     <td><?php echo $datos_xvta['total']; ?></td>
                                     <td><?php echo $datos_xpi['total']; ?></td>
                                     <td><?php echo $datos_xe['total']; ?></td>
                                     <td><?php echo $datos_xc['total']; ?></td>
                                     <!--Material  Tecnico--> 
                                     <!---<td><?php// echo $datos_xmt['total']; ?></td>-->
                                     <!--Sobre Pedido--> 
                                     <!--<td><td><?php //echo $datos_xsp['total']; ?></td>-->
                                       <td><?php echo $datos_xmq['total']; ?></td>
                                        <td><?php echo $datos_xep['total']; ?></td>
                                     <!--Marca Alterna--> 
                                     <!--<td><td><?php //echo $datos_xma['total']; ?></td>-->
                                    <!--Agregamos Entregas-->
                                    <td><?php echo $datos_entregas['total'] ?></td> 
                                    <td><?php echo $datos_listas['total']; ?></td>
                                    <!--Por  Ubicacion-->
                                    <td><?php echo $datos_xu['total']; ?></td>
                             </tr>


                     </tbody>
                 </table>

                <div  class="table-responsive"> 
                 <table class="table table-condensed">
                     <thead>
                             <tr>
                                     <th></th>
                                    <th>Credito</th>
                                   <th>Precio</th><!--<th>Vta</th>-->
                                    <th>Transito</th><!---<th>Pedido Incompleto</th>-->
                                    <th>Existencia</th>
                                    <th>Orden Compra</th>
                                    <!--Desabilitado  21-03-2017  <th>Material Tecnico</th>--> 
                                    <!--Desabilitado  21-03-2017  <th>Sobre Pedido</th>-->
                                    <th>Maquila</th>
                                    <th>Ventas Industriales</th><!-- Cambio  de Nombre 21-03-2017 <th>Exportaciones</th>--> 
                                    <!--Desabilitado  21-03-2017  <th>Marca Alterna</th>-->
                                    <th>Logistica Entregas</th><!--<th>Entregas</th>-->  
                                    <th>Pedidos Emitidos</th><!--<th>Listas</th>-->
                                    <th>Ubicacion</th>
                                    <th>TOTAL</th>
                             </tr>
                     </thead>
                     <tbody>
                             <tr>
                                     <th>$ Monto</th>
                                      <td><?php echo number_format($datos_xcredito['total'], 2, '.', ','); ?></td>
                                     <td><?php echo number_format($datos_xvta['total'], 2, '.', ','); ?></td>
                                     <td><?php echo number_format($datos_xpi['total'], 2, '.', ','); ?></td>
                                     <td><?php echo number_format($datos_xe['total'], 2, '.', ','); ?></td>
                                   <td><?php echo number_format($datos_xc['total'], 2, '.', ','); ?></td>
                                   <!--Material Tecnico--> 
                                   <!-- <td><?php //echo number_format($datos_xmt['total'], 2, '.', ','); ?></td>-->
                                   <!--Sobre Pedido--> 
                                   <!--<td><?php ///echo number_format($datos_xsp['total'], 2, '.', ','); ?></td>-->
                                    <td><?php echo number_format($datos_xmq['total'], 2, '.', ','); ?></td>
                                    <td><?php echo number_format($datos_xep['total'], 2, '.', ','); ?></td>
                                   <!--Marca Alterna--> 
                                   <!--<td><?php //echo number_format($datos_xma['total'], 2, '.', ','); ?></td>--> 
                                      <!--Entregas-->
                                      <td><?php echo number_format($datos_entregas['total'], 2, '.', ','); ?></td>
                                     <td><?php echo number_format($datos_listas['total'], 2, '.', ','); ?></td>
                                   <!--Por  Ubicacion-->
                                    <td><?php echo number_format($datos_xu['total'], 2, '.', ','); ?></td>
                                     <td><?php 
                                           // $grantotal=$datos_xcredito['total']+$datos_xvta['total']+$datos_xpi['total']+$datos_xe['total']+$datos_xc['total']+$datos_xmt['total']+$datos_xsp['total']+$datos_xmq['total']+$datos_xep['total']+$datos_xma['total']+$datos_listas['total'];
                                            $grantotal=$datos_xcredito['total']+$datos_xvta['total']+$datos_xpi['total']+$datos_xe['total']+$datos_xc['total']+$datos_xmq['total']+$datos_xep['total']+$datos_entregas['total']+$datos_listas['total']+$datos_xu['total'];
                 
                                              echo number_format($grantotal, 2, '.', ','); 
                                      
                                      ?></td>
                             </tr>


                     </tbody>
                 </table>
                </div>    
        <?php }?> 
   </div>    
    <div>  
        <br> 
        <br> 
       
            <div class="table-responsive">
    <table  class="table  table-bordered">
            <thead>
                <tr>
                    <th>Folio:</th>
                    <th>F_Alta</th>
                   <?php  if ($tipo<>1){?>
                        <th>F_Factura</th>
                        <th>F_Entrega</th>
                   <?php }?>    
                    <th>Cliente</th>
                    <th>Agente</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Importe</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>   
          <tbody>
              <?php while ($reg = mysqli_fetch_array($query_filtro)) {  ?>
              <tr>
                <td><?php echo $reg['n_remision']; ?></td>
                <td><?php echo $reg['fecha_alta']; ?></td>
                 <?php  if ($tipo<>1){?>
                    <td><?php echo $reg['fecha_factura']; ?></td>
                    <td><?php echo $reg['fecha_entrega']; ?></td>
                 <?php }?>        
                <td><?php echo $reg['nom_cte']; ?></td>
                <td><?php echo $reg['nom_age']; ?></td>
                <td><?php echo $reg['nom_prod']; ?></td>
                <td><?php 
                       if ($tipo<>1){
                           echo number_format($reg['cant_prod'], 2, '.', ',');
                           $volumen=$reg['cant_prod'];
                       }else{
                            echo number_format($reg['cant_falta'], 2, '.', ','); 
                             $volumen=$reg['cant_falta'];
                       }
                ?>
                
                
                
                </td>
                <td><?php 
                
                       if ($reg['moneda_prod']==0){
                           $tot=($volumen*$reg['precio_condcto'])*$reg['tipo_cambio'];
                       }else{
                           $tot=$volumen*$reg['precio_condcto'];
                       }
                    echo number_format($tot, 2, '.', ','); 
                
                
                ?></td>
                <td> <a href="cronos-recogedor.php?con_consecutivo=<?php echo $reg['id_detalle']; ?>&remision=<?php echo $reg['n_remision']; ?>&agente=<?php echo $reg['n_agente']; ?>&cliente=<?php echo $reg['cve_cte']; ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=430,height=500,scrollbars=yes'); return false;"> <img src="iconos/detalle.PNG"/></a> </td>
                <?php
                
                if ($tipo==1){
                    ///SUPER NOTA EN EL ESTATUS2 de la tabla de detalle se va a dejar el caracter C para identificar que el movimiento esta detenido por ORDEN DE COMPRA

                    if ($reg['estatus1']!="C"){   //Validar que el pedido no este en espera por credito 18-04-2012
                             switch ($reg['estatus']){
                             case "E":
                                    print '<td><img src="iconos/emitida.PNG" title="Emitida" /> </td>';
                                    switch ($reg['estatus2']){
                                             case  "P";
                                                            print '<td><img src="iconos/ped_incompleto.png" title="Pedido Incompleto" /> </td>';
                                             break;
                                            case  "E";
                                                    print '<td><img src="iconos/existencia.png" title="Existencia" /> </td>';
                                             break;
                                            case  "C";
                                                    print '<td><img src="iconos/oc.PNG" title="Orden de Compra" /> </td>';  
                                             break;
                                             case  "M";
                                                    print '<td><img src="iconos/casco.PNG" title="Falta Material Tecnico" /> </td>';  
                                             break; 
                                          case  "EP";
                                            print '<td><img src="iconos/xep.png" title="Exportación" /> </td>'; 
                                            break; 
                                         case  "MA";
                                                   print '<td><img src="iconos/xma.png" title="Marca Alterna" /> </td>';  
                                            break; 
                                         case  "SP";
                                                   print '<td><img src="iconos/xsp.png" title="Sobre Pedido" /> </td>'; 
                                            break; 
                                         case  "MQ";
                                                   print '<td><img src="iconos/xmq.png" title="Maquila" /> </td>';  
                                            break; 
                                         
                                    }

                            //  print ("<td> <input type='CHECKBOX' name='borrar[]' value=".$reg['id_detalle']."> </td>");
                                    break;
                             case "C":
                                    print '<td><img src="iconos/cancelada.PNG" title="Cancelada" /> </td>';
                                    break;
                             case "P":
                                    print '<td><img src="iconos/parcial.PNG"/ title="Facturada Parcial"> </td>';
                                    switch ($reg['estatus2']){
                                             case  "P";
                                                            print '<td><img src="iconos/ped_incompleto.png" title="Pedido Incompleto" /> </td>';
                                             break;
                                            case  "E";
                                                    print '<td><img src="iconos/existencia.png" title="Existencia" /> </td>';
                                             break;
                                            case  "C";
                                                    print '<td><img src="iconos/oc.PNG" title="Orden de Compra" /> </td>';  
                                             break;
                                             case  "M";
                                                    print '<td><img src="iconos/casco.PNG" title="Falta Material Tecnico" /> </td>';  
                                             break; 
                                           case  "EP";
                                            print '<td><img src="iconos/xep.png" title="Exportación" /> </td>'; 
                                            break; 
                                         case  "MA";
                                                   print '<td><img src="iconos/xma.png" title="Marca Alterna" /> </td>';  
                                            break; 
                                         case  "SP";
                                                   print '<td><img src="iconos/xsp.png" title="Sobre Pedido" /> </td>'; 
                                            break; 
                                         case  "MQ";
                                                   print '<td><img src="iconos/xmq.png" title="Maquila" /> </td>';  
                                            break; 
                                    }
                            //  print ("<td> <input type='CHECKBOX' name='borrar[]' value=".$reg['id_detalle']."> </td>");
                                    break;
                             case "F":
                                    print '<td><img src="iconos/facturada.PNG" title="Facturada" /> </td>';
                                    break;
                             case "A":
                                    print '<td><img src="iconos/autorizar.PNG" title="Por Autorizar" /> </td>';
                                    break;
                            case "NA":
                                    print '<td><img src="iconos/na.PNG" title="NO Autorizado" /> </td>';
                                    break;  
                            }
                    }else{
                              print '<td><img src="iconos/credito1.png" title="Credito" /> </td>';  
                    }
              }     
                     ?> 
             </tr>       
              <?php }?>
          </tbody>
      </table>
    </div>
                                            
     
       
  
</div> <!-- Div de Contenido  -->


 <?php require_once('foot_gerentes.php');?>     