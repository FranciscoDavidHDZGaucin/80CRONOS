
<?php

session_start ();
$MM_restrictGoTo = "login.php";
if (!(isset($_SESSION['usuario_valido']))){
    header("Location: ". $MM_restrictGoTo);
    exit;
}

require_once('Connections/conecta1.php');
 require_once('formato_datos.php');




$cursor=("CREATE TEMPORARY TABLE IF NOT EXISTS pedidos.gasto_temptb (
    AccountCode VARCHAR(50) NOT NULL
    , credit DECIMAL(12,2) NOT NULL DEFAULT 0.00
    , debit DECIMAL(12,2) NOT NULL DEFAULT 0.00
    , duedate VARCHAR(10) NOT NULL
	, linememo VARCHAR(50) NOT NULL
	, projectcode VARCHAR(10)  NULL
	, reference1 VARCHAR(50) NOT NULL
	, reference2 VARCHAR(50) NOT NULL
	, reference3 VARCHAR(50)  NULL
	, taxdate VARCHAR(10) NOT NULL
) ENGINE=MEMORY;");

$date_inicio=$_POST['fecha_ini'];
$date_final=$_POST['fecha_fin'];
$empleado=$_POST['empleado'];
$caracteres=strlen($_POST['fecha_ini']);
$fecha_poliza=$_POST['fecha3'];
$anio=substr($fecha_poliza,0,4);

$mes=substr($fecha_poliza,5,2);
$dia=substr($fecha_poliza,8,2);
$fecha_formato=$anio.$mes.$dia;

if (strlen($_POST['fecha_ini'])>1){
		$op1="1";
		//echo "entro al verdadero";
	}else{
		$op1="0";
		//echo "NO entro al verdadero";
	}
		
	if ($empleado!=0){
		$op2="1";
	}else{
		$op2="0";
	}	
	$dato=$op1.$op2;
	switch ($dato) {    //consulta donde se obtiene los gastos para convertirlos en poliza
		case '00':   //Todos
					//$query=("select * from poliza where pago>0");
					$consulta_sql=("SELECT poliza.id, poliza.id_gto,poliza.agente, poliza.nom_age, poliza.pago, poliza.subtot_pago, poliza.iva_pago, poliza.tasa_iva, poliza.ieps, poliza.es_gas, poliza.retencion, poliza.rfc_gas, poliza.rfc_gasto, poliza.deducible, agente.formato_cta, agente.usu_centrocto, catalogo.nombre, catalogo.formato_cta as sys_gasto FROM poliza  inner join agente on poliza.agente=agente.usu_consecutivo  inner join catalogo on poliza.id_gto=catalogo.id where poliza.pago>0");
			break;
		case '10':   //Solo Fecha
				$consulta_sql=sprintf("SELECT poliza.id, poliza.id_gto, poliza.agente, poliza.nom_age, poliza.pago, poliza.subtot_pago, poliza.iva_pago, poliza.tasa_iva, poliza.ieps, poliza.es_gas, poliza.retencion,poliza.f_pago, poliza.rfc_gas, poliza.rfc_gasto, poliza.deducible, agente.formato_cta, agente.usu_centrocto, catalogo.nombre, catalogo.formato_cta as sys_gasto FROM poliza  inner join agente on poliza.agente=agente.usu_consecutivo  inner join catalogo on poliza.id_gto=catalogo.id where poliza.pago>0 and poliza.f_pago>=%s and poliza.f_pago<=%s order by poliza.nom_age",
				GetSQLValueString($date_inicio,"date"), 
				GetSQLValueString($date_inicio,"date"));
				//$query = sprintf("select * from poliza where pago>0 and  fecha>=%s and f_pago<=%s order by f_pago desc", 
			break;
		case '01':   //Solo Empleado
				$consulta_sql=sprintf("SELECT poliza.id, poliza.id_gto, poliza.agente, poliza.nom_age, poliza.pago, poliza.subtot_pago, poliza.iva_pago, poliza.tasa_iva, poliza.ieps, poliza.es_gas, poliza.retencion, poliza.rfc_gas,poliza.rfc_gasto, poliza.deducible, agente.formato_cta, agente.usu_centrocto, catalogo.nombre, catalogo.formato_cta as sys_gasto FROM poliza  inner join agente on poliza.agente=agente.usu_consecutivo  inner join catalogo on poliza.id_gto=catalogo.id where poliza.pago>0 and poliza.agente=%s",
				GetSQLValueString($empleado,"int"));
				//$listado=mysqli_query($conecta1,$query) or die(mysqli_error($conecta1));
				//$query = sprintf("select *from poliza where pago>0 and agente=%s order by f_pago desc",
			break;	
		case '11':   //Fecha y empleado
				//$query = sprintf("select * from poliza where pago>0 and agente=%s and f_pago>=%s and f_pago<=%s order by fecha desc", 
				$consulta_sql=sprintf("SELECT poliza.id, poliza.id_gto, poliza.agente, poliza.nom_age, poliza.pago, poliza.subtot_pago, poliza.iva_pago, poliza.tasa_iva, poliza.ieps, poliza.es_gas, poliza.retencion, poliza.rfc_gas, poliza.rfc_gasto, poliza.deducible, agente.formato_cta, agente.usu_centrocto, catalogo.nombre, catalogo.formato_cta as sys_gasto FROM poliza  inner join agente on poliza.agente=agente.usu_consecutivo  inner join catalogo on poliza.id_gto=catalogo.id where poliza.pago>0 and poliza.agente=%s and poliza.f_pago>=%s and poliza.f_pago<=%s",
				GetSQLValueString($empleado,"int"), 
				GetSQLValueString($date_inicio,"date"), 
				GetSQLValueString($date_inicio,"date"));
			break;
	}
$query_agentes=mysqli_query($conecta1,$consulta_sql) or die (mysqli_error($conecta1));	
$ejecutar_consulta=mysqli_query($conecta1,$consulta_sql) or die (mysqli_error($conecta1));
$Total = mysqli_num_rows ($ejecutar_consulta);	
	

if($Total<>0) {
 
  //$crear_cursor=mysqli_query($conecta1,$cursor) or die (mysqli_error($conecta1));
  $crear_cursor=mysqli_query($conecta1, $cursor) or die (mysqli_error($conecta1));	
	while ($reg_datos = mysqli_fetch_array($ejecutar_consulta)) {   
		   	
         //Identificar que tipo de gasto es el que se esta consultando
		
        $tipo=1;		
		 if ($reg_datos['es_gas']==1) {
				$tipo=2;
		}
		
		 if ($reg_datos['retencion']>0 and $reg_datos['nombre']=="FLETES" )   {
				$tipo=3;
		}
		$comodin=0;
		$comodin2="";
		$comodinfecha=$fecha_formato;
		
		
		 if ($reg_datos['deducible']==1) {
			 //Gasto se va a la cuenta de NO DEDUCIBLES
				   ///Crear la linea del Agente Credit
							echo "Deducible";	  
							$crear_registros=sprintf("INSERT INTO gasto_temptb (AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",						
							GetSQLValueString($reg_datos['formato_cta'],"text"),
							GetSQLValueString($reg_datos['pago'],"double"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   
						   ///
						   
						   $sys_cuenta="_SYS00000000714";
						   $cuenta="NO DEDUCIBLE";
						   ///Crear la linea de NO DEDUCIBLE
						   $crear_registros=sprintf("INSERT INTO gasto_temptb (AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2, reference3,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($sys_cuenta,"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($reg_datos['subtot_pago'],"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($cuenta,"text"),
							GetSQLValueString($reg_datos['usu_centrocto'],"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($cuenta,"text"),
							GetSQLValueString($reg_datos['rfc_gasto'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   ///
		}else{
              switch ($tipo) {
				
			 
				case 1:    //Gasto Normal
						   $comodin=0;
						   $comodin2="";
						   $comodinfecha=$fecha_formato;
						  
						  // 	echo $comodinfecha;
						 // echo $reg_datos['sys_gasto']. "<br>";
						 // echo $reg_datos['retencion'];
						 // echo "Gasto";	   
						  ///Crear la linea del Agente Credit
								  
							$crear_registros=sprintf("INSERT INTO gasto_temptb (AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",						
							GetSQLValueString($reg_datos['formato_cta'],"text"),
							GetSQLValueString($reg_datos['pago'],"double"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   
						   ///
						   
						   ///Crear la linea del Gasto
						   $crear_registros=sprintf("INSERT INTO gasto_temptb (AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2, reference3,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($reg_datos['sys_gasto'],"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($reg_datos['subtot_pago'],"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['usu_centrocto'],"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['rfc_gasto'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   ///
						   
						   
						   ///Crear la linea del IVA 
							  ///conocer la tasa iva
						   switch ($reg_datos['tasa_iva']) {
							case 0:
							  $cuenta_iva="_SYS00000000135";
							  $nombre_iva="IVA ACREDITABLE 0%";
							  break;    
							case 11:
							  $cuenta_iva="_SYS00000001062";
							  $nombre_iva="IVA ACREDITABLE 11%";
							  break;
							case 16:
							  $cuenta_iva="_SYS00000001051";
							  $nombre_iva="IVA ACREDITABLE 16%";
							  break;
							}
							
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2, reference3, taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($cuenta_iva,"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($reg_datos['iva_pago'],"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($nombre_iva,"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['rfc_gasto'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
								   
						   ///
				  
				  break;    
				case 2:    //Consumo de Gasolina
				
						 //echo "Gasolina";
						   $comodin=0;
						   $comodin2="";
						   $comodinfecha=$fecha_formato;
						   $sub=$reg_datos['subtot_pago'];
						   $ieps=$reg_datos['ieps'];
						   $combustible_sinieps=($sub-$ieps);
						   
						   //echo $sub."<br>" ;
						   //echo $combustible_sinieps;
						   ///Crear la linea del Agente Credit
						  
						  
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2, taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($reg_datos['formato_cta'],"text"),
							GetSQLValueString($reg_datos['pago'],"double"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   
						   ///
						
						   
						   ///Crear la linea del IVA 
							  ///conocer la tasa iva
						   switch ($reg_datos['tasa_iva']) {
							case 0:
							  $cuenta_iva="_SYS00000000135";
							  $nombre_iva="IVA ACREDITABLE 0%";
							  break;    
							case 11:
							  $cuenta_iva="_SYS00000001062";
							  $nombre_iva="IVA ACREDITABLE 11%";
							  break;
							case 16:
							  $cuenta_iva="_SYS00000001051";
							  $nombre_iva="IVA ACREDITABLE 16%";
							  break;
							}
							
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2, reference3, taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($cuenta_iva,"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($reg_datos['iva_pago'],"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($nombre_iva,"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['rfc_gas'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
								   
						   ///
							
							
						   
						   ///Agregar cuenta IEPS
							$formato_ctaieps="_SYS00000000989";
							$nombre_ieps="IEPS GASOLINA";
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2, reference3, taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($formato_ctaieps,"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($ieps,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($nombre_ieps,"text"),
							GetSQLValueString($reg_datos['usu_centrocto'],"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['rfc_gas'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
								   
						   /// 	
							
							///Crear la linea del Gasto
						   $crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2, reference3, taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($reg_datos['sys_gasto'],"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($combustible_sinieps,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['usu_centrocto'],"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['rfc_gas'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   /// 
						   
											
				  break;
				case 3:   //Flete
						//Retencion   SYS00000000666	
						//echo "Con Retencion";
						   $comodin=0;
						   $comodin2="";
						   $comodinfecha=$fecha_formato;
						   $pago=$reg_datos['pago'];
						   $retencion=$reg_datos['retencion'];
						   $pago_retencion=($pago-$retencion);
						   
						  // echo $retencion."<br>" ;
						  // echo $pago."<br>" ;
						  // echo $pago_retencion."<br>" ;
						  // echo $comodin."<br>" ;
						   //echo $combustible_sinieps;
						   ///Crear la linea del Agente Credit
						  
						 // echo "agente" ;
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($reg_datos['formato_cta'],"text"),
							GetSQLValueString($pago_retencion,"double"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   
						   ///
						
						   
						   ///Crear la linea del IVA 
							  ///conocer la tasa iva
						   switch ($reg_datos['tasa_iva']) {
							case 0:
							  $cuenta_iva="_SYS00000000135";
							  $nombre_iva="IVA ACREDITABLE 0%";
							  break;    
							case 11:
							  $cuenta_iva="_SYS00000001062";
							  $nombre_iva="IVA ACREDITABLE 11%";
							  break;
							case 16:
							  $cuenta_iva="_SYS00000001051";
							  $nombre_iva="IVA ACREDITABLE 16%";
							  break;
							}
							
						//	echo "iva" ;
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($cuenta_iva,"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($reg_datos['iva_pago'],"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($nombre_iva,"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
								   
						   ///
							
						  
						  
						//   echo "retencion" ;
						   ///Agregar cuenta Retencion
							$formato_ctaieps="_SYS00000000284";
							$nombre_ieps="IVA RETENIDO POR TRANSPORTE";
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($formato_ctaieps,"text"),
							GetSQLValueString($retencion,"double"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($nombre_ieps,"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
								   
						   /// 	
							
							
							//echo "Gasto" ;
							///Crear la linea del Gasto
						   $crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($reg_datos['sys_gasto'],"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($reg_datos['subtot_pago'],"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['usu_centrocto'],"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   /// 
						
				  break;
			
			  }           
				   
		}	
		   
		   
		   
	} 

}




$consultar=("SELECT * from gasto_temptb");
$ejecutar_consulta2=mysqli_query($conecta1,$consultar) or die (mysqli_error($conecta1));
$Total2 = mysqli_num_rows ($ejecutar_consulta2);

//$exporta_csv=("Select * From gasto_temptb INTO OUTFILE 'c:\temporal\archivo.csv' fields terminated by ',' lines terminated by '\n' ");

//$ejecuta_exporta= mysqli_query($conecta1,$exporta_csv) or die (mysqli_error($conecta1));

// EXORTARLO A EXCEL
$r=mysqli_query($conecta1,$consultar) or die (mysqli_error($conecta1));
$return = '';
if( mysqli_num_rows($r)>0){
    $return .= '<table border=1>';
    $cols = 0;
    while($rs = mysql_fetch_row($r)){
        $return .= '<tr>';
        if($cols==0){
            $cols = sizeof($rs);
            $cols_names = array();
            for($i=0; $i<$cols; $i++){
                $col_name = mysql_field_name($r,$i);
                $return .= '<th>'.htmlspecialchars($col_name).'</th>';
                $cols_names[$i] = $col_name;
            }
            $return .= '</tr><tr>';
        }
        for($i=0; $i<$cols; $i++){
            #En esta iteraci�n podes manejar de manera personalizada datos, por ejemplo:
            if($cols_names[$i] == 'fechaAlta'){ #Fromateo el registro en formato Timestamp
                $return .= '<td>'.htmlspecialchars(date('d/m/Y H:i:s',$rs[$i])).'</td>';
            }else if($cols_names[$i] == 'activo'){ #Estado l�gico del registro, en vez de 1 o 0 le muestro Si o No.
                $return .= '<td>'.htmlspecialchars( $rs[$i]==1? 'SI':'NO' ).'</td>';
            }else{
                $return .= '<td>'.htmlspecialchars($rs[$i]).'</td>';
            }
        }
        $return .= '</tr>';
    }
    $return .= '</table>';
    mysql_free_result($r);
}
#Cambiando el content-type m�s las <table> se pueden exportar formatos como csv
header("Content-type: application/vnd-ms-excel; charset=iso-8859-1");
header("Content-Disposition: attachment; filename=NombreDelExcel_".date('d-m-Y').".xls");
echo $return;  


//echo $date_inicio . "<br>";
//echo $date_final . "<br>";	
//echo $consulta_sql . "<br>";	
//echo $tipo;
?>
<html>
<head>
<title>Pedidos</title>

</head>
<body> 
    <a href="gast_rep_poliza1.php">[Regresar]</a>
		 
		<?php if($Total2<>0) {  ?>
		 
		      <?php  $contador=1; ?> 
				<table width="700" border="1" align="left">
						<tr>
						<td width="25">RecordKey</td>
						<td width="25">LineNum</td>
						<td width="25">AccountCode</td>
						<td width="25">AdditionalReference</td>
						<td width="25">BaseSum</td>
						<td width="25">ContraAccount</td>
						<td width="25">CostingCode</td>
						<td width="25">Credit</td>
						<td width="25">Debit</td>
						<td width="25">DueDate</td>
						<td width="25">FCCredit</td>
						<td width="25">FCCurrency</td>
						<td width="25">FCDebit</td>
						<td width="25">GrossValue</td>
						<td width="25">LineMemo</td>
						<td width="25">ProjectCode</td>
						<td width="25">Reference1</td>
						<td width="25">Reference2</td>
						<td width="25">ReferenceDate1</td>
						<td width="25">ReferenceDate2</td>
						<td width="25">ShortName</td>
						<td width="25">SystemBaseAmount</td>
						<td width="25">SystemVatAmount</td>
						<td width="25">TaxDate</td>
						<td width="25">TaxGroup</td>
						<td width="25">VatAmount</td>
						<td width="25">VatDate</td>
						<td width="25">VatLine</td>
						
						</tr>
						
						<tr>
						<td width="25">RecordKey</td>
						<td width="25">LineNum</td>
						<td width="25">AccountCode</td>
						<td width="25">AdditionalReference</td>
						<td width="25">BaseSum</td>
						<td width="25">ContraAccount</td>
						<td width="25">CostingCode</td>
						<td width="25">Credit</td>
						<td width="25">Debit</td>
						<td width="25">DueDate</td>
						<td width="25">FCCredit</td>
						<td width="25">FCCurrency</td>
						<td width="25">FCDebit</td>
						<td width="25">GrossValue</td>
						<td width="25">LineMemo</td>
						<td width="25">ProjectCode</td>
						<td width="25">Reference1</td>
						<td width="25">Reference2</td>
						<td width="25">ReferenceDate1</td>
						<td width="25">ReferenceDate2</td>
						<td width="25">ShortName</td>
						<td width="25">SystemBaseAmount</td>
						<td width="25">SystemVatAmount</td>
						<td width="25">TaxDate</td>
						<td width="25">TaxGroup</td>
						<td width="25">VatAmount</td>
						<td width="25">VatDate</td>
						<td width="25">VatLine</td>
						
						</tr>
						
				<?php	while ($reg =mysqli_fetch_array($ejecutar_consulta2)) {  ?> 
							  
									<tr>
									<td>1</td>
									<td><?php echo $contador; ?></td>
									<td><?php echo $reg['AccountCode']; ?></td>
									<td><?php echo $reg['reference3']; ?></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><?php echo $reg['credit']; ?></td>
									<td><?php echo $reg['debit']; ?></td>
									<td><?php echo $reg['duedate']; ?></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><?php echo $reg['linememo']; ?></td>
									<td><?php echo $reg['projectcode']; ?></td>
									<td><?php echo $reg['reference1']; ?></td>
									<td><?php echo $reg['reference2']; ?></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><?php echo $reg['taxdate']; ?></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									
									</tr>
					  <?php 
					    $contador=$contador+1; 
					  } ?>
                      
				<?php	   } else {
				  echo $Total;
				} ?>
				</table>
				
			   

</body>
</html>

<?php




///$borrar_cursor=("DROP TABLE gasto_temptb");
///$ejecutar_borrar=mysqli_query($conecta1,$borrar_cursor) or die (mysqli_error($conecta1));
?>



