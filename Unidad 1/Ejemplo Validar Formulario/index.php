<?php

define ('PROCFORM_818A','true');
include 'procform.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validación completa de un formulario</title>
</head>
<body>

<?php
//Si no hay errores y se han procesado los datos del formulario
if (empty($formerrors) && !empty($datos))
{
    echo "Los datos del formulario están ok y son los siguientes:";    
    echo '<UL>';
    array_walk ($datos,function ($value,$key) { echo "<LI>$key = $value</LI>";}); 
    echo '</UL>';
}
//Si hay errores
elseif (!empty($formerrors))
{
    echo "Los datos del formulario no están ok.";
    echo '<UL>';
    array_walk ($formerrors,function ($value) { echo '<LI>'.$value.'</LI>';}); 
    echo '</UL>';
}

//Rellenamos los campos del formulario ya existentes

$form=file_get_contents('formulario.html');
foreach($datos as $key=>$dato)
{
    $form=str_replace('[!'.$key.'!]',$dato,$form);    
}
foreach($datos as $key=>$dato)
{
    if ($dato!=='no')
        $form=str_replace('[!*'.$key.'!]','checked',$form);    
}
if (isset($datos['recogida']))
{
    $form=str_replace('[!*recogida '.$datos['recogida'].'!]','selected',$form); 
}

//Limpiamos los marcadores de rellenado.
$form=preg_replace('/\[!.*!\]/','',$form);

echo $form;

?>

</body>
</html>