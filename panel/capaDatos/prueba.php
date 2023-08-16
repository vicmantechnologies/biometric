<?php
session_start();
require('../../conexion/conexion.php');


$conn = conectar();

$empresa = $_GET['empresa'];
$fechaInicio = $_GET['fechaInicio'];
$fechaFin = $_GET['fechaFin'];
$datos = [
    ["Nombre1", "Apellido1", "Edad1", "email1@example.com"],
    ["Nombre2", "Apellido2", "Edad2", "email2@example.com"],
    ["Nombre3", "Apellido3", "Edad3", "email3@example.com"]
];

// Convertir los datos a formato JSON
$jsonDatos = json_encode($datos);

echo $jsonDatos;
?>