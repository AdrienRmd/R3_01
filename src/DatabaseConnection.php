<?php
class DatabaseConnection
{
    private string $host;
    private int $port;
    private string $dbname;
    private string $user;
    private string $password;
    private ?PDO $pdo = null;

    public function __construct()
    {
        $config = $this->loadEnv();
        $this->host = $config['DB_HOST'];
        $this->port = (int) $config['DB_PORT'];
        $this->dbname = $config['DB_NAME'];
        $this->user = $config['DB_USER'];
        $this->password = $config['DB_PASSWORD'];
        $this->connect();
    }

    private function loadEnv(): array
    {
        $envFile = dirname(__DIR__) . '/.env';
        if (!file_exists($envFile)) {
            die("Fichier .env introuvable à la racine du projet.");
        }
        return parse_ini_file($envFile);
    }

    private function connect(): void
    {
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Erreur de connexion : " . $e->getMessage()); // Log caché pour l'admin
            die("Erreur de connexion à la base de données.");       // Message générique pour l'utilisateur
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
