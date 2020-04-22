<?php
////****pub_addevitbevidencia.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_addevitbevidencia.php 
 	Fecha  Creacion : 10/08/2017 
	Descripcion  : 
			Archivo  para  Agregar  Evidencias a las  Tabla pub_evidencia_publicidad

  */
 function GetFormatoArchivo($tipo_archivo)
{
      
      if(strcmp($tipo_archivo, "pdf")==0)
       {
            $NombreOr ='.pdf'; ///$_FILES['ARCH']['name'];
       }else{
              if(strcmp($tipo_archivo, "jpg")==0)
                 {
                      $NombreOr ='.jpg';
                 }else{ if(strcmp($tipo_archivo,"jpeg")==0)
                     {
                         $NombreOr ='.jpg';
                     }
                     else {
                             if(strcmp($tipo_archivo,"jpe")==0)
                             {
                                 $NombreOr ='.jpg';
                             }
                             else {
                                     if(strcmp($tipo_archivo, "jfif")==0)
                                     {
                                         $NombreOr ='.jpg';
                                     }
                                     else
                                     {
                                         if(strcmp($tipo_archivo, "png")==0||strcmp($tipo_archivo, "PNG")==0)
                                         {
                                            $NombreOr ='.png';
                                         }
                                     }  
                             } 
                     }   

                 } 
             
         }
       $Destino  = $NombreOr;
    
   return   $Destino;
}


///**Conexion Mysql 
include '../Connections/conecta1.php';
///***Formato  de Datosa
require_once('../formato_datos.php'); 

////***Obtenemos el  Folio 
$folio  = filter_input(INPUT_POST,'fl'); 
////***+Obtenemos  los  Formatos 
$JSON_DET =  filter_input(INPUT_POST, 'DET');

$Areglo_DET = json_decode($JSON_DET);
    ///*Varible Vaidacion Insert
    $ExDet =1;
    $conce = 1;

    
       
foreach ($Areglo_DET  as $ELM)
{
   $typeArchivo =$ELM->{'ARCH'} ;
   ///***Generamos el  Nombre del  Archivo 
    $direcci =  "EVI-".$folio."-".$conce. GetFormatoArchivo($typeArchivo); 
     ///**String  Insert  
    $String_prodInsert  = sprintf("Insert INTO pedidos.pub_evidencia_publicidad SET  folio =%s,nom_archivo =%s ,fech_up=Now()  ",
            GetSQLValueString($folio, "int"),
            GetSQLValueString($direcci, "text"));
    $qery_det_pub = mysqli_query($conecta1, $String_prodInsert);
    if(!$qery_det_pub){ 
        $ExDet =0;
        BREAK;
        
    } ///Fail  Insert 
    $conce++;
}
if($ExDet==1)
{
        ////***Obtenemos el Id  del  Numero de  Folio 
     $stringGetID = sprintf("select id  from pedidos.pub_encabeza_publicidad   where  pub_folio =%s",
      GetSQLValueString($folio, "int"));
     $qery_Getid = mysqli_query($conecta1,$stringGetID);
     $fetch_getId = mysqli_fetch_array($qery_Getid);


     ////****Generamos  Cadena  De insercion 
     $String_Insert_Cabezera = sprintf("Update pub_encabeza_publicidad set est_evidecia=1  where  id = %s",
      GetSQLValueString($fetch_getId['id'], "int"));
     ///****Generamos  Qery  
     $qery_InsertEncabeza = mysqli_query($conecta1, $String_Insert_Cabezera) ; 
     ///***Validar  Qery  Cabeza
     if(!$qery_InsertEncabeza)
     {   ///***Error insert Consulta 
        $ExDet = 0; 
     }else{
         ///**Insert Correct
         $ExDet = 1; }
}




 $pub_arreglo  =   Array(
 			 "Res002" => $ExDet ///Retornamos  Resultado  Detalle.
 	);
///**Convertimos a  Json  
  $convert_json  =  json_encode($pub_arreglo);
  header('Content-type: application/json');
echo  $convert_json ;


?> 