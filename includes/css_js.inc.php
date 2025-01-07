<?php
$page = basename($_SERVER["PHP_SELF"]); // Deze lijn haalt de bestandsnaam op van het huidige script dat wordt uitgevoerd, zonder het pad. 

if (isset($_SERVER["admin"])) {
    $manifestUrl = "../dist/.vite/manifest.json";
    $source = "admin/js/index.js";
} elseif ($page === "detail.php") {
    $manifestUrl = "./dist/.vite/manifest.json";
    $source = "js/detail.js";
} elseif ($page === "login.php") {
    $manifestUrl = "./dist/.vite/manifest.json";
    $source = "js/login.js";
} elseif ($page === "register.php") {
    $manifestUrl = "./dist/.vite/manifest.json";
    $source = "js/register.js";
} else {
    $manifestUrl = "./dist/.vite/manifest.json";
    $source = "js/index.js";
}
$manifestJson = file_get_contents($manifestUrl);
$manifestObj = json_decode($manifestJson, true);
$cssPath = $manifestObj[$source]["css"][0];
$cssGlobal = $manifestObj[$source]["css"][1];  // this line is to link global css
$jsPath = $manifestObj[$source]["file"];