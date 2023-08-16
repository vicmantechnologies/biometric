<?php
session_start();

define('DB_HOST', '181.204.235.27');
define('DB_USER', 'sa');
define('DB_PASS', 'VmL2021*');
define('DB_NAME', 'DataBaseRegistros_p');

function conectar()
{
    $options = array(
        "UID" => DB_USER,
        "PWD" => DB_PASS,
        "Database" => DB_NAME,
        "CharacterSet" => "UTF-8"
    );

    $conn = sqlsrv_connect(DB_HOST, $options);
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true)); // Mostrar errores de conexión, si los hay
    }

    return $conn;
}

// Ejecutar consulta
function ejecutarConsulta($sql, $params)
{
    $conn = conectar();

    // Preparar la consulta con los parámetros
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true)); // Mostrar errores de preparación, si los hay
    }

    // Ejecutar la consulta
    $result = sqlsrv_execute($stmt);

    if ($result === false) {
        die(print_r(sqlsrv_errors(), true)); // Mostrar errores de ejecución, si los hay
    }

    // Procesar el resultado y obtener los datos
    $data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }

    // Cerrar la conexión y liberar recursos
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

    return $data;
}
?>
