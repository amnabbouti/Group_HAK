<?php


function makeRequest(String $url)
{
    $curl_handle = curl_init();

    curl_setopt($curl_handle, CURLOPT_URL, $url); // de locatie waar ik een request naartoe stuur
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true); // ik wil een antwoord ontvangen van de request url
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false); // expliciet zeggen dat we van http naar https toch willen werken

    $curl_data = curl_exec($curl_handle);
    curl_close($curl_handle);

    $response = json_decode($curl_data);

    if ($response === null)
        return false;

    return $response;
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

function isValidLogin(String $mail, String $password): bool
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
        header("Location: admin.php");
        exit;
    }
}

function existingUsername(String $username): bool
{
    $sql = "SELECT username FROM users WHERE usernmae = :username";
    $stmt = connectToDB()->prepare($sql);
    $stmt->execute([':username' => $username]);
    return $stmt->fetch(PDO::FETCH_COLUMN);
}

function existingMail(String $mail): bool
{
    $sql = "SELECT mail FROM users WHERE mail = :mail";
    $stmt = connectToDB()->prepare($sql);
    $stmt->execute([':mail' => $mail]);
    return $stmt->fetch(PDO::FETCH_COLUMN);
}

function registerNewMember(String $username, String $firstname, String $lastname, String $mail, String $password): bool|int
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
