<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="css/w3.css">
        <title>Mantenimiento Departamentos</title>
    </head>
    <body >
        <div class="w3-container w3-light-blue" style="width:60%; margin:auto; padding: 40px; height: 100%;">
            <h1>Mantenimiento Departamentos</h1>
            
            <?php
            include "config/configDpto.php";
            try {
                //Creamos la conexion a la base de datos
                $db = new PDO(DATOSCONEXION, USER, PASSWORD);
                //Definición de los atributos para lanzar una excepcion si se produce un error
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                include "librerias/LibreriaValidacionFormularios.php";
                $DescDepartamento = "";
               
                if (filter_has_var(INPUT_POST, 'Buscar')) {
                    $DescDepartamento = limpiarCampos($_POST['DescDepartamento']);
                }

                ?>


                <form class="w3-container" style="width:30%" action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="post">

                    <label for="DescDepartamento" class="w3-tag w3-tag w3-padding w3-round-large w3-light-grey">Descripcion:</label><br />
                    <br/><input class="w3-input" type="text" name="DescDepartamento" value="<?php echo $DescDepartamento; ?>" >

                    <!--<?PHP echo $errorDepartamento; ?>-->
                    <input class="w3-input" type="submit" name="Buscar" value="Buscar">

                </form>
                <br />

             

                <p style="text-align: center;">
                    <a href="nuevo.php"><img src="images/nuevo.png" width="40px" height="40px" /></a> 
                    <a href="importar.php"><img src="images/importar.png" width="40px" height="40px" /></a>
                    <a href="exportar.php"><img src="images/exportar.png" width="40px" height="40px" /></a> 
                </p>

                <?PHP
                //Creamos la consulta

                $consulta = "SELECT * FROM Departamento WHERE DescDepartamento LIKE CONCAT('%',:DescDepartamento,'%')";
                //Preparamos la sentencia
                $sentencia = $db->prepare($consulta);
                //Inyectamos los parametros  en el query
                $sentencia->bindParam(":DescDepartamento", $DescDepartamento);
                //La ejecutamos
                $sentencia->execute();


                echo "<table class='w3-table-all'>";
                echo "<tr><th>Codigo</th><th>Descripcion</th><th>Acciones</th></tr>";

                while ($departamento = $sentencia->fetch(PDO::FETCH_OBJ)) {//Mientras haya resultados, se muestran formateados. FETCH avanza el puntero
                    echo "<tr>";
                    echo "<td>" . $departamento->CodDepartamento . "</td>";
                    echo "<td>" . $departamento->DescDepartamento . "</td>";
                    // echo "<td>" . $departamento->FechaBaja . "</td>";
                    echo "<td><a href='editar.php?CodDepartamento=$departamento->CodDepartamento'><img src='images/editar.png' width='20px' height='20px'/></a><a href='borrar.php?CodDepartamento=$departamento->CodDepartamento'><img src='images/borrar.png'  width='20px' height='20px'/></a></td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</div>";
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
