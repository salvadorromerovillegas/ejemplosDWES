<?php

//Establecemos la palabra por defecto

define('DEFAULT_TXT','Brillante');

// Paso 1: establecemos que "$data" tiene los siguientes valores por defecto
$data = ['i'=>-1,
        'txt'=>DEFAULT_TXT];

/*Paso 2: comprobamos si han sido recibidos los datos de las cookies, si todo es correcto "adoptamos" los datos recibidos
  Se comprueba: 
     - Que esté presente 'vart' en las cookies.
     - Que esté presente 'verf' en las cookies.
     - Que verf sea el sha256 de vart
  */
if (isset($_COOKIE['vart']) && isset($_COOKIE['verf'])
    && hash('sha256',$_COOKIE['vart'])===$_COOKIE['verf'])
{
    //Si la verificación es correcta
    $dataTemp=unserialize($_COOKIE['vart']); //Deserializamos en una variable temporal
    if (isset($dataTemp['i'], $dataTemp['txt']))
    {
        $data = $dataTemp;
    }        
    unset($dataTemp); //Eliminamos variable temporal
}

//Creamos una variable para contener posibles errores
$errors=[];

/*Paso 3: comprobación que los parámetros vía GET o POST se han recibido
  Nota: en la tarea solo se pedía el parámetro vía $_POST
 */
if ( !empty($_GET) && isset($_GET['txt']) && strlen($_GET['txt'])>0
 || !empty($_POST) && isset($_POST['txt']) && strlen($_POST['txt'])>0)
{
    $tGlob=isset($_GET['txt']) ? $_GET['txt'] : $_POST['txt'];  //TEMPORAL VARIABLE
    $tLen=strlen($tGlob); //TEMPORAL VARIABLE
    if ($tLen<4 || $tLen>10) //Comprobar longitud de la cadena (entre 4 y 10)
    {
        $data['txt']=DEFAULT_TXT;        
        $errors[]=$tLen<4 ? 
                    'El texto no es correcto, dado que tiene longitud menor de 4.' :
                    'El texto no es correcto, dado que tiene longitud mayor de 10.';
    }
    else 
    {
        $data['txt']=$tGlob;
    }
    $data['i']=-1; //Establecemos $data['i'] a -1 para comenzar a poner el primer caracter   
    unset($tLen); //UNSET TEMPORAL VARIABLE
    unset($tGlob); //UNSET TEMPORAL VARIABLE
}  


/* Paso 4: calculamos los datos de visualización. */
$pos=$data['i'];
$cellSize=100.0/strlen($data['txt']);

/* Paso 5: actualizamos el valor de $data['i'] para la siguiente posición. */
$data['i']=($data['i']+1)%strlen($data['txt']);


/* Paso 6: añadimos al array con los datos de la cookie un número aleatorio y una marca de 
tiempo para que el SHA256 sea cada vez diferente */

$data['rnd']=random_int(1000000,9999999);
$data['timestamp']=time();

/* Paso 7: serializamos y generamos las cookies */

$tDataSerialized=serialize($data); //TEMPORAL VARIABLE
setcookie('vart',$tDataSerialized);
setcookie('verf',hash('sha256',$tDataSerialized));    
unset($tDataSerialized); //UNSET TEMPORAL VARIABLE

?>
<!DOCTYPE html>
<HTML>
<HEAD>
    <meta http-equiv="refresh" content="4">
<style>
    .container {
        display:table;
        width:100%;
        height:90px;
        padding:0px;
        margin:0px;
    }
    .row {
        display: table-row;
    }
    .cell {        
        display:table-cell;
        text-align:center;
        vertical-align:middle;
        font-size:52px;
        background-color:#ffb300;
        width:<?=$cellSize?>%; 
    }
    .active {
        background-color:#ff8000;
    }
</style>
</HEAD>
<BODY>
<div class="container">
    <div class="row">
<?php /* Paso 8: mostramos la palabra */ ?>
        <?php
        for ($i=0;$i<strlen($data['txt']);$i++) :
        ?>
        <div class="cell <?=$data['i']===$i?'active':''?>"><?=$data['txt'][$i]?></div>
        <?php
        endfor;
        ?>        

    </div>
</div>
<div style="text-align:right;margin-top:20px">        
        <form action="" method="POST">
            <input type="text" name="txt" value="">
            <input type="submit" value="Siguiente">
        </form>
</div>

<?php /* Paso 9: mostramos los errores. */ ?>
<?php if ($errors) :?>

Errores:
<ul>
    <?php foreach ($errors as $error) : ?>
        <li><?=$error?></li>
    <?php endforeach; ?>
</ul>

<?php endif;?>

</BODY>
</HTML>

