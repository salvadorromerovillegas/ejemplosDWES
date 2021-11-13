<?php

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
    <title>Ejemplo conexión</title>
</head>
<body>
<?php

    if ($pdo)
    {
        echo '<H1>Se pudo conectar con la base de datos.</H1>';
    }
    else
    {
        echo '<H1>Error al conectar a la base de datos.</H1>';
    }

?>
</body>
</html>