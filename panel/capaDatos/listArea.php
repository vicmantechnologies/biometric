<?php
require('../../conexion/conexion.php');
session_start();
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $conn = conectar();

    $query = "SELECT A.IdArea AS Id, A.Nombre AS NombreArea, A.Estado, S.Nombre AS NombreSede, S.IdSede, E.Nombre AS NombreEmpresa FROM Areas A
    INNER JOIN Sede S ON S.IdSede = A.IdSede
    INNER JOIN Empresa E ON E.IdEmpresa = S.IdEmpresa ";
    $result = sqlsrv_query($conn, $query) or die(sqlsrv_errors());

    $contador = 0;
    $data = array();

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $array_devolver = array();
        $array_devolver['Id'] = $row['Id'];
        $array_devolver['NombreArea'] = $row['NombreArea'];
        $array_devolver['IdSede'] = $row['IdSede'];
        $array_devolver['NombreSede'] = $row['NombreSede'];
        $array_devolver['NombreEmpresa'] = $row['NombreEmpresa'];
        $array_devolver['Estado'] = $row['Estado'];
    
        $data['data'][$contador] = $array_devolver;
        $contador++;
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} else {
    header("refresh:1; url=../page_404.html");
    die();
}
?>
