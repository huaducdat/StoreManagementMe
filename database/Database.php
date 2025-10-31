<?php
class Database
{
    private $host;
    private $username;
    private $password;
    private $dbname;
    private $pdo;

    public function __construct($config, $db_name = null)
    {
        $this->host = $config['host'];
        $this->username = $config['usename'];
        $this->password = $config['password'];
        $this->dbname = $db_name;
    }
    public function connect()
    {
        if (!$this->pdo) {
            $dsn = "mysql:host={$this->host}" . ($this->dbname ? ";dbname={$this->dbname}" : "");
            try{
                $this->pdo = new PDO($dsn, $this->username, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->exec('SET NAMES utf8mb4');
            } catch(PDOException $e){
                die("❌ Connect error: " . $e->getMessage());
            }
        }
        return $this->pdo;
    }
}
