<?php
// db_connection.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$db   = "tadreeb_db"; // تأكدي من أن اسم قاعدة البيانات في XAMPP هو 'tadreeb_db'
$user = "root";       // اسم المستخدم الافتراضي لـ XAMPP
$pass = "";           // كلمة المرور الافتراضية لـ XAMPP (عادة فارغة)

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Database connection failed",
        "details" => $e->getMessage()
    ]);
    exit;
}
?>