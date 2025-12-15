<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

require_once __DIR__ . "/db_connection.php";

/**
 * If an ID is provided → return one internship
 */
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

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
         WHERE internshipID = ?"
    );

    $stmt->execute([$id]);
    echo json_encode($stmt->fetch() ?: []);
    exit;
}

/**
 * No ID → return ALL internships
 */
$stmt = $pdo->query(
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
     ORDER BY deadline ASC"
);

echo json_encode($stmt->fetchAll());
