<?php
require 'functions.inc.php';
requiredLoggedIn();

$discoveryMethods = getDiscoveryMethods();
$habitabilities = getHabitabilities();

$errors = [];
$submitted = false;

if (isset($_POST['submit'])) {

    $submitted = true;

    $name = "";
    $description = "";
    $image = "";
    $length_of_year = null;
    $moons = null;
    $temperature = "";
    $diameter = null;
    $discovery_method_id = null;
    $habitability_id = null;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a planet</title>
</head>

<body>
    <main>

        <h1>Add a planet</h1>

        <form action="index.php" method="POST">
            <!-- Name -->
            <label for="name">Planet Name*:</label>
            <input type="text" id="name" name="name" required>

            <!-- Description -->
            <label for="description">Description*:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <!-- Image -->
            <label for="image">Image:</label>
            <input type="text" id="image" name="image" placeholder="https://...">

            <!-- Length of Year -->
            <label for="length_of_year">Length of Year (in Earth days):</label>
            <input type="number" id="length_of_year" name="length_of_year" step="0.01">

            <!-- Moons -->
            <label for="moons">Number of Moons:</label>
            <input type="number" id="moons" name="moons">

            <!-- Temperature -->
            <label for="temperature">Average Temperature (°C):</label>
            <input type="text" id="temperature" name="temperature">

            <!-- Diameter -->
            <label for="diameter">Diameter (in km):</label>
            <input type="number" id="diameter" name="diameter" step="0.01">

            <!-- Date Discovered -->
            <label for="date_discovered">Date Discovered:</label>
            <input type="datetime-local" id="date_discovered" name="date_discovered">

            <!-- Mass -->
            <label for="mass">Mass:</label>
            <input type="text" id="mass" name="mass" placeholder="e.g., 5.972 x 10^24 kg">

            <!-- Distance from Sun -->
            <label for="distance_from_sun">Distance from Sun (in million km):</label>
            <input type="number" id="distance_from_sun" name="distance_from_sun" step="0.01">

            <!-- Discovery Method (Dropdown) -->
            <label for="discovery_method">Discovery Method*:</label>
            <select id="discovery_method" name="discovery_method" required>
                <option value="">Select a discovery method</option>
                <option value="1">Transit</option>
                <option value="2">Radial Velocity</option>
                <option value="3">Direct Imaging</option>
                <!-- Dynamically add options from the `discovery_methods` table -->
            </select>

            <!-- Habitability (Dropdown) -->
            <label for="habitability">Habitability*:</label>
            <select id="habitability" name="habitability" required>
                <option value="">Select habitability level</option>
                <option value="1">Habitable</option>
                <option value="2">Potentially Habitable</option>
                <option value="3">Non-Habitable</option>
                <!-- Dynamically add options from the `habitability` table -->
            </select>

            <!-- Submit Button -->
            <button type="submit" name="submit" id="submit">Submit Planet</button>
        </form>
    </main>

</body>

</html>