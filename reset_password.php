<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    die('Invalid token.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <form action="update_password.php" method="post">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <label for="password">Enter new password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>