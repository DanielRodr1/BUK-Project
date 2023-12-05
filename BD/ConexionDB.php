<?php
class ConexionBD {
    private $host = "localhost";
    private $db_name = "Inso2";
    private $username = "postgres";
    private $password = "sistemas";
    public $conn;

    public function obtenerConexion() {
        $this->conn = null;

        try {
            $this->conn = new PDO("pgsql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
