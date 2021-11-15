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

/**
 * Función para buscar un usuario por email en la base de datos (no retorna password)
 * @param PDO $pdo Conexión PDO válida a la base de datos (espera una conexión válida)
 * @param string $email Email a buscar en la base de datos.
 * @return Datos del usuario: id, nombre, apellidos, email, fecha de creación (instancia de DateTime),
 *      ultimo_acceso (si no existe null, si existe como instancia de DateTime), habilitado
 *      Si el usuario no se encuentra, retorna false.
 */
function buscarUsuario ($pdo, $email)
{
    $resultados=false;
    $selectQuery='SELECT id, nombre, apellidos, creacion, ultimo_acceso, habilitado from usuarios where email=:email';
    $pdoStmt=$pdo->prepare($selectQuery);
    $pdoStmt->bindParam('email',$email);
    if ($pdoStmt->execute())
    {
            $resultados=$pdoStmt->fetch(PDO::FETCH_ASSOC);
            if ($resultados)
            {
                $resultados['email']=$email;
                $resultados['creacion']=new DateTime($resultados['creacion']);
                if ($resultados['ultimo_acceso']!=null)
                    $resultados['ultimo_acceso']=new DateTime($resultados['ultimo_acceso']);
            }   
    }   
    return $resultados;
}

/** 
*Función que busca todos los usuarios que han sido creados entre una fecha de inicio y una fecha de fin dadas, ambas incluidas.
*@param PDO $pdo Conexión PDO válida a la base de datos.
*@param DateTime $inicio Instancia de DateTime con la fecha de inicio.
*@param DateTime $fin Instancia de DateTime con la fecha de fin. No se verifica que sea posterior a $inicio.
*@return array Array de dos dimensiones con id, nombre, apellidos y email de los usuarios creados en ese rango de fechas.
*/
function buscarUsuariosFC (PDO $pdo, DateTime $inicio, DateTime $fin){
    $ret=[];
    $selectQuery='SELECT id, nombre, apellidos, email, habilitado FROM usuarios WHERE creacion>=:inicio AND creacion<=:fin';
    $pdoStmt=$pdo->prepare($selectQuery);
    $pdoStmt->bindValue('inicio',$inicio->format('Y-m-d 00:00'),PDO::PARAM_STR);
    $pdoStmt->bindValue('fin',$fin->format('Y-m-d 23:59'),PDO::PARAM_STR);
    if($pdoStmt->execute())
    {
        while ($resultado=$pdoStmt->fetch(PDO::FETCH_ASSOC))
        {
            $ret[]=$resultado;
        }
    }
    return $ret;
}