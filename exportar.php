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
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "config/configDpto.php";
try {
    //Creamos la conexion a la base de datos
    $db = new PDO(DATOSCONEXION, USER, PASSWORD);
    //Definición de los atributos para lanzar una excepcion si se produce un error
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $xml = new DomDocument('1.0', 'UTF-8');
    //agregamos la etiqueta raiz departamentos
    $raiz = $xml->createElement('departamentos');
    $raiz = $xml->appendChild($raiz);

    $consulta = "SELECT * FROM Departamento";
    //Preparamos la sentencia
    $sentencia = $db->prepare($consulta);
    //La ejecutamos
    $sentencia->execute();
    
    while ($fila = $sentencia->fetch(PDO::FETCH_OBJ)) {
        $departamento = $xml->createElement('Departamento');
        $departamento = $raiz->appendChild($departamento);
        $codigo = $xml->createElement('CodDepartamento', $fila->CodDepartamento);
        $codigo = $departamento->appendChild($codigo);

        $descripcion = $xml->createElement('DescDepartamento', $fila->DescDepartamento);
        $descripcion = $departamento->appendChild($descripcion);
    }

    $xml->formatOutput = true;  //poner los string en la variable $strings_xml:
    $strings_xml = $xml->saveXML();
    $fecha = new DateTime();
    $rutaFichero = 'xml/Departamentos' . $fecha->getTimestamp() . '.xml';
    $xml->save($rutaFichero);
    echo "Fichero xml guardado en $rutaFichero<br /><br />";
    echo "<a  class='w3-tag w3-padding w3-round-large w3-light-grey' style='text-decoration: none' href='mantenimiento.php'>Volver</a>";
    echo "</div>";
} catch (PDOException $PdoE) {
    //Capturamos la excepcion en caso de que se produzca un error,mostramos el mensaje de error y deshacemos la conexion
    echo($PdoE->getMessage());
    unset($db);
}
?>