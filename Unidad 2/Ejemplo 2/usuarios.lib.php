<?php

/**
 * Crea un nuevo usuario en el sistema.
 * @param PDO $pdo Conexión a PDO. Se espera que sea una conexión válida.
 * @param array $userData Datos del usuario a crear. Array asociativo con las llaves: nombre, apellidos, email y password
 * @return Id del usuario (número entero positivo), false en caso de error al ejecutar la consulta o -1 en caso de que el email ya exista.
 */
function crearUsuario ($pdo, $userData)
{
    $ret=false;
    $req=['nombre','apellidos','email','password'];  
    if (count(array_diff($req,array_keys($userData)))==0
        && count ($userData)==4)
    {   

        $insertQuery='INSERT INTO usuarios (nombre, apellidos, email, password) values (:nombre, :apellidos, :email, SHA2(:comb,256))';
        $userData['comb']=$userData['password'].$userData['email'];
        unset ($userData['password']);
        $pdoStmt=$pdo->prepare($insertQuery);
        try {
            if ($pdoStmt->execute($userData))
            {
                $ret=$pdo->lastInsertId();
            }
        } 
        catch (PDOException $e)
        {
            if ($e->getCode()==='23000') //email ya existente
            {
                $ret=-1;
            }
            else
            {
                echo "<H1>Error en la consulta:</H1>";
                echo "Datos de la consulta realizada: <PRE>";
                $pdoStmt->debugDumpParams();
                echo "</PRE>";
                echo $e->getMessage();
                echo "<BR>";
                echo $e->getCode();
                echo "<BR>";
            }
        }
    }
    return $ret;
}