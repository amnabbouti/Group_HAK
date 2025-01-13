<?php
include_once 'includes/init.php';

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = (int)$_POST['id'];
    $userId = $_SESSION['id'];

    $stmt = $db->prepare("SELECT COUNT(*) FROM user_likes WHERE user_id = :userId AND planet_id = :planetId");
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':planetId', $id);
    $stmt->execute();
    $alreadyLiked = $stmt->fetchColumn();

    if ($alreadyLiked == 0) {
        $stmt = $db->prepare("UPDATE planets SET likes = likes + 1 WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // likes count
        $stmt = $db->prepare("UPDATE users SET likes = likes + 1 WHERE id = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        // record
        $stmt = $db->prepare("INSERT INTO user_likes (user_id, planet_id) VALUES (:userId, :planetId)");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':planetId', $id);
        $stmt->execute();
    }
    $stmt = $db->prepare("SELECT likes FROM planets WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $likes = $stmt->fetchColumn();

    echo json_encode(['success' => true, 'likes' => $likes]);
} else {
    echo json_encode(['success' => false]);
}
?>