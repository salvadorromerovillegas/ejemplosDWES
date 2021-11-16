<?php


/**
 * Función que recibe como un argumento una cadena y crea una lista partiendo de ella.
 * La cadena se parte usando el separador "|" y las diferentes partes se muestran como
 * una lista.
 * @param string $cadena Cadena donde se usa el delimitador '|' para cada parte.
 */
function generarListaDesdeCadena(string $cadena)
{
    $result='';
    $partes=explode('|',$cadena);
    if (count($partes)>0)
    {
        $result.='<UL>';
        foreach($partes as $parte)
        {
            $result.=sprintf('<LI>%s</LI>',trim($parte));
        }
        $result.='</UL>';
    }
    return $result;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea una lista</title>
</head>
<body>
    <?php
        if (isset($_POST['lista']))
        {
            
            $lista=htmlspecialchars(trim($_POST['lista']));
            if (strlen($lista)>0)
            {
                echo '<H1>Lista generada:</H1>';
                echo generarListaDesdeCadena($lista);
            }
            else
            {
                echo "ERROR: Debe indicar alguna cadena.<bR>";
            }
        }         
    ?>
    <form action="" method="post">
        <label for='lista'>
        Introduce la cadena a convertir en lista (separador '|'):<br>
        <textarea name="lista" id="lista" cols="30" rows="10"><?php if (isset($lista)) echo $lista; ?></textarea>
        </label>
        <br>
        <input type="submit" value="Enviar!">
    </form>
</body>
</html>