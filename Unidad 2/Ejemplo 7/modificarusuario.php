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

    if ($pdo)
    {
        $emailActual='email1@test.test';
        $nuevosDatos=['email'=>'email1email1@test.test', 'nombre'=>'TEST1', 'apellidos'=>'TESTAP1', 'habilitado'=>false];
        $resultado=modificarUsuario($pdo, $emailActual,$nuevosDatos);
        if ($resultado>0)
        {
            echo '<h1>El usuario se modifico adecuadamente.</h1>';
        }
        elseif ($resultado===0)
        {
            echo '<H1>No hay variación en los datos, no se ha modificado.</H1>';
        }
        else
        {
            echo '<h1>El usuario no se pudo modificar.</h1>';
        }
    }
    else
    {
        echo '<H1>Error al conectar a la base de datos.</H1>';
    }

?>
</body>
</html>