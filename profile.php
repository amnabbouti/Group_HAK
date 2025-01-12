<?php
include_once 'includes/init.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
$user = getAllUsers($_SESSION['id'] ?? null)[0] ?? null;

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit;
}
$user = default_profile_picture($user);

// Fetch the liked planets for the user
$stmt = $db->prepare("SELECT p.id, p.name, p.description, p.image, p.likes FROM planets p
                      JOIN user_likes ul ON p.id = ul.planet_id
                      WHERE ul.user_id = :userId");
$stmt->bindParam(':userId', $_SESSION['id']);
$stmt->execute();
$likedPlanets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $mail = trim($_POST['mail']);
    $profile_picture = $user['profile_picture'];

    // Validation
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

<html lang="en">
<?php require_once 'includes/head.php'; ?>
<body>
    <?php require_once 'includes/header.php'; ?>
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

        <section class="liked-planets">
            <h2>Liked Planets</h2>
            <div class="crud-container">
                <?php foreach ($likedPlanets as $planet): ?>
                    <div class="crud-item">
                        <div class="crud-image">
                            <a href="detail.php?id=<?= $planet['id']; ?>">
                                <img src="<?= $planet['image'] ?>" alt="<?= $planet['name'] ?>">
                            </a>
                        </div>
                        <div class="crud-details">
                            <h3><?= $planet['name'] ?></h3>
                            <p><?= implode(' ', array_slice(explode(' ', $planet['description']), 0, 10)) . '...'; ?></p>
                            <div class="like-container">
                                <span class="like-count"><?= $planet['likes'] ?></span>
                                <button class="like-button" data-planet-id="<?= $planet['id']; ?>">
                                    <i class="fa fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <?php require_once 'includes/footer.php'; ?>
</body>
</html>