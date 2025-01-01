<?php
require 'vendor/autoload.php';
function connectToDB()
{
    $db_host = getenv('DB_HOST') ?: 'localhost';
    $db_user = getenv('DB_USER') ?: 'root';
    $db_password = getenv('DB_PASSWORD') ?: 'root';
    $db_db = getenv('DB_NAME') ?: 'db_planets';
    $db_port = getenv('DB_PORT') ?: 8889;

    try {
        $db = new PDO(
            'mysql:host=' . $db_host . ';port=' . $db_port . ';dbname=' . $db_db,
            $db_user,
            $db_password
        );
    } catch (PDOException $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        die();
    }
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $db;
}

//function connectToDB()
//{
//    $db_host = 'localhost';
//    $db_user = 'root';
//    $db_password = 'root';
//    $db_db = 'db_planets';
//    $db_port = 8889;
//
//    try {
//        $db = new PDO('mysql:host=' . $db_host . '; port=' . $db_port . '; dbname=' . $db_db, $db_user, $db_password);
//    } catch (PDOException $e) {
//        echo "Error!: " . $e->getMessage() . "<br />";
//        die();
//    }
//    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
//    return $db;
//}