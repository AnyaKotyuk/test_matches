<?php

/**
 * Class DB
 * Work with database easier
 */
class DB{

    private static $instance = null;
    private $USER = 'matches';
    private $PASSWORD = 'matches';
    private $DATABASE = 'matches';
    private $HOST = 'localhost';
    private $connection;


    /**
     * Create DB connection
     */
    private function __construct()
    {
        $this->connection = mysqli_connect($this->HOST, $this->USER, $this->PASSWORD, $this->DATABASE) or die('Connection error');
    }


    /**
     * Get instance
     *
     * @return DB|null
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    /**
     * Query sql
     *
     * @param $q
     * @return mixed
     */
    public function db_query($q)
    {
        $res = $this->connection->query($q);
        if (!$res) die ('Query error!');
        return $res;
    }

}