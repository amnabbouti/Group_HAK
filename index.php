<?php
session_start(); // Start the session
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

// Include necessary files and libraries
require "includes/db.inc.php";
include_once "includes/css_js.inc.php";
require 'functions.inc.php';
require 'vendor/autoload.php';
$db = connectToDB();

// Get user data if logged in
$user = [];
if (isset($_SESSION['id'])) {
    $query = $db->prepare("SELECT * FROM users WHERE id = ?");
    $query->execute([$_SESSION['id']]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
}

// Get featured NASA data
$nasaData = getNasaFeaturedData();
$featuredTitle = $nasaData['title'];
$featuredDescription = $nasaData['description'];
$featuredImage = $nasaData['image'];
$mediaType = $nasaData['mediaType'];

// query filters and parameters
$filters = [];
$params = [];

// Sorting logic
$orderBy = "ORDER BY id ASC"; //by id
if (!empty($_GET['sort']) && in_array($_GET['sort'], ['name', 'diameter', 'moons', 'date_discovered'])) {
    $orderBy = "ORDER BY " . htmlspecialchars($_GET['sort'] . " ASC");
}

// Search functionality
if (!empty($_GET['name'])) {
    $name = $_GET['name'];
    $filters[] = "name LIKE :name";
    $params[':name'] = "%$name%";
}

// Filtering by moons count
if (isset($_GET['moons']) && !empty($_GET['moons'])) {
    $moons = $_GET['moons'];
    if ($moons == 'No Moons') {
        $filters[] = "moons = 0";
    } elseif ($moons == '1 Moon') {
        $filters[] = "moons = 1";
    } elseif ($moons == 'More than 1 Moon') {
        $filters[] = "moons > 1";
    }
}

// query and count query using helper functions
list($query, $params) = buildPlanetQuery($filters, $params, $orderBy);
$countQuery = buildCountQuery($filters, $params);

// paginated planet data
$stmt = $db->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$planetData = $stmt->fetchAll(PDO::FETCH_ASSOC);

//total planet count for pagination
$countStmt = $db->prepare($countQuery);
foreach ($params as $key => $value) {
    $countStmt->bindValue($key, $value);
}
$countStmt->execute();
$totalPlanets = $countStmt->fetchColumn();
$itemsPerPage = 16;
$totalPages = ceil($totalPlanets / $itemsPerPage);

// current page number (integer)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Pagination
$pagination = paginate($planetData, $itemsPerPage, $page);
$paginatedItems = $pagination['items'];
$previousPage = $pagination['previousPage'];
$nextPage = $pagination['nextPage'];

// Redirect to the last page if page exceeds
if ($page > $totalPages) {
    header("Location: ?page=$totalPages");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miller's World</title>
    <link rel="stylesheet" href="./dist/<?= $cssPath ?>"/>
    <link rel="stylesheet" href="./dist/<?= $cssGlobal ?>"/>
    <script type="module" src="./dist/<?= $jsPath ?>"></script>
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <script type="module" src="/public/main.js" defer></script>
    <script src="https://kit.fontawesome.com/f5cdfe48d9.js" crossorigin="anonymous"></script>

</head>

<body>
    <header>
        <nav>
            <div class="search">
                <!-- Planet Search -->
                <form method="get" action="">
                    <input type="text" name="name" placeholder="Search for a planet..."
                           value="<?= $_GET['name'] ?? '' ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
            <a href="index.php" class="logo">
                <img src="public/assets/images/logo.svg" alt="Miller's World Logo">
            </a>
            <div>
                <ul class="nav_links">
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="form.php">Add a planet</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="login.php">Log In</a></li>
                    <li class="dropdown">
                        <a href="profile.php" class="profile-picture-header">
                            <?php if (!empty($user['profile_picture'])): ?>
                                <img src="<?= $user['profile_picture'] ?>"
                                     alt="Profile Picture"
                                     style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                            <?php else: ?>
                                <i class="fa-solid fa-user fa-xl"></i>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-content">
                            <?php if (isset($_SESSION['id']) && !empty($_SESSION['id'])): ?>
                                <a href="logout.php">Logout</a>
                            <?php endif; ?>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="filters">
            <form method="GET" action="">
                <select name="moons" id="moons">
                    <option value="">Moon Count</option>
                    <option
                          value="No Moons" <?= isset($_GET['moons']) && $_GET['moons'] == 'No Moons' ? 'selected' : '' ?>>
                        No Moons
                    </option>
                    <option value="1 Moon" <?= isset($_GET['moons']) && $_GET['moons'] == '1 Moon' ? 'selected' : '' ?>>
                        1 Moon
                    </option>
                    <option
                          value="More than 1 Moon" <?= isset($_GET['moons']) && $_GET['moons'] == 'More than 1 Moon' ? 'selected' : '' ?>>
                        More than 1 Moon
                    </option>
                </select>
                <button type="submit">Filter</button>
            </form>

        </section>
        <section class="featured-banner">
            <div id="picture_of_the_month">
                <?php if ($mediaType === "image"): ?>
                    <img src="<?= $featuredImage; ?>" alt="<?= $featuredTitle; ?>">
                <?php elseif ($mediaType === "video"): ?>
                    <video controls>
                        <source src="<?= $featuredImage; ?>" type="video/mp4">
                        Your browser does not support video.
                    </video>
                <?php else: ?>
                    <p>No media available for today.</p>
                <?php endif; ?>
            </div>

            <div class="content">
                <h2>Picture of The Day</h2>
                <h3><?= $featuredTitle; ?></h3>
                <p><?= $featuredDescription; ?></p>
                <a href="#planets">
                    <button>Explore The Universe</button>
                </a>
            </div>
            <section class="socials">
                <div class="curiosity-model">
                    <p id="flight">Discover Space With Miller's World</p>
                    <model-viewer
                          id="curiosity"
                          src="public/assets/models/space_shuttle.glb"
                          alt="Curiosity Rover"
                          shadow-intensity="1"
                          background-color="#000000"
                          camera-orbit="-75deg auto 1m"
                          min-camera-orbit="auto auto 20m"
                          max-camera-orbit="auto auto 20m"
                          exposure="1"
                          ground-plane
                          style="width: 300px; height: 200px; overflow: hidden"
                          shadow-intensity="1"
                          environment-image="neutral"
                          scale="0.5 0.5 0.5"
                          field-of-view="90deg">
                    </model-viewer>
                </div>
            </section>
        </section>

        <section class="planets">
            <div class="container" id="planets">
                <?php foreach ($paginatedItems as $planet): ?>
                    <article>
                        <div class="head">
                            <div>
                                <a href="detail.php?id=<?= $planet['id']; ?>">
                                    <img src="<?= $planet['image'] ?>"
                                        alt="<?= $planet['name'] ?>">
                                </a>
                            </div>
                        </div>
                        <div class="foot">
                            <h3><?= $planet['name'] ?></h3>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Pagination -->
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

            <div class="logo">
                <img src="public/assets/images/logo.svg" alt="Miller's World Logo">
            </div>

            <ul class="social-icons">
                <li><a href="https://facebook.com" target="_blank" rel="noopener noreferrer">
                        <i class="fa-brands fa-facebook fa-2x"></i></a>
                </li>
                <li><a href="https://instagram.com" target="_blank" rel="noopener noreferrer">
                        <i class="fa-brands fa-instagram fa-2x"></i></a>
                </li>

                <li><a href="https://example.com" target="_blank" rel="noopener noreferrer">
                        <i class="fa-brands fa-x-twitter fa-2x"></i></a>
                </li>
                <li><a href="mailto:contact@millersworld.com">
                        <i class="fa-solid fa-envelope fa-2x"></i></a>
                </li>
            </ul>
            <ul>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
    </footer>
</body>

</html>