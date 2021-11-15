<?php

/**
 * Crea un nuevo usuario en el sistema.
 * @param PDO $pdo Conexión a PDO. Se espera que sea una conexión válida.
 * @param array $userData Datos del usuario a crear. Array asociativo con las llaves: nombre, apellidos, email y password
 * @return Id del usuario (número entero positivo), false en caso de error al ejecutar la consulta o -1 en caso de que el email ya exista.
 */
function crearUsuario (PDO $pdo, array $userData)
{
    $ret=false;
    $req=['nombre','apellidos','email','password'];  
    if (count(array_diff($req,array_keys($userData)))==0
        && count ($userData)==4)
    {   
        $pdo->beginTransaction();

        $selectQuery='SELECT count(*) FROM usuarios WHERE email=:email';
        
        $pdoStmt1=$pdo->prepare($selectQuery);
        
        $pdoStmt1->bindValue('email',$userData['email']);

        if ($pdoStmt1->execute() && $pdoStmt1->fetchColumn()==0) //Verificamos que el usuario no exista.
        {
            $insertQuery='INSERT INTO usuarios (nombre, apellidos, email, password) values (:nombre, :apellidos, :email, SHA2(:comb,256))';
            $pdoStmt=$pdo->prepare($insertQuery);
        
            $nombre=$userData['nombre'];
            $pdoStmt->bindParam('nombre',$nombre);
        
            $apellidos=$userData['apellidos'];
            $pdoStmt->bindParam('apellidos',$apellidos);
        
            $email=$userData['email'];
            $pdoStmt->bindParam('email',$email);
        
            $comb=$userData['password'].$userData['email'];
            $pdoStmt->bindParam('comb',$comb);    

            try {
                if ($pdoStmt->execute())
                {
                    $ret=$pdo->lastInsertId();
                }
                $pdo->commit();
            } 
            catch (PDOException $e)
            {
                echo "<H1>Error en la consulta:</H1>";
                echo "Datos de la consulta realizada: <PRE>";
                $pdoStmt->debugDumpParams();
                echo "</PRE>";
                echo $e->getMessage();
                echo "<BR>";
                echo $e->getCode();
                echo "<BR>";
                $pdo->rollBack();
            }
        }
        else //Ya hay un registro con ese email.
        {
            $ret=-1;
            $pdo->rollBack(); 
        }
    }
    return $ret;
}