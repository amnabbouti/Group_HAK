<?php
require_once 'includes/init.php';
$planets = getPlanets();
$users = getAllUsers();

$sort = 'name';
$direction = 'ASC';

if (in_array(@$_GET['sort'], ['id', 'name', 'date_added', 'date_edited'])) {
    $sort = $_GET['sort'];
}

if (in_array(@$_GET['dir'], ['down'])) {
    $direction = 'DESC';
}

$planets = sortPlanets($sort, $direction);
?>

<html lang="en">
<?php require_once 'includes/head.php'; ?>

<body>
    <?php require_once 'includes/header.php'; ?>
    <main>
        <section class="planets">
            <h1>Admin Dashboard</h1>
            <table style="padding: 10px">
                <thead>
                    <tr>
                        <th><a href="?sort=id&dir=<?= ($sort == 'id' && $direction == 'ASC' ? 'down' : 'up'); ?>">#ID↕</a></th>
                        <th><a href="?sort=name&dir=<?= ($sort == 'name' && $direction == 'ASC' ? 'down' : 'up'); ?>">Planet↕</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th><a href="?sort=date_added&dir=<?= ($sort == 'date_added' && $direction == 'ASC' ? 'down' : 'up'); ?>">Date added↕</th>
                        <th><a href="?sort=date_edited&dir=<?= ($sort == 'date_edited' && $direction == 'ASC' ? 'down' : 'up'); ?>">Date edited↕</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($planets as $planet): ?>
                        <tr>
                            <td><?= $planet['id']; ?></td>
                            <td><?= $planet['name']; ?></td>
                            <td><?= mb_strimwidth($planet['description'], 0, 100, "..."); ?></td>
                            <td><img src="<?= $planet['image']; ?>" alt="<?= $planet['name']; ?>"></td>
                            <td><?= date("Y-m-d", strtotime($planet['date_added'])) ?: 'Not available'; ?></td>
                            <td><?= $planet['date_edited'] ? date("Y-m-d", strtotime($planet['date_edited'])) : 'Never edited'; ?></td>
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
                                <form method="post" action="deletePlanet.php">
                                    <input type="hidden" name="id" value="<?= $planet['id']; ?>">
                                    <button type="submit" class="delete"
                                        onclick="return confirm('Are you sure you want to delete this planet?');">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <br>
            <br>
            <br>
            <hr>
            <br>
            <br>
            <h1>Manage users</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="8">No users found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <?php $user = default_profile_picture($user); ?>
                            <tr>
                                <td><?= $user['id']; ?></td>
                                <td>
                                    <img src="<?= $user['profile_picture']; ?>"
                                        alt="<?= $user['username']; ?>"
                                        style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; display: block; margin: 0 auto;">
                                </td>
                                <td><?= $user['username']; ?></td>
                                <td><?= $user['firstname']; ?></td>
                                <td><?= $user['lastname']; ?></td>
                                <td><?= $user['mail']; ?></td>
                                <td>
                                    <span style="font-weight: bold; font-size: 1.2em; text-transform: uppercase;"><?= $user['role']; ?></span>
                                </td>
                                <td class="buttons">
                                    <form method="post" action="deleteUser.php">
                                        <input type="hidden" name="id" value="<?= $user['id']; ?>">
                                        <button type="submit" class="delete"
                                            onclick="return confirm('Are you sure you want to delete this user?');">
                                            Delete
                                        </button>
                                    </form>
                                    <form method="get" action="edit.php">
                                        <input type="hidden" name="id" value="<?= $user['id']; ?>">
                                        <button type="submit" class="edit">Edit</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
    <?php require_once 'includes/footer.php'; ?>
</body>

</html>