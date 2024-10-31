<?php
require_once __DIR__ . '/Convocatoria.php';

$convocatoria = new Convocatoria();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge los datos del formulario de la convocatoria
    $datosConvocatoria = $_POST;

    // Verificar que todos los campos requeridos estén presentes
    if (empty($datosConvocatoria['workArea']) || empty($datosConvocatoria['salary']) ||
        empty($datosConvocatoria['modality']) || empty($datosConvocatoria['timeWork']) ||
        empty($datosConvocatoria['vacancies']) || empty($datosConvocatoria['hiringProcess']) ||
        empty($datosConvocatoria['selectionCriteria']) || empty($datosConvocatoria['notifyApplicant']) ||
        empty($datosConvocatoria['dateStart']) || empty($datosConvocatoria['dateLimit']) ||
        empty($datosConvocatoria['dateInterview']) || empty($datosConvocatoria['dateAnnouncement'])) {
        
        // Retorna error si faltan campos
        header('Location: ../abrirConvocatoria.php?error=campos');
        exit();
    }

    // Intenta guardar la convocatoria y verifica si ya existe
    $resultado = $convocatoria->guardarConvocatoria($datosConvocatoria);

    if ($resultado === 'duplicada') {
        // Redirige con un parámetro indicando que es duplicada
        header('Location: ../abrirConvocatoria.php?error=duplicada');
        exit();
    } elseif ($resultado === true) {
        // Redirige a la creación de cuestionario si no hay duplicado
        header('Location: ../crearCuestionario.html?id=' . $convocatoria->getLastInsertedId());
        exit();
    } else {
        // En caso de otro tipo de error
        header('Location: ../abrirConvocatoria.php?error=general');
        exit();
    }
}
?>
