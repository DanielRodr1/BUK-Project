<?php 
require_once __DIR__ . '/Convocatoria.php';

$convocatoria = new Convocatoria();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
    $idconvocatoria = isset($_POST['idannouncement']) ? $_POST['idannouncement'] : '';
    $totalPoints = isset($_POST['totalPoints']) ? $_POST['totalPoints'] : '';

    // Verificar que los datos no estén vacíos
    if (empty($email) || empty($idconvocatoria) || empty($totalPoints)) {
        echo "Error: faltan datos para guardar la postulación.";
        exit();
    }

    // Llamar al método para guardar la postulación sin crear una nueva convocatoria
    if ($convocatoria->guardarUsuarioConvocatoria($email, $idconvocatoria, $totalPoints)) {
        header('Location: ../verConvocatoriaPostulante.php');
        exit();
    } else {
        echo "Error al guardar la postulación.";
    }
}
?>