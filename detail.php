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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $planet['name'] ?></title>
    <link rel="stylesheet" href="./dist/<?= $cssPath ?>"/>
    <link rel="stylesheet" href="./dist/<?= $cssGlobal ?>"/>
    <script type="module" src="./dist/<?= $jsPath ?>"></script>
    <script type="module"
            src="https://cdn.jsdelivr.net/npm/@google/model-viewer@3.0.1/dist/model-viewer.min.js"></script>
    <script type="module" src="public/main.js" defer></script>
    <script src="https://kit.fontawesome.com/f5cdfe48d9.js" crossorigin="anonymous"></script>

</head>
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
                              style="width: 100%; height: 300px;">
                        </model-viewer>
                        <?php if (!empty($planet['image'])): ?>
                            <div style="text-align: center; margin-top: 4rem;">
                                <img src="<?= $planet['image'] ?>" alt="<?= $planet['name'] ?>"
                                     style="max-width: 100%; height: auto;">
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div>
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
                            <td><strong>length of year (days)</strong></td>
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
                            <td><?php
                                if (!empty($planet['habitability_id'])) {
                                    $atmosphereData = [
                                        1 => "Oxygen-rich, Risk: Low, Potential: High",
                                        2 => "Carbon dioxide-rich, Risk: High, Potential: Low",
                                        3 => "Nitrogen-oxygen, Risk: Medium, Potential: Medium",
                                        4 => "Methane-rich, Risk: Medium, Potential: Low",
                                        5 => "Ammonia-rich, Risk: High, Potential: Very Low",
                                        6 => "Breathable, Supports life",
                                        7 => "Thin, Uninhabitable",
                                        8 => "Dense, No potential"
                                    ];
                                    echo $atmosphereData[$planet['habitability_id']] ?? 'Unknown';
                                } else {
                                    echo 'Unknown';
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td><strong>Breathable Atmosphere</strong></td>
                            <td><?php
                                if (!empty($planet['habitability_id'])) {
                                    $breathableData = [
                                        1 => "Yes",
                                        2 => "No",
                                        3 => "Yes",
                                        4 => "Yes",
                                        5 => "No",
                                        6 => "Yes",
                                        7 => "No",
                                        8 => "No"
                                    ];
                                    echo $breathableData[$planet['habitability_id']] ?? 'Unknown';
                                } else {
                                    echo 'Unknown';
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td><strong>Atmosphere Density</strong></td>
                            <td><?php
                                if (!empty($planet['habitability_id'])) {
                                    $densityData = [
                                        1 => "1",
                                        2 => "1.2",
                                        3 => "0.9",
                                        4 => "1.1",
                                        5 => "0.8",
                                        6 => "Unknown",
                                        7 => "Unknown",
                                        8 => "Unknown"
                                    ];
                                    echo $densityData[$planet['habitability_id']] ?? 'Unknown';
                                } else {
                                    echo 'Unknown';
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td><strong>Risk Level</strong></td>
                            <td><?php
                                if (!empty($planet['habitability_id'])) {
                                    $riskData = [
                                        1 => "Low",
                                        2 => "High",
                                        3 => "Medium",
                                        4 => "Medium",
                                        5 => "High",
                                        6 => "Unknown",
                                        7 => "Unknown",
                                        8 => "Unknown"
                                    ];
                                    echo $riskData[$planet['habitability_id']] ?? 'Unknown';
                                } else {
                                    echo 'Unknown';
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td><strong>Habitability Potential</strong></td>
                            <td><?php
                                if (!empty($planet['habitability_id'])) {
                                    $potentialData = [
                                        1 => "High",
                                        2 => "Low",
                                        3 => "Medium",
                                        4 => "Low",
                                        5 => "Very Low",
                                        6 => "Supports life",
                                        7 => "Uninhabitable",
                                        8 => "No potential"
                                    ];
                                    echo $potentialData[$planet['habitability_id']] ?? 'Unknown';
                                } else {
                                    echo 'Unknown';
                                }
                                ?></td>
                        </tr>
                    </table>
                    <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 2rem;">
                        <a href="index.php" class="bt"
                           style="text-align: center;text-wrap: nowrap; padding: 1rem; width: 170px; text-decoration: none; background-color: #0c0c3b; color: white; border-radius: 5px;">Back
                            to Planets</a>
                        <a href="#" class="bt"
                           style="text-align: center; padding: 1rem; width: 170px; text-decoration: none; background-color: #0c0c3b; color: white; border-radius: 5px;">Add
                            image</a>
                    </div>
                
                </div>
            </div>
        </section>
    </main>
    <?php require_once 'includes/footer.php'; ?>
</body>

</html>