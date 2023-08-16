<?php
require('../../conexion/conexion.php');
session_start();
// Verificar si se recibió un ID válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $conn = conectar();
    $idSede = $_GET['id'];

    $query = "SELECT * FROM Sede WHERE IdSede = $idSede";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        // Obtener los datos de la sede
        $sede = $result->fetch_assoc();

        $response = [
            'resultado' => 1,
            'sede' => $sede
        ];
    } else {
        $response = [
            'resultado' => 0,
            'mensaje' => 'No se encontraron datos de la sede'
        ];
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
 else {
$response = [
    'resultado' => 0,
    'mensaje' => 'ID de sede inválido'
];
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
