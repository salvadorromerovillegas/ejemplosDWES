# Ejemplo: generar una lista html desde cadena

En este ejemplo se realiza un script que genera una lista partiendo de una cadena de texto en la que los elementos de la lista están separados por una barra horizontal '|'.

Por ejemplo, partiendo de la cadena:

```
Elemento 1 | Elemento 2 | Elemento 3
```

Se genera una lista como:

```html
 <UL>
     <LI>Elemento 1</LI>
     <LI>Elemento 2</LI>
     <LI>Elemento 3</LI>
 </UL>
```

En este ejemplo se ponen en práctica varios aspectos:

* Creación de funciones propias (se crea la función `generarListaDesdeCadena`) que genera la lista.
* Invocación de funciones propias.
* Procesamiento de datos recibidos vía POST.
* Uso del método `explode`.
* Uso del método `trim`.
* Uso del método `htmlspecialchars` (para evitar que se introduzca HTML en la lista que luego haga que la lista no se visualize bien).

