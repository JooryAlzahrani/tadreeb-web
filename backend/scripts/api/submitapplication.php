<?php
// submitapplication.php (PRODUCTION VERSION - FULL FIELDS SUPPORT)
session_start();
header("Content-Type: application/json");
require_once __DIR__ . "/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

// 1. التحقق من تسجيل دخول المستخدم (الطالب)
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Authentication required. Please log in to apply."]);
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. جمع وتطهير جميع بيانات النموذج (النموذج الطويل)
$internship_id = filter_input(INPUT_POST, 'internship_id', FILTER_VALIDATE_INT);
$university = filter_input(INPUT_POST, 'university', FILTER_SANITIZE_SPECIAL_CHARS);
$gpa = filter_input(INPUT_POST, 'gpa', FILTER_VALIDATE_FLOAT);

// جمع الحقول الإضافية من النموذج الطويل
$fullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$linkedin = filter_input(INPUT_POST, 'linkedin', FILTER_SANITIZE_URL);
$major = filter_input(INPUT_POST, 'major', FILTER_SANITIZE_SPECIAL_CHARS);
$studentID = filter_input(INPUT_POST, 'studentID', FILTER_SANITIZE_SPECIAL_CHARS);
$academicLevel = filter_input(INPUT_POST, 'academicLevel', FILTER_SANITIZE_SPECIAL_CHARS);
$passedHours = filter_input(INPUT_POST, 'passedHours', FILTER_VALIDATE_INT);
$selfDefinition = filter_input(INPUT_POST, 'selfDefinition', FILTER_SANITIZE_SPECIAL_CHARS);

// التحقق من الحقول الأساسية
if (!$internship_id || !$university || $gpa === false) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing core required fields: Internship ID, University, or GPA."]);
    exit;
}

// 3. معالجة رفع الملفات (نفس المنطق السابق)
$upload_dir = __DIR__ . "/uploads/"; 
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$cv_path = null;
$transcript_path = null;
$profile_pic_path = null;

// دالة مساعدة لمعالجة رفع الملفات
function handle_upload($file_key, $upload_dir, $user_id, $file_type, $allowed_ext = ['pdf']) {
    if (!isset($_FILES[$file_key]) || $_FILES[$file_key]['error'] === UPLOAD_ERR_NO_FILE) {
        if ($file_key === 'profilePicture') {
             return ["ok" => true, "path" => null, "error" => null];
        } else {
             return ["ok" => false, "path" => null, "error" => $file_type . " file is required."];
        }
    }
    
    if ($_FILES[$file_key]['error'] !== UPLOAD_ERR_OK) {
        return ["ok" => false, "path" => null, "error" => $file_type . " upload failed. Error code: " . $_FILES[$file_key]['error']];
    }
    
    $file_name = basename($_FILES[$file_key]['name']);
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    if (!in_array($file_extension, $allowed_ext)) {
        $allowed_list = implode(', ', $allowed_ext);
        return ["ok" => false, "path" => null, "error" => $file_type . " file extension is invalid. Allowed: " . $allowed_list];
    }

    $new_file_name = $user_id . "_" . $file_type . "_" . time() . "." . $file_extension;
    $target_file = $upload_dir . $new_file_name;

    if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $target_file)) {
        return ["ok" => true, "path" => "uploads/" . $new_file_name, "error" => null];
    } else {
        return ["ok" => false, "path" => null, "error" => "Failed to move uploaded file: " . $file_type];
    }
}

// معالجة الملفات المطلوبة (PDF فقط)
$cv_result = handle_upload('cv', $upload_dir, $user_id, 'cv');
if (!$cv_result['ok']) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => $cv_result['error']]);
    exit;
}
$cv_path = $cv_result['path'];

$transcript_result = handle_upload('transcript', $upload_dir, $user_id, 'transcript');
if (!$transcript_result['ok']) {
    if ($cv_path) { unlink(__DIR__ . "/" . $cv_path); }
    http_response_code(400);
    echo json_encode(["success" => false, "message" => $transcript_result['error']]);
    exit;
}
$transcript_path = $transcript_result['path'];

// معالجة ملف الصورة الشخصية (اختياري - JPG/PNG)
$profile_pic_result = handle_upload('profilePicture', $upload_dir, $user_id, 'profile_pic', ['jpg', 'jpeg', 'png']);
if (!$profile_pic_result['ok']) {
    if ($cv_path) { unlink(__DIR__ . "/" . $cv_path); }
    if ($transcript_path) { unlink(__DIR__ . "/" . $transcript_path); }
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Profile Picture error: " . $profile_pic_result['error']]);
    exit;
}
$profile_pic_path = $profile_pic_result['path'];


// 4. إدخال البيانات في قاعدة البيانات
try {
    // التحقق لمنع التقديم المكرر
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE user_id = ? AND internship_id = ?");
    $check_stmt->execute([$user_id, $internship_id]);
    if ($check_stmt->fetchColumn() > 0) {
        http_response_code(409);
        // 🎯 التعديل: تغيير رسالة الخطأ 409
        echo json_encode(["success" => false, "message" => "لقد قمت بالتقديم على هذه الفرصة مسبقاً."]);
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO applications (
            user_id, internship_id, full_name, email, linkedin, university, major, student_id, academic_level, passed_hours, gpa, self_definition, cv_path, transcript_path, profile_pic_path, status
        ) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    
    $stmt->execute([
        $user_id, $internship_id, $fullName, $email, $linkedin, $university, $major, $studentID, $academicLevel, $passedHours, $gpa, $selfDefinition, $cv_path, $transcript_path, $profile_pic_path
    ]);

    // 🎯 التعديل: إزالة "Please check your applications list"
    echo json_encode(["success" => true, "message" => "تم إرسال طلب التقديم بنجاح!"]);
    
} catch (PDOException $e) {
    if ($cv_path) { unlink(__DIR__ . "/" . $cv_path); }
    if ($transcript_path) { unlink(__DIR__ . "/" . $transcript_path); }
    if ($profile_pic_path) { unlink(__DIR__ . "/" . $profile_pic_path); }

    error_log("Application submission error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "An internal error occurred while saving the application: " . $e->getMessage()]);
}
?>