<?php 
               
//gast_generadorXaGente.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : gast_generadorXaGente.php
 	Fecha  Creacion :18/12/2017
	Descripcion  : 
 *             
 *      Modificacion : 
 *  
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
 require_once('funciones.php');
 require_once('fpdf/fpdf.php');
 mssql_select_db("AGROVERSA");   
 
 
 
 ///echo $_SESSION['fecha_start']." al ";
 ///       echo $_SESSION['fecha_end']."<br>"; 
////***** Inicio Clase  PDF *************************************************************************************
  class PDF extends FPDF
{
// Cabecera de página
    
    function Header()
    {
       
        
       
      //////****Fecha de ALTA   
        $dtStat = new DateTime($_SESSION['fecha_start']);  
        $feStart = $dtStat->format("d/m/Y"); 
      ////******Fecha Fin           
        $dTEnd = new DateTime($_SESSION['fecha_end']);  
        $feEnd = $dTEnd->format("d/m/Y"); 
     /////**** Concatenacion  Fechas           
         $concatnFech =  "Fecha de  Inicio:".$feStart."   Fecha Fin:".$feEnd ;  
        
        
        
        $leyenda1=utf8_decode('Relacion de Pago a Agentes del Periodo');
        $this->SetFont('Arial','B',15);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(30,10,'AGROQUIMICOS  VERSA, S.A. DE C.V.',0,0,'C');
         $this->Ln(10);
        
          $this->Image('images/logoversa.png',10,8,33);
           $this->Image('images/cronos_blanco.png',170,8,33);
         $this->Cell(80);
        $this->Cell(30,10,$leyenda1,0,0,'C');
        
         $this->Ln(10);
       $this->Cell(80);
        $this->Cell(30,10,$concatnFech,0,0,'C');
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

////************************************************************************************************************* 
if ($_SESSION['fecha_start']!="" or $_SESSION['fecha_end']!=""){
		$op1="1";
	}else{
		$op1="0";
	}
		
	if ($_SESSION['empleado']!=0){
		$op2="1";
	}else{
		$op2="0";
	}	

   
 $todo=$op1.$op2;
///echo $empleado . "<br>";
///echo $todo . "<br>";

	/*  echo   $_SESSION['fecha_start']."<br>";
	echo	 $_SESSION['fecha_end']."<br>";
	echo 	 $_SESSIOM['empleado']."<br>";
          */  
	
		switch ($todo) {
		case '00':   //Todos
					$query=("SELECT IF (pedidos.poliza.agente >= 1 AND pedidos.poliza.agente  <= 6,
                                                (SELECT  zona   FROM pedidos.relacion_gerentes WHERE cve_gte = pedidos.poliza.agente  group by cve_gte), 
                                                 IF(pedidos.poliza.agente >= 400 AND pedidos.poliza.agente  <= 450, nom_age,(SELECT nom_empleado  FROM pedidos.relacion_gerentes  where  cve_age = pedidos.poliza.agente ))) AS NombreGenall,
                                                 round(sum(pago),2) as pagado, 
                                                 round(sum(subtot_pago),2) as subtot, 
                                                 round(sum(iva_pago),2) as iva  ,cc
                                                 from pedidos.poliza  where  pago > 0 and fech_vbo_geren is not null   group by agente");
			break;
		case '10':   //Solo Fecha
				$query = sprintf("SELECT IF (pedidos.poliza.agente >= 1 AND pedidos.poliza.agente  <= 6,
                                                (SELECT  zona   FROM pedidos.relacion_gerentes WHERE cve_gte = pedidos.poliza.agente  group by cve_gte), 
                                                 IF(pedidos.poliza.agente >= 400 AND pedidos.poliza.agente  <= 450, nom_age,(SELECT nom_empleado  FROM pedidos.relacion_gerentes  where  cve_age = pedidos.poliza.agente ))) AS NombreGenall,
                                                 round(sum(pago),2) as pagado, 
                                                 round(sum(subtot_pago),2) as subtot, 
                                                 round(sum(iva_pago),2) as iva ,cc 
                                                 from pedidos.poliza  where  pago > 0 and  poliza.f_pago>=%s and poliza.f_pago<=%s and fech_vbo_geren is not null   group by agente", 
				GetSQLValueString($_SESSION['fecha_start'],"date"), 
				GetSQLValueString($_SESSION['fecha_end'],"date"));
				
				
			break;
		case '01':   //Solo Empleado
				$query = sprintf("SELECT IF (pedidos.poliza.agente >= 1 AND pedidos.poliza.agente  <= 6,
                                                (SELECT  zona   FROM pedidos.relacion_gerentes WHERE pago > 0 and cve_gte = pedidos.poliza.agente  group by cve_gte), 
                                                 IF(pedidos.poliza.agente >= 400 AND pedidos.poliza.agente  <= 450, nom_age,(SELECT nom_empleado  FROM pedidos.relacion_gerentes  where  cve_age = pedidos.poliza.agente ))) AS NombreGenall,
                                                 round(sum(pago),2) as pagado, 
                                                 round(sum(subtot_pago),2) as subtot, 
                                                 round(sum(iva_pago),2) as iva,cc  
                                                 from pedidos.poliza where pago > 0 and poliza.agente=%s and fech_vbo_geren is not null   group by agente",
				GetSQLValueString($_SESSION['empleado'],"int"));
				
			break;	
		case '11':   //Fecha y empleado
				$query = sprintf("SELECT IF (pedidos.poliza.agente >= 1 AND pedidos.poliza.agente  <= 6,
                                                (SELECT  zona   FROM pedidos.relacion_gerentes WHERE cve_gte = pedidos.poliza.agente  group by cve_gte), 
                                                 IF(pedidos.poliza.agente >= 400 AND pedidos.poliza.agente  <= 450, nom_age,(SELECT nom_empleado  FROM pedidos.relacion_gerentes  where  cve_age = pedidos.poliza.agente ))) AS NombreGenall,
                                                 round(sum(pago),2) as pagado, 
                                                 round(sum(subtot_pago),2) as subtot, 
                                                 round(sum(iva_pago),2) as iva ,cc  
                                                 from pedidos.poliza   where  pago > 0 and poliza.agente=%s and poliza.f_pago>=%s and poliza.f_pago<=%s and fech_vbo_geren is not null   group by agente", 
				GetSQLValueString($_SESSION['empleado'],"int"), 
				GetSQLValueString($_SESSION['fecha_start'],"date"), 
				GetSQLValueString($_SESSION['fecha_end'],"date"));				
				
			break;
	}  

                        /*  $query_iva=mysqli_query($suma_iva,$conecta1) or die (mysql_error());
			  $query_sub=mysqli_query($suma_sub,$conecta1) or die (mysql_error());
                         $query_tot=mysqli_query($suma_tot,$conecta1) or die (mysql_error());
			  $query_ret=mysqli_query($suma_ret,$conecta1) or die (mysql_error());
			  
			  $row_iva = mysqli_fetch_array($query_iva);
			  $row_sub = mysqli_fetch_array($query_sub);
			  $row_tot = mysqli_fetch_array($query_tot);
			  $row_ret = mysqli_fetch_array($query_ret);*/


$sql_consulta= mysqli_query($conecta1,$query) ;
  $Total = mysqli_num_rows($sql_consulta);
	
$acumulado;			
if($Total<>0) { 
    
    
     $pdf1=new PDF('P','mm','letter');
       $pdf1->AddPage();
    	
	 	
        $pdf1->Ln(7);
        
        $pdf1->SetFont('Arial','B',6);
     
        $pdf1->cell(65,5,"Nombre",1,0,'L');
        $pdf1->cell(12,5,'Su Total',1,0,'L');
        $pdf1->cell(12,5,'Iva',1,0,'P');
        $pdf1->cell(12,5,'$ Total',1,0,'L');
       $pdf1->Ln(5);
    while ($row = mysqli_fetch_array($sql_consulta)) {  
            
                $pdf1->cell(65,5,$row['NombreGenall'],1,0,'L');//17,5
                $pdf1->cell(12,5,number_format($row['subtot'], 2, '.', ','),1,0,'L');
                $pdf1->cell(12,5, number_format($row['iva'], 2, '.', ','),1,0,'L');
               
                $pdf1->cell(12,5,number_format($row['pagado'], 2, '.', ','),1,0,'L');
              
            $pdf1->Ln(5);
     
    }     
      $pdf1->Output(); 
}
 ?>
