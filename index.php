<?php
include_once "includes/css_js.inc.php";
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
            <svg width="100px" viewBox="0 0 2600 2600" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/">
                <path d="M1260.69,604.965c543.779,0 985.26,441.481 985.26,985.26c-0,543.78 -441.481,985.26 -985.26,985.26c-543.78,0 -985.26,-441.48 -985.26,-985.26c-0,-543.779 441.48,-985.26 985.26,-985.26Zm-839.759,797.886c-13.422,60.326 -20.501,123.03 -20.501,187.374c-0,474.791 385.469,860.26 860.26,860.26c130.193,0 253.67,-28.984 364.324,-80.846c-145.714,-26.363 -256.378,-153.984 -256.378,-307.248c0,-172.33 139.91,-312.24 312.24,-312.24c165.624,0 301.302,129.233 311.611,292.276c81.435,-131.424 128.463,-286.364 128.463,-452.202c-0,-474.79 -385.47,-860.26 -860.26,-860.26c-150.297,0 -291.643,38.627 -414.644,106.485c2.408,-0.038 4.821,-0.057 7.239,-0.057c247.346,0 448.16,200.814 448.16,448.159c-0,148.746 -72.623,280.664 -184.316,362.2c24.131,30.852 38.52,69.684 38.52,111.851c-0,100.323 -81.45,181.773 -181.774,181.773c-100.323,0 -181.773,-81.45 -181.773,-181.773c-0,-10.119 0.828,-20.046 2.421,-29.716c-180.085,-23.628 -326.657,-154.348 -373.592,-326.036Zm1098.72,-3.342c56.235,0 101.89,45.656 101.89,101.891c0,56.235 -45.655,101.891 -101.89,101.891c-56.235,-0 -101.891,-45.656 -101.891,-101.891c-0,-56.235 45.656,-101.891 101.891,-101.891Zm228.707,-390.519c114.666,0 207.76,93.094 207.76,207.76c-0,114.665 -93.094,207.759 -207.76,207.759c-114.666,0 -207.76,-93.094 -207.76,-207.759c0,-114.666 93.094,-207.76 207.76,-207.76Z" style="fill:#0d0d0d;" />
            </svg>
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