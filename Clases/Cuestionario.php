<?php

include_once '../BD/ConexionDB.php';

class Cuestionario
{
    private $conn;

    public function __construct()
    {
        $conexionBD = new ConexionBD();
        $this->conn = $conexionBD->obtenerConexion();
    }
    
    public function guardarCuestionario($datosCuestionario)
    {
        try {
            // Verificar si hay preguntas antes de intentar guardarlas
            if (!empty($datosCuestionario) && is_array($datosCuestionario)) {
                // Preparar las consultas fuera del bucle

                $stmtIdAnnouncement = $this->conn->prepare("SELECT COUNT(*) AS total FROM announcement");
                $stmtIdAnnouncement->execute();
                $idAnnouncement = $stmtIdAnnouncement->fetch(PDO::FETCH_ASSOC);


                $stmtForm = $this->conn->prepare("INSERT INTO Forms (announcement_id_fk) VALUES (?)");
                $stmtPregunta = $this->conn->prepare("INSERT INTO Questions (question_text, form_id_fk) VALUES (?, ?)");
                $stmtAlternativa = $this->conn->prepare("INSERT INTO Alternatives (alternative_text, points, question_id_fk) VALUES (?, ?, ?)");

                // Insertar un formulario y obtener el ID del formulario
                $stmtForm->execute([$idAnnouncement['total']]);
                $formId = $this->conn->lastInsertId();

                // Iterar sobre las preguntas
                foreach ($datosCuestionario as $indicePregunta => $pregunta) {
                    // Validar que haya texto de pregunta antes de intentar guardar
                    if (!empty($pregunta['texto'])) {
                        // Insertar la pregunta en la base de datos
                        $stmtPregunta->execute([$pregunta['texto'], $formId]);

                        $preguntaId = $this->conn->lastInsertId();

                        // Verificar si hay alternativas antes de intentar guardarlas
                        foreach ($pregunta['alternativas'] as $indiceAlternativa => $alternativa) {
                            // Validar que haya texto de alternativa antes de intentar guardar
                            if (!empty($alternativa['texto'])) {
                                $stmtAlternativa->execute([$alternativa['texto'], $alternativa['puntos'], $preguntaId]);
                            }
                        }
                    }
                }

                return true;
            } else {
                echo "No hay preguntas para guardar.";
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al guardar el cuestionario: " . $e->getMessage();
            return false;
        }
    }
}

$cuestionario = new Cuestionario();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardarCuestionario'])) {
    $datosCuestionario = $_POST['preguntas'];

    if ($cuestionario->guardarCuestionario($datosCuestionario)) {
        // Éxito, redireccionar o realizar alguna acción adicional
        header("Location: ../homeJefe.html");
    } else {
        // Error al guardar el cuestionario
        echo "Error al guardar el cuestionario.";
    }
}
?>