<?php

namespace litePDO;

use PDO;
use configConect\configFile;
use PDOException;

class SQL
{
    public $db,$host,$user,$pass, $params;
    private static $instance;
    private $pdo;

    /** Init and connect */
    function __construct()
    {
        $params = new configFile();
        $this->param = $params->getParams();
        $this->db = $this->param['db'];
        $this->host = $this->param['host'];
        $this->user = $this->param['username'];
        $this->pass = $this->param['password'];
        try{       
            $this->pdo = new PDO('mysql:dbname=' . $this->db . ';host=' . $this->host . ';charset=utf8', $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec('set names utf8');
            $this->pdo->exec('SET SESSION group_concat_max_len = 1000000');
            $this->pdo->exec("SET sql_mode=''");
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            die();
        }
    }

    // public static function init()
    // {
    //     $dbConnection = null;
        // if (in_array($_SERVER['SERVER_NAME'], ['localhost'])) {
        //     ini_set('display_errors', 'On');
        //     error_reporting(E_ALL & ~E_NOTICE);
        // } else {
        //     ini_set('display_errors', 'Off');
        // }

        // if (file_exists(__DIR__ . '/../db.cfg.php')) {
        //     include __DIR__ . '/../db.cfg.php';
        //     $dbConnection = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';charset=utf8', DB_USER, DB_PASS);
        // } else {
        //     // if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/db.cfg.php')) {
        //     //     include $_SERVER['DOCUMENT_ROOT'] . '/db.cfg.php';
        //     //     $dbConnection = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';charset=utf8', DB_USER, DB_PASS);
        //     // }

        //    // можно добавить свои настройки на прямую, но не желательно
        //     // else {
        //     // $dbConnection = new PDO('mysql:dbname='database';host='localhost';charset=utf8', 'user', 'password');
        //     //}
        // }
        // $bd = $this->db;
    //     $dbConnection = new PDO('mysql:dbname=' . $this->db . ';host=' . $this->host . ';charset=utf8', $this->user, $this->pass);
    //     $dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //     $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //     $dbConnection->exec('set names utf8');
    //     $dbConnection->exec('SET SESSION group_concat_max_len = 1000000');
    //     $dbConnection->exec("SET sql_mode=''");

    //     return $dbConnection;
    // }

    public function getParams() {
        return $this->db;   
    }

    public static function q($sql, $params = [])
    {
        // $dbConnection = self::getInstance();

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            if (in_array($_SERVER['SERVER_NAME'], ['localhost'])) {
                // echo '<pre>';
                // echo $sql;
                // echo "\n\n\n------- \n\n\n";
                // print_r($params);
                die("Method q() - Произошла ошибка в SQL-запросе. Обратитесь к Вашему менеджеру. <br />");
            } else {
                // echo '<pre>';
                // print_r($e);
                // echo "\n\n\n------- \n\n\n";
                // echo $sql;
                die("Method q() - Произошла ошибка в SQL-запросе. Обратитесь к Вашему менеджеру. <br />");
            }
        }
    }

    public static function q1($sql, $params = [])
    {
         $dbConnection = self::getInstance();

        try {
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            if (in_array($_SERVER['SERVER_NAME'], ['localhost'])) {
                // echo '<pre>';
                // print_r($e);
                // echo "\n\n\n------- \n\n\n";
                // echo $sql;
                // echo "\n\n\n------- \n\n\n";
                // print_r($params);
                die("Method q1() - Произошла ошибка в SQL-запросе. Обратитесь к Вашему менеджеру.<br />");
            } else {
                // echo '<pre>';
                // print_r($e);
                // echo "\n\n\n------- \n\n\n";
                // echo $sql;
                // echo "\n\n\n------- \n\n\n";
                die("Method q1() - Произошла ошибка в SQL-запросе. Обратитесь к Вашему менеджеру.<br />");
            }
        }
    }

    public static function qi($sql, $params = [], $ignore_exceptions = 0)
    {
        // $dbConnection = self::getInstance();
        try {
            $stmt = $this->pdo->prepare($sql);

            if ($stmt->execute($params)) {
                return $this->pdo->lastInsertId();
            }

            return false;
        } catch (PDOException $e) {
            if ($ignore_exceptions) {
                return;
            }
            if (in_array($_SERVER['SERVER_NAME'], ['localhost'])) {
                // echo '<pre>';
                // print_r($e);
                // echo "\n\n\n------- \n\n\n";
                // echo $sql;
                // echo "\n\n\n------- \n\n\n";
                // print_r($params);
                die("Method qi() - Произошла ошибка в SQL-запросе. Обратитесь к Вашему менеджеру.<br />");
            } else {
                die("Method qi() - Произошла ошибка в SQL-запросе. Обратитесь к Вашему менеджеру.<br />");
            }
        }
    }

    public static function qCount($sql, $params = [])
    {
        // $dbConnection = self::getInstance();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn();
    }

    public static function qRows()
    {
        // $dbConnection = self::getInstance();
        $stmt = $$this->pdo->query('SELECT FOUND_ROWS() as num');

        return $stmt->fetchColumn(0);
    }
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->pdo, $method), $args);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
