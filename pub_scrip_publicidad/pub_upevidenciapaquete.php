<?php
////****pub_upevidenciapaquete.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_upevidenciapaquete.php
 	Fecha  Creacion : 10/08/2017
	Descripcion  : 
*              Archivo  para  Subir  Imagenes de  Evidencias 
  */

/////*****Obteneos el Objeto 
$folio = $_POST['fl'];


 $Result_Up['estado'] =1;
////****Funcion para  Generar la  Ruta del Archivo.
function    Get_New_Ruta_Archivo ($folio,$tipo_archivo,$concecutivo)
{
        $NombreOr ="";
        $ruta = '../pub_evi/EVI-';
      /////*************************************
      if(strpos($tipo_archivo, "pdf"))
       {
            $NombreOr =$folio.'-'.$concecutivo.'.pdf'; ///$_FILES['ARCH']['name'];
       }else{
              if(strpos($tipo_archivo, "jpg"))
                 {
                      $NombreOr =$folio.'-'.$concecutivo.'.jpg';
                 }else{
                     if(strpos($tipo_archivo, "jpeg"))
                     {
                         $NombreOr =$folio.'-'.$concecutivo.'.jpg';
                     }
                     else {
                             if(strpos($tipo_archivo,"jpe"))
                             {
                                 $NombreOr =$folio.'-'.$concecutivo.'.jpg';
                             }
                             else {
                                     if(strpos($tipo_archivo, "jfif"))
                                     {
                                         $NombreOr =$folio.'-'.$concecutivo.'.jpg';
                                     }
                                     else
                                     {
                                         if(strpos($tipo_archivo, "png")||strpos($tipo_archivo, "PNG"))
                                         {
                                            $NombreOr =$folio.'-'.$concecutivo.'.png';
                                         }
                                     }  
                             } 
                     }   

                 } 
             
         }
        $Destino  = $ruta.$NombreOr;
    
   return   $Destino;
}
$concecutivo =1;
foreach ($_FILES as  $file){
                ///********************************************************
		if($file['error'] == UPLOAD_ERR_OK )
		{
			
			 $temporal = $file['tmp_name'];
                         $tipo_archivo = $file['type'];
			
                         ///Validamos  el Archivo
                         if( strpos($tipo_archivo, "pdf")||strpos($tipo_archivo, "jpg")||strpos($tipo_archivo, "png")||strpos($tipo_archivo, "jpeg")||strpos($tipo_archivo, "jpe") )
                         {        
                            move_uploaded_file($temporal, Get_New_Ruta_Archivo($folio,$tipo_archivo,$concecutivo));   
                         $Result_Up['ruta']= Get_New_Ruta_Archivo($folio,$tipo_archivo,$concecutivo);
                            
                            }
                         else
                         {
                             $Result_Up['estado']=0;
                         }
                         ////*****************************************
                   
                            
                         ////******************************************
		}
		if ($file['error']=='') //Si no existio ningun error, retornamos un mensaje por cada archivo subido
		{   
                    if($Result_Up['estado']!= 0)
                    { 
                       /// $Result_Up['estado']=1;
                        $Result_Up['estado'] =$concecutivo;
			$mensage = '-> Archivo <b>'.$NombreOr.'</b> Subido correctamente. <br>'.$Error_tipo ;
                     $Result_Up['msg']=$mensage;
                        
                    }   
		}
	        if ($file['error']!='')//Si existio algún error retornamos un el error por cada archivo.
		{
			$mensage = '-> No se pudo subir el archivo <b>'.$NombreOr.'</b> debido al siguiente Error: n'.$file['error']; 
                        $Result_Up['estado']=2;
                           $Result_Up['msg']=$mensage;
                 BREAK;        
                }
                //*Aumentamos
                $concecutivo++;
                
}                
                
                ///echo $mensage;
                ///***Retornamos  Error
                header('Content-type: application/json');
                echo json_encode($Result_Up);



?>
