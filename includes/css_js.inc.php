<?php
$page = basename($_SERVER["PHP_SELF"]);

if (isset($_SERVER["admin"])) {
    $manifestUrl = "../dist/.vite/manifest.json";
    $source = "admin/js/index.js";

} elseif ($page === "detail.php") {
    $manifestUrl = "./dist/.vite/manifest.json";
    $source = "js/detail.js";


} else {
    $manifestUrl = "./dist/.vite/manifest.json";
    $source = "js/index.js";
}
$manifestJson = file_get_contents($manifestUrl);
$manifestObj = json_decode($manifestJson, true);
$cssPath = $manifestObj[$source]["css"][0];
$globalcssPath = $manifestObj[$source]["css"][1];  // this line is ro link global css
$jsPath = $manifestObj[$source]["file"];