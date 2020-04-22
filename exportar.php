<?php

///
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : exportar.php
 	Fecha  Creacion : 11/10/2016 
	Descripcion  : 
          Copia  del  Proyecto   Proyeccion Alias presupuestos 
 *        Nombre  del  Archivo  Origen : exportar.php  
 *        Servidor : .17       
	Modificado  Fecha  : 
*/

            
require_once('Connections/conecta1.php');  //Conectarse a Mysql  
///require_once('funciones.php');       
    
    
    
    
 mysqli_select_db($conecta1, $database_conecta1);
     
     
     
    $string_1=$_REQUEST['q1'];
    $string_suma=$_REQUEST['q2'];
    $string_sumac=$_REQUEST['q3'];
        
     $query_1=  mysqli_query($conecta1,$string_1) or die (mysqli_error($conecta1));
         
     $query_suma=mysqli_query($conecta1,$string_suma) or die (mysqli_error($conecta1));
     $totalsuma=  mysqli_fetch_assoc($query_suma);
         
     $query_sumac=mysqli_query($conecta1,$string_sumac) or die (mysqli_error($conecta1));
     $totalsumac=  mysqli_fetch_assoc($query_sumac);
         
     
     
     //////************************* 
                    ///Buscar el correo del gerente se pide el código del Agente 
         function zona_ex($agente,$conexion ){

            try
            {
                require_once 'formato_datos.php';
             ///***********************
            $string_format = sprintf('SELECT * FROM relacion_gerentes WHERE cve_age=%s',
                GetSQLValueString($agente,"int" ));
            $consulta = mysqli_query($conexion,$string_format)or   die (mysqli_error($conexion));

            $zona_fetch  = mysqli_fetch_array($consulta);
          ///  return $zona_fetch['zona'];
             return   $zona_fetch['nom_gte'];   
            }
            catch(PDOException $e){
              ///return  '<p>Error!!</p>';
                return  'Error  Clave Cliente';
                
            }



         }
         ////****Name_Mes
        function name_mes($nmes) 
        {
                 switch ($nmes)
                {
                 case 1:
                                $nombremes1="ENERO";
                                break;
                 case 2:
                                $nombremes1="FEBRERO";

                                break;
                 case 3:
                                $nombremes1="MARZO";

                                break;
                 case 4:
                                $nombremes1="ABRIL";

                                break;
                case 5:
                                $nombremes1="MAYO";

                                break;		
                case 6:
                                $nombremes1="JUNIO";

                                break;
                case 7:
                                $nombremes1="JULIO";

                                break;
                case 8:
                                $nombremes1="AGOSTO";

                                break;
                case 9:
                                $nombremes1="SEPTIEMBRE";
                                break;
                case 10:
                                $nombremes1="OCTUBRE";
                                break;
                case 11:
                                $nombremes1="NOVIEMBRE";
                                break;
                case 12:
                                $nombremes1="DICIEMBRE";
                                break;
                }
          return $nombremes1;
        }

     
         
    ///*********Inicio  Documento  Excel********************************************************
    ///Cargamos  Librea  para  Excel 
   require_once 'Classes/PHPExcel.php'; 
    ////Generamos el  objeto  Excel 
          $objExcel =  new PHPExcel(); 
     ///Asignamos  Propiedades  libro  Excel 
            $objExcel->getProperties()->setCreator("Reporte Proyeccion")
                       ->setLastModifiedBy("Fco David")  
                       ->setTitle("Reporte_Proyeccion")
                       ->setSubject("")
                       ->setDescription("Informe Proyeccion")
                       ->setKeywords("")
                       ->setCategory("ReporteProyeccion") ; //Categorias
    ///*******************************************************************     
    ///Titulo del  Documento
        $tituloDoc  = "Reporte_Proyeccion"; 
    ///Declaramos  arreglo  con Titulos Columnas  12
        $titulosColumnas =  array ('Cve_Almacen','Almacen','Clasificación','Clave','Producto','Agente','Zona','Mes','Anio','Precio','Cantidad','Demanda','Total'); 
   ///Agregamos titulos  del Pedido 
            $objExcel->setActiveSheetIndex(0)
                
                ->setCellValue('A3',$titulosColumnas[0])
                ->setCellValue('B3',$titulosColumnas[1])
                ->setCellValue('C3',$titulosColumnas[2])
                ->setCellValue('D3',$titulosColumnas[3])
                ->setCellValue('E3',$titulosColumnas[4])
                ->setCellValue('F3',$titulosColumnas[5])
                ->setCellValue('G3',$titulosColumnas[6])
                ->setCellValue('H3',$titulosColumnas[7])
               ->setCellValue('I3',$titulosColumnas[8]) 
               ->setCellValue('J3',$titulosColumnas[9])
               ->setCellValue('K3',$titulosColumnas[10])
               ->setCellValue('L3',$titulosColumnas[11])
               ->setCellValue('M3',$titulosColumnas[12]);     
     //**Ciclo  para  agregar  la informacion
     $i = 4 ; 
     while ($fila=mysqli_fetch_array($query_1))
     {
                     $objExcel->setActiveSheetIndex(0)
                               ->setCellValue('A'.$i,$fila['cve_alma'])
                            ->setCellValue('B'.$i,$fila['nom_alma'])
                            ->setCellValue('C'.$i,$fila['clasifica'])
                            ->setCellValue('D'.$i,$fila['cve_prod'])
                            ->setCellValue('E'.$i,$fila['nom_prod'])
                            ->setCellValue('F'.$i, utf8_encode( $fila['nom_age'])) ///('F'.$i,$fila['zona'])
                            ->setCellValue('G'.$i,zona_ex($fila['cve_age'],$conecta1))
                            ->setCellValue('H'.$i,name_mes($fila['mes']))  
                            ->setCellValue('I'.$i,$fila['anio'])//$fila['tipo_aviso'])
                            ->setCellValue('J'.$i,$fila['precio'])
                            ->setCellValue('K'.$i,$fila['cantidad']) 
                            ->setCellValue('L'.$i,$fila['demanda'])  
                             ->setCellValue('M'.$i,number_format($fila['total'], 2, '.', ','));
                            
         $i++; 
        
                
     }
     ////***** Agregamos el  Total  de  Cantidad   y   Total   
     $objExcel->setActiveSheetIndex(0)
               ->setCellValue('J'.$i,"Totales")
              ->setCellValue('K'.$i,number_format($totalsumac['total'], 2, '.', ','))
              ->setCellValue('M'.$i,"$".number_format($totalsuma['total'], 2, '.', ','));
     
    /***************************************************************************************
        * Ahora procedemos a asignar el ancho de las columnas de forma automática en base al 
        contenido de cada una de ellas y lo hacemos con un ciclo de la siguiente forma.
       */
        for($i = 'A'; $i <= 'M'; $i++){
            $objExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);    
        }
        ///*********************************************
        // Se asigna el nombre a la hoja
            $objExcel->getActiveSheet()->setTitle('Reporte_Proyeccion');

            // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
            $objExcel->setActiveSheetIndex(0);

            // Inmovilizar paneles
            //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
            $objExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);
       ///******************************************************
        // Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_Proyeccion.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;   
     ////******Fin  Diseño  Documento  Excel
?>	