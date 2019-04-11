<?php
    class DatabaseInterface
    {
        private $dbConnector = null;

        function __construct()
        {
            $this->dbConnector = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
            if (mysqli_connect_errno()) {
                die("Error while connecting to the database: " . mysqli_connect_errno() . "-" . mysqli_connect_error());
            }
            $this->dbConnector->set_charset("utf8");
        }

        function __destruct()
        {
            if (!$this->dbConnector->close()) {
                die("Error closing the connection: " . $this->get_error());
            }
        }

        function query($query)
        {
            $result = $this->dbConnector->query($query);
            if (!$result) {
                printf("Error query: %s\n", $this->dbConnector->sqlstate);
            }
            return $result;
        }

        function start_transaction()
        {
            $this->dbConnector->autocommit(false);
        }

        function end_transaction()
        {
            $this->dbConnector->commit();
            $this->dbConnector->autocommit(true);
        }

        function rollback_transaction()
        {
            $this->dbConnector->rollback();
            $this->dbConnector->autocommit(true);
        }

        function secure($input)
        {
            $input = strip_tags($input);
            $input = htmlentities($input);
            $input = stripcslashes($input);
            return $this->dbConnector->real_escape_string($input);
        }

        function get_error()
        {
            return $this->dbConnector->error;
        }
    }
?>
