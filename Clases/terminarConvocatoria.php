<?php
include_once __DIR__ . '/Convocatoria.php';

$convocatoria = new Convocatoria();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si el ID de la convocatoria está presente en la solicitud
    if (!empty($_POST['announcement_id'])) {
        $announcementId = $_POST['announcement_id'];

        // Llamar a la función para finalizar la convocatoria
        $resultado = $convocatoria->terminarConvocatoria($announcementId);

        if ($resultado) {
            // Redirigir de nuevo a la página de convocatorias del jefe
            header("Location: ../verConvocatoriaJefe.php");
            exit();
        } else {
            echo "Error: No se pudo finalizar la convocatoria.";
        }
    } else {
        echo "Error: ID de convocatoria no proporcionado.";
    }
} else {
    echo "Acción no permitida.";
}
?>
