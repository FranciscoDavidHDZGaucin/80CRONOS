<?php
//Funcion para crear los correos
require("includes/class.phpmailer.php"); //Importamos la funci�n PHP class.phpmailer 
function correos($fromname, $subject,$destinatario,$descc,$mensaje)
{
//Mandar Correo de Aviso
	$mail = new PHPMailer(); 
	//header("Location:"."contacto_enviado.html"); 
	//Luego tenemos que iniciar la validaci�n por SMTP: 
	$mail->IsSMTP(); 
	$mail->IsHTML(true); // El correo se env�a como HTML
        $mail->CharSet="UTF-8";
	$mail->SMTPAuth = true; // True para que verifique autentificaci�n de la cuenta o de lo contrario False 
	 $mail->SMTPSecure = 'tls';
	$mail->Username = "notificaciones@agroversa.mx"; // Cuenta de e-mail 
	$mail->Password = "S3cur3K3y"; // Password 
						 
	$mail->Host = "smtp.office365.com"; 
	$mail->Port = 587; // Puerto a utilizar
	
	$mail->From = "notificaciones@agroversa.mx"; 
						
	$mail->FromName = $fromname; 
	$mail->Subject = $subject;
	//$mail->Timeout=5;   ///Par en casos de que necesitemos una respuesta pronta del servidor				
	$mail->AddAddress($destinatario,"Destinatario"); 
     
     //   if ($descc!=""){
     //       $mail->AddCC($descc,"Con Copia");
     //       AddBCC=con copia oculta
     //       AddCC=con copia
      //  }
      
	//Rutina para mandar los correos con copia a varios destinatarios
       foreach($descc as $descc=>$valor){
            $mail->AddCC(trim($valor),trim($valor));
        }
        
	$mail->AltBody = 'Para leer este mensaje, por favor use la vista compatibilidad con HTML, Gracias!';			   	
	$mail->Body = $mensaje;
	$mail->Send(); 
							
}


?>