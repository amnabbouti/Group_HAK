<?php
session_start();
require_once "includes/db.inc.php"; // Include your database connection
require_once "functions.inc.php"; // Include helper functions

// Verify the session to ensure the user is an admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ensure the request is a POST and the 'id' is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    // Sanitize the planet ID
    $planetId = intval($_POST['id']);

    // Fetch the current state of the planet's publication status
    $query = "SELECT is_published FROM planets WHERE id = ?";
    $pdo = connectToDB();
    $stmt = $pdo->prepare($query);
    $stmt->execute([$planetId]);
    $planet = $stmt->fetch();

    if ($planet) {
        // Ensure the value of is_published is an integer (fallback to 0 if null)
        $currentStatus = isset($planet['is_published']) ? intval($planet['is_published']) : 0;

        // Toggle the publish state (0 becomes 1, 1 becomes 0)
        $newStatus = $currentStatus === 1 ? 0 : 1;

        // Update the publish state in the database
        $updateQuery = "UPDATE planets SET is_published = ? WHERE id = ?";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([$newStatus, $planetId]);

        // Optional: Set a success feedback message
        $_SESSION['message'] = $newStatus ? "Planet published successfully!" : "Planet unpublished successfully!";

        // Redirect back to the admin dashboard
        header("Location: admin.php");
        exit;
    } else {
        // If the planet ID is invalid
        $_SESSION['error'] = "Invalid planet ID or planet not found.";
        header("Location: admin.php");
        exit;
    }
} else {
    // For invalid requests
    $_SESSION['error'] = "Invalid request.";
    header("Location: admin.php");
    exit;
}