<?php
include_once '../BD/ConexionDB.php';
include_once 'Usuario.php';

class Postulante extends Usuario{

    protected $conn;
    protected $table_usuarios = "usuarios"; // Nombre de la tabla de usuarios
    private $table_dnis = "dnis";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function registerUser($document, $name, $lastName, $telephone, $email, $password)
    {
        // Paso 1: Validar si el DNI está en la tabla 'dnis'
        $queryDNI = "SELECT * FROM " . $this->table_dnis . " WHERE dni = :document";
        $stmtDNI = $this->conn->prepare($queryDNI);
        $stmtDNI->bindParam(":document", $document);
        $stmtDNI->execute();

        if ($stmtDNI->rowCount() === 0) {
            return "El DNI no es válido. Por favor, ingrese un DNI correcto.";
        }

        // Paso 2: Validar si el nombre coincide con el DNI proporcionado
        $queryNombre = "SELECT * FROM " . $this->table_dnis . " WHERE dni = :document AND name = :name";
        $stmtNombre = $this->conn->prepare($queryNombre);
        $stmtNombre->bindParam(":document", $document);
        $stmtNombre->bindParam(":name", $name);
        $stmtNombre->execute();

        if ($stmtNombre->rowCount() === 0) {
            return "El nombre no coincide con el DNI proporcionado. Por favor, ingrese el nombre correcto.";
        }

        // Paso 3: Validar si el apellido coincide con el DNI y el nombre proporcionados
        $queryApellido = "SELECT * FROM " . $this->table_dnis . " WHERE dni = :document AND name = :name AND lastname = :lastname";
        $stmtApellido = $this->conn->prepare($queryApellido);
        $stmtApellido->bindParam(":document", $document);
        $stmtApellido->bindParam(":name", $name);
        $stmtApellido->bindParam(":lastname", $lastName);
        $stmtApellido->execute();

        if ($stmtApellido->rowCount() === 0) {
            return "El apellido no coincide con el DNI y nombre proporcionados. Por favor, ingrese el apellido correcto.";
        }

        // Validación de duplicados para el DNI, correo y teléfono
        // Verificar si el DNI ya ha sido registrado en la tabla de usuarios
        $queryUsuarioDNI = "SELECT * FROM " . $this->table_usuarios . " WHERE document = :document";
        $stmtUsuarioDNI = $this->conn->prepare($queryUsuarioDNI);
        $stmtUsuarioDNI->bindParam(":document", $document);
        $stmtUsuarioDNI->execute();

        if ($stmtUsuarioDNI->rowCount() > 0) {
            return "El DNI ya ha sido registrado con anterioridad.";
        }

        // Verificar si el correo ya ha sido registrado
        $queryEmail = "SELECT * FROM " . $this->table_usuarios . " WHERE email = :email";
        $stmtEmail = $this->conn->prepare($queryEmail);
        $stmtEmail->bindParam(":email", $email);
        $stmtEmail->execute();

        if ($stmtEmail->rowCount() > 0) {
            return "El correo ya ha sido registrado con anterioridad.";
        }

        // Verificar si el teléfono ya ha sido registrado
        $queryTelephone = "SELECT * FROM " . $this->table_usuarios . " WHERE telephone = :telephone";
        $stmtTelephone = $this->conn->prepare($queryTelephone);
        $stmtTelephone->bindParam(":telephone", $telephone);
        $stmtTelephone->execute();

        if ($stmtTelephone->rowCount() > 0) {
            return "El celular ya ha sido registrado con anterioridad.";
        }

        // Inserción del nuevo usuario en la tabla 'usuarios'
        $queryInsert = "INSERT INTO " . $this->table_usuarios . " (document, name, lastName, telephone, email, password, rol) 
                        VALUES (:document, :name, :lastName, :telephone, :email, :password, :rol)";
        $stmtInsert = $this->conn->prepare($queryInsert);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $rol = 'postulante';

        $stmtInsert->bindParam(":document", $document);
        $stmtInsert->bindParam(":name", $name);
        $stmtInsert->bindParam(":lastName", $lastName);
        $stmtInsert->bindParam(":telephone", $telephone);
        $stmtInsert->bindParam(":email", $email);
        $stmtInsert->bindParam(":password", $hashedPassword);
        $stmtInsert->bindParam(":rol", $rol);

        try {
            $stmtInsert->execute();
            return "Registro exitoso";
        } catch (PDOException $e) {
            echo "Error en la ejecución de la consulta: " . $e->getMessage();
        }

        return "Error al registrar el usuario";
    }

}

// Obtén la conexión
$database = new ConexionBD();
$db = $database->obtenerConexion();

// Verifica si se está realizando un registro
if (isset($_POST['document']) && isset($_POST['name']) && isset($_POST['lastName']) && isset($_POST['telephone']) && isset($_POST['email']) && isset($_POST['password1'])) {
    $postulante = new Postulante($db);

    $document = $_POST['document'];
    $name = $_POST['name'];
    $lastName = $_POST['lastName'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $password = $_POST['password1'];

    $resultado = $postulante->registerUser($document, $name, $lastName, $telephone, $email, $password);

    // Responder con un JSON para manejarlo en el frontend
    if ($resultado === "Registro exitoso") {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $resultado]);
    }
    exit();
}


?>