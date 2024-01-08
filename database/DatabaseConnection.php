<?php
class DatabaseConnection {
    private $host = 'db';
    private $username = 'testuser';
    private $password = 'testpassword';
    private $database = 'testdb';

    protected $connection;

    public function __construct() {
        if (!isset($this->connection)) {
            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

            if (!$this->connection) {
                echo 'Cannot connect to database server';
                exit;
            }
        }

        return $this->connection;
    }
}
?>
