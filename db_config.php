<?php
    class Database{
        private $host = "162.240.219.174";
        private $db_name = "wangfood_onlinecatalog";
        private $username = "wangfood_onlinecatalog";
        private $password = "Wang2025!~";  
        public $connection;

        public function dbConnect(){
            $this->connection = null;
            try{
                $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            }
            catch(PDOException $exception){
                echo "Error połączenia: " . $exception->getMessage();
            }
            
            return $this->connection;
        }
    }
?>