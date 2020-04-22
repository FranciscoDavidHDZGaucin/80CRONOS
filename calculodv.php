
<?
function dias_concorte($f1,$f2) 
{
    $fecha1 = strtotime($f2);
    $fecha2= strtotime($f1);
    /* 

    $resultado=  $f2-$f1;

    $dias = ($resultado/60/60/24);
    //$dias = $resultado;
    $dias=$dias*-1;
    */

    ////No se incluye los domingos
    for($fecha1;$fecha1<=$fecha2;$fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1))){ 
        if((strcmp(date('D',$fecha1),'Sun')!=0)){
            //echo date('Y-m-d D',$fecha1) . '<br />';
            $contador=$contador+1;
        }

    }  
    $dias=$contador-1;

    if ($dias<0){
        $dias=0;
    }
    return $dias;

}


///contar los domingos en un rango de fecha
function ndomingos($f1,$f2) 
{
    $fecha1 = strtotime($f2);
    $fecha2= strtotime($f1);
    
    ////identificar los domingos
    $contador=0;
    for($fecha1;$fecha1<=$fecha2;$fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1))){ 
        if((strcmp(date('D',$fecha1),'Sun')==0)){
            //echo date('Y-m-d D',$fecha1) . '<br />';
            $contador=$contador+1;
        }

    }  
  
    return $contador;

}

///sumar a una fecha n numero de días
function fechaf($fecha,$intervalo){
       $fecha1 = date_create($fecha);
       $comodin="'";
        $incremento=$comodin.$intervalo." days".$comodin;
        date_add($fecha1, date_interval_create_from_date_string($incremento));
       
         
          
        return date_format($fecha1, 'Y-m-d');
       
   }

  
   //identificar fecha mayor   14-05-2016
   
   function mayor($fecha1,$fecha2){
    
    if($fecha1>=$fecha2){
        $valor=$fecha1;
    }else{
        $valor=$fecha2;
    }
    
    return $valor;
}
   
   
   
   
?>