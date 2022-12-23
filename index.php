<?php
include 'src/include.php';

use litePDO\SQL;

$params = new SQL('config.php');
var_dump($params->getParams());

$params::q1("SHOW TABLES;");