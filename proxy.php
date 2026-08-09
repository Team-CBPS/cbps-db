<?php
// Robust CORS-Enabled Proxy with domain whitelisting and error handling

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, HEAD, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Preflight header response for CORS
    http_response_code(204);
    exit;
}

if (!isset($_GET['url'])) {
    http_response_code(400);
    echo "Missing 'url' parameter";
    exit;
}

// Sanitize and parse the URL
$url = filter_var($_GET['url'], FILTER_SANITIZE_URL);
if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo "Invalid URL";
    exit;
}

// Whitelist allowed domains for security
$allowed_hosts = [
    'github.com',
    'raw.githubusercontent.com',
    'drive.google.com',
    'gist.githubusercontent.com'
];

$parsed_url = parse_url($url);
if (!isset($parsed_url['host']) || !in_array($parsed_url['host'], $allowed_hosts)) {
    http_response_code(403);
    echo "Domain not allowed";
    exit;
}

// Use cURL for better compatibility and error handling
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
// Optionally set timeout
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
// Optionally set user agent (some domains require it)
curl_setopt($ch, CURLOPT_USERAGENT, 'cbps-db-proxy/1.0');

// Forward HTTP headers from the target if possible
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
if ($response === false) {
    http_response_code(500);
    echo "Failed to fetch remote file: " . curl_error($ch);
    curl_close($ch);
    exit;
}

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $header_size);
$body = substr($response, $header_size);

// Forward content-type if available
if (preg_match('/Content-Type:\s*([^\r\n]+)/i', $headers, $matches)) {
    header("Content-Type: " . trim($matches[1]));
} else {
    header("Content-Type: application/octet-stream");
}

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code >= 400) {
    http_response_code($http_code);
    echo "Error fetching resource. HTTP Code: $http_code";
    exit;
}

// All good, send the response
echo $body;
?>