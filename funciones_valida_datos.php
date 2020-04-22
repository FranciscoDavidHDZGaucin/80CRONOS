<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :funciones_valida_datos.php  
 	Fecha  Creacion : 16/08/2016 
	Descripcion  : 
	Archivo   hecho  para contener  funciones de  validacion 
        
	Modificado  Fecha  : 
*/

///Inicio Validar  Datos  para un  Nuevo Destino  
function  valida_datos_new_destino ($calle,$colonia,$ciudad,$cp,$estado,$pais)
{
   
   ///***Areglo para mostrar mensajes de Errores 
   $_msnError =  array();
   ///***Validamos para Calle 
   if (empty($calle)) 
   {
       $_msnError[]="Error No Especifico la Calle";
    }
    ///***Validamos  Colonia 
     if(empty($colonia))
     {
         $_msnError[] =  "Error No Especifico la Colonia" ; 
     }
     ///***Validamos para Ciudad
    if(empty($ciudad))
    {
       $_msnError[]  ="Error No Especifico la Ciudad" ;
    }
    ///***Validamos C.P
    if(empty($cp)) 
    {
        $_msnError[]= "Error No Especifico el C.P";
    }
    ///***Validamos para Estado
    if(empty($estado))
    {
        $_msnError[] = "Error No Especifico el Estado";
    }
    ///***Validamos para Pais
    if(empty($pais)) 
    {
      $_msnError[] ="Error No Especifico el Pais";  
    }
    ///***Comparamos la   dimencion del  arreglo si  el   arreglo esta  vacio 
    //* retornamos   true   
    if(count($_msnError)>= 0)
    {
        $_result = $_msnError; 
    }
    else {
        $_result  = true;  
    }
   ///***Retornamos  Resultado   
   return   $_result;
    
}