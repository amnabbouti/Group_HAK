<?php
include_once 'includes/init.php';
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
        if (existingUsername($username)) {
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
            $errors[] = "First name can not contain special characters.";
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
            $errors[] = "Last name can not contain special characters.";
        }
    }
    if (!isset($_POST['mail'])) {
        $errors[] = "E-mail is required.";
    } else {
        $mail = $_POST['mail'];
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "E-mail address is invalid.";
        }
        if (existingMail($mail)) {
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
    if (count($errors) == 0) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $newId = registerNewMember($username, $firstname, $lastname, $mail, $hashedPassword, 'user');
        if (!$newId) {
            $errors[] = "An unknown error has occurred, please contact us...";
        } else {
            setLogin($newId);
            $_SESSION['id'] = $newId; // Store user ID in session
            $_SESSION['message'] = "Welcome $firstname!";
            header("Location: profile.php");
            exit;
        }
    }
}
?>

<html lang="en">
<?php require_once 'includes/head.php'; ?>
<body>
    <?php require_once 'includes/header.php'; ?>
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
            <div class="form-wrapper">
                <form method="post" action="register.php">
                    <div class="form-group username">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter a username"
                               value="<?= $username; ?>"/>
                    </div>
                    <div class="form-group firstname">
                        <label for="firstname">Firstname</label>
                        <input type="text" id="firstname" name="firstname" placeholder="Enter your Firstname"
                               value="<?= $firstname; ?>"/>
                    </div>
                    <div class="form-group lastname">
                        <label for="lastname">Lastname</label>
                        <input type="text" id="lastname" name="lastname" placeholder="Enter your Lastname"
                               value="<?= $lastname; ?>"/>
                    </div>
                    <div class="form-group mail">
                        <label for="mail">E-mail</label>
                        <input type="email" id="mail" name="mail" placeholder="Enter a valid E-mail"
                               value="<?= $mail; ?>">
                    </div>
                    <div class="form-group password">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter a valid Password"/>
                    </div>
                    <button type="submit" value="submit" name="submit">Register</button>
                </form>
                <div class="signup-link">
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </div>
            </div>
        </section>
    </main>
    <?php require_once 'includes/footer.php'; ?>
</body>
</html>