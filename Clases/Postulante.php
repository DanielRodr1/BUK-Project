<?php
include_once '../BD/ConexionDB.php';

class Postulante extends Usuario{

    protected $conn;
    protected $table_usuarios = "usuarios"; // Nombre de la tabla de usuarios
    private $table_dnis = "Dnis";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function registerUser($document, $name, $lastName, $telephone, $email, $password)
    {
        // Código de registerUser
        $queryDNI = "SELECT * FROM " . $this->table_dnis . " WHERE dni = :document";
        $stmtDNI = $this->conn->prepare($queryDNI);
        $stmtDNI->bindParam(":document", $document);
        $stmtDNI->execute();

        if ($stmtDNI->rowCount() === 0) {
            return "INGRESE UN DNI VÁLIDO POR FAVOR";
        }

        $queryUsuarioDNI = "SELECT * FROM " . $this->table_usuarios . " WHERE document = :document";
        $stmtUsuarioDNI = $this->conn->prepare($queryUsuarioDNI);
        $stmtUsuarioDNI->bindParam(":document", $document);
        $stmtUsuarioDNI->execute();

        if ($stmtUsuarioDNI->rowCount() > 0) {
            return "EL DNI YA HA SIDO REGISTRADO CON ANTERIORIDAD";
        }

        $queryEmail = "SELECT * FROM " . $this->table_usuarios . " WHERE email = :email";
        $stmtEmail = $this->conn->prepare($queryEmail);
        $stmtEmail->bindParam(":email", $email);
        $stmtEmail->execute();

        if ($stmtEmail->rowCount() > 0) {
            return "EL CORREO YA HA SIDO REGISTRADO CON ANTERIORIDAD";
        }

        $queryTelephone = "SELECT * FROM " . $this->table_usuarios . " WHERE telephone = :telephone";
        $stmtTelephone = $this->conn->prepare($queryTelephone);
        $stmtTelephone->bindParam(":telephone", $telephone);
        $stmtTelephone->execute();

        if ($stmtTelephone->rowCount() > 0) {
            return "EL CELULAR YA HA SIDO REGISTRADO CON ANTERIORIDAD";
        }

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

?>