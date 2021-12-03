<?php

//Prevenimos que se pueda invocar directamente el script de procesamiento
//de formulario.
if (!defined('PROCFORM_818A')) header('Location: index.php');

//Definiciones de constantes
define('REG_FECHA','/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/');
define('REG_HORA','/^\d{1,2}:\d{2}$/');
define('REG_EDAD','/^\d{1,3}$/'); 
define('REG_EMAIL','/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/');
define('RECOGIDAS', ['04','11','14','18','21','23','29','41'] );


//arrays para almacenar errors y datos
$formerrors=[];
$datos=[];

if ($_POST) // Si se han recibido datos $_POST debe contener algo
{

/// Verificación de la provincia de recogida.
if (isset($_POST['recogida']) && in_array($_POST['recogida'],RECOGIDAS))
{ 
    $datos['recogida']=$_POST['recogida'];
}
else
{
    $formerrors[]="La provincia de recogida no es correcta.";
}

/// Verificación de la fecha de recogida (incluida verificación de si la fecha es válida).
if (isset($_POST['drecogida']))
{
    $drecogida=trim($_POST['drecogida']);

    if(preg_match(REG_FECHA,$drecogida,$matches)){ 
        //Usamos los grupos de la expresión regular para extraer las partes
        $dia=$matches[1];
        $mes=$matches[2];
        $year=$matches[3];
        if (checkdate($mes,$dia,$year))
        {
            $datos['drecogida']=$drecogida;
        }
        else
        {
            $formerrors[]="La fecha de recogida no es válida (no existe).";
        }

    }
    else
    {
        $formerrors[]="La fecha de recogida no tiene el formato esperado.";
    }
}
else
{
    $formerrors[]="La fecha de recogida no se ha indicado.";
}

/// Verificación de la hora de recogida
if (isset($_POST['hrecogida']) && preg_match(REG_HORA,$hrecogida=trim($_POST['hrecogida'])))
{
    
    //En este caso usamos explode para separar la partes de la hora y verificar si la hora es correcta.
    list($hora,$min)=explode(":",$hrecogida);    
    if ($hora>=0 && $hora<=23 && $min>=0 && $min<=59) {
        $datos['hrecogida']=$hrecogida;
    }
    else
    {
        $formerrors[]="La hora de recogida no es correcta.";
    }
}
else
{
    $formerrors[]="La hora de recogida no es correcta o no se ha indicado.";
}

///Verificación del día de devolución

if (isset($_POST['ddevolucion']) && preg_match(REG_FECHA,$ddevolucion=trim($_POST['ddevolucion']),$matches))
{
    //Usamos los grupos de la expresión regular para extraer las partes de la fecha
    $dia=$matches[1];
    $mes=$matches[2];
    $year=$matches[3];
    if (checkdate($mes,$dia,$year))
    {
        $datos['ddevolucion']=$ddevolucion;
    }
    else
    {
        $formerrors[]="La fecha de devolución no es válida (no existe).";
    }
}
else
{
    $formerrors[]="La fecha de devolución no es correcta o no se ha indicado.";
}

///Comprobación de la hora de devolución

if (isset($_POST['hdevolucion']) && preg_match(REG_HORA,$hdevolucion=trim($_POST['hdevolucion'])))
{
    //En este caso usamos explode para separar la partes de la hora y verificar si la hora es correcta.
    list($hora,$min)=explode(":",$hdevolucion);
    if ($hora>=0 && $hora<=23 && $min>=0 && $min<=59) {
        $datos['hdevolucion']=$_POST['hdevolucion'];
    }
    else
    {
        $formerrors[]="La hora de devolución no es correcta.";
    }
}
else
{
    $formerrors[]="La hora de devolución no es correcta o no se ha indicado.";
}

/// Comprobamos que la fecha/hora de devolución sea posterior a la fecha/hora de recogida.
if (isset($datos['ddevolucion']) && isset($datos['hdevolucion']) 
&& isset($datos['drecogida']) && isset($datos['hrecogida']))
{
    $dtDevolucion=DateTime::createFromFormat('j/n/Y G:i', $datos['ddevolucion'].' '.$datos['hdevolucion']);
    $dtRecogida=DateTime::createFromFormat('j/n/Y G:i', $datos['drecogida'].' '.$datos['hrecogida']);
    //Para la comparación usamos el formato de fecha/hora como número entero (timestamp)
    if ($dtDevolucion->getTimestamp()<=$dtRecogida->getTimestamp())
    {
        $formerrors[]="La fecha de devolución es anterior o igual a la fecha de recogida.";
        unset($datos['ddevolucion']);
        unset($datos['hdevolucion']);
    }
}

/// Comprobamos la edad (mayor de 18 años)
if (isset($_POST['edad']) && preg_match(REG_EDAD,$_POST['edad']))
{
    $edad=intval($_POST['edad']); 
    if ($edad<18)
    {
        $formerrors[]="No se puede alquilar con menos de 18 años.";
    }
    else
    {
        $datos['edad']=$edad;
    }
}
else
{
    $formerrors[]="La edad no es correcta.";
}

//Comprobamos el mail
if (isset($_POST['email']) && preg_match(REG_EMAIL,$email=trim($_POST['email'])))
{
    $datos['email']=$email;
}
else
{
    $formerrors[]="El email no es correcto.";
}

/// Comprobamos las ofertas seleccionadas.

if (isset($_POST['ofertas']))
{
    if (
        (
            isset($_POST['ofertas']['otros']) || isset($_POST['ofertas']['alquiler'])
        )
        &&
            !isset($datos['email'])
        )    
    {
        $formerrors[]="No se está presente el email y no se pueden marcar las ofertas.";
    }
    else
    {
        $datos['ofertas_otros']=$_POST['ofertas']['otros']??'no';
        $datos['ofertas_alquiler']=$_POST['ofertas']['alquiler']??'no';
    }
}  else
{
    $datos['ofertas_otros']='no';
    $datos['ofertas_alquiler']='no';
}

}//end if($_POST)
