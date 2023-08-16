<?php
require('../../conexion/conexion.php');
session_start();
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sedeIds = $_GET['idSede'];
    $empresa = $_GET['idEmpresa'];
    $areas = isset($_GET['idArea']) ? $_GET['idArea'] : ''; // Recibir como un string con áreas separadas por comas o cadena vacía
    $fechaInicio = $_GET['fechainicio'];
    $fechaFin = $_GET['fechafin'];

    $conn = conectar();

    $query = "SELECT Registro.cedula, Empleados.Nombres AS NomEmpleados, Empleados.Apellidos AS ApeEmpleados, Registro.fechaRegistro, Registro.horaRegistro, Sede.Nombre AS NombreSede, EmpleadosEmpresa.Nombre AS NombreEmpresa, Areas.Nombre AS NombreArea 
    FROM Registro 
    INNER JOIN Sede ON Registro.IdSede = Sede.IdSede
    INNER JOIN Empleados ON Registro.Cedula = Empleados.Documento
    INNER JOIN Areas ON Empleados.idArea = Areas.IdArea
    INNER JOIN Empresa AS EmpleadosEmpresa ON Empleados.IdEmpresa = EmpleadosEmpresa.IdEmpresa
    WHERE Registro.fechaRegistro BETWEEN CONVERT(DATE, '$fechaInicio', 126) AND CONVERT(DATE, '$fechaFin', 126) AND Empleados.IdSede = $sedeIds AND Empleados.IdEmpresa = $empresa";

    // Agregar la condición para filtrar por las áreas seleccionadas, solo si hay áreas seleccionadas
    if (!empty($areas)) {
        $query .= " AND Empleados.idArea IN ($areas)";
    }

    $query .= " ORDER BY Registro.fechaRegistro DESC, Registro.horaRegistro DESC";
    $result = sqlsrv_query($conn, $query) or die(sqlsrv_errors());

    $contador = 0;
    $data = array();

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
