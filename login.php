<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require("includes/db.inc.php");
include_once "includes/css_js.inc.php";
require("functions.inc.php");


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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./dist/<?= $cssPath ?>"/>
    <link rel="stylesheet" href="./dist/<?= $cssGlobal ?>"/>
    <script type="module" src="./dist/<?= $jsPath ?>"></script>
</head>

<body>
    <header>
        <nav>
            <div class="search">
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
                    <li><a href="index.php">HOME</a></li>
                    <li><a href="#">PPROFILE</a></li>
                    <li><a href="#">CONTACT US</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <section>
            <h1>Login</h1>
            <?php if (count($errors)): ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <div class="form-wrapper">
                <form method="post" action="login.php" class="login-form">
                    <div class="form-group">
                        <label for="mail">E-mail</label>
                        <input type="email" id="mail" name="mail" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password"
                               required/>
                    </div>
                    <div class="password">
                        <p>Forgot your password? <a href="reset-password.php">Click here to reset it.</a></p>
                    </div>
                    <div class="form-actions">
                        <button type="submit" value="submit" name="submit" class="btn">Login</button>
                    </div>
                </form>
                <div class="signup-link">
                    <p>Don't have an account? <a href="register.php">Sign Up</a></p>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <div class="container">
            <div class="logo">
                <img src="public/assets/images/logo.svg" alt="Miller's World Logo">
            </div>
            <ul>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
    </footer>
</body>

</html>