<?php

$DB_SERVER = "mysql-server";
$DB_USER  = "root";
$DB_PASS  = "secret";
$DB_NAME = "php-ajax-optional";

$dsn = "mysql:host=$DB_SERVER;dbname=$DB_NAME;";

// var_dump($dsn);

try {
    $conn = new PDO($dsn, $DB_USER, $DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($conn) {
        //	 echo "Connected to the $DB_NAME database successfully!";
    }
} catch (PDOException $e) {
    echo $e->getMessage();
    die();
}
