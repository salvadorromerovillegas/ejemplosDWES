# Ejemplo 6 - Buscar multiples registros en un rango de fechas

Este ejemplo parte de la base de que ya has entendido el [ejemplo 4](../Ejemplo%204). Repásalo si es necesario.

La idea de este ejemplo es permitir la búsqueda de usuarios en función de la fecha de registro (columna `creacion`). Dada una fecha de inicio y otra fecha de fin, se buscan todos los usuarios que hay en la base de datos cuya fecha de `creacion` está entre dicho inicio y fin (ambos incluidos).

Para lograr esto se ha implementado una nueva función en el script [usuarios.lib.php](usuarios.lib.php). Veamosla:

```php
function buscarUsuariosFC (PDO $pdo, DateTime $inicio, DateTime $fin){
    $ret=[];
    $selectQuery='SELECT id, nombre, apellidos, email, habilitado FROM usuarios WHERE creacion>=:inicio AND creacion<=:fin';
    $pdoStmt=$pdo->prepare($selectQuery);
    $pdoStmt->bindValue('inicio',$inicio->format('Y-m-d 00:00'),PDO::PARAM_STR);
    $pdoStmt->bindValue('fin',$fin->format('Y-m-d 23:59'),PDO::PARAM_STR);
    if($pdoStmt->execute())
    {
        while ($resultado=$pdoStmt->fetch(PDO::FETCH_ASSOC))
        {
            $ret[]=$resultado;
        }
    }
    return $ret;
}
```
## Fíjate en...

### Uso del método `fetch`

Fíjate que el método `fetch` está dentro de un bucle:
```php
        while ($resultado=$pdoStmt->fetch(PDO::FETCH_ASSOC))
        {
            $ret[]=$resultado;
        }
```
En cada iteración de ese bucle se invoca el método `fetch`. Cada invocación rescatará una fila de resultados de la consulta, hasta que no queden más resultados, caso en el que retornará `false`.

El argumento `PDO::FETCH_ASSOC` hace referencia a que se obtendrán los datos como un array asociativo (`$resultado['id']`, `$resultado['nombre]'`, etc.).

### Conversión de fechas

Es necesario pasar la fecha a la base de datos en el formato esperado, que generalmente es: `YYYY-MM-AA`. Para realizar esto podemos apoyarnos en la clase `DateTime` y usar el método `format`:

```php
$inicio->format('Y-m-d 00:00')
```

El método `buscarUsuariosFC` espera que `$inicio` y `$fin` sean una instancia de la clase `DateTime`. Es por ello que en su invocación, en el script [buscarusuariosfc.php](buscarusuariosfc.php) se crean dos instancias de dichas clases para pasarlas como parámetro:

```php
    $inicio=DateTime::createFromFormat('d/m/Y','01/01/2021');
    $fin=DateTime::createFromFormat('d/m/Y','31/12/2030');
    if ($pdo)
    {
        $usuarios=buscarUsuariosFC($pdo,$inicio,$fin);
        //... resto del código
    }
```
