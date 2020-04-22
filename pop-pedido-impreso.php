<?php

 session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
  require_once('formato_datos.php');
   require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
   require_once('funciones.php');
   require_once('fpdf/fpdf.php');
 mssql_select_db("AGROVERSA");   
 
 
 
 class PDF extends FPDF
{
// Cabecera de página
    
    function Header()
    {
       // $id_unico=$_REQUEST['id_unico'];
      //  $fecha_alta=$_REQUEST['fecha_alta'];
        // Logo
       // $this->Image('images/logoms.jpg',10,8,33);
        // Arial bold 15
        
        $remision = $_REQUEST['remision'];
        $agente = $_REQUEST['agente'];
        $cliente = $_REQUEST['cliente'];
        
        
        
        $leyenda1=utf8_decode('Remisión #');
        $this->SetFont('Arial','B',15);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(30,10,$leyenda1.$remision,0,0,'C');
         $this->Ln(10);
          $this->Image('images/logoversa.png',10,8,33);
           $this->Image('images/cronos_blanco.png',170,8,33);
         $this->Cell(80);
        $this->Cell(30,10,'AGROQUIMICOS  VERSA, S.A. DE C.V.',0,0,'C');
        
         $this->Ln(10);
       $this->Cell(80);
        $this->Cell(30,10,'Zona:'.$_SESSION["usuario_nombre"],0,0,'C');
        // Salto de línea
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
          $leyenda_foot=utf8_decode('"Generamos bienestar para los hogares maximizando lo que la tierra nos da"  Página');
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,$leyenda_foot.$this->PageNo(),0,0,'C');
    }
}

///Datos encabezado del pedido
  $remision = $_REQUEST['remision'];
        $agente = $_REQUEST['agente'];
        $cliente = $_REQUEST['cliente'];
$string_encabezado=  sprintf("select * from encabeza_pedido where n_remision=%s and n_agente=%s and cve_cte=%s",
                      GetSQLValueString($remision, 'int'),
                         GetSQLValueString($agente, 'int'),
                         GetSQLValueString($cliente, 'text'));     
$query_encabezado=mysqli_query($conecta1, $string_encabezado) or die (mysqli_error($conecta1));
$result0_datos=  mysqli_fetch_assoc($query_encabezado);

if ($result0_datos['moneda']==0){
    $ley_moneda="USD";
}else{
     $ley_moneda="MXP";
}


$leyenda2=utf8_decode(' días');
$leyenda3=utf8_decode('Descripción');


  $pdf1=new PDF('L','mm','letter');
        $pdf1->AddPage();
        $pdf1->SetFont('Arial','',8);
        $pdf1->cell(150,5,'Fecha:'.$result0_datos['fecha_alta'],0,0,'L');
        $pdf1->Ln(7);
         $pdf1->cell(150,5,'Plazo:'.$result0_datos['plazo'].$leyenda2,0,0,'L');
        $pdf1->Ln(7);
        $pdf1->cell(150,5,'Cliente:'.$result0_datos['cve_cte'],0,0,'L');
        $pdf1->Ln(7);
        $pdf1->cell(150,5,'Nombre Cliente:'.utf8_decode($result0_datos['nom_cte']),0,0,'L');
		  $pdf1->Ln(5);
     
      
       $pdf1->MultiCell(100,5,'Comentarios: '.utf8_decode($result0_datos['observacion']));
		
		
        $pdf1->Ln(7);
        
        $pdf1->SetFont('Arial','B',6);
        $pdf1->cell(17,5,'Clave',1,0,'L');
        $pdf1->cell(65,5,$leyenda3,1,0,'L');
        $pdf1->cell(12,5,'Unidad',1,0,'L');
        $pdf1->cell(12,5,'Cantidad',1,0,'L');
        
        $pdf1->cell(12,5,'$Precio',1,0,'L');
        $pdf1->cell(12,5,'$Desc',1,0,'L');
        $pdf1->cell(12,5,'%',1,0,'L');
        $pdf1->cell(12,5,'$IEPS',1,0,'L');
        $pdf1->cell(5,5,'%',1,0,'L');
        $pdf1->cell(12,5,'$IVA',1,0,'L');
        $pdf1->cell(12,5,'$P.Neto',1,0,'L');
        $pdf1->cell(15,5,'$Total',1,0,'L');
          $pdf1->Ln(5);
 
      //revisar producto por producto
$string_productos =sprintf("SELECT * FROM detalle_pedido WHERE n_remision=%s and n_agente=%s and cve_cte=%s and terminada=1",
                         GetSQLValueString($remision, 'int'),
                         GetSQLValueString($agente, 'int'),
                         GetSQLValueString($cliente, 'text'));        

$result_detalle=mysqli_query($conecta1, $string_productos) or die (mysqli_error($conecta1));  
 $montoieps6=0;
 $montoieps7=0;
 $montoieps9=0;
 $acumulado_bruto=0;
 $acumulado_dcto=0;
 $acumulado_iva=0;
         
 while ($row = mysqli_fetch_array($result_detalle)) {  
             ///Calculo de operaciones
                if(is_null($row['bonificacion'])){  ///no calcular si la partida es bonificacion
                   
                  
                    $monto_dcto=$row['precio_prod']*($row['dcto_prod']/100);
                    $monto_ieps=($row['precio_condcto']*($row['ieps']/100))*$row['cant_prod'];

                    $sub_afteriva=($row['precio_condcto']*$row['cant_prod'])+$monto_ieps;
                    $monto_iva=$sub_afteriva*($row['iva']);
                    $total_linea=($row['precio_condcto']*$row['cant_prod'])+$monto_ieps+$monto_iva;

                    switch ($row['ieps']) {
                        case 6:
                             $montoieps6=$montoieps6+$monto_ieps;

                            break;
                         case 7:
                               $montoieps7=$montoieps7+$monto_ieps;

                            break;
                         case 9:
                               $montoieps9=$montoieps9+$monto_ieps;

                            break;

                    }


                    $acumulado_bruto=$acumulado_bruto+($row['precio_prod']*$row['cant_prod']);
                    $acumulado_dcto=$acumulado_dcto+($monto_dcto*$row['cant_prod']);
                    $acumulado_iva=$acumulado_iva+$monto_iva;
                }else{
                    $monto_dcto=0;
                    $monto_ieps=0;
                    $sub_afteriva=0;
                    $monto_iva=0;
                    $total_linea=0;
                    /*
                    $montoieps6=0;
                    $montoieps7=0;
                    $montoieps9=0;
                     
                     */
                    
                }
              //// 
               $pdf1->cell(17,5,$row['cve_prod'],1,0,'L');
                $pdf1->cell(65,5,$row['nom_prod'],1,0,'L');
                $pdf1->cell(12,5,$row['litkg_unidad'],1,0,'L');
                $pdf1->cell(12,5,number_format($row['cant_prod'],1, '.', ','),1,0,'L');

                $pdf1->cell(12,5,$row['precio_prod'],1,0,'L');
              
                  $pdf1->cell(12,5,number_format($monto_dcto,2, '.', ','),1,0,'L');
                  $pdf1->cell(12,5,number_format($row['dcto_prod'],2, '.', ','),1,0,'L');
                $pdf1->cell(12,5,number_format($monto_ieps,2, '.', ','),1,0,'L');
                $pdf1->cell(5,5,number_format($row['ieps'],1, '.', ','),1,0,'L');
                $pdf1->cell(12,5,number_format($monto_iva,2, '.', ','),1,0,'L');
                $pdf1->cell(12,5,$row['precio_condcto'],1,0,'L');
                $pdf1->cell(15,5,number_format($total_linea,2, '.', ','),1,0,'L');
            $pdf1->Ln(5);
     
     
 }     
 
 $gran_total=$acumulado_bruto-$acumulado_dcto+$acumulado_iva+$montoieps6+$montoieps7+$montoieps9;

     $pdf1->Ln(5);    
 $pdf1->SetFont('Arial','B',9);
         $pdf1->cell(158,5,'',0,0,'L');
       $pdf1->cell(40,5,'SubTotal      '.'$'.number_format($acumulado_bruto,2, '.', ','),1,0,'L');
       $pdf1->Ln(5);
       $pdf1->cell(158,5,'',0,0,'L');
        $pdf1->cell(40,5,'Descuento    '.'$'.number_format($acumulado_dcto,2, '.', ','),1,0,'L');
       $pdf1->Ln(5);
       $pdf1->cell(158,5,'',0,0,'L');
         $pdf1->cell(40,5,'IVA 16        '.'$'.number_format($acumulado_iva,2, '.', ','),1,0,'L');
       $pdf1->Ln(5);
       $pdf1->cell(158,5,'',0,0,'L');
         $pdf1->cell(40,5,'IEPS 6 %      '.'$'.number_format($montoieps6,2, '.', ','),1,0,'L');
       $pdf1->Ln(5);
       $pdf1->cell(158,5,'',0,0,'L');
         $pdf1->cell(40,5,'IEPS 7 %      '.'$'.number_format($montoieps7,2, '.', ','),1,0,'L');
       $pdf1->Ln(5);
       $pdf1->cell(158,5,'',0,0,'L');
         $pdf1->cell(40,5,'IEPS 9 %     '.'$'.number_format($montoieps9,2, '.', ','),1,0,'L');
       $pdf1->Ln(5);
       $pdf1->cell(145,5,'',0,0,'L');
         $pdf1->cell(53,5,'Total     '.'$'.number_format($gran_total,2, '.', ',').' '.$ley_moneda,1,0,'L');
       $pdf1->Ln(5);
      
       $pdf1->MultiCell(158,5,'Destino: '.utf8_decode($result0_datos['destino']));
        
        
        $pdf1->Output();
 
?>
