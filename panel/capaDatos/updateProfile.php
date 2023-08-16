<?php
require('../../conexion/conexion.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = conectar();

    // Use prepared statements to update data securely
    $query = "UPDATE Usuario SET NombreUsuario=?, Correo=? WHERE IdUsuario=?";
    $params = array($_POST['nickname'], $_POST['correo'], $_SESSION['userId']);
    $result = sqlsrv_query($conn, $query, $params);

    if ($result) {
        // Update the session variables with the new values
        $_SESSION['NombreUsuario'] = $_POST['nickname'];
        $_SESSION['username'] = $_POST['correo'];

        // Prepare the response data with the updated values
        $array_devolver['mensaje'] = '¡Todo se realizo con Exito!';
        $array_devolver['resultado'] = '1';
        $array_devolver['newNickname'] = $_POST['nickname'];
        $array_devolver['newCorreo'] = $_POST['correo'];
    } else {
        $array_devolver['mensaje'] = 'Actualización Fallida';
        $array_devolver['resultado'] = '0';
    }

    // Return the response as JSON
    echo json_encode($array_devolver);
}
?>
