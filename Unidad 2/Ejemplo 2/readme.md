# Ejemplo 2 - Sentencia INSERT en PDO

Vamos a ver un pequeño ejemplo de como realizar inserciones usando PDO, conectando a una base de datos MySQL.

## Tabla en MySQL

En este ejemplo vamos a trabajar con la siguiente tabla:

```sql
CREATE TABLE usuarios (
	id bigint NOT NULL auto_increment, 
	nombre VARCHAR(35) not null,
	apellidos VARCHAR(35) not null,
	email VARCHAR(254) not null, /*email de usuario*/
	password VARCHAR(64) not null, /*hash password 64 bytes --> 512 bits (máximo de SHA2) */
	creacion TIMESTAMP not null default now(), /*Fecha de creación */
	ultimo_acceso TIMESTAMP,  /*Último acceso al sistema*/
    habilitado BOOL default true, /*usuario habilitado o deshabilitado*/
    constraint usuarios_pk primary key (id),
    constraint usuarios_email unique (email)
);
```
En la tabla anterior, como puedes observar, almacenamos los datos de diferentes usuarios de una web (podría ser cualquiera). 

En esta tabla, tenemos varios campos interesantes. Uno de ellos, el más interesente es posiblemente el `password`. 

El `password` no vamos a almacenarlo directamente en la base de datos, sino que para su inserción almacenaremos un HASH tipo SHA2. No vamos a adentrarnos aquí en que es un HASH, lo suyo es que lo googlees si no sabes que és.

Para insertar un usuario aquí usaremos una sentencia como:

```sql
INSERT INTO usuarios (nombre, apellidos, email, password) VALUES ('Jon', 'Nieve', 'jonnieve@noexiste.com', SHA2('pwdjonnieve@noexiste.com',256));
```
El resto de los datos, o no son obligatorios, o tienen un valor por defecto.

## Preparación de la conexión PDO

Para conectar a la base de datos usaremos un código como el siguiente:

```php

$usuario='dwes';
$password='dwesDWES*1';

try {

    $pdo=new PDO('mysql:host=localhost;port=3306;schema=vigilancia', $usuario, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e)
{
    $pdo=false;
}

```
El código anterior activa `PDO::ATTR_ERRMODE` a `PDO::ERRMODE_EXCEPTION`. Esto significa que cuando haya un error al ejecutar un consulta (por ejemplo, si se viola una restricción - constraint), se lanzará una excepción tipo `PDOException` que podremos capturar y comprobar que ha pasado. Esto va a ser muy útil.

## Función para realizar la inserción de usuario

En el archivo [usuarios.lib.php](usuarios.lib.php) puedes se ha creado una función para realizar la inserción:

```php
/**
 * Crea un nuevo usuario en el sistema.
 * @param PDO $pdo Conexión a PDO. Se espera que sea una conexión válida.
 * @param array $userData Datos del usuario a crear. Array asociativo con las llaves: nombre, apellidos, email y password
 * @return Id del usuario (número entero positivo), false en caso de error al ejecutar la consulta o -1 en caso de que el email ya exista.
 */
function crearUsuario ($pdo, $userData)
{
    $ret=false;
    $req=['nombre','apellidos','email','password'];  
    if (count(array_diff($req,array_keys($userData)))==0
        && count ($userData)==4)
    {   

        $insertQuery='INSERT INTO usuarios (nombre, apellidos, email, password) values (:nombre, :apellidos, :email, SHA2(:comb,256))';
        $userData['comb']=$userData['password'].$userData['email'];
        unset ($userData['password']);
        $pdoStmt=$pdo->prepare($insertQuery);
        try {
            if ($pdoStmt->execute($userData))
            {
                $ret=$pdo->lastInsertId();
            }
        } 
        catch (PDOException $e)
        {
            if ($e->getCode()==='23000') //email ya existente
            {
                $ret=-1;
            }
            else
            {
                echo "<H1>Error en la consulta:</H1>";
                echo "Datos de la consulta realizada: <PRE>";
                $pdoStmt->debugDumpParams();
                echo "</PRE>";
                echo $e->getMessage();
                echo "<BR>";
                echo $e->getCode();
                echo "<BR>";
            }
        }
    }
    return $ret;
}
```
En el código anterior:

* Se comprueba que el número de parámetros recibidos sea el necesario para la consulta que tenemos entre manos. Esto se hace con la funcion `array_diff` y contando el número de parámetros.
* Después, se genera la cadena a la que se le aplicará la función hash conocida como SHA2, que será básicamente la concatenación del password y el email.
* A continuación se elimina uno de los elementos del array `unset ($userData['password']);` dado que el array debe contener exactamente el número de elementos esperados al invocar `execute`.
* Ya solo nos queda invocar `$pdo->prepare(...)` para ejecutar la consulta y `$pdo->execute(...)` para ejecutar la consulta preparada pasandole los datos a sustituir en la consulta preparada, en formato de array asociativo.

## Detalles a observar del código anterior

### Uso de las excepciones

Las excepciones nos van a permitir capturar eventos como restricciones no cumplidas (primary key ya existente, campo único ya existente, ...):

```php
catch (PDOException $e)
        {
            if ($e->getCode()==='23000') //email ya existente
            {
                $ret=-1;
            }
            else
            {
                echo "<H1>Error en la consulta:</H1>";
                echo "Datos de la consulta realizada: <PRE>";
                $pdoStmt->debugDumpParams();
                echo "</PRE>";
                echo $e->getMessage();
                echo "<BR>";
                echo $e->getCode();
                echo "<BR>";
            }
        }
```
Esto podemos también hacerlo con transacciones, pero de esta forma nos ahorramos sentencias (aunque el código es un poco más farragoso).

### Forma de indicar los parámetros de la consulta

La forma de indicar los parámetros en la consulta en las consultas preparadas de PDO tienen varias formas.

La forma más conveniente es indicandolo con ":", por ejemplo: `:email`:

```php
$insertQuery='INSERT INTO usuarios (nombre, apellidos, email, password) values (:nombre, :apellidos, :email, SHA2(:comb,256))';
```

Es la forma que yo recomiendo, porque permite nombrar el parámetro. No obstante, también se puede usar `?`:


```php
$insertQuery='INSERT INTO usuarios (nombre, apellidos, email, password) values (?, ?, ?, SHA2(?,256))';
```

En este caso, el array pasado al método execute debería ir nombrado.