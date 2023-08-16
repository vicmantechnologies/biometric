<?php
require('../../conexion/conexion.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $conn = conectar();

    $query = "SELECT * FROM Horario";
    $result = sqlsrv_query($conn, $query) or die(sqlsrv_errors());

    $data = array();
    $contador = 0;

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $array_devolver = array();
        $array_devolver['IdHorario'] = $row['IdHorario'];
       $array_devolver['HorarioApertura'] = $row['HorarioApertura']->format('H:i');
    $array_devolver['HorarioCierre'] = $row['HorarioCierre']->format('H:i');

        $array_devolver['Estado'] = $row['Estado'];

        $data['data'][$contador] = $array_devolver;
        $contador++;
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} else {
    header("HTTP/1.1 400 Bad Request");
    echo "Petición no válida";
}
