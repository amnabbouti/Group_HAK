<?php
include_once "db.inc.php";

use Dotenv\Dotenv;

// Nasa API
function getNasaFeaturedData(): array
{
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
    $nasaApiKey = $_ENV['NASA_API_KEY'];
    $url = "https://api.nasa.gov/planetary/apod?api_key={$nasaApiKey}";
    $response = @file_get_contents($url);
    $data = $response ? json_decode($response, true) : null;
    if (!$data || isset($data['error'])) {
        return [
            'title' => "Astronomy Picture of the Day Unavailable",
            'description' => "No description available.",
            'image' => "",
            'mediaType' => "error"
        ];
    }
    return [
        'title' => $data['title'] ?? "Astronomy Picture of the Day",
        'description' => $data['explanation'] ?? "Explore the cosmos with Miller's world!",
        'image' => $data['url'] ?? "",
        'mediaType' => $data['media_type'] ?? "image"
    ];
}

// pagination
function paginate($planetData, $itemsPerPage, $currentPage)
{
    $totalItems = count($planetData);
    $totalPages = ceil($totalItems / $itemsPerPage);
    $previousPage = ($currentPage > 1) ? $currentPage - 1 : null;
    $nextPage = ($currentPage < $totalPages) ? $currentPage + 1 : null;
    // Alleen de items van de huidige pagina tonen (show only current page's items)
    $start = ($currentPage - 1) * $itemsPerPage;
    $currentPageData = array_slice($planetData, $start, $itemsPerPage);
    return [
        'items' => $currentPageData,
        'previousPage' => $previousPage,
        'nextPage' => $nextPage
    ];
}

function buildFiltersAndParams(array $input): array
{
    $filters = ["is_published = 1"]; // Default filter
    $params = [];
    if (!empty($input['name'])) {
        $filters[] = "name LIKE :name";
        $params[':name'] = "%" . $input['name'] . "%";
    }
    if (isset($input['moons']) && !empty($input['moons'])) {
        if ($input['moons'] == 'No Moons') {
            $filters[] = "moons = 0";
        } elseif ($input['moons'] == '1 Moon') {
            $filters[] = "moons = 1";
        } elseif ($input['moons'] == 'More than 1 Moon') {
            $filters[] = "moons > 1";
        }
    }

    return [$filters, $params];
}

function getOrderBy(array $input): string
{
    $orderBy = "ORDER BY name ASC";

    if (!empty($input['sort']) && in_array($input['sort'], ['name', 'diameter', 'moons', 'date_discovered', 'distance_from_sun'])) {
        //default to ASC if 'order' is not used, otherwise use 'desc'
        $direction = isset($input['order']) && $input['order'] == 'desc' ? 'DESC' : 'ASC';
        $orderBy = "ORDER BY " . $input['sort'] . " " . $direction;
    }

    return $orderBy;
}


// getting planets for the detail page
function get_planet_by_id(int $id): ?array
{
    try {
        $db = connectToDB();
        $sql = "SELECT * FROM planets WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $planet = $stmt->fetch(PDO::FETCH_ASSOC);
        return $planet;
    } catch (PDOException $e) {
        error_log("Error fetching planet details: " . $e->getMessage());
        return null;
    }
}

// getting all the users for the admin page and profile page
function getAllUsers(?int $id = null): array
{
    $db = connectToDB();
    $sql = "SELECT id, username, firstname, lastname, mail, role, profile_picture FROM users";
    $params = [];
    if ($id !== null) {
        $sql .= " WHERE id = ?";
        $params[] = $id;
    }
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    if ($id !== null) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? [$user] : [];
    }
    // Fetch all users
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLoggedInUser(): array
{
    $user = [];
    if (isset($_SESSION['id'])) {
        $db = connectToDB();
        $query = $db->prepare("SELECT * FROM users WHERE id = ?");
        $query->execute([$_SESSION['id']]);
        $user = $query->fetch(PDO::FETCH_ASSOC);
    }
    return $user;
}


function setLogin($uid = false)
{
    $_SESSION['loggedin'] = time() + 3600;
    if ($uid) {
        $_SESSION['uid'] = $uid;
    }
}

function isLoggedIn(): bool
{
    $loggedin = FALSE;
    if (isset($_SESSION['loggedin'])) {
        if ($_SESSION['loggedin'] > time()) {
            $loggedin = TRUE;
            setLogin();
        }
    }
    return $loggedin;
}

// checking valid logins
function isValidLogin($mail, $password)
{
    $db = connectToDB();
    $stmt = $db->prepare("SELECT id, password, role FROM users WHERE mail = :mail");
    $stmt->execute([':mail' => $mail]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (strlen($user['password']) === 32 && md5($password) === $user['password']) {
            //rehashing the password
            $newHashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateStmt = $db->prepare("UPDATE users SET password = :password WHERE id = :id");
            $updateStmt->execute([':password' => $newHashedPassword, ':id' => $user['id']]);
            return $user['id'];
        }
        if (password_verify($password, $user['password'])) {
            return $user['id'];
        }
    }
    return false;
}

function requiredLoggedIn()
{
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function requiredLoggedOut()
{
    if (isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function existingUsername($username)
{
    $pdo = connectToDB();
    $stmt = $pdo->prepare("SELECT username FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}

function existingMail(string $mail): bool
{
    $sql = "SELECT mail FROM users WHERE mail = :mail";
    $stmt = connectToDB()->prepare($sql);
    $stmt->execute([':mail' => $mail]);
    return $stmt->fetch(PDO::FETCH_COLUMN);
}

// handeling login function
function handleLogin(array $postData): ?array
{
    $errors = [];
    if (empty(trim($postData['mail'] ?? ''))) {
        $errors[] = "Please fill in e-mail.";
    }
    if (empty(trim($postData['password'] ?? ''))) {
        $errors[] = "Please fill in password.";
    }
    if (empty($errors)) {
        if ($uid = isValidLogin($postData['mail'], $postData['password'])) {
            setLogin($uid);
            $_SESSION['id'] = $uid;
            $db = connectToDB();
            $query = $db->prepare("SELECT role FROM users WHERE id = ?");
            $query->execute([$uid]);
            $user = $query->fetch();
            if ($user) {
                $_SESSION['role'] = $user['role'];
                if ($user['role'] === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: profile.php");
                }
                exit;
            }
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
    return $errors;
}

// registering new users
function registerNewMember(string $username, string $firstname, string $lastname, string $mail, string $password, string $role): bool|int
{
    $db = connectToDB();
    $sql = "INSERT INTO users(username, firstname, lastname, mail, password, role) VALUES (:username, :firstname, :lastname, :mail, :password, :role)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':firstname' => $firstname,
        ':lastname' => $lastname,
        ':mail' => $mail,
        ':password' => $password,
        ':role' => $role,
    ]);
    return $db->lastInsertId();
}

function deletePlanet(int $id)
{
    $db = connectToDB();
    $sql = "DELETE FROM planets
            WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':id' => $id
    ]);
    return $db->lastInsertId();
}

function deleteUser(int $id)
{
    $db = connectToDB();
    $sql = "DELETE FROM users
            WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':id' => $id
    ]);
    return $db->lastInsertId();
}

function getPlanets(): array
{
    $sql = "SELECT id, name, description, image, is_published, date_added, date_edited FROM planets";

    $stmt = connectToDB()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDiscoveryMethods(): array
{
    $sql = "SELECT * FROM discovery_methods";
    $stmt = connectToDB()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getHabitabilities(): array
{
    $sql = "SELECT * FROM habitability";

    $stmt = connectToDB()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertPlanet(string $name, string $description, string $image, $length_of_year, $moons, $temperature, $diameter, $date_discovered, $mass, $distance_from_sun, $discovery_method_id, $habitability_id)
{
    $db = connectToDB();
    $sql = "INSERT INTO planets (name, description, image, length_of_year, moons, temperature, diameter, date_discovered, mass, distance_from_sun, discovery_method_id, habitability_id) VALUES (
        :name, :description, :image, :length_of_year, :moons, :temperature, :diameter, :date_discovered, :mass, :distance_from_sun, :discovery_method_id, :habitability_id
    )";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':image' => $image,
        ':length_of_year' => $length_of_year,
        ':moons' => $moons,
        ':temperature' => $temperature,
        ':diameter' => $diameter,
        ':date_discovered' => $date_discovered,
        ':mass' => $mass,
        ':distance_from_sun' => $distance_from_sun,
        ':discovery_method_id' => $discovery_method_id,
        ':habitability_id' => $habitability_id
    ]);
    return $db->lastInsertId();
}

function updatePlanet(int $id, string $name, string $description, string $image, $length_of_year, $moons, $temperature, $diameter, $date_discovered, $mass, $distance_from_sun, $discovery_method_id, $habitability_id): bool|int
{
    $db = connectToDB();
    $sql = "UPDATE planets SET name= :name, description= :description, image= :image, length_of_year= :length_of_year, moons= :moons, temperature= :temperature, diameter= :diameter, date_discovered= :date_discovered, mass= :mass, distance_from_sun= :distance_from_sun, discovery_method_id= :discovery_method_id, habitability_id= :habitability_id 
            WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':name' => $name,
        ':description' => $description,
        ':image' => $image,
        ':length_of_year' => $length_of_year,
        ':moons' => $moons,
        ':temperature' => $temperature,
        ':diameter' => $diameter,
        ':date_discovered' => $date_discovered,
        ':mass' => $mass,
        ':distance_from_sun' => $distance_from_sun,
        ':discovery_method_id' => $discovery_method_id,
        ':habitability_id' => $habitability_id
    ]);
    return $db->lastInsertId();
}

// get userbyid for updating user details/edit button
function getUserById(int $id): ?array
{
    try {
        $db = connectToDB();
        $sql = "SELECT id, username, firstname, lastname, mail, role, profile_picture FROM users WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user : null;
    } catch (PDOException $e) {
        error_log("Error fetching user details: " . $e->getMessage());
        return null;
    }
}

// Update user details
function updateMember(int $id, string $username, string $firstname, string $lastname, string $mail, ?string $profile_picture): bool
{
    try {
        $db = connectToDB();
        $stmt = $db->prepare("SELECT id FROM users WHERE (username = :username OR mail = :mail) AND id != :id");
        $stmt->execute([':username' => $username, ':mail' => $mail, ':id' => $id]);
        if ($stmt->rowCount() > 0) {
            return false;
        }
        $sql = "UPDATE users SET 
                    username = :username,
                    firstname = :firstname,
                    lastname = :lastname,
                    mail = :mail,
                    profile_picture = :profile_picture
                WHERE id = :id";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':username' => $username,
            ':firstname' => $firstname,
            ':lastname' => $lastname,
            ':mail' => $mail,
            ':profile_picture' => $profile_picture ?? $profile_picture == 'public/assets/images/user.jpg',
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Error updating user details: " . $e->getMessage());
        return false;
    }
}



// setting a default profile picture for users that have no picture
function default_profile_picture(array $user): array
{
    if (empty($user['profile_picture']) || !file_exists($user['profile_picture'])) {
        $user['profile_picture'] = '../public/assets/images/user.png';
    }
    return $user;
}

function sortPlanets(string $sort, string $direction)
{
    $db = connectToDB();
    $sql = "SELECT * FROM planets ORDER BY $sort $direction";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// password reset
function getUserByEmail($mail)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE mail = :mail");
    $stmt->bindParam(':mail', $mail);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function saveToken($userId, $token, $expiry)
{
    global $db;
    $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (:user_id, :token, :expiry)");
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expiry', $expiry);
    $stmt->execute();
}

function getToken($token)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM password_resets WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function updatePassword($userId, $password)
{
    global $db;
    $stmt = $db->prepare("UPDATE users SET password = :password WHERE id = :id");
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
}

function deleteToken($token)
{
    global $db;
    $stmt = $db->prepare("DELETE FROM password_resets WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
}

function existingName(String $name): bool
{
    $sql = "SELECT name FROM planets WHERE name = :name";
    $stmt = connectToDB()->prepare($sql);
    $stmt->execute([':name' => $name]);
    return $stmt->fetch(PDO::FETCH_COLUMN);
}
