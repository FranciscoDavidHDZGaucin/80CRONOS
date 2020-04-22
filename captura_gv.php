<?php
///captura_gv
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : captura_gv.php 
 	Fecha  Creacion : 05/10/2016
	Descripcion  : 
              Script    Perteneciente a  la  seccion  Guia  Visitas 
 *            Proyecto  Cronos
 *            Codigo  Copiado  del  Script    Principal.php 
	Modificado  Fecha  : 
*/
///****Cabecera Cronos 
require_once('header.php');
///***Conexion  sap
require_once('conexion_sap/sap.php');

require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***Conexion Mysql  
require_once('Connections/conecta1.php');
////****************************************************
////////////////////////INSERTAR REGISTROS//////////////////////////////////
   if(isset($_POST["Grabar"])){
       
       
    //   require_once('Connections/conecta2.php');
    //    require_once('formato_datos.php'); 
   
 //mysql_select_db($database_conecta1,$conecta1);
$fecha = $_POST['Fecha'];  
$ciudad = $_POST['ciudad'] ;  
$cliente = $_POST['cliente2'];  
  $asunto=$_POST['asunto']; 
  $result=$_POST['result'];
  $venta=$_POST['venta'];
  $objventa=$_POST['objventa'];
  $cultivosL=$_POST['cultivos'];
  $Week=date("W"); 
  $Agentes = $_SESSION["Agente"] ; 
  $Zona =  $_SESSION["Zona"];
  $NoAgente=$_SESSION["usuario_agente"];
  $subAgente=$_SESSION["usuario_nombre"];
$fecha2 = date("D", strtotime($fecha)); 
$comparacion=date("W", strtotime(date("Y-m-d")));
$Y=date("Y");
$FormatYW = $Y."-W".$Week;

$numeroSemana = date("W", strtotime($fecha)); 
$calcWeek = $Week - date('W');
//$inicioW =date('Y-m-d', strtotime('Monday' . ($calcWeek-1) . ' weeks'));
$inicioW =date('Y-m-d', strtotime('Sunday' . ($calcWeek-1) . ' weeks'));

$finW =date('Y-m-d', strtotime('Saturday' . $calcWeek . ' days'));
//echo "diasinicio".$inicioW.'</br>';
//
//echo "diasfinIII".$finW.'</br>';
//echo 'Sunday'.$inicioWI.'</br>';
//echo 'Cal'.$calcWeek ;
//$inicioW =date('Y-m-d', strtotime('Monday' . ($calcWeek-1) . ' weeks'));
//$finW =date('Y-m-d', strtotime('Saturday' . $calcWeek . ' weeks'));

//echo 'inicio cal'.$inicioW.'<br>';
//echo 'fin cal'.$finW.'<br>';

function dias($fecha2)
{
    switch ($fecha2){
        case "Mon": $day="Lunes";
                        break;
        case "Tue": $day="Martes";
                        break;
        case "Wed": $day="Miercoles";
                        break;
        case "Thu": $day="Jueves";
                        break;
        case "Fri": $day="Viernes";
                        break;
        case "Sat": $day="Sabado";
                        break;
       case "Sun": $day="Domingo";
                        break;
    }
    return $day;
}

$fechad=dias($fecha2);
 
/*$ns=mysqli_query($conecta1,'INSERT INTO Captura(FechaG, Ciudad,Cliente,Asunto,Resultado,Venta,ObjVenta,Week,Agente,Zona)  VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)',
 GetSQLValueString($fecha, 'date'),
 GetSQLValueString($ciudad, 'text'),
        GetSQLValueString($cliente, 'text'),
        GetSQLValueString($asunto, 'text'),
        GetSQLValueString($result, 'text'),
        GetSQLValueString($venta, 'int'),
        GetSQLValueString($objventa, 'text'),
        GetSQLValueString($Week, 'text'),
         GetSQLValueString($Agentes, 'text'),
         GetSQLValueString($Zona, 'text'));*/

//
 if($fecha >= $inicioW and $fecha <= $finW and ($numeroSemana==$comparacion)){
   
    $ns="INSERT INTO Captura(Dia,FechaG, Ciudad,Cliente,Asunto,Resultado,Venta,ObjVenta,Week,Agente,Zona,weekF,SubAgent,NoAgent,Cultivos)  VALUES('$fechad','$fecha','$ciudad','$cliente','$asunto','$result','$venta','$objventa','$FormatYW','$Agentes','$Zona','$Week','$subAgente','$NoAgente','$cultivosL')";
    $insert= mysqli_query($conecta1, $ns) or die (mysqli_error($conecta1));
 
 }
 else 
 {
     echo '<SCRIPT LANGUAGE="JavaScript">
            alert(" La fecha no pertenece a la semana actual");
            location.href="Principal.php"
           </SCRIPT> ';
     
 }
   }
 
 
 ////////////////////////ELIMINAR REGISTROS//////////////////////////////////
   if(isset($_GET["borra"])){
       require_once('Connections/conecta1.php');
        $borrar=$_GET["borra"];

	
	$consulta="Delete from Prospectos where ID_Pros=$borrar";
	$c=mysqli_query($conecta1,$consulta);
   }
     ///Elegir la Base de datos
    //mssql_select_db("AGROVERSA");
  
  $consulta_cliente = sprintf("SELECT * FROM plataforma_ctesagtes where SlpCode=%s or U_agente2=%s ORDER BY CardName ASC",
                               GetSQLValueString( $_SESSION["usuario_agente"] , "int"),
                               GetSQLValueString( $_SESSION["usuario_agente"] , "int"));
 $sql_cliente=mssql_query($consulta_cliente);

///*****************************************************
?>
  <!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Captura Guias Visita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="select3/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Loading Flat UI -->
    <link href="select3/dist/css/flat-ui.css" rel="stylesheet">
 
    <link href="select2/gh-pages.css" rel="stylesheet">
    <link href="select2/select2.css" rel="stylesheet">
      
      
    <link rel="shortcut icon" href="select3/dist/img/favicon.ico">

     <link href="Css1/ui-lightness/jquery-ui-1.10.0.custom.css" rel="stylesheet">
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.custom.js"></script>
	<script src="js/modernizr.js"></script>
  
        <SCRIPT language=JavaScript>
        function formatCurrency(num)
        {
             num = num.toString().replace(/ |,/g,'');
             if(isNaN(num)) 
            num = "0";
            cents = Math.floor((num*100+0.5)%100);
            num = Math.floor((num*100+0.5)/100).toString();
            if(cents < 10) 
                cents = "0" + cents;
            for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
                num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));
                return (' ' + num + '.' + cents);
        }
        </SCRIPT>
        <script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
         <script language="JavaScript"> 
         function habilita(){
             $(".inputText").removeAttr("disabled");
             } 

         function deshabilita(){ 
             $(".inputText").attr("disabled","disabled");
             }
         </script> 

      <script>
             Modernizr.load({
                     test: Modernizr.inputtypes.date,
                     nope: "js/jquery-ui.custom.js",
                     callback: function() {
                       $("input[type=date]").datepicker();
                     }
               });
     </script>    
  </head>

  <body>
       
      <div class="container">   
      <!--*****Inicio  Codigo Copia---->
       <div align="center">
            <img src="images/GuiasLogo.jpg" width="450px">

        </div>
        

    
<!--
<input checked="" id="tab-1" name="tab-group-1" type="radio" />
        <label for="tab-1">Inicio</label>
        <div class="content">
        <br /> -->
   
      <!--<div align="right"> <a href="logout.php">CERRAR SESION</a></div> -->
      <p><h4>Zona: <?php echo $_SESSION["Zona"]; ?></p></h4>
      <?php
      function gerentes($x){
          
      switch ($x)
      {
         case "SUR" : $G= "ING. SILVINO MARTINEZ RAMIREZ";
             break;
          case "NORTE" : $G= "ING. JOSÉ CARLOS LÓPEZ TIZNADO";
             break;
          case "CENTRO" :$G= "ING. SERGIO ALBERTO GARCÍA GODÍNEZ";
             break;
          case "VERUR" : $G="ING. ALFREDO LU CARLIN";
             break;
         case "LOCAL" :  $G="LUIS DANIEL CISNEROS";
             break;
         case "SEMILLAS" :  $G="ING. SANTIAGO VERA GONZÁLEZ ";
             break;
      }
    return $G;
      }
      
      ?>
      <?php $Gerente = gerentes($_SESSION['Zona']); ?>
 <p><div><h4>Gerente Regional: <?php echo $Gerente; ?></h4></div></p>
               <p><h4>Representante: <?php echo     $_SESSION["Agente"] ; ?> - <?php echo $_SESSION['usuario_nombre'] ; ?> </h4></p>
        


               <div align="right">
                
                <h3 style="color: blue">Semana #:
                    <?php $Week=date("W - Y ");
                     $week2 = date("W");
              
                    echo $Week; 
                    $Week=date("W");
                    $Y=date("Y");
                    $FormatYW = $Y."-W".$Week;
                
                
                ?></h3>
                
            </div>
            <fieldset>

       
                <form action="captura_gv.php" method="post">
                
         <table class=" table table-responsive"  > 
            <tbody> 
          <tr> 
                <thead>
                <th>Fecha</th>
                <th>Ciudad</th>    
                <th>Cliente</th>
                <th>Asunto</th>
                <th>Resultado</th>
                <th>Venta</th>
                <th>Objecion Venta</th>
                                 </thead>
            </tr>  
                <tr> 
                   
                    <td>
                    <input  type="date"  class ="form-control  col-lg-4 col-md-4"  name="Fecha"  required></td> 
                    <td >
                    <input type="text" class ="form-control col-lg-4 col-md-4" name="ciudad"></td> 
                    <td>  
                    <select name="cliente2"  id="cliente" class ="form-control col-lg-4 col-md-4"  style="width:220px" width="140">
                       <option>N/A</option>
                     <?php

                       while ($rowc=mssql_fetch_array($sql_cliente))
                               
                         {
                                 
                                    echo '<option value="'.$rowc['CardName'].'">'.$rowc['CardName'].'</option>';	
                             		
                         }
                         
                         
                     ?>
                     </select></td>
                    <td >                         
                    <SELECT name="asunto" id='ww' class ="form-control col-lg-4 col-md-4" onchange="if(this.value=='5.TRABAJOOFICINA' || this.value=='4.CAPACITACION' || this.value=='7.EXPOS' ) {document.getElementById('cliente').disabled =true} else {document.getElementById('cliente').disabled = false} if(this.value == '9.MONITOREO'){document.getElementById('cult').style.visibility='visible';}else {document.getElementById('cult').style.visibility='hidden';} style='width:120px' ">
                                   <option>1.VENTAS</option>
                                    <option>2.COBRANZA</option>
                                     <option>3.PROMOCION</option>
                                     <option>4.CAPACITACION</option>
                                     <option>5.TRABAJOOFICINA</option>
                                      <option>6.PARCELADEMOSTRATIVA</option>
                                         <option>7.EXPOS</option>
                                         <option>8.REUNIONESTRABAJO</option>
                                        <option>9.MONITOREO</option>
                    </select>
                     <div style="visibility: hidden" id="cult" ><center><b>Cultivo</b><br><select name="cultivos"   style='width:140px'>
                                <option></option>
                                <option>MAIZ</option>
                                <option>ALFALFA</option>
                                <option>SORGO</option>
                                <option>HORTALIZA</option>
                                <option>NOGAL</option>
                                <option>AVENA</option>
                                <option>ALGODON</option>
                    </select></center></div></td> 
                    <td >
                    <input type="text" name="result" class ="form-control col-lg-4 col-md-4" maxlength="140"></td> 
                    <td >
                        <input type="text" name="venta" class ="form-control col-lg-4 col-md-4" placeholder="$$$$$$$$" onBlur="this.value=formatCurrency(this.value);" style="width:90px"></td> 
                    <td >
                    <input type="text" name="objventa" class ="form-control col-lg-4 col-md-4" maxlength="140"></td>    
           
                </tr></tbody></table>
             <input type="submit" class ="btn btn-success" name="Grabar" value="Grabar Datos" /> 
         </form><br>
             </fieldset>
            
         
        <fieldset  width="30">
            
            <legend>Lunes</legend>
         <!--  <TABLE BORDER=2 CELLSPACING=1 CELLPADDING=1> -->
            <table class=" table table-responsive"  >    
            <TR><TD>&nbsp;<B>Fecha</B></TD> <TD>&nbsp;<B>Ciudad</B>&nbsp;</TD><TD>&nbsp;<B>Cliente</B>&nbsp;</TD><TD>&nbsp;<B>Asunto</B>&nbsp;</TD><TD>&nbsp;<B>Resultado</B>&nbsp;</TD><TD>&nbsp;<B>Venta</B>&nbsp;</TD><TD>&nbsp;<B>Objecion Venta</B>&nbsp;</TD><TD>&nbsp;<B>Cultivo</B>&nbsp;</TD></TR> 
          
 <?php     
                   
           require_once('Connections/conecta1.php');
            require_once('formato_datos.php'); 
                
                    
                     $agent =$_SESSION['Agente'] ;
                     $User=  $_SESSION['usuario_nombre'] ; 
                $resultad = "SELECT * 
                             FROM  Captura where Dia='Lunes' and Agente= '$agent' and Week >='$FormatYW' and SubAgent='$User';";  
                //FROM  Captura where Dia='Lunes' and Agente= '$agent' and weekF >='$week2' and SubAgent='$User';";  SE CAMBIO POR EL WEEK PORQUE FILTRABA MAS CAMPOS
                $sql = mysqli_query($conecta1, $resultad);
                
                 while($row = mysqli_fetch_array($sql)) { 
            printf("<tr><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s&nbsp;</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td><a href='borrar.php?borra=$row[0]'>Eliminar</a></td></tr>", $row["FechaG"], $row["Ciudad"], $row["Cliente"], $row["Asunto"], $row["Resultado"], $row["Venta"], $row["ObjVenta"],$row["Cultivos"]); 
                } 
 
            mysql_free_result($sql);
                ?>
        

            </table>
        </fieldset>


        <fieldset>
            <legend>Martes</legend>
            <!-- <TABLE BORDER=2 CELLSPACING=1 CELLPADDING=1> -->
             <table class=" table table-responsive"  >    
            <TR><TD>&nbsp;<B>Fecha</B></TD> <TD>&nbsp;<B>Ciudad</B>&nbsp;</TD><TD>&nbsp;<B>Cliente</B>&nbsp;</TD><TD>&nbsp;<B>Asunto</B>&nbsp;</TD><TD>&nbsp;<B>Resultado</B>&nbsp;</TD><TD>&nbsp;<B>Venta</B>&nbsp;</TD><TD>&nbsp;<B>Objecion Venta</B>&nbsp;</TD><TD>&nbsp;<B>Cultivo</B>&nbsp;</TD></TR> 
            <?php     
           require_once('Connections/conecta1.php');
           require_once('formato_datos.php'); 

            $resultad2 = "SELECT * 
            FROM  Captura where Dia='Martes' and Agente= '$agent' and Week >='$FormatYW' and SubAgent='$User';";  
            $sql2 = mysqli_query($conecta1, $resultad2);

            while($row = mysqli_fetch_array($sql2)) { 
            printf("<tr><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s&nbsp;</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td><a href='borrar.php?borra=$row[0]'>Eliminar</a></td></tr>", $row["FechaG"], $row["Ciudad"], $row["Cliente"], $row["Asunto"], $row["Resultado"], $row["Venta"], $row["ObjVenta"],$row["Cultivos"]); 
            } 
            mysql_free_result($sql2); ?>
            </table>
        </fieldset>
         
         <fieldset>
            <legend>Miercoles</legend>
           <!-- <TABLE BORDER=2 CELLSPACING=1 CELLPADDING=1> -->
            <table class=" table table-responsive"  >    
            <TR><TD>&nbsp;<B>Fecha</B></TD> <TD>&nbsp;<B>Ciudad</B>&nbsp;</TD><TD>&nbsp;<B>Cliente</B>&nbsp;</TD><TD>&nbsp;<B>Asunto</B>&nbsp;</TD><TD>&nbsp;<B>Resultado</B>&nbsp;</TD><TD>&nbsp;<B>Venta</B>&nbsp;</TD><TD>&nbsp;<B>Objecion Venta</B>&nbsp;</TD><TD>&nbsp;<B>Cultivo</B>&nbsp;</TD></TR> 
            <?php       
            require_once('Connections/conecta1.php');
            require_once('formato_datos.php'); 

            $resultad3 = "SELECT * 
                          FROM  Captura where Dia='Miercoles' and Agente= '$agent' and Week >='$FormatYW' and SubAgent='$User';";  
            $sql3 = mysqli_query($conecta1, $resultad3);
 
 
            while($row = mysqli_fetch_array($sql3)) { 
            printf("<tr><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s&nbsp;</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td><a href='borrar.php?borra=$row[0]'>Eliminar</a></td></tr>",$row["FechaG"], $row["Ciudad"], $row["Cliente"], $row["Asunto"], $row["Resultado"], $row["Venta"], $row["ObjVenta"], $row["Cultivos"]); 
            } 
            mysql_free_result($sql3); ?>
            </TABLE>
         </fieldset>

        <fieldset>
            <legend>Jueves</legend>
            <!--<TABLE BORDER=2 CELLSPACING=1 CELLPADDING=1> -->
             <table class=" table table-responsive"  >    
            <TR><TD>&nbsp;<B>Fecha</B></TD> <TD>&nbsp;<B>Ciudad</B>&nbsp;</TD><TD>&nbsp;<B>Cliente</B>&nbsp;</TD><TD>&nbsp;<B>Asunto</B>&nbsp;</TD><TD>&nbsp;<B>Resultado</B>&nbsp;</TD><TD>&nbsp;<B>Venta</B>&nbsp;</TD><TD>&nbsp;<B>Objecion Venta</B>&nbsp;</TD><TD>&nbsp;<B>Cultivo</B>&nbsp;</TD></TR> 
            <?php     
            require_once('Connections/conecta1.php');
            require_once('formato_datos.php'); 

            $resultad4 = "SELECT * 
                          FROM  Captura where Dia='Jueves' and Agente= '$agent' and Week >='$FormatYW' and SubAgent='$User';";  
            $sql4 = mysqli_query($conecta1, $resultad4);
 
 
            while($row = mysqli_fetch_array($sql4)) { 
            printf("<tr><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s&nbsp;</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td><a href='borrar.php?borra=$row[0]'>Eliminar</a></td></tr>",$row["FechaG"], $row["Ciudad"], $row["Cliente"], $row["Asunto"], $row["Resultado"], $row["Venta"], $row["ObjVenta"], $row["Cultivos"]); 
            } 
            mysql_free_result($sql4); ?></table></fieldset>

        <fieldset>
            <legend>Viernes</legend>
            <!--<TABLE BORDER=2 CELLSPACING=1 CELLPADDING=1> -->
             <table class=" table table-responsive"  >    
            <TR><TD>&nbsp;<B>Fecha</B></TD> <TD>&nbsp;<B>Ciudad</B>&nbsp;</TD><TD>&nbsp;<B>Cliente</B>&nbsp;</TD><TD>&nbsp;<B>Asunto</B>&nbsp;</TD><TD>&nbsp;<B>Resultado</B>&nbsp;</TD><TD>&nbsp;<B>Venta</B>&nbsp;</TD><TD>&nbsp;<B>Objecion Venta</B>&nbsp;</TD><TD>&nbsp;<B>Cultivo</B>&nbsp;</TD></TR> 
            <?php     
            require_once('Connections/conecta1.php');
            require_once('formato_datos.php'); 

            $resultad5 = "SELECT * 
                          FROM  Captura where Dia='Viernes' and Agente= '$agent' and Week >='$FormatYW' and SubAgent='$User';";  
            $sql5 = mysqli_query($conecta1, $resultad5);
 
 
             while($row = mysqli_fetch_array($sql5)) { 
             printf("<tr><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s&nbsp;</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td><a href='borrar.php?borra=$row[0]'>Eliminar</a></td></tr>", $row["FechaG"], $row["Ciudad"], $row["Cliente"], $row["Asunto"], $row["Resultado"], $row["Venta"], $row["ObjVenta"], $row["Cultivos"]); 
             } 
             mysql_free_result($sql5); ?></table></fieldset>

        <fieldset>
            <legend>Sabado</legend>
            <!--<TABLE BORDER=2 CELLSPACING=1 CELLPADDING=1> -->
             <table class=" table table-responsive"  >    
            <TR><TD>&nbsp;<B>Fecha</B></TD> <TD>&nbsp;<B>Ciudad</B>&nbsp;</TD><TD>&nbsp;<B>Cliente</B>&nbsp;</TD><TD>&nbsp;<B>Asunto</B>&nbsp;</TD><TD>&nbsp;<B>Resultado</B>&nbsp;</TD><TD>&nbsp;<B>Venta</B>&nbsp;</TD><TD>&nbsp;<B>Objecion Venta</B>&nbsp;</TD><TD>&nbsp;<B>Cultivo</B>&nbsp;</TD></TR> 
            <?php     
            require_once('Connections/conecta1.php');
            require_once('formato_datos.php'); 

            $resultad6 = "SELECT * 
                          FROM  Captura where Dia='Sabado' and Agente= '$agent' and Week >='$FormatYW' and SubAgent='$User';";  
            $sql6 = mysqli_query($conecta1, $resultad6);
 
 
            while($row = mysqli_fetch_array($sql6)) { 
            printf("<tr><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s&nbsp;</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td><a href='borrar.php?borra=$row[0]'>Eliminar</a></td></tr>", $row["FechaG"], $row["Ciudad"], $row["Cliente"], $row["Asunto"], $row["Resultado"], $row["Venta"], $row["ObjVenta"], $row["Cultivos"]); 
            } 
            mysql_free_result($sql6); ?></table></fieldset>

             </tbody> 
        
            </table>           
      </div>
      <!--*****FIN  Codigo Copia---->
      </div> <!-- /.Canvas -->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script src="select3/dist/js/vendor/jquery.min.js"></script>      
    <script src="select3/dist/js/flat-ui.min.js"></script>        
    <script src="select3/assets/js/application.js"></script>
    
    
    <script src="select2/buscar-cool.js"></script>   
    <script type="text/javascript" src="select2/jquery.min.1.10.2.js"></script>
    <script src="select2/select2.js"></script>   
    <!--<script src="select2/jasny-bootstrap.min.js"></script>-->
  </body>
</html>    
