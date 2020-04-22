<?php
 session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>CRONOS-COMISIONES</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="select3/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Loading Flat UI -->
    <link href="select3/dist/css/flat-ui.css" rel="stylesheet">
 
    <link href="select2/gh-pages.css" rel="stylesheet">
    <link href="select2/select2.css" rel="stylesheet">
      
      
    <link rel="shortcut icon" href="select3/dist/img/favicon.ico">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="../../dist/js/vendor/html5shiv.js"></script>
      <script src="../../dist/js/vendor/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <style>
      body {
     /*   min-height: 2000px;*/
        padding-top: 70px;
      }
      
    .dropdown-submenu {
  position: relative;
}
.dropdown-submenu > .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -6px;
  margin-left: -1px;
}
.dropdown-submenu:hover > .dropdown-menu {
  display: block;
}
.dropdown-submenu:hover > a:after {
  border-left-color: #fff;
}
.dropdown-submenu.pull-left {
  float: none;
}
.dropdown-submenu.pull-left > .dropdown-menu {
  left: -100%;
  margin-left: 10px;
}
      
      
      
    </style>
    
<div class="navbar navbar-inverse navbar-default navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		    <span class="sr-only">Toggle navigation</span>
		    <span class="icon-bar"></span>
		    <span class="icon-bar"></span>
		    <span class="icon-bar"></span>
		  </button>
                    <a class="navbar-brand" href="index_comisiones.php">Cronos</a>
		</div>
		
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		  <ul class="nav navbar-nav">
		     <!-- Menú de Parametros  -->
                        <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Configuración <b class="caret"></b></a>
                          <ul class="dropdown-menu">

                              <li><a href="objetivos_comisiones.php">Objetivos Mensuales</a></li>
                            <li><a href="#">Objetivos Asertividad</a></li>
                            <li><a href="#">Cumplimiento Generales</a></li>


                          </ul>
                        </li>
                         <!-- Fin menu  de Parametros  -->		    
		    <li class="dropdown">
			 <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Comisiones 2016 <span class="caret"></span></a>
			  <ul class="dropdown-menu" role="menu">				          
			
			    <li class="dropdown-submenu">
			        <a tabindex="-1" href="#">% Cartera Vencida <i class="fa fa-chevron-right"></i></a>
			        <ul class="dropdown-menu">
			          <li><a tabindex="-1" href="calcula_cartera_comisiones.php?mes=1&anio=2016">Enero</a></li>			                  
			                                    
                                    <li><a href="calcula_cartera_comisiones.php?mes=2&anio=2016">Febrero</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=3&anio=2016">Marzo</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=4&anio=2016">Abril</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=5&anio=2016">Mayo</a></li> 
                                    <li><a href="calcula_cartera_comisiones.php?mes=6&anio=2016">Junio</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=7&anio=2016">Julio</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=8&anio=2016">Agosto</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=9&anio=2016">Septiembre</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=10&anio=2016">Octubre</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=11&anio=2016">Noviembre</a></li> 
                                    <li><a href="calcula_cartera_comisiones.php?mes=12&anio=2016">Diciembre</a></li>
                                     
			        </ul>
			    </li>	
                             <li class="dropdown-submenu">
			        <a tabindex="-1" href="#">% Asertividad <i class="fa fa-chevron-right"></i></a>
			        <ul class="dropdown-menu">
			           <li><a tabindex="-1" href="calculo_asertividad_comisiones.php?mes=1&anio=2016">Enero</a></li>			                  
			                                    
                                   <li><a href="calculo_asertividad_comisiones.php?mes=2&anio=2016">Febrero</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=3&anio=2016">Marzo</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=4&anio=2016">Abril</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=5&anio=2016">Mayo</a></li> 
                                    <li><a href="calculo_asertividad_comisiones.php?mes=6&anio=2016">Junio</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=7&anio=2016">Julio</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=8&anio=2016">Agosto</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=9&anio=2016">Septiembre</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=10&anio=2016">Octubre</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=11&anio=2016">Noviembre</a></li> 
                                    <li><a href="calculo_asertividad_comisiones.php?mes=12&anio=2016">Diciembre</a></li>
			        </ul>
			    </li>	
                             <li class="dropdown-submenu">
			        <a tabindex="-1" href="#">Cumple Generales <i class="fa fa-chevron-right"></i></a>
			        <ul class="dropdown-menu">
			            <li><a tabindex="-1" href="calculo_generales_comisiones.php?mes=1&anio=2016">Enero</a></li>			                  
			                                    
                                   <li><a href="calculo_generales_comisiones.php?mes=2&anio=2016">Febrero</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=3&anio=2016">Marzo</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=4&anio=2016">Abril</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=5&anio=2016">Mayo</a></li> 
                                    <li><a href="calculo_generales_comisiones.php?mes=6&anio=2016">Junio</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=7&anio=2016">Julio</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=8&anio=2016">Agosto</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=9&anio=2016">Septiembre</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=10&anio=2016">Octubre</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=11&anio=2016">Noviembre</a></li> 
                                    <li><a href="calculo_generales_comisiones.php?mes=12&anio=2016">Diciembre</a></li>
			        </ul>
			    </li>
                            
                             <li class="dropdown-submenu">
			        <a tabindex="-1" href="#">Calcular Comision <i class="fa fa-chevron-right"></i></a>
			        <ul class="dropdown-menu">
                                    <li><a tabindex="-1" href="ventas_comisiones.php?mes=1&anio=2016">Enero</a></li>			                  
			                                    
                                   <li><a href="ventas_comisiones.php?mes=2&anio=2016">Febrero</a></li>
                                    <li><a href="ventas_comisiones.php?mes=3&anio=2016">Marzo</a></li>
                                    <li><a href="ventas_comisiones.php?mes=4&anio=2016">Abril</a></li>
                                    <li><a href="ventas_comisiones.php?mes=5&anio=2016">Mayo</a></li> 
                                    <li><a href="ventas_comisiones.php?mes=6&anio=2016">Junio</a></li>
                                    <li><a href="ventas_comisiones.php?mes=7&anio=2016">Julio</a></li>
                                    <li><a href="ventas_comisiones.php?mes=8&anio=2016">Agosto</a></li>
                                    <li><a href="ventas_comisiones.php?mes=9&anio=2016">Septiembre</a></li>
                                    <li><a href="ventas_comisiones.php?mes=10&anio=2016">Octubre</a></li>
                                    <li><a href="ventas_comisiones.php?mes=11&anio=2016">Noviembre</a></li> 
                                    <li><a href="ventas_comisiones.php?mes=12&anio=2016">Diciembre</a></li>
			        </ul>
                                
			    </li>	
			   
			  </ul>
                         
                         
			</li> <!-- .dropdown -->
                         <li class="dropdown">
			 <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Comisiones 2017 <span class="caret"></span></a>
			  <ul class="dropdown-menu" role="menu">				          
			
			    <li class="dropdown-submenu">
			        <a tabindex="-1" href="#">% Cartera Vencida <i class="fa fa-chevron-right"></i></a>
			        <ul class="dropdown-menu">
			          <li><a tabindex="-1" href="calcula_cartera_comisiones.php?mes=1&anio=2017">Enero</a></li>			                  
			                                    
                                    <li><a href="calcula_cartera_comisiones.php?mes=2&anio=2017">Febrero</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=3&anio=2017">Marzo</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=4&anio=2017">Abril</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=5&anio=2017">Mayo</a></li> 
                                    <li><a href="calcula_cartera_comisiones.php?mes=6&anio=2017">Junio</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=7&anio=2017">Julio</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=8&anio=2017">Agosto</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=9&anio=2017">Septiembre</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=10&anio=2017">Octubre</a></li>
                                    <li><a href="calcula_cartera_comisiones.php?mes=11&anio=2017">Noviembre</a></li> 
                                    <li><a href="calcula_cartera_comisiones.php?mes=12&anio=2017">Diciembre</a></li>
                                     
			        </ul>
			    </li>	
                             <li class="dropdown-submenu">
			        <a tabindex="-1" href="#">% Asertividad <i class="fa fa-chevron-right"></i></a>
			        <ul class="dropdown-menu">
			           <li><a tabindex="-1" href="calculo_asertividad_comisiones.php?mes=1&anio=2017">Enero</a></li>			                  
			                                    
                                   <li><a href="calculo_asertividad_comisiones.php?mes=2&anio=2017">Febrero</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=3&anio=2017">Marzo</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=4&anio=2017">Abril</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=5&anio=2017">Mayo</a></li> 
                                    <li><a href="calculo_asertividad_comisiones.php?mes=6&anio=2017">Junio</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=7&anio=2017">Julio</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=8&anio=2017">Agosto</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=9&anio=2017">Septiembre</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=10&anio=2017">Octubre</a></li>
                                    <li><a href="calculo_asertividad_comisiones.php?mes=11&anio=2017">Noviembre</a></li> 
                                    <li><a href="calculo_asertividad_comisiones.php?mes=12&anio=2017">Diciembre</a></li>
			        </ul>
			    </li>	
                             <li class="dropdown-submenu">
			        <a tabindex="-1" href="#">Cumple Generales <i class="fa fa-chevron-right"></i></a>
			        <ul class="dropdown-menu">
			            <li><a tabindex="-1" href="calculo_generales_comisiones.php?mes=1&anio=2017">Enero</a></li>			                  
			                                    
                                   <li><a href="calculo_generales_comisiones.php?mes=2&anio=2017">Febrero</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=3&anio=2017">Marzo</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=4&anio=2017">Abril</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=5&anio=2017">Mayo</a></li> 
                                    <li><a href="calculo_generales_comisiones.php?mes=6&anio=2017">Junio</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=7&anio=2017">Julio</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=8&anio=2017">Agosto</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=9&anio=2017">Septiembre</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=10&anio=2017">Octubre</a></li>
                                    <li><a href="calculo_generales_comisiones.php?mes=11&anio=2017">Noviembre</a></li> 
                                    <li><a href="calculo_generales_comisiones.php?mes=12&anio=2017">Diciembre</a></li>
			        </ul>
			    </li>
                            
                             <li class="dropdown-submenu">
			        <a tabindex="-1" href="#">Calcular Comision <i class="fa fa-chevron-right"></i></a>
			        <ul class="dropdown-menu">
                                    <li><a tabindex="-1" href="ventas_comisiones.php?mes=1&anio=2017">Enero</a></li>			                  
			                                    
                                   <li><a href="ventas_comisiones.php?mes=2&anio=2017">Febrero</a></li>
                                    <li><a href="ventas_comisiones.php?mes=3&anio=2017">Marzo</a></li>
                                    <li><a href="ventas_comisiones.php?mes=4&anio=2017">Abril</a></li>
                                    <li><a href="ventas_comisiones.php?mes=5&anio=2017">Mayo</a></li> 
                                    <li><a href="ventas_comisiones.php?mes=6&anio=2017">Junio</a></li>
                                    <li><a href="ventas_comisiones.php?mes=7&anio=2017">Julio</a></li>
                                    <li><a href="ventas_comisiones.php?mes=8&anio=2017">Agosto</a></li>
                                    <li><a href="ventas_comisiones.php?mes=9&anio=2017">Septiembre</a></li>
                                    <li><a href="ventas_comisiones.php?mes=10&anio=2017">Octubre</a></li>
                                    <li><a href="ventas_comisiones.php?mes=11&anio=2017">Noviembre</a></li> 
                                    <li><a href="ventas_comisiones.php?mes=12&anio=2017">Diciembre</a></li>
			        </ul>                                
			    </li>			   
			  </ul>                        
			</li> <!-- .dropdown -->                        
		  </ul> <!-- .nav .navbar-nav -->
                  <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Salir: <?php echo $_SESSION["usuario_nombre"]; ?></a></li>
          </ul>
		</div><!-- /.navbar-collapse -->
		
	</div><!-- /.container-fluid -->
</div>
      

   <div class="container">
  