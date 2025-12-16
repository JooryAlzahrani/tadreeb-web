<?php
// submit_review.php (FINAL FIX - Correcting 'created_at' column name)
session_start();
header("Content-Type: application/json");
// تأكدي من أن المسار إلى db_connection.php صحيح
require_once __DIR__ . "/db_connection.php"; 

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

// 1. التحقق من تسجيل دخول المستخدم (الطالب)
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Authentication required. Please log in to submit a review."]);
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. جمع وتطهير بيانات النموذج
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
$organization = filter_input(INPUT_POST, 'organization', FILTER_SANITIZE_SPECIAL_CHARS);
$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS);
$major = filter_input(INPUT_POST, 'major', FILTER_SANITIZE_SPECIAL_CHARS);
$review_text = filter_input(INPUT_POST, 'review_text', FILTER_SANITIZE_SPECIAL_CHARS);

// 3. التحقق من الحقول المطلوبة
if (empty($name) || empty($organization) || empty($city) || empty($major) || empty($review_text)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "All review fields (Name, Organization, City, Major, and Review Text) are required."]);
    exit;
}

// 4. إدخال البيانات في قاعدة البيانات
try {
    // 🎯 التعديل: تغيير اسم العمود من 'review_date' إلى 'created_at' ليطابق قاعدة بياناتك
    $stmt = $pdo->prepare("
        INSERT INTO reviews (user_id, name, organization, city, major, review_text, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([$user_id, $name, $organization, $city, $major, $review_text]);

    echo json_encode(["success" => true, "message" => "تم إرسال مراجعتك بنجاح! شكراً لمشاركتك."]);
    
} catch (PDOException $e) {
    error_log("Review submission error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "An internal error occurred while saving the review: " . $e->getMessage()]);
}
?>