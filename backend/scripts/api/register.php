<?php
header("Content-Type: application/json");
require_once __DIR__ . "/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

$full_name = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm'] ?? '';

//  Basic validation
if (!$full_name || !$email || empty($password) || $password !== $confirm_password) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Please fill all fields correctly, and ensure passwords match."]);
    exit;
}

// 🔑 Secure Password Hashing (تجزئة كلمة المرور)
$password_hash = password_hash($password, PASSWORD_DEFAULT);


try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        http_response_code(409); // Conflict
        echo json_encode(["success" => false, "message" => "This email is already registered."]);
        exit;
    }

    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, ?)");
    
    // استخدام $password_hash المخزنة بدلاً من الكلمة العادية
    $stmt->execute([$full_name, $email, $password_hash, 'student']);

    echo json_encode(["success" => true, "message" => "Registration successful. You can now log in.", "redirect" => "login.html"]);
    
} catch (PDOException $e) {
    // Log the error detail internally, but send a generic error to the user
    error_log("Registration error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "An error occurred during registration. Please try again later."]);
}
?>