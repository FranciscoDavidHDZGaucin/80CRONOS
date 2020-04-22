<?php
/*
    ********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : Principal.php     
 	Fecha  Creacion : 20/09/2016
	Descripcion  : 
         Copia  del  archivo Principal    perteneciente  al    Proyecto  Pedidos
	Modificado  Fecha  : 
 *      ******05/10/2016  Se procede  a  separa  el  Script  Principal.php  
 *                        dado  a que  al  momento de  desplegarce en el  cronos  no funciona  correctamente
 *                        se   divide  en  2   
 *                        Uno con el  nombre  de 
 *                              ****captura_gv.php 
 *                              ***prospecto_gv.php
*/
///****Inicio   Librerias  Utilizadas  en Cronos
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

///****FIN    Librerias  Utilizadas  en Cronos
////////////////////////INSERTAR REGISTROS//////////////////////////////////
   if(isset($_POST["Grabar"])){
       
       
       require_once('Connections/conecta2.php');
        require_once('formato_datos.php'); 
   


 mysql_select_db($database_conecta1,$conecta1);


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
       require_once('Connections/conecta2.php');
        $borrar=$_GET["borra"];

	
	$consulta="Delete from Prospectos where ID_Pros=$borrar";
	$c=mysqli_query($conecta1,$consulta);
   }
  
   
   ////////////////////////INSERTAR PROSPECTOS///////////////////////////
    if(isset($_POST["insertar"]))
    {
           
        require_once('Connections/conecta2.php');
        
        $fecha = $_POST['fecha'];  
        $ciudad = $_POST['ciudad'] ;  
        $prospecto = $_POST['prospectos'];  
        $compromiso=$_POST['compromiso']; 
        $fechacom=$_POST['fcom'];
        $fechavisit=$_POST['fvisit'];
        $Week=date("W"); 
        $Agentes = $_SESSION['Agente'];
        $Zona =  $_SESSION["Zona"];
        $subAgente=$_SESSION["usuario_nombre"];
        $NoAgente=$_SESSION["usuario_agente"];
        $fecha2 = date("D", strtotime($fecha)); 
        $Y=date("Y");
        $FormatYW = $Y."-W".$Week;


        function dias($fecha2)
        {
            switch ($fecha2)
            {
                    
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
            }   
            return $day;
        }

        $fechad=dias($fecha2);


   
        $ns=mysqli_query($conecta1,'INSERT INTO Prospectos(FechaV, Ciudad,NombreP,Compromiso,FechaC,FechaSV,Week,Agente,Zona,SubAgente,WeekF,Dia,NoAgentP) VALUES("'.$fecha.'", "'.$ciudad.'", "'.$prospecto.'","'.$compromiso.'","'.$fechacom.'","'.$fechavisit.'","'.$Week.'","'.$Agentes.'","'.$Zona.'","'.$subAgente.'","'.$FormatYW.'","'.$fechad.'","'.$NoAgente.'")');  

    }
   ///////////////////////////////////////////////////////////////////////

    ///Elegir la Base de datos
    mssql_select_db("AGROVERSA");
  
  $consulta_cliente = sprintf("SELECT * FROM plataforma_ctesagtes where SlpCode=%s or U_agente2=%s ORDER BY CardName ASC",
                               GetSQLValueString( $_SESSION["usuario_agente"] , "int"),
                               GetSQLValueString( $_SESSION["usuario_agente"] , "int"));
 $sql_cliente=mssql_query($consulta_cliente);

 
?> 
  <!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Detalle Cartera Vencida</title>
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
        
<div class="tabs">
    
<div class="tab">
   
<input checked="" id="tab-1" name="tab-group-1" type="radio" />
        <label for="tab-1">Inicio</label>
        <div class="content">
        <br />
        

    
      <!--<div align="right"> <a href="logout.php">CERRAR SESION</a></div> -->
      <p><h4>Zona: <?php echo $_SESSION["Zona"]; ?></p></h4>
      <?php
      function gerentes($x){
          
      switch ($x)
      {
         case "SUR" : $G= "ING. ÁNGEL PEÑA ESQUIVEL";
             break;
          case "NORTE" : $G= "ING. JOSÉ CARLOS LÓPEZ TIZNADO";
             break;
          case "CENTRO" :$G= "ING. SERGIO ALBERTO GARCÍA GODÍNEZ";
             break;
          case "VERUR" : $G="ING. JUAN CARLOS SUSTAITA";
             break;
         case "LOCAL" :  $G="ING. SANTIAGO VERA GONZÁLEZ";
             break;
         case "SEMILLAS" :  $G="LUIS DANIEL CISNEROS ";
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

       
         <form action="Principal.php" method="post">
                
             <table BORDER=1  > 
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
                   
                    <td width="90">
                    <input  type="date" style="width:126px" name="Fecha"  required></td> 
                    <td width="130">
                    <input type="text" name="ciudad"></td> 
                    <td width="140">  
                    <select name="cliente2"  id="cliente"   style="width:220px" width="140">
                       <option>N/A</option>
                     <?php

                       while ($rowc=mssql_fetch_array($sql_cliente))
                               
                         {
                                 
                                    echo '<option value="'.$rowc['CardName'].'">'.$rowc['CardName'].'</option>';	
                             		
                         }
                         
                         
                     ?>
                     </select></td>
                    <td width="130">                         
                    <SELECT name="asunto" id='ww' onchange="if(this.value=='5.TRABAJOOFICINA' || this.value=='4.CAPACITACION' || this.value=='7.EXPOS' ) {document.getElementById('cliente').disabled =true} else {document.getElementById('cliente').disabled = false} if(this.value == '9.MONITOREO'){document.getElementById('cult').style.visibility='visible';}else {document.getElementById('cult').style.visibility='hidden';} style='width:120px' ">
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
                    <td width="130">
                    <input type="text" name="result" maxlength="140"></td> 
                    <td width="90">
                        <input type="text" name="venta" placeholder="$$$$$$$$" onBlur="this.value=formatCurrency(this.value);" style="width:90px"></td> 
                    <td width="130">
                    <input type="text" name="objventa" maxlength="140"></td>    
           
                </tr></tbody></table>
             <input type="submit" name="Grabar" value="Grabar Datos" /> 
         </form><br>
             </fieldset>
            
         
        <fieldset  width="30">
            
            <legend>Lunes</legend>
           <TABLE BORDER=2 CELLSPACING=1 CELLPADDING=1> 
              
            <TR><TD>&nbsp;<B>Fecha</B></TD> <TD>&nbsp;<B>Ciudad</B>&nbsp;</TD><TD>&nbsp;<B>Cliente</B>&nbsp;</TD><TD>&nbsp;<B>Asunto</B>&nbsp;</TD><TD>&nbsp;<B>Resultado</B>&nbsp;</TD><TD>&nbsp;<B>Venta</B>&nbsp;</TD><TD>&nbsp;<B>Objecion Venta</B>&nbsp;</TD><TD>&nbsp;<B>Cultivo</B>&nbsp;</TD></TR> 
          
 <?php     
                   
           require_once('Connections/conecta2.php');
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
            <TABLE BORDER=2 CELLSPACING=1 CELLPADDING=1> 
            <TR><TD>&nbsp;<B>Fecha</B></TD> <TD>&nbsp;<B>Ciudad</B>&nbsp;</TD><TD>&nbsp;<B>Cliente</B>&nbsp;</TD><TD>&nbsp;<B>Asunto</B>&nbsp;</TD><TD>&nbsp;<B>Resultado</B>&nbsp;</TD><TD>&nbsp;<B>Venta</B>&nbsp;</TD><TD>&nbsp;<B>Objecion Venta</B>&nbsp;</TD><TD>&nbsp;<B>Cultivo</B>&nbsp;</TD></TR> 
            <?php     
           require_once('Connections/conecta2.php');
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
            <TABLE BORDER=2 CELLSPACING=1 CELLPADDING=1> 
            <TR><TD>&nbsp;<B>Fecha</B></TD> <TD>&nbsp;<B>Ciudad</B>&nbsp;</TD><TD>&nbsp;<B>Cliente</B>&nbsp;</TD><TD>&nbsp;<B>Asunto</B>&nbsp;</TD><TD>&nbsp;<B>Resultado</B>&nbsp;</TD><TD>&nbsp;<B>Venta</B>&nbsp;</TD><TD>&nbsp;<B>Objecion Venta</B>&nbsp;</TD><TD>&nbsp;<B>Cultivo</B>&nbsp;</TD></TR> 
            <?php       
            require_once('Connections/conecta2.php');
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
            <TABLE BORDER=2 CELLSPACING=1 CELLPADDING=1> 
            <TR><TD>&nbsp;<B>Fecha</B></TD> <TD>&nbsp;<B>Ciudad</B>&nbsp;</TD><TD>&nbsp;<B>Cliente</B>&nbsp;</TD><TD>&nbsp;<B>Asunto</B>&nbsp;</TD><TD>&nbsp;<B>Resultado</B>&nbsp;</TD><TD>&nbsp;<B>Venta</B>&nbsp;</TD><TD>&nbsp;<B>Objecion Venta</B>&nbsp;</TD><TD>&nbsp;<B>Cultivo</B>&nbsp;</TD></TR> 
            <?php     
            require_once('Connections/conecta2.php');
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
            <TABLE BORDER=2 CELLSPACING=1 CELLPADDING=1> 
            <TR><TD>&nbsp;<B>Fecha</B></TD> <TD>&nbsp;<B>Ciudad</B>&nbsp;</TD><TD>&nbsp;<B>Cliente</B>&nbsp;</TD><TD>&nbsp;<B>Asunto</B>&nbsp;</TD><TD>&nbsp;<B>Resultado</B>&nbsp;</TD><TD>&nbsp;<B>Venta</B>&nbsp;</TD><TD>&nbsp;<B>Objecion Venta</B>&nbsp;</TD><TD>&nbsp;<B>Cultivo</B>&nbsp;</TD></TR> 
            <?php     
            require_once('Connections/conecta2.php');
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
            <TABLE BORDER=2 CELLSPACING=1 CELLPADDING=1> 
            <TR><TD>&nbsp;<B>Fecha</B></TD> <TD>&nbsp;<B>Ciudad</B>&nbsp;</TD><TD>&nbsp;<B>Cliente</B>&nbsp;</TD><TD>&nbsp;<B>Asunto</B>&nbsp;</TD><TD>&nbsp;<B>Resultado</B>&nbsp;</TD><TD>&nbsp;<B>Venta</B>&nbsp;</TD><TD>&nbsp;<B>Objecion Venta</B>&nbsp;</TD><TD>&nbsp;<B>Cultivo</B>&nbsp;</TD></TR> 
            <?php     
            require_once('Connections/conecta2.php');
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
 
</div>
  
        <div class="tab">
            <input id="tab-3" name="tab-group-1" type="radio"  <?php  if(isset($_GET["borra"]) or isset($_POST["insertar"])){ echo 'checked="true" ';} ?> />
            <label for="tab-3">Prospectos</label>
            <br />
                <div class="content"  >
                    <form action="Principal.php" method="post">   
                 <fieldset> <legend>Prospecto</legend>
                 <table id="tablaUsuarios7" border="2"> 
                   <tbody> 
                     <tr> 
                        <td width="136">Fecha Visita</td> 
                        <td width="149">Ciudad</td> 
                        <td width="176">Nombre</td> 
                        <td width="176">Compromiso</td> 
                        <td width="146">Fecha Compromiso</td> 
                        <td width="125">Fecha Sig. Visita</td>       
                     </tr> 
                     
                       <tr> 
                   
                    
                    <td><input  type="date" name="fecha"  required></td> 
                     <td><input type="text" name="ciudad"></td> 
                   <td><input type="text" name="prospectos"></td> 
                   <td><input type="text" name="compromiso"></td> 
                   <td> <input  type="date" name="fcom"  required>
                    <td> <input  type="date" name="fvisit"  required>
             </tr>
                   </tbody> 
                 </table> 
                   
            </fieldset> 
               <div align="center"><input type="submit" name="insertar" value="Grabar Datos" /></div>         
               </form>
                    <br> <br>
                   <table  width="596" border="1" align="Center">
            <tr> 
                <thead>
                 <th width="380">Fecha Visita</th> 
                        <th width="149">Ciudad</th> 
                        <th width="176">Nombre</th> 
                        <th width="176">Compromiso</th> 
                        <th width="130">Fecha Compromiso</th> 
                        <th width="430">Fecha Sig. Visita</th>      
                        
                </thead>
            </tr> 
           
            <tbody>
                <?php 
                require_once('Connections/conecta2.php');
                require_once('formato_datos.php'); 
                
                $sql12= "Select *
                         From Prospectos where Agente= '$agent' and WeekF >='$FormatYW'";
                // From Prospectos where Agente= '$agent' and Week >='$week2'"; SE CAMBIO POR EL COMPO WEEK PARA FILTRAR LO ACTUAL
            $sqlp=  mysqli_query($conecta1, $sql12) or die (mysqli_error($conecta1));

                while($row = mysqli_fetch_array($sqlp)) { ?>
                <tr>
                    <td width="280"><?php echo $row['FechaV']; ?></td>
                    <td width="70"><?php echo $row['Ciudad']; ?></td>
                    <td width="370"><?php echo $row['NombreP']; ?></td>
                    <td width="170"><?php echo $row['Compromiso']; ?></td>
                    <td width="470"><?php echo $row['FechaC']; ?></td>
                    <td width="270"><?php echo $row['FechaSV']; ?></td>
                  <td><a href='Principal.php?borra=<?php echo $row[0]?>'>Eliminar</a></td>
                   
                </tr>
               
                <?php } ?>
            </tbody>
               </table>
                </div>
        </div>      
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