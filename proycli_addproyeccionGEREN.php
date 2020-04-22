<?php
////proycli_addproyeccionGEREN.php


/*
********     INFORMACION ARCHIVO ***************** 
    Nombre  Archivo : proycli_addproyeccionGEREN.php
    Fecha  Creacion : 16/02/2018
    Descripcion  : 
 *              
  */


////**Inicio De Session 
session_start();
///****Cabecera Cronos 
require_once 'header_gerentes.php';
///***Conexion Mysql  
require_once('Connections/conecta1.php');
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
require_once('funciones_proyecciones.php'); 
///require_once('conexion_sap/sap.php'); 
require_once('CONEC_UNIFICADO/conexion_jupiter.php');

///$idagente = $_SESSION["usuario_agente"];

 mssql_query("SET ANSI_NULLS ON");
                         mssql_query("SET ANSI_WARNINGS ON");
$STRGGET  = sprintf("SELECT COD_ZONA,AGENTE,ZONA FROM pedidos.vwCAT_AGENTES_JOIN_RELACIONGERENTES_MYSQL WHERE cve_gte =%s",
GetSQLValueString($_SESSION["usuario_agente"], "int"));
$QERYAGENTES  = mssql_query($STRGGET);
if(!mssql_query($STRGGET ))
   {

    $STRGGET  =mssql_get_last_message() ; 
   } 


IF(isset($_POST['agentes'])){
     $idagente = $_POST['agentes']; 
  $_SESSION["AGENTE_SEND_GERENTE"] =$_POST['agentes'];
    $STRGTEMPORAL = "
      IF OBJECT_ID('tempdb..TEMPORAL_PROYECCIONES') IS  NULL
    BEGIN
    CREATE TABLE #TEMPORAL_PROYECCIONES
     (  
                id_proyec  INT, 
                id_prs_confir  INT,
                id_prs_rev2 INT ,
                id_prs_rev1 INT null ,
                num_agente INT,
                cve_prod  NVARCHAR(20),
                cve_cliente  NVARCHAR(20),
                nombre_prod varchar(200) NULL,
                cliente nvarchar(100) NULL, 
                cantidad_confir int NULL, 
                precio_confir  decimal(10,2) NULL, 
                costo_confir decimal(10,2) NULL, 
                proyeccion_confir int NULL, 
                mes_confir int NULL, 
                year_confir int NULL, 
               cantidad_rev2 int  NULL , 
                precio_rev2 decimal(10,2) NULL, 
                costo_rev2 decimal(10,2) NULL, 
                proyeccion_rev2 int NULL, 
                mes_rev2 int  NULL, 
                year_rev2 int NULL , 
                cantidad_rev1 int NULL , 
                precio_rev1 decimal(10,2) NULL, 
                costo_rev1 decimal(10,2) NULL, 
                proyeccion_rev1 int  NULL, 
                mes_rev1 int NULL, 
                year_rev1 int  NULL, 
                familia varchar(45) NULL, 
               venta1CNF decimal(10,2) NULL, 
               venta1RV2 decimal(10,2)NULL, 
               venta1RV1 decimal(10,2)NULL
             
           )


    END";
    if(!mssql_query($STRGTEMPORAL))
    {
      $estprocedute_2 =  'ERROR GENERERAR TABLA'. mssql_get_last_message() ; 

    }else {

     $estprocedute_2 =  'EXITO TABLA'; 

    }   


            ////***Nombre del  Procidimiento  a Utilizar 
                        $sTOREPRO =   mssql_init("pedidos.spTABLA_PRESUPUESTOS_PROYECCIONES");
                        mssql_query("SET ANSI_NULLS ON");
                         mssql_query("SET ANSI_WARNINGS ON");
                        

                        ///*****Obtenemos Meses que se Actulizaran hooo  Insertaran
            if(! mssql_execute($sTOREPRO)) 
            {
              $estprocedute =  'ERROR GENERERAR  PROCEDIMIENTO'. mssql_get_last_message() ; 

            }else {

             $estprocedute =  'EXITO PROCEDIMIENTO'; 

            } 




            ///*****Obtenemos los  meses   asignados 
            $strMesAsig  =sprintf("select * from pedidos.proyec_mesasignacion"); 
            ///****Generamos QERY  
            $qerAsigMes =  mssql_query($strMesAsig);
            ///***Obtenemos  el  fech arreglo
            $fecAsigMes = mssql_fetch_array($qerAsigMes);




    /*
    $string_get_info =sprintf(" select  *  from  TEMPORAL_PROYECCIONES where num_agente = %s   order by cliente",
                                            GetSQLValueString($_SESSION["usuario_agente"], "int"));


                            ///****Generamos QERY Obtener  Presupuesto   
    if(!$qergetpresu=mssql_query($string_get_info)) 
      {
     /// $aasdasdas =  'ERROR '. mssql_get_last_message() ;
     die("ERROR".mssql_error());
    }else {

     $aasdasdas =  'EXITO '; 

    }      */ 
                            ///***Variabel change  color

    $idagente = $_POST['agentes'];  ////$_SESSION["usuario_agente"]; $_POST['agentes']
     
    $querycliente=sprintf("SELECT * FROM [JUPITER].[pedidos].[vwCLIENTES_CRONOS_SAP] WHERE SlpCode=%s OR U_agente2=%s order by  CardName   asc",
    GetSQLValueString($idagente, "int"),
            GetSQLValueString($idagente, "int"));
    $cliente = mssql_query($querycliente);

      $_REQUEST['SELECTAGENTE'] =$idagente ;

            $string_listadoprod=("SELECT * FROM pedidos.productos   order by  desc_prod");
    $tabla = mysqli_query($conecta1,$string_listadoprod);
}


?> 
<style type="text/css">


.confir01 {
    min-width: 233px;
    background-color: #23d2378f;
}

.confir02 {
    min-width: 233px;
    background-color: #7fafdecc;
}
.confir03 {
    min-width: 233px;
    background-color: #c3ced6c2
}   


.infomain {
    background-color: #777;
    color: white;
    font-weight: bolder;
    font-size: 24px;
        text-align: center;
}
.element01 {
    background-color: #dddddd4a;
}

.elementPROYECNOPRESUPUESTADA {
    background-color: #F27090;
}
a:hover {

    color: #ffff;

}
a:active {

    color: #2bb4ff;

}
input#prodnew {
    width: 400px;
}
input#clientenew {
    width: 400px;
}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    /* display: none; <- Crashes Chrome on hover */
    -webkit-appearance: none;
    margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
}
textarea#coment_adi {
    height: 212px;
    font-size: 20px;
}
button#saveelem {
    font-size: 22px;
    font-stretch: expanded;
}
#BTNREFRESH {
    font-size: 26px;
    width: 210px;
}
.ocultarcol {
    position: static;
    margin-left: 20px;
}
td.confir01.cnfhidencol {
    font-family: inherit;
 /*   font-size: 27px;*/
    text-align: center;
}

td.confir02.cnfhidencolrv2 {
    font-size: 27px;
    text-align: center;
}
td.confir03.cnfhidencolrv1 {
    font-size: 27px;
    text-align: center;
}


input {
    text-align: right;
}
.ocultarINPUTS{
   display: none;
    
}

.cnfhidencolrv2.VTS {
    text-align: right;
}
.cnfhidencol.VTS{
   text-align: right;
}
.cnfhidencolrv1.VTS
{
  text-align: right;
}
.glyphicon.glyphicon-usd {

    color: black;
    }
    .input-group-addon {

    background: aliceblue;

}

#RVN02PRECIOSNEW {

    min-width: 120px;

}
#CFNPRENEW {

    min-width: 120px;

}
#RVN01PRECIOSNEW {

    min-width: 120px;

}

</style>
<script type="text/javascript">
        var ArMaintoSend = new  Array(); 
        var ArProyectoAdd =  new  Array();
        const  mainnumber = <?php echo  $_SESSION["usuario_agente"];  ?>; 
                var PROYESINPRE =  new Array(); 
         console.log("<?php echo $estprocedute ;  ?>");
</script>
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

/*
 $("#tbpla").chromatable({
    width: "100%", // specify 100%, auto, or a fixed pixel amount
                height: "1000px",
                scrolling: "yes" // must have the jquery-1.3.2.min.js script installed to use
}); 
    */

 $("#saveelem").click(function(){
          $.when(function(){ $(this).attr('disabled',true); }).then(function(){
            $("#SAFEINF").modal("show");

            var aretosen  =JSON.stringify(ADD_PROYECCION_AND_PRESUPUESTADO());
    
                    $.ajax({
                                    type:'POST',
                                    url: 'proyec_proyecciones/proyec_addproyeccion.php',
                                    data: {"ObjsPro":aretosen}, 
                                    success: function (datos) { 
                                        console.log(datos)
                                       PUT_CERO_iNPUT_FUERAPROYECC();
                                       if(datos.ERROR == 0){
                                            
                                             $("#contproyeccfuera").empty();
                                             $("#contproyeccfuera").html(MAKE_HTML_FOR_PROYEC_SINPRES());
                                          ///  PROYESINPRE =  new Array();
                                             $('#SAFEINF').modal('toggle');
                                           $("#saveelem").attr('disabled',false );
                                           ///*******MOSTRAR PROYECCIONES***************************************
                                            $(".cnfhidencol").show();
                                            $("#btnhidennf").attr('value', 'true');
                                            $(".cnfhidencolrv2").show();
                                            $("#btnhiderev2").attr('value', 'true');
                                            $(".cnfhidencolrv1").show();
                                             $("#btnhiderev1").attr('value', 'true');
                                           
                                           ///**********************************************
                                           
                                       }else{
                                           $('#SAFEINF').modal('toggle');
                                         $("#coment_adi").text("NUMERO De ERROR"+ String(datos.ERROR.NUM_ERROR));
                                            $("#Modal_ERROR").modal("show");
                                          ///   PROYESINPRE =  new Array(); 
                                            $("#saveelem").attr('disabled',false );
                                           
                                       }
                                        
                                    }
                            }).fail( function( jqXHR, textStatus, errorThrown ) {
                                    $('#SAFEINF').modal('toggle');
                                                if (jqXHR.status === 0) {

                                               ///   alert('Not connect: Verify Network.');
                                                 $("#coment_adi").text("Revise su Conexion de Internet");
                                                } else if (jqXHR.status == 404) {

                                                //  alert('Requested page not found [404]');
                                                          $("#coment_adi").text("Excedido el tiempo de espera de la plataforma por lo que es imposible Agregar las  proyecciones. Para  poder agregar  las  proyecciones inicie de nuevo la sesión");

                                                } else if (jqXHR.status == 500) {

                                                //  alert('Internal Server Error [500].');
                                                    $("#coment_adi").text("Excedido el tiempo de espera de la plataforma.Inicie de nuevo la sesión ");

                                                } else if (textStatus === 'parsererror') {

                                                //  alert('Requested JSON parse failed.');
                                                   $("#coment_adi").text("Ocurrió un Error  Comuníquese con el administrador Estroctura de  Informacion Incorrecta");

                                                } else if (textStatus === 'timeout') {

                                                //  alert('Time out error.');
                                                  $("#coment_adi").text("Excedido el tiempo de espera de la plataforma.Inicie de nuevo la sesión ");


                                                } else if (textStatus === 'abort') {

                                                //  alert('Ajax request aborted.');
                                                 $("#coment_adi").text("Ocurrió un Error  Comuníquese con el administrador Ajax request aborted. ");

                                                } else {

                                                 /// alert('Uncaught Error: ' + jqXHR.responseText);
                                                  $("#coment_adi").text("Ocurrió un Error  Comuníquese con el administrador."+ jqXHR.responseText);

                                                }
                                                              $("#Modal_ERROR").modal("show");
                                                              $("#saveelem").attr('disabled',false );
                                              });
          });

 });

$(document).on("keyup",".sumconfir",function(){
///$(".sumconfir").keyup(function(){  

                var  idmadeCNF =  "#CFN"+$(this).attr('prsid');
                var  idmadeCFNPRE=  "#CFNPRE"+$(this).attr('prsid');
                var  idmadeCFNMON =  "#CFNMON"+$(this).attr('prsid');
                                var  idmadeCFNMONTXT =  "#CFNMONTXT"+$(this).attr('prsid');

                 var getValueProye = $(idmadeCNF).val() ; 
                /// var getValuepre =$(idmadeCFNPRE).val().replace(',', "") ; 
                                var getValuepre =$(idmadeCFNPRE).val() ; 
                           
                var proye = getValueProye != "" ? getValueProye :0; 
                var pres = getValuepre!= "" ?getValuepre : 0;


            var  summm = GetFlot2Deci( parseFloat(proye) )* GetFlot2Deci( parseFloat(pres)) ;

             $(idmadeCFNMON).val(GetFlot2Deci(summm));
                         $(idmadeCFNMONTXT).val(currency(GetFlot2Deci(summm), 2, [',', ",", '.']));

             ////*****Convertir a  Formato  Moneda  currency(ArglMain[i].CMG, 2, [',', ",", '.'])
  
                         
          var aregmonto = GetMonto_Update(); 
                    
          var convermoney = currency( GetFlot2Deci(aregmonto.SUMACNF), 2, [',', ",", '.']);
         $("#MNCNF").val(convermoney);




});
$(document).on("keyup",".sumrev1",function(){
///$(".sumrev1").keyup(function(){  

                var  idmadeRVN01PROYEC =  "#RVN01PROYEC"+$(this).attr('prsid');
                var  idmadeRVN01PREC=  "#RVN01PRECIOS"+$(this).attr('prsid');
                var  idmadeRVN01 =  "#RVN01"+$(this).attr('prsid');
                            var  IDRVN01TXT =  "#RVN01TXT"+$(this).attr('prsid');
                                
                var getValueProye = $(idmadeRVN01PROYEC).val(); 
                 var getValuepre =$(idmadeRVN01PREC).val();//.replace(',', "") ; 

                var proye = getValueProye != "" ? getValueProye :0; 
                var pres = getValuepre!= "" ?getValuepre : 0;

            var  summm = GetFlot2Deci( parseFloat(proye) )* GetFlot2Deci( parseFloat(pres)) ;

             $(idmadeRVN01).val(GetFlot2Deci(summm));
                           $(IDRVN01TXT).val(currency( GetFlot2Deci(summm), 2, [',', ",", '.']));
                           
         var aregmonto = GetMonto_Update(); 
          var convermoney = currency( GetFlot2Deci(aregmonto.SUMARV1), 2, [',', ",", '.']);
         $("#MNCRV1").val(convermoney);
                  /// console.log("PRECIO CONFIRMA :"+pres)
                  /// console.log("TOTAL MONEDA :"+currency( GetFlot2Deci(summm), 2, [',', ",", '.']) )

});

$(document).on("keyup",".sumrev2",function(){
///$(".sumrev2").keyup(function(){  

                var  idmadeRVN02PROYEC =  "#RVN02PROYEC"+$(this).attr('prsid');
                var  idmadeRVN02PREC=  "#RVN02PRECIOS"+$(this).attr('prsid');
                var  idmadeRVN02 =  "#RVN02"+$(this).attr('prsid');
                                var  idmadeRVN02TXT =  "#RVN02TXT"+$(this).attr('prsid');

                var getValueProye = $(idmadeRVN02PROYEC).val() ; 
                 var getValuepre =$(idmadeRVN02PREC).val()//.replace(',', "") ; 

                var proye = getValueProye != "" ? getValueProye :0; 
                var pres = getValuepre!= "" ?getValuepre : 0;

            var  summm = GetFlot2Deci( parseFloat(proye) )* GetFlot2Deci( parseFloat(pres)) ;

             $(idmadeRVN02).val(GetFlot2Deci(summm));
                         $(idmadeRVN02TXT).val(currency( GetFlot2Deci(summm), 2, [',', ",", '.']));
        
          var aregmonto = GetMonto_Update(); 
          var convermoney = currency( GetFlot2Deci(aregmonto.SUMARV2), 2, [',', ",", '.']);
         $("#MNCRV2").val(convermoney);
                   /// console.log("Ejecutandoce rev2")
});


$("#BTNPRUEBA").click(function(){


    /* var aretosen  =JSON.stringify(GET_PROYECCION_FUERA_PRESUPUESTO());
         console.log(aretosen)*/
        /* var arregloproy  =GetProyeccionROw();
         var aretosen  =GET_PROYECCION_FUERA_PRESUPUESTO();
         if(aretosen != false )
         {
             arregloproy.push(aretosen);
         }
      console.log(arregloproy) */
        

///***Mostramos Modal  
   ///    $("#Modal_ERROR").modal("show");
 
    console.log(ADD_PROYECCION_AND_PRESUPUESTADO());

});
///*****Refrescar 
$("#BTNREFRESH").click(function(){
    
    location.reload(true);
   
});
////*****SELECCIONAMOS AL  CLIENTE
$("#btn-apli").click(function(){
        
        ///***Mostramos Modal  
        $('#MODSELC').modal('show');  
});  
////****SELECT CLIENTE 
$("#cliente").click(function(){

 var  cve_prod =$("#cliente   option:selected").val();  
 var   nom_cli =$("#cliente   option:selected").attr('nomcli'); 


$("#cliselectcve").text("Clave: "+cve_prod); 
$("#cliselectnom").text("Nombre: "+nom_cli);
//$("#prodselect").val(); 


});

////****SELECT PRODUCTO 
$("#producto").click(function(){

 var  cve_cl =$("#producto   option:selected").val();  
 var   nom_prod =$("#producto   option:selected").attr('nomprod'); 


$("#prodselectcve").text("Clave: "+cve_cl); 
$("#prodselectnom").text("Nombre: "+nom_prod);
//$("#prodselect").val(); 


});
////***BTN 
$("#btn_put_selecciones").click(function(){

 var  cve_cli =$("#cliente   option:selected").val();  
 var   nom_cli =$("#cliente   option:selected").attr('nomcli'); 
 var  cve_prod =$("#producto   option:selected").val();  
 var   nom_prod =$("#producto   option:selected").attr('nomprod'); 

 if( ExistProyeccion(String(cve_prod),String(cve_cli)) ===false )
 {
     $("#clientenew").val(nom_cli);
    $("#clientenew").attr('cvecl',cve_cli);

    $("#prodnew").val(nom_prod);
    $("#prodnew").attr('cvecl',cve_prod);
     
     
 }else{
      $("#coment_adi").text("Lo Sentimos NO podemos  Agragar Proyeccion para el \n Cliente: "+nom_cli+"\n Con el Producto: "+nom_prod +" Dado a que Existe Proyeccion"  );
          $("#Modal_ERROR").modal("show");
 }

          
          





});


            
///******Funcion Para OBTENER EL  mONTO DE  ACTUALUIZACION 
 function   GetMonto_Update()
 {
                var  SUMACNF = 0 ; 
                var  SUMARV1 = 0 ;
                var  SUMARV2 = 0 ;

        for( var i  in areglopr)
        {   
            
                     if(areglopr[i].prsidcnf == 0 )   
                     {    
                        ///***Obtenemos IDENTIDFICADOR iD  A
            var  idmadeCFNMON =  "#CFNMON"+areglopr[i].prsid;
            var  idmadeRVN01 =  "#RVN01"+areglopr[i].prsid;
            var  idmadeRVN02 =  "#RVN02"+areglopr[i].prsid;
                    }else{
                        ///***Obtenemos IDENTIDFICADOR iD  A
            var  idmadeCFNMON =  "#CFNMON"+areglopr[i].prsidcnf;
            var  idmadeRVN01 =  "#RVN01"+areglopr[i].prsidrv1;
            var  idmadeRVN02 =  "#RVN02"+areglopr[i].prsidrv2;
                        
                    }
                    
                    
            var montoconfi = parseFloat($(idmadeCFNMON).val().replace(',', "")); 
            var montorev01 = parseFloat($(idmadeRVN01).val().replace(',', "")); 
            var montorev02=  parseFloat($(idmadeRVN02).val().replace(',', "")); 

            SUMACNF +=  montoconfi ;
            SUMARV1 +=  montorev01; 
            SUMARV2 +=  montorev02;  

        }
                
               for( var i  in PROYESINPRE)
        {   
            ///***Obtenemos IDENTIDFICADOR iD  A
            var  idmadeCFNMON =  "#CFNMON"+PROYESINPRE[i].prsid;
            var  idmadeRVN01 =  "#RVN01"+PROYESINPRE[i].prsid;
            var  idmadeRVN02 =  "#RVN02"+PROYESINPRE[i].prsid;

            var montoconfi = parseFloat($(idmadeCFNMON).val().replace(',', "")); 
            var montorev01 = parseFloat($(idmadeRVN01).val().replace(',', "")); 
            var montorev02=  parseFloat($(idmadeRVN02).val().replace(',', "")); 

            SUMACNF +=  montoconfi ;
            SUMARV1 +=  montorev01; 
            SUMARV2 +=  montorev02;  

        }
                
                var  CFNMONNEW  = parseFloat($( "#CFNMONNEW").val().replace(',', "")) ;
                var RVN02PRECIOSNEW =   parseFloat($( "#RVN02PRECIOSNEW").val().replace(',', ""));
                var RVN01PROYECNEW = parseFloat($( "#RVN01PROYECNEW").val().replace(',', ""));
               
                if( CFNMONNEW !== null && CFNMONNEW !== 0  && isNaN(CFNMONNEW) !==true  )
                {
                    
                    SUMACNF +=  CFNMONNEW ;
                }
                 if( RVN02PRECIOSNEW !== null  && RVN02PRECIOSNEW !==0 && isNaN(RVN02PRECIOSNEW) !==true)
                {
                    
                    SUMARV2 += RVN02PRECIOSNEW;
                }
                 if( RVN01PROYECNEW !== null && RVN01PROYECNEW !==0 && isNaN(RVN01PROYECNEW) !==true )
                {
                    
                    SUMARV1 +=  RVN01PROYECNEW ;
                }
                
                

    return  RES =  { "SUMACNF" :SUMACNF , "SUMARV1" :SUMARV1 ,"SUMARV2":SUMARV2   }; 

    } 
////****Funcion pra Obtener Funcion de  Proyeccion Presuppuestada         
 function   ADD_PROYECCION_AND_PRESUPUESTADO ()
 {
     var arregloproy  =GetProyeccionROw();
         var aretosen  =GET_PROYECCION_FUERA_PRESUPUESTO();
         if(aretosen != false  && aretosen != null)
         {
             arregloproy.push(aretosen);
             areglopr.push(aretosen);
         }
       return  arregloproy;
     
 }
  ////*****Existe Proyeccion  EN      
  function  ExistProyeccion(cve_prod ,cve_clie)
  {
       var  EXIST = false;
       for(var i  in  areglopr)
       {
           if(areglopr[i].cveprod.localeCompare(cve_prod) === 0  && areglopr[i].cveclie.localeCompare(cve_clie) === 0  )
           {
            EXIST = true; 
            break;
           }
       }
       if(EXIST != true )
       {  
                for(var   i in PROYESINPRE)
                {
                   if(PROYESINPRE[i].cveprod.localeCompare(cve_prod) === 0  && PROYESINPRE[i].cveclie.localeCompare(cve_clie) === 0  )
                    {
                     EXIST = true; 
                     break;
                    }  

                }
       }
      return EXIST ;
  }
 ///////***** iNPUTS  Empty to cero 
 function  PUT_CERO_iNPUT_FUERAPROYECC()
 {
    $("#clientenew").attr('cvecl','');    
   $("#clientenew").val("") 
    
    $("#prodnew").attr('cvecl','');  
    $("#prodnew").val("");  

   $("#CFNNEW").val(0)
  $("#CFNPRENEW").val(0)
  $("#CFNMONNEW").val(0)
  ///****REV01
  $("#RVN01PROYECNEW").val(0)
  $("#RVN01PRECIOSNEW").val(0)
  $("#RVN01NEW").val(0)
  ///***REV2
  $("#RVN02PROYECNEW").val(0) 
  $("#RVN02PRECIOSNEW").val(0)
  $("#RVN02NEW").val(0)
     
     
 }
///****Proyeccion Fuera de  Presupuesto
function  GET_PROYECCION_FUERA_PRESUPUESTO()
{   
    var  OBJETMIN = null ;
    var cve_cli = $("#clientenew").attr('cvecl');    
    var nom_cli = $("#clientenew").val();
    
    var cve_prod = $("#prodnew").attr('cvecl');  
    var  nom_prod =   $("#prodnew").val();  
    if( ExistProyeccion(cve_prod ,cve_cli) ==false )
    {               var  ValueProyyec = {
                        "ProyCNF": $("#CFNNEW").val().replace(',', ""),
                        "preconfi":$("#CFNPRENEW").val(),//.replace(',', ""),
                        "montoconfi":$("#CFNMONNEW").val().replace(',', ""),
                        ///****REV01
                        "ProyREV1": $("#RVN01PROYECNEW").val().replace(',', ""),
                        "prerev01":$("#RVN01PRECIOSNEW").val(),//.replace(',', ""),
                        "montorev01":$("#RVN01NEW").val().replace(',', ""),
                        ///***REV2
                        "ProyREV2":$("#RVN02PROYECNEW").val().replace(',', ""), 
                        "prerev02":$("#RVN02PRECIOSNEW").val(),//.replace(',', ""),
                        "montorev02":$("#RVN02NEW").val().replace(',', "")
                    };
    
        
        if((nom_prod !== "" && nom_prod != null &&  nom_prod != undefined ) &&(nom_cli !== "" && nom_cli !== null && nom_cli != undefined)    ){
        
                        /////*****VAlidacion asdasdasdasd
                        if( 
                        ((typeof(ValueProyyec.ProyCNF) != undefined &&  ValueProyyec.ProyCNF != "") && (typeof(ValueProyyec.montoconfi) !== undefined &&   ValueProyyec.montoconfi != "" /*&&   ValueProyyec.montoconfi != "0"*/ ))|| 
                        ((typeof(ValueProyyec.ProyREV1) != undefined && ValueProyyec.ProyREV1 != "" )   &&  (typeof(ValueProyyec.montorev01) !== undefined /*&&   ValueProyyec.montorev01 != "0"*/ &&  ValueProyyec.montorev01 != ""))||
                        ( (typeof(ValueProyyec.ProyREV2) != undefined && ValueProyyec.ProyREV2 != "" ) &&  (typeof(ValueProyyec.montorev02) !== undefined /*&&   ValueProyyec.montorev02 != "0"*/ &&  ValueProyyec.montorev02 != "") )
                            )  
                {
                                  OBJETMIN  =  {
                                 "itsprofuera" : 0,   
                                "prsidcnf":0,
                                "prsidrv2":0,
                                "prsidrv1":0,
                                 "prsid" :String(cve_cli+cve_prod),
                                "cveprod":cve_prod, 
                                "cveclie":cve_cli,
                                "nom_prod":nom_prod,
                                "nom_cli":nom_cli,
                                "proycapt":ValueProyyec ,
                            
                                "familia":"",
                                                                                        "venta1CNF":0,
                                                                                        "venta1RV1":0,
                                                                                        "venta1RV2":0
                    
                            };
                                
                                PROYESINPRE.push(OBJETMIN);   
                            
                }
                            
                  console.log(ValueProyyec);       
                      
                            
         }else {
                OBJETMIN =  false; 
         }             
     }else{
         OBJETMIN =  false; 
           $("#coment_adi").text("Lo Sentimos NO podemos  Agragar Proyeccion para el \n Cliente: "+nom_cli+"\n Con el Producto: "+nom_prod +" Dado a que Existe Proyeccion"  );
          $("#Modal_ERROR").modal("show");
     }
    return  OBJETMIN;
    
 
}
  ///****Funcion para Obtener  Un Elemento  FLOTAT  CON DOSS  DeCIAMELES
 function  GetFlot2Deci(NUM)
 {
     var elemFix = (NUM).toFixed(2)

    return   parseFloat(elemFix); 
 }  


//////*******************************************************************************
  function   GetProyeccionROw(){
        var   arregloproy = new Array(); 
        for( var i  in areglopr)
        {
                    try{
                            if(areglopr[i].prsidcnf == 0)
                            {
                                ///***Obtenemos IDENTIDFICADOR iD  A
                var  idmadeCNF =  "#CFN"+areglopr[i].prsid;
                var  idmadeCFNPRE=  "#CFNPRE"+areglopr[i].prsid;
                var  idmadeCFNMON =  "#CFNMON"+areglopr[i].prsid;

                 var  idmadeRVN01PROYEC =  "#RVN01PROYEC"+areglopr[i].prsid;
                var  idmadeRVN01PREC=  "#RVN01PRECIOS"+areglopr[i].prsid;
                var  idmadeRVN01 =  "#RVN01"+areglopr[i].prsid;


                var  idmadeRVN02PROYEC =  "#RVN02PROYEC"+areglopr[i].prsid;
                var  idmadeRVN02PREC=  "#RVN02PRECIOS"+areglopr[i].prsid;
                var  idmadeRVN02 =  "#RVN02"+areglopr[i].prsid;
 
                            }else {    
                              ///***Obtenemos IDENTIDFICADOR iD  A
                var  idmadeCNF =  "#CFN"+areglopr[i].prsidcnf;
                var  idmadeCFNPRE=  "#CFNPRE"+areglopr[i].prsidcnf;
                var  idmadeCFNMON =  "#CFNMON"+areglopr[i].prsidcnf;

                 var  idmadeRVN01PROYEC =  "#RVN01PROYEC"+areglopr[i].prsidrv1;
                var  idmadeRVN01PREC=  "#RVN01PRECIOS"+areglopr[i].prsidrv1;
                var  idmadeRVN01 =  "#RVN01"+areglopr[i].prsidrv1;


                var  idmadeRVN02PROYEC =  "#RVN02PROYEC"+areglopr[i].prsidrv2;
                var  idmadeRVN02PREC=  "#RVN02PRECIOS"+areglopr[i].prsidrv2;
                var  idmadeRVN02 =  "#RVN02"+areglopr[i].prsidrv2;


                var  idmadeREV1  = "#RVN01"+areglopr[i].prsid;
                var  idmadeREV2 = "#RVN02"+areglopr[i].prsid;
                            }

                            ////*********Input PROYECCION*****************************************************************
                              var  ProyCNF =  $(idmadeCNF).val().replace(',', "");
                              var  preconfi=  $(idmadeCFNPRE).val();//.replace(',', ""),
                  var montoconfi= $(idmadeCFNMON).val().replace(',', "");
                                ///****REV01
                                var ProyREV1= $(idmadeRVN01PROYEC).val().replace(',', "");
                                var prerev01=$(idmadeRVN01PREC).val();//.replace(',', ""),
                                var montorev01=$(idmadeRVN01).val().replace(',', "");
                                ///***REV2
                                var ProyREV2=$(idmadeRVN02PROYEC).val().replace(',', ""); 
                                var prerev02=$(idmadeRVN02PREC).val();//.replace(',', ""),
                                var montorev02=$(idmadeRVN02).val().replace(',', ""); 
                            ////********************************************************************************************************
                                console.log(prerev02);








                var  ValueProyyec = {
                                ///****GET INFO CNF
                                "ProyCNF": $(idmadeCNF).val().replace(',', ""),
                                "preconfi":$(idmadeCFNPRE).val(),//.replace(',', ""),
                                "montoconfi":$(idmadeCFNMON).val().replace(',', ""),
                                ///****REV01
                                "ProyREV1": $(idmadeRVN01PROYEC).val().replace(',', ""),
                                "prerev01":$(idmadeRVN01PREC).val(),//.replace(',', ""),
                                "montorev01":$(idmadeRVN01).val().replace(',', ""),
                                ///***REV2
                                "ProyREV2":$(idmadeRVN02PROYEC).val().replace(',', ""), 
                                "prerev02":$(idmadeRVN02PREC).val(),//.replace(',', ""),
                                "montorev02":$(idmadeRVN02).val().replace(',', "")


                }
                        
                        ///*****SI ESTA  VACIA LA PROYECCION PONEMOS PROYECCION ****************************************************************************************************    
                        if((typeof(ValueProyyec.ProyCNF) === undefined ||  ValueProyyec.ProyCNF === ""))
                        {
                            ///*****OBTENEMOS   EL PRESUPUESTO  
                            var PRESUPUCNF =  $(idmadeCNF).attr('PRESUCNF');
                            ValueProyyec.ProyCNF  = PRESUPUCNF;
                        }
                        /////*****************************************************************************
                        if((typeof(ValueProyyec.ProyREV1) === undefined || ValueProyyec.ProyREV1 === "" ) )
                        {
                            var PRESUPUREV1=$(idmadeRVN01PROYEC).attr('PRESURV1');
                            ValueProyyec.ProyREV1=PRESUPUREV1
                        }
                        ///************************************************************
                        if((typeof(ValueProyyec.ProyREV2) === undefined || ValueProyyec.ProyREV2 === "" ))
                        {
                             var PRESUPUREV2=$(idmadeRVN02PROYEC).attr('PRESURV2');
                            ValueProyyec.ProyREV2=PRESUPUREV2 
                            
                        }
                        
                        ///*********************************************************************************************************
                if( 
    ((typeof(ValueProyyec.ProyCNF) != undefined &&  ValueProyyec.ProyCNF != "") && (typeof(ValueProyyec.montoconfi) !== undefined &&   ValueProyyec.montoconfi != "" /*&&   ValueProyyec.montoconfi != "0"*/ ))|| 
    ((typeof(ValueProyyec.ProyREV1) != undefined && ValueProyyec.ProyREV1 != "" )   &&  (typeof(ValueProyyec.montorev01) !== undefined /*&&   ValueProyyec.montorev01 != "0"*/ &&  ValueProyyec.montorev01 != ""))||
    ( (typeof(ValueProyyec.ProyREV2) != undefined && ValueProyyec.ProyREV2 != "" ) &&  (typeof(ValueProyyec.montorev02) !== undefined /*&&   ValueProyyec.montorev02 != "0"*/ &&  ValueProyyec.montorev02 != "") )
                )  
                {
                        areglopr[i].proycapt=ValueProyyec;  
                        arregloproy.push(areglopr[i]);
                }

        }catch(e)
                        {
                            console.log("ERROR EN  EL OBJETO :"+ e +"\N" +"lINEA ERROR"+e.lineNumber );
                            console.log(areglopr[i]);
                            
                        }



         }
         console.log(arregloproy)

        return arregloproy           

  }

        
 function  HTML_ROW_PROYECCION(OBJEPRYECCION,CONCE)
 {
     
  var AREGPROYEC = OBJEPRYECCION.proycapt ;
  var NOM_PRODUCTO = String(OBJEPRYECCION.nom_prod);
  
  var CNF_presio = currency( GetFlot2Deci(parseFloat(AREGPROYEC.preconfi)), 2, [',', ",", '.']);
  var CNF_MONTO = currency( GetFlot2Deci(parseFloat(AREGPROYEC.montoconfi)), 2, [',', ",", '.']);
  
     
     
     /*
     <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                         
                    </div>
     
     */
  
  var STRNGROW =   '<tr><td>'+
                             OBJEPRYECCION.nom_cli + 
                            '</td><td><strong>NODISPONIBLE</strong></td> '+ 
                            '<td>'+
                             NOM_PRODUCTO +   
                            '</td>'+ 
                            '<td class="cnfhidencol VTS">0</td>'+ 
                             '<td class="cnfhidencol VTS">0</td>'+ ///currency( GetFlot2Deci(), 2, [',', ",", '.'])
                    '<td class="cnfhidencol"><input  id="CFN'+OBJEPRYECCION.prsid+'"   prsid="'+OBJEPRYECCION.prsid+'"  type="number"  estproy ="CNF"   class="sumconfir   form-control" min=0 value ="'+String( AREGPROYEC.ProyCNF)+'" ></td>'+
                                        '<td class="cnfhidencol"><div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span><input  id="CFNPRE'+OBJEPRYECCION.prsid+'"   prsid="'+OBJEPRYECCION.prsid+'"  type="text" min="0" class="sumconfir   form-control" min=0  value ="'+String( currency( GetFlot2Deci(parseFloat(AREGPROYEC.preconfi)), 2, [',', ",", '.']))+'" > </div> </td>'+
                    '<td class="cnfhidencol"><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span> <input disabled  id="CFNMON'+OBJEPRYECCION.prsid+'"   prsid="'+OBJEPRYECCION.prsid+'"  type="text" min="0" class="intgen form-control" min=0 value ="'+String( currency( GetFlot2Deci(parseFloat(AREGPROYEC.montoconfi)), 2, [',', ",", '.']))+'" > </div>  </td>'+
      
                    '<td class="cnfhidencolrv2 VTS" >0 </td>'+
                                        '<td class="cnfhidencolrv2 VTS" >0</td>'+
                    '<td class="cnfhidencolrv2"   ><input  id="RVN02PROYEC'+OBJEPRYECCION.prsid+'"   prsid="'+OBJEPRYECCION.prsid+'" type="number"   estproy ="CNF"   class="sumrev2   form-control" min=0 value ="'+String( AREGPROYEC.ProyREV2)+'" ></td>'+
                    '<td class="cnfhidencolrv2"><div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span> <input  id="RVN02PRECIOS'+OBJEPRYECCION.prsid+'"   prsid="'+OBJEPRYECCION.prsid+'" type="text" min="0" class="sumrev2   form-control" min=0  value ="'+String( currency( GetFlot2Deci(parseFloat(AREGPROYEC.prerev02)), 2, [',', ",", '.']))+'" ></div></td>'+
                    '<td class="cnfhidencolrv2"><div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span><input disabled  id="RVN02'+OBJEPRYECCION.prsid+'"   prsid="'+OBJEPRYECCION.prsid+'" type="text"  min="0" class="intgen form-control" min=0 value ="'+String( currency( GetFlot2Deci(parseFloat(AREGPROYEC.montorev02)), 2, [',', ",", '.']))+'"  ></div></td>'+
                                        '<td class="cnfhidencolrv1 VTS">0</td>'+
                                         '<td class="cnfhidencolrv1 VTS">0</td>'+
                        '<td class="cnfhidencolrv1"><input  id="RVN01PROYEC'+OBJEPRYECCION.prsid+'"   prsid="'+OBJEPRYECCION.prsid+'"  type="number"  estproy ="CNF"   class="sumrev1   form-control" min=0  value ="'+String( AREGPROYEC.ProyREV1)+'"></td>'+
                    '<td class="cnfhidencolrv1"><div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span><input  id="RVN01PRECIOS'+OBJEPRYECCION.prsid+'"   prsid="'+OBJEPRYECCION.prsid+'"  type="text" min="0" class="sumrev1   form-control" min=0  value ="'+String( currency( GetFlot2Deci(parseFloat(AREGPROYEC.prerev01)), 2, [',', ",", '.']))+'"></div></td>'+
                    '<td class="cnfhidencolrv1"><div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span><input disabled  id="RVN01'+OBJEPRYECCION.prsid+'"   prsid="'+OBJEPRYECCION.prsid+'"   type="text" min="0" class="intgen form-control" min=0  value ="'+String( currency( GetFlot2Deci(parseFloat(AREGPROYEC.montorev01)), 2, [',', ",", '.']))+'"></div></td>'+
                    '</tr>'; 
     
     return  STRNGROW;
     
 }
 function MAKE_HTML_FOR_PROYEC_SINPRES()
 {  
     var  HTML_INPUTS = ""; 
     for(var  i  in PROYESINPRE )
     {
         
         HTML_INPUTS += HTML_ROW_PROYECCION(PROYESINPRE[i],i);
         
     }
     return HTML_INPUTS;
     
 }





$(".intgen").on({
                                "focus": function (event) {
                                        $(event.target).select();
                                },
                                "keyup": function (event) {
                                        $(event.target).val(function (index, value ) {
                                                return value.replace(/\D/g, "")
                                                                        .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                                                                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
                                        });
                                }
 });

$(".decimalprecio").on({
                                "focus": function (event) {
                                        $(event.target).select();
                                },
                                "keyup": function (event) {
                                        $(event.target).val(function (index, value ) {
                                                return value.replace(/\D/g, "")
                                                                        .replace(/([0-9])([0-9]{2})$/, '$1.$2');
                                        });
                                }
 });



$("#btnhidennf").change(function(){

        if($("#btnhidennf").is(":checked")){    
            ///***Bonificacion Habilitada
            $(".cnfhidencol").show();
              $(this).attr('value', 'true');
        }else{ 
            ///***Bonificacion Desabilitada 
            $(".cnfhidencol").hide();
             $(this).attr('value', 'false');
        }
 });

$("#btnhiderev2").change(function(){

        if($("#btnhiderev2").is(":checked")){   
            ///***Bonificacion Habilitada
            $(".cnfhidencolrv2").show();
              $(this).attr('value', 'true');
        }else{ 
            ///***Bonificacion Desabilitada 
            $(".cnfhidencolrv2").hide();
             $(this).attr('value', 'false');
        }
 });
 $("#btnhiderev1").change(function(){

        if($("#btnhiderev1").is(":checked")){   
            ///***Bonificacion Habilitada
            $(".cnfhidencolrv1").show();
              $(this).attr('value', 'true');
        }else{ 
            ///***Bonificacion Desabilitada 
            $(".cnfhidencolrv1").hide();
             $(this).attr('value', 'false');
        }
 });




});
</script>

</div>
<div  class="col-lg-12 col-sm-12">
<!--Contenendor  Filtro-->  
 <div  class="col-lg-12 col-sm-12">     
     <div class="col-sm-1" ></div>
                            <div class="col-sm-6" > 
                                   <form name="form1" id="form1" Method="POST" action="proycli_addproyeccionGEREN.php">
                                <p><?php  echo $idagente;?></p>
                                    <strong>Seleccione Agentes</strong>
                                    <div class="input-group input-group select2-bootstrap-prepend">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                                                <span class="glyphicon glyphicon-search"></span>
                                            </button>
                                        </span>
                                        <select name="agentes" class="form-control select2"  onchange="this.form.submit()" >
                                            
                                            <?php
                                            while ($row = mssql_fetch_array($QERYAGENTES)) {
                                    if ($row['COD_ZONA'] == $_REQUEST['SELECTAGENTE']) {

                echo '<option selected value="'.$row['COD_ZONA'].'"  nomcli="'.utf8_encode($row['AGENTE']).'" >' . $row['ZONA'] . '  &&  ' . utf8_encode($row['AGENTE']) . '</option>';

                                                } else {

                                                     echo '<option  value="'.$row['COD_ZONA'].'"  nomcli="'.utf8_encode($row['AGENTE']).'" >' . $row['ZONA'] . '  &&  ' . utf8_encode($row['AGENTE']) . '</option>';
                                                        
                                                           
                                                        
                                                        
                                                }
                                                
                                            }
                                        
                                            ?>
                                        </select>
                                    </div>

                                </form>

                            </div>
                            <div class="col-sm-6" ></div>

 </div>
 
<!--Fin contenedor-->

<!--Contenedor Tabla-->  
 <div  class="col-lg-12 col-md-12 col-sm-12">   
   <?php IF(isset($_POST['agentes'])){ ?>   
     <table  class="table   table-bordered " id="dataTables-prouyecc"  >
            <thead>
                <!----> 
                <tr>
                                    <td><button   type="button" class="btn btn-info"    id="saveelem" >Guardar Proyeccion <span class="glyphicon glyphicon-floppy-disk"></span></button>   </td>
                    <td><button   type="button" class="btn btn-info"    id="BTNREFRESH">Refrescar <span class="glyphicon glyphicon-refresh"></span></button> </td>
                                        <td > 
                                            <div class="ocultarcol">
                                                <div  class="row">  <strong>  <input id="btnhidennf" checked   type="checkbox"/>Ocultar  Confirmacion </strong> </div>
                                                <div  class="row">  <strong>  <input id="btnhiderev2" checked  type="checkbox"/>Ocultar Revision 2</strong></div>
                                            <div  class="row">  <strong>  <input id="btnhiderev1" checked  type="checkbox"/>Ocultar Revision 1</strong></div>
                                            </div>
                                        </td>
                    <td class="confir01 cnfhidencol"  colspan  ="5">CONFIRMACION</td>
                    <td class="confir02 cnfhidencolrv2"  colspan  ="5">REVISION 2</td>
                    <td class="confir03 cnfhidencolrv1"   colspan  ="5">REVISION 1</td>
                </tr>
                <!--Contenedor  mes suamtoria -->
                    <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="confir01 cnfhidencol"   colspan  ="4">
                        <?php   /// Mes Confirmacion 
                                echo     name_mes($fecAsigMes['mes_confir']); 
                        ?>      
                    </td>
                    <td class="confir01 cnfhidencol"  disabled  colspan  ="1">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                          <input  type="text" min="0" class=" intgen form-control"   disabled  id="MNCNF"    >
                    </div>


           
             </td>
                    <td class="confir02  cnfhidencolrv2"  colspan  ="4">
                        <?php   ///Mes Revision 2 
                                echo     name_mes($fecAsigMes['mes_rev2']);
                        ?>
                    </td>
                    <td class="confir02  cnfhidencolrv2"  colspan  ="1">
                    <div class="input-group">    
                            <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                          <input  type="text" min="0" class="confir01 intgen form-control"   disabled  id="MNCRV2"    >
                    </div>

           

                    </td>
                    <td class="confir03 cnfhidencolrv1"  colspan  ="4">
                        <?php   ///Me Revision 3 
                                echo     name_mes($fecAsigMes['mes_rev1']);
                        ?>
                    </td>
                    <td class="confir03  cnfhidencolrv1"   colspan  ="1">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                         <input  type="text" min="0"  class="confir01 intgen form-control"   disabled  id="MNCRV1"    >
                    </div>
                        
             </td>
                </tr>
                <!--Fin Contenedor Sumatoria --> 
                <!--Row  MAIN  Informacion-->
                <tr  class="infomain">
                    
                    <td class="colorrow">Cliente   <a id='btn-apli' href="#" data-toggle="tooltip" data-placement="top" title="Agregar Proyeccion por Cliente fuera de Presupuesto"   ><i class="glyphicon glyphicon-plus"> </i> </a></td>
                    <td class="colorrow">Familia</td>
                    <td class="colorrow">Producto </td>
                    <!--Show  Confiramcion-->
                                        <td class="cnfhidencol " > <span data-toggle="tooltip" data-placement="top" title="Venta Historica" >VT2017</span></td>
                                        <td class="cnfhidencol " > <span data-toggle="tooltip" data-placement="top" title="Cantidad Presupuestada" >PPT</span></td>
                    <td class="cnfhidencol">Proyeccion</td>
                    <td class="cnfhidencol">Precio</td>
                    <td class="cnfhidencol"  >Monto</td>
                    <!--Show  Revision 2-->
                                        <td class="cnfhidencolrv2" > <span data-toggle="tooltip" data-placement="top" title="Venta Historica" >VT2017</span></td>
                    <td class="cnfhidencolrv2"><span data-toggle="tooltip" data-placement="top" title="Cantidad Presupuestada" >PPT</span> </td>
                    <td  class="cnfhidencolrv2">Proyeccion</td>
                    <td class="cnfhidencolrv2">Precio</td>
                    <td class="cnfhidencolrv2">Monto</td>
                    <!--Show  Revision 1-->
                                        <td class="cnfhidencolrv1" > <span data-toggle="tooltip" data-placement="top" title="Venta Historica" >VT2017</span></td>
                    <td class="cnfhidencolrv1"><span data-toggle="tooltip" data-placement="top" title="Cantidad Presupuestada" >PPT</span></td>
                    <td class="cnfhidencolrv1">Proyeccion</td>
                    <td class="cnfhidencolrv1">Precio</td>
                    <td class="cnfhidencolrv1">Monto</td>
                </tr>
                <tr>
                    <td>
                                            <div class="input-group">

                                                          <input disabled  id="clientenew" type="text" class="form-control"  placeholder="Cliente">
                                        </div>  
                                                </td>
                                                        <td><strong>NODISPONIBLE</strong> 
                                                </td>   
                                                <td>
                                                        <div class="input-group">
                                                                <input disabled  id="prodnew" type="text" class="form-control"  placeholder="Producto">
                                                        </div>   
                                                </td>
                                        <td class="cnfhidencol"> <input disabled   type="text"  class="form-control" min=0  value="VT2017" > </td>
                    <td class="cnfhidencol"><input disabled   type="text"  class="form-control" min=0  value="PPT" > </td>
                    <td class="cnfhidencol"><input  id="CFNNEW"   prsid="NEW"  type="number"  estproy ="CNF"   class="sumconfir   form-control" min=0 value=0 ></td>
              <td class="cnfhidencol"><div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span><input  id="CFNPRENEW" prsid="NEW"  type="number"  step="any"   class="sumconfir   form-control" min=0 value=0   ></div></td>
                    <td class="cnfhidencol"><div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span> 
            <input disabled  id="CFNMONNEW" prsid="NEW"   type="text"  class="intgen form-control" min=0  value=0  ></div></td>
                    <!--Show  Revision 2-->
                      <td class="cnfhidencolrv2"> <input disabled   type="text"  class="form-control" min=0  value="VT2017" ></td>
              <td class="cnfhidencolrv2"><input disabled   type="text"  class="form-control" min=0  value="PPT" ></td>
                    <td class="cnfhidencolrv2"><input  id="RVN02PROYECNEW"   type="number" prsid="NEW"  estproy ="CNF"   class="sumrev2   form-control" min=0  value="0" ></td>
                    <td class="cnfhidencolrv2" >
            <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
             <!--PRECIO-->
              <input  id="RVN02PRECIOSNEW" prsid="NEW"  type="number"  step="any"   class="sumrev2   form-control" min=0 value="0.00" >
            </div>
          </td>
                    <td class="cnfhidencolrv2"><div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
            <input disabled  id="RVN02NEW"   type="text" prsid="NEW"  class="intgen form-control" min=0  value="0"></div></td>
                                        
                                        
                    <!--Show  Revision 1-->
            <td class="cnfhidencolrv1"><input disabled   type="text"  class="form-control" min=0  value="VT2017" ></td>
                    <td class="cnfhidencolrv1"> <input disabled   type="text"  class="form-control" min=0  value="PPT" ></td>
                  <td class="cnfhidencolrv1">
           <input  id="RVN01PROYECNEW"  prsid="NEW"  type="number"  estproy ="CNF"   class="sumrev1   form-control" min=0 value=0  ></td>
                    <td class="cnfhidencolrv1">
            <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                  <input  id="RVN01PRECIOSNEW" prsid="NEW"  type="number"  step="any"   class="sumrev1   form-control" min=0  value=0 >
            </div>
          </td>
                    <td class="cnfhidencolrv1"><div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
            <input disabled  id="RVN01NEW" prsid="NEW"   type="text"  class="intgen form-control" min=0  value=0 ></div></td>


                </tr> 
                             
                </thead>
                                <thead id="contproyeccfuera"> 
                               
                                
                                
                                </thead>
            <tbody>
                            
                                  
                <?php
                                    /*
                                     * <tbody id="contproyeccfuera">
                            
                        </tbody>
                                     */
                                
                                
                    /* echo         $string_get_info =sprintf("select * from  pedidos.vwpROYECC_FORMATO_AGREGADO_PROYECCION where num_agente = %s order by cliente",
                                        GetSQLValueString($_SESSION["usuario_agente"], "int"));*/
                

                            $string_get_info =sprintf("select  *  from #TEMPORAL_PROYECCIONES where num_agente = %s   order by cliente",
                                        GetSQLValueString($idagente, "int"));


                        ///****Generamos QERY Obtener  Presupuesto   
                        $qergetpresu  = mssql_query($string_get_info);
                        ///***Variabel change  color
                        $stecolor =true;
                        ///****Arreglo contenedorIdentificadores
                        $AReyPre =  array() ; 
                        ///******
                        $SUM_MOTO_CNF = 0; 
                        $SUM_MOTO_RV2= 0; 
                        $SUM_MOTO_RV1 =  0 ;


                        while($rowpresuget= mssql_fetch_array($qergetpresu))
                        {
                            /*
                            *************Determinamos si ES UNA PROYECCION PRESUPUESTADA O NUEVA PROYECCION 
                            */  
                             if( $rowpresuget['id_prs_confir'] == 0  && $rowpresuget['id_prs_rev2'] ==0 && $rowpresuget['id_prs_rev1'] ==0)
                             {
                                                             $DETER_TYPE_REGISTRO = FALSE;
                              
                             }else{
                                   $DETER_TYPE_REGISTRO = TRUE;
                                                                    

                                                                
                                                                   
                                                                   
                                                                   
                                                                   
                             }  


///INCIO DETERMINA SI ES UNA PROYECCION NO PRESUPUESTADA 
if($DETER_TYPE_REGISTRO == FALSE)
{
                                //**************************************************************************************************************************
                                
                                    echo  '<tr class="elementPROYECNOPRESUPUESTADA">'; 
                                    $stecolor=false; 
                            
                                

                                echo  '<td >'.$rowpresuget['cliente'].'</td>';
                                echo  '<td >'.$rowpresuget['familia'].'</td>';
                                echo  '<td >'.$rowpresuget['nombre_prod'].'</td>';                  

/*------------------------COLUMNAS  CONFIRMACION --------------------------------------------------------------------------------------------------------------------------*/   
                                                                echo  '<td class="cnfhidencol  VTS" >'. number_format($rowpresuget['venta1CNF'], 2, '.', ',') .'</td>';
                                $montocon =  $rowpresuget['precio_confir']*$rowpresuget['proyeccion_confir'] ;
                                echo  '<td class="cnfhidencol VTS" ></td>';
                                /*-------Input obtener  proyecciones Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencol">
      <input  id="CFN'.$rowpresuget['id_proyec'].'" prsid ="'.$rowpresuget['id_proyec'].'"  type="number"  estproy ="CNF"   class="sumconfir   form-control" min=0  value='.$rowpresuget['proyeccion_confir'] .'  ></td>';
        /*-------Input Precio Proyeccion Confirmacion---------------------------------------------------------------*/

                echo  '<td class="cnfhidencol">
        <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                            <input    id="CFNPRE'.$rowpresuget['id_proyec'].'" prsid ="'.$rowpresuget['id_proyec'].'"  type="number" step="any" class="sumconfir   form-control" min=0  value='.number_format($rowpresuget['precio_confir'], 2, '.', ',')      .'>
                </div> 

                  </td>';
         


       /*-------Input Monto  Proyeccion Confirmacion---------------------------------------------------------------*/
       
                echo  '<td class="cnfhidencol">
                            <div class="ocultarINPUTS" >
                <input disabled  id="CFNMON'.$rowpresuget['id_proyec'].'" prsid ="'.$rowpresuget['id_proyec'].'"  type="text" min="0" class="intgen form-control" min=0  value='.$montocon .'></div>
                                                           <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span> <input disabled  id="CFNMONTXT'.$rowpresuget['id_proyec'].'" prsid ="'.$rowpresuget['id_proyec'].'"  type="text" min="0" class="intgen form-control" min=0  value="'.number_format($montocon, 2, '.', ',') .'"></div> 
                                                        
                  </td>';


                  /****REALIZAMOS  LA SUMATORIA DE CNF LOS  MONTOS ****/
                  $SUM_MOTO_CNF  += $montocon ; 
    /*------------------------------------------------COLUMNAS REVICION 002---------------------------------------------------------------------------------------------------*/    
                                $montorev2 =  $rowpresuget['precio_rev2']*$rowpresuget['proyeccion_rev2'] ; 
                                                                echo  '<td class="cnfhidencolrv2 VTS" >  0</td>';
                                echo  '<td class="cnfhidencolrv2 VTS" > 0</td>';
                /*-------Input obtener  proyecciones Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencolrv2"><input  id="RVN02PROYEC'.$rowpresuget['id_proyec'].'" prsid ="'.$rowpresuget['id_proyec'].'"  type="number" min="0"  estproy ="RV2"  class="sumrev2   form-control" min=0 value="'. $rowpresuget['proyeccion_rev2'].'"  ></td>';
            

                        /*-------Input Precio Proyeccion Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencolrv2"> <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span><input  id="RVN02PRECIOS'.$rowpresuget['id_proyec'].'" prsid ="'.$rowpresuget['id_proyec'].'" type="number" step="any"  min="0" class="sumrev2   form-control" min=0  value="'. $rowpresuget['precio_rev2'].'"></div></td>';
                            


                    /*-------Input Monto  Proyeccion Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencolrv2"><div class="ocultarINPUTS" >
        <input disabled  id="RVN02'.$rowpresuget['id_proyec'].'" prsid ="'.$rowpresuget['id_proyec'].'" type="text" min="0" class="intgen form-control" min=0  value='.$montorev2 .'></div>'
                            . ' <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span><input disabled  id="RVN02TXT'.$rowpresuget['id_proyec'].'" prsid ="'.$rowpresuget['id_proyec'].'" type="text" min="0" class="intgen form-control" min=0  value="'.  number_format( $montorev2 , 2, '.', ',')   .'"></div>
                            </td>';
                /****REALIZAMOS  LA SUMATORIA DE REV2 LOS  MONTOS ****/
                            $SUM_MOTO_RV2  += $montorev2 ;  

           
/*----------------------COLUMNAS REVICION 001--------------------id_RV1 , id_RV2---------- precioRV2,montoRV2 ----------------------------------------------------------------------------------------------*/  
            $montorev1 =  $rowpresuget['precio_rev1']*$rowpresuget['proyeccion_rev1'] ;
                                
                                echo  '<td class="cnfhidencolrv1"></td>';
                                                             echo  '<td class="cnfhidencolrv1"></td>';    

                 /*-------Input CAPTURAR   proyecciones Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencolrv1"><input  id="RVN01PROYEC'.$rowpresuget['id_proyec'].'" prsid ="'.$rowpresuget['id_proyec'].'" type="number" estproy ="RV1" class="sumrev1   form-control" min=0 value='.$rowpresuget['proyeccion_rev1'].'  ></td>';

             

                                /*-------Input Precio Proyeccion Confirmacion---------------------------------------------------------------*/
                 echo  '<td class="cnfhidencolrv1">  <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span><input  id="RVN01PRECIOS'.$rowpresuget['id_proyec'].'" prsid ="'.$rowpresuget['id_proyec'].'"  type="number" step="any" min="0" class="sumrev1   form-control" min=0  value="'.$rowpresuget['precio_rev1'].'"></div></td>';

         
           

                    /*-------Input Monto  Proyeccion Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencolrv1"><div class="ocultarINPUTS" >
        <input disabled  id="RVN01'.$rowpresuget['id_proyec'].'" prsid ="'.$rowpresuget['id_proyec'].'"  type="text" min="0" class="intgen form-control" min=0  value='. $montorev1.'></div>'
                            . ' <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span><input disabled  id="RVN01TXT'.$rowpresuget['id_proyec'].'" prsid ="'.$rowpresuget['id_proyec'].'"  type="text" min="0" class="intgen form-control" min=0  value="'.number_format( $montorev1, 2, '.', ',') .'"></div> 
                            </td>'; 
                    /****REALIZAMOS  LA SUMATORIA DE REV1 LOS  MONTOS ****/
                    $SUM_MOTO_RV1   += $montorev1;   


           ///****ARREGLO DE OBJETOS  
                                $OBJ =  array(
                                                "prsid"=>$rowpresuget['id_proyec'],
                                                                                         "prsidcnf"=>0,
                                                "prsidrv2"=>0,
                                                "prsidrv1"=>0,
                                                "cveprod"=>$rowpresuget['cve_prod'],
                                                "cveclie"=>$rowpresuget['cve_cliente'],
                                                "nom_cli"=>$rowpresuget['cliente'],
                                                "nom_prod"=>$rowpresuget['nombre_prod'],
                                                                                        "itsprofuera"=> 0,
                                                                                        "familia"=>$rowpresuget['familia'],
                                                "proycapt"=>"",
                                                                                        "venta1CNF"=>$rowpresuget['venta1CNF'],
                                                                                        "venta1RV1"=>$rowpresuget['venta1RV1'],
                                                                                        "venta1RV2"=>$rowpresuget['venta1RV2']

                                );  
                                ////Agregamos al  Areglo
                                array_push($AReyPre, $OBJ); 

}///FIN DETERMINA SI ES UNA PROYECCION NO PRESUPUESTADA 


///INCIO DETERMINA SI ES UNA PROYECCION DE PRESUPUESTO
if($DETER_TYPE_REGISTRO == TRUE )
{
                            //*********************Realizamos una consulta  para obtener  las  proyecciones por  clienetes  en  curso *****************************************************************************************************
                            $strgetMAINProy = sprintf("SELECT * FROM  pedidos.proyec_JoinProyecciones  where  num_agenteCNF =%s  AND cve_clienteCNF = %s and  cve_prodCNF =%s", 
                                GetSQLValueString( $rowpresuget['num_agente'],"int"),
                                GetSQLValueString( $rowpresuget['cve_cliente'] ,"text"),
                            GetSQLValueString( $rowpresuget['cve_prod'] ,"text"));

                        /// echo   '<tr><td>'.$strgetMAINProy.'</td></tr>';
                            ///****Realizamosla peticion para obtener la proyeccion cargada    
                            $qergetPROYECMAI  =  mssql_query($strgetMAINProy);
                            ///****Obtenemos el  arreglo  Asociativo 
                            $PROYECMAIN_AGREGADOS = mssql_fetch_array($qergetPROYECMAI);

                            //**************************************************************************************************************************
                                if($stecolor)
                                {
                                    echo  '<tr class="element01">'; 
                                    $stecolor=false; 
                            
                                }else{
                                    echo  '<tr>';
                                    $stecolor=true; 
                             
                                }   

                                echo  '<td >'.$rowpresuget['cliente'].'</td>';
                                echo  '<td >'.$rowpresuget['familia'].'</td>';
                                echo  '<td >'.$rowpresuget['nombre_prod'].'-'.$PROYECMAIN_AGREGADOS['cve_prodCNF'].'</td>';                 

    /*------------------------COLUMNAS  CONFIRMACION --------------------------------------------------------------------------------------------------------------------------*/       
                                                              echo  '<td class="cnfhidencol VTS" > '.number_format($rowpresuget['venta1CNF'], 2, '.', ',').'</td>';
                                $montocon =  $rowpresuget['precio_confir']*$rowpresuget['cantidad_confir'] ;
                                echo  '<td class="cnfhidencol VTS">'.$rowpresuget['cantidad_confir'] .'</td>';
        /*-------Input Precio Proyeccion Confirmacion---------------------------------------------------------------*/
        //****Validadmos  si Existe en  la Tabla de proyecciones Main   id_CNF
        if($rowpresuget['id_prs_confir'] ==$PROYECMAIN_AGREGADOS['id_prsCNF']  ){
       

                /*-------Input obtener  proyecciones Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencol"><input  id="CFN'.$rowpresuget['id_prs_confir'].'" prsid ="'.$rowpresuget['id_prs_confir'].'"  type="number"  estproy ="CNF"   class="sumconfir   form-control" min=0  PRESUCNF="'.$rowpresuget['cantidad_confir'].'"    value='.$PROYECMAIN_AGREGADOS['proyeccionCNF'] .' ></td>';
        
                  echo  '<td class="cnfhidencol" >
                              <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span><input   id="CFNPRE'.$rowpresuget['id_prs_confir'].'" prsid ="'.$rowpresuget['id_prs_confir'].'" type="number" step="any" min="0" class="sumconfir   form-control"  value="'.number_format( $PROYECMAIN_AGREGADOS['precioCNF'], 2, '.', ',') .'"></div>
 
                  </td>';


        }else {
                        /*-------Input obtener  proyecciones Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencol"><input  id="CFN'.$rowpresuget['id_prs_confir'].'" prsid ="'.$rowpresuget['id_prs_confir'].'"  type="number"  estproy ="CNF"   class="sumconfir   form-control" min=0  PRESUCNF="'.$rowpresuget['cantidad_confir'].'"    value='.$rowpresuget['cantidad_confir'] .' ></td>';
        
                echo  '<td class="cnfhidencol">
                             <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                 <input    id="CFNPRE'.$rowpresuget['id_prs_confir'].'" prsid ="'.$rowpresuget['id_prs_confir'].'" type="number" step="any" min="0" class="sumconfir   form-control" min=0  value="'.number_format($rowpresuget['precio_confir'], 2, '.', ',')      .'">
                 </div>

                  </td>';
         } 


       /*-------Input Monto  Proyeccion Confirmacion---------------------------------------------------------------*/
       if($rowpresuget['id_prs_confir'] ==$PROYECMAIN_AGREGADOS['id_prsCNF']    ){

                  echo  '<td  class="cnfhidencol">
                         <div class="ocultarINPUTS" ><input   id="CFNMON'.$rowpresuget['id_prs_confir'].'" prsid ="'.$rowpresuget['id_prs_confir'].'"  type="text" min="0" class="intgen form-control" min=0  value='.number_format($PROYECMAIN_AGREGADOS['montoCNF'], 2, '.', ',')   .'></div>
                <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                    <input disabled  id="CFNMONTXT'.$rowpresuget['id_prs_confir'].'" prsid ="'.$rowpresuget['id_prs_confir'].'"  type="text" min="0" class="intgen form-control" min=0  value="'.number_format($PROYECMAIN_AGREGADOS['montoCNF'], 2, '.', ',')   .'">   </div> 
                                        </td>';
                  /****REALIZAMOS  LA SUMATORIA DE CNF LOS  MONTOS ****/
                  $SUM_MOTO_CNF  += $PROYECMAIN_AGREGADOS['montoCNF']; 

        }else {

                echo  '<td class="cnfhidencol">
                            <div class="ocultarINPUTS" ><input   id="CFNMON'.$rowpresuget['id_prs_confir'].'" prsid ="'.$rowpresuget['id_prs_confir'].'"  type="text" min="0" class="intgen form-control" min=0  value='.number_format($montocon, 2, '.', ',') .'></div>
                                                         <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                                       <input disabled  id="CFNMONTXT'.$rowpresuget['id_prs_confir'].'" prsid ="'.$rowpresuget['id_prs_confir'].'"  type="text" min="0" class="intgen form-control" min=0  value="'.number_format($montocon, 2, '.', ',') .'"> </div>     
                                                            
                  </td>';


                  /****REALIZAMOS  LA SUMATORIA DE CNF LOS  MONTOS ****/
                  $SUM_MOTO_CNF  += $montocon ; 

         } 

        /*------------------------------------------------COLUMNAS REVICION 002---------------------------------------------------------------------------------------------------*/    
                                $montorev2 =  $rowpresuget['precio_rev2']*$rowpresuget['cantidad_rev2'] ;
                                                                  echo  '<td class="cnfhidencolrv2 VTS" > '.number_format($rowpresuget['venta1RV2'], 2, '.', ',') .'</td>';
                                echo  '<td class="cnfhidencolrv2 VTS">'.$rowpresuget['cantidad_rev2'].'</td>';
                        
         //   if($rowpresuget['id_prs_rev2'] ==$PROYECMAIN_AGREGADOS['id_prsRV2'] ){
      if($rowpresuget['cve_prod'] ==$PROYECMAIN_AGREGADOS['cve_prodRV2'] && $rowpresuget['cve_cliente'] ==$PROYECMAIN_AGREGADOS['cve_clienteRV2']  ){  

                        /*-------Input obtener  proyecciones Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencolrv2"><input  id="RVN02PROYEC'.$rowpresuget['id_prs_rev2'].'" prsid ="'.$rowpresuget['id_prs_rev2'].'"  type="number" min="0"  estproy ="RV2"  class="sumrev2   form-control" min=0  PRESURV2="'.$rowpresuget['cantidad_rev2'].'"      value='. $PROYECMAIN_AGREGADOS['proyeccionRV2'] .'></td>';
            
                        /*-------Input Precio Proyeccion Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencolrv2">
           <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
        <input  id="RVN02PRECIOS'.$rowpresuget['id_prs_rev2'].'" prsid ="'.$rowpresuget['id_prs_rev2'].'"  type="number" step="any" min="0" class="sumrev2   form-control" min=0  value="'. $PROYECMAIN_AGREGADOS['precioRV2'] .'"></div> 
 
                  </td>';


             }else{
                             /*-------Input obtener  proyecciones Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencolrv2"><input  id="RVN02PROYEC'.$rowpresuget['id_prs_rev2'].'" prsid ="'.$rowpresuget['id_prs_rev2'].'"  type="number" min="0"  estproy ="RV2"  class="sumrev2   form-control" min=0  PRESURV2="'.$rowpresuget['cantidad_rev2'].'"      value='. $rowpresuget['cantidad_rev2'].'></td>';
            
                        /*-------Input Precio Proyeccion Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencolrv2">
           <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
        <input  id="RVN02PRECIOS'.$rowpresuget['id_prs_rev2'].'" prsid ="'.$rowpresuget['id_prs_rev2'].'"  type="number" step="any" min="0" class="sumrev2   form-control" min=0  value="'. $rowpresuget['precio_rev2'] .'"> </div></td>';
                            
            }

            ///if($rowpresuget['id_prs_rev2'] ==$PROYECMAIN_AGREGADOS['id_prsRV2']    ){
        if($rowpresuget['cve_prod'] ==$PROYECMAIN_AGREGADOS['cve_prodRV2'] && $rowpresuget['cve_cliente'] ==$PROYECMAIN_AGREGADOS['cve_clienteRV2']  ){ 
                    /*-------Input Monto  Proyeccion Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencolrv2"><div class="ocultarINPUTS" ><input disabled  id="RVN02'.$rowpresuget['id_prs_rev2'].'" prsid ="'.$rowpresuget['id_prs_rev2'].'"  type="text" min="0" class="intgen form-control" min=0  value='. number_format($PROYECMAIN_AGREGADOS['montoRV2'], 2, '.', ',')    .'></div>
             <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                            <input disabled  id="RVN02TXT'.$rowpresuget['id_prs_rev2'].'" prsid ="'.$rowpresuget['id_prs_rev2'].'"  type="text" min="0" class="intgen form-control" min=0  value="'. number_format($PROYECMAIN_AGREGADOS['montoRV2'], 2, '.', ',')    .'"></div>
                  </td>';
                  /****REALIZAMOS  LA SUMATORIA DE REV2 LOS  MONTOS ****/
                            $SUM_MOTO_RV2  += $PROYECMAIN_AGREGADOS['montoRV2'] ; 

            }else{

                    /*-------Input Monto  Proyeccion Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencolrv2">'
                        . '<div class="ocultarINPUTS" ><input disabled  id="RVN02'.$rowpresuget['id_prs_rev2'].'" prsid ="'.$rowpresuget['id_prs_rev2'].'" type="text" min="0" class="intgen form-control" min=0  value='.  number_format( $montorev2 , 2, '.', ',')   .'></div>'
                        . '<div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>'
                        . '<input disabled  id="RVN02TXT'.$rowpresuget['id_prs_rev2'].'" prsid ="'.$rowpresuget['id_prs_rev2'].'" type="text" min="0" class="intgen form-control" min=0  value="'.  number_format( $montorev2 , 2, '.', ',')   .'"></div>  </td>';
                /****REALIZAMOS  LA SUMATORIA DE REV2 LOS  MONTOS ****/
                            $SUM_MOTO_RV2  += $montorev2 ;  

            }
            



                
        /*----------------------COLUMNAS REVICION 001--------------------id_RV1 , id_RV2---------- precioRV2,montoRV2 ----------------------------------------------------------------------------------------------*/  


                                $montorev1 =  $rowpresuget['precio_rev1']*$rowpresuget['cantidad_rev1'] ;
                                 echo  '<td class="cnfhidencolrv1 VTS" > '.number_format($rowpresuget['venta1RV1'], 2, '.', ',') .'</td>';
                                echo  '<td class="cnfhidencolrv1 VTS">'.$rowpresuget['cantidad_rev1'].'</td>';


            
            /// if($rowpresuget['id_prs_rev1'] ==$PROYECMAIN_AGREGADOS['id_prsRV1']    ){
 if($rowpresuget['cve_prod'] ==$PROYECMAIN_AGREGADOS['cve_prodRV1'] && $rowpresuget['cve_cliente'] ==$PROYECMAIN_AGREGADOS['cve_clienteRV1']  ){ 
                                 /*-------Input CAPTURAR   proyecciones Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencolrv1"><input  id="RVN01PROYEC'.$rowpresuget['id_prs_rev1'].'" prsid ="'.$rowpresuget['id_prs_rev1'].'" type="number" estproy ="RV1" class="sumrev1   form-control" min=0  PRESURV1="'.$rowpresuget['cantidad_rev1'].'"    value='.$PROYECMAIN_AGREGADOS['proyeccionRV1'] .'  ></td>';

                            /*-------Input Precio Proyeccion Confirmacion---------------------------------------------------------------*/
                 echo  '<td class="cnfhidencolrv1">
             <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
           <input  id="RVN01PRECIOS'.$rowpresuget['id_prs_rev1'].'" prsid ="'.$rowpresuget['id_prs_rev1'].'"  type="number" step="any" min="0" class="sumrev1   form-control" min=0  value="'.$PROYECMAIN_AGREGADOS['precioRV1'].'"></div>
 
                  </td>';


             }else{
                                 /*-------Input CAPTURAR   proyecciones Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencolrv1"><input  id="RVN01PROYEC'.$rowpresuget['id_prs_rev1'].'" prsid ="'.$rowpresuget['id_prs_rev1'].'" type="number" estproy ="RV1" class="sumrev1   form-control" min=0  PRESURV1="'.$rowpresuget['cantidad_rev1'].'"    value='.$rowpresuget['cantidad_rev1'].'  ></td>';

                                /*-------Input Precio Proyeccion Confirmacion---------------------------------------------------------------*/
                 echo  '<td class="cnfhidencolrv1">
             <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
           <input  id="RVN01PRECIOS'.$rowpresuget['id_prs_rev1'].'" prsid ="'.$rowpresuget['id_prs_rev1'].'" type="number" step="any" min="0" class="sumrev1   form-control" min=0  value="'.$rowpresuget['precio_rev1'].'"></div></td>';

            }
           /// if($rowpresuget['id_prs_rev1'] ==$PROYECMAIN_AGREGADOS['id_prsRV1']    ){
      if($rowpresuget['cve_prod'] ==$PROYECMAIN_AGREGADOS['cve_prodRV1'] && $rowpresuget['cve_cliente'] ==$PROYECMAIN_AGREGADOS['cve_clienteRV1']  ){ 
                            /*-------Input Monto  Proyeccion Confirmacion---------------------------------------------------------------*/
            echo  '<td class="cnfhidencolrv1"><div class="ocultarINPUTS" ><input disabled  id="RVN01'.$rowpresuget['id_prs_rev1'].'" prsid ="'.$rowpresuget['id_prs_rev1'].'"  type="text" min="0" class="intgen form-control" min=0  value='.$PROYECMAIN_AGREGADOS['montoRV1'].'></div>
         <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                              <input disabled  id="RVN01TXT'.$rowpresuget['id_prs_rev1'].'" prsid ="'.$rowpresuget['id_prs_rev1'].'"  type="text" min="0" class="intgen form-control" min=0  value="'. number_format( $PROYECMAIN_AGREGADOS['montoRV1'] , 2, '.', ',')   .'"> </div> 
                  </td>';
                    /****REALIZAMOS  LA SUMATORIA DE REV1 LOS  MONTOS ****/
                    $SUM_MOTO_RV1   += $PROYECMAIN_AGREGADOS['montoRV1'];   

            }else{

                    /*-------Input Monto  Proyeccion Confirmacion---------------------------------------------------------------*/
            echo  '<td  class="cnfhidencolrv1"><div class="ocultarINPUTS" ><input disabled  id="RVN01'.$rowpresuget['id_prs_rev1'].'" prsid ="'.$rowpresuget['id_prs_rev1'].'"  type="text" min="0" class="intgen form-control" min=0  value='.$montorev1.'></div>'
        .' <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>'
                            . '<input disabled  id="RVN01TXT'.$rowpresuget['id_prs_rev1'].'" prsid ="'.$rowpresuget['id_prs_rev1'].'"  type="text" min="0" class="intgen form-control" min=0  value="'.number_format( $montorev1 , 2, '.', ',')    .'"></div>'
                            . '</td>';  
                    /****REALIZAMOS  LA SUMATORIA DE REV1 LOS  MONTOS ****/
                    $SUM_MOTO_RV1   += $montorev1;   


            }

            ///****ARREGLO DE OBJETOS  
                                $OBJ =  array(
                                                "prsid"=>0,
                          "prsidcnf"=>$rowpresuget['id_prs_confir'],
                                                "prsidrv2"=>$rowpresuget['id_prs_rev2'],
                                                "prsidrv1"=>$rowpresuget['id_prs_rev1'],
                                                "cveprod"=>$rowpresuget['cve_prod'],
                                                "cveclie"=>$rowpresuget['cve_cliente'],
                                                "nom_cli"=>$rowpresuget['cliente'],
                                                "nom_prod"=>$rowpresuget['nombre_prod'],
                                                                                        "familia"=>$rowpresuget['familia'],
                                                                                        "itsprofuera"=> 0,  
                                                "proycapt"=>"",
                                                                                        "venta1CNF"=>$rowpresuget['venta1CNF'],
                                                                                        "venta1RV1"=>$rowpresuget['venta1RV1'],
                                                                                        "venta1RV2"=>$rowpresuget['venta1RV2']

                                );  
                                ////Agregamos al  Areglo
                                array_push($AReyPre, $OBJ);

        }///FIN DETERMINA SI ES UNA PROYECCION DE PRESUPUESTO

                                
                                




                        }

                        
                         $MONTARG =  array  ( "MNTCNF" => $SUM_MOTO_CNF,
                                              "MNTRV2" => $SUM_MOTO_RV2 ,
                                              "MNTRV1" => $SUM_MOTO_RV1 )  ;


     ?> 
            </tbody>
     </table>

<?php } ?>


 </div>
<!--Fin contenedor-->
<script type="text/javascript">

             //************************************************************************************ 
////*****Convertir a  Formato  Moneda  currency(ArglMain[i].CMG, 2, [',', ",", '.'])
function currency(value, decimals, separators) {
        decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
        separators = separators || ['.', "'", ','];
        var number = (parseFloat(value) || 0).toFixed(decimals);
        if (number.length <= (4 + decimals))
                return number.replace('.', separators[separators.length - 1]);
        var parts = number.split(/[-.]/);
        value = parts[parts.length > 1 ? parts.length - 2 : 0];
        var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
                separators[separators.length - 1] + parts[parts.length - 1] : '');
        var start = value.length - 6;
        var idx = 0;
        while (start > -3) {
                result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start))
                        + separators[idx] + result;
                idx = (++idx) % 2;
                start -= 3;
        }
        return (parts.length == 3 ? '-' : '') + result;
} 


         var   areglopr = <?php  echo  json_encode($AReyPre);   ?>; 
         var   Areg_MONTOS =<?php   echo json_encode($MONTARG);    ?>;    

         ///***** ASIGNAMOS  MOTNOTS 
        document.getElementById("MNCNF").value = currency(Areg_MONTOS.MNTCNF , 2, [',', ",", '.']);     
         document.getElementById("MNCRV2").value =currency( Areg_MONTOS.MNTRV2, 2, [',', ",", '.']); 
        document.getElementById("MNCRV1").value = currency( Areg_MONTOS.MNTRV1, 2, [',', ",", '.']); 



</script>
<!--************Dialog Estatus********************-->
     <div  class="modal fade" id="MODSELC" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
                <button type="button" class="close_coment close" data-dismiss="modal">&times;</button>
                <h5>Agregado de Cliente y Producto <h5>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div id="CONTMOD" class="row" class="well">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4"><h4 id="nfMen"></h4></div>
                        <div class="col-sm-6"></div>
                        
                    </div>
                     <div  class="row" class="well">
                        
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-10" > 
                                    <strong>Seleccione Cliente</strong>
                                    <div class="input-group input-group select2-bootstrap-prepend">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                                                <span class="glyphicon glyphicon-search"></span>
                                            </button>
                                        </span>
                                        <select name="cliente" class="form-control select2" id="cliente" >
                                            <option>Cliente</option>  
                                            <?php
                                            while ($row = mssql_fetch_array($cliente)) {
                                                
                                                  /*  echo '<option selected value="'.$row['CardCode'].'"  nomcli="'.utf8_encode($row['CardName']).'" >' . $row['CardCode'] . '-' . utf8_encode($row['CardName']) . '</option>';*/
                                               
                                              if ($row['CardCode'] == $_REQUEST['cliente']) {

                                                    echo '<option selected value="'.$row['CardCode'].'"  nomcli="'.utf8_encode($row['CardName']).'" >' . $row['CardCode'] . '-' . utf8_encode($row['CardName']) . '</option>';
                                                } else {
                                                  echo '<option  value="'.$row['CardCode'].'"  nomcli="'.utf8_encode($row['CardName']).'" >' . $row['CardCode'] . '-' . utf8_encode($row['CardName']) . '</option>';
                                                   $_REQUEST['cliente'] =$row['CardCode'];
                                                }

                                            }
                                        
                                            ?>
                                        </select>
                                    </div>



                            </div>
                            <div class="col-sm-1" ></div>
                    </div>
                    <div  class="row" class="well">
                        
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-10" >
                                <strong>Seleccione  Producto</strong>
                                      <div class="input-group input-group select2-bootstrap-prepend">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </span>
                    <select name="producto" class="form-control select2" id="producto"  >
                        <option>Producto</option>
                        <?php
                       while ($row = mysqli_fetch_assoc($tabla)) {
                            if ($row['cve_prod'] == $_REQUEST['producto']) {

                                echo '<option selected value="' . $row['cve_prod'] .'"  nomprod="'.utf8_encode($row['desc_prod']).'" >'. $row['cve_prod'] . '-' . $row['desc_prod'] . '</option>';
                            } else {
                           
                                echo '<option selected value="' . $row['cve_prod'] .'"  nomprod="'.utf8_encode($row['desc_prod']).'" >' . $row['cve_prod'] . '-' . $row['desc_prod'] . '</option>';
                            }
                        }
                               
                        
                        ?>
                    </select>
                </div>  

                             </div>
                            <div class="col-sm-1" ></div>
                    </div>
                        <br>
                    <div  class="row" class="well">
                        
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-10" >
                                    <div  class="row">
                                            <strong >Cliente:</strong>
                                            <br>
                                            <strong id="cliselectcve">  </strong>
                                            <br>
                                            <strong id="cliselectnom">  </strong>
                                    </div>
                                    <div  class="row">
                                        <strong >Producto:</strong>
                                            <br>
                                            <strong id="prodselectcve">  </strong>
                                            <br>
                                            <strong id="prodselectnom">  </strong>
                                    </div>  
                            </div>
                            <div class="col-sm-1" ></div>
                    </div>



                    <div  class="row" class="well">
                        
                            
                            <div class="col-sm-4" ></div>
                            <div class="col-sm-1" > <button  type="button"  id="btn_put_selecciones" class="btnEstatus  btn btn-info" value="1" data-dismiss="modal" >Agregar</button> </div>
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-1" ><button  type="button"  class="btnEstatus  btn btn-danger" data-dismiss="modal" value="2" >Cancelar</button></div>
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">

            </div>
          </div>
         </div>
     </div>                  
          
    <!--***********************************************-->

    <!--************Dialog ESpera   ********************-->
     <div  class="modal fade" id="SAFEINF" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
                
                <h5>GUARDANDO PROYECCIONES<h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                            <div class="col-sm-12">
                                <div class="col-sm-5"></div>
                                 <div class="col-sm-2"> <img id="ESPERACARGA" src="images/CARGAN_DO_001.gif" alt="Girl in a jacket"></div>
                                 <div class="col-sm-5"></div>
                            </div>      
                </div>
            </div>
            <div class="modal-footer">

            </div>
          </div>
         </div>
     </div>                  
          
    <!--***********************************************-->
<!---Modal  Para  el comentario ----->    
     <div  class="modal fade" id="Modal_ERROR" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
              <button type="button" class="close_coment close " data-dismiss="modal">&times;</button>
              <h4 class="modal-title">ERROR DETECTADO</h4>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                     
                   
                    <div class="row"> 
                        <textarea disabled class="form-control"  type="text"  id="coment_adi"  ></textarea>
                    </div> 
                     
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="close_coment" class="close_coment btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
      </div>
  <!-------------------> 




</div>
<?php   
/********************TEMPORAL_PROYECCIONES*/
   $DETABLE=("DROP TABLE IF EXISTS #TEMPORAL_PROYECCIONES ");
mssql_query($DETABLE);



///****Agregamos  fOOT
require_once('foot.php');
?> 