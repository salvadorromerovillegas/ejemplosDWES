# Ejemplo de validación completa de un formulario.

En este ejemplo se presenta un formulario donde se solicitan varios datos relativos a una empresa de alquiler de vehículos on-line:

* Provincia de recogida (select).
* Fecha de recogida.
* Hora de recogida.
* Fecha de devolución.
* Hora de devolución.
* Edad del conductor.
* Email.
* Y dos checkbox que permiten indicar si el usuario desea o no recibir publicidad de:
    * Ofertas de vehículos en alquiler.¨
    * Ofertas de otros productos.

La idea aquí es verificar todos los datos:

* Provincia de recogida correcta (dentro de uno de los valores esperados).
* Fechas de recogida y devolución con formato (dd/mm/aaaa) y válida según el calendario gregoriano.
* Hora de recogida y devolución con formato (hh:mm) y válidas (superior o igual a 00:00 y inferior o igual a 23:59).
* Fecha/hora recogida anterior a la fecha/hora devolución.
* Edad del conductor igual o superior a 18 años.
* Email válido.
* Checkbox marcados solo si se ha indicado el email.

Para realizar este ejercicio se hace el procesamiento en un script anexo (procform.php), que generará dos arrays, uno con posibles errores y otro con los datos correctos. 

En el ejemplo se utilizan expresiones regulares, métodos como `checkdate` y la clase `DateTime` en diferentes variantes. 

Los datos considerados correctos se reemplazarán en el formulario para que el usuario solo tenga que corregir los datos incorrectos. Para esto se utilizan marcadores de sustitución creados ad-hoc para el ejercicio. 

Para realizar esto último se utilizan métodos como `str_replace` y `preg_replace`.

