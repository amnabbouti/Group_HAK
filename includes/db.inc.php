<?php
require 'vendor/autoload.php';
function connectToDB()
{
    $db_host = getenv('DB_HOST') ?: 'j9mt6.h.filess.io';
    $db_user = getenv('DB_USER') ?: 'planets_fellowduty';
    $db_password = getenv('DB_PASSWORD') ?: '318ff839f41592cfd90fedf58d90c5a19c839364';
    $db_db = getenv('DB_NAME') ?: 'planets_fellowduty';
    $db_port = getenv('DB_PORT') ?: 3307;

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
