<?php
 ///***testCorreo.php 

////Agregamos  Modulo Correos
require_once('correos.php');
$erik = "egonzalez@agroversa.com.mx";
$pancho = "fhernandez@agroversa.com.mx"; 
$mens = "Holi :V "; 
$from  = "Desviaciones Incompletas";
$suibj = "Desviaciones";
///****Validacion par  Enviar   Correo a los Agentes  ////****Mandamos bmesta@agroversa.com.mx
correos($from,$suibj,$erik,$pancho,$mens);
?> 