<?php 
 ///***pub_addcatalogoprod.php 
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_addcatalogoprod.php 
 	Fecha  Creacion : 05/05/2017 
	Descripcion  : 
			Archivo  para    Agregar Un Producto Nuevo  al  Catalogo  
  *                     Varible de  Resultados  $ExitCAB
 *                      Entiendase :  
 *                              $ExitCAB => 0  => Error en  La Insercion O Update 
 *                              $ExitCAB => 1  => Insercion  y Update  Correctos
 *                              $ExitCAB => 2  => Error  En el Formato de  la  Imagen
 *                              $ExitCAB => 3  => Error  Clave  Repetida Al momento de querer Insertar
       Modificaciones : 
  *                     13/06/2017    MModificamos para que pueda  Modificar  el Producto  
  *                                   para  eso  se inicia la varibele  
  *                                   $ACCION_apli  Entiendase :                     
                                              $ACCION_apli => 1 => Insertar ; 
                                              $ACCION_apli => 2 => Update ;
  * 
  * *////**Conexion Mysql 
include '../Connections/conecta1.php';
///***Formato  de Datosa
require_once('../formato_datos.php'); 
////***FUncion para  Obtener la  Imagen 
///****Funcion para Reconocer  los   diferentes  formatos Jpg 
function   Get_Format_Img ($tipo_archivo)
{
         
    if((strcmp($tipo_archivo, "jpg")==0)||(strcmp($tipo_archivo, "jpeg")==0)||(strcmp($tipo_archivo,"jpe")==0)||(strcmp($tipo_archivo, "jfif")==0) )
    {
          $NombreOr ='jpg';
    }else{                
        if((strcmp($tipo_archivo, "png")==0)|| (strcmp($tipo_archivo, "PNG")==0))
        {
           $NombreOr ='png';
        }else{
            $NombreOr ="INCOM";
            
        }  
    }  
   return   $NombreOr; 
}
///********************************************************
///***Obtenemos  los  Arreglos  json 		
$JSON_PROD=  filter_input(INPUT_POST, 'OBJ');
 $formato = filter_input(INPUT_POST, 'FOR');
$ACCION_apli = filter_input(INPUT_POST, 'ACCI');
////***Validamos el Formato del archivo 
IF(strcmp(Get_Format_Img ($formato),"INCOM")==0)
{  ////***Error en el Formato 
   $ExitCAB = 2;  
    
}else{  
            ///***Convertimos el Encabe A JSON
            $Arreglo_fetch = json_decode($JSON_PROD );
             //**Obtenemos los  Valores  
            $cve_prod = $Arreglo_fetch->{'cve_prod'} ; ///cLAVEAS 
            $nom_prod = $Arreglo_fetch->{'nom_prod'} ;///Fecha
            $PU= $Arreglo_fetch->{'PU'} ;///Zona 
            $Cant= $Arreglo_fetch->{'Cant'} ;///Region o Unidad
            $preTotal= $Arreglo_fetch->{'preTotal'} ;///Cliente
             $descrip= $Arreglo_fetch->{'DES'} ;
             
             
             
             
        /////***Validacion para Detectar  Entero 
        IF($ACCION_apli==1 ){
        
                
         //***Obtener  Id  del  Producto  
            $strGetIdprod = sprintf("select id from  pedidos.pub_catalogo_publicidad where  codig_prod =%s", GetSQLValueString($cve_prod, "text"));
            $max_id_cre_pagares = mysqli_query($conecta1,$strGetIdprod );
           $NumRows = mysqli_num_rows($max_id_cre_pagares); 
         ////**** Validar que el registro   No exista en  la   Tabla  asd
           if($NumRows == 0 ||$NumRows ==NULL ){
                ////****Generamos  Cadena  De insercion 
                 $String_Insert_Prod = sprintf("Insert INTO pub_catalogo_publicidad  SET codig_prod=%s,articulo=%s,precio_unitario=%s,cantidad=%s,precio_total=%s,descripcion=%s ",
                 GetSQLValueString($cve_prod, "text"),
                 GetSQLValueString($nom_prod, "text"),
                 GetSQLValueString($PU, "double"),
                 GetSQLValueString($Cant, "int"),
                 GetSQLValueString($preTotal, "double"),
                 GetSQLValueString($descrip, "text"));
                ///****Generamos  Qery  
                $qery_InsertProd = mysqli_query($conecta1, $String_Insert_Prod) ; 
                ///***Validar  Qery  Cabeza
                if(!$qery_InsertProd)
                {   ///***Error insert Consulta 
                   $ExitCAB = 0; 
                }else{
                    ///**Insert Correct
                    $ExitCAB = 1; 
                    //***Obtener  Id  del  Producto  
                    $strGetIdprod = sprintf("select id from  pedidos.pub_catalogo_publicidad where  codig_prod =%s", GetSQLValueString($cve_prod, "text"));
                    $max_id_cre_pagares = mysqli_query($conecta1,$strGetIdprod );
                    $res_id = mysqli_fetch_array($max_id_cre_pagares);
                    $plus_one_id = $res_id['id'];
                    ////****Obtenemos la   rutal del  aarchivo
                    $RootImg = Get_Format_Img($formato);
                    ////***Cadena Para  Agregar el  Nombre de la Imagen a la Bd 
                    $strSetIg = sprintf("UPDATE pub_catalogo_publicidad  SET  imagen_prod=%s  where id =%s ", GetSQLValueString($cve_prod."-".$plus_one_id.".".$RootImg , "text"),GetSQLValueString($plus_one_id, "int") );
                    ///**Qery 
                    $qerUpdate  = mysqli_query($conecta1,$strSetIg );
                    if(!$qerUpdate)
                    {   ///***Error insert Consulta 
                       $ExitCAB = 0; 
                    }


                }
         }else{
             $ExitCAB = 3 ;///Error Clave  Repetida
             
         }
         
        }////******Fin Codigo Insert**********************
        /////***Validacion para Detectar Update 
        IF($ACCION_apli==2 ){
           //***Obtener  Id  del  Producto  
            $strGetIdprod = sprintf("select id,imagen_prod from  pedidos.pub_catalogo_publicidad where  codig_prod =%s", GetSQLValueString($cve_prod, "text"));
            $max_id_cre_pagares = mysqli_query($conecta1,$strGetIdprod );
            $res_id = mysqli_fetch_array($max_id_cre_pagares);
            $plus_one_id = $res_id['id']; 
            ///**Eliminamos la Imagen Anterior a
            unlink('../pub_catalogo/'.$res_id['imagen_prod']); 
            
            
            
        ////****Generamos  Cadena  De insercion 
        $String_Insert_Prod = sprintf("UPDATE  pub_catalogo_publicidad  SET articulo=%s,precio_unitario=%s,cantidad=%s,precio_total=%s,descripcion=%s where id=%s ",
         GetSQLValueString($nom_prod, "text"),
         GetSQLValueString($PU, "double"),
         GetSQLValueString($Cant, "int"),
         GetSQLValueString($preTotal, "double"),
         GetSQLValueString($descrip, "text"),GetSQLValueString($plus_one_id, "int"));
        ///****Generamos  Qery  
        $qery_InsertProd = mysqli_query($conecta1, $String_Insert_Prod) ; 
        ///***Validar  Qery  Cabeza
        if(!$qery_InsertProd)
        {   ///***Error insert Consulta 
           $ExitCAB = 0; 
        }else{
            ///**Insert Correct
            $ExitCAB = 1; 
           
            ////****Obtenemos la   rutal del  aarchivo
            $RootImg = Get_Format_Img($formato);
            ////***Cadena Para  Agregar el  Nombre de la Imagen a la Bd 
            $strSetIg = sprintf("UPDATE pub_catalogo_publicidad  SET  imagen_prod=%s  where id =%s ", GetSQLValueString($cve_prod."-".$plus_one_id.".".$RootImg , "text"),GetSQLValueString($plus_one_id, "int") );
            ///**Qery 
            $qerUpdate  = mysqli_query($conecta1,$strSetIg );
            if(!$qerUpdate)
            {   ///***Error insert Consulta 
               $ExitCAB = 0; 
            }


        }
        
        }////******Fin Codigo Update**********************
        
        
        
        
        
        
}

 $pub_arreglo  =   Array(
 			"Res001" => $ExitCAB  ///Retornamos  Resultado Insert Cabecera
                       
 	);
///**Convertimos a  Json  
  $convert_json  =  json_encode($pub_arreglo);
  header('Content-type: application/json');
echo  $convert_json ;


?> 