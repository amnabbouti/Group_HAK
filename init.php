<?php
session_start();
require_once "includes/css_js.inc.php";
require_once "includes/db.inc.php";
require_once 'functions.inc.php';
require_once 'vendor/autoload.php';
$db = connectToDB();
if (basename($_SERVER['PHP_SELF']) === 'admin.php' && !empty($_SESSION['refresh_page'])) {
    echo "<script>location.reload();</script>";
    unset($_SESSION['refresh_page']);
}
