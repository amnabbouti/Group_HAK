<?php
require_once 'init.php';
// verify admin is user
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ensure the request is a POST and the 'id' is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    // Sanitize the planet ID
    $planetId = intval($_POST['id']);
    $query = "SELECT is_published FROM planets WHERE id = ?";
    $pdo = connectToDB();
    $stmt = $pdo->prepare($query);
    $stmt->execute([$planetId]);
    $planet = $stmt->fetch();

    if ($planet) {
        $currentStatus = isset($planet['is_published']) ? intval($planet['is_published']) : 0;
        $newStatus = $currentStatus === 1 ? 0 : 1;
        // Update the publish state in the database
        $updateQuery = "UPDATE planets SET is_published = ? WHERE id = ?";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([$newStatus, $planetId]);
        $_SESSION['message'] = $newStatus ? "Planet published successfully!" : "Planet unpublished successfully!";
        header("Location: admin.php");
        exit;
    } else {
        $_SESSION['error'] = "Invalid planet ID or planet not found.";
        header("Location: admin.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: admin.php");
    exit;
}