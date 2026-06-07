<?php

class Database {
    // Credenciais padrão do Laragon
    private $host = "localhost";
    private $db_name = "db_pweb1_frota"; // Nome do banco que criamos no script SQL
    private $username = "root";
    private $password = ""; // Senha vazia por padrão no Laragon
    public $conn;

    // Método para estabelecer e retornar a conexão com o banco
    public function getConnection() {
        $this->conn = null;

        try {
            // Cria a conexão via PDO com charset utf8mb4 (evita problemas com acentuação)
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", 
                $this->username, 
                $this->password
            );
            
            // Configura o PDO para lançar exceções em caso de erro (facilita muito para debugar)
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $exception) {
            // Em caso de erro, exibe a mensagem de falha
            echo "<div style='color: red; padding: 10px; border: 1px solid red; margin: 10px;'>";
            echo "<strong>Erro na conexão com o banco de dados:</strong> " . $exception->getMessage();
            echo "</div>";
        }

        return $this->conn;
    }
}
?>