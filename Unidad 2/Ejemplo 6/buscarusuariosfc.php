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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar usuarios</title>
</head>
<body>
<?php
    $inicio=DateTime::createFromFormat('d/m/Y','01/01/2021');
    $fin=DateTime::createFromFormat('d/m/Y','31/12/2030');
    if ($pdo)
    {
        $usuarios=buscarUsuariosFC($pdo,$inicio,$fin);
        $printHeader=true;
        echo '<TABLE border="1">';
        foreach ($usuarios as $usuario)
        {
            if ($printHeader)
            {
                echo '<TR>';
                array_walk($usuario,function ($val,$key){ echo "<th>$key</th>";});
                echo '</TR>';
                $printHeader=false;
            }
            echo '<TR>';
            array_walk($usuario,function ($val,$key){ echo "<td>$val</td>";});
            echo '</TR>';
        }
        echo '</TABLE>';
    }
    else
    {
        echo '<H1>Error al conectar a la base de datos.</H1>';
    }

?>
</body>
</html>