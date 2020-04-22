<div  class="form-group-inline">     
	<?php
	
	  switch ($_SESSION['tipousuario_proyeccion']) {
			case 101:
						//Agente

				  echo ' <a href="listado.php" class="btn btn-info">Reporte</a>';
                             $res_acceso=  modificar_proyeccion($_SESSION['tipousuario_proyeccion']);
                             if ($res_acceso==1){
                                  echo '  <a href="crear1.php" class="btn btn-info">Captura</a>';	
                             }else{
                                 echo '  <a href="#" class="btn btn-default">Captura</a>';	
                             }
                            
				 	
			    break;
					  
			case 100:		  
				//Planeador

					  break;

			default:
					//Gerente
					echo ' <a href="listado2.php" class="btn btn-info">Reporte</a>';
                               $res_acceso=  modificar_proyeccion($_SESSION['tipousuario_proyeccion']);
                             if ($res_acceso==1){
                                  echo '  <a href="crear12.php" class="btn btn-info">Captura</a>';
                             }else{
                                 echo '  <a href="#" class="btn btn-default">Captura</a>';
                             }
				 
				break;
}   
	
	
	
	
	?>
	
	
	
			 
			
	
   </div>	