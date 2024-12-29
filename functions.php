<?php

function makeRequest(string $url)
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


/**
 * Fetch data from an API
 * @param string $url
 * @return array|null
 */
function fetchDataFromApi($url)
{
    $response = @file_get_contents($url);
    return $response ? json_decode($response, true) : null;
}

/**
 * Get NASA's Astronomy Picture of the Day data
 * @param string $apiKey NASA API key
 * @return array Processed data including title, description, image, and media type
 */
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

/**
 * Get Unsplash images
 * @param string $query
 * @param string $apiKey
 * @return array
 */
function getUnsplashImages($query, $apiKey)
{
    $url = "https://api.unsplash.com/search/photos?query=" . urlencode($query) . "&client_id={$apiKey}";
    $data = fetchDataFromApi($url);

    return isset($data['results']) ? array_map(fn($item) => $item['urls']['regular'], $data['results']) : [];
}

/**
 * Paginate items
 * @param array $items
 * @param int $itemsPerPage
 * @param int $currentPage
 * @return array
 */
function paginate($items, $itemsPerPage, $currentPage)
{
    $totalItems = count($items);
    $totalPages = ceil($totalItems / $itemsPerPage);
    $currentPage = max(1, $currentPage);
    $startIndex = ($currentPage - 1) * $itemsPerPage;

    return [
        'items' => array_slice($items, $startIndex, $itemsPerPage),
        'previousPage' => $currentPage > 1 ? $currentPage - 1 : null,
        'nextPage' => $currentPage < $totalPages ? $currentPage + 1 : null,
        'totalPages' => $totalPages
    ];
}
