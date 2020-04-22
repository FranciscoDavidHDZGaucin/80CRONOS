<?PHP 


class _ord {

    public  $Main_File  = NULL ;
    public  $_PESO = NULL;  
    public  $_Nombre_temporal = null ;  
    public  $temporal = null ; 
    public  $TIPOARCHIVO = "TIPOARCHIVO" ; 
    public  $est_archivo =1 ; 
    public  $exception_objt  ="HOLA ESTO ES UN MENSAJE DE ERROR  :T " ;  
    public  $new_ruta =""; 
    public  $ESTATUS_ARCHIVO= FALSE ; /* eSTADO fINAL  SE CARGO EL ARCHIVO  */

    /**Bariables de control   */
    public  $mens_archivo ="HOLA DESDE EL MENSAJE DE ARCHIVO";
    
    public  function  GET_TIPOARCHIVO()
        { return  $this->TIPOARCHIVO ; }
    public  function  GET_ESTATUS_ARCHIVO()
    { return  $this->est_archivo; }
    public  function  GET_ERROR_OBJ()
    { return $this->exception_objt ; }

    public  function  GET_MENSAJE_ARCHIVO()
    { return $this->mens_archivo ; }

    public  function  GET_NOMBRE_TEMPORAL()
    {  return  $this->_Nombre_temporal;}
    public  function  GET_NEW_DIRECCION()
    { return  $this->new_ruta ; }
    public  function  RESULTADO_ORDC()
    {
        return $this->ESTATUS_ARCHIVO;
    } 
    public  function JSON_OBJCLASS()
    {       $JSON_OBJET = array (
                        "Main_File"=>$this->Main_File,
                        "_PESO"=>$this->_PESO, 
                        "_Nombre_temporal"=>$this->_Nombre_temporal, 
                        "temporal"=>$this->temporal,
                        "TIPOARCHIVO"=>$this->TIPOARCHIVO , 
                        "est_archivo"=>$this->est_archivo, 
                        "exception_obj"=>$this->exception_obj,
                        "new_ruta"=>$this->new_ruta, 
                        "ESTATUS_ARCHIVO"=>$this->ESTATUS_ARCHIVO  );
            return json_encode($JSON_OBJET);



    }


}

  //function  CARGAR_ARCHIVO(  $_FILES,$FOLIO,$CONECMYSQL1){
    function  CARGAR_ARCHIVO($FOLIO,$CONECMYSQL1){
 
    $POB_ORD = new _ord();
    try {
         
        If($_FILES['archORD']['tmp_name']==UPLOAD_ERR_OK)
        {   
            
            //////////////////////ARCHIVO/////////////////////////////////  
              /// $ext2=  tipo2(basename($_FILES['archORD']['name']));
               $POB_ORD->_PESO = $_FILES['archORD']['size'];
               $POB_ORD->_Nombre_temporal=$_FILES['archORD']['tmp_name'];
               ///////
              $POB_ORD->temporal = $_FILES['archORD']['tmp_name'];
              $POB_ORD->TIPOARCHIVO =  $_FILES['archORD']['type'];
   
              $TIO_ARCHIVO  =  $POB_ORD->GET_TIPOARCHIVO();
               ////****
               $New_ruta_and_vali =  Get_New_Ruta_Archivo( $TIO_ARCHIVO,$FOLIO) ;
               $POB_ORD->new_ruta = $New_ruta_and_vali['Root'];
               ///Validamos  el Archivo
              if(strpos($POB_ORD->TIPOARCHIVO, "doc")||strpos($POB_ORD->TIPOARCHIVO, "docx")||strpos($POB_ORD->TIPOARCHIVO, "xls")||strpos($POB_ORD->TIPOARCHIVO, "csv")||strpos($POB_ORD->TIPOARCHIVO, "xlsx")||strpos($POB_ORD->TIPOARCHIVO, "ppt")
                  ||strpos($POB_ORD->TIPOARCHIVO, "pptx")|| strpos($POB_ORD->TIPOARCHIVO, "pdf")||strpos($POB_ORD->TIPOARCHIVO, "jpg")||strpos($POB_ORD->TIPOARCHIVO, "png")||strpos($POB_ORD->TIPOARCHIVO, "jpeg")||strpos($POB_ORD->TIPOARCHIVO, "jpe")
                       ||$New_ruta_and_vali['Office_elem']==1)
               {        
                  move_uploaded_file($POB_ORD->temporal,$New_ruta_and_vali['Root'] );   
               }
               else
               {
                   $POB_ORD->est_archivo= 0 ;
                  $POB_ORD->mens_archivo= "Error: El  tipo de Archivo No es valido"; 
                  
               }
            
        
        }
        if ($_FILES['archORD']['error']=='' && $POB_ORD->est_archivo=1  ) //Si no existio ningun error, retornamos un mensaje por cada archivo subido
        {   
            $POB_ORD->mens_archivo="archivo Agregado con Exito";
                        
        }
        if ($_FILES['archORD']['error']!=''||$POB_ORD->est_archivo= 0)//Si existio algún error retornamos un el error por cada archivo.
        {
            $POB_ORD->mens_archivo="Error lo sentimos existen problemas con el archivo";
        }
        ////***** Validamos 
        if($POB_ORD->est_archivo=1  && $_FILES['archORD']['tmp_name']==UPLOAD_ERR_OK )
        {
               


            $POB_ORD->mens_archivo="TODO BIEN PERRO :D";
            $POB_ORD->ESTATUS_ARCHIVO = TRUE; 
         ////----- 
        }    
           




    } catch (Exception  $th) {
        $POB_ORD->exception_objt = $th ;  
    }

   

    return $POB_ORD;



}

////****Funcion para  Generar la  Ruta del Archivo.
function    Get_New_Ruta_Archivo ($tipo_archivo,$FOLIO)
{
        $NombreOr ="";
        $ruta = 'ORDC_PEDIDOS/';
        $nom_temporal =  time(); 
        /* Entiendase  como 
         *      $RESUL_FUNCTION['Office_elem']=0 El elemento no es  un Formato  Office 
                $RESUL_FUNCTION['Office_elem']=1; El elemento es  un Formato  Office
         *          */
        $RESUL_FUNCTION['Office_elem']=0;   
       if(strpos($tipo_archivo, "pdf"))
       {
            $NombreOr ="REM_ORDC".$FOLIO.'.pdf'; ///$_FILES['ARCH']['name'];
       }else{
             
                if(strpos($tipo_archivo, "jpg"))
                 {
                      $NombreOr ="REM_ORDC".$FOLIO.'.jpg';
                 }else{
                     if(strpos($tipo_archivo, "jpeg"))
                     {
                         $NombreOr ="REM_ORDC".$FOLIO.'.jpg';
                     }
                     else {
                             if(strpos($tipo_archivo,"jpe"))
                             {
                                 $NombreOr ="REM_ORDC".$FOLIO.'.jpg';
                             }
                             else {
                                     if(strpos($tipo_archivo, "jfif"))
                                     {
                                         $NombreOr ="REM_ORDC".$FOLIO.'.jpg';
                                     }
                                     else
                                     {
                                         if(strpos($tipo_archivo, "png")||strpos($tipo_archivo, "PNG"))
                                         {
                                            $NombreOr ="REM_ORDC".$FOLIO.'.png';
                                         }else 
                                         {   
                                             ////*****Inicio  Validacion  Documentos office
                                                ///**  Validacion Documentos Word
                                              if(strpos($tipo_archivo, "dot")||strpos($tipo_archivo, "doc")||strpos($tipo_archivo, "docx")||strpos($tipo_archivo, "dotm")||strpos($tipo_archivo, "docm")||strpos($tipo_archivo, "dotx")||strpos($tipo_archivo, "dotm"))
                                                {
                                                   $NombreOr ="REM_ORDC".$FOLIO.'.docx';
                                                   $RESUL_FUNCTION['Office_elem']=1;
                                                }else {
                                                    ///****Validacion  Documento  Excel
                                                    //   if(strpos($tipo_archivo, "xlsx")||strpos($tipo_archivo, "xlsm")||strpos($tipo_archivo, "xltx")||strpos($tipo_archivo, "xltm")||strpos($tipo_archivo, "xlam")||strpos($tipo_archivo, "xls")||strpos($tipo_archivo, "csv"))
                                                    //   {
                                                    //         $NombreOr ="REM_ORDC".$FOLIO.'.xlsx';
                                                    //         $RESUL_FUNCTION['Office_elem']=1;
                                                    //   }else {
                                                    // ///****Validacion  Documento  Power Point 
                                                    //         if(strpos($tipo_archivo, "ppa")||strpos($tipo_archivo, "pot")||strpos($tipo_archivo, "pps")||
                                                    //                 strpos($tipo_archivo, "xml")||strpos($tipo_archivo, "pptm")||strpos($tipo_archivo, "potx")||
                                                    //                 strpos($tipo_archivo, "xlam")||strpos($tipo_archivo, "potm")||strpos($tipo_archivo, "ppam")||
                                                    //                 strpos($tipo_archivo, "ppsx")||strpos($tipo_archivo, "ppsm")||strpos($tipo_archivo, "sldx")||
                                                    //                 strpos($tipo_archivo, "sldm")||strpos($tipo_archivo, "thmx"))
                                                    //         {
                                                    //          $NombreOr ="REM_ORDC".$FOLIO.'.'.$tipo_archivo;
                                                    //          $RESUL_FUNCTION['Office_elem']=1;
                                                    //         }
                                                    //   }
                                                    switch ($tipo_archivo) {
                                                        case "application/vnd.ms-excel":
                                                            $NombreOr ="REM_ORDC".$FOLIO.'.xlsx';
                                                            $RESUL_FUNCTION['Office_elem']=1;
                                                            break;
                                                        case "text/xls":
                                                            $NombreOr ="REM_ORDC".$FOLIO.'.xls';
                                                            $RESUL_FUNCTION['Office_elem']=1;
                                                            break;
                                                        case "text/xlsx":
                                                            $NombreOr ="REM_ORDC".$FOLIO.'.xlsx';
                                                            $RESUL_FUNCTION['Office_elem']=1;
                                                            break;
                                                        case "text/csv":
                                                            $NombreOr ="REM_ORDC".$FOLIO.'.csv';
                                                            $RESUL_FUNCTION['Office_elem']=1;
                                                            break;
                                                        case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
                                                            $NombreOr ="REM_ORDC".$FOLIO.'.xls';
                                                            $RESUL_FUNCTION['Office_elem']=1;
                                                            break;
                                                        // case "xls":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.xls';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //     break;
                                                        // case "text/csv":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.csv';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //     break;
                                                        // case "ppa":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.ppa';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //     break;
                                                        // case "pot":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.pot';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //     break;
                                                        // case "pps":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.pps';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //     break;
                                                        // case "xml":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.xml';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //     break;
                                                        // case "pptm":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.pptm';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //     break;
                                                        // case "potx":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.potx';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //     break;
                                                        // case "xlam":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.xlam';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //     break;
                                                        // case "potm":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.potm';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //     break;
                                                        // case "ppam":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.ppam';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //     break;
                                                        // case "ppsx":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.ppsx';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //     break;
                                                        // case "ppsm":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.ppsm';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //     break;    
                                                        // case "ppam":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.ppam';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //      break;
                                                        // case "ppsx":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.ppsx';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //     break;
                                                        // case "ppsm":
                                                        //     $NombreOr ="REM_ORDC".$FOLIO.'.ppsm';
                                                        //     $RESUL_FUNCTION['Office_elem']=1;
                                                        //     break;      
                                                        default:
                                                           echo "El formato del archivo no es válido";
                                                    }
                                                }
                                             //////*****Fin  Validacion Documento  Office
                                         }
                                     }  
                             } 
                     }   

                 } 
             
         }
        $RESUL_FUNCTION['Root']= $ruta.$NombreOr;
        $Destino  = $RESUL_FUNCTION;
    
   return   $Destino;
}
///*******FUNCION PARA REVISAR SI EXISTE DOCUMENTO CON LA  REMISION *************************************************
function  Existe_ORDC($FOLIO,$CONECMYSQL){

    $infoArray = [];

    $arrRESULTADO = array( "numcol"=>0 ,"numdocto"=>"","consulta"=>"" ) ; 

    $STRG_SELE_FILE  = sprintf("SELECT ID, REMISION,DOCUMENTO FROM pedidos.TB_LOG_CARGA_ORDC_PEDIDOS where REMISION =%s",
        GetSQLValueString($FOLIO, "int")
    );
    $SERESULT =mysqli_query($CONECMYSQL,$STRG_SELE_FILE) ;
              
    $número_filas = mysql_num_rows($SERESULT);

    /*if ($número_filas == 1  )
    {*/
         while ($ftchdocto = mysqli_fetch_array($SERESULT) )
         {
            $arrRESULTADO["numcol"] = $ftchdocto["ID"];
            $arrRESULTADO["numdocto"] =$ftchdocto["DOCUMENTO"] ;
            $arrRESULTADO["consulta"] =$STRG_SELE_FILE ;

            array_push($infoArray, $arrRESULTADO);


         }
        
    //}
    return $infoArray; 
}
?>