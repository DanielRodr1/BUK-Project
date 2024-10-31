<?php

include_once __DIR__ . '/../BD/ConexionDB.php';

class Convocatoria
{
    private $conn;
    private $table_usuarios = "usuarios";

    public function __construct()
    {
        $conexionBD = new ConexionBD();
        $this->conn = $conexionBD->obtenerConexion();
    }

    public function getLastInsertedId() {
        return $this->conn->lastInsertId();
    }

    public function guardarConvocatoria($datosConvocatoria)
    {
        $workArea = $datosConvocatoria['workArea'];
        $salary = $datosConvocatoria['salary'];
        $modality = $datosConvocatoria['modality'];
        $timeWork = $datosConvocatoria['timeWork'];

        $vacancies = $datosConvocatoria['vacancies'];
        $hiringProcess = $datosConvocatoria['hiringProcess'];
        $selectionCriteria = $datosConvocatoria['selectionCriteria'];
        $notifyApplicant = $datosConvocatoria['notifyApplicant'];

        $dateStart = $datosConvocatoria['dateStart'];
        $dateLimit = $datosConvocatoria['dateLimit'];
        $dateInterview = $datosConvocatoria['dateInterview'];
        $dateAnnouncement = $datosConvocatoria['dateAnnouncement'];

        $responsibilities = isset($_POST['responsibilities']) ? $_POST['responsibilities'] : [];
        $benefits = isset($_POST['benefits']) ? $_POST['benefits'] : [];
        $requirements = isset($_POST['requirements']) ? $_POST['requirements'] : [];
        $evaluators = isset($_POST['evaluators']) ? $_POST['evaluators'] : [];

        try {
            // Verificación de duplicados
            $stmt = $this->conn->prepare(
                "SELECT COUNT(*) AS total FROM Jobs 
                 JOIN Bases ON Jobs.announcement_id_fk_job = Bases.announcement_id_fk
                 WHERE Jobs.workArea = :workArea AND Bases.dateStart = :dateStart"
            );
            $stmt->bindParam(':workArea', $workArea);
            $stmt->bindParam(':dateStart', $dateStart);
            $stmt->execute();
    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row['total'] > 0) {
                // Retorna 'duplicada' si ya existe una convocatoria con los mismos datos
                return 'duplicada';
            }
    
            // Inserción de la convocatoria
            $stmt = $this->conn->prepare(
                "INSERT INTO Announcement (vacancies, hiringProcess, selectionCriteria, notifyApplicant, status) 
                VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $datosConvocatoria['vacancies'], 
                $datosConvocatoria['hiringProcess'], 
                $datosConvocatoria['selectionCriteria'], 
                $datosConvocatoria['notifyApplicant'], 
                'abierta'
            ]);
    
            $announcementId = $this->conn->lastInsertId();

            // Insertar en Bases
            $stmt = $this->conn->prepare(
                "INSERT INTO Bases (dateAnnouncement, dateInterview, dateLimit, dateStart, announcement_id_fk) 
                VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([$dateAnnouncement, $dateInterview, $dateLimit, $dateStart, $announcementId]);

            // Insertar en Evaluators
            foreach ($evaluators as $evaluator) {
                $stmt = $this->conn->prepare(
                    "INSERT INTO Evaluators (evaluator_name, bases_id_fk_evaluator) VALUES (?, ?)"
                );
                $stmt->execute([$evaluator, $announcementId]);
            }

            // Insertar en Jobs
            $stmt = $this->conn->prepare(
                "INSERT INTO Jobs (workArea, salary, modality, timeWork, announcement_id_fk_job) 
                VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([$workArea, $salary, $modality, $timeWork, $announcementId]);

            // Insertar en Requirements
            foreach ($requirements as $requirement) {
                $stmt = $this->conn->prepare(
                    "INSERT INTO Requirements (requirement_text, bases_id_fk) VALUES (?, ?)"
                );
                $stmt->execute([$requirement, $announcementId]);
            }

            // Insertar en Responsibilities
            foreach ($responsibilities as $responsibility) {
                $stmt = $this->conn->prepare(
                    "INSERT INTO Responsibilities (responsibility_text, job_id_fk) VALUES (?, ?)"
                );
                $stmt->execute([$responsibility, $announcementId]);
            }

            // Insertar en Benefits
            foreach ($benefits as $benefit) {
                $stmt = $this->conn->prepare(
                    "INSERT INTO Benefits (benefit_text, job_id_fk_benefit) VALUES (?, ?)"
                );
                $stmt->execute([$benefit, $announcementId]);
            }

            return true;
        } catch (PDOException $e) {
            // Manejar excepciones y retornar error en JSON
            echo json_encode(['success' => false, 'message' => "Error guardando la convocatoria: " . $e->getMessage()]);
            return false;
        }
    }

    public function Listar_convocatorias($tipoInterfaz)
    {
        try {
            // Consulta para obtener información de las convocatorias
            $stmt = $this->conn->query("
                SELECT DISTINCT Announcement.vacancies, Jobs.workArea, Jobs.salary, Jobs.modality, 
                                Jobs.timeWork, Announcement.announcement_id, Announcement.status
                FROM Announcement
                LEFT JOIN Bases ON Announcement.announcement_id = Bases.announcement_id_fk
                LEFT JOIN Jobs ON Announcement.announcement_id = Jobs.announcement_id_fk_job
            ");
    
            $convocatorias = array();
    
            // Obtener resultados uno por uno
            while ($convocatoria = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Si la convocatoria está cerrada y la interfaz es de postulante, omitirla
                if ($tipoInterfaz === 'postulante' && $convocatoria['status'] === 'cerrada') {
                    continue;
                }
    
                // Agregar responsabilidades y beneficios a la convocatoria actual
                $convocatoria['responsabilidades'] = $this->obtenerResponsabilidades($convocatoria['announcement_id']);
                $convocatoria['beneficios'] = $this->obtenerBeneficios($convocatoria['announcement_id']);
    
                // Construcción del HTML para cada convocatoria
                $html = '<div class="container">';
    
                $html .= '<div class="contFirst container">';
                $html .= '<div class="firstRow row">';
                $html .= '<div class="col-md-4">';
                $html .= '<h4 name="workArea">' . $convocatoria['workarea'] . '</h4>';
                $html .= '</div>';
                $html .= '<div class="col-md-8">';
                $html .= '<div class="labels-container">';
                $html .= '<label for="salary" name="salary"> S/. ' . $convocatoria['salary'] . ' <br>(MENSUAL) </label>';
                $html .= '<label for="timeWork" name="timeWork">' . 'TIEMPO <br>' . $convocatoria['timework'] . '</label>';
                $html .= '<label for="modality" name="modality">' . 'MODALIDAD <br>' . $convocatoria['modality'] . '</label>';
                $html .= '<label for="vacancies" name="vacancies">' . $convocatoria['vacancies'] . ' VACANTES <br>DISPONIBLES </label>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
    
                $html .= '<div class="secondRow row">';
                $html .= '<h5>Sus principales responsabilidades son:</h5>';
                foreach ($convocatoria['responsabilidades'] as $responsabilidad) {
                    $html .= '<p>' . $responsabilidad['responsibility_text'] . '</p>' . '<br><br>';
                }
                $html .= '</div>';
    
                $html .= '<div class="thirdRow row">';
                $html .= '<h5>Sus beneficios son:</h5>';
                foreach ($convocatoria['beneficios'] as $beneficio) {
                    $html .= '<p>' . $beneficio['benefit_text'] . '</p>' . '<br><br>';
                }
                $html .= '</div>';
    
                // Sección de botones
                $html .= '<div class="button-group">';
    
                if ($tipoInterfaz === 'jefe') {
                    $html .= '<button class="btn-1 btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#basesModal' . $convocatoria['announcement_id'] . '">VER BASES</button>';
                    $html .= '<button class="btn-3 btn btn-dark" onclick="redirigirVerPostulantes(' . $convocatoria['announcement_id'] . ')">VER POSTULANTES</button>';
    
                    if ($convocatoria['status'] !== 'cerrada') {
                        $html .= '<form action="Clases/terminarConvocatoria.php" method="POST" style="display:inline-block; margin-left: 40px;">';
                        $html .= '<input type="hidden" name="announcement_id" value="' . $convocatoria['announcement_id'] . '">';
                        $html .= '<button class="btn-2 btn btn-dark" type="submit">TERMINAR CONVOCATORIA</button>';
                        $html .= '</form>';
                    } else {
                        $html .= '<form action="Clases/abrirConvocatoria.php" method="POST" style="display:inline-block; margin-left: 40px;">';
                        $html .= '<input type="hidden" name="announcement_id" value="' . $convocatoria['announcement_id'] . '">';
                        $html .= '<button class="btn-2 btn btn-success" type="submit">INICIAR CONVOCATORIA</button>';
                        $html .= '</form>';
                    }
                } elseif ($tipoInterfaz === 'postulante' && $convocatoria['status'] !== 'cerrada') {
                    $html .= '<button class="btn-1 btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#basesModal' . $convocatoria['announcement_id'] . '">VER BASES</button>';
                    $html .= '<button id="postulacionBtn1" class="btn-2 btn btn-dark btn-postulacion" type="button" onclick="redirigirPostulacion(' . $convocatoria['announcement_id'] . ')">POSTULARME</button>';
                }
    
                $html .= '</div>'; // Fin del group-button
                $html .= '</div>'; // Fin del container de convocatoria
    
                $convocatorias[] = array(
                    'html' => $html,
                    'announcement_id' => $convocatoria['announcement_id']
                );
            }
    
            return $convocatorias;
    
        } catch (PDOException $e) {
            echo "Error al obtener las convocatorias: " . $e->getMessage();
            return false;
        }
    }


    
    public function listar_bases($convocatoria_id)
    {
        try {
            // Consulta para obtener información de las bases de la convocatoria específica
            $modal = $this->conn->prepare("SELECT DISTINCT Announcement.hiringProcess, Announcement.selectionCriteria, Announcement.notifyApplicant, Bases.dateStart, Bases.dateLimit, Bases.dateInterview, Bases.dateAnnouncement
                                        FROM Announcement
                                        INNER JOIN Bases ON Announcement.announcement_id = Bases.announcement_id_fk
                                        WHERE Announcement.announcement_id = :convocatoria_id");

            $modal->bindParam(':convocatoria_id', $convocatoria_id, PDO::PARAM_INT);
            $modal->execute();

            // Obtener resultados como un array asociativo
            $bases = $modal->fetchAll(PDO::FETCH_ASSOC);

            // Agregar requisitos y evaluadores a las bases
            foreach ($bases as &$base) {
                $base['requisitos'] = $this->obtenerRequisitos($convocatoria_id);
                $base['evaluadores'] = $this->obtenerEvaluadores($convocatoria_id);
            }

            // Generar el HTML del modal y las bases
            $html = '<div class="modal fade" id="basesModal' . $convocatoria_id . '" tabindex="-1" aria-labelledby="basesModalLabel' . $convocatoria_id . '" aria-hidden="true">';
            $html .= '<div class="modal-dialog modal-lg modal-dialog-centered">';
            $html .= '<div class="modal-content">';
            $html .= '<div class="modal-header">';
            $html .= '<h5 class="modal-title" id="basesModalLabel' . $convocatoria_id . '">Bases de Convocatoria</h5>';
            $html .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
            $html .= '</div>';
            $html .= '<div class="modal-body">';
            $html .= '<div class="container">';
            $html .= '<div class="row">';
            $html .= '<div class="col-md-6 border-end border-2 border-dark">';
            $html .= '<h6>Requisitos:</h6>';
            $html .= '<ul>';
            foreach ($bases[0]['requisitos'] as $requisito) {
                $html .= '<li>' . $requisito['requirement_text'] . '</li>';
            }
            $html .= '</ul>';
            $html .= '<h6>Proceso de Contratación:</h6>';
            $html .= '<p>'. $bases[0]['hiringprocess'] .'</p>';
            $html .= '<h6>Cronograma:</h6>';
            $html .= '<ul>';
            $html .= '<li>Fecha de Inicio: '. $bases[0]['datestart'] .'</li>';
            $html .= '<li>Fecha de Límite: '. $bases[0]['datelimit'] .'</li>';
            $html .= '<li>Fecha de Entrevista: '. $bases[0]['dateinterview'] .'</li>';
            $html .= '<li>Anuncio de Candidato: '. $bases[0]['dateannouncement'] .'</li>';
            $html .= '</ul>';
            $html .= '</div>';
            $html .= '<div class="col-md-6">';
            $html .= '<h6>Comité Evaluador:</h6>';
            $html .= '<ul>';
            foreach ($bases[0]['evaluadores'] as $evaluador) {
                $html .= '<li>' . $evaluador['evaluator_name'] . '</li>';
            }
            $html .= '</ul>';
            $html .= '<h6>Criterios de selección:</h6>';
            $html .= '<p>'. $bases[0]['selectioncriteria'] .'</p>';
            $html .= '<h6>Comunicar al postulante:</h6>';
            $html .= '<p>'. $bases[0]['notifyapplicant'] .'</p>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';

            return $html;
        } catch (PDOException $e) {
            echo "Error al obtener las bases: " . $e->getMessage();
            return false;
        }
    }
    private function obtenerResponsabilidades($announcementId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Responsibilities WHERE job_id_fk IN (SELECT job_id FROM Jobs WHERE announcement_id_fk_job = ?)");
        $stmt->execute([$announcementId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerBeneficios($announcementId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Benefits WHERE job_id_fk_benefit IN (SELECT job_id FROM Jobs WHERE announcement_id_fk_job = ?)");
        $stmt->execute([$announcementId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerEvaluadores($announcementId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Evaluators WHERE bases_id_fk_evaluator IN (SELECT bases_id FROM Bases WHERE announcement_id_fk = ?)");
        $stmt->execute([$announcementId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerRequisitos($announcementId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Requirements WHERE bases_id_fk IN (SELECT bases_id FROM Bases WHERE announcement_id_fk = ?)");
        $stmt->execute([$announcementId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPreguntas($convocatoria_id)
    {
        try {
            // Consulta para obtener preguntas y sus alternativas asociadas a una convocatoria específica
            $stmt = $this->conn->prepare("
                SELECT q.question_id, q.question_text, a.alternative_id, a.alternative_text, a.points
                FROM questions q
                LEFT JOIN alternatives a ON q.question_id = a.question_id_fk
                LEFT JOIN forms f ON q.form_id_fk = f.form_id
                WHERE f.announcement_id_fk = :convocatoria_id
            ");
            $stmt->bindParam(':convocatoria_id', $convocatoria_id, PDO::PARAM_INT);
            $stmt->execute();
            // Obtener resultados como un array asociativo
            $preguntas = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pregunta_id = $row['question_id'];

                // Si la pregunta aún no está en el array, agrégala
                if (!isset($preguntas[$pregunta_id])) {
                    $preguntas[$pregunta_id] = array(
                        'question_id' => $pregunta_id,
                        'question_text' => $row['question_text'],
                        'alternativas' => array()
                    );
                }

                // Agregar alternativa a la pregunta
                $preguntas[$pregunta_id]['alternativas'][] = array(
                    'alternative_id' => $row['alternative_id'],
                    'alternative_text' => $row['alternative_text'],
                    'points' => $row['points']
                );
            }

            return array_values($preguntas); // Devolver solo los valores del array para reindexar
        } catch (PDOException $e) {
            echo "Error al obtener las preguntas: " . $e->getMessage();
            return false;
        }
    }


    public function guardarUsuarioConvocatoria($email, $idconvocatoria, $totalPoints) {
        try {
            // Verificar si el usuario existe en la tabla de usuarios
            $query = "SELECT id FROM " . $this->table_usuarios . " WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row) {
                $userId = $row['id'];
                
                // Insertar en la tabla user_announcement
                $query = "INSERT INTO user_announcement (user_id, announcement_id, puntaje) VALUES (:user_id, :announcement_id, :puntaje)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
                $stmt->bindParam(":announcement_id", $idconvocatoria, PDO::PARAM_INT);
                $stmt->bindParam(":puntaje", $totalPoints, PDO::PARAM_INT);
                $stmt->execute();
    
                return true;
            } else {
                echo "Error: Usuario no encontrado con el email proporcionado.";
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al guardar la postulación: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerPostulantesPorConvocatoria($announcement_id) {
        try {
            // Supongamos que $this->pdo es tu instancia de PDO
            $stmt = $this->conn->prepare("
                SELECT ua.user_id, u.lastname, u.name, u.telephone, ua.puntaje
                FROM user_announcement ua
                JOIN usuarios u ON ua.user_id = u.id
                WHERE ua.announcement_id = :announcement_id
            ");

            $stmt->bindParam(':announcement_id', $announcement_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo de errores, por ejemplo, registrar el error y devolver un array vacío o false
            error_log('Error al obtener postulantes: ' . $e->getMessage());
            return [];
        }
    }
    
    public function verificarPostulacionExistente($email, $announcementId)
    {
        try {
            // Consulta para verificar si el usuario ya se ha postulado a la convocatoria
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) AS total FROM user_announcement ua
                JOIN usuarios u ON ua.user_id = u.id
                WHERE u.email = :email AND ua.announcement_id = :announcement_id
            ");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':announcement_id', $announcementId);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si el total es mayor que 0, significa que el usuario ya se ha postulado
            return $row['total'] > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar postulación existente: " . $e->getMessage());
            return false;
        }
    }

    public function terminarConvocatoria($announcementId)
    {
        try {
            $nuevoEstado = 'cerrada'; // Cambiar el estado a 'cerrada'
            $stmt = $this->conn->prepare(
                "UPDATE Announcement SET status = :nuevoEstado WHERE announcement_id = :announcementId"
            );
            $stmt->bindParam(':nuevoEstado', $nuevoEstado);
            $stmt->bindParam(':announcementId', $announcementId);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error al terminar la convocatoria: " . $e->getMessage();
            return false;
        }
    }

    public function abrirConvocatoria($announcementId)
    {
        try {
            $nuevoEstado = 'abierta'; // Cambiar el estado a 'abierta'
            $stmt = $this->conn->prepare(
                "UPDATE Announcement SET status = :nuevoEstado WHERE announcement_id = :announcementId"
            );
            $stmt->bindParam(':nuevoEstado', $nuevoEstado);
            $stmt->bindParam(':announcementId', $announcementId);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error al abrir la convocatoria: " . $e->getMessage();
            return false;
        }
    }


}
?>