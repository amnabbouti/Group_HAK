<?php
include_once "includes/db.inc.php";

use Dotenv\Dotenv;

// Gegevens ophalen van de API
function getNasaFeaturedData()
{
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $nasaApiKey = $_ENV['NASA_API_KEY'];
    return getNasaApodData($nasaApiKey);
}

function fetchDataFromApi($url)
{
    $response = @file_get_contents($url);
    return $response ? json_decode($response, true) : null;
}

function getNasaApodData($apiKey)
{
    $url = "https://api.nasa.gov/planetary/apod?api_key={$apiKey}";
    $data = fetchDataFromApi($url);
    if (!$data || isset($data['error'])) {
        return [
            'title' => "Astronomy Picture of the Day Unavailable",
            'description' => "No description available.",
            'image' => "",
            'mediaType' => "error"
        ];
    }
    // Gegevens structureren voor NASA APOD
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

function buildPlanetQuery($filters, $params, $orderBy)
{
    // Query samenstellen om planeten op te halen
    $whereClause = !empty($filters) ? "WHERE " . implode(" AND ", $filters) : "";
    $query = "SELECT id, name, description, image FROM planets $whereClause $orderBy";
    return [$query, $params];
}

function buildCountQuery($filters)
{
    // Tel query voor paginering (count query for pagination)
    $whereClause = !empty($filters) ? "WHERE " . implode(" AND ", $filters) : "";
    return "SELECT COUNT(*) FROM planets $whereClause";
}


// function by id voor de detail pagina.
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

function setLogin($uid = false)
{
    $_SESSION['loggedin'] = time() + 3600;

    if ($uid) {
        $_SESSION['uid'] = $uid;
    }
}

function isLoggedIn(): bool
{
    session_start();

    $loggedin = FALSE;

    if (isset($_SESSION['loggedin'])) {
        if ($_SESSION['loggedin'] > time()) {
            $loggedin = TRUE;
            setLogin();
        }
    }

    return $loggedin;
}

function isValidLogin(string $mail, string $password): bool
{
    $sql = "SELECT id FROM users WHERE mail=:mail AND password=:password AND status = 1";

    $stmt = connectToDB()->prepare($sql);
    $stmt->execute([
        ':mail' => $mail,
        ':password' => md5($password)
    ]);
    return $stmt->fetch(PDO::FETCH_COLUMN);
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
        header("Location: index.php");
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

function registerNewMember(string $username, string $firstname, string $lastname, string $mail, string $password): bool|int
{
    $db = connectToDB();
    $sql = "INSERT INTO users(username, firstname, lastname, mail, password) VALUES (:username, :firstname, :lastname, :mail, :password)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':firstname' => $firstname,
        ':lastname' => $lastname,
        ':mail' => $mail,
        ':password' => md5($password),
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

function getPlanets(): array
{
    $sql = "SELECT * FROM planets";

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
