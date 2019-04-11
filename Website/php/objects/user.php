<?php
    class User
    {
        public $username;
        public $password;

        function __construct($u, $p) {
            $this->username = $u;
            $this->password = $p;
        }
    }
?>
