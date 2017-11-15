<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="css/w3.css">
        <title>Borrar Departamento</title>
    </head>
    <body>




        <div class="w3-container w3-light-blue" style="width:60%; margin:auto; padding: 40px; height: 100%;">
            <?php
            /*
              Autor: Juan Carlos Pastor Regueras
              Borrar
              Fecha de modificacion: 13-11-2017
             */
            //Información de la base de datos. Host y nombre de la BD
            include "config/configDpto.php";

            //Incluimos nuestra libreria de validacion
            include "librerias/LibreriaValidacionFormularios.php";

            // Constantes para los valores maximos y minimos
            define("MIN", 1);
            define("MAX", 3);


            $erroresCampos = array(
                'CodDepartamento' => '',
                'DescDepartamento' => ''
            );
            // Array de errores, utilizado para mostrar el mensaje de error correspondiente al valor devuelto por la funcion de validacion
            $arrayErrores = array(" ", "No ha introducido ningun valor<br />", "El valor introducido no es valido<br />", "Tamaño minimo no valido<br />", "Tamaño maximo no valido<br />");

            //Variable de control, utilizada para saber si algun campo introducido es erroneo
            $error = false;

            // Variable que guardará el valor devuelto por las funciones de validacion
            $valida = 0;

            try {
                //Creamos la conexion a la base de datos
                $db = new PDO(DATOSCONEXION, USER, PASSWORD);
                //Definición de los atributos para lanzar una excepcion si se produce un error
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if (isset($_GET['CodDepartamento'])) {
                    $CodDepartamento = $_GET['CodDepartamento'];

                    $consulta = "SELECT * FROM Departamento WHERE CodDepartamento = :CodDepartamento";
                    //Preparamos la sentencia
                    $sentencia = $db->prepare($consulta);
                    //Inyectamos los parametros  en el query
                    $sentencia->bindParam(":CodDepartamento", $CodDepartamento);
                    //La ejecutamos
                    $sentencia->execute();
              
                    if ($sentencia->rowCount() == 1) {
                        $departamento = $sentencia->fetch(PDO::FETCH_OBJ);
                        ?>

                        <form class="w3-container" style="width:30%" action="<?PHP echo $_SERVER['PHP_SELF'] . "?CodDepartamento=$CodDepartamento"; ?>" method="post">

                            <label class="w3-tag w3-tag w3-padding w3-round-large w3-light-grey" for="CodDepartamento">Codigo Departamento:</label><br />
                            <br /><input type="text" name="CodDepartamento" value="<?php echo $departamento->CodDepartamento; ?>" readonly><br /><br />


                            <label class="w3-tag w3-tag w3-padding w3-round-large w3-light-grey" for="DescDepartamento">Descripcion Departamento:</label><br />
                            <br /><input type="text" name="DescDepartamento" value="<?php echo $departamento->DescDepartamento; ?>" readonly><br /><br />


                            <input type="submit" name="Borrar" value="Borrar">

                        </form>


                        <?php
                        if (isset($_POST['Borrar'])) {
                           
                            $consulta = "DELETE FROM Departamento WHERE CodDepartamento = :CodDepartamento";
                            //Preparamos la sentencia
                            $sentencia = $db->prepare($consulta);
                            //Inyectamos los parametros  en el query
                            $sentencia->bindParam(":CodDepartamento", $CodDepartamento);

                            //La ejecutamos
                            if ($sentencia->execute()) {
                                header("Location: mantenimiento.php");
                            }
                        }
                    } else {
                        echo "<p>El departamento que quiere borrar no existe</p>";
                    }
                } else {
                    header("Location: mantenimiento");
                }
            } catch (PDOException $PdoE) {
                //Capturamos la excepcion en caso de que se produzca un error,mostramos el mensaje de error y deshacemos la conexion
                echo($PdoE->getMessage());
                unset($db);
            }
            ?>
        </div>
    </body>
</html>
