<?php
    class Connection{
        private $user;
        private $password;
        private $host;
        private $connection;

        public function __construct($user, $password, $host){
            $this->user = $user;
            $this->password = $password;
            $this->host = $host;
        }

        public function newPDO(){
            if($this->user == null || $this->host == null){
                return false;
            }else{
                try{
                    $this->connection = new PDO($this->host, $this->user, $this->password);
                    return true;
                }catch(PDOException $e){
                    return false;
                }
            }
        }

        public function __get($key){
            return $this->$key;
        }

        public function statement($statement){
            try{
                if($this->connection != null){
                    $prepared = $this->connection->prepare($statement);
                    $prepared->execute();
                    return $prepared;
                }
            }catch(PDOException $e){
                return $e;
            }
        }
    }

?>