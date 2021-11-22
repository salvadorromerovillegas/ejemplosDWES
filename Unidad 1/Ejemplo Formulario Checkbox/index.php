<?php
    //Array que contiene la lista de deportes a mostrar (y sus valores por defecto en caso de que no se señalen.)
    $deportes=[
                'balonmano'=>'no',
                'baloncesto'=>'no',
                'béisbol'=>'no',
                'criquet'=>'no',
                'fútbol'=>'no',
                'hockey'=>'no',
                'polo'=>'no',
                'rugby'=>'no',
                'voleibol'=>'no',
                'waterpolo'=>'no'
    ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Document</title>
    <style>
        .si {font-weight: bold;}
        .no {font-style: italic;}
    </style>
</head>
<body>
<?php
    //verificamos que existe el dato en $_POST y que es un array        
    if (isset($_POST['deportes']) && is_array($_POST['deportes']))
    {
        //nos quedamos con los deportes que tenemos previstos (intersección) descartando otros (evitando manipulaciones).
        $deportes_rec=array_intersect_key($_POST['deportes'],$deportes);                
        //nos quedamos con los campos que solo tienen un valor como "si" (evitando manipulaciones).
        $deportes_rec=array_filter($deportes_rec, fn($val)=>$val==='si');
        //completamos los campos que no haya marcado el usuario con "no":
        $deportes_rec=array_merge($deportes,$deportes_rec);
        //mostramos los datos:
        echo "<ul>";
        foreach ($deportes_rec as $dep=>$val)
        {
            echo "<li class='$val'>Has marcado que $dep $val es uno de tus deportes de equipo favorito.</li>";        
        }
        echo "</ul>";
    }        
    else
    {
        $deportes_rec=$deportes;
    }

    ?>
    <H1>
    Señala tus deportes de equipo favoritos:
    </H1>
    <form action="" method="post">     
        <br>
        <?php   
        //Generamos la lista de deportes, marcando los que previamente se habían marcado.     
        foreach($deportes_rec as $deporte=>$val) {
        ?>
                <input type="checkbox" name="deportes[<?=$deporte;?>]" value='si' <?php if ($val==='si') {echo 'checked';} ?>> <?=ucfirst($deporte)?><br>
        <?php
        }
        ?>
        <input type="submit" value="Enviar!">
    </form>   
</body>
</html>