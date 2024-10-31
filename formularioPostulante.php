<?php
include_once __DIR__ . '/Clases/Convocatoria.php';
$convocatoria = new Convocatoria();

// Obtener el email del usuario desde la cookie
$email = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';

// Obtener el ID de la convocatoria desde la URL
$announcement_id = isset($_GET['idannouncement']) ? $_GET['idannouncement'] : 0;

// Verificar si el usuario ya se ha postulado
$yaPostulado = $convocatoria->verificarPostulacionExistente($email, $announcement_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-4C2PxmMj1ZI8Y+9H54uN5IsZ6pJuhtO5YOb7WnHk/KR4J6XGz9znTyy7RzyffsVH" crossorigin="anonymous"/>
    <link rel="stylesheet" href="public/css/formulario.css"/>
    <title>Formulario Postulante</title>
</head>
<body>
    
<nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="homePostulante.html">
                <img src="img/logo.png" alt="logo de la empresa" class="img-fluid logoEmpresa">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="homePostulante.html">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="verConvocatoriaPostulante.php">Convocatorias</a>
                    </li>
                </ul>
                
                <!-- Nuevo elemento con el ícono a la derecha -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form action="Clases/cerrarSesion.php" method="post" class="nav-link">
                            <button type="submit" class="btn btn-link">
                                <i class="far fa-user-circle fa-2x"></i> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-5">
        <?php if ($yaPostulado): ?>
            <!-- Mostrar el modal automáticamente -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modalYaPostulado = new bootstrap.Modal(document.getElementById('modalYaPostulado'));
                    modalYaPostulado.show();
                });
            </script>
        <?php else: ?>
            <!-- Aquí va el contenido del formulario si el usuario no se ha postulado -->
            <h1>Formulario de Postulación</h1>
            <?php 
                $preguntas = $convocatoria->obtenerPreguntas($announcement_id);

                $html = '<form action="Clases/GuardarUC.php" method="post" id="postulacionForm" onsubmit="return validateForm()">';
                $html .= '<div class="row">';

                foreach ($preguntas as $pregunta) {
                    $html .= '<div class="col-md-6">';
                    $html .= '<div class="preg">';
                    $html .= '<label for="pregunta' . $pregunta['question_id'] . '">' . $pregunta['question_text'] . '</label>';
                    $html .= '<select class="form-select" id="pregunta' . $pregunta['question_id'] . '" name="pregunta' . $pregunta['question_id'] . '" required>';
                    $html .= '<option value="0" selected>Seleccione su respuesta</option>';

                    // Agregar opciones (alternativas) al menú desplegable
                    foreach ($pregunta['alternativas'] as $alternativa) {
                        $html .= '<option value="' . $alternativa['points'] . '">' . $alternativa['alternative_text'] . '</option>';
                    }

                    $html .= '</select>';
                    $html .= '</div>';
                    $html .= '</div>';
                }

                $html .= '</div>';
                $html .= '<div id="errorMessage" class="error-message text-center" style="margin-top: 5px; font-weight: bolder; margin-bottom: 5px; color:red"></div>';
                $html .= '<input type="hidden" id="idannouncement" name="idannouncement" value="' . $announcement_id . '">';
                $html .= '<input type="hidden" id="totalPoints" name="totalPoints" value="">';
                $html .= '<button class="btn-send btn" type="submit">ENVIAR</button>';
                $html .= '</form>';

                echo $html;
            ?>   
        <?php endif; ?>
    </div>

    <!-- Modal para notificar que el usuario ya se ha postulado -->
    <div class="modal fade" id="modalYaPostulado" tabindex="-1" aria-labelledby="modalYaPostuladoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalYaPostuladoLabel">Aviso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Ya te has postulado a esta convocatoria.
                </div>
                <div class="modal-footer">
                    <a href="verConvocatoriaPostulante.php" class="btn btn-secondary">Volver a Convocatorias</a>
                </div>
            </div>
        </div>
    </div>

    <footer>
        ola <br> ol
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/761684499b.js" crossorigin="anonymous"></script>
    <script>
        function validateForm() {
            var isValid = true;
            var errorMessage = document.getElementById('errorMessage');
            var selects = document.querySelectorAll('select'); // Obtiene todos los select del formulario
            var totalPoints = 0;

            // Limpiar el mensaje de error al comenzar
            errorMessage.innerHTML = '';

            // Verificar cada select del formulario
            selects.forEach(function(select) {
                // Validar que la opción seleccionada no sea la opción por defecto ("Seleccione su respuesta")
                if (select.value === '0' || select.value === 'Seleccione su respuesta') {
                    // Mostrar mensaje de error
                    errorMessage.innerHTML = 'Por favor, seleccione una respuesta para cada pregunta.';
                    // Indicar que el formulario no es válido
                    isValid = false;
                } else {
                    // Sumar los puntos de la opción seleccionada
                    totalPoints += parseInt(select.value);
                }
            });

            // Si el formulario no es válido, detener el envío
            if (!isValid) {
                return false; // Evita el envío del formulario
            }

            // Asignar los puntos totales al campo oculto para enviarlos con el formulario
            document.getElementById('totalPoints').value = totalPoints;

            return isValid;
        }
    </script>
    
</body>
</html>