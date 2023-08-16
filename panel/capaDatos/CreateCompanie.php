<?php
require('../../conexion/conexion.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $conn = conectar();
    $array_devolver = array(); // Crear un array para almacenar la respuesta

    if ($_POST['id'] == "0") {
       
        $result = sqlsrv_query($conn, "INSERT INTO Empresa (Nombre, Direccion, Telefono, Estado) VALUES ('".$_POST['nomEmpresa']."', '".$_POST['dirEmpresa']."', '".$_POST['telEmpresa']."', '".$_POST['estado']."')");

        if ($result) {
            $array_devolver['mensaje'] = 'Empresa Registrada';
            $array_devolver['resultado'] = '1';
        } else {
            $error_message = sqlsrv_errors();
            if ($error_message !== null) {
                $array_devolver['mensaje'] = 'Error al crear la empresa: ' . $error_message[0]['message'];
            } else {
                $array_devolver['mensaje'] = 'No es posible crear la empresa';
            }
            $array_devolver['resultado'] = '0';
        } }else {
            $result = sqlsrv_query($conn, "UPDATE Empresa SET Nombre='".$_POST['nomEmpresa']."', Direccion='".$_POST['dirEmpresa']."', Telefono='".$_POST['telEmpresa']."', Estado='".$_POST['estado']."' WHERE IdEmpresa='".$_POST['id']."'");


            if($result)
            {
                
                $array_devolver['mensaje'] ='Empresa Actualizado!';
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
