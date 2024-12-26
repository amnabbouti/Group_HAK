<?php
include_once "includes/css_js.inc.php";
require("functions.php");

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

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
} else {
    echo "No planets found.";
}


$itemsPerPage = 6;
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
        </div>
    </header>

    <main>
        <section class="main_banner">
            <div class="container">
                <a href="#">
                    <h3>Picture of the month</h3>
                    <p>Name - Description/Article of planet</p>
                </a>
            </div>
        </section>

        <section class="planets">
            <div class="container">
                <?php foreach ($paginatedItems as $photo): ?>
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