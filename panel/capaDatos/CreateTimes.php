<?php
require('../../conexion/conexion.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = conectar();

    $array_devolver = array();

    if ($_POST['id'] == "0") {
        $result = sqlsrv_query($conn, "INSERT INTO Horario (HorarioApertura, HorarioCierre, Estado) VALUES ('".$_POST['horarioApertura']."', '".$_POST['horarioCierre']."', '1')");

        if ($result) {
            $array_devolver['mensaje'] = 'Horario Registrado';
            $array_devolver['resultado'] = '1';
        } else {
            $error_message = sqlsrv_errors();
            if ($error_message !== null) {
                $array_devolver['mensaje'] = 'Error al crear la horarios: ' . $error_message[0]['message'];
            } else {
                $array_devolver['mensaje'] = 'No es posible crear el horario';
            }
            $array_devolver['resultado'] = '0';
        }
    } else {
        $result = sqlsrv_query($conn, "UPDATE Horario SET HorarioApertura='".$_POST['horarioApertura']."', HorarioCierre='".$_POST['horarioCierre']."',  Estado='".$_POST['estado']."' WHERE IdHorario='".$_POST['id']."'");

        if ($result) {
            $array_devolver['mensaje'] = 'Horario Actualizada';
            $array_devolver['resultado'] = '1';
        } else {
            $error_message = sqlsrv_errors();
            if ($error_message !== null) {
                $array_devolver['mensaje'] = 'Error al actualizar la horario: ' . $error_message[0]['message'];
            } else {
                $array_devolver['mensaje'] = 'No es posible actualizar la horario';
            }
            $array_devolver['resultado'] = '0';
        }
    }

    echo json_encode($array_devolver, JSON_UNESCAPED_UNICODE);
} else {
    header("refresh:1; url=../page_404.html");
    die();
}
?>
