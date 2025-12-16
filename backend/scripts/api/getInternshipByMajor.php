<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

require_once __DIR__ . "/db_connection.php";

/**
 * Validate input
 */
if (!isset($_GET['major']) || empty(trim($_GET['major']))) {
    http_response_code(400);
    echo json_encode([
        "error" => "Major parameter is required"
    ]);
    exit;
}

$major = trim($_GET['major']);

/**
 * Query internships by major
 * Using LIKE because majors are stored as text ( "Computer Science, IT")
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
     WHERE major LIKE ?
     ORDER BY deadline ASC"
);

$stmt->execute(["%$major%"]);

$results = $stmt->fetchAll();

echo json_encode($results);
