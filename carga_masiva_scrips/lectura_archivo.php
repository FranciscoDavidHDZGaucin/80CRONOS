<?php
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : lectura_archivo.php  
 	Fecha  Creacion : 14/01/2017
	Descripcion  : 
  *         Escrip  Diseñado  para  leer  un archivo de  Excel  para  Obtener 
  *         los  datos  para   una  posterior  insercion de datos en la  Base de  Datos 
  *         
  *  
	
	Modificado  Fecha  : 
  * 
  *      16/01/2017   Se  termino de  Diseñar   el    prototipo  base  para el  analisis  del  
  *                   archivo  de  ecel sin embargo no cuenta con  validacion de  formato.
  *      20/01/2017   Se terminos de  Definir el  string   encargado   de  almacenar  toda  la 
  *                    informacion en  formato html.
  *                    Incio  de  Estructura  del  Objeto  Proyeccion   para   el  tratado  de la informacion.   
  *                   
  *                    
*/

//  Include PHPExcel_IOFactory
include '../Classes/PHPExcel/IOFactory.php';
include '../Connections/conecta1.php';
include 'obj_proyeccion.php';


/********Funcion para Generar Html 
 *   Nombre  Funcion: get_html_Table
 *   Entrada  de la  Funcion :   
 *                           **** Hoja Seleccionada : $sheet
 *                           **** N#  Renglones Tabla: $highestRow
 *                           **** N#  De columnas: $highestColumn     
 *   Salida  de  la  Funcion  :  Strig  con  codigo HTML 
  *************************************/   
function   get_html_Table ($sheet ,$highestRow,$highestColumn) 
{
     $string_cadena_html =    '<table  class="table  table-condensed ">'. 
                      '<thead>'.
                         '<tr>'.
                           '<th>Clave Agente</th>'.
                           '<th>Clave Almacen</th>'.
                           '<th>Clave Producto</th>'.
                           '<th>Mes</th>'.
                           '<th>Year</th>'.
                           '<th>Demanda</th>'.
                           '</tr>'.
                      '</thead>';
                $string_html_2 = '<tbody>';
   //Ciclo  para Obtner el  Renglon del  Documento  Excel
                for ($row = 2; $row <= $highestRow; $row++){ 

                 //  Almacenamos la Informacion en  un arenglo  la  informacion de captura por  fila
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL, TRUE, FALSE);
                     $string_html_2 .= '<tr id =ID'.$row.'>';
                  
                    foreach($rowData[0] as $k=>$v)
                    {
                        ////***
                       // echo   '<br><p>'."Row: ".$row."- Col: ".($k+1)." = ".$v.'</p>' ;///."<br />";
                      
                        if($k+1 == 1||$k+1 == 2 || $k+1 == 3 || $k+1 == 4 || $k+1 == 5 ||$k+1 == 6 ) //  ) && $k+1 == 1  )
                        {
                            $string_html_2.='<th>'.$v.'</th>';
                            
                        }     

                    }
                    $string_html_2.='</tr>';
               }
               ////****** Fin Ciclo 
                $string_html_2.'</tbody>';
                $string_html_2.'</table>';
                //////*****Concatenamos la  Cadena 
              $string_html =    $string_cadena_html.$string_html_2; 
                
 return   $string_html;    
}


///**  Validamos el  archivo  si  se cargo  correctamente
if($_FILES['ARCH']['error'] == UPLOAD_ERR_OK)
{
    ///***Obtenemos la  Direccion  
    $archivo = $_FILES['ARCH']['tmp_name'];
    $inputFileName = $archivo;

        //  Leemos el  Archivo  Excel 
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        ///***Espesificamos  la  Hoja  de excel de la cual  vamos  a extraer  la  informacion 
        $sheet = $objPHPExcel->getSheet(0); 
        //Obtnemos las dimensiones  del Archivo
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
      $areglo_proyecciones =  array();
        /////*********************************************************
       for ($row = 2, $i=1 ; $row <= $highestRow; $row++){ 
                //  Almacenamos la Informacion en  un arenglo  la  informacion de captura por  fila
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL, TRUE, FALSE);
                    foreach($rowData[0] as $k=>$v)
                    {
                       
                        /* echo   '<br><p>'."Row: ".$row."- Col: ".($k+1)." = ".$v.'</p>' ;///."<br />";
                        if($k+1 == 1||$k+1 == 2 || $k+1 == 3 || $k+1 == 4 || $k+1 == 5 ||$k+1 == 6 ) //  ) && $k+1 == 1  )
                        {
                            $string_html_2.='<th>'.$v.'</th>';
                            
                        }*/
                       
                        switch ($k+1)
                        {
                           case 1 : 
                                $cve_agente = $v;
                           break;
                           case 2 : 
                               $cve_almacen= $v;
                           break;
                           case 3 :
                               $cve_prod= $v;
                           break;
                           case 4 : 
                               $mes= $v;
                           break;
                           case 5 : 
                               $year= $v;
                           break;
                           case 6 : 
                               $demanda= $v;
                           break;
                        }
                         
                     }
                  
                     //$areglo_proyecciones['ID'.$row]=new Proyeccion($row,$cve_almacen,$cve_agente,$cve_prod,$mes,$year,$demanda) ;
                 array_push( $areglo_proyecciones,new Proyeccion($row,$cve_almacen,$cve_agente,$cve_prod,$mes,$year,$demanda));
       
                }    
               
                
           }
                
       ///****************************************
                           
        $array_json  = array(
           "HTML"=> get_html_Table ($sheet ,$highestRow,$highestColumn), /// Generar Html
           "PROYE"=> json_encode($areglo_proyecciones),
           "NElem"=> $highestRow 
         );        
        $json_resultado = json_encode($array_json);
   ///****Fin Condicion  
        ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
    echo $json_resultado; 




?> 
