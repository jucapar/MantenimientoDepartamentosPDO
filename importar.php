<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="css/w3.css">
        <title>Importar xml</title>
    </head>
    <body>

        <div class="w3-container w3-light-blue" style="width:60%; margin:auto; padding: 40px; height: 100%;">
            <?php
            /*
              Autor: Juan Carlos Pastor Regueras
              Formulario para añadir un departamento a la tabla Departamento con validación de entrada y control de errores.
              Fecha de modificacion: 10-11-2017
             */
            //Información de la base de datos. Host y nombre de la BD
            include "config/configDpto.php";
            try {
                //Creamos la conexion a la base de datos
                $db = new PDO(DATOSCONEXION, USER, PASSWORD);
                //Definición de los atributos para lanzar una excepcion si se produce un error
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $PdoE) {
                //Capturamos la excepcion en caso de que se produzca un error,mostramos el mensaje de error y deshacemos la conexion
                echo($PdoE->getMessage());
                unset($db);
            }

            include "librerias/LibreriaValidacionFormularios.php";
            $error = false;
            if (filter_has_var(INPUT_POST, 'Importar')) {//Si hemos pulsado el boton de Enviar
                $xml_file = $_FILES['fichero']['tmp_name']; //Archivo xml a cargar

                if (file_exists($xml_file)) { //Si existe, se carga
                    $xml = simplexml_load_file($xml_file);
                } else {//si no existe, error
                    $error = true;
                    unset($db);
                }
            }
            //Si no hemos pulsado el boton, o ha habido un error en la validacion mostrapollarmos el formulario
            if (!filter_has_var(INPUT_POST, 'Importar') || $error) {
                ?>
                <form class="w3-container" style="width:30%" action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="post"  enctype="multipart/form-data">
                    <label class="w3-tag w3-tag w3-padding w3-round-large w3-light-grey" for="fichero">Seleccione un fichero XML:</label><br />
                    <br /><input type="file" id="fichero" name="fichero" >
                    <br /><br />
                    <input type="submit" name="Importar" value="Importar">
                </form>
            </div>
            <?PHP
        } else {
            //Variable que contará el numero de registro que llevamos insertados, nos servirá para saber el registro que ha fallado
            $numRegistros = 0;
            //Variable para comprobar que todas las inserciones han sido correctas
            $correcto = true;
            //Variables auxiliares para la inyeccion de parametros
            $CodDepartamento = "";
            $DescDepartamento = "";

            //Comienzo de la transacción para una serie de operaciones. Se desactiva el modo autocommit( El modo auto-commit significa que toda consulta que se ejecute tiene su propia transacción implícita
            
            //Preparamos la consulta
            $consulta = "INSERT INTO Departamento (CodDepartamento,DescDepartamento) VALUES (:CodDepartamento,:DescDepartamento)";
            //Preparamos la sentencia
            $sentencia = $db->prepare($consulta);
            //Inyectamos las variables
            $sentencia->bindParam(":CodDepartamento", $CodDepartamento);
            $sentencia->bindParam(":DescDepartamento", $DescDepartamento);
            //Recorremos nuestro fichero XML 
            foreach ($xml->Departamento as $departamento) {
                //Incrementamos el numero de registros que queremos insertar;
                $numRegistros++;
                if (validarCadenaAlfabetica($departamento->CodDepartamento) == 0 && validarCadenaAlfanumerica($departamento->DescDepartamento) == 0) {

                    //Inyectamos los parametros
                    $CodDepartamento = limpiarCampos($departamento->CodDepartamento);
                    $DescDepartamento = limpiarCampos($departamento->DescDepartamento);
                    //Si la ejecucion es correcta incrementamos el numero de registros correctos
                    try {
                        $sentencia->execute();
                        
                    } catch (PDOException $PdoE) {
                        //Si la ejecucion de uno de los registros falla, mostramos por pantalla cual ha fallado
                        echo("<p>La insercion del registro $numRegistros ha fallado</p>");
                    }
                } else {
                    echo("<p>El registro  $numRegistros no es valido</p>");
                }
            }
            echo "Importacion finalizada<br /><br />";
            echo "<a href='mantenimiento.php'>Volver</a>";
        }
        unset($db);
        ?>
    </body>
</html>