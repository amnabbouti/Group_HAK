<?php
session_start();

// Restrict access to admin users
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

include_once "includes/css_js.inc.php";
require("includes/db.inc.php");
$errors = [];
$success = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $mail = trim($_POST['mail'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($firstname)) {
        $errors[] = "First name is required.";
    }
    if (empty($lastname)) {
        $errors[] = "Last name is required.";
    }
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    if (empty($errors)) {
        try {
            $db = connectToDB();
            $query = $db->prepare("SELECT id FROM users WHERE mail = ?");
            $query->execute([$mail]);
            if ($query->fetch()) {
                $errors[] = "An account with this email already exists.";
            } else {
                $candidateUsername = strtolower($firstname . '.' . $lastname);
                $username = $candidateUsername;
                $counter = 1;
                while (true) {
                    $usernameQuery = $db->prepare("SELECT id FROM users WHERE username = ?");
                    $usernameQuery->execute([$username]);
                    if (!$usernameQuery->fetch()) {
                        break;
                    }
                    $username = $candidateUsername . $counter;
                    $counter++;
                }
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $insert = $db->prepare(
                    "INSERT INTO users (firstname, lastname, mail, username, password, role, status) 
                     VALUES (?, ?, ?, ?, ?, 'admin', 1)"
                );
                $insert->execute([$firstname, $lastname, $mail, $username, $hashedPassword]);

                $success = "New admin registered successfully with username: " . htmlspecialchars($username);
            }
        } catch (PDOException $e) {
            $errors[] = "An error occurred: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Admin</title>
    <link rel="stylesheet" href="./dist/<?= $cssPath ?>">
    <link rel="stylesheet" href="./dist/<?= $cssGlobal ?>"/>

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
                    <li><a href="admin.php">Dashboard</a></li>
                    <li><a href="index.php">Main Page</a></li>
                    <li><a href="logout.php" class="btn btn-logout">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <section>
            <h1>Register New Admin</h1>
            <?php if (!empty($errors)) : ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($errors as $error) : ?>
                            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif (!empty($success)) : ?>
                <div class="success-message">
                    <p><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php endif; ?>

            <form method="post" action="admin_register.php">
                <div class="form-group">
                    <label for="firstname">First Name:</label>
                    <input type="text" name="firstname" id="firstname" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name:</label>
                    <input type="text" name="lastname" id="lastname" required>
                </div>
                <div class="form-group">
                    <label for="mail">Email:</label>
                    <input type="email" name="mail" id="mail" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="btn">Register Admin</button>
            </form>
        </section>
    </main>

    <footer>
        <div class="container">

            <div class="logo">
                <img src="public/assets/images/logo.svg" alt="Miller's World Logo">
            </div>
            <p>&copy; <?= date('Y'); ?> Your Company. All rights reserved.</p>
            <ul>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
    </footer>
</body>
</html>