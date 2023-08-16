<?php

require('../../conexion/conexion.php');
session_start();
header('Content-Type: text/html; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        // Establecer la conexión utilizando la función conectar()
        $conn = conectar();

        // Consulta SQL para Ciudad con parámetros
        $sql = "SELECT * FROM Ciudad WHERE Estado = ? ORDER BY Nombre";
        $params = array(1); // Valor del parámetro Estado = 1
        $result = ejecutarConsulta($sql, $params);
        $data = array('ciudad' => $result);

        // Consulta SQL para Empresas con parámetros
        $sql2 = "SELECT * FROM Empresa WHERE Estado = ? ORDER BY Nombre";
        $params2 = array(1); // Valor del parámetro Estado = 1
        $result2 = ejecutarConsulta($sql2, $params2);
        $data['Empresas'] = $result2;

        $sql3 = "SELECT Sede.*, Empresa.Nombre AS NombreEmpresa FROM Sede
        INNER JOIN Empresa ON Sede.IdEmpresa = Empresa.IdEmpresa
        WHERE Sede.Estado = ? ORDER BY Sede.Nombre";
        $params3 = array(1); // Valor del parámetro Estado = 1
        $result3 = ejecutarConsulta($sql3, $params3);
        $data['Sede'] = $result3;

        // Consulta SQL para Areas con parámetros
        $sql4 = "SELECT * FROM Areas WHERE Estado = ? ORDER BY Nombre";
        $params4 = array(1); // Valor del parámetro Estado = 1
        $result4 = ejecutarConsulta($sql4, $params4);
        $data['Areas'] = $result4;

        // Consulta SQL para obtener todas las empresas sin parámetros
        $sql5 = "SELECT * FROM Empresa ORDER BY Nombre";
        $params5 = array(); // Array vacío ya que no hay parámetros
        $result5 = ejecutarConsulta($sql5, $params5);
        $data['Empre'] = $result5;

        // Step 4: Enable JSON Encoding Errors
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_ERROR_UTF8);
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("refresh:1; url=../page_404.html");
    die();
}
?>