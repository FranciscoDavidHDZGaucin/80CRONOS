 </div> <!-- /.Canvas -->
      

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
     
    
    
   <script src="select3/dist/js/vendor/jquery.min.js"></script>          
   <script src="select3/assets/js/application.js"></script>
    <script src="select3/dist/js/flat-ui.min.js"></script> 
 
    
    <script src="select2/buscar-cool.js"></script>    
    <!--     <script type="text/javascript" src="select2/jquery.min.1.10.2.js"></script>   -->  
 
    <script src="select2/select2.js"></script>  
    
   
    <!--<script src="select2/jasny-bootstrap.min.js"></script>-->
   
    <!-- DataTables JavaScript -->
    <script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
   
      <script>
      $(function () {
        $('[data-toggle=tooltip]').tooltip();
      });
      
       $(document).ready(function() {
        $('#dataTables-existencias').DataTable({
                responsive: true
        });
    });
      
      
      
       ///Tabla utilizada en el archivo gast_rep_conta1.php  muestra los gastos capturados 
       $(document).ready(function() {
        $('#dataTables-gastosconta1').DataTable({
                responsive: true
        });
    });
      
      
      ///Tabla utilizada en el archivo crear23_grilla.php   para la captura del dato demanda  Planeadord
       $(document).ready(function() {
        $('#dataTables-agregardemanda').DataTable({
                responsive: true
        });
    });
      ///Tabla utilizada En platafroma gastos
       $(document).ready(function() {
        $('#dataTables-gastos').DataTable({
                responsive: true
        });
    });
      
       ///Tabla utilizada en el archivo crear2.php   para la captura el dato Cantidad Agente
       $(document).ready(function() {
       /* $('#dataTables-agregaproyecccion-agente').DataTable({
                responsive: true
        });*/
        var   table  = $('#dataTables-agregaproyecccion-agente').DataTable(); 
        $('#min,#max').keyup(function(){
            table.draw();
        });
         
        
    });
      
       ///Tabla utilizada en el archivo crear22_grilla.php   para la captura el dato Cantidad Gerente
       $(document).ready(function() {
        $('#dataTables-agregaproyecccion-gerente').DataTable({
                responsive: true
        });
    });
    
     ///Tabla utilizada en el archivo listado_pland.php  listado de reporte proyeccion planeador
       $(document).ready(function() {
        $('#dataTables-listado_pland').DataTable({
                responsive: true
        });
    });
    
    ///Tabla utilizada en el archivo listado_pland.php  listado de reporte proyeccion planeador
       $(document).ready(function() {
        $('#dataTables-estado-pedidos-gerentes').DataTable({
                responsive: true
        });
    });
      
    </script>
  </body>
  <!--<footer class="foot_tem_index_gerentes ">
        <div class="col-lg-12  col-md-12  col-sm-12"> 
      
            <P class="footer-title   text-right">"Generamos Bienestar para los hogares maximizando lo que la tierra nos da"</P>
         
          </div>  
   </footer> --> 
</html>