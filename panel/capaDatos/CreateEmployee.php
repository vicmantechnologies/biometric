<?php
require('../../conexion/conexion.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = conectar();
    $array_devolver = array(); // Crear un array para almacenar la respuesta

    if ($_POST['idEmployee'] != "0") {
       
        $result = sqlsrv_query($conn, "UPDATE Empleados SET Nombres='".$_POST['name']."', Apellidos='".$_POST['ape']."', Documento='" .$_POST['identidy']."', IdEmpresa='" .$_POST['empresa']."', IdSede='".$_POST['sede']."', idArea='".$_POST['area']."', Estado='".$_POST['estado']."' WHERE IdEmpleado='".$_POST['idEmployee']."'");


        if ($result) {
            $array_devolver['mensaje'] = 'Empleado actualizado';
            $array_devolver['resultado'] = '1';
        } else {
            $error_message = sqlsrv_errors();
            if ($error_message !== null) {
                $array_devolver['mensaje'] = 'Error al actualizar el empleado: ' . $error_message[0]['message'];
            } else {
                $array_devolver['mensaje'] = 'No es posible modificar el empleado';
            }
            $array_devolver['resultado'] = '0';
        } }

    echo json_encode($array_devolver, JSON_UNESCAPED_UNICODE);
} else {
    header("refresh:1; url=../page_404.html");
    die();
}
?>
