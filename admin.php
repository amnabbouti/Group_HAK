<?php
session_start();
require_once "includes/css_js.inc.php";
require "includes/db.inc.php";
include_once "includes/css_js.inc.php";
require 'functions.inc.php';
require 'vendor/autoload.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
requiredLoggedIn();
$planets = getPlanets();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./dist/<?= $cssPath ?>">
    <link rel="stylesheet" href="./dist/<?= $cssGlobal ?>">
    <title>Admin page</title>
</head>

<body>
    <header>
        <nav>
            <a href="index.php" class="logo">
                <img src="public/assets/images/logo.svg" alt="Miller's World Logo">
            </a>
            <div>
                <ul class="nav_links">
                    <li><a href="#">Log In</a></li>
                    <li><a href="logout.php">Logout</a></li>
                    <li><a href="admin_register.php" class="btn btn-register-admin">Add New Admin</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <section class="planets">
            <h1>Admin Dashboard</h1>
            <table>
                <thead>
                <tr>
                    <th>#ID</th>
                    <th>Planet</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Date added</th>
                    <th>Date edited</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($planets as $planet): ?>
                    <tr>
                        <td><?= $planet['id']; ?></td>
                        <td><?= $planet['name']; ?></td>
                        <td><?= mb_strimwidth($planet['description'], 0, 100, "..."); ?></td>
                        <td><img src="<?= $planet['image']; ?>" alt="<?= $planet['name']; ?>" width="140" height="100">
                        </td>
                        <td>Not yet in db</td>
                        <td>Not yet in db</td>
                        <td class="buttons">
                            <form method="post" action="publish.php">
                                <input type="hidden" name="id" value="<?= $planet['id']; ?>">
                                <button type="submit" class="publish">
                                    <?= $planet['is_published'] ? 'Unpublish' : 'Publish'; ?>
                                </button>
                            </form>
                            <form method="get" action="detail.php">
                                <input type="hidden" name="id" value="<?= $planet['id']; ?>">
                                <button type="submit" class="view">View</button>
                            </form>
                            <form method="get" action="edit.php">
                                <input type="hidden" name="id" value="<?= $planet['id']; ?>">
                                <button type="submit" class="edit">Edit</button>
                            </form>
                            <form method="post" action="delete.php">
                                <input type="hidden" name="id" value="<?= $planet['id']; ?>">
                                <button type="submit" class="delete"
                                        onclick="return confirm('Are you sure you want to delete this article?');">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <div class="container">

            <div class="logo">
                <img src="public/assets/images/logo.svg" alt="Miller's World Logo">
            </div>
            <?= "php works on the main & footer" ?>
            <ul>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
    </footer>
</body>

</html>