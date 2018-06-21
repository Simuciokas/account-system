<?php

$dbServerName = "localhost";
$dbUserName = "root";
$dbPassword = "";
$dbName = "visma";

$conn = new PDO('mysql:host='.$dbServerName.';dbname='.$dbName, $dbUserName, $dbPassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);