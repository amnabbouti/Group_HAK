<?php
include_once "includes/css_js.inc.php";
require 'functions.php';
require 'vendor/autoload.php';
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$nasaApiKey = $_ENV['NASA_API_KEY'];
$unsplashAccessKey = $_ENV['UNSPLASH_ACCESS_KEY'];

// NASA API
$nasaData = getNasaApodData($nasaApiKey);
$featuredTitle = $nasaData['title'];
$featuredDescription = $nasaData['description'];
$featuredImage = $nasaData['image'];
$mediaType = $nasaData['mediaType'];

// Unsplash API
$query = 'planet';
$imageUrls = getUnsplashImages($query, $unsplashAccessKey);

// Pagination
$itemsPerPage = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pagination = paginate($imageUrls, $itemsPerPage, $page);
$paginatedItems = $pagination['items'];
$previousPage = $pagination['previousPage'];
$nextPage = $pagination['nextPage'];
$totalPages = $pagination['totalPages'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miller's World</title>
    <link rel="stylesheet" href="./dist/<?= $cssPath ?>"/>
    <link rel="stylesheet" href="./dist/<?= $globalcssPath ?>"/>  <!--added a path for global css -->
    <script type="module" src="./dist/<?= $jsPath ?>"></script>
</head>

<body>
    <header>
        <nav>
            <div class="search">
                <input type="text" name="search" id="search" placeholder="Search for a planet...">
                <button type="submit">Search</button>
            </div>
            <a href="#" class="logo">
                <img src="public/hak_logo_concept1.svg" alt="Miller's World Logo">
            </a>
            <div>
                <ul class="nav_links">
                    <li><a href="#">Add a planet</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Log In</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <section class="featured-banner">
            <div id="picture_of_the_month">
                <a href="#">
                    <?php if ($mediaType === "image"): ?>
                        <img src="<?= $featuredImage; ?>"
                             alt="<?= $featuredTitle; ?>">
                    <?php elseif ($mediaType === "video"): ?>
                        <video controls>
                            <source src="<?= $featuredImage; ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php else: ?>
                        <p>No media available for today.</p>
                    <?php endif; ?>
                </a>
            </div>

            <div class="content">
                <h3><?= $featuredTitle; ?></h3>
                <p><?= $featuredDescription; ?></p>

                <a href="#planets">
                    <button>Explore More</button>
                </a>
            </div>
        </section>
        <section class="socials">
            empty space for extra info
        </section>

        <section class="planets">
            <div class="container" id="planets">
                <?php foreach ($paginatedItems as $photo): ?>
                    <article>
                        <div class="head">
                            <div>
                                <a href="detail.php">
                                    <img src="<?= $photo; ?>" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="foot">
                            <h3>Name planet</h3>
                            <p>Small description</p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="pagination">
            <div class="container">
                <ul>
                    <?php if ($previousPage): ?>
                        <li><a href="?page=<?= $previousPage ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li><a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                    <?php if ($nextPage): ?>
                        <li><a href="?page=<?= $nextPage ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <h3>Logo HAK</h3>
            <?= "php works on the main & footer" ?>
            <ul>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
    </footer>
</body>

</html>