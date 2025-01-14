<?php

include_once 'includes/init.php';

$user = getLoggedInUser(); // logged in users
$nasaData = getNasaFeaturedData();  // nasa api


// fetching liked planets for users
$likedPlanets = [];
if ($user) {
    $stmt = $db->prepare("SELECT planet_id FROM user_likes WHERE user_id = :userId");
    $stmt->bindParam(':userId', $user['id']);
    $stmt->execute();
    $likedPlanets = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// query filters and parameters
list($filters, $params) = buildFiltersAndParams($_GET);
$orderBy = getOrderBy($_GET);
// Build
$whereClause = !empty($filters) ? "WHERE " . implode(" AND ", $filters) : "";
$query = "SELECT id, name, description, image, likes FROM planets $whereClause $orderBy";
$countQuery = "SELECT COUNT(*) FROM planets $whereClause"; //count
$stmt = $db->prepare($query);  // planet data (query execution)
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$planetData = $stmt->fetchAll(PDO::FETCH_ASSOC);
$countStmt = $db->prepare($countQuery); // total count
foreach ($params as $key => $value) {
    $countStmt->bindValue($key, $value);
}
$countStmt->execute();
$totalPlanets = $countStmt->fetchColumn();

// Pagination
$itemsPerPage = 12;
$totalPages = ceil($totalPlanets / $itemsPerPage);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page > $totalPages) {
    header("Location: ?page=$totalPages");
    exit;
}
$pagination = paginate($planetData, $itemsPerPage, $page); // paginated data
$paginatedItems = $pagination['items'];
$previousPage = $pagination['previousPage'];
$nextPage = $pagination['nextPage'];

?>

<html lang="en">
<?php require_once 'includes/head.php'; ?>

<body>
    <?php require_once 'includes/header.php'; ?>
    <main>

        <section class="featured-banner">
            <div id="picture_of_the_month">
                <?php if ($nasaData['mediaType'] === "image"): ?>
                    <img src="<?= $nasaData['image']; ?>" alt="<?= $nasaData['description']; ?>">
                <?php elseif ($nasaData['mediaType'] === "video"): ?>
                    <video controls>
                        <source src="<?= $nasaData['image']; ?>" type="video/mp4">
                        Your browser does not support video.
                    </video>
                <?php else: ?>
                    <p>No media available for today.</p>
                <?php endif; ?>
            </div>

            <div class="content">
                <h2>Picture of The Day</h2>
                <h3><?= $nasaData['title']; ?></h3>
                <p><?= $nasaData['description']; ?></p>
                <a href="#planets">
                    <button>Explore The Universe</button>
                </a>
            </div>
        </section>

        <section class="filters">
            <form method="GET" action="">
                <div class="filter-dropdown">
                    <a class="filter-dropdown-button" id="dropdownButton">Filters <span class="dropdown-arrow"><i
                                  class="fa fa-chevron-down"></i></span></a>
                    <div class="filter-dropdown-content" id="dropdownContent">
                        <div class="filter-group">
                            <span class="filter-label">Moon Count</span>
                            <select name="moons" id="moons">
                                <option value="">Select Moon Count</option>
                                <option
                                      value="No Moons" <?= isset($_GET['moons']) && $_GET['moons'] == 'No Moons' ? 'selected' : '' ?>>
                                    No Moons
                                </option>
                                <option
                                      value="1 Moon" <?= isset($_GET['moons']) && $_GET['moons'] == '1 Moon' ? 'selected' : '' ?>>
                                    1 Moon
                                </option>
                                <option
                                      value="More than 1 Moon" <?= isset($_GET['moons']) && $_GET['moons'] == 'More than 1 Moon' ? 'selected' : '' ?>>
                                    More than 1 Moon
                                </option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <span class="filter-label">Sort By</span>
                            <select name="sort" id="sort">
                                <option
                                      value="name" <?= isset($_GET['sort']) && $_GET['sort'] == 'name' ? 'selected' : '' ?>>
                                    Name
                                </option>
                                <option
                                      value="diameter" <?= isset($_GET['sort']) && $_GET['sort'] == 'diameter' ? 'selected' : '' ?>>
                                    Diameter
                                </option>
                                <option
                                      value="moons" <?= isset($_GET['sort']) && $_GET['sort'] == 'moons' ? 'selected' : '' ?>>
                                    Moons
                                </option>
                                <option
                                      value="date_discovered" <?= isset($_GET['sort']) && $_GET['sort'] == 'date_discovered' ? 'selected' : '' ?>>
                                    Date Discovered
                                </option>
                                <option
                                      value="distance_from_sun" <?= isset($_GET['sort']) && $_GET['sort'] == 'distance_from_sun' ? 'selected' : '' ?>>
                                    Distance From Sun
                                </option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <span class="filter-label">Order</span>
                            <select name="order" id="order">
                                <option
                                      value="asc" <?= isset($_GET['order']) && $_GET['order'] == 'asc' ? 'selected' : '' ?>>
                                    Ascending (A-Z)
                                </option>
                                <option
                                      value="desc" <?= isset($_GET['order']) && $_GET['order'] == 'desc' ? 'selected' : '' ?>>
                                    Descending (Z-A)
                                </option>
                            </select>
                        </div>
                        <button type="submit">Apply</button>
                    </div>
                </div>
            </form>
        </section>


        <section class="planets">
            <?php foreach ($paginatedItems as $planet): ?>
                <div class="container">
                    <article id="planets">
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
                            <p><?= implode(' ', array_slice(explode(' ', $planet['description']), 0, 10)) . '...'; ?></p>
                            <div class="actions">
                                <a href="detail.php?id=<?= $planet['id']; ?>">DETAILS</a>
                                <div class="like-container">
                                    <span class="like-count"
                                          id="like-count-<?= $planet['id']; ?>"><?= $planet['likes'] ?? 0 ?></span>
                                    <button
                                          class="like-button <?= in_array($planet['id'], $likedPlanets) ? 'liked' : '' ?>"
                                          data-planet-id="<?= $planet['id']; ?>">
                                        <i class="fa-solid fa-heart full-heart"></i>
                                        <i class="fa-regular fa-heart empty-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </section>
        <section class="pagination">
            <div class="container">
                <ul>
                    <?php if ($page > 1): ?>
                        <li><a href="?page=1&sort=<?= $_GET['sort'] ?? '' ?>&order=<?= $_GET['order'] ?? 'asc' ?>"><i
                                      class="fa-solid fa-angles-left"></i></a></li>
                        <li>
                            <a href="?page=<?= $previousPage ?>&sort=<?= $_GET['sort'] ?? '' ?>&order=<?= $_GET['order'] ?? 'asc' ?>"><i
                                      class="fa-solid fa-chevron-left"></i></a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li>
                            <a href="?page=<?= $i ?>&sort=<?= $_GET['sort'] ?? '' ?>&order=<?= $_GET['order'] ?? 'asc' ?>"
                               class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                        <li>
                            <a href="?page=<?= $nextPage ?>&sort=<?= $_GET['sort'] ?? '' ?>&order=<?= $_GET['order'] ?? 'asc' ?>"><i
                                      class="fa-solid fa-chevron-right"></i></a></li>
                        <li>
                            <a href="?page=<?= $totalPages ?>&sort=<?= $_GET['sort'] ?? '' ?>&order=<?= $_GET['order'] ?? 'asc' ?>"><i
                                      class="fa-solid fa-angles-right"></i></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </section>
    </main>
    <?php require_once 'includes/footer.php'; ?>
</body>

</html>