<?php
include_once "includes/css_js.inc.php";
require("functions.php");
require 'vendor/autoload.php';
require 'vendor/autoload.php';

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);


use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// image of the day from nasa API
$nasaApiKey = $_ENV['NASA_API_KEY'];
$unsplashAccessKey = $_ENV['UNSPLASH_ACCESS_KEY'];
$nasaApiUrl = "https://api.nasa.gov/planetary/apod?api_key={$nasaApiKey}";
$response = file_get_contents($nasaApiUrl);
$nasaData = json_decode($response, true);
if (isset($nasaData['error']) || !$response) {
    $featuredTitle = "Astronomy Picture of the Day Unavailable";
    $featuredDescription = "no description available";
    $featuredImage = "";
    $mediaType = "error";
} else {
    $featuredTitle = $nasaData['title'] ?? "Astronomy Picture of the Day";
    $featuredDescription = $nasaData['explanation'] ?? "Explore the cosmos with miller's world!";
    $featuredImage = $nasaData['url'] ?? "";
    $mediaType = $nasaData['media_type'] ?? "image";
}

// unsplash for testing
$query = 'planet';
$unsplashApiUrl = "https://api.unsplash.com/search/photos?query=" . urlencode($query) . "&client_id={$unsplashAccessKey}";
$response = file_get_contents($unsplashApiUrl);
$data = json_decode($response, true);
if (isset($data['results']) && count($data['results']) > 0) {
    $imageUrls = [];
    foreach ($data['results'] as $photo) {
        $imageUrls[] = $photo['urls']['regular'];
    }
} else {
    echo "No planets found.";
}

$itemsPerPage = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalItems = count($imageUrls);
$totalPages = ceil($totalItems / $itemsPerPage);
$startIndex = ($page - 1) * $itemsPerPage;
$paginatedItems = array_slice($imageUrls, $startIndex, $itemsPerPage);
$previousPage = ($page > 1) ? $page - 1 : null;
$nextPage = ($page < $totalPages) ? $page + 1 : null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miller's World</title>
    <link rel="stylesheet" href="./dist/<?= $cssPath ?>" />
    <link rel="stylesheet" href="./dist/<?= $globalcssPath ?>" /> <!--added a path for global css -->
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
</body>

</html>