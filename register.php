<?php

require("includes/db.inc.php");
require("functions.php");

requiredLoggedOut();

$errors = [];

$firstname = "";
$lastname = "";
$mail = "";
$password = "";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <section>
        <h1>Register</h1>
        <?php if (count($errors)): ?>
            <div>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div>
            <form method="post" action="register.php">
                <div class="firstname">
                    <label for="firstname">Firstname</label>
                    <input type="text" id="firstname" name="firstname" value="<?= $firstname; ?>" />
                </div>
                <div class="lastname">
                    <label for="lastname">Lastname</label>
                    <input type="text" id="lastname" name="lastname" value="<?= $lastname; ?>" />
                </div>
                <div class="mail">
                    <label for="mail">E-mail</label>
                    <input type="email" id="mail" name="mail" value="<?= $mail; ?>">
                </div>
                <div class="password">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" value="<?= $password; ?>" />
                </div>
                <button type="submit" value="submit" name="submit">Register</button>
            </form>
            <div>
                <p>Already have an account? <a href="login.php">Login</a>
                </p>
            </div>
        </div>
    </section>

</body>

</html>