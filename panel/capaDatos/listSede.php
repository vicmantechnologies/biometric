<?php
require('../../conexion/conexion.php');
session_start();
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
 
    $conn = conectar();
    $query = "SELECT S.*, C.Nombre AS NombreCiudad, E.Nombre AS NombreEmpresa
    FROM Sede S
    INNER JOIN Ciudad C ON C.IdCiudad = S.IdCiudad
    INNER JOIN Empresa E ON E.IdEmpresa = S.IdEmpresa
    ";
    $result = sqlsrv_query($conn, $query) or die(sqlsrv_errors());

    $data = array();
    $contador = 0;

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $array_devolver = array();
        $array_devolver['IdSede'] = $row['IdSede'];
        $array_devolver['IdCiudad'] = $row['NombreCiudad'];
        $array_devolver['IdEmpresa'] = $row['NombreEmpresa'];
        $array_devolver['NombreCiudad'] = $row['IdCiudad'];
        $array_devolver['NombreEmpresa'] = $row['IdEmpresa'];
        $array_devolver['Nombre'] = $row['Nombre'];
        $array_devolver['Estado'] = $row['Estado'];

        $data['data'][$contador] = $array_devolver;
        $contador++;
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} else {
    header("HTTP/1.1 400 Bad Request");
    echo "Petición no válida";
}
