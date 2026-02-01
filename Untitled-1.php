<?php
include 'includes/db.php'; // Using your PDO $pdo variable
session_start();

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

// --- 2. FETCH CURRENT USER DATA ---
try {
    $stmt = $pdo->prepare("SELECT username, email FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_msg = "Error fetching data: " . $e->getMessage();
}

// --- 3. HANDLE PROFILE UPDATE ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];

    try {
        $update = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
        $update->execute([$new_username, $new_email, $user_id]);
        
        $success_msg = "Profile updated successfully!";
        // Refresh local data
        $user['username'] = $new_username;
        $user['email'] = $new_email;
    } catch (PDOException $e) {
        $error_msg = "Update failed: " . $e->getMessage();
    }
}

// --- 4. HANDLE PASSWORD UPDATE ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $current_pass = $_POST['current_password'];
    $new_pass     = $_POST['new_password'];
    $conf_pass    = $_POST['confirm_password'];

    try {
        // Get current hashed password
        $stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $db_pass = $stmt->fetchColumn();

        if (password_verify($current_pass, $db_pass)) {
            if ($new_pass === $conf_pass) {
                $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
                $upd = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                $upd->execute([$hashed_pass, $user_id]);
                $success_msg = "Password changed successfully!";
            } else {
                $error_msg = "New passwords do not match.";
            }
        } else {
            $error_msg = "Current password is incorrect.";
        }
    } catch (PDOException $e) {
        $error_msg = "Password update failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .nav-pills .nav-link.active { background-color: #0d6efd; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row">
        <div class="col-md-3 mb-4">
            <h4 class="fw-bold mb-4">Settings</h4>
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                <button class="nav-link active text-start mb-2" data-bs-toggle="pill" data-bs-target="#v-profile"><i class="bi bi-person me-2"></i> Profile</button>
                <button class="nav-link text-start mb-2" data-bs-toggle="pill" data-bs-target="#v-security"><i class="bi bi-shield-lock me-2"></i> Security</button>
            </div>
        </div>

        <div class="col-md-9">
            <?php if ($success_msg): ?>
                <div class="alert alert-success alert-dismissible fade show"><?= $success_msg ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; ?>
            <?php if ($error_msg): ?>
                <div class="alert alert-danger alert-dismissible fade show"><?= $error_msg ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; ?>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="v-profile">
                    <div class="card p-4">
                        <h5 class="mb-4">Profile Information</h5>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>

                <div class="tab-pane fade" id="v-security">
                    <div class="card p-4">
                        <h5 class="mb-4">Update Password</h5>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" name="update_password" class="btn btn-danger">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>