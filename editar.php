<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="css/estilos23.css">
        <title>Editar Departamento</title>
    </head>
    <body>

        <?php
        /*
          Autor: Juan Carlos Pastor Regueras
          Nuevo
          Fecha de modificacion: 13-11-2017
         */
        //Información de la base de datos. Host y nombre de la BD
        include "configDpto.php";
        try {
            //Creamos la conexion a la base de datos
            $db = new PDO(DATOSCONEXION, USER, PASSWORD);
            //Definición de los atributos para lanzar una excepcion si se produce un error
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $CodDepartamentoBuscar = $_GET['CodDepartamento'];

            $consulta = "SELECT * FROM Departamento WHERE CodDepartamento = :CodDepartamento";
            //Preparamos la sentencia
            $sentencia = $db->prepare($consulta);
            //Inyectamos los parametros  en el query
            $sentencia->bindParam(":CodDepartamento", $CodDepartamentoBuscar);
            //La ejecutamos
            if ($sentencia->execute()) {
                $departamento = $sentencia->fetch(PDO::FETCH_OBJ);
            }
            ?>

            <form action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="post">

                <label for="CodDepartamento">Codigo Departamento:</label><br />
                <input type="text" name="CodDepartamento" value="<?php echo $departamento->CodDepartamento; ?>"><br /><br />


                <label for="DescDepartamento">Descripcion Departamento:</label><br />
                <input type="text" name="DescDepartamento" value="<?php echo $departamento->DescDepartamento; ?>"><br /><br />


                <input type="submit" name="enviar" value="Enviar">

            </form>

            <?php
        } catch (PDOException $PdoE) {
            //Capturamos la excepcion en caso de que se produzca un error,mostramos el mensaje de error y deshacemos la conexion
            echo($PdoE->getMessage());
            unset($db);
        }
        ?>

    </body>
</html>