# Tarjeta de claves

Un servicio web utiliza unas tarjetas de claves del siguiente estilo:

```
A S N R I F
E I I N F A
E R I R N S
M N F I N R
```

Las tarjetas están compuestas por un cuadrante de texto como el anterior. Para autenticarse se pide que el usuario, además de indicar su nombre de usuario, que indique la combinación de letras que hay en una columna o fila, y en una dirección (fila: izquierda, o derecha; columna: arriba o abajo), existiendo 4 combinaciones posibles.

Por ejemplo, el sistema podría requerir que el usuario indicara casos como los siguiente:

- Fila 2 Dirección IZQUIERDA: el usuario debería indicar AFNIIE
- Fila 2 Dirección DERECHA: el usuario debería indicar EIINFA
- Columna 3 Dirección ABAJO: el usuario debería indicar NIIF
- Columna 3 Dirección ARRIBA: el usuario debería indicar FIIN

Tu misión consiste en realizar uno o varios métodos que, dada una tarjeta de claves como la anterior, una fila o columna y una dirección, compruebe si el usuario ha indicado la clave correcta o no. 

Eso sí, el método principal tendría la siguiente cabecera:

```php
function checkCardKey ($tarjeta, $clave, $direccionBusqueda, $filaOColumna)
```

Donde

- `$tarjeta` será la tarjeta en formato array de cadenas `['ABCDE','FGENE','RRNDS','RRESA']`
- `$clave` será la cadena a comprobar
- `$direccionBusqueda` es la dirección de búsqueda será 'D' para abajo, 'U' para arriba, 'L' para izquierda y 'R' para derecha (Down, Up, Left y Right)
- `$filaOColumna` será el número de fila o columna. Si la dirección es `D` o `U` se entenderá que `$filaOColumna` será un número de columna, y si la dirección es `L` o `R` se entenderá que `$filaOColumna` es un número de fila.

Nota: el método retornará `false` siempre que la clave no coincida o que no quepa (por que sea más grande del número de filas esperadas). Tienes que intentar concebir tu algoritmo pensando en tarjetas de diferentes tamaños, no de un tamaño fijo.

## Casos de prueba

```php
$card=['ABCDE',
       'EGHIJ',
       'KIMIJ'];

//debe mostrar true
var_dump(checkCardKey($card,'AEK',ABAJO,0));
//debe mostrar true
var_dump(checkCardKey($card,'IGB',ARRIBA,1));
//debe mostrar true
var_dump(checkCardKey($card,'EGHIJ',DERECHA,1));
//debe mostrar true
var_dump(checkCardKey($card,'JIMIK',IZQUIERDA,2));
//debe mostrar false
var_dump(checkCardKey($card,'AHK',ABAJO,0));
//debe mostrar false
var_dump(checkCardKey($card,'IGH',ARRIBA,1));
//debe mostrar false
var_dump(checkCardKey($card,'EHHIJ',DERECHA,1));
//debe mostrar false
var_dump(checkCardKey($card,'JIMHK',IZQUIERDA,2));
//debe mostrar false (no cumple con longitud)
var_dump(checkCardKey($card,'YI',IZQUIERDA,2));
//debe mostrar false (no cumple con longitud)
var_dump(checkCardKey($card,'YIIRJRJR',ARRIBA,2));
//debe mostrar false (fila o columna no válida)
var_dump(checkCardKey($card,'YI',IZQUIERDA,33));
//debe mostrar false (fila o columna no válida)
var_dump(checkCardKey($card,'YIIRJRJR',ARRIBA,54));
```