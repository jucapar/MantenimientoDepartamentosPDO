<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="css/w3.css">
        <title>Editar Departamento</title>
    </head>
    <body>


       

        <div class="w3-container w3-light-blue" style="width:60%; margin:auto; padding: 40px; height: 100%;">
            <?php
            /*
              Autor: Juan Carlos Pastor Regueras
              Editar
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
                    $CodDepartamentoBuscar = $_GET['CodDepartamento'];

                    $consulta = "SELECT * FROM Departamento WHERE CodDepartamento = :CodDepartamento";
                    //Preparamos la sentencia
                    $sentencia = $db->prepare($consulta);
                    //Inyectamos los parametros  en el query
                    $sentencia->bindParam(":CodDepartamento", $CodDepartamentoBuscar);
                    //La ejecutamos
                    $sentencia->execute();
                    if ($sentencia->rowCount() == 1) {
                        $departamento = $sentencia->fetch(PDO::FETCH_OBJ);
                        ?>

                        <form class="w3-container" style="width:30%" action="<?PHP echo $_SERVER['PHP_SELF'] . "?CodDepartamento=$CodDepartamentoBuscar"; ?>" method="post">

                            <label class="w3-tag w3-tag w3-padding w3-round-large w3-light-grey" for="CodDepartamento">Codigo Departamento:</label><br />
                            <br /><input type="text" name="CodDepartamento" value="<?php echo $departamento->CodDepartamento; ?>" readonly><br /><br />


                            <label class="w3-tag w3-tag w3-padding w3-round-large w3-light-grey" for="DescDepartamento">Descripcion Departamento:</label><br />
                            <br /><input type="text" name="DescDepartamento" value="<?php echo $departamento->DescDepartamento; ?>"><br /><br />


                            <input type="submit" name="Editar" value="Editar">

                        </form>

                    
                    <?php
                    if (isset($_POST['Editar'])) {

                        $departamento->CodDepartamento = limpiarCampos($_POST['CodDepartamento']);
                        //Ejecutamos la funcion de validacion y recogemos el valor devuelto

                        $valida = validarCadenaAlfanumerica(limpiarCampos($_POST['DescDepartamento']));
                        //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                        if ($valida != 0) {
                            //Asignamos el error producido al valor correspondiente en el array de errores
                            $erroresCampos['DescDepartamento'] = $arrayErrores[$valida];
                            //Activamos el class correspondiente para marcar el borde del campo en rojo
                            $erroresEstilos['DescDepartamento'] = "error";
                            //Como ha habido un error, la variable de control $error toma el valor true
                            $error = true;
                        } else {
                            //Si no ha habido ningun error, guardamos el valor enviado en el array de departamento
                            $departamento->DescDepartamento = limpiarCampos($_POST['DescDepartamento']);
                        }

                        if (!$error) {
                            $consulta = "UPDATE Departamento SET DescDepartamento = :DescDepartamento WHERE CodDepartamento = :CodDepartamento";
                            //Preparamos la sentencia
                            $sentencia = $db->prepare($consulta);
                            //Inyectamos los parametros  en el query
                            $sentencia->bindParam(":CodDepartamento", $departamento->CodDepartamento);
                            $sentencia->bindParam(":DescDepartamento", $departamento->DescDepartamento);
                            //La ejecutamos
                            if ($sentencia->execute()) {
                                header("Location: mantenimiento.php");
                            }
                        } else {

                            echo "<p>" . $erroresCampos['DescDepartamento'] . "</p>";
                        }
                    }
                } else {
                    echo "<p>El departamento que quiere editar no existe</p>";
                }
            } else {
                header("Location: mantenimiento.php");
               

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
