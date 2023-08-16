<?php
require('../../conexion/conexion.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $conn = conectar();
    // Recibir los datos enviados desde el frontend
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $renewPassword = $_POST['renewPassword'];

    // Obtener el ID del usuario actualmente autenticado (asegúrate de que esto se ajuste a cómo identificas al usuario en tu aplicación)
    $userId = $_SESSION['userId'];

    // Validar que los campos no estén vacíos y que la nueva contraseña coincida con la confirmación de la nueva contraseña
    if (empty($currentPassword) || empty($newPassword)) {
        $response = array(
            'resultado' => 0,
            'mensaje' => 'Por favor, complete todos los campos.',
        );
    } elseif ($newPassword !== $renewPassword) {
        $response = array(
            'resultado' => 0,
            'mensaje' => 'La nueva contraseña y la confirmación no coinciden.',
        );
    } else {
        // Verificar que la contraseña actual proporcionada por el usuario coincida con la almacenada en la base de datos para el usuario específico
        $query = "SELECT Contrasena FROM Usuario WHERE IdUsuario = ?";
        $params = array($userId);

        $stmt = sqlsrv_query($conn, $query, $params);
        if ($stmt === false) {
            $response = array(
                'resultado' => 0,
                'mensaje' => 'Error en la consulta de la contraseña actual.',
            );
        } else {
            $row = sqlsrv_fetch_array($stmt);
            $storedPassword = $row['Contrasena'];
    
            // Verificar si la contraseña actual ingresada coincide con la almacenada en la base de datos
            if ($storedPassword !== $currentPassword) {
                $response = array(
                    'resultado' => 0,
                    'mensaje' => 'La contraseña actual ingresada no coincide con la almacenada en la base de datos.',
                );
            } else {
                // Contraseña actual correcta, continuar con el cambio de contraseña
                if ($newPassword === $renewPassword) {
                    // Generar el hash de la nueva contraseña (puedes agregar aquí el proceso de hash, en este caso, se asume que la contraseña ya viene hasheada)
                    $hashedNewPassword = $newPassword;
    
                    // Actualizar la contraseña en la base de datos
                    $updateQuery = "UPDATE Usuario SET Contrasena = ? WHERE IdUsuario = ?";
                    $updateParams = array($hashedNewPassword, $userId);
    
                    $updateStmt = sqlsrv_query($conn, $updateQuery, $updateParams);
                    if ($updateStmt === false) {
                        $response = array(
                            'resultado' => 0,
                            'mensaje' => 'Error al actualizar la contraseña.',
                        );
                    } else {
                        $response = array(
                            'resultado' => 1,
                            'mensaje' => 'Contraseña actualizada correctamente.',
                        );
                    }
                } else {
                    $response = array(
                        'resultado' => 0,
                        'mensaje' => 'La nueva contraseña y la confirmación no coinciden.',
                    );
                }
            }
        }
    }

    // Devolver la respuesta al frontend en formato JSON
    echo json_encode($response);

    // Cerrar la conexión a la base de datos
    sqlsrv_close($conn);
}
?>
