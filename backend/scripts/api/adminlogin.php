<?php
// admin_login.php
session_start();
header("Content-Type: application/json");
require_once __DIR__ . "/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

// Collect input
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';

if (!$email || empty($password)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Email and password are required."]);
    exit;
}

try {
    // 1. Fetch user data by email
    $stmt = $pdo->prepare("SELECT user_id, full_name, password_hash, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash']) && $user['role'] === 'admin') {
        
        // 2. Success: Set Session Variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role']; // MUST be 'admin'

        echo json_encode([
            "success" => true,
            "message" => "Admin Login successful!",
            "redirect" => "admin-dashboard.html"
        ]);
        
    } else {
        // 3. Failure: Invalid credentials or not an admin
        http_response_code(401); 
        echo json_encode(["success" => false, "message" => "Invalid credentials or unauthorized access."]);
    }

} catch (PDOException $e) {
    error_log("Admin Login error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "An internal error occurred."]);
}
?>