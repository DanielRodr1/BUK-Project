<?php

include_once __DIR__ . '/../BD/ConexionDB.php';

class Convocatoria
{
    private $conn;

    public function __construct()
    {
        $conexionBD = new ConexionBD();
        $this->conn = $conexionBD->obtenerConexion();
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

            // Insertar en Announcement
            $stmt = $this->conn->prepare("INSERT INTO Announcement (vacancies, hiringProcess, selectionCriteria, notifyApplicant) 
            VALUES (?, ?, ?, ?)");
            $stmt->execute([$vacancies, $hiringProcess, $selectionCriteria, $notifyApplicant]);

            // Obtener el ID de la convocatoria recién insertada
            $announcementId = $this->conn->lastInsertId();

            // Insertar en Bases
            $stmt = $this->conn->prepare("INSERT INTO Bases (dateAnnouncement, dateInterview, dateLimit, dateStart, announcement_id_fk) 
            VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$dateAnnouncement, $dateInterview, $dateLimit, $dateStart, $announcementId]);


            // Insertar en Evaluators
            foreach ($evaluators as $evaluator) {
                $stmt = $this->conn->prepare("INSERT INTO Evaluators (evaluator_name, bases_id_fk_evaluator) VALUES (?, ?)");
                $stmt->execute([$evaluator, $announcementId]);
            }

            // Insertar en Jobs
            $stmt = $this->conn->prepare("INSERT INTO Jobs (workArea, salary, modality, timeWork, announcement_id_fk_job) 
                                            VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$workArea, $salary, $modality, $timeWork, $announcementId]);

            // Insertar en Requirements
            //$this->insertDynamicFields($announcementId, 'Requirements', $requirements);

            foreach ($requirements as $requirement) {
                $stmt = $this->conn->prepare("INSERT INTO Requirements (requirement_text, bases_id_fk) VALUES (?, ?)");
                $stmt->execute([$requirement, $announcementId]);
            }

            // Insertar en Responsibilities
            //$this->insertDynamicFields($announcementId, 'Responsibilities', $responsibilities);

            foreach ($responsibilities as $responsability) {
                $stmt = $this->conn->prepare("INSERT INTO Responsibilities (responsibility_text, job_id_fk) VALUES (?, ?)");
                $stmt->execute([$responsability, $announcementId]);
            }

            // Insertar en Benefits
            //$this->insertDynamicFields($announcementId, 'Benefits', $benefits);

            foreach ($benefits as $benefit) {
                $stmt = $this->conn->prepare("INSERT INTO Benefits (benefit_text, job_id_fk_benefit) VALUES (?, ?)");
                $stmt->execute([$benefit, $announcementId]);
            }


            return true;
        } catch (PDOException $e) {
            echo "Error saving the convocation: " . $e->getMessage();
            return false;
        }
    }

    public function Listar_convocatorias($tipoInterfaz)
    {
        try {
            // Consulta para obtener información de las convocatorias
            $stmt = $this->conn->query("SELECT DISTINCT Announcement.vacancies, Jobs.workArea, Jobs.salary, Jobs.modality, Jobs.timeWork, Announcement.announcement_id
                                        FROM Announcement
                                        INNER JOIN Bases ON Announcement.announcement_id = Bases.announcement_id_fk
                                        INNER JOIN Jobs ON Announcement.announcement_id = Jobs.announcement_id_fk_job");

            // Obtener resultados como un array asociativo
            $convocatorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Agregar responsabilidades y beneficios a cada convocatoria
            foreach ($convocatorias as &$convocatoria) {
                $convocatoria['responsabilidades'] = $this->obtenerResponsabilidades($convocatoria['announcement_id']);
                $convocatoria['beneficios'] = $this->obtenerBeneficios($convocatoria['announcement_id']);
            }

            // HTML para mostrar la lista de convocatorias
            $html = '<div class="container">';
            foreach ($convocatorias as $convocatoria) {
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

                $html .= '<div class="button-group">';

                if ($tipoInterfaz === 'jefe') {
                    $html .= '<button class="btn-1 btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#basesModal">VER BASES</button>';
                    $html .= '<button class="btn-3 btn btn-dark" onclick="redirigirVerPostulantes()">VER POSTULANTES</button>';
                    $html .= '<button class="btn-2 btn btn-dark terminar-concurso-btn" type="button" data-bs-toggle="modal" data-bs-target="#finConcursoModal">TERMINAR CONVOCATORIA</button>';
                } elseif ($tipoInterfaz === 'postulante' || $tipoInterfaz === 'nologin') {
                    $html .= '<button class="btn-1 btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#basesModal">VER BASES</button>';
                    $html .= '<button id="postulacionBtn1" class="btn-2 btn btn-dark btn-postulacion" type="button" onclick="redirigirPostulacion(\'convocatoria1\')">POSTULARME</button>';
                }

                $html .= '</div>';
                $html .= '</div>';
            } // Cierre del bucle foreach
            $html .= '</div>';

            return $html;
        } catch (PDOException $e) {
            echo "Error al obtener las convocatorias: " . $e->getMessage();
            return false;
        }
    }

    public function listar_bases()
    {
        try {
            // Consulta para obtener información de las convocatorias
            $modal = $this->conn->query("SELECT DISTINCT Announcement.hiringProcess, Announcement.selectionCriteria, Announcement.notifyApplicant, Bases.dateStart, Bases.dateLimit, Bases.dateInterview, Bases.dateAnnouncement, Announcement.announcement_id
                                        FROM Announcement
                                        INNER JOIN Bases ON Announcement.announcement_id = Bases.announcement_id_fk");

            // Obtener resultados como un array asociativo
            $bases = $modal->fetchAll(PDO::FETCH_ASSOC);

            // Agregar responsabilidades y beneficios a cada convocatoria
            foreach ($bases as &$base) {
                $base['requisitos'] = $this->obtenerRequisitos($base['announcement_id']);
                $base['evaluadores'] = $this->obtenerEvaluadores($base['announcement_id']);
            }

            $html = '<div class="modal fade" id="basesModal" tabindex="-1" aria-labelledby="basesModalLabel" aria-hidden="true">';
            $html .= '<div class="modal-dialog modal-lg modal-dialog-centered">';
            $html .= '<div class="modal-content">';
            $html .= '<div class="modal-header">';
            $html .= '<h5 class="modal-title" id="basesModalLabel">Bases de Convocatoria</h5>';
            $html .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
            $html .= '</div>';
            $html .= '<div class="modal-body">';
            $html .= '<div class="container">';
            $html .= '<div class="row">';
            $html .= '<div class="col-md-6 border-end border-2 border-dark">';
            $html .= '<h6>Requisitos:</h6>';
            $html .= '<ul>';
            foreach ($base['requisitos'] as $requisito) {
                $html .= '<li>' . $requisito['requirement_text'] . '</li>';
            }
            $html .= '</ul>';
            $html .= '<h6>Proceso de Contratación:</h6>';
            $html .= '<p>'. $base['hiringprocess'] .'</p>';
            $html .= '<h6>Cronograma:</h6>';
            $html .= '<ul>';
            $html .= '<li>Fecha de Inicio: '. $base['datestart'] .'</li>';
            $html .= '<li>Fecha de Límite: '. $base['datelimit'] .'</li>';
            $html .= '<li>Fecha de Entrevista: '. $base['dateinterview'] .'</li>';
            $html .= '<li>Anuncio de Candidato: '. $base['dateannouncement'] .'</li>';
            $html .= '</ul>';
            $html .= '</div>';
            $html .= '<div class="col-md-6">';
            $html .= '<h6>Comité Evaluador:</h6>';
            $html .= '<ul>';
            foreach ($base['evaluadores'] as $evaluador) {
                $html .= '<li>' . $evaluador['evaluator_name'] . '</li>';
            }
            $html .= '</ul>';
            $html .= '<h6>Criterios de selección:</h6>';
            $html .= '<p>'. $base['selectioncriteria'] .'</p>';
            $html .= '<h6>Comunicar al postulante:</h6>';
            $html .= '<p>'. $base['notifyapplicant'] .'</p>';
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
}

// Ejemplo de uso:
$convocatoria = new Convocatoria();
echo $convocatoria->Listar_convocatorias($tipoInterfaz);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge los datos del formulario
    $datosConvocatoria = $_POST;

    // Guarda la convocatoria
    if ($convocatoria->guardarConvocatoria($datosConvocatoria)) {
        header("Location: ../crearCuestionario.html");
    } else {
        echo "Error al guardar la convocatoria.";
    }
}
?>