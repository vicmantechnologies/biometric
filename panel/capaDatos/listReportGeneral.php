<?php
require('../../conexion/conexion.php');
session_start();
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $fechaInicio = $_GET['fechainicio'];
    $fechaFin = $_GET['fechafin'];

    $conn = conectar();



    $query = "SELECT Registro.cedula, Empleados.Nombres AS NomEmpleados, Empleados.Apellidos AS ApeEmpleados, Registro.fechaRegistro, Registro.horaRegistro, Sede.Nombre AS NombreSede, Empresa.Nombre AS NombreEmpresa, Areas.Nombre AS NombreArea 
    FROM Registro 
    INNER JOIN Sede ON Registro.IdSede = Sede.IdSede
    INNER JOIN Empleados ON Registro.Cedula = Empleados.Documento
    INNER JOIN Areas ON Empleados.idArea = Areas.IdArea
    INNER JOIN Empresa ON Registro.IdEmpresa = Empresa.IdEmpresa
    WHERE Registro.fechaRegistro BETWEEN CONVERT(DATE, '$fechaInicio', 126) AND CONVERT(DATE, '$fechaFin', 126)";

    $query .= " ORDER BY Registro.fechaRegistro DESC, Registro.horaRegistro DESC";

    // Utilizar sentencias preparadas para evitar inyección SQL
    $result = sqlsrv_query($conn, $query) or die(sqlsrv_errors());

    $data = array();

    // Obtener los datos y agregarlos al array $data
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $array_devolver = array();
        $array_devolver['cedula'] = $row['cedula'];
        $array_devolver['NomEmpleados'] = $row['NomEmpleados'];
        $array_devolver['ApeEmpleados'] = $row['ApeEmpleados'];
        $array_devolver['NombreSede'] = $row['NombreSede'];
        $array_devolver['NombreEmpresa'] = $row['NombreEmpresa'];
        $array_devolver['NombreArea'] = $row['NombreArea'];
        $array_devolver['horaRegistro'] = $row['horaRegistro'];
        $array_devolver['fechaRegistro'] = $row['fechaRegistro']->format('Y-m-d');
        $data[] = $row;
    }

    // Cierra la conexión después de usarla
    sqlsrv_close($conn);

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} else {
    header("refresh:1; url=../page_404.html");
    die();
}
?>