<?php
include_once 'includes/init.php';
$discoveryMethods = getDiscoveryMethods();
$habitabilities = getHabitabilities();

$errors = [];
$submitted = false;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $planet = get_planet_by_id($id);
    if ($planet) {
        $name = $planet['name'];
        $description = $planet['description'];
        $image = $planet['image'];
        $length_of_year = $planet['length_of_year'];
        $moons = $planet['moons'];
        $temperature = $planet['temperature'];
        $diameter = $planet['diameter'];
        $date_discovered = $planet['date_discovered'];
        $mass = $planet['mass'];
        $distance_from_sun = $planet['distance_from_sun'];
        $discovery_method_id = $planet['discovery_method_id'];
        $habitability_id = $planet['habitability_id'];
    } else {
        header("Location: admin.php?error=Planet not found.");
        exit;
    }
}

if (isset($_POST['submit'])) {
    $submitted = true;
    $id = $_POST['id'];

    $name = "";
    $description = "";
    $image = "";
    $length_of_year = null;
    $moons = null;
    $temperature = "";
    $diameter = null;
    $date_discovered = null;
    $mass = "";
    $distance_from_sun = null;
    $discovery_method_id = null;
    $habitability_id = null;

    if (isset($_POST['submit'])) {
        $submitted = true;

        // Validatie voor naam
        if (!isset($_POST['name'])) {
            $errors[] = "Planet name is missing.";
        } else {
            if (strlen($_POST['name']) == 0) {
                $errors[] = "Planet name cannot be empty.";
            } else {
                $name = $_POST['name'];
            }
        }

        // Validatie voor beschrijving
        if (!isset($_POST['description'])) {
            $errors[] = "Description is missing.";
        } else {
            if (strlen($_POST['description']) == 0) {
                $errors[] = "Description cannot be empty.";
            } else {
                $description = $_POST['description'];
            }
        }

        // Validatie voor afbeelding
        if (isset($_POST['image']) && strlen($_POST['image']) > 0) {
            if (!filter_var($_POST['image'], FILTER_VALIDATE_URL)) {
                $errors[] = "Image must be a valid URL.";
            } else {
                $image = $_POST['image'];
            }
        } else {
            $image = 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg';
        }

        // Validatie voor lengte van het jaar
        if (isset($_POST['length_of_year']) && strlen($_POST['length_of_year']) > 0) {
            if (!is_numeric($_POST['length_of_year'])) {
                $errors[] = "Length of year must be a valid number.";
            } else {
                $length_of_year = (float)$_POST['length_of_year'];
            }
        } else {
            $length_of_year = null;
        }

        // Validatie voor aantal manen
        if (isset($_POST['moons']) && strlen($_POST['moons']) > 0) {
            if (!is_numeric($_POST['moons']) || (int)$_POST['moons'] < 0) {
                $errors[] = "Number of moons must be a valid positive integer.";
            } else {
                $moons = (int)$_POST['moons'];
            }
        } else {
            $moons = null;
        }

        // Validatie voor temperatuur
        if (isset($_POST['temperature']) && strlen($_POST['temperature']) > 0) {
            $temperature = $_POST['temperature'];
        } else {
            $temperature = null;
        }

        // Validatie voor diameter
        if (isset($_POST['diameter']) && strlen($_POST['diameter']) > 0) {
            if (!is_numeric($_POST['diameter']) || (float)$_POST['diameter'] <= 0) {
                $errors[] = "Diameter must be a positive number.";
            } else {
                $diameter = (float)$_POST['diameter'];
            }
        } else {
            $diameter = null;
        }

        // Validatie voor ontdekkingsdatum
        if (isset($_POST['date_discovered']) && strlen($_POST['date_discovered']) > 0) {
            $date_discovered = $_POST['date_discovered'];
        } else {
            $date_discovered = null;
        }

        // Validatie voor massa
        if (isset($_POST['mass']) && strlen($_POST['mass']) > 0) {
            $mass = $_POST['mass'];
        } else {
            $mass = null;
        }

        // Validatie voor afstand tot de zon
        if (isset($_POST['distance_from_sun']) && strlen($_POST['distance_from_sun']) > 0) {
            if (!is_numeric($_POST['distance_from_sun']) || (float)$_POST['distance_from_sun'] <= 0) {
                $errors[] = "Distance from the sun must be a positive number.";
            } else {
                $distance_from_sun = (float)$_POST['distance_from_sun'];
            }
        } else {
            $distance_from_sun = null;
        }

        // Validatie voor discovery method
        if (!isset($_POST['discovery_method']) || (int)$_POST['discovery_method'] === 0) {
            $errors[] = "Discovery method is missing.";
        } else {
            $discovery_method_id = (int)$_POST['discovery_method'];
        }


        // Validatie voor habitability
        if (!isset($_POST['habitability']) || (int)$_POST['habitability'] === 0) {
            $errors[] = "Habitability is missing.";
        } else {
            $habitability_id = (int)$_POST['habitability'];
        }

        if (count($errors) == 0) {
            $result = updatePlanet((int)$id, $name, $description, $image, $length_of_year, $moons, $temperature, $diameter, $date_discovered, $mass, $distance_from_sun, $discovery_method_id, $habitability_id);
            header("Location: admin.php?message=Record with id $id ($name) is successfully edited...");
            exit;
        }
    }
}

?>
<html lang="en">
<?php require_once 'includes/head.php'; ?>

<body>
    <?php require_once 'includes/header.php'; ?>
    <main>

        <h1>Edit Planet</h1>
        <?php if (count($errors)): ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <section>

            <form action="edit.php" method="POST">
                <!-- Hidden input $id -->
                <input type="hidden" name="id" value="<?= @$id; ?>">
                <!-- Name -->
                <label for="name">Planet Name*:</label>
                <input type="text" id="name" name="name" value="<?= @$name; ?>">

                <label for="description">Description*:</label>
                <textarea id="description" name="description" rows="4"><?= @$description; ?></textarea>

                <label for="image">Image:</label>
                <input type="text" id="image" name="image" placeholder="https://..." value="<?= @$image; ?>">

                <label for="length_of_year">Length of Year (in Earth days):</label>
                <input type="number" id="length_of_year" name="length_of_year" step="0.01"
                       value="<?= @$length_of_year; ?>">

                <label for="moons">Number of Moons:</label>
                <input type="number" id="moons" name="moons" value="<?= @$moons; ?>">

                <label for="temperature">Average Temperature (°C):</label>
                <input type="text" id="temperature" name="temperature" value="<?= @$temperature; ?>">

                <label for="diameter">Diameter (in km):</label>
                <input type="number" id="diameter" name="diameter" step="0.01" value="<?= @$diameter; ?>">

                <label for="date_discovered">Date Discovered:</label>
                <input type="datetime-local" id="date_discovered" name="date_discovered"
                       value="<?= @$date_discovered; ?>">

                <label for="mass">Mass:</label>
                <input type="text" id="mass" name="mass" placeholder="e.g., 5.972 x 10^24 kg" value="<?= @$mass; ?>">

                <label for="distance_from_sun">Distance from Sun (in million km):</label>
                <input type="number" id="distance_from_sun" name="distance_from_sun" step="0.01"
                       value="<?= @$distance_from_sun; ?>">

                <label for="discovery_method">Discovery Method*:</label>
                <select id="discovery_method" name="discovery_method">
                    <option <?= @$discovery_method_id == null ? 'selected' : ''; ?> value="0">Please select a discovery
                        method
                    </option>
                    <?php foreach ($discoveryMethods as $dm): ?>
                        <option
                              value="<?= $dm['id']; ?>" <?= $dm['id'] == @$discovery_method_id ? 'selected' : ''; ?>><?= $dm['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="habitability">Habitability*:</label>
                <select id="habitability" name="habitability">
                    <option <?= @$habitability_id == null ? 'selected' : ''; ?> value="0">Please select a habitability
                    </option>
                    <?php foreach ($habitabilities as $habit): ?>
                        <option
                              value="<?= $habit['id']; ?>" <?= $habit['id'] == @$habitability_id ? 'selected' : ''; ?>><?= $habit['atmosphere_type']; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="submit"
                        id="submit"><?= isset($_GET['id']) ? 'Update Planet' : 'Submit Planet'; ?></button>

                <button type="button" onclick="window.location.href='admin.php'">Cancel</button>
            </form>
        </section>
    </main>
    <?php require_once 'includes/footer.php'; ?>
</body>

</html>