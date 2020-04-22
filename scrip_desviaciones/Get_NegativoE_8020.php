<?php
///*****Get_NegativoE_8020
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : Get_NegElem_8020.php 
 	Fecha  Creacion :24/05/2017  
	Descripcion  : 
 *          Escrip  Encargado  de  Obtener   los  Porcentajes   y ademas  de  Ordenar    todos los elemetos  NEGATIVOS  
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
$ToltNeg = filter_input(INPUT_POST, 'ToltNeg');
$Are_VarPu_NE = json_decode(filter_input(INPUT_POST, 'Are_VarPu_NE'));
///***Agregar Elementos Negativos
$Are_VarPu_NEWhitPor =array();
////***Ciclo paras   Obtener Porcentajes para el  80/20 
foreach ($Are_VarPu_NE  as $ELEM)
{
     $ELEM['Porcent'] = round(($ELEM['VarPu']/$ToltNeg)*100);
     array_push($Are_VarPu_NEWhitPor, $ELEM);
   
     
}
///**Ordenamos  los Elementos Negativos 
$Are_VarPu_NEORDER = simple_quick_sort($Are_VarPu_NEWhitPor);
///****Arreglo Solo 80 Negativos
 $Are_only80Ne = array();
///******Obtenemos  los  Elementos  que sumados  Obtengan el  80 % Negativos
foreach($Are_VarPu_NEORDER  as  $ELEM)
{   
   /// IF($ELEM->{'Porcent'} >0.10){
        $SUM80 += $ELEM->{'Porcent'} ;
        
         IF($SUM80 <= 80)
            {
              array_push($Are_only80Ne, $ELEM);
            }
        
  ///   }
} 
///*********************************************************************
$AREGLO_WHIT_ALL =  ARRAY ( "ElNeg" =>json_encode($Are_only80Ne) );
///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
echo json_encode($AREGLO_WHIT_ALL);
///echo json_encode($sorted); ///implode(",",$sorted)." @sorted<br>";

?>