<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: common_login.php");
    exit();
}

$username = $_SESSION['username'];

$stmt = $pdo->prepare("
    SELECT u.*, r.role AS role_name
    FROM users u
    LEFT JOIN roles r ON u.role_id = r.role_id 
    WHERE u.username = ?
");
$stmt->execute([$username]);
$profile_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profile_data) {
    die("Profile not found.");
}


$role_name = $profile_data['role_name'];
$extra_fields = [];

if ($role_name === 'STUDENT') {
    $extra_fields = [
        'Department' => $profile_data['department'] ?? 'N/A',
        'Semester' => $profile_data['semester'] ?? 'N/A'
    ];
} elseif ($role_name === 'FACULTY') {
    $extra_fields = [
        'Department' => $profile_data['department'] ?? 'N/A'
    ];
}

$initial = strtoupper($username[0]);

include 'includes/header.php';
?>

<link rel="stylesheet" href="css/profile.css">

<div class="container py-5">
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
                    <div class="data-value"><i class="fas fa-fingerprint"></i> #ID-0<?= $profile_data['user_id'] ?></div>
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
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'includes/footer.php'; ?>
