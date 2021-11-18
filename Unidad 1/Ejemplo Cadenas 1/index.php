<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejemplo Cadenas 1</title>
</head>
<body>
    Reemplazador de emoticonos:
    <ul>
        <li> :-) se reemplazará por &#x1F600; </li>
        <li> ;-) se reemplazará por &#x1F609; </li>
        <li> (-: se reemplazará por &#x1F643; </li>
        <li> :-( se reemplazará por &#x1F641; </li>
        <li> :-o se reemplazará por &#x1F62E; </li>
    </ul>
    <form action="" method="post">
        Introduce el texto:<br>
        <textarea name="texto" id="" cols="30" rows="10"></textarea><br>
        <input type="submit" value="Enviar">
    </form>
    <?php
    $subs=[ ':-)'=>'&#x1F600;',
            ';-)'=>'&#x1F609;',
            '(-:'=>'&#x1F643;',
            ':-('=>'&#x1F641;',
            ':-o'=>'&#x1F62E;'];
    if (isset($_POST['texto']))
    {
        $texto=$_POST['texto'];
        foreach($subs as $key=>$val)
        {
            $texto=str_replace($key,$val,$texto);
        }   
        echo $texto;
    }
    ?>
</body>
</html>