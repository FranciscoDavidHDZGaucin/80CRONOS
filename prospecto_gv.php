<?php

///*prospecto_gv
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : prospecto_gv.php 
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
////////////////////////INSERTAR PROSPECTOS///////////////////////////
    if(isset($_POST["insertar"]))
    {
           
     
        
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
         
            <input id="tab-3" name="tab-group-1" type="radio"  <?php  if(isset($_GET["borra"]) or isset($_POST["insertar"])){ echo 'checked="true" ';} ?> />
            <label for="tab-3">Prospectos</label>
            <br />
                <div class="content"  >
                    <form action="prospecto_gv.php" method="post">   
                 <fieldset> <legend>Prospecto</legend>
                 <table id="tablaUsuarios7"  class="table  table-responsive" > 
                   <tbody> 
                     <tr> 
                        <td>Fecha Visita</td> 
                        <td>Ciudad</td> 
                        <td >Nombre</td> 
                        <td >Compromiso</td> 
                        <td >Fecha Compromiso</td> 
                        <td >Fecha Sig. Visita</td>       
                     </tr> 
                     
                       <tr> 
                   
                    
                           <td><input class="form-control"  type="date" name="fecha"  required></td> 
                     <td><input class="form-control" type="text" name="ciudad"></td> 
                   <td><input class="form-control" type="text" name="prospectos"></td> 
                   <td><input class="form-control" type="text" name="compromiso"></td> 
                   <td> <input class="form-control" type="date" name="fcom"  required>
                    <td> <input class="form-control" type="date" name="fvisit"  required>
             </tr>
                   </tbody> 
                 </table> 
                   
            </fieldset> 
               <div align="center"><input class ="btn  btn-success"  type="submit" name="insertar" value="Grabar Datos" /></div>         
               </form>
                    <br> <br>
     <!---  <table  width="596" border="1" align="Center"> --> 
            <table  class="table table-responsive">
            <tr> 
                <thead>
                 <th >Fecha Visita</th> 
                        <th >Ciudad</th> 
                        <th >Nombre</th> 
                        <th >Compromiso</th> 
                        <th >Fecha Compromiso</th> 
                        <th >Fecha Sig. Visita</th>      
                        
                </thead>
            </tr> 
           
            <tbody>
                <?php 
              
                
                $sql12= "Select *
                         From Prospectos where Agente= '$agent' and WeekF >='$FormatYW'";
                // From Prospectos where Agente= '$agent' and Week >='$week2'"; SE CAMBIO POR EL COMPO WEEK PARA FILTRAR LO ACTUAL
            $sqlp=  mysqli_query($conecta1, $sql12) or die (mysqli_error($conecta1));

                while($row = mysqli_fetch_array($sqlp)) { ?>
                <tr>
                    <td ><?php echo $row['FechaV']; ?></td>
                    <td ><?php echo $row['Ciudad']; ?></td>
                    <td ><?php echo $row['NombreP']; ?></td>
                    <td ><?php echo $row['Compromiso']; ?></td>
                    <td ><?php echo $row['FechaC']; ?></td>
                    <td ><?php echo $row['FechaSV']; ?></td>
                  <td><a href='Principal.php?borra=<?php echo $row[0]?>'>Eliminar</a></td>
                   
                </tr>
               
                <?php } ?>
            </tbody>
               </table>
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
