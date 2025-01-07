<?php
// Display errors for development
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

// Include necessary files and libraries
require "includes/db.inc.php";
include_once "includes/css_js.inc.php";
require 'functions.inc.php';
require 'vendor/autoload.php';
$planets = getPlanets();

// print "<pre>";
// print_r($planets);
// print "</pre>";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>
</head>

<body>
    <header>
        <nav>
            <div class="search">
                <!-- Planet Search -->
                <form method="get" action="">
                    <input type="text" name="name" placeholder="Search for a planet..."
                        value="<?= $_GET['name'] ?? '' ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
            <a href="#" class="logo">
                <img src="public/assets/images/logo.svg" alt="Miller's World Logo">
            </a>
            <div>
                <ul class="nav_links">
                    <li><a href="#">Add a planet</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Log In</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <section class="planets">
            <h1>Planets overview</h1>
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
                            <td><img src="<?= $planet['image']; ?>" alt="<?= $planet['name']; ?>" width="100" height="100">
                            </td>
                            <td>Not yet in db</td>
                            <td>Not yet in db</td>
                            <td>
                                <a href="detail.php?id=<?= $planet['id']; ?>">View</a>
                                <!-- TODO edit page needs to be created -->
                                <a href="edit.php?id=<?= $planet['id']; ?>">Edit</a>
                                <!--  delete page  -->
                                <form method="post" action="delete.php">
                                    <input type="hidden" name="id" value="<?= $planet['id']; ?>">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this article?');">
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