<?PHP
   session_start ();
require_once('Connections/conecta1.php');
require_once('formato_datos.php');   


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
	//echo $empleado . "<br>";
	//echo $todo . "<br>";

	
	switch ($todo) {
		case '00':   //Todos
					$query=("SELECT sum(pago) as pagado,  cuenta, agente.usu_nombre from poliza inner join agente on poliza.agente=agente.usu_consecutivo  group by agente, cuenta, agente.usu_nombre");
			break;
		case '10':   //Solo Fecha
				$query = sprintf("SELECT sum(pago) as pagado, sum(subtot_pago) as subtot, sum(iva_pago) as iva,  sum(retencion) as retencion,  cuenta, agente.usu_nombre from poliza inner join agente on poliza.agente=agente.usu_consecutivo where poliza.f_pago>=%s and poliza.f_pago<=%s  group by agente, cuenta, agente.usu_nombre", 
				GetSQLValueString($_SESSION['fecha_start'],"date"), 
				GetSQLValueString($_SESSION['fecha_end'],"date"));
				
				$suma_iva=sprintf("select sum(iva_pago) as total_iva from poliza where pago>0 and  f_pago>=%s and f_pago<=%s",
				GetSQLValueString($_SESSION['fecha_start'],"date"), 
				GetSQLValueString($_SESSION['fecha_end'],"date"));
				
				$suma_sub=sprintf("select sum(subtot_pago) as total_sub from poliza where pago>0 and  f_pago>=%s and f_pago<=%s",
				GetSQLValueString($_SESSION['fecha_start'],"date"), 
				GetSQLValueString($_SESSION['fecha_end'],"date"));
				
				$suma_tot=sprintf("select sum(pago) as total_total from poliza where pago>0 and  f_pago>=%s and f_pago<=%s",
				GetSQLValueString($_SESSION['fecha_start'],"date"), 
				GetSQLValueString($_SESSION['fecha_end'],"date"));
				
				$suma_ret=sprintf("select sum(retencion) as total_ret from poliza where pago>0 and  f_pago>=%s and f_pago<=%s",
				GetSQLValueString($_SESSION['fecha_start'],"date"), 
				GetSQLValueString($_SESSION['fecha_end'],"date"));
			break;
		case '01':   //Solo Empleado
				$query = sprintf("SELECT sum(pago) as pagado,  cuenta, agente.usu_nombre from poliza inner join agente on poliza.agente=agente.usu_consecutivo where poliza.agente=%s  group by agente, cuenta, agente.usu_nombre",
				GetSQLValueString($_SESSION['empleado'],"int"));
				
			break;	
		case '11':   //Fecha y empleado
				$query = sprintf("SELECT sum(pago) as pagado,  cuenta, agente.usu_nombre from poliza inner join agente on poliza.agente=agente.usu_consecutivo where poliza.agente=%s and poliza.f_pago>=%s and poliza.f_pago<=%s  group by agente, cuenta, agente.usu_nombre", 
				GetSQLValueString($_SESSION['empleado'],"int"), 
				GetSQLValueString($_SESSION['fecha_start'],"date"), 
				GetSQLValueString($_SESSION['fecha_end'],"date"));				
				
			break;
	}  
//echo $query."<br>";     
mysql_select_db($database_conecta1, $conecta1);
$query_iva=mysql_query($suma_iva,$conecta1) or die (mysql_error());
			  $query_sub=mysql_query($suma_sub,$conecta1) or die (mysql_error());
              $query_tot=mysql_query($suma_tot,$conecta1) or die (mysql_error());
			  $query_ret=mysql_query($suma_ret,$conecta1) or die (mysql_error());
			  
			  $row_iva = mysql_fetch_assoc($query_iva);
			  $row_sub = mysql_fetch_assoc($query_sub);
			  $row_tot = mysql_fetch_assoc($query_tot);
			  $row_ret = mysql_fetch_assoc($query_ret);
$sql_consulta=mysql_query($query,$conecta1) or die (mysql_error());
$Total = mysql_num_rows ($sql_consulta);
$acumulado;
print ("<P ALIGN='CENTER'>[ <A HREF='poliza_sap1.php'>Regresar</A> ]</P>\n");
echo "Relacion de Pago a Agentes del Periodo: ";
echo $_SESSION['fecha_start']." al ";
echo $_SESSION['fecha_end']."<br>";  
echo "<br>";			
if($Total<>0) {  ?>
		<table width="584" border="1" align="left" cellspacing="0">
					<tr>
					<td valign="top">Cuenta</td>
					<td valign="top">Nombre</td>
					<td valign="top">Subtotal</td>
					<td valign="top">IVA</td>
					<td valign="top">Retencion</td>
					<td valign="top">Total</td>
					</tr>
					<?php		while ($reg = mysql_fetch_array($sql_consulta)) {   ?>
					
					               <?php 
								    $subtotal=$reg['pagado']-$reg['retencion'];
									$acumulado=$acumulado+$subtotal;
									?>
								   
									<tr>
									<td><?php echo $reg['cuenta']; ?></td>
									<td><?php echo $reg['usu_nombre']; ?></td>
									<td><?php echo number_format($reg['subtot'], 2, '.', ','); ?></td>
									<td><?php echo number_format($reg['iva'], 2, '.', ','); ?></td>
									<td><?php echo number_format($reg['retencion'], 2, '.', ','); ?></td>
                                    <td><?php echo number_format($subtotal, 2, '.', ','); ?></td>		
                                    </tr>									
							<?php }?>
							
					<tr>
					<td valign="top"></td>
					<td valign="top">Total General</td>
					<td valign="top"><?php echo number_format($row_sub['total_sub'], 2, '.', ','); ?></td>
					<td valign="top"><?php echo number_format($row_iva['total_iva'], 2, '.', ','); ?></td>
					<td valign="top"><?php echo number_format($row_ret['total_ret'], 2, '.', ','); ?></td>
					<td valign="top"><?php echo number_format($acumulado, 2, '.', ','); ?></td>
					</tr>		
					</table>			
<?php	}	

mysql_free_result($sql_consulta);
?>
						   
