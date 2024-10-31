<?php
include_once __DIR__ . '/Convocatoria.php';

$convocatoria = new Convocatoria();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['announcement_id'])) {
        $announcementId = $_POST['announcement_id'];

        $resultado = $convocatoria->abrirConvocatoria($announcementId);

        if ($resultado) {
            header("Location: ../verConvocatoriaJefe.php");
            exit();
        } else {
            echo "Error: No se pudo abrir la convocatoria.";
        }
    } else {
        echo "Error: ID de convocatoria no proporcionado.";
    }
} else {
    echo "Acción no permitida.";
}
?>
