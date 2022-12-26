<?php

namespace configConect;

class configFile {

    public $db = "";
    // public $type = "";
    public $host = "";
    public $username = "";
    public $password = "";

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
}