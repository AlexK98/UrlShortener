<?php

namespace App\Storage;

use App\Config\DBase as DB;
use App\Helpers\DateHelper;
use App\Message\Message;

class LinkStorage extends Message
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function store(string $linkHash, string $sourceLink): bool
    {
        if (empty($linkHash) || empty($sourceLink)) {
            $this->setMessage('Can not store. Some of data is empty.');
            return false;
        }

        $sql = 'INSERT INTO ' . DB::$table;
        $sql .= ' (' . DB::$colHashed . ', ' . DB::$colSource . ', ' . DB::$colCreatedAt . ') ';
        $sql .= 'VALUES (:' . DB::$colHashed . ', :' . DB::$colSource . ', :' . DB::$colCreatedAt . ')';

        $params = [
            DB::$colHashed => $linkHash,
            DB::$colSource => $sourceLink,
            DB::$colCreatedAt => DateHelper::getDate('Y-m-d H:i:s')
        ];

        if (!$this->db->queryDb($sql, $params)) {
            $this->setMessage('Failed to store URL!');
            return false;
        }

        return true;
    }

    public function isHashStored(string $linkHash): bool
    {
        $sql = 'SELECT ' . DB::$colHashed;
        $sql .= ' FROM ' . DB::$table;
        $sql .= ' WHERE ' . DB::$colHashed . ' = :' . DB::$colHashed;

        $params = [
            DB::$colHashed => $linkHash
        ];

        if (!$this->db->getColumn($sql, $params)) {
            $this->setMessage('Hash is not stored in DB');
            return false;
        }

        return true;
    }

    public function getLongUrlByHash(string $linkHash): string
    {
        $sql = 'SELECT ' . DB::$colSource;
        $sql .= ' FROM ' . DB::$table;
        $sql .= ' WHERE ' . DB::$colHashed . ' = :' . DB::$colHashed;

        $params = [
            DB::$colHashed => $linkHash
        ];

        return $this->db->getColumn($sql, $params);
    }
}
