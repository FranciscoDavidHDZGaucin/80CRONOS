<?php 


  require_once('Connections/ventas.php');
require_once('formato_datos.php');
require_once('fpdf/fpdf.php');
//require_once('funciones.php');
mysqli_select_db($conecta1, $database_conecta1);


$string_ppto_vta=("Select * from vista_presupuesto where anio_pre=2016 limit 50");
                
$sql_ppto_vta=mysqli_query($ventas, $string_ppto_vta) or die (mysqli_error($ventas));

class PDF extends FPDF
{
// Cabecera de página
    
    function Header()
    {
           $id_unico=$_REQUEST['id_unico'];
        $fecha_alta=$_REQUEST['fecha_alta'];
        // Logo
        $this->Image('images/logoversa.png',10,8,33);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(30,10,'AGROQUIMICOS VERSA, S.A. DE C.V.',0,0,'C');
      
       
        // Salto de línea
        $this->Ln(15);
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,'Presupuesto de Ventas Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

        
     
        $pdf1=new PDF('P','mm','letter');
        $pdf1->AddPage();
        $pdf1->SetFont('Arial','',9);
      
       
        $pdf1->SetFont('Arial','B',9);
        $pdf1->cell(20,5,'Clave:',1,0,'L');
        $pdf1->cell(20,5,'cantidad:',1,0,'L');
        $pdf1->cell(70,5,'Descripcion:',1,0,'L');
        $pdf1->cell(20,5,'Dcto%:',1,0,'L');
        $pdf1->cell(20,5,'P/U:',1,0,'L');
        $pdf1->cell(25,5,'Importe:',1,0,'L');
       $pdf1->Ln(5);
          
 while ($row = mysqli_fetch_array($sql_ppto_vta)) {
     
           $pdf1->cell(20,5,$row['cveprod_pre'],1,0,'L');
            $pdf1->cell(20,5,$row['mes_pre'],1,0,'L');
            $pdf1->cell(70,5,$row['descripcion'],1,0,'L');
            $pdf1->cell(20,5,$row['cantidad_pre'],1,0,'L');
           // $pdf1->cell(20,5,$row['p_uni'],1,0,'L');
           // $pdf1->cell(25,5,$row['importe'],1,0,'L');
            $pdf1->Ln(5);
     
     
 }
         
        
        $pdf1->Output();
        // put your code here
       
        
?>
  
