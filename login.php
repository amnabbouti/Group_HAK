<?php
include_once 'includes/init.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = handleLogin($_POST);
}
?>
<html lang="en">
<?php require_once 'includes/head.php'; ?>
<body>
    <?php require_once 'includes/header.php'; ?>
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
                        <p>Forgot your password? <a href="request_reset.php">Click here to reset it.</a></p>
                    </div>
                    <div class="form-actions">
                        <button type="submit" value="submit" name="submit" class="btn">Login</button>
                    </div>
                </form>
                <div class="signup-link">
                    <p>Don't have an account? <a href="register.php">Sign Up</a></p>
                    <p>Forgot your password? <a href="request_reset.php">Reset it here</a></p>
                </div>
            </div>
        </section>
    </main>
    <?php require_once 'includes/footer.php'; ?>
</body>
</html>