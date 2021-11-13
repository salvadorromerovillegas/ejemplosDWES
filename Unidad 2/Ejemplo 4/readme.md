# Ejemplo 4 - SELECT en PDO

Nota: este ejemplo parte de la base de que ya has entendido el [ejemplo 2](../Ejemplo%202). Repásalo si es necesario.

En este ejemplo vamos a buscar registros en la base de datos (`SELECT ....`). En este caso vamos a buscar un único registro, es decir, vamos a realizar una consulta que solo va a retonar un registro.

En la base de datos, el campo email es único, es decir, no puede haber dos usuarios con el mismo email. Es por ello que podemos asegurar que al buscar un usuario con una sentencia como la siguiente:

```sql 
SELECT id, nombre, apellidos, creacion, ultimo_acceso, habilitado from usuarios where email='mail@mail.com`
```
Podemos asegurar que solo se retornará un registro.

La idea en este ejemplo es buscar un usuario por email y rescatar todos los campos, excepto el password. 

Si echas un vistazo al archivo [usuarios.lib.php](usuarios.lib.php) verás que hay un método llamado `buscarUsuario` que implementa justamente eso:

```php
function buscarUsuario ($pdo, $email)
{
    $resultados=false;
    $selectQuery='SELECT id, nombre, apellidos, creacion, ultimo_acceso, habilitado from usuarios where email=:email';
    $pdoStmt=$pdo->prepare($selectQuery);
    $pdoStmt->bindParam('email',$email);
    if ($pdoStmt->execute())
    {
            $resultados=$pdoStmt->fetch(PDO::FETCH_ASSOC);
            if ($resultados)
            {
                $resultados['email']=$email;
                $resultados['creacion']=new DateTime($resultados['creacion']);
                if ($resultados['ultimo_acceso']!=null)
                    $resultados['ultimo_acceso']=new DateTime($resultados['ultimo_acceso']);
            }   
    }   
    return $resultados;
}
```

En el ejemplo anterior se utiliza una consulta preparada muy sencilla. Sin embargo, aquí el detalle en el que tienes que fijarte es en el uso de `fetch`. Ese método permitirá obtener un registro de los obtenidos en la consulta. Recuerda que en este caso se espera un registro o ninguno.

El método `fetch` retorna false si no hay resultados, en consecuencia, el código  `if ($resultados) {...` será false si el usuario no existe. 

Si el método `fetch` retorna algo (porque el registro existe), retornará un array asociativo (`PDO::FETCH_ASSOC`), y en ese caso el `if($resultado) {....} ` se evaluará a `true`.

>_Nota: si ponemos un array asociativo con contenido dentro de un if (p.e.: `if ([1,2,3]) { }`) se evaluará como `true`. Cosas de php._

Por otro lado, ten en cuenta que el método `fetch` permitirá obtener todos los registros rescatados de la base de datos de forma secuencial. La primera invocación retornará el primero, la segunda el segundo... hasta llegar al último. Cuando ya no quedan registros, retornará `false`.

