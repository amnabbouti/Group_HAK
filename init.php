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

//requires login
if (in_array(basename($_SERVER['PHP_SELF']), ['admin.php', 'delete.php', 'form.php', 'login.php', 'profile.php', 'register.php']) && !isLoggedIn()) {
    requiredLoggedIn();
}

// extra security
if (basename($_SERVER['PHP_SELF']) === 'admin.php' || basename($_SERVER['PHP_SELF']) === 'admin_register.php') {
    if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
        header("Location: login.php");
        exit;
    }
}