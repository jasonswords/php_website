<?php

namespace Itb;

class Database
{
    const DB_NAME = 'project';
    const DB_USER = 'root';
    const DB_PASS = 'toor';
    const DB_HOST = 'localhost:3306';

    private $connection;

    public function getConnection()
    {
        return $this->connection;
    }

    public function __construct()
    {
        try {
            $dsn = 'mysql:dbname=' . self::DB_NAME . ';host=' . self::DB_HOST;
            $this->connection = new \PDO(
                $dsn,
                self::DB_USER,
                self::DB_PASS
            );
        } catch (\Exception $e){
            print '<pre>';
            var_dump($e);
        }
    }



}



