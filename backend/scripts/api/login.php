<?php
// login.php
session_start();
header("Content-Type: application/json");
require_once __DIR__ . "/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

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

    if ($user && password_verify($password, $user['password_hash'])) {
        
        // 2. Success: Set Session Variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];
        
        $redirect_to = ($user['role'] === 'admin') ? 'admin-dashboard.html' : 'homepage.html';

        echo json_encode([
            "success" => true,
            "message" => "Login successful!",
            "redirect" => $redirect_to,
            "role" => $user['role']
        ]);
        
    } else {
        // 3. Failure: Invalid credentials
        http_response_code(401); // Unauthorized
        echo json_encode(["success" => false, "message" => "Invalid email or password."]);
    }

} catch (PDOException $e) {
    error_log("Login error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "An internal error occurred."]);
}
?>