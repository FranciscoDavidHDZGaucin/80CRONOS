<?php
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : convenio_detalle_representante_vista
 	Fecha  Creacion :  Desconocida   
	Descripcion  : 
	      Muestra  los  detalles  de l Convenio  s
	Modificado  Fecha  : 
  *       29/12/2016   Modificion  para  gererar  formato  Pdf para  la impresion 
  *                    de   la  tabla   
  * 
*/



 session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
  require_once('formato_datos.php');
   require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
 mssql_select_db("AGROVERSA");    
 ///**** Agregamos Librerias  PDF   
    require_once('funciones.php');
   require_once('fpdf/fpdf.php');
//***********************************************
//***Inicio  classe Para  Gerenerar  Pdf  
  class    PDF  extends   FPDF 
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
           $this->Image('iconos/logocronos.jpg',170,8,33);
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
 ///****Fin   Definicion  Classe  PDF    
$remision = $_REQUEST['remision'];


$string_productos =sprintf("SELECT * FROM detalle_convenio WHERE n_remision=%s",
                         GetSQLValueString($remision, 'int'));
$queryremisiones=mysqli_query($conecta1, $string_productos) or die (mysqli_error($conecta1));

$listaprecios;
$listacomercial = "plataformaproductosl8";
$numerogerente = $_SESSION["usuario_rol"];
///*****Obtenemos  el  Encabezado  de la  Remision 
$string_encabezado = sprintf("SELECT cve_cte ,nom_cte,observacion,comentario_rechazo  FROM  encabeza_convenio WHERE n_remision =%s",GetSQLValueString($remision, 'int'));
///**Realizamos el  Qery 
$qery_encabezado   = mysqli_query($conecta1, $string_encabezado) or die (mysqli_error($conecta1));
///***Transformamos  el  Resultado  a  asociativo  
$asoc_encabezado   = mysqli_fetch_array($qery_encabezado);
///**************************************************************************
if($numerogerente=="3"){
    $listaprecios = "plataformaproductosl4";
} else if ($numerogerente=="1"){
    $listaprecios = "plataformaproductosl5";
} else if ($zona=="6"){
    $listaprecios = "plataformaproductosl6";
} else if ($zona==""){
    
} else if ($zona=="2"){
    $listaprecios = "plataformaproductosl3";    
} else if ($zona=="10"){
    $listaprecios = "plataformaproductosl8";    
}

$querylista="SELECT * FROM ".$listaprecios;
$resultadolista = mssql_query($querylista);
///*** Generamos un nuevo  Objeto 
$pdf1=new PDF('P','mm','letter');
///***Agregamos  una  nueva Hoja
$pdf1->AddPage();
///****Agregamos  El nombre  y  cve cliente  y Observaciones
///***Determinamos el  tipo de Letra
$pdf1->SetFont('Arial','',8);
///**** Agregamos el  Nombre 
$pdf1->cell(150,5,'Clave Cliente : '.$asoc_encabezado['cve_cte'],0,0,'L');
///*** Damos espacio
$pdf1->Ln(7);
$pdf1->cell(150,5,'Nombre Cliente : '.$asoc_encabezado['nom_cte'],0,0,'L');
$pdf1->Ln(7);
$pdf1->cell(150,5,'Observaciones : '.$asoc_encabezado['observacion'],0,0,'L');
$pdf1->Ln(7);
$pdf1->cell(150,5,'Comentarios Sobre Rechazo : '.utf8_decode($asoc_encabezado['comentario_rechazo']),0,0,'L');

$pdf1->Ln(7);
///****Definimos  Encabezado de  la  tabla 
  $pdf1->SetFont('Arial','B',6);
        $pdf1->cell(75,5,'Producto',1,0,'L');
        $pdf1->cell(20,5,'Cantidad',1,0,'L');
        $pdf1->cell(20,5,'Precio Req.',1,0,'L');
        $pdf1->cell(15,5,'Total',1,0,'L');
        $pdf1->Ln(5);
///******Ciclo  para   agregar  productos del  convenio  
while ($rowl = mysqli_fetch_array($queryremisiones)) {
                     
                          
                             $pdf1->cell(75,5,$rowl['nom_prod'],1,0,'L');
                             $pdf1->cell(20,5,number_format(ceil($rowl['cant_prod'])),1,0,'L');
                             $pdf1->cell(20,5,number_format(ceil($rowl['precio_representante'])),1,0,'L');
                             $pdf1->cell(15,5,number_format(ceil($rowl['total_prod'])),1,0,'L');
                             $pdf1->Ln(5);
                             
                           IF ($rowl['boni_estado']==1){
                               
                               $string_nombreboni=  sprintf("SELECT * FROM plataformaproductosl1 WHERE ItemCode =%s",
                               GetSQLValueString($rowl['boni_productoid'], "text"));
                               
                               $querynombreboni = mssql_query($string_nombreboni);
                               $fetchnombreboni=mssql_fetch_assoc($querynombreboni);
                               
                               $nombreprodboni=$fetchnombreboni['ItemName'];
                           }
} 



///****Mandamos  Mostrar el  Resultado 
$pdf1->Output();
?>
          
    
    