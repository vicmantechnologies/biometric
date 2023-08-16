<?php
require('../../conexion/conexion.php');
session_start();
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
 // Establecer la conexión usando la función conectar() definida en 'conexion.php'
 $conn = conectar();
 
    $query = "SELECT * FROM Empresa";
    $result = sqlsrv_query($conn, $query) or die(sqlsrv_errors());

    $data = array();
    $contador = 0;

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $array_devolver = array();
        $array_devolver['IdEmpresa'] = $row['IdEmpresa'];
        $array_devolver['Nombre'] = $row['Nombre'];
        $array_devolver['Direccion'] = $row['Direccion'];
        $array_devolver['Telefono'] = $row['Telefono'];
        $array_devolver['Estado'] = $row['Estado'];

        $data['data'][$contador] = $array_devolver;
        $contador++;
    }
    print_r($row);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} else {
    header("HTTP/1.1 400 Bad Request");
    echo "Petición no válida";
}