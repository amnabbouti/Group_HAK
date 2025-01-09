<?php
include_once 'includes/init.php';

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

                $success = "New admin registered successfully with username: " . $username;
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
    <?php require_once 'includes/header.php'; ?>
    <main>
        <section>
            <h1>Register New Admin</h1>
            <?php if (!empty($errors)) : ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($errors as $error) : ?>
                            <li><?= $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif (!empty($success)) : ?>
                <div class="success-message">
                    <p><?= $success; ?></p>
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
    <?php require_once 'includes/footer.php'; ?>
</body>
</html>