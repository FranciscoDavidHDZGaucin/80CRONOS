<?php
////*pub_subirImgprod.php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_subirImgprod.php
 	Fecha  Creacion : 09/06/2017
	Descripcion  : 
*              Archivo  para  Subir  Imagenes de  un determinado producto 
  */

///**Conexion Mysql 
include '../Connections/conecta1.php';
///***Formato  de Datosa
require_once('../formato_datos.php'); 


/////*****Obteneos el Objeto 
$ObjetoInf = json_decode($_POST['OBJ']);
//***Obtener  Id  del  Producto  
$strGetIdprod = sprintf("select id from  pedidos.pub_catalogo_publicidad where  codig_prod =%s", GetSQLValueString($ObjetoInf->{'cve_prod'}, "text"));
$max_id_cre_pagares = mysqli_query($conecta1,$strGetIdprod ) or die(mysqli_error($conecta1));
$res_id = mysqli_fetch_array($max_id_cre_pagares);
$plus_one_id = $res_id['id'];


 $Result_Up['estado'] =1;
////****Funcion para  Generar la  Ruta del Archivo.
function    Get_New_Ruta_Archivo ($cve_prod,$tipo_archivo,$plus_one_id)
{
        $NombreOr ="";
        $ruta = '../pub_catalogo/';
     
                if(strpos($tipo_archivo, "jpg"))
                 {
                      $NombreOr =$cve_prod.'-'.$plus_one_id.'.jpg';
                 }else{
                     if(strpos($tipo_archivo, "jpeg"))
                     {
                         $NombreOr =$cve_prod.'-'.$plus_one_id.'.jpg';
                     }
                     else {
                             if(strpos($tipo_archivo,"jpe"))
                             {
                                 $NombreOr =$cve_prod.'-'.$plus_one_id.'.jpg';
                             }
                             else {
                                     if(strpos($tipo_archivo, "jfif"))
                                     {
                                         $NombreOr =$cve_prod.'-'.$plus_one_id.'.jpg';
                                     }
                                     else
                                     {
                                         if(strpos($tipo_archivo, "png")||strpos($tipo_archivo, "PNG"))
                                         {
                                            $NombreOr =$cve_prod.'-'.$plus_one_id.'.png';
                                         }
                                     }  
                             } 
                     }   

                 } 
             
         
       
        $Destino  = $ruta.$NombreOr;
    
   return   $Destino;
}
///********************************************************
		if($_FILES['ARCH']['error'] == UPLOAD_ERR_OK )
		{
			
			 $temporal = $_FILES['ARCH']['tmp_name'];
                         $tipo_archivo = $_FILES['ARCH']['type'];
			
                         ///Validamos  el Archivo
                         if(strpos($tipo_archivo, "jpg")||strpos($tipo_archivo, "png")||strpos($tipo_archivo, "jpeg")||strpos($tipo_archivo, "jpe") )
                         {        
                            move_uploaded_file($temporal, Get_New_Ruta_Archivo($ObjetoInf->{'cve_prod'},$tipo_archivo,$plus_one_id));   
                         $Result_Up['ruta']= Get_New_Ruta_Archivo($ObjetoInf->{'cve_prod'},$tipo_archivo,$plus_one_id);
                            
                            }
                         else
                         {
                             $Result_Up['estado']=0;
                         }
                         ////*****************************************
                   
                            
                         ////******************************************
		}
		if ($_FILES['ARCH']['error']=='') //Si no existio ningun error, retornamos un mensaje por cada archivo subido
		{   
                    if($Result_Up['estado']!= 0)
                    { 
                        $Result_Up['estado']=1;
			$mensage = '-> Archivo <b>'.$NombreOr.'</b> Subido correctamente. <br>'.$Error_tipo ;
                     $Result_Up['msg']=$mensage;
                        
                    }   
		}
	        if ($_FILES['ARCH']['error']!='')//Si existio algún error retornamos un el error por cada archivo.
		{
			$mensage = '-> No se pudo subir el archivo <b>'.$NombreOr.'</b> debido al siguiente Error: n'.$_FILES['ARCH']['error']; 
                        $Result_Up['estado']=2;
                           $Result_Up['msg']=$mensage;
                        
                }
                ///echo $mensage;
                ///***Retornamos  Error
                header('Content-type: application/json');
                echo json_encode($Result_Up);



?>
