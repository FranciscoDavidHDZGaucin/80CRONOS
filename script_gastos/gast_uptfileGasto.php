<?php
///gast_uptfileGasto.php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : gast_uptfileGasto.php
 	Fecha  Creacion : 07/11/2017
	Descripcion  : 
*              Archivo  para  Subir  Imagenes de  Evidencias 
  */

 $Result_Up['estado'] =1;
 
 $concecutivo =1;
////****Funcion para  Generar la  Ruta del Archivo.
function    GeNerarRutaAlma ($tipo_archivo,$NombreArch)
{
        $NombreOr ="";
        $ruta = '../CFD_PAGOS/';
      /////*************************************
      if(strpos($tipo_archivo, "pdf"))
       {
            $NombreOr =$NombreArch;///.'.pdf'; ///$_FILES['ARCH']['name'];
       }else{
              if(strpos($tipo_archivo, "xml"))
                 {
                      $NombreOr = $NombreArch;///.'.xml';
                 }
       }
        $Destino  = $ruta.$NombreOr;
    
   return   $Destino;
} 
 
 
 ///*****Ciclo para  Agregar Archivos 
foreach ($_FILES as  $file){
                ///********************************************************
		if($file['error'] == UPLOAD_ERR_OK )
		{
			
			 $temporal = $file['tmp_name'];
                         $tipo_archivo = $file['type'];
                         $nombefile = $file['name'];
			
                         ///Validamos  el Archivo
                         if( strpos($tipo_archivo, "pdf")||strpos($tipo_archivo, "xml") )
                         {        
                            move_uploaded_file($temporal, GeNerarRutaAlma($tipo_archivo,$nombefile));   
                         $Result_Up['ruta']= GeNerarRutaAlma ($tipo_archivo,$nombefile);
                            
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