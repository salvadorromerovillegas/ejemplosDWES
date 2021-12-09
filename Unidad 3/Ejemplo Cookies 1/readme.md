# Ejemplo de uso de cookies con validación HASH

Este ejemplo es un ejemplo sencillo del uso de cookies:

* Se envía una cookie con un texto sencillo.
* Se envía una cookie con un hash de dicho texto, para comprobar posibles alternaciones.

El hash de la cookie se ha calculado con el algoritmo CRC32, que es un algoritmo muy sencillo y poco recomendable para situaciones donde la seguridad sea importante.

Fíjate en el uso del array `$_COOKIE` donde aparecerán los datos recibidos del navegador. Ten en cuenta que hasta que no se envíe por primera vez la cookie del servidor al navegador, el navegador no podrá enviarla de nuevo al servidor, simplemente porque no la tiene. 

Ten en cuenta también que el uso de `setcookie` (que envía las cookies al servidor) no modifica el array `$_COOKIE` (que solo contendrá la información recibida del navegador).