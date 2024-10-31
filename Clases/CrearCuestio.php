<?php


require_once __DIR__ . '/Clases/Convocatoria.php'; // Asegúrate de poner la ruta correcta

// Ejemplo de uso:
$convocatoria = new Convocatoria();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge los datos del formulario
    $datosConvocatoria = $_POST;

    // Guarda la convocatoria
    if ($convocatoria->guardarConvocatoria(datosConvocatoria: $datosConvocatoria)) {
        header("Location: ../crearCuestionario.html");
    } else {
        echo "Error al guardar la convocatoria.";
    }
}


