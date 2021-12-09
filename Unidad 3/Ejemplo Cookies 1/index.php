<?php
$test='cadena de ejemplo';

if (isset($_COOKIE['test1']) && isset($_COOKIE['test2']))
{
    if( hash('crc32',$_COOKIE['test1'])==$_COOKIE['test2'])
      echo 'Datos no alterados:'.$_COOKIE['test1'];
    else
      echo 'Datos modificados intencionadamente';
}
else
{
   setcookie('test1',$test,time()+90);
   setcookie('test2',hash('crc32',$test),time()+90);
   echo 'Sin cookies, cookies enviadas (90 segundos de vida).';
}