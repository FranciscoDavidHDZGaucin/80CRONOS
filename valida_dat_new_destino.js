/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :valida_dat_new_destino.js  
 	Fecha  Creacion : 16/08/2016 
	Descripcion  : 
	Archivo   hecho  para contener  funciones de  validacion 
        
	Modificado  Fecha  : 
*/
$(document).ready(function (){
//***Inicio Document.ready 
  ///**Btn  Enviar   
   $("#fornewdes").submit(function (event) {
        
      var _calle =  $("#calle").val(); 
      var _colonia  =$("#colonia").val();
      var _ciudad =$("#ciudad").val();
      var _cp = $("#cp").val();
      var _estado = $("#estado").val();
      var _pais = $("#pais").val();
      
      ///***************************************
      var   result =  true ; 
      if (_calle == "")
      {
         $("#_divcalle").addClass("form-group has-error") ;
         result=  false ;
      }
      if (_colonia == "")
      {
          $("#_divcolonia").addClass("form-group has-error");
         result=  false ; 
      }
      if (_ciudad == "")
      {
          $("#_divciudad").addClass("form-group  has-error");
         result=  false ; 
      }
      if (_cp == "")
      {
          $("#_divcp").addClass("form-group  has-error");
         result=  false ; 
      }      
      if (_estado == "")
      {
          result=  false ; 
      }
      if (_pais == "")
      { 
         $("#_divpais").addClass("form-group  has-error");
         result=  false ; 
      }      
   ///***********************************************************
    if (result)
    {
      
    }else{
        /*$( "span" ).text( "Not valid!" ).show().fadeOut( 1000 );
  event.preventDefault();
        */
    }
     
   });
//**Fin Document.ready    
});