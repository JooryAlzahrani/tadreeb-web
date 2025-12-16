<?php
// getInternshipByStatus.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

require_once __DIR__ . "/db_connection.php";

/**
 * Validate input status
 */
if (!isset($_GET['status']) || empty(trim($_GET['status']))) {
    http_response_code(400);
    echo json_encode([
        "error" => "Status parameter is required ('open' or 'closed')"
    ]);
    exit;
}

$status = trim($_GET['status']);

/**
 * Handle 'all' status: redirect to main API
 */
if ($status === 'all') {
    // Redirect internal request to the main API file to fetch all data
    header("Location: getInternships.php");
    exit;
}

/**
 * Query internships by status (e.g., status = 'open' or status = 'closed')
 */
$stmt = $pdo->prepare(
    "SELECT 
        internshipID AS id,
        title,
        major,
        location,
        short_description,
        full_description,
        requirements,
        image_url,
        application_link,
        deadline
     FROM Internship
     WHERE status = ?
     ORDER BY deadline ASC"
);

$stmt->execute([$status]);

$results = $stmt->fetchAll();

echo json_encode($results);
?><?php
// getInternshipByStatus.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

require_once __DIR__ . "/db_connection.php";

/**
 * Validate input status
 */
if (!isset($_GET['status']) || empty(trim($_GET['status']))) {
    http_response_code(400);
    echo json_encode([
        "error" => "Status parameter is required ('open' or 'closed')"
    ]);
    exit;
}

$status = trim($_GET['status']);

/**
 * Handle 'all' status: redirect to main API
 */
if ($status === 'all') {
    // Redirect internal request to the main API file to fetch all data
    header("Location: getInternships.php");
    exit;
}

/**
 * Query internships by status (e.g., status = 'open' or status = 'closed')
 */
$stmt = $pdo->prepare(
    "SELECT 
        internshipID AS id,
        title,
        major,
        location,
        short_description,
        full_description,
        requirements,
        image_url,
        application_link,
        deadline
     FROM Internship
     WHERE status = ?
     ORDER BY deadline ASC"
);

$stmt->execute([$status]);

$results = $stmt->fetchAll();

echo json_encode($results);
?>