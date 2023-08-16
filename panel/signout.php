<?php
session_start();

// Borra todas las variables de sesión
session_unset();

// Destruye la sesión actual
session_destroy();

// Borra las cookies de sesión
// joel.gonzalez@holdingvml.net
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Redirige a la página de inicio o donde desees que vaya después de cerrar sesión
header("Refresh: 5; URL=../registerCity.php");
exit();
?>

