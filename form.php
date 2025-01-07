<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <main>

        <h1>Add a planet</h1>

        <form action="index.php" method="POST">
            <!-- Name -->
            <label for="name">Planet Name:</label>
            <input type="text" id="name" name="name" required>

            <!-- Description -->
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <!-- Image -->
            <label for="image">Image (optional):</label>
            <input type="file" id="image" name="image" accept="image/*">

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
            <label for="mass">Mass (e.g., 5.972 x 10^24 kg):</label>
            <input type="text" id="mass" name="mass">

            <!-- Distance from Sun -->
            <label for="distance_from_sun">Distance from Sun (in million km):</label>
            <input type="number" id="distance_from_sun" name="distance_from_sun" step="0.01">

            <!-- Discovery Method (Dropdown) -->
            <label for="discovery_method_id">Discovery Method:</label>
            <select id="discovery_method_id" name="discovery_method_id" required>
                <option value="">Select a discovery method</option>
                <option value="1">Transit</option>
                <option value="2">Radial Velocity</option>
                <option value="3">Direct Imaging</option>
                <!-- Dynamically add options from the `discovery_methods` table -->
            </select>

            <!-- Habitability (Dropdown) -->
            <label for="habitability_id">Habitability:</label>
            <select id="habitability_id" name="habitability_id" required>
                <option value="">Select habitability level</option>
                <option value="1">Habitable</option>
                <option value="2">Potentially Habitable</option>
                <option value="3">Non-Habitable</option>
                <!-- Dynamically add options from the `habitability` table -->
            </select>

            <!-- Model Path -->
            <label for="model_path">3D Model Path (optional):</label>
            <input type="text" id="model_path" name="model_path" placeholder="e.g., /models/planet.glb">

            <!-- Submit Button -->
            <button type="submit">Submit Planet</button>
        </form>
    </main>

</body>

</html>