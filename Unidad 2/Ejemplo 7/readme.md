# Ejemplo 7 - Modificar los datos de un usuario (transaccional)

Este ejemplo parte de la base de que ya has entendido el [ejemplo 6](../Ejemplo%206). Repásalo si es necesario.

## Usuarios de prueba

Para facilitar el testeo, se han incluido en el archivo [usuarios.sql](usuarios.sql) algunos usuarios de prueba:

```sql
INSERT INTO usuarios (nombre, apellidos, email, password) VALUES ('test1', 'testap1','email1@test.test',SHA2('testemail1@test.test',256));
INSERT INTO usuarios (nombre, apellidos, email, password) VALUES ('test2', 'testap2','email2@test.test',SHA2('testemail2@test.test',256));
INSERT INTO usuarios (nombre, apellidos, email, password) VALUES ('test3', 'testap3','email3@test.test',SHA2('testemail3@test.test',256));
```

## Función modificarUsuario

En este ejemplo se crea una función que permite modificar uno o más datos de un usuario:

```php
$nuevosDatos=['email'=>'email1email1@test.test', 'nombre'=>'TEST1', 'apellidos'=>'TESTAP1', 'habilitado'=>false];
$resultado=modificarUsuario($pdo, $emailActual,$nuevosDatos);
```
Los datos modificables del usuario son: nombre, apellidos, email y si el usuario está habilitado o no. Pueden indicarse todos los datos o un subconjunto de ellos.

Para lograr esto se ha implementado la función `modificarUsuario` que admite 3 parámetros:

```php
function modificarUsuario (PDO $pdo, string $currentemail, array $newData)
```
A groso modo, la lógica de esta función es la siguiente:

* Se inicia transacción.
* Se comprueba que usuario indicado ($currentemail) existe y se rescatan sus datos. Si no existe, se termina la transacción con `rollback`.
* Si cambia el correo electrónico, se comprueba que no haya ya otro usuario que tenga dicho correo electrónico. Si lo hubiera, habría que cancelar la transacción con un `rollback`.
* Por último, si todo ha ido bien, se actualizan sus datos y se retorna el número de filas modificadas. Después de realizar esta operación, se hace `commit` para finalizar con éxito la transacción.

>_Nota: para retornar saber el número de filas de la base de datos que se han modificado, se usa el méotdo rowCount asociado a cada PDOStatement. Sin embargo, debes tener en cuenta que si los datos son exactamente los mismos, no se realizará la actualización, por lo que este método retornará 0._

