<?php
$page = basename($_SERVER["PHP_SELF"]);

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

} elseif ($page === "profile.php") {
    $manifestUrl = "./dist/.vite/manifest.json";
    $source = "js/profile.js";

} elseif ($page === "admin_register.php") {
    $manifestUrl = "./dist/.vite/manifest.json";
    $source = "js/admin_register.js";

} elseif ($page === "admin.php") {
    $manifestUrl = "./dist/.vite/manifest.json";
    $source = "js/admin.js";
} elseif ($page === "terms_privacy.php") {
    $manifestUrl = "./dist/.vite/manifest.json";
    $source = "js/terms_privacy.js";

} elseif ($page === "form.php") {
    $manifestUrl = "./dist/.vite/manifest.json";
    $source = "js/form.js";
} elseif ($page === "edit.php") {
    $manifestUrl = "./dist/.vite/manifest.json";
    $source = "js/edit.js";
} else {
    $manifestUrl = "./dist/.vite/manifest.json";
    $source = "js/index.js";
}
$manifestJson = file_get_contents($manifestUrl);
$manifestObj = json_decode($manifestJson, true);
$cssPath = $manifestObj[$source]["css"][0];
$cssGlobal = $manifestObj[$source]["css"][1];  // this line is to link global css
$jsPath = $manifestObj[$source]["file"];