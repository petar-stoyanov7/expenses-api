<?php

namespace Core;

use PDOException;
use PDO;

abstract class DbModelAbstract
{
    protected $connection;
    private $hostname;
    private $database;
    private $username;
    private $password;
    private $options;

    public function __construct()
    {
        $Config = new Config();
        $config = $Config->getConfigDetail('db');
        $this->hostname = $config['host'];
        $this->username = $config['usr'];
        $this->password = $config['pwd'];
        $this->database = $config['db'];
        $this->options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        ];
        try {
            $this->connection = new \PDO(
                "mysql:host=$this->hostname; 
                dbname=$this->database",
                $this->username,
                $this->password,
                $this->options
            );
        } catch(PDOException $e) {
            die("Could not connect: $e->getMessage()");
        }
    }

    public function getData($sql, $values = null) {
        if (null !== $values) {
            $result = $this->connection->prepare($sql);
            $result->execute($values);
            return $result->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $result = $this->connection->prepare($sql);
            $result->execute();
            return $result->fetchAll(\PDO::FETCH_ASSOC);
        }
    }

    public function execute($sql, $values = null) {
        if (null !== $values) {
            try {
                $statement = $this->connection->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
                $statement->execute($values);
                return $this->connection->lastInsertId();
            } catch (PDOException $e) {
                die('DB Error: '. $e->getMessage());
            }
        } else {
            try {
                $this->connection->query($sql);
            } catch (PDOException $e) {
                die('Error in execution: ' . $e->getMessage());
            }
        }
    }
}