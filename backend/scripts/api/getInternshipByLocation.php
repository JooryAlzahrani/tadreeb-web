<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

require_once __DIR__ . "/db_connection.php";

/**
 * 1️⃣ If ID is provided → return one internship
 */
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

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
 * 2️⃣ If location is provided → filter by location
 */
if (isset($_GET['location']) && $_GET['location'] !== "all") {
    $location = $_GET['location'];

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
         WHERE location = ?
         ORDER BY deadline ASC"
    );

    $stmt->execute([$location]);
    echo json_encode($stmt->fetchAll());
    exit;
}

/**
 * 3️⃣ No location → return all internships
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
