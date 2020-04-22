<?php
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :obj_proyeccion.php  
 	Fecha  Creacion : 20/01/2017
	Descripcion  : 
	   Script   Que  determina  el  Objeto   Proyecciones 
	Modificado  Fecha  : 
  *             20/01/2017    Se genero estrucura  prototipo
  *             21/01/2017    Se  Define las  Validaciones de   cada  uno de los campos 
  *                           del  objeto   
*/
///***Formato  de Datosa
require_once('../formato_datos.php');
include('../Connections/MysqlI.php');

class  Proyeccion {
   
    public   $num_elem;   ///N# de  Elemento en el  Archivo
     public $cve_almacen; //Clave  del Almacen 
     public  $nom_almacen; //Nombre del Almacen
     public  $cve_agente; //Clave  del Agentes
     public  $nom_agente; //Nombre del  Agente
     public  $cve_producto ; 
     public  $year;       // Año 
     public  $mes;  
     public  $cant_dem; //Cantidad  de  Damanda
     public  $result_val;
     private   $OBJ_MYSQL;


    ///*****Variables  Contenedoras Error ********************************************
     /*
      * Entiendase Que :  **** $error_obj ******
      *      error_obj = 0 -> El Objeto Esta Mal 
      *      error_obj = 1 -> El Objeto Esta Bien 
      *      error_obj = 2 -> El Objeto Esta Inser
      *      error_obj = 3 -> El Objeto Esta Update 
      */
     public  $error_obj= 1; 
     public  $num_campos_error;
     public  $nombre_campos_error; 
     public  $error_mesage; 
     ////*****Campos para  Almacenar  Resultados 
     public  $qery_type ; ////Entiendase  como 0=> Error  1 => Insert  2=> Update

     
     public function __construct ($num_elem,$cve_alma,$cve_agente,$cve_prod,$mes,$year,$demanda)
     {  
         $this->num_elem=$num_elem;
         $this->cve_almacen=$cve_alma ;
       //  $this->nom_almacen=$nom_almacen ;
         $this->cve_agente=$cve_agente; 
       //  $this->nom_agente=$nom_agente;
         $this->year=  $year;
         $this->mes=$mes;
         $this->cant_dem=$demanda;
         $this->cve_producto =$cve_prod;
     
        ////**** 
     }
     public function  Set_error_obj($var)
     {
         $this->error_obj =$var; 
     }  
     public function  Set_num_campos_error($var)
     {
          $this->num_campos_error =$var;
     }  
     public function  Set_nombre_campos_error($var)
     {
          $this->nombre_campos_error=$var;   
     }  
     public function  Set_error_mesage($var)
     {
         $this->error_mesage =$var;
     } 
    
     /////***********************************
     public function   Get_Cve_Almacen()
     {
         return  $this->cve_almacen; 
     }
     public function   Get_Cve_Agente()
     {
         return  $this->cve_agente; 
     }
     public function   Get_Cve_producto()
     {
         return  $this->cve_producto; 
     }
      public function   Get_num_elem()
     {
         return  $this->num_elem; 
     }
     public  function   Get_Mes()
     {
         return $this->mes;
     }
     public   function   Get_Year()
     {
         return $this->year;
     }////$cant_dem
     public   function   Get_Demanda()
     {
         return $this->cant_dem;
     }
 
} 
?> 

