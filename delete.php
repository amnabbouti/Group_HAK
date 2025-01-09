<?php
require('includes/functions.inc.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    if ($id > 0) {
        deletePlanet($id);
        header("Location: admin.php?message=Article $id has been deleted.");
        exit;
    }
}
header("Location: admin.php?message=Invalid article ID.");
exit;
