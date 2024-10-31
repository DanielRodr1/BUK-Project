<?php
    class ConexionBD {
        private $host = "postgresql-bukconvocatorias.alwaysdata.net";
        private $db_name = "bukconvocatorias_calidad";
        private $username = "bukconvocatorias";
        private $password = "luisysusana23";
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
