<?PHP
   session_start ();    
?>
<HTML LANG="es">
<HEAD>
<TITLE>Desconectar</TITLE>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="2; login.php"> 
</HEAD>
<BODY>

<?PHP
   if (isset($_SESSION['usuario_valido']))
   {
        
      session_destroy ();
      print ("<BR><BR><P ALIGN='CENTER'>Conexión finalizada con éxito</P>\n");
      print ("<P ALIGN='CENTER'>[ <A HREF='index.php'>Inicio</A> ]</P>\n");
   }
   else
   {
      print ("<BR><BR>\n");
      print ("<P ALIGN='CENTER'>No existe una conexión activa</P>\n");
      print ("<P ALIGN='CENTER'>[ <A HREF='index.php'>Regresar</A> ]</P>\n");
   }
?>

</BODY>
</HTML>
