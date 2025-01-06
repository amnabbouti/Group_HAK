<?php

require("includes/db.inc.php");
require("functions.php");


requiredLoggedOut();

$errors = [];

if (isset($_POST['mail'])) {

    if (!strlen($_POST['mail'])) {
        $errors[] = "Please fill in e-mail.";
    }

    if (!strlen($_POST['password'])) {
        $errors[] = "Please fill in password.";
    }

    if ($uid = isValidLogin($_POST['mail'], $_POST['password'])) {

        setLogin();

        header("Location: admin.php");
        exit;
    } else {
        $errors[] = "E-mail and/or password is not correct.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <title>Login</title>
</head>

<body>
    <header>
        <nav>
            <div class="search">
                <!-- Planet Search -->
                <form method="get" action="">
                    <input type="text" name="name" placeholder="Search for a planet..."
                           value="<?= $_GET['name'] ?? '' ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
            <a href="index.php" class="logo">
                <img src="public/assets/images/logo.svg" alt="Miller's World Logo">
            </a>
            <div>
                <ul class="nav_links">
                    <li><a href="#">Add a planet</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Log In</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <section>
        <h1>Login</h1>
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
            <form method="post" action="login.php">
                <div class="mail">
                    <label for="mail">E-mail</label>
                    <input type="email" id="mail" name="mail">
                </div>
                <div class="password">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"/>
                </div>
                <button type="submit" value="submit" name="submit">Login</button>
            </form>
            <div>
                <p>Don't have an account? <a href="register.php">Sign Up</a>
                </p>
            </div>
        </div>
    </section>
    <footer>
        <div class="container">

            <div class="logo">
                <img src="public/assets/images/logo.svg" alt="Miller's World Logo">
            </div>
            <?= "php works on the main & footer" ?>
            <ul>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
    </footer>
</body>

</html><