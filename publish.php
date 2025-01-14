<?php
require_once 'includes/init.php';

// post request(id required)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
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