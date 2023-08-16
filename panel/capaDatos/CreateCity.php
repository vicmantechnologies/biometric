<?php
require('../../conexion/conexion.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = conectar();

    $array_devolver = array();

    if ($_POST['id'] == "0") {
        $result = sqlsrv_query($conn, "INSERT INTO Ciudad (Nombre, Estado) VALUES ('".$_POST['nomCiudad']."', '1')");

        if ($result) {
            $array_devolver['mensaje'] = 'Ciudad Registrada';
            $array_devolver['resultado'] = '1';
        } else {
            $error_message = sqlsrv_errors();
            if ($error_message !== null) {
                $array_devolver['mensaje'] = 'Error al crear la ciudad: ' . $error_message[0]['message'];
            } else {
                $array_devolver['mensaje'] = 'No es posible crear la ciudad';
            }
            $array_devolver['resultado'] = '0';
        }
    } else {
        $result = sqlsrv_query($conn, "UPDATE Ciudad SET Nombre='".$_POST['nomCiudad']."',  Estado='".$_POST['estado']."' WHERE IdCiudad='".$_POST['id']."'");

        if ($result) {
            $array_devolver['mensaje'] = 'Ciudad Actualizada';
            $array_devolver['resultado'] = '1';
        } else {
            $error_message = sqlsrv_errors();
            if ($error_message !== null) {
                $array_devolver['mensaje'] = 'Error al actualizar la ciudad: ' . $error_message[0]['message'];
            } else {
                $array_devolver['mensaje'] = 'No es posible actualizar la ciudad';
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
