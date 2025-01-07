<?php
$_SERVER["admin"] = true;
include_once "../includes/css_js.inc.php";
require('./includes/db.inc.php');

$id = (int)@$_GET['id'];

if (!$id) {
    redirect();
}

$item = getGeoCacheById($id);

if (!$item) {
    redirect();
}

function redirect()
{
    http_response_code(404);
    header('Location: index.php');
    exit;
}

$levels = getGeoLevels();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geocaches READ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
          integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="../dist/<?= $cssPath ?>"/>
    <script type="module" src="../dist/<?= $jsPath ?>"></script>
    <style>
        #map{
            height: 400px;
        }
    </style>
</head>

<body>


    <div class="container">
        <main>
            <h2>Geocache detail (#<?= $item['id']; ?>)</h2>
            <hr/>

            <h1>
                <?= $item['name']; ?>
            </h1>

            <h4>Level</h4>
            <p>
                <?= isset($levels[$item['level_id']]) ? $levels[$item['level_id']] : '- unkown -'; ?>
            </p>

            <h4>Description</h4>
            <p>
                <?= $item['description']; ?>
            </p>

            <h4>Hint</h4>
            <p>
                <?= $item['hint']; ?>
            </p>

            <div id="map"></div>

        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    const lat = <?= $item['latitude']; ?>,
        long = <?= $item['longitude']; ?>;

    let map = L.map('map').setView([lat, long], 17);

    const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    const circle = L.circle([lat, long], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.25,
        radius: 25
    }).addTo(map);
</script>


</html>