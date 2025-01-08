<?php
session_start();
require_once "includes/db.inc.php";
require_once "functions.inc.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        error_log("Invalid ID: " . print_r($_POST, true));
        header("Location: admin_page.php?error=invalid_id");
        exit;
    }

    $planetId = intval($_POST['id']);
    $planet = get_planet_by_id($planetId);

    if (!$planet) {
        error_log("Planet not found for ID: " . $planetId);
        header("Location: admin_page.php?error=planet_not_found");
        exit;
    }

    try {
        $newState = $planet['is_published'] ? 0 : 1;

        // Updating DB
        $db = connectToDB();
        $stmt = $db->prepare("UPDATE planets SET is_published = :is_published WHERE id = :id");
        $stmt->execute([':is_published' => $newState, ':id' => $planetId]);

        error_log("Planet ID: $planetId - is_published updated to $newState");
        header("Location: admin_page.php?success=publish_toggled");
        exit;
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        header("Location: admin_page.php?error=database_error");
        exit;
    }
} else {
    header("Location: admin_page.php?error=invalid_request");
    exit;
}