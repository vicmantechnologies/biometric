<?php
require('../../conexion/conexion.php');
session_start();
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $conn = conectar();

    $query = "SELECT E.*, S.Nombre AS NomSede, Em.Nombre AS NomEmpre, A.Nombre AS NomArea
    FROM Empleados E
    INNER JOIN Empresa Em ON Em.IdEmpresa = E.IdEmpresa
    INNER JOIN Sede S ON S.IdSede = E.IdSede
    INNER JOIN Areas A ON A.IdArea = E.IdArea ORDER BY IdEmpleado ASC;
    ";
    $result = sqlsrv_query($conn, $query) or die(sqlsrv_errors());

    $contador = 0;
    $data = array();

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $array_devolver = array();
        $array_devolver['IdEmpleado'] = $row['IdEmpleado'];
        $array_devolver['Nombres'] = $row['Nombres'];
        $array_devolver['Documento'] = $row['Documento'];
        $array_devolver['IdEmpresa'] = $row['IdEmpresa'];
        $array_devolver['NomEmpre'] = $row['NomEmpre'];
        $array_devolver['IdSede'] = $row['IdSede'];
        $array_devolver['NomSede'] = $row['NomSede'];
        $array_devolver['idArea'] = $row['idArea'];
        $array_devolver['NomArea'] = $row['NomArea'];
        $array_devolver['Estado'] = (int)$row['Estado'];
        $array_devolver['Apellidos'] = $row['Apellidos'];
    
        $data['data'][$contador] = $array_devolver;
        $contador++;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} else {
    header("refresh:1; url=../page_404.html");
    die();
}
?>
