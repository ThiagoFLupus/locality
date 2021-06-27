<?php

namespace Locality\connectionDB;

include 'config.php';

class GetConnectionDB {
    private string $host;
    private string $user;
    private string $password;
    private string $database;
    private string $port;
    private $connection;

    public function __construct($host, $database, $port, $user, $password){
        $this-> host = $host;
        $this-> database = $database;
        $this-> port = $port;
        $this-> user = $user;
        $this-> password = $password;

        $this->connection = $this->setConnection();
    }

    private function setConnection(){
        try {
            echo "Conectando à base de dados ........\n\n";
            $connection = new \PDO("mysql:host={$this-> host};dbname={$this-> database};port={$this-> port}", $this-> user, $this-> password);
            $connection-> setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            echo "Conectado a {$this-> database} em {$this-> host} com sucesso.";
            echo "\n\n";
            return $connection;
        } catch (\PDOException $error) {
            die("Não foi possível se conectar ao banco de dados {$this-> database} :" . $error->getMessage() . "\n\n");
        }
    }
    public function getConnection(){
        return $this->connection;
    }
}

//$connDB= new GetConnectionDB($dataConnDB['host'], $dataConnDB['database'], $dataConnDB['port'], $dataConnDB['user'], $dataConnDB['password']);