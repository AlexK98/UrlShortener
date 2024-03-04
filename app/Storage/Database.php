<?php

namespace App\Storage;

use App\Config\Consts;
use App\Config\DBase as DB;
use App\Message\Message;
use PDO;
use PDOException;
use PDOStatement;

class Database extends Message
{
    private ?PDO $pdo;
    private ?PDOStatement $stmt;

    public function __construct()
    {
        // create connection
        try {
            $dsn = 'mysql:host=' . DB::$host . ';dbname=' . DB::$dbase;
            $this->connect($dsn, DB::$user, DB::$pass);
        } catch (PDOException $e) {
            // CREATION OF DATABASE AND FILLING IT WITH THINGS IS HERE SOLELY FOR DEV PURPOSES
            // =====================================================================================
            // if no database is present (1049) on host we create it and its table
            if ($e->getCode() === 1049) {
                try {
                    $dsn = 'mysql:host=' . DB::$host;
                    $this->connect($dsn, DB::$user, DB::$pass);
                } catch (PDOException $q) {
                    $this->setMessage('Error: ' . $q->getMessage());
                    exit($this->getMessage());
                }

                // create database and all related stuff
                $this->createDatabase();
                $this->useDatabase();
                $this->createTable();

                $this->setMessage('<span style="color: orange">Site is running fresh</span>');
            } else {
                $this->setMessage('Error: <span style="color: red">' . $e->getMessage() . '</span>');
            }
        }
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    private function connect(string $dsn, string $user, string $pass): void
    {
        $this->pdo = new PDO($dsn, $user, $pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function disconnect(): void
    {
        $this->stmt = null;
        $this->pdo = null;
    }

    // Database query
    public function queryDb(string $sql, array $params): bool
    {
        try {
            $this->stmt = $this->pdo->prepare($sql);
        } catch (PDOException $e) {
            $this->setMessage('Error: ' . $e->getMessage());
            return false;
        }

        foreach ($params as $key => $value) {
            if (!$this->stmt->bindValue(':' . $key, $value)) {
                $this->setMessage('Could not bind [' . $value . '] to [:' . $key . ']');
                return false;
            }
        }

        try {
            $this->stmt->execute();
        } catch (PDOException $e) {
            $this->setMessage('Error: ' . $e->getMessage());
            return false;
        }

        return true;
    }

    // Query database and return SINGLE value
    public function getColumn(string $sql, array $params): string
    {
        if (!$this->queryDb($sql, $params)) {
            $this->setMessage($this->getMessage());
            return '';
        }

        $value = $this->stmt->fetchColumn();

        if (is_bool($value)) {
            $this->setMessage('Value is NOT stored');
            return '';
        }

        return strval($value);
    }

    // ========================================================================================

    // HELPER/DEVELOPMENT ONLY METHODS
    // ===============================================================
    private function createDatabase()
    {
        $sql = 'CREATE DATABASE IF NOT EXISTS ' . DB::$dbase;
        $sql .= ' CHARACTER SET ' . DB::$charset;
        $sql .= ' COLLATE ' . DB::$collate;

        $this->stmt = $this->pdo->prepare($sql);

        $result = $this->pdo->exec($this->stmt->queryString);
        if ($result === false) {
            exit(__METHOD__ . ' failed');
        }
    }

    private function useDatabase()
    {
        // createDatabase() call should precede call of this method
        $sql = 'USE ' . DB::$dbase;
        $this->stmt = $this->pdo->prepare($sql);

        $result = $this->pdo->exec($this->stmt->queryString);
        if ($result === false) {
            exit(__METHOD__ . ' failed');
        }
    }

    private function createTable()
    {
        // useDatabase() call should precede call of this method
        $sql = 'CREATE TABLE IF NOT EXISTS ' . DB::$table;
        $sql .= ' (';
        $sql .= 'id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,';
        $sql .= DB::$colHashed . ' VARCHAR(' . Consts::HASH_MAX_LENGTH . ') NOT NULL ,';
        $sql .= DB::$colSource . ' VARCHAR(' . Consts::URL_MAX_LENGTH . ') NOT NULL ,';
        $sql .= DB::$colCreatedAt . ' DATETIME';
        $sql .= ')';

        $this->stmt = $this->pdo->prepare($sql);

        $result = $this->pdo->exec($this->stmt->queryString);

        if ($result === false) {
            exit(__METHOD__ . ' failed');
        }
    }
}
