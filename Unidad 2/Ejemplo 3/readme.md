# Ejemplo 3 - Sentencia INSERT en PDO (versión bindParam)

Este ejemplo parte de la base de que ya has entendido el [ejemplo 2](../Ejemplo%202). Repásalo si es necesario.

En este ejemplo, se ha modificado la función `crearUsuario` del archivo [usuarios.lib.php](usuarios.lib.php) en vez de pasar los parámetros de la consulta preparada en el método `execute`, vamos a hacer uso del método `bindParam`. Veamos como cambia el código:

```php
        $nombre=$userData['nombre'];
        $pdoStmt->bindParam('nombre',$nombre);
        
        $apellidos=$userData['apellidos'];
        $pdoStmt->bindParam('apellidos',$apellidos);
        
        $email=$userData['email'];
        $pdoStmt->bindParam('email',$email);
        
        $comb='';
        $pdoStmt->bindParam('comb',$comb);
        $comb=$userData['password'].$userData['email'];

        try {
            if ($pdoStmt->execute())
            {
                $ret=$pdo->lastInsertId();
            }
        }
```

Lo primero que tienes que tener en cuenta es que `bindParam` siempre necesita una variable como segundo argumento, porque lo que va a hacer es enlazar la variable con el parámetro.

```php
$email=$userData['email'];
$pdoStmt->bindParam('email',$email);
```
Al hacer `execute` se accede al valor almacenado en dicha variable y se reemplaza en la consulta. 

De hecho, si se modifica el valor de la variable después de ejecutar `bindParam` pero antes de ejecutar `execute`, recogera el valor de dicha variable igualmente. Es el caso de la variable `$comb`:

```php
$comb='';
$pdoStmt->bindParam('comb',$comb);
$comb=$userData['password'].$userData['email'];
```
Fíjate que su valor se asigna después de hacer el `bindParam` (pero antes de `execute`).



