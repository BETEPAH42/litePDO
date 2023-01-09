<?php

namespace configConect;

class configFile {

    private $db = "";
    // public $type = "";
    private $host = "";
    private $username = "";
    private $password = "";
    private static $instance;

    function __construct()
    {
        if(file_exists('config.php')) {
            $params = [
                'db'=>'',
                'host'=>'',
                'username'=>'',
                'password'=>'',
            ];
            include_once('config.php');
            // $this->type = '';
            $this->db = $params['db'];
            $this->host = $params['host'];
            $this->username = $params['username'];
            $this->password = $params['password'];
        }
    }

    function getParams() {
        $db = [
            'db'        =>  $this->db,
            'host'      =>  $this->host,
            'username'  =>  $this->username,
            'password'  =>  $this->password,
        ];
        return $db;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getStringDsnMySQL() {
        return "mysql:dbname={$this->db};host={$this->host};charset=utf8";
    } 
    
    public function getUsername() {
       return $this->username;
    }   

    public function getPassword() {
        return $this->password;
    }
}