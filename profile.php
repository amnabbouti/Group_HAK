<?php
require_once 'init.php';
requiredLoggedIn();

$user_id = $_SESSION['id'] ?? null;
$users = getAllUsers($user_id);
$user = $users[0] ?? null;
if (!$user) {
    header("Location: login.php"); // Redirect if no user
    exit;
}
$user = default_profile_picture($user);

// profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $mail = trim($_POST['mail']);
    $profile_picture = $user['profile_picture'];

    // Validate required fields
    if (empty($username) || empty($firstname) || empty($lastname) || empty($mail)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        // Check for username and email conflicts
        if ($username != $user['username'] && existingUsername($username)) {
            $error_message = "This username is already taken.";
        } elseif ($mail != $user['mail'] && existingMail($mail)) {
            $error_message = "An account with this email already exists.";
        } else {
            // profile pic
            if (!empty($_FILES['profile_picture']['name'])) {
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $upload_dir = 'uploads/';
                $file_name = basename($_FILES['profile_picture']['name']);
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $file_size = $_FILES['profile_picture']['size'];
                $file_tmp_name = $_FILES['profile_picture']['tmp_name'];
                $new_file_name = $upload_dir . uniqid('', true) . '.' . $file_ext;

                if (!in_array($file_ext, $allowed_extensions)) {
                    $error_message = "Invalid file type. Allowed types: JPG, JPEG, PNG, GIF.,webP";
                } elseif ($file_size > 2 * 1024 * 1024) {
                    $error_message = "File size must be less than 2MB.";
                } else {
                    if (move_uploaded_file($file_tmp_name, $new_file_name)) {
                        $profile_picture = $new_file_name;

                        // If the user already has a pic delete the old file
                        if (!empty($user['profile_picture']) && file_exists($user['profile_picture'])) {
                            unlink($user['profile_picture']);
                        }
                    } else {
                        $error_message = "Failed to upload the profile picture.";
                    }
                }
            }

            if (empty($error_message)) {
                $update_query = $db->prepare("
                    UPDATE users
                    SET username = :username, firstname = :firstname, lastname = :lastname, mail = :mail, profile_picture = :profile_picture
                    WHERE id = :id
                ");
                $update_query->execute([
                    ':username' => $username,
                    ':firstname' => $firstname,
                    ':lastname' => $lastname,
                    ':mail' => $mail,
                    ':profile_picture' => $profile_picture,
                    ':id' => $user_id
                ]);

                $user = [
                    'username' => $username,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'mail' => $mail,
                    'profile_picture' => $profile_picture
                ];
                $success_message = "Your profile has been updated successfully.";
            }
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
    <link rel="stylesheet" href="./dist/<?= $cssGlobal ?>">
    <script type="module" src="./dist/<?= $jsPath ?>"></script>
    <script src="https://kit.fontawesome.com/f5cdfe48d9.js" crossorigin="anonymous"></script>
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
                <li>
                    <a href="profile.php" class="profile-picture-header">
                        <?php if (!empty($user['profile_picture']) && file_exists($user['profile_picture'])): ?>
                            <img src="<?= $user['profile_picture']; ?>"
                                 alt="Profile Picture"
                                 style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                        <?php else: ?>
                            <img src="public/assets/images/user.png"
                                 alt="Default Profile Picture"
                                 style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="section1">
            <?php if (!empty($error_message) || !empty($success_message)): ?>
            <div class="notification <?= !empty($error_message) ? 'error' : 'success' ?> show">
                <?= !empty($error_message) ? $error_message : $success_message ?>
                </div><?php endif; ?>

            <?php if (!empty($error_message) || !empty($success_message)): ?>
                <script>
                    setTimeout(() => {
                        document.querySelector('.notification').classList.remove('show');
                    }, 4000);
                </script>
            <?php endif; ?>

            <div class="profile-details">
                <img src="<?= $user['profile_picture'] ?? 'uploads/default-profile.png' ?>" alt="Profile Picture">
                <span class="change-picture-icon" onclick="document.getElementById('profile_picture').click();">
                <i class="fa-solid fa-camera fa-2x"></i></span>
                <h2> <?= $user['firstname'] . ' ' . $user['lastname'] ?></h2>
            </div>
        </section>

        <section class="update">
            <h2>Update Profile</h2>
            <form id="profile-form" method="POST" action="profile.php" enctype="multipart/form-data">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?= $user['username'] ?>" required>
                <br>
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" value="<?= $user['firstname'] ?>" required>
                <br>
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" value="<?= $user['lastname'] ?>" required>
                <br>
                <label for="mail">Email:</label>
                <input type="email" id="mail" name="mail" value="<?= $user['mail'] ?>" required>
                <br>
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" id="profile_picture" name="profile_picture">
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