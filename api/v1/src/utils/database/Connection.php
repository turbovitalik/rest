<?php

namespace Rest\Utils\Database;

class Connection
{
    private static $instance;

    private $connection;

    public function __construct()
    {
        $this->connection = new \PDO(
            'mysql:host=' . getenv('MYSQL_DB_HOST') . ';dbname=' . getenv('MYSQL_DB_NAME'),
            getenv('MYSQL_DB_USER'),
            getenv('MYSQL_DB_PASSWORD')
        );

        $this->connection->setAttribute(
            \PDO::ATTR_ERRMODE,
            \PDO::ERRMODE_EXCEPTION
        );
    }

    private function __clone()
    {
        // To keep this empty is enough
    }

    private function __wakeup()
    {
        // To keep this empty is enough
    }

    public static function getInstance()
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        return new self();
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
