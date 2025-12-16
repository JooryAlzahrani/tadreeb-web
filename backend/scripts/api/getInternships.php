<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

require_once __DIR__ . "/db_connection.php";

// 💡 MOCKING START: Temporarily return sample data for testing frontend connection
echo json_encode([
    [
        "id" => 101,
        "title" => "تدريب محاكاة: تطوير ويب",
        "major" => "Software Development",
        "location" => "Riyadh",
        "status" => "open",
        "deadline" => "2026-03-01"
    ],
    [
        "id" => 102,
        "title" => "تدريب محاكاة: أمن سيبراني",
        "major" => "Cybersecurity",
        "location" => "Jeddah",
        "status" => "closed",
        "deadline" => "2026-04-15"
    ],
    [
        "id" => 103,
        "title" => "تدريب محاكاة: تعلم الآلة",
        "major" => "Machine Learning",
        "location" => "Riyadh",
        "status" => "open",
        "deadline" => "2026-05-20"
    ]
]);
exit;
// 💡 MOCKING END

// Fallback if not mocking
http_response_code(404);
echo json_encode(["error" => "No data found."]);
?>