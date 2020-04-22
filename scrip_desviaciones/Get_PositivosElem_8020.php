<?php
 ///****Get_PositivosElem_8020.php  
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : Get_PositivosElem_8020.php  
 	Fecha  Creacion :24/05/2017  
	Descripcion  : 
 *          Escrip  Encargado  de  Obtener   los  Porcentajes   y ademas  de  Ordenar    todos los elemetos  Positivos 
 *          Retorna Los elementos ha Evaluar del Resultado  80-20  
  */
///***Funcion de Ordenamiento  Quick Sort  Para Ordenar elementos 
function simple_quick_sort($arr)
{
    if(count($arr) <= 1){
        return $arr;
    }
    else{
        
        $Obje = $arr[0];
        $pivot =$Obje->{'Porcent'}; /// $arr[0];
        $left = array();
        $right = array();
        for($i = 1; $i < count($arr); $i++)
        {   
            $objI = $arr[$i];
            if($objI->{'Porcent'} > $pivot){
                $left[] = $arr[$i];
            }
            else{
                $right[] = $objI;///$arr[$i];
            }
        }
        return array_merge(simple_quick_sort($left), array($Obje), simple_quick_sort($right));
    }
}
///**Obtenemos  la  Informacion  
///****Total de  Venta  Elementos Positivos 
$ToltPos = filter_input(INPUT_POST, 'ToltPos');
///****Areglo con Elementos  Postivos
$Are_VarPu_PO= json_decode(filter_input(INPUT_POST, 'Are_VarPu_PO'));

///***Agregar Elementos Positivos
$Are_VarPu_POSWhitPor =array();
//***Calculaos  el Porcetage de cada  Uno de los elementos
foreach ($Are_VarPu_PO  as $ELEM)
{
     $ELEM->{'Porcent'} = round(($ELEM->{'VarPu'}/$ToltPos)*100);
     array_push($Are_VarPu_POSWhitPor, $ELEM);
   // echo  $ELEM['Porcent']."<br>"; 
}
///**Ordenamos  los Elementos Positivos
$Are_VarPu_POORDER = simple_quick_sort($Are_VarPu_POSWhitPor);
///****Arreglo Solo 80% Posit
 $Are_only80PO = array();
///******Obtenemos  los  Elementos  que sumados  Obtengan el  80 % Positivos
foreach($Are_VarPu_POORDER  as  $ELEM)
{   
   $SUM80POS += $ELEM->{'Porcent'} ;
   
    IF($SUM80POS <= 80)
    {
      array_push($Are_only80PO, $ELEM);
    }
                 
}
///***Retornamos  el  Resultado
$AREGLO_WHIT_ALL =  ARRAY ( "ElPos" => json_encode($Are_only80PO)  );
///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
echo json_encode($AREGLO_WHIT_ALL);