<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="css/w3.css">
        <title>Mantenimiento Departamentos</title>
    </head>
    <body>


        <?php
        /*
          Autor: Juan Carlos Pastor Regueras
          Index mantenimiento de Departamentos
          Fecha de modificacion: 13-10-2017
         */
        $DescDepartamento = "";
        include "configDpto.php";
        try {
            //Creamos la conexion a la base de datos
            $db = new PDO(DATOSCONEXION, USER, PASSWORD);
            //Definición de los atributos para lanzar una excepcion si se produce un error
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            include "LibreriaValidacionFormularios.php";
            /*

              //Incluimos nuestra libreria de validacion
              include "LibreriaValidacionFormularios.php";

              // Constantes para los valores maximos y minimos
              define("MIN", 1);
              define("MAX", 100);

              // Array de errores, utilizado para mostrar el mensaje de error correspondiente al valor devuelto por la funcion de validacion
              $arrayErrores = array(" ", "No ha introducido ningun valor<br />", "El valor introducido no es valido<br />", "Tamaño minimo no valido<br />", "Tamaño maximo no valido<br />");

              //Variable de control, utilizada para saber si algun campo introducido es erroneo
              $error = false;

              // Variable que guardará el valor devuelto por las funciones de validacion
              $valida = 0;
              // Inicializamos las variables de Departamento y las variables de errores
              $DescDepartamento = "";
              $errorDepartamento = "";
              $estilosDepartamento = "";


              if (filter_has_var(INPUT_POST, 'Buscar')) {//Si hemos pulsado el boton de Enviar
              //Ejecutamos la funcion de validacion y recogemos el valor devuelto
              $valida = validarCadenaAlfanumerica($_POST['DescDepartamento'], MIN, MAX);
              //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
              if ($valida != 0) {
              //Asignamos el error producido al valor correspondiente en el array de errores
              $errorDepartamento = $arrayErrores[$valida];
              //Activamos el class correspondiente para marcar el borde del campo en rojo
              $estilosDepartamento = "error";
              //Como ha habido un error, la variable de control $error toma el valor true
              $error = true;
              //Si no ha habido ningun error, guardamos el valor enviado en el array de departamento
              } else {
              //Si no ha habido ningun error, guardamos el valor enviado en el array de departamento
              $DescDepartamento = $_POST['DescDepartamento'];
              }

             */ if (filter_has_var(INPUT_POST, 'Buscar')) {

                $DescDepartamento = limpiarCampos($_POST['DescDepartamento']);
            }



//Si no hemos pulsado el boton, o ha habido un error en la validacion mostrarmos el formulario
            ?>

            <form action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="post">

                <label for="DescDepartamento">Descripcion:</label>
                <input type="text" name="DescDepartamento" value="<?php echo $DescDepartamento; ?>" >
                <!--<?PHP echo $errorDepartamento; ?>-->
                <input type="submit" name="Buscar" value="Buscar">

            </form>
            <br />
            <a href="nuevo.php"><img src="images/nuevo.png" width="20px" height="20px" /></a>   
            <?PHP
            //Creamos la consulta

            $consulta = "SELECT * FROM Departamento WHERE DescDepartamento LIKE CONCAT('%',:DescDepartamento,'%')";
            //Preparamos la sentencia
            $sentencia = $db->prepare($consulta);
            //Inyectamos los parametros  en el query
            $sentencia->bindParam(":DescDepartamento", $DescDepartamento);
            //La ejecutamos
            $sentencia->execute();
            echo "<table>";
            echo "<tr><td>Codigo</td><td>Descripcion</td></tr>";
            while ($departamento = $sentencia->fetch(PDO::FETCH_OBJ)) {//Mientras haya resultados, se muestran formateados. FETCH avanza el puntero
                echo "<tr>";
                echo "<td>" . $departamento->CodDepartamento . "</td>";
                echo "<td>" . $departamento->DescDepartamento . "</td>";
                // echo "<td>" . $departamento->FechaBaja . "</td>";
                echo "<td><a href='editar.php?CodDepartamento=$departamento->CodDepartamento'><img src='images/editar.png' width='20px' height='20px'/></a><a href='borrar.php?CodDepartamento=$departamento->CodDepartamento'><img src='images/borrar.png'  width='20px' height='20px'/></a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } catch (PDOException $PdoE) {
            //Capturamos la excepcion en caso de que se produzca un error,mostramos el mensaje de error y deshacemos la conexion
            echo($PdoE->getMessage());
            unset($db);
        }
//Cerramos la conexion
        unset($db);
        ?>
    </body>
</html>