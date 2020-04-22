<?php

/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : validar_informacion.php  
 	Fecha  Creacion : 24/01/2017
	Descripcion  : 
                Script  Diseñado  par  validar cada  uno de los  elementos 
 *              Solo  agregar  los estados  de cada  uno de  los elementos 
	Modificado  Fecha  : 
*/

//  Include PHPExcel_IOFactory
include '../Classes/PHPExcel/IOFactory.php';
include '../Connections/conecta1.php';
include 'obj_proyeccion.php';
////***Obtenemos  todos los  elementos  Json 
$json_arreglo = filter_input(INPUT_POST, 'ELEMENTOS'); 
////***Obtenemos  el  numero  de  elementos  existentes
$num_elemntos = filter_input(INPUT_POST,'NELEM');
////***Convertimos   el  Json  en  el  areglo de objetos 
$Arreglo_Obj_JSON =  json_decode($json_arreglo);
//**Definimos el  Arreglo  Contenedor de todas  las  Proyecciones 
$areglo_proyecciones  =  array();
//***Definimos   Arreglo para alamcenar  Proyecciones con errores 
$arre_Error_ = array();
////***Definimos  la  varibleq que  Determinara  si es  Correcta la Informacion o  NO 
$auto_for_send = 1 ; ///Entiendase  que $auto_for_send = 1 => Proyeccion Autorizada  Pero  $auto_for_send = 0  => Proyeccion Denegada 

for($j =0 ; $j<$num_elemntos-1;  $j ++)
{  
    /////***Obtenemos El  Objeto del  Json 
   $arreglo  =  (array) $Arreglo_Obj_JSON[$j];  
   ///**Generamos el  Objeto 
   $obj_Proye = new Proyeccion($arreglo['num_elem'],$arreglo['cve_almacen'],$arreglo['cve_agente'],$arreglo['cve_producto'],$arreglo['mes'],$arreglo['year'],$arreglo['cant_dem']);
   ///** Generamos cadena de consulta Para  Validar  la  Existencias En Almacen
   $string_exis_alma  = sprintf("Select  count(*) AS RE from  almacenes_proyeccion where almacen=%s &&   agente=%s",
                                       GetSQLValueString($obj_Proye->Get_Cve_Almacen(), "int"),
                                       GetSQLValueString( $obj_Proye->Get_Cve_Agente(), "int"));
  ////****Generamos Cadena  para  validar el  Producto 
   $string_exis_pro  = sprintf("SELECT count(*) AS RE FROM productos  where cve_prod =%s",
                                GetSQLValueString($obj_Proye->Get_Cve_producto(), "text")); 
  
                  ////****Generamos  Objeto Mysql*******
                $mysqli_PRO = new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1);                      
                if (!$ROW=$mysqli_PRO->query($string_exis_alma))
                {
                  $obj_Proye->Set_error_mesage("Error en Conexion !!!!!".$string_exis_alma);
                  $auto_for_send = 0;
                  $obj_Proye->Set_error_obj(0);
                  array_push($arre_Error_, $obj_Proye->Get_num_elem());
                  
                }else{
            
                 ///***Convertimos    asocitivo  la  respuesta
                   $RESUL =$ROW->fetch_array(MYSQLI_ASSOC); ///$ROW->fetch_array(MYSQLI_ASSOC);
                    if($RESUL['RE']==1)
                    {
                      /// $OBJ_->error_obj =1 ;
                         $obj_Proye->Set_error_obj(1);
                         $obj_Proye->Set_error_mesage("Exito");
                    }else{
                       
                        $obj_Proye->Set_num_campos_error($j);
                        $obj_Proye->Set_error_mesage("Campo  Almacen  o  Clave Agente");
                        $obj_Proye->Set_error_obj(0);
                        $auto_for_send = 0;
                        array_push($arre_Error_, $obj_Proye->Get_num_elem());
                    } 
                }
                ////**********************
                if($obj_Proye->error_obj == 1 )
                {

                    if (!$ROW=$mysqli_PRO->query($string_exis_pro))
                    {
                      $obj_Proye->Set_error_mesage("Error en Conexion !!!!!".$string_exis_alma);
                      $auto_for_send = 0;
                      $obj_Proye->Set_error_obj(0);
                      array_push($arre_Error_, $obj_Proye->Get_num_elem());
                    }else{

                     ///***Convertimos    asocitivo  la  respuesta
                       $RESUL_pro =$ROW->fetch_array(MYSQLI_ASSOC); ///$ROW->fetch_array(MYSQLI_ASSOC);
                        if($RESUL_pro['RE']==1)
                        {
                          /// $OBJ_->error_obj =1 ;
                             $obj_Proye->Set_error_obj(1);
                             $obj_Proye->Set_error_mesage("Exito");
                        }else{

                            $obj_Proye->Set_num_campos_error($j);
                            $obj_Proye->Set_error_mesage("Campo Cve Producto");
                            $obj_Proye->Set_error_obj(0);
                            $auto_for_send = 0;
                            array_push($arre_Error_,$obj_Proye->Get_num_elem());
                        } 

                    }
                }    
           ////***Agregamo elemento  Arreglo    
    array_push($areglo_proyecciones,$obj_Proye) ;    
}
$obj_Proye_K = array();
////******Busca   Repetidos 
for($k=0 ; $k<$num_elemntos-1; $k++)
{
      /////***Obtenemos El  Objeto del  Json 
   $arreglo  =  (array) $Arreglo_Obj_JSON[$k];  
   ///**Generamos el  Objeto 
   $obj_Proye_K = new Proyeccion($arreglo['num_elem'],$arreglo['cve_almacen'],$arreglo['cve_agente'],$arreglo['cve_producto'],$arreglo['mes'],$arreglo['year'],$arreglo['cant_dem']);
    for ($L =0 ; $L <$num_elemntos-1 ;$L++)
                {
                       /////***Obtenemos El  Objeto del  Json 
                $arreglo_L  =  (array) $Arreglo_Obj_JSON[$L];  
                ///**Generamos el  Objeto 
                $obj_Proye_L = new Proyeccion($arreglo_L['num_elem'],$arreglo_L['cve_almacen'],$arreglo_L['cve_agente'],$arreglo_L['cve_producto'],$arreglo_L['mes'],$arreglo_L['year'],$arreglo_L['cant_dem']);
                ///***Que sea  diferente al  numero  a compara  y si cumple  todas  
                       //las conticiones es  un elemeto Repetido
                    if (($k !=$L)&&($obj_Proye_K->Get_Cve_Agente()==$obj_Proye_L->Get_Cve_Agente() &&$obj_Proye_K->Get_Cve_Almacen()==$obj_Proye_L->Get_Cve_Almacen()&& strcasecmp($obj_Proye_K->Get_Cve_producto(),$obj_Proye_L->Get_Cve_producto())==0  &&$obj_Proye_K->Get_Mes()==$obj_Proye_L->Get_Mes()&&$obj_Proye_K->Get_Year()==$obj_Proye_L->Get_Year())) 
                    {
                            $areglo_proyecciones[$k]->Set_num_campos_error($k);
                            $areglo_proyecciones[$k]->Set_error_mesage("Campo Duplicado");
                            $areglo_proyecciones[$k]->Set_error_obj(0);
                                     $auto_for_send = 0;
                                     array_push($arre_Error_,$areglo_proyecciones[$k]->Get_num_elem());
                    }

                }
    
}

   $array_json  = array(
           "Escrip_Ejecutado"=> "Escrip Validar  Informacion",
           "AUTO_PARA_ENVIO" =>$auto_for_send ,
           "NElemento"=>$j,
           "elem_val"=>  json_encode($areglo_proyecciones),
           "Elem_ERRO"=>$arre_Error_
           
         );        
        $json_resultado = json_encode($array_json);
   ///****Fin Condicion  
        ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
    echo $json_resultado; 




?>
