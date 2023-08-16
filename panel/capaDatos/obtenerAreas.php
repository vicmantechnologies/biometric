<?php
require('../../conexion/conexion.php');
session_start();

if (!isset($_GET['sede']) || empty($_GET['sede'])) {
    die('No se proporcionó el Id de la sede.');
}
$SedeId = intval($_GET['sede']);

$conn = conectar();
$areas = array();

// Consulta para obtener las empresas relacionadas con la sede seleccionada
$query = "SELECT a.IdArea, a.Nombre AS NombreArea, a.Estado FROM Areas a INNER JOIN Sede s ON s.IdSede = a.IdSede WHERE s.IdSede = ?";
$params = array($SedeId);

// Ejecutar la consulta
$resultado = sqlsrv_query($conn, $query, $params);

if ($resultado === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Recorrer los resultados y almacenar las empresas en un arreglo
while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
    $areas[] = $fila;
}

// Devolver las areas en formato JSON
echo json_encode($areas);
?>
