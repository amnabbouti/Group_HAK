<?php
include_once "includes/css_js.inc.php";
require("includes/db.inc.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miller's World</title>
    <link rel="stylesheet" href="./dist/<?= $cssPath ?>" />
    <script type="module" src="./dist/<?= $jsPath ?>"></script>
</head>

<body>
    <header>
        <nav>
            <div class="search">
                <input type="text" name="search" id="search" placeholder="Search for a planet...">
                <button type="submit">Search</button>
            </div>
            <div><img src="./hak_logo_concept1.svg" alt="Miller's World Logo"></div>
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
        <?= "php works on the main" ?>
        <section>
            <div id="picture_of_the_month">
                <!-- TODO -> Picture of the month links to the detail.php of that picture -->
                <a href="#">
                    <img src="public/image_moon1.jpg" alt="Image of a Moon">
                    <h3>Picture of the month</h3>
                    <p>Name - Description/Article of planet</p>
                </a>
            </div>
        </section>
        <section id="socials">
            <h3>Maybe a gap or place for our socials</h3>
        </section>
        <section id="grid_planets">
            <h3>GRID of all the planets in db with pagination</h3>
        </section>
    </main>
    <footer>
        <h3>This is the footer</h3>
    </footer>
</body>

</html>