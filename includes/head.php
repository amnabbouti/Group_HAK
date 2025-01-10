<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
        if (basename($_SERVER['PHP_SELF']) === 'index.php') {
            echo "Miller's World";
        } elseif (basename($_SERVER['PHP_SELF']) === 'admin.php') {
            echo "Admin Platform";
        } elseif (basename($_SERVER['PHP_SELF']) === 'profile.php') {
            echo "Profile";
        } elseif (basename($_SERVER['PHP_SELF']) === 'detail.php') {
            echo $planetName ?? 'Detail';
        } elseif (basename($_SERVER['PHP_SELF']) === 'login.php') {
            echo "Login";
        } elseif (basename($_SERVER['PHP_SELF']) === 'form.php') {
            echo "Add Planet";
        } elseif (basename($_SERVER['PHP_SELF']) === 'register.php') {
            echo "Registration";
        }
        ?>
    </title>
    <link rel="stylesheet" href="/dist/<?= $cssPath ?>"/>
    <link rel="stylesheet" href="/dist/<?= $cssGlobal ?>"/>
    <script type="module" src="/dist/<?= $jsPath ?>"></script>
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <script type="module" src="../public/main.js" defer></script>
    <script src="https://kit.fontawesome.com/f5cdfe48d9.js" crossorigin="anonymous"></script>
</head>

