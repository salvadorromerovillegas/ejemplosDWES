# Reemplazador de emoticonos por emojis

En este ejemplo se realiza un script que dada una cadena como la siguiente:

>Qué tal estás.
Espero que bien, yo estoy :-( desde que no te veo.

Genere una cadena como la siguiente:

>Qué tal estás.
Espero que bien, yo estoy &#x1F641; desde que no te veo.

La idea es que dado un texto recibido desde un formulario, se realicen los siguientes reemplazos (de emoticono a un emoji):

| Emoticono | Emoji |
|:-:	|:-:	|
| :-) 	| 😀 	|
| ;-) 	| 😉 	|
| (-: 	| 🙃 	|
| :-( 	| 🙁 	|
| :-o 	| 😮 	|

Aprovechamos que cada Emoji tiene un código de escape, como el siguiente:

```html
        😀 es la secuencia de escape &#x1F600;
        😉 es la secuencia de escape &#x1F609;
        🙃 es la secuencia de escape &#x1F643;
        🙁 es la secuencia de escape &#x1F641;
        😮 es la secuencia de escape &#x1F62E; 
```

El reemplazo es relativamente sencillo, pero para realizarlo te recomiendo usar la función `str_replace`.