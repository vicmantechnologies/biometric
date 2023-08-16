<?php
require('../../conexion/conexion.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = conectar();
    $array_devolver = array();

    if ($_POST['id'] == "0") {
        $result = sqlsrv_query($conn, "INSERT INTO Areas (Nombre, Estado, IdSede) VALUES ('".$_POST['nomArea']."', '1', '".$_POST['sede']."')");

        if ($result) {
            $array_devolver['mensaje'] = 'Area Registrada';
            $array_devolver['resultado'] = '1';
        } else {
            $error_message = sqlsrv_errors();
            if ($error_message !== null) {
                $array_devolver['mensaje'] = 'Error al crear la Area: ' . $error_message[0]['message'];
            } else {
                $array_devolver['mensaje'] = 'No es posible crear la Area';
            }
            $array_devolver['resultado'] = '0';
        }
    } else {
        $result = sqlsrv_query($conn, "UPDATE Areas SET Nombre='".$_POST['nomArea']."',  Estado='".$_POST['estado']."', IdSede='".$_POST['sede']."' WHERE IdArea='".$_POST['id']."'");

        if ($result) {
            $array_devolver['mensaje'] = 'Area Actualizada';
            $array_devolver['resultado'] = '1';
        } else {
            $error_message = sqlsrv_errors();
            if ($error_message !== null) {
                $array_devolver['mensaje'] = 'Error al actualizar la Area: ' . $error_message[0]['message'];
            } else {
                $array_devolver['mensaje'] = 'No es posible actualizar la area';
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
