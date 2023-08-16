<?php
require('../../conexion/conexion.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = conectar();
    $array_devolver = array();
    if ($_POST['idUser'] == "0") {
                // Crear el usuario
                $query = sqlsrv_query($conn, "INSERT INTO Usuario (Nombre, NombreUsuario, Contrasena, Correo, FechaCreacion, App, estado) VALUES ('".$_POST['name']."', '".$_POST['user']."', '".$_POST['pass']."', '".$_POST['correo']."', GETDATE(), '".$_POST['permiso']."', 1)");
    

                if ($query) {
                    $array_devolver['mensaje'] = 'Usuario registrado';
                    $array_devolver['resultado'] = '1';
                } else {
                    $error_message = sqlsrv_errors();
                    if ($error_message !== null) {
                        $array_devolver['mensaje'] = 'Error al crear el usuario: ' . $error_message[0]['message'];
                    } else {
                        $array_devolver['mensaje'] = 'No es posible crear el usuario';
                    }
                    $array_devolver['resultado'] = '0';
                }
 
    } else {
       // Actualizar el usuario
       $query = sqlsrv_query($conn, "UPDATE Usuario SET Nombre = '".$_POST['name']."', NombreUsuario = '".$_POST['user']."', Correo = '".$_POST['correo']."', App = '".$_POST['permiso']."', estado = '".$_POST['estado']."' WHERE IdUsuario = '".$_POST['idUser']."' ");

       if ($query) {
           $array_devolver['mensaje'] = 'Usuario actualizado';
           $array_devolver['resultado'] = '1';
       } else {
           $error_message = sqlsrv_errors();
           if ($error_message !== null) {
               $array_devolver['mensaje'] = 'Error al realizar la operación: ' . $error_message[0]['message'];
           } else {
               $array_devolver['mensaje'] = 'No es posible realizar la operación';
           }
           $array_devolver['resultado'] = '0';
       }
    }

    echo json_encode($array_devolver, JSON_UNESCAPED_UNICODE);
} else {
    header("HTTP/1.1 404 Not Found");
    exit();
}

?>