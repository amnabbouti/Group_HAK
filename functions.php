<?php

use Dotenv\Dotenv;

function getNasaFeaturedData()
{
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    // NASA API
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
    return [
        'title' => $data['title'] ?? "Astronomy Picture of the Day",
        'description' => $data['explanation'] ?? "Explore the cosmos with Miller's world!",
        'image' => $data['url'] ?? "",
        'mediaType' => $data['media_type'] ?? "image"
    ];
}

function paginate($planetData, $itemsPerPage, $currentPage)
{
    $totalItems = count($planetData);
    $totalPages = ceil($totalItems / $itemsPerPage);
    $previousPage = ($currentPage > 1) ? $currentPage - 1 : null;
    $nextPage = ($currentPage < $totalPages) ? $currentPage + 1 : null;
    // Slice only show the current page's items
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
    $whereClause = !empty($filters) ? "WHERE " . implode(" AND ", $filters) : "";
    $query = "SELECT id, name, description, image FROM planets $whereClause $orderBy";
    return [$query, $params];
}

function buildCountQuery($filters)
{
    // Count query for pagination
    $whereClause = !empty($filters) ? "WHERE " . implode(" AND ", $filters) : "";
    return "SELECT COUNT(*) FROM planets $whereClause";
}

