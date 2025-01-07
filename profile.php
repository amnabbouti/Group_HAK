<?php
session_start();
require("includes/db.inc.php");
require("functions.inc.php");
require_once "includes/css_js.inc.php";
requiredLoggedIn();
$db = connectToDB();

// user id
$user_id = $_SESSION['id'] ?? null;

// Fetch
$query = $db->prepare("SELECT username, firstname, lastname, mail FROM users WHERE id = ?");
$query->execute([$user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// user niet bekend
if (!$user) {
    header("Location: login.php");
    exit;
}

// profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $mail = trim($_POST['mail']);

    // validation
    if (empty($username) || empty($firstname) || empty($lastname) || empty($mail)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        // error checks
        if ($username != $user['username'] && existingUsername($username)) {
            $error_message = "This username is already taken.";
        } elseif ($mail != $user['mail'] && existingMail($mail)) {
            $error_message = "An account with this email already exists.";
        } else {
            // Update van db users
            $update_query = $db->prepare("
                UPDATE users 
                SET username = :username, firstname = :firstname, lastname = :lastname, mail = :mail
                WHERE id = :id
            ");
            $update_query->execute([
                ':username' => $username,
                ':firstname' => $firstname,
                ':lastname' => $lastname,
                ':mail' => $mail,
                ':id' => $user_id
            ]);

            // Reload updated data
            $user = [
                'username' => $username,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'mail' => $mail
            ];
            $success_message = "Your profile has been updated successfully.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="./dist/<?= $cssPath ?>">
    <link rel="stylesheet" href="./dist/<?= $cssGlobal, ENT_QUOTES, 'UTF-8' ?>">
    <script type="module" src="./dist/<?= $jsPath, ENT_QUOTES, 'UTF-8' ?>"></script>
</head>
<body>
    <header>
        <nav>
            <a href="index.php" class="logo">
                <img src="public/assets/images/logo.svg" alt="Miller's World Logo">
            </a>
            <ul class="nav_links">
                <li><a href="index.php">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h1>Profile</h1>
            <?php if (!empty($error_message)): ?>
                <p style="color: red;"><?= $error_message, ENT_QUOTES, 'UTF-8' ?></p>
            <?php elseif (!empty($success_message)): ?>
                <p style="color: green;"><?= $success_message, ENT_QUOTES, 'UTF-8' ?></p>
            <?php endif; ?>

            <div class="profile-details">
                <p><strong>Username:</strong> <?= $user['username'], ENT_QUOTES, 'UTF-8' ?></p>
                <p><strong>First Name:</strong> <?= $user['firstname'], ENT_QUOTES, 'UTF-8' ?></p>
                <p><strong>Last Name:</strong> <?= $user['lastname'], ENT_QUOTES, 'UTF-8' ?></p>
                <p><strong>Email:</strong> <?= $user['mail'], ENT_QUOTES, 'UTF-8' ?></p>
            </div>
        </section>

        <section>
            <h2>Update Profile</h2>
            <form method="POST" action="profile.php">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username"
                       value="<?= $user['username'], ENT_QUOTES, 'UTF-8' ?>" required>
                <br>
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname"
                       value="<?= $user['firstname'], ENT_QUOTES, 'UTF-8' ?>" required>
                <br>
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname"
                       value="<?= $user['lastname'], ENT_QUOTES, 'UTF-8' ?>" required>
                <br>
                <label for="mail">Email:</label>
                <input type="email" id="mail" name="mail"
                       value="<?= $user['mail'], ENT_QUOTES, 'UTF-8' ?>" required>
                <br>
                <button type="submit">Update</button>
            </form>
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