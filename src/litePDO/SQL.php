<?php

namespace litePDO;

use PDO;
use configConect\configFile;
use PDOException;

class SQL 
{
    protected $params;
    private static $instance;
    private $pdo;

    /** Init and connect */
    public function __construct()
    {
        include_once 'configConnect.php';
        $params = new configFile();
        $this->params = $params->getParams();

        try{
            $this->pdo = new PDO($params->getStringDsnMySQL(),$params->getUsername(),$params->getPassword());
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec('set names utf8');
            $this->pdo->exec('SET SESSION group_concat_max_len = 1000000');
            $this->pdo->exec("SET sql_mode=''");
        } catch (PDOException $e) {
            print "Error!: <pre>" . print_r($e)."</pre>";
            die();
        } 
    }

    public static function q($sql, $params = [])
    {
        $dbConnection = self::getInstance()->pdo;

        try {
            $stmt = $dbConnection->prepare($sql);
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
        $dbConnection = self::getInstance()->pdo;

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
        $dbConnection = self::getInstance()->pdo;
        try {
            $stmt = $dbConnection->prepare($sql);

            if ($stmt->execute($params)) {
                return $dbConnection->lastInsertId();
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
        $dbConnection = self::getInstance()->pdo;
        $stmt = $dbConnection->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn();
    }

    public static function qRows()
    {
        $dbConnection = self::getInstance()->pdo;
        $stmt = $dbConnection->query('SELECT FOUND_ROWS() as num');

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
