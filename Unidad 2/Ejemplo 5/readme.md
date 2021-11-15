# Ejemplo 5 - Insertar un registro versión transaccional

Este ejemplo parte de la base de que ya has entendido el [ejemplo 3](../Ejemplo%203). Repásalo si es necesario.

En este ejemplo se ha modificado la función `crearUsuario` del archivo [usuarios.lib.php](usuarios.lib.php). En esta ocasión, se ha modificado el código para comprobar si existe ya un usuario que ya tenga el email proporcionado antes de proceder a insertar el registro:

```php
    $selectQuery='SELECT count(*) FROM usuarios WHERE email=:email';
        
    $pdoStmt1=$pdo->prepare($selectQuery);
        
    $pdoStmt1->bindValue('email',$userData['email']);

    if ($pdoStmt1->execute() && $pdoStmt1->fetchColumn()==0) //Verificamos que el usuario no exista.
    {
        ///Realizar la inserción
    } 
    else
    {
        ///No se puede realizar la inserción, dado que el registro existe
    }       
```
Esta es una solución más robusta la verdad. Sin embargo, para que esta solución sea plenamente robusta, debemos hacerla como una transacción. En una aplicación web pueden producirse cientos de consultas por minuto a una base de datos, y podría ocurrir que entre la consulta `SELECT` anterior y la inserción se insertara un registro con el email indicado y se produjera un error en nuestra aplicación. 

Para solucionarlo se utilizan transacciones:

```php

    $pdo->beginTransaction(); //Comienzo de la transaccción

    $selectQuery='SELECT count(*) FROM usuarios WHERE email=:email';
        
    $pdoStmt1=$pdo->prepare($selectQuery);
        
    $pdoStmt1->bindValue('email',$userData['email']);

    if ($pdoStmt1->execute() && $pdoStmt1->fetchColumn()==0) //Verificamos que el usuario no exista.
    {
        ///Realizar la inserción
        ...
        $pdo->commit(); //Si la inserción se hizo con éxito hacemos commit para confirmar los cambios
    } 
    else
    {
        ///No se puede realizar la inserción, dado que el registro existe
        $pdo->rollback(); //Se cancela la transacción retornando a un estado consistente de la base de datos.
    }       
```
Este es un ejemplo muy sencillo de transacciones. Hay ejemplos donde la necesidad de usar transacciones se hace más patente.

> _Nota: fíjate que en este ejemplo se hace uso de `bindValue(...)`. `bindValue` es muy diferente `bindParam`, dado que `bindValue` no vincula la variable al valor del parámetro de la consulta SQL, sino que solo establece el valor a usar como parámetro en la consulta._