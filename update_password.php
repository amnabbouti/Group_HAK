<?php
include_once 'includes/init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $reset = getToken($token);

    if ($reset && strtotime($reset['expiry']) > time()) {
        updatePassword($reset['user_id'], $password);
        deleteToken($token);
        echo "Your password has been reset successfully.";
    } else {
        echo "Invalid or expired token.";
    }
}

?>