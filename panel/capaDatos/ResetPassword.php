<?php
require('../../conexion/conexion.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = conectar();

    $array_devolver = array();

    $userId = $_POST['userId'];
    $newPassword = $_POST['newPassword'];

    $query = "UPDATE Usuario SET Contrasena = ? WHERE IdUsuario = ?";
    $params = array($newPassword, $userId);

    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        $array_devolver['mensaje'] = 'Contraseña restablecida correctamente';
        $array_devolver['resultado'] = '1';
    } else {
        $error_message = sqlsrv_errors();
        if ($error_message !== null) {
            $array_devolver['mensaje'] = 'Error al restablecer la contraseña: ' . $error_message[0]['message'];
        } else {
            $array_devolver['mensaje'] = 'No es posible restablecer la contraseña';
        }
        $array_devolver['resultado'] = '0';
    }

    echo json_encode($array_devolver, JSON_UNESCAPED_UNICODE);
} else {
    header("HTTP/1.1 404 Not Found");
    exit();
}
?>
