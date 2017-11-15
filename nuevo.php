<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="css/w3.css">
        <title>Nuevo Departamento</title>
    </head>
    <body>
        <div class="w3-container w3-light-blue" style="width:60%; margin:auto; padding: 40px; height: 100%;">

            <?php
            /*
              Autor: Juan Carlos Pastor Regueras
              Nuevo
              Fecha de modificacion: 13-11-2017
             */
            //Información de la base de datos. Host y nombre de la BD
            include "config/configDpto.php";
            try {
                //Creamos la conexion a la base de datos
                $db = new PDO(DATOSCONEXION, USER, PASSWORD);
                //Definición de los atributos para lanzar una excepcion si se produce un error
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Incluimos nuestra libreria de validacion
                include "librerias/LibreriaValidacionFormularios.php";

                // Constantes para los valores maximos y minimos
                define("MIN", 1);
                define("MAX", 3);

                // Array de errores, utilizado para mostrar el mensaje de error correspondiente al valor devuelto por la funcion de validacion
                $arrayErrores = array(" ", "No ha introducido ningun valor<br />", "El valor introducido no es valido<br />", "Tamaño minimo no valido<br />", "Tamaño maximo no valido<br />");

                //Variable de control, utilizada para saber si algun campo introducido es erroneo
                $error = false;

                // Variable que guardará el valor devuelto por las funciones de validacion
                $valida = 0;



                // Inicializamos todos los arrays
                $departamento = array(
                    'CodDepartamento' => '',
                    'DescDepartamento' => ''
                );


                $erroresCampos = array(
                    'CodDepartamento' => '',
                    'DescDepartamento' => ''
                );

                $erroresEstilos = array(
                    'CodDepartamento' => '',
                    'DescDepartamento' => ''
                );

                if (filter_has_var(INPUT_POST, 'Crear')) {//Si hemos pulsado el boton de Enviar
                    //Comenzamos las validaciones de datos
                    //Ejecutamos la funcion de validacion y recogemos el valor devuelto
                    $valida = validarCadenaAlfabetica(limpiarCampos($_POST['CodDepartamento']), MIN, MAX);
                    //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                    if ($valida != 0) {
                        //Asignamos el error producido al valor correspondiente en el array de errores
                        $erroresCampos['CodDepartamento'] = $arrayErrores[$valida];
                        //Activamos el class correspondiente para marcar el borde del campo en rojo
                        $erroresEstilos['CodDepartamento'] = "error";
                        //Como ha habido un error, la variable de control $error toma el valor true
                        $error = true;
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    } else {
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                        $departamento['CodDepartamento'] = limpiarCampos($_POST['CodDepartamento']);
                    }

                    $valida = validarCadenaAlfanumerica(limpiarCampos($_POST['CodDepartamento']), MIN, MAX);
                    //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                    if ($valida != 0) {
                        //Asignamos el error producido al valor correspondiente en el array de errores
                        $erroresCampos['DescDepartamento'] = $arrayErrores[$valida];
                        //Activamos el class correspondiente para marcar el borde del campo en rojo
                        $erroresEstilos['DescDepartamento'] = "error";
                        //Como ha habido un error, la variable de control $error toma el valor true
                        $error = true;
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    } else {
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                        $departamento['DescDepartamento'] = limpiarCampos($_POST['DescDepartamento']);
                    }
                }
                //Si no hemos pulsado el boton, o ha habido un error en la validacion mostrarmos el formulario
                if (!filter_has_var(INPUT_POST, 'Crear') || $error) {
                    ?>
                    <form class="w3-container" style="width:30%" action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="post">

                        <label class="w3-tag w3-tag w3-padding w3-round-large w3-light-grey" for="CodDepartamento">Codigo Departamento:</label><br />
                        <br /><input type="text" name="CodDepartamento" value="<?php echo $departamento['CodDepartamento']; ?>" class="<?PHP echo $erroresEstilos['CodDepartamento']; ?>"><br /><br />
                        <?PHP echo $erroresCampos['CodDepartamento']; ?>


                        <label class="w3-tag w3-tag w3-padding w3-round-large w3-light-grey"  for="DescDepartamento">Descripcion Departamento:</label><br />
                        <br /><input type="text" name="DescDepartamento" value="<?php echo $departamento['DescDepartamento']; ?>" class="<?PHP echo $erroresEstilos['DescDepartamento']; ?>"><br /><br />
                        <?PHP echo $erroresCampos['DescDepartamento']; ?>


                        <input type="submit" name="Crear" value="Crear">


                    </form>
                </div>

                <?PHP
            } else {
                //Creamos la consulta
                $consulta = "INSERT INTO Departamento (CodDepartamento,DescDepartamento) VALUES(:CodDepartamento,:DescDepartamento)";
                //Preparamos la sentencia
                $sentencia = $db->prepare($consulta);
                //Inyectamos los parametros del insert en el query
                $sentencia->bindParam(":CodDepartamento", $departamento['CodDepartamento']);
                $sentencia->bindParam(":DescDepartamento", $departamento['DescDepartamento']);
                //Ejecutamos la consulta

                try {
                    $sentencia->execute();
                    header("Location: mantenimiento.php");
                } catch (PDOException $PdoE) {
                    echo "<p>Error al crear el nuevo departamento<p>";
                    unset($db);
                }

                unset($db);
            }
        } catch (PDOException $PdoE) {
            //Capturamos la excepcion en caso de que se produzca un error,mostramos el mensaje de error y deshacemos la conexion
            echo($PdoE->getMessage());
            unset($db);
        }
        ?>


    </body>
</html>
