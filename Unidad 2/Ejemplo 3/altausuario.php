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
    <title>Alta usuarios</title>
</head>
<body>
<?php

    $usuario=['nombre'=>'Jon','apellidos'=>'Nieve', 'email'=>'jonnieve@blufffff.net','password'=>'1234'];
    if ($pdo)
    {
        $resultado=crearUsuario($pdo,$usuario);
        if ($resultado===false)
        {
            echo 'El usuario no se pudo crear por un error en la base de datos.';
        }
        elseif ($resultado==-1)
        {
            echo 'El usuario no se pudo crear porque ya existe un email igual.';
        }
        else
        {
            echo "El usuario se ha creado y su id es $resultado";
        }
    }
    else
    {
        echo '<H1>Error al conectar a la base de datos.</H1>';
    }

?>
</body>
</html>