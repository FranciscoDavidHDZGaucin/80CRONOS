<?php

////****  adi_reporte_excel.php   
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_reporte_excel.php  
 	Fecha  Creacion : 30/11/2016 
	Descripcion  : 
                    Scrip  para  Generar  reporte  Excel  basandose en  la tabla de  adicionales 
	Modificado  Fecha  : 
*/
///***Conexion Mysql  
require_once('../Connections/conecta1.php');
 ////*****Obtenemos  la fecha Actal
 $date_NOW  =   date("Y-m-d");
///***Generamos la  consulta 
 $string_consulta_adicionales = "select *  from  adicionales"; 
 ///****Objeto  conexion  Mysql 
    $mysqli_PRO =   new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 

 ///***Generamos  la consulta
 $qery_adicionales =  $mysqli_PRO->query($string_consulta_adicionales);
 ///***Inicio   Modelado del  Reporte  en Excel
 //**Libreria Excel 
require_once ('../Classes/PHPExcel.php');
////Generamos el  objeto  Excel 
      $objExcel =  new PHPExcel(); 
 ///Asignamos  Propiedades  libro  Excel 
       $objExcel->getProperties()->setCreator("Reporte Adicionales_".$date_NOW)
                   ->setLastModifiedBy("Fco David")  
                   ->setTitle("Reporte Adicionales_".$date_NOWW)
                   ->setSubject("")
                   ->setDescription("Reporte General Adicionales")
                   ->setKeywords("adicionales")
                   ->setCategory("Reporte Adicionales") ;  //Categorias
///*******************************************************************    
    //Declaramos  arreglo  con Titulos Indice de Hoja:0
        $titulosColumnas =  array ('ID','Nombre Usuario','Clave Usuario','Nombre Producto','N# Producto','Fecha Solitud','Fecha Requerimiento','Precio Solicitud P/Venta','Cantidad Requerida','Autorizacion','Almacen','Inventario','Proyeccion','Venta','Fecha Compromiso','Fecha Real de Entrega','Estatus de Entrega','Ventas Totales de RTES Bodega','Proyeccion Total de Bodega','Venta Cierre de mes'); 
   ///Agregamos titulos  del Cliente 
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
                ->setCellValue('M3',$titulosColumnas[12])
                ->setCellValue('N3',$titulosColumnas[13])//////
                ->setCellValue('O3',$titulosColumnas[14])//////
                ->setCellValue('P3',$titulosColumnas[15])
                ->setCellValue('Q3',$titulosColumnas[16])
                ->setCellValue('R3',$titulosColumnas[17])
                ->setCellValue('S3',$titulosColumnas[18])
                ->setCellValue('T3',$titulosColumnas[19]) ;
     ///******************************************************
     //**Ciclo  para  agregar  la informacion
    $i = 4 ; 
      while ($fila= $qery_adicionales->fetch_array(MYSQLI_ASSOC))
     {           
                    ///***Validacion  Autorizacion
                    $auto_gerente = "";
                            if ($fila['auto']==0)
                            {
                             $auto_gerente=   'Pendiente';  
                            }else{
                                 if ($fila['auto']==2)
                                 {
                                    $auto_gerente=  'Rechazado';
                                 }else{
                                       $auto_gerente= 'Autorizado'; 
                                 }


                            }
                    ////********************************
                    ////****Status Entrega
                    $status_entrega ="";        
                       if ($fila['est_entrega']==0)
                     {
                       $status_entrega = 'Pendiente';  
                     }else{
                          if ($fila['est_entrega']==2)
                          {
                            $status_entrega = 'En Transito';
                          }else{
                             $status_entrega = 'Entregado'; 
                          }
                            
                     
                     }
                     ///********************************
                     $objExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$i,$fila['id'])
                            ->setCellValue('B'.$i,$fila['nombre_usu'])
                            ->setCellValue('C'.$i,$fila['cve_usuario'])
                            ->setCellValue('D'.$i,$fila['codigo_pro'])
                            ->setCellValue('E'.$i, $fila['nom_pro'])
                            ->setCellValue('F'.$i,$fila['fecha_sol'])
                            ->setCellValue('G'.$i,$fila['fecha_rq'])
                            /// /*********************************************
                            ->setCellValue('H'.$i,$fila['precio_sol_pv'])
                            ->setCellValue('I'.$i,$fila['cant_req'])
                            ->setCellValue('J'.$i,$auto_gerente)
                            ->setCellValue('K'.$i,$fila['almacen'])
                            ->setCellValue('L'.$i, $fila['inventario'])
                            ->setCellValue('M'.$i,$fila['proyeccion'])
                         
                            ->setCellValue('N'.$i,$fila['venta'])
                            ->setCellValue('O'.$i,$fila['fech_compro'])
                            ->setCellValue('P'.$i,$fila['fech_real'])
                            ->setCellValue('Q'.$i, $status_entrega)
                            ->setCellValue('R'.$i,$fila['ventotal_por_bodega'])
                            ->setCellValue('S'.$i,$fila['proyeccion_total_bode'])
                            ->setCellValue('T'.$i,$fila['vent_cierre_mes']);
                            
         $i++; 
     } 
            
            
            
/***************************************************************************************
        * Ahora procedemos a asignar el ancho de las columnas de forma automática en base al 
        contenido de cada una de ellas y lo hacemos con un ciclo de la siguiente forma.
       */
        for($i = 'A'; $i <= 'T'; $i++){
            $objExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);    
        }
        ///*********************************************
        // Se asigna el nombre a la hoja
            $objExcel->getActiveSheet()->setTitle('Reporte_Adicionales '.$date_NOW);

            // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
            $objExcel->setActiveSheetIndex(0);

            // Inmovilizar paneles
            //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
            $objExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);
       ///******************************************************
        // Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_Adicionales_'.$date_NOW.'.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;   
     ////******Fin  Diseño  Documento  Excel
?>