<?php
    // Inicia la sesión
    session_start();

    // Destruye todas las variables de sesión
    session_destroy();

    // Elimina la cookie 'email'
    setcookie('email', '', time() - 3600, "/"); // Establece la cookie con un tiempo negativo para eliminarla

    // Redirige al usuario a la página de inicio de sesión
    header("Location: ../Login.php");
    exit();
?>