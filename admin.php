<?php
require('includes/db.inc.php');
require('functions.php');

$planets = getPlanets();

print "<pre>";
print_r($planets);
print "</pre>";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>

<body>
    <section class="planets">
        <h1>Planets overview</h1>
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Planet</th>
                    <th>Description</th>
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
                        <td><?= $planet['description']; ?></td>
                        <td><?= $planet['date_discovered']; ?></td>
                        <td>Not yet added</td>
                        <td>View</td>
                        <td>Edit</td>
                        <td>Delete</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

</body>

</html>