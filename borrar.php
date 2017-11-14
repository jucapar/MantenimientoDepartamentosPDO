<?php


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

     include "configDpto.php";
        try {
            //Creamos la conexion a la base de datos
            $db = new PDO(DATOSCONEXION, USER, PASSWORD);
            //Definición de los atributos para lanzar una excepcion si se produce un error
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $CodDepartamento = $_GET['CodDepartamento'];
            $consulta = "DELETE FROM Departamento WHERE CodDepartamento = :CodDepartamento";
                    //Preparamos la sentencia
                    $sentencia = $db->prepare($consulta);
                    //Inyectamos los parametros  en el query
                    $sentencia->bindParam(":CodDepartamento", $CodDepartamento);
                   
                    //La ejecutamos
                    if ($sentencia->execute()) {
                        header("Location: index.php");
                    }
            
        }catch (PDOException $PdoE) {
            //Capturamos la excepcion en caso de que se produzca un error,mostramos el mensaje de error y deshacemos la conexion
            echo($PdoE->getMessage());
            unset($db);
        }
       
        ?>