<?php

define('DERECHA',0);
define('IZQUIERDA',1);
define('ABAJO',2);
define('ARRIBA',3);

$card=['ABCDE',
       'EGHIJ',
       'KIMIJ'];

//debe mostrar true
var_dump(checkCardKey($card,'AEK',ABAJO,0));
//debe mostrar true
var_dump(checkCardKey($card,'IGB',ARRIBA,1));
//debe mostrar true
var_dump(checkCardKey($card,'EGHIJ',DERECHA,1));
//debe mostrar true
var_dump(checkCardKey($card,'JIMIK',IZQUIERDA,2));
//debe mostrar false
var_dump(checkCardKey($card,'AHK',ABAJO,0));
//debe mostrar false
var_dump(checkCardKey($card,'IGH',ARRIBA,1));
//debe mostrar false
var_dump(checkCardKey($card,'EHHIJ',DERECHA,1));
//debe mostrar false
var_dump(checkCardKey($card,'JIMHK',IZQUIERDA,2));
//debe mostrar false (no cumple con longitud)
var_dump(checkCardKey($card,'YI',IZQUIERDA,2));
//debe mostrar false (no cumple con longitud)
var_dump(checkCardKey($card,'YIIRJRJR',ARRIBA,2));
//debe mostrar false (fila o columna no válida)
var_dump(checkCardKey($card,'YI',IZQUIERDA,33));
//debe mostrar false (fila o columna no válida)
var_dump(checkCardKey($card,'YIIRJRJR',ARRIBA,54));

function checkCardKey ($tarjeta, $clave, $direccionBusqueda, $filaOColumna)
{
    //Número de columnas  (no es necesario hacerlo así, es por si la matriz es irregular)
    $numCols=min(array_map("strlen",$tarjeta)); //¡¡Cuidado!! Esto se hace así porque es un array de cadenas.
    //Número de filas
    $numFils=count($tarjeta);

    // $columna y $fila representan desde donde se empieza a buscar
    // $next contendrá la función a aplicar para calcular la siguiente posición
    switch ($direccionBusqueda)
    {
        case DERECHA: case 'R':
                $fila=$filaOColumna;
                $columna=0;
                $next=function (&$fila,&$columna) {$columna++;};            
            break;
        case IZQUIERDA: case 'L':
                $fila=$filaOColumna;
                $columna=$numCols-1;                
                $next=function (&$fila,&$columna) {$columna--;};            
            break;
        case ARRIBA: case 'U':
                $columna=$filaOColumna;
                $fila=$numFils-1;                
                $next=function (&$fila,&$columna) {$fila--; };            
           break;
        case ABAJO: case 'D':
                $columna=$filaOColumna;
                $fila=0;                
                $next=function (&$fila,&$columna) {$fila++;};            
           break;
        default:
            //Si la dirección no es una especificada, entonces se retorna false
            return false;
            break;
    }
    
    //Comprobamos que la longitud de la clave corresponda con el número de columnas o filas
    if(($direccionBusqueda===DERECHA || $direccionBusqueda===IZQUIERDA) && strlen($clave)!=$numCols)
        return false;
    if(($direccionBusqueda===ARRIBA || $direccionBusqueda===ABAJO) && strlen($clave)!=$numFils)
        return false;
    

    $CLAVEREAL=''; //contendrá la CLAVE BUENA que hay en la dirección indicada
    //En el siguiente bucle extraemos cual es la CLAVE BUENA 
    while ($columna>=0 && $columna<$numCols 
            && $fila>=0 && $fila<$numFils)
        {
            $stringFila=$tarjeta[$fila];//Extraemos el string de la fila
            $CLAVEREAL.=$stringFila[$columna]; //Extraemos el carácter en la posición $columna y lo concatenamos en $CLAVEREAL
            $next($fila,$columna); //Calculamos la siguiente posición
        }        
    return $CLAVEREAL===$clave; //Si la clave real es igual a la clave de usuario entonces se debe ser true 
    
}

?>
