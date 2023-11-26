<?php

    class Usuario{
        private $id;
        private $user;
        private $password;
        private $rutaimgbig;
        private $rutaimgsmall;

        public function __construct($id, $user, $password, $rutaimgbig, $rutaimgsmall){
            $this->id = $id;
            $this->user = $user;
            $this->password = $password;
            $this->rutaimgbig = $rutaimgbig;
            $this->rutaimgsmall = $rutaimgsmall;
        }

        public function __get($key){
            return $this->$key;
        }

        public function __set($key, $value){
            $this->$key = $value;
        }

        public function __toString(){
            return $this->id."|".$this->user."|".$this->user."|".$this->password."|".$this->rutaimgbig."|".$this->rutaimgsmall;
        }
    }

?>