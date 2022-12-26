<?php

 phpinfo();

include 'src/include.php';
 
use litePDO\SQL;

//print_r(PDO::getAvailableDrivers());
$params = new SQL('config.php');
var_dump($params->getParams());

$params::q1("SHOW TABLES;");