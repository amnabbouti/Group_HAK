<?php

global $cssPath, $jsPath, $globalcssPath;
$source = "js/detail.js";
require("functions.php");
require 'includes/db.inc.php';
require 'includes/css_js.inc.php';
require 'vendor/autoload.php';

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        $db = connectToDB();
        $sql = "SELECT * FROM planets WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $planet = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$planet) {
            echo "<p>No planet details found for ID: {$id}</p>";
            exit;
        }
    } catch (PDOException $e) {
        error_log("Error fetching planet details: " . $e->getMessage());
        die("Error: Could not fetch details.");
    }
} else {
    die("<p>Invalid or missing planet ID.</p>");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $planet['name'] ?></title>
    <link rel="stylesheet" href="./dist/<?= $cssPath ?>"/>
    <link rel="stylesheet" href="./dist/<?= $globalcssPath ?>"/>
    <script type="module" src="./dist/<?= $jsPath ?>"></script>
    <script type="module"
            src="https://cdn.jsdelivr.net/npm/@google/model-viewer@3.0.1/dist/model-viewer.min.js"></script>

</head>

<body>
    <header>
        <nav>
            <div class="search">
                <input type="text" name="search" id="search" placeholder="Search for a planet...">
                <button type="submit">Search</button>
            </div>
            <a href="#" class="logo">
                <img src="public/assets/images/logo.svg" alt="Miller's World Logo">
            </a>
            <div>
                <ul class="nav_links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Log In</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <section class="planet-detail">
            <div class="container">
                <div class="planet-image">
                    <h2>Explore the 3D Model of <?= $planet['name'] ?></h2>
                    <model-viewer
                          src="<?= $planet['model_path'] ?>"
                          alt="<?= $planet['name'] ?> Model"
                          start-rotation="0 0 0"
                          auto-rotate
                          auto-rotate-speed="4"
                          camera-controls
                          disable-zoom
                          style="width: 100%; height: 500px;">
                    </model-viewer>
                
                </div>
                <div class="planet-info-container">
                    <h1><?= $planet['name'] ?></h1>
                    <p><?= $planet['description'] ?></p>
                    <ul>
                        <li><strong>Mass:</strong> <?= $planet['mass'] ?> kg</li>
                        <li><strong>Distance from the
                                Sun:</strong> <?= $planet['distance_from_sun'] ?>
                        </li>
                        <li><strong>Gasses:</strong> <?= $planet['gasses'] ?? 'Unknown' ?></li>
                    </ul>
                </div>
            </div>
        </section>
        <a href="index.php" class="button">Back to Planets</a>
        <?php if (!empty($planet['model_path'])): ?>
            <div style="display: flex; justify-content: center;">
                <img src="<?= $planet['image'] ?>" alt="<?= $planet['name'] ?>">

            </div>
        <?php else: ?>
            <p>No 3D model available for this planet.</p>
        <?php endif; ?>
    </main>
    <footer>
        <div class="container">
            <h3>Logo HAK</h3>
            <?= "php" ?>
            <ul>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
    </footer>
</body>

</html>