<?php
require('../conexion/conexion.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conectar();
    $username = $_POST["username"];
    $password = $_POST["password"];
    var_dump($username, $password);
    // Consulta SQL para verificar las credenciales y el estado del usuario
    $query = "SELECT * FROM Usuario WHERE Correo = ? AND Contrasena = ?";
    $params = array($username, $password); // Agregar los parámetros aquí
    $result = ejecutarConsulta($query, $params);

    // Verificar si se encontró un usuario con las credenciales proporcionadas
    if (!empty($result)) {
        // Obtener el primer registro (asumiendo que solo hay un usuario con las mismas credenciales)
        $row = $result[0];
        $userId = $row['IdUsuario'];
        $nombre = $row['Nombre'];
        $nameUser = $row['NombreUsuario'];
        $estadoUser = $row['estado'];

        // Verificar el estado del usuario
        if ($estadoUser == 1) {
            // Guardar la información en las variables de sesión
            $_SESSION['userId'] = $userId;
            $_SESSION['username'] = $username;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['NombreUsuario'] = $nameUser;
            $_SESSION['isAuthenticated'] = true;
            header("Location: registerCity.php");
            exit();
        } else {
            // Usuario inactivo
            header("Location: ../index.php?estado=0");
            exit();
        }
    } else {
        // Credenciales incorrectas
        header("Location: ../index.php?error=1&username=" . urlencode($username));
        exit();
    }

    // ...

} else {
    header("Location: ../index.php?estado=0");
    exit();
}
?>
