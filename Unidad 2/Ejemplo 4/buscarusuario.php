<?php
include_once 'usuarios.lib.php';

$usuario='dwes';
$password='dwesDWES*1';

try {

    $pdo=new PDO('mysql:host=localhost;port=3306;dbname=vigilancia', $usuario, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e)
{
    $pdo=false;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar un usuario</title>
</head>
<body>
<?php

    if ($pdo)
    {

        $datosDelUsuario=buscarUsuario($pdo,'jonnieve@blufffff.net');
        if ($datosDelUsuario===false)
        {
            echo 'El usuario no existe en la base de datos.';
        }
        else
        {
            echo 'Los datos del usuario son:';
            echo '<UL>';
            array_walk ($datosDelUsuario, function ($val,$key) 
                {
                    echo "<LI>$key = ";
                    if ($val instanceof DateTime) {
                        echo $val->format('d/m/Y');
                    }
                    elseif ($val===null)
                    {
                        echo "No existe.";
                    }
                    else echo $val;
                    echo "</LI>";
                }
            );
            echo '</UL>';
        }
    }
    else
    {
        echo '<H1>Error al conectar a la base de datos.</H1>';
    }

?>
</body>
</html>