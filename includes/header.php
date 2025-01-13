<?php
include_once 'includes/init.php';
if (isset($_SESSION['id'])) {
    $user = getAllUsers($_SESSION['id'])[0] ?? null;
    $user = default_profile_picture($user);
}
?>

<?php if (basename($_SERVER['PHP_SELF']) === 'admin.php'): ?>
    <header>
        <nav>
            <a href="/index.php" class="logo" onclick="location.href='/index.php'">
                <img src="../public/assets/images/logo.svg" alt="Miller's World Logo">
            </a>
            <div>
                <ul class="nav_links">
                    <li>
                        <button onclick="location.href='logout.php'" class="btn">Logout</button>
                    </li>
                    <li>
                        <button onclick="location.href='admin_register.php'" class="btn btn-register-admin">
                            Add New Admin
                        </button>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
<?php else: ?>
    <header>
        <nav>
            <div class="search">
                <form method="get" action="">
                    <input type="text" name="name" placeholder="Search for a planet..."
                           value="<?= $_GET['name'] ?? '' ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
            <a href="../index.php" class="logo" onclick="location.href='/index.php'">
                <img src="../public/assets/images/logo.svg" alt="Miller's World Logo">
            </a>
            <div>
                <ul class="nav_links">
                    <!--                    <li><a href="../profile.php">Profile</a></li>-->
                    <li><a href="../form.php">Add a planet</a></li>
                    <!--                    <li><a href="../login.php">Log In</a></li>-->
                    <li class="dropdown">
                        <a href="../profile.php" class="profile-picture-header">
                            <?php if (isset($_SESSION['id']) && !empty($user['profile_picture'])): ?>
                                <img src="<?= $user['profile_picture'] ?>" alt="Profile Picture">
                            <?php else: ?>
                                <img src="../public/assets/images/user.png" alt="Default Profile Picture">
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-content">
                            <?php if (isset($_SESSION['id']) && !empty($_SESSION['id'])): ?>
                                <a href="../logout.php">Logout</a>
                                <a href="../login.php">Login</a>
                                <a href="../profile.php">Profile</a>
                            <?php endif; ?>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
<?php endif; ?>