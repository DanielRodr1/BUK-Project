<?php
include_once '../BD/ConexionDB.php';

class Usuario
{
    private $conn;
    private $table_usuarios = "usuarios";
    private $table_dnis = "Dnis";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function iniciarSesion($email, $password)
    {
        // Código de validarCredenciales
        $query = "SELECT * FROM " . $this->table_usuarios . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);

        try {
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                if ($row['rol'] == 'trabajador') {
                    if ($password == $row['password']) {
                        return ['rol' => $row['rol'], 'usuario' => $row];
                    }
                } else {
                    if (password_verify($password, $row['password'])) {
                        return ['rol' => $row['rol'], 'usuario' => $row];
                    }
                }
            }
        } catch (PDOException $e) {
            echo "Error en la ejecución de la consulta: " . $e->getMessage();
        }

        return false;
    }
}

// Obtén la conexión
$database = new ConexionBD();
$db = $database->obtenerConexion();

// Verifica si se está realizando un inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
    $usuario = new Usuario($db);

    // Llama al método iniciarSesion
    $sesion = $usuario->iniciarSesion($_POST['email'], $_POST['password']);

    if ($sesion) {
        $rol = $sesion['rol'];

        // Redirige según el rol
        if ($rol == 'trabajador') {
            header("Location: ../homeJefe.html");
            exit();
        } elseif ($rol == 'postulante') {
            header("Location: ../homePostulante.html");
            exit();
        }
    } else {
        echo "Credenciales incorrectas.";
    }
}
?>
