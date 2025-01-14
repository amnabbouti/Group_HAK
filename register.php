<?php
include_once 'includes/init.php';
$errors = [];
$success = [];
$username = "";
$firstname = "";
$lastname = "";
$mail = "";
$password = "";
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$id = "";


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user = getUserById($id);
    if ($user) {
        $username = $user['username'];
        $firstname = $user['firstname'];
        $lastname = $user['lastname'];
        $mail = $user['mail'];
    } else {
        $errors[] = "User not found.";
    }
}


if (isset($_POST['submit'])) {
    if (!isset($_POST['username']) || strlen($_POST['username']) < 1) {
        $errors[] = "Username is required.";
    } else {
        $username = $_POST['username'];
        if (!preg_match("/^[a-zA-Z0-9_-]+$/", $username)) {
            $errors[] = "Username can not contain spaces or special characters.";
        }
        if (existingUsername($username) && $username != $user['username']) {
            $errors[] = "Username already exists.";
        }
    }

    if (!isset($_POST['firstname']) || strlen($_POST['firstname']) < 1) {
        $errors[] = "First name is required.";
    } else {
        $firstname = $_POST['firstname'];
        if (preg_match("/[^a-zA-Z\s'-]/", $firstname)) {
            $errors[] = "First name can not contain special characters.";
        }
    }

    if (!isset($_POST['lastname']) || strlen($_POST['lastname']) < 1) {
        $errors[] = "Last name is required.";
    } else {
        $lastname = $_POST['lastname'];
        if (preg_match("/[^a-zA-Z\s'-]/", $lastname)) {
            $errors[] = "Last name can not contain special characters.";
        }
    }

    if (!isset($_POST['mail']) || !filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid E-mail is required.";
    } else {
        $mail = $_POST['mail'];
        if (existingMail($mail) && $mail != $user['mail']) {
            $errors[] = "Mail already exists.";
        }
    }

    if (isset($_POST['password']) && strlen($_POST['password']) > 0) {
        $password = $_POST['password'];
        // added a "." as regex for the username, we can delete it afterwards.
        if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/", $password)) {
            $errors[] = "Password needs to contain at least 1 uppercase letter, 1 lowercase, 1 symbol, 1 number and needs to be at least 8 characters long.";
        }
    }
    if (count($errors) == 0) {
        if ($id) {
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                updateMember($id, $username, $firstname, $lastname, $mail, $hashedPassword);
            } else {
                updateMember($id, $username, $firstname, $lastname, $mail, $hashedPassword);
            }
            $success[] = "User updated successfully.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $newId = registerNewMember($username, $firstname, $lastname, $mail, $hashedPassword, 'user');
            if ($newId) {
                setLogin($newId);
                $_SESSION['id'] = $newId;
                $_SESSION['message'] = "Welcome $firstname!";
                header("Location: profile.php");
                exit;
            } else {
                $errors[] = "An unknown error has occurred, please contact us...";
            }
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
            <h1><?php echo $id ? "Edit User" : "Register"; ?></h1>
            <?php if (count($errors)): ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error; ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php elseif (count($success)): ?>
                    <div class="success-messages">
                        <ul>
                            <?php foreach ($success as $message): ?>
                                <li><?= $message; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="form-wrapper">
                    <form method="post" action="register.php?id=<?= $id; ?>">
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
                            <input type="password" id="password" name="password"
                                   placeholder="Enter a new password (optional)"/>
                        </div>
                        <button type="submit" name="submit">Save</button>
                    </form>
                </div>
        </section>
    </main>
    <?php require_once 'includes/footer.php'; ?>
</body>

</html>