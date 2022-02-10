<?php

session_start();

define ('TAMLIN',3);
define ('FILAS',10);
define ('COLUMNAS',10);

if (!isset($_SESSION['m']))
{

    $_SESSION['m']=array_fill(0,FILAS,array_fill(0,COLUMNAS,0));
}
else
{
    $f=filter_input(INPUT_GET,'f',FILTER_SANITIZE_NUMBER_INT);
    $c=filter_input(INPUT_GET,'c',FILTER_SANITIZE_NUMBER_INT);
    if ($f && $c && $f>0 && $f<=count($_SESSION['m']) 
        && $c>0 && $c<=count($_SESSION['m'][0]))
    {
        $_SESSION['m'][$f-1][$c-1]=1;
        $enRaya=false;
        for ($ff=0;$ff<TAMLIN && !$enRaya;$ff++)
        {            
            for ($cc=0;$cc<TAMLIN && !$enRaya;$cc++)
            {
                $enRaya=r($_SESSION['m'],$f-$ff,$c-$cc);
            }
        }
        if ($enRaya)
        {
            echo TAMLIN." EN RAYA ENCONTRADO. COMENZAMOS DE NUEVO.";
            $_SESSION['m']=array_fill(0,FILAS,array_fill(0,COLUMNAS,0));
        }
    }
}

function r($array, $f, $c)
{  
    $filas=count($array);
    $columnas=count($array[0]);    
    if ($f<0 || $c<0 || $f>=$filas || $c>=$columnas)
        return false;
    //Busqueda dirección derecha
    $recuento=0;
    for ($i=0;$i<TAMLIN;$i++)
    {
        $nuevaFila=$f;
        $nuevaColumna=$c+$i;
        if ($nuevaFila>=0 && $nuevaColumna>=0 &&
            $nuevaFila<$filas && $nuevaColumna<$columnas &&
            $array[$nuevaFila][$nuevaColumna]===1)
        {
            $recuento++;            
        }
    }
    if ($recuento===TAMLIN) return true;
    
    
    //Busqueda dirección abajo derecha
    $recuento=0;
    for ($i=0;$i<TAMLIN;$i++)
    {
        $nuevaFila=$f+$i;
        $nuevaColumna=$c+$i;
        if ($nuevaFila>=0 && $nuevaColumna>=0 &&
            $nuevaFila<$filas && $nuevaColumna<$columnas &&
            $array[$nuevaFila][$nuevaColumna]===1)
        {
            $recuento++;            
        }
    }
    if ($recuento===TAMLIN) return true;
    
    //Busqueda dirección abajo 
    $recuento=0;
    for ($i=0;$i<TAMLIN;$i++)
    {
        $nuevaFila=$f+$i;
        $nuevaColumna=$c;
        if ($nuevaFila>=0 && $nuevaColumna>=0 &&
            $nuevaFila<$filas && $nuevaColumna<$columnas &&
            $array[$nuevaFila][$nuevaColumna]===1)
        {
            $recuento++;            
        }
    }
    if ($recuento===TAMLIN) return true;
    
    //Busqueda dirección abajo izquierda
    $recuento=0;
    for ($i=0;$i<TAMLIN;$i++)
    {
        $nuevaFila=$f+$i;
        $nuevaColumna=$c-$i;
        if ($nuevaFila>=0 && $nuevaColumna>=0 &&
            $nuevaFila<$filas && $nuevaColumna<$columnas &&
            $array[$nuevaFila][$nuevaColumna]===1)
        {
            $recuento++;            
        }
    }
    if ($recuento===TAMLIN) return true;
    
    //Busqueda dirección izquierda
    $recuento=0;
    for ($i=0;$i<TAMLIN;$i++)
    {
        $nuevaFila=$f;
        $nuevaColumna=$c-$i;
        if ($nuevaFila>=0 && $nuevaColumna>=0 &&
            $nuevaFila<$filas && $nuevaColumna<$columnas &&
            $array[$nuevaFila][$nuevaColumna]===1)
        {
            $recuento++;            
        }
    }
    if ($recuento===TAMLIN) return true;
    
    //Busqueda arriba izquierda
    $recuento=0;
    for ($i=0;$i<TAMLIN;$i++)
    {
        $nuevaFila=$f-$i;
        $nuevaColumna=$c-$i;
        if ($nuevaFila>=0 && $nuevaColumna>=0 &&
            $nuevaFila<$filas && $nuevaColumna<$columnas &&
            $array[$nuevaFila][$nuevaColumna]===1)
        {
            $recuento++;            
        }
    }
    if ($recuento===TAMLIN) return true;
    
    //Busqueda arriba
    $recuento=0;
    for ($i=0;$i<TAMLIN;$i++)
    {
        $nuevaFila=$f-$i;
        $nuevaColumna=$c;
        if ($nuevaFila>=0 && $nuevaColumna>=0 &&
            $nuevaFila<$filas && $nuevaColumna<$columnas &&
            $array[$nuevaFila][$nuevaColumna]===1)
        {
            $recuento++;            
        }
    }
    if ($recuento===TAMLIN) return true;

    //Busqueda arriba derecha
    $recuento=0;
    for ($i=0;$i<TAMLIN;$i++)
    {
        $nuevaFila=$f-$i;
        $nuevaColumna=$c+$i;
        if ($nuevaFila>=0 && $nuevaColumna>=0 &&
            $nuevaFila<$filas && $nuevaColumna<$columnas &&
            $array[$nuevaFila][$nuevaColumna]===1)
        {
            $recuento++;            
        }
    }
    if ($recuento===TAMLIN) return true;
    
    return false;
}

?>
<style>
    .r {
        width: 50px;
        height: 50px;        
    }
    .blue {
        background: lightblue;
    }
    .red {
        background: tomato;
    }
</style>
<?php
echo '<TABLE>';
for ($f=0;$f<count($_SESSION['m']);$f++)
{
    echo '<TR>';
    for ($c=0;$c<count($_SESSION['m'][$f]);$c++)
    {
        
        $g=$_SESSION['m'][$f][$c]===0?'blue':'red';        
        $f1=$f+1;
        $c1=$c+1;
        echo "<TD class='$g'><a href='ejercicio1.php?f=$f1&c=$c1'><div class='r'></div></a></TD>";
    }
    echo '</TR>';
}
echo '</TABLE>';

?>