<?php
include_once('ejercicio1_config.inc.php');
define ('RESOURCES_PATH','translations\\',true);
define ('FOOTER','ejercicio1.trans.foot.html',true);
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Ejercicio 1.- Solución.</title>
</head>
<body>
<?php

echo "<H1>{$_SERVER['PHP_SELF']}</H1>";
//Comprobamos que existe $_GET['lang'] y que es un idioma que está en el array $MENSAJES
if (isset($_GET['lang']) && array_key_exists($_GET['lang'],$MENSAJES)) {
    //Incluimos la traducción usando el contenido del array $MENSAJES
    include (RESOURCES_PATH.$MENSAJES[$_GET['lang']]);
}
else
{
    //Incluimos la traducción en inglés usando el array $MENSAJES
    include (RESOURCES_PATH.$MENSAJES['en']);
}

//Cargamos el footer (opcionalmente verificamos si el archivo existe)
if (file_exists(RESOURCES_PATH.FOOTER))
{
    readfile(RESOURCES_PATH.FOOTER);
}

//Esto último también se podría poner como:
//file_exists(RESOURCES_PATH.FOOTER) && readfile(RESOURCES_PATH.FOOTER);

?>
</body>
</html>