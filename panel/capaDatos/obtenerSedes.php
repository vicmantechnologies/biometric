<?php
require('../../conexion/conexion.php');
session_start();
if (!isset($_GET['empresa']) || empty($_GET['empresa'])) {
    die('No se proporcionó el Id de la sede.');
}
$EmpresaId = intval($_GET['empresa']);
$conn = conectar();


$sedes = array();

// Consulta para obtener las empresas relacionadas con la sede seleccionada
$query = "SELECT s.IdSede, s.Nombre AS NombreSede FROM Sede s INNER JOIN Empresa e ON e.IdEmpresa = s.IdEmpresa WHERE e.IdEmpresa = ?";
$params = array($EmpresaId);

// Ejecutar la consulta
$resultado = sqlsrv_query($conn, $query, $params);

if ($resultado === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Recorrer los resultados y almacenar las empresas en un arreglo
while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
    $sedes[] = $fila;
}

// Devolver las sedes en formato JSON
echo json_encode($sedes);
?>
