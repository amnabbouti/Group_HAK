<?php
include_once "includes/css_js.inc.php";

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

// print "<pre>";
// var_dump($planet_images);
// print "</pre>";

$accessKey = 'vGiBVlx1ebXeCPSQrofSg68UGu9A_BH2hDitylo3gUU';
$endpoint = 'https://api.unsplash.com/search/photos';

$query = 'planet';
$url = $endpoint . '?query=' . urlencode($query) . '&client_id=' . $accessKey;

$response = file_get_contents($url);
$data = json_decode($response, true);

if (isset($data['results']) && count($data['results']) > 0) {
    $imageUrls = [];
    foreach ($data['results'] as $photo) {
        $imageUrls[] = $photo['urls']['regular'];
    }
    echo '<pre>';
    print_r($imageUrls);
    echo '</pre>';
} else {
    echo "No planets found.";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miller's World</title>
    <link rel="stylesheet" href="./dist/<?= $cssPath ?>" />
    <script type="module" src="./dist/<?= $jsPath ?>"></script>
</head>

<body>
    <header>
        <div class="container">
            <div>
                <input type="text" name="search" id="search" placeholder="Search...">
                <button type="submit">Search</button>
            </div>
            <nav>
                <ul>
                    <li><a href="#">Add a Planet</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Login</a></li>
                </ul>
            </nav>
            <!-- <a href="#"><i class="icon-hak_logo_concept1"></i></a> -->
        </div>
    </header>
    <main>
        <section class="main_banner">
            <div class="container">
                <!-- TODO -> Picture of the month links to the detail.php of that picture -->
                <a href="#">
                    <!-- <img src="public/image_moon1.jpg" alt="Image of a Moon"> -->
                    <h3>Picture of the month</h3>
                    <p>Name - Description/Article of planet</p>
                </a>
            </div>
        </section>
        <section class="socials">
            <div class="container">
                <ul>
                    <li><a href="#"><i class="icon-pacman"></i></a></li>
                    <li><a href="#"><i class="icon-video-camera"></i></a></li>
                    <li><a href="#"><i class="icon-stack"></i></a></li>
                    <li><a href="#"><i class="icon-connection"></i></a></li>
                </ul>
            </div>
        </section>
        <section class="planets">
            <div class="container">

                <?php foreach ($imageUrls as $photo): ?>
                    <article>
                        <div class="head">
                            <div>
                                <a href="#">
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