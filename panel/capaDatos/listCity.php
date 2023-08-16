<?php
require('../../conexion/conexion.php');
session_start();
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Establecer la conexión usando la función conectar() definida en 'conexion.php'
    $conn = conectar();

    $query = "SELECT * FROM Ciudad";
    $result = sqlsrv_query($conn, $query) or die(sqlsrv_errors());

    $contador = 0;
    $data = array();

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $array_devolver = array();
        $array_devolver['IdCiudad'] = $row['IdCiudad'];
        $array_devolver['Nombre'] = $row['Nombre'];
        $array_devolver['Estado'] = $row['Estado'];
    
        $data['data'][$contador] = $array_devolver;
        $contador++;
    }

    // Cierra la conexión después de usarla
    sqlsrv_close($conn);

    echo json_encode($data, JSON_UNESCAPED_UNICODE);

} else {
    header("refresh:1; url=../page_404.html");
    die();
}
?>
