<?php
require('../../conexion/conexion.php');
session_start();
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $conn = conectar();
    $query = "SELECT * FROM Usuario";
    $result = sqlsrv_query($conn, $query) or die(sqlsrv_errors());

    $contador = 0;
    $data = array();

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $array_devolver = array();
        $array_devolver['IdUsuario'] = $row['IdUsuario'];
        $array_devolver['Nombre'] = $row['Nombre'];
        $array_devolver['NombreUsuario'] = $row['NombreUsuario'];
        $array_devolver['Correo'] = $row['Correo'];
        $array_devolver['Contrasena'] = $row['Contrasena'];
        $array_devolver['App'] = (int)$row['App'];
        $array_devolver['estado'] = (int)$row['estado'];

        $data['data'][$contador] = $array_devolver;
        $contador++;
    }

    // Don't print_r($row); here, it's unnecessary

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} else {
    header("refresh:1; url=../page_404.html");
    die();
}
?>
