<?php
include 'includes/db.php';
include 'includes/functions.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: common_login.php");
    exit();
}

$username = $_SESSION['username'];
$profile_data = null;
$role_name = "";
$extra_fields = [];

$stmt = $pdo->prepare("SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id = r.role_id WHERE u.username = ?");
$stmt->execute([$username]);
$profile_data = $stmt->fetch();

if ($profile_data) {
    $role_name = $profile_data['role_name'];
} 
else {
    $stmt = $pdo->prepare("SELECT *, 'STUDENT' as role_name FROM student WHERE username = ?");
    $stmt->execute([$username]);
    $profile_data = $stmt->fetch();

    if ($profile_data) {
        $role_name = "Student";
        $extra_fields = ["Stream" => $profile_data['stream'], "Semester" => $profile_data['semester']];
    } else {
        $stmt = $pdo->prepare("SELECT *, 'FACULTY' as role_name FROM faculty WHERE username = ?");
        $stmt->execute([$username]);
        $profile_data = $stmt->fetch();
        
        if ($profile_data) {
            $role_name = "Faculty Member";
            $extra_fields = ["Department" => $profile_data['department']];
        }
    }
}

if (!$profile_data) { die("Profile not found."); }

$initial = strtoupper($username[0]);
include 'includes/header.php';
?>

<link rel="stylesheet" href="css/profile.css">
<div class="profile-actions">
            <a href="home.php" class="back-btn">
                <i class="fas fa-chevron-left"></i> Back to Dashboard
            </a>
        </div>
<div class="profile-wrapper">
    <div class="profile-card">
        <div class="profile-banner"></div>
        
        <div class="profile-header-main">
            <div class="avatar-initials"><?= $initial ?></div>
            <div class="header-text">
                <h1><?= htmlspecialchars($profile_data['username']) ?></h1>
                <div class="role-badge">
                    <i class="fas fa-user-shield"></i> <?= $role_name ?>
                </div>
            </div>
        </div>

        <div class="profile-details-grid">
            <div class="info-item">
                <label>Email ID</label>
                <div class="data-value"><i class="fas fa-envelope"></i> <?= htmlspecialchars($profile_data['email']) ?></div>
            </div>

            <div class="info-item">
                <label>Member ID</label>
                <div class="data-value"><i class="fas fa-fingerprint"></i> #ID-0<?= $profile_data['user_id'] ?? $profile_data['id'] ?></div>
            </div>

            <?php foreach ($extra_fields as $label => $val): ?>
                <div class="info-item">
                    <label><?= $label ?></label>
                    <div class="data-value"><i class="fas fa-briefcase"></i> <?= htmlspecialchars($val) ?></div>
                </div>
            <?php endforeach; ?>

            <div class="info-item">
                <label>Join Date</label>
                <div class="data-value"><i class="fas fa-calendar-check"></i> <?= date('F d, Y', strtotime($profile_data['created_at'])) ?></div>
            </div>

            <div class="info-item">
                <label>Profile Status</label>
                <div class="data-value" style="color: #059669;"><i class="fas fa-check-circle"></i> Verified Account</div>
            </div>
        </div>
        
        <div class="profile-actions">
            <a href="home.php" class="back-btn">
                <i class="fas fa-chevron-left"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>