<?php

include_once 'includes/init.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    $planet = get_planet_by_id($id);
    if (!$planet) {
        echo "<p>No planet details found for ID: {$id}</p>";
        exit;
    }
} else {
    die("<p>Invalid or missing planet ID.</p>");
}
?>

<html lang="en">
<?php require_once 'includes/head.php'; ?>
<body>
    <header>
        <nav>
            <div class="search">
                <form method="get" action="index.php">
                    <input type="text" name="name" id="search" placeholder="Search for a planet...">
                    <button type="submit">Search</button>
                </form>
            </div>
            <a href="index.php" class="logo">
                <img src="public/assets/images/logo.svg" alt="Miller's World Logo">
            </a>
            <div>
                <ul class="nav_links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="login.php">Log In</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <section class="planet-detail">
            <div class="container">
                <div class="planet-image">
                    <h2>Explore <?= $planet['name'] ?></h2>
                    <?php if (!empty($planet['model_path'])): ?>
                        <model-viewer
                              src="<?= $planet['model_path'] ?>"
                              alt="<?= $planet['name'] ?> Model"
                              auto-rotate
                              auto-rotate-speed="4"
                              camera-controls
                              disable-zoom
                              style="width: 100%; height: 400px;">
                        </model-viewer>
                        <?php if (!empty($planet['image'])): ?>
                            <div class="planet-image-container">
                                <img src="<?= $planet['image'] ?>" alt="<?= $planet['name'] ?>">
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="planet-image-container">
                            <img src="<?= $planet['image'] ?>" alt="<?= $planet['name'] ?>">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="planet-info-container">
                    <h1><?= $planet['name'] ?></h1>
                    <p><?= $planet['description'] ?></p>
                    <table>
                        <tr>
                            <th>Property</th>
                            <th>Value</th>
                        </tr>
                        <tr>
                            <td><strong>Mass</strong></td>
                            <td><?= $planet['mass'] ?> kg</td>
                        </tr>
                        <tr>
                            <td><strong>Distance from the Sun</strong></td>
                            <td><?= $planet['distance_from_sun'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Gasses</strong></td>
                            <td><?= $planet['gasses'] ?? 'Unknown' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Number of moons</strong></td>
                            <td><?= $planet['moons'] ?? 'Unknown' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Length of year (days)</strong></td>
                            <td><?= $planet['length_of_year'] ?? 'Unknown' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Temperature</strong></td>
                            <td><?= $planet['temperature'] ?? 'Unknown' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Diameter</strong></td>
                            <td><?= $planet['diameter'] ?? 'Unknown' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Atmosphere Composition</strong></td>
                            <td><?= $atmosphereData[$planet['habitability_id']] ?? 'Unknown' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Breathable Atmosphere</strong></td>
                            <td><?= $breathableData[$planet['habitability_id']] ?? 'Unknown' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Atmosphere Density</strong></td>
                            <td><?= $densityData[$planet['habitability_id']] ?? 'Unknown' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Risk Level</strong></td>
                            <td><?= $riskData[$planet['habitability_id']] ?? 'Unknown' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Habitability Potential</strong></td>
                            <td><?= $potentialData[$planet['habitability_id']] ?? 'Unknown' ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>
    </main>
    <?php require_once 'includes/footer.php'; ?>
</body>
</html>