<?php

namespace Osmo;

use Osmo\Validation;

class Database
{
    public \PDO $connection;
    private array $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;

        if(!array_key_exists('servername', $this->data)) {
            $this->data['servername'] = 'localhost';
        }

        if(!array_key_exists('port', $this->data)) {
            $this->data['port'] = 3306;
        }

        if(!array_key_exists('table', $this->data)) {
            $this->data['table'] = 'users';
        }
    }

    public function setServername(string $servername)
    {
        $this->data['servername'] = $servername;
    }

    public function setDatabase(string $database)
    {
        $this->data['database'] = $database;
    }

    public function setUsername(string $username)
    {
        $this->data['username'] = $username;
    }

    public function setPassword(string $password)
    {
        $this->data['password'] = $password;
    }

    public function setPort(int $port)
    {
        $this->data['port'] = $port;
    }

    public function setTable(string $table)
    {
        $this->data['table'] = $table;
    }

    public function getTable(): string
    {
        return $this->data['table'];
    }

    public function start()
    {
        Validation::required([
            $this->data['servername'], $this->data['username'], $this->data['database']
        ]);

        $servername = $this->data['servername'];
        $database = $this->data['database'];
        $port = $this->data['port'];

        try {
            $this->connection = new \PDO("mysql:host=$servername;dbname=$database;port=$port", $this->data['username'], $this->data['password']);
            $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        return $this->connection;
    }
}