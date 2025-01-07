<?php
include_once "includes/css_js.inc.php";
require("includes/db.inc.php");
require("functions.php");


requiredLoggedOut();

$errors = [];

$username = "";
$firstname = "";
$lastname = "";
$mail = "";
$password = "";

if (isset($_POST['submit'])) {
    if (!isset($_POST['username'])) {
        $errors[] = "Username is required.";
    } else {
        $username = $_POST['username'];

        if (strlen($username) < 1) {
            $errors[] = "Username is required.";
        }
        if (!preg_match("/^[a-zA-Z0-9_-]+$/", $username)) {
            $errors[] = "Username can not contain spaces or special characters.";
        }
        if (existingUsername($username) == true) {
            $errors[] = "Username already exists.";
        }
    }

    if (!isset($_POST['firstname'])) {
        $errors[] = "First name is required.";
    } else {
        $firstname = $_POST['firstname'];

        if (strlen($firstname) < 1) {
            $errors[] = "First name is required.";
        }
        if (preg_match("/[^a-zA-Z\s'-]/", $firstname)) {
            $errors[] = "First name can not contain special characters";
        }
    }

    if (!isset($_POST['lastname'])) {
        $errors[] = "Last name is required.";
    } else {
        $lastname = $_POST['lastname'];

        if (strlen($lastname) < 1) {
            $errors[] = "Last name is required.";
        }

        if (preg_match("/[^a-zA-Z\s'-]/", $lastname)) {
            $errors[] = "First name can not contain special characters";
        }
    }

    if (!isset($_POST['inputmail'])) {
        $errors[] = "E-mail is required.";
    } else {
        $mail = $_POST['inputmail'];
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "E-mail address is invalid.";
        }
        if (existingMail($mail) == true) {
            $errors[] = "Mail already exists.";
        }
    }

    if (!isset($_POST['password'])) {
        $errors[] = "Password is required.";
    } else {
        $password = $_POST['password'];
        if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/", $password)) {
            $errors[] = "Password needs to contain at least 1 uppercase letter, 1 lowercase, 1 symbol, 1 number and needs to be at least 8 characters long.";
        }
    }

    if (count($errors) == 0) { // er werden geen fouten geregistreerd tijdens validatie
        $newId = registerNewMember($username, $firstname, $lastname, $mail, $password);
        if (!$newId) {
            $errors[] = "An unknown error has occured, pleace contact us...";
        } else {
            setLogin();
            $_SESSION['message'] = "Welcome $firstname!";
            header("Location: admin.php");
            exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./dist/<?= $cssPath ?>"/>
    <link rel="stylesheet" href="./dist/<?= $cssGlobal ?>"/>
    <script type="module" src="./dist/<?= $jsPath ?>"></script>

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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="login.php">Log In</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="registration">
            <h1>Register</h1>
            <?php if (count($errors)): ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form method="post" action="register.php">
                <div class="form-group username">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= $username; ?>"/>
                </div>
                <div class="form-group firstname">
                    <label for="firstname">Firstname</label>
                    <input type="text" id="firstname" name="firstname" value="<?= $firstname; ?>"/>
                </div>
                <div class="form-group lastname">
                    <label for="lastname">Lastname</label>
                    <input type="text" id="lastname" name="lastname" value="<?= $lastname; ?>"/>
                </div>
                <div class="form-group mail">
                    <label for="mail">E-mail</label>
                    <input type="email" id="mail" name="mail" value="<?= $mail; ?>">
                </div>
                <div class="form-group password">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" value="<?= $password; ?>"/>
                </div>
                <button type="submit" value="submit" name="submit">Register</button>
            </form>
            <div class="login-link">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </section>
    </main>
    <footer>
        <div class="container">
            <div class="logo">
                <img src="public/assets/images/logo.svg" alt="Miller's World Logo">
            </div>
            <?= "php works on the main & footer" ?>
            <ul class="footer-links">
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
    </footer>
</body>

</html>