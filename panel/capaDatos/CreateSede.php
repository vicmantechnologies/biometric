<?php
require('../../conexion/conexion.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $conn = conectar();
    $array_devolver = array(); // Crear un array para almacenar la respuesta

    if ($_POST['id'] == "0") {
       
        $result = sqlsrv_query($conn, "INSERT INTO Sede (Nombre, IdEmpresa, IdCiudad, Estado, IdUsuario) VALUES ('".$_POST['sede']."','" .$_POST['empresa']."', '".$_POST['ciudad']."', '1', '".$_SESSION['userId']."')");

        if ($result) {
            $array_devolver['mensaje'] = 'Sede Registrada';
            $array_devolver['resultado'] = '1';
        } else {
            $error_message = sqlsrv_errors();
            if ($error_message !== null) {
                $array_devolver['mensaje'] = 'Error al crear la sede: ' . $error_message[0]['message'];
            } else {
                $array_devolver['mensaje'] = 'No es posible crear la sede';
            }
            $array_devolver['resultado'] = '0';
        } } else {
            $result = sqlsrv_query($conn, "UPDATE Sede SET Nombre='".$_POST['sede']."', IdEmpresa='" .$_POST['empresa']."', IdCiudad='".$_POST['ciudad']."', Estado='".$_POST['estado']."' WHERE IdSede='".$_POST['id']."'");
            if($result)
            {
                $array_devolver['mensaje'] ='Sede Actualizado!';
                $array_devolver['resultado'] = '1';
            }
            else
            {
                $array_devolver['mensaje'] ='Actualización Fallida';
                $array_devolver['resultado'] ='0';
            }
        }

    echo json_encode($array_devolver, JSON_UNESCAPED_UNICODE);
} else {
    header("refresh:1; url=../page_404.html");
    die();
}
?>
