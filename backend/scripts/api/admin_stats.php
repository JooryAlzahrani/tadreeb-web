<?php
// admin_stats.php
session_start();
header("Content-Type: application/json");
require_once __DIR__ . "/db_connection.php";

// 1. Authorization Check (Must be logged in as Admin)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["error" => "Access Denied. Admin privileges required."]);
    exit;
}

try {
    // Define date ranges for simplicity (Today & Last 7 days)
    $today = date("Y-m-d 00:00:00");
    // $seven_days_ago = date("Y-m-d 00:00:00", strtotime("-7 days"));
    
    // 2. Fetch Daily KPIs (KPIs)
    $daily_reg = $pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= '$today'")->fetchColumn();
    $daily_apps = $pdo->query("SELECT COUNT(*) FROM applications WHERE created_at >= '$today'")->fetchColumn();
    // Assuming you track 'signed' status in the applications table
    $daily_signed = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'signed' AND created_at >= '$today'")->fetchColumn();
    
    // 3. Fetch Monthly Statistics (Monthly)
    $total_students = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
    $total_companies = $pdo->query("SELECT COUNT(DISTINCT title) FROM Internship")->fetchColumn(); // Simplified
    $total_matches = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'signed'")->fetchColumn(); 

    // 4. Fetch Top Organizations (Table)
    $top_orgs_stmt = $pdo->query("
        SELECT 
            i.title AS organization, 
            COUNT(a.application_id) AS applications_count
        FROM applications a
        JOIN Internship i ON a.internship_id = i.internshipID
        GROUP BY i.title
        ORDER BY applications_count DESC
        LIMIT 4
    ");
    $top_orgs = $top_orgs_stmt->fetchAll();

    // 5. Structure and Return Data
    echo json_encode([
        "success" => true,
        "daily" => [
            "registered_today" => (int)$daily_reg,
            "applications_today" => (int)$daily_apps,
            "signed_today" => (int)$daily_signed,
            "top_orgs" => $top_orgs 
        ],
        "monthly" => [
            "total_students" => (int)$total_students,
            "partner_companies" => (int)$total_companies,
            "successful_matches" => (int)$total_matches
        ],
    ]);

} catch (PDOException $e) {
    error_log("Admin Stats error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Internal server error fetching statistics."]);
}
?>