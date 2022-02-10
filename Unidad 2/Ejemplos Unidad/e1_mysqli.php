<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "
http://www.w3.org/TR/html4/loose.dtd">
<!-- Desarrollo Web en Entorno Servidor -->
<!-- Tema 3 : Trabajar con bases de datos en PHP -->
<!-- Ejemplo: Consultas preparadas con MySQLi -->
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Ejercicio Tema 3: Consultas preparadas en MySQLi</title>
</head>
<body>
<?php
    if (isset($_POST['producto'])) 
    {        
        $producto = addslashes($_POST['producto']);
    }

    @ $dwes = new mysqli("localhost", "dwes", "", "dwes");
    $error = $dwes->connect_errno;
    $mensaje='';
    if ($error == null) {
        // Comprobamos si tenemos que actualizar los valores
        if (isset($_POST['actualiz'])) {
            // Preparamos la consulta
            $tienda = $_POST['tienda'];
            $unidades = $_POST['unidades'];
            $consulta = $dwes->stmt_init();
            $sql = "UPDATE stock SET unidades=? WHERE tienda=? AND producto=?";            
            $consulta->prepare($sql);
            // La ejecutamos dentro de un bucle, tantas veces como tiendas haya
            for($i=0;$i<count($tienda);$i++) {
                $consulta->bind_param('iis', $unidades[$i], $tienda[$i],$producto);
                $consulta->execute();
            }
            $mensaje = "Se han actualizado los datos.";
            $consulta->close();
        }
    }
    // Si no se ha podido establecer la conexión, generamos un mensaje de error
    else $mensaje = $dwes->connect_error;
?>
<div id="encabezado">
<h1>Ejercicio: Consultas preparadas con MySQLi</h1>
<form id="form_seleccion" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
    <span>Producto: </span>
    <select name="producto">
<?php
    // Rellenamos el desplegable con los datos de todos los productos
    if ($error == null) {
        $sql = "SELECT cod, nombre_corto FROM producto";
        $resultado = $dwes->query($sql);
        if($resultado) {
            $row = $resultado->fetch_assoc();
            while ($row != null) {
                echo "<option value='${row['cod']}'";
                // Si se recibió un código de producto lo seleccionamos
                // en el desplegable usando selected='true'
                if (isset($producto) && $producto == $row['cod'])
                    echo " selected='true'";
                echo ">${row['nombre_corto']}</option>";
                $row = $resultado->fetch_assoc();
            }
            $resultado->close();
        }
    }
?>
    </select>
    <input type="submit" value="Mostrar stock" name="enviar"/>
</form>
</div>
<div id="contenido">
    <h2>Stock del producto en las tiendas:</h2>
<?php
    // Si se recibió un código de producto y no se produjo ningún error
    // mostramos el stock de ese producto en las distintas tiendas
    if ($error == null && isset($producto)) {
        // Ahora necesitamos también el código de tienda
        $sql = <<<SQL
SELECT tienda.cod, tienda.nombre, stock.unidades
FROM tienda INNER JOIN stock ON tienda.cod=stock.tienda
WHERE stock.producto='$producto'
SQL;        
        $resultado = $dwes->query($sql);
        if($resultado) {
            // Creamos un formulario con los valores obtenidos
            echo '<form id="form_actualiz" action="'.$_SERVER['PHP_SELF'].'" method="post">';
            $row = $resultado->fetch_assoc();
            while ($row != null) {
                // Metemos ocultos el código de producto y los de las tiendas
                echo "<input type='hidden' name='producto' value='$producto'/>";
                echo "<input type='hidden' name='tienda[]' value='".$row['cod']."'/>";
                echo "<p>Tienda ${row['nombre']}: ";
                // El número de unidades ahora va en un cuadro de texto
                echo "<input type='text' name='unidades[]' size='4' ";
                echo "value='".$row['unidades']."'/> unidades.</p>";
                $row = $resultado->fetch_assoc();
            }
            $resultado->close();
            echo "<input type='submit' value='Actualizar' name='actualiz'/>";
            echo "</form>";
        }
    }
?>
</div>
<div id="pie">
<?php
    // Si se produjo algún error se muestra en el pie
    if ($error != null) echo "<p>Se ha producido un error! $mensaje</p>";
    else {
        echo $mensaje;
        $dwes->close();
        unset($dwes);
    }
?>
</div>
</body>
</html>