<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: common_login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$msg = "";

$table = $role === 'STUDENT' ? 'student_details' : ($role === 'FACULTY' ? 'faculty_details' : 'users');


if (isset($_POST['update_profile'])) {

    $new_username = trim($_POST['new_username']);

    
    $email = $_POST['email'];

    $check = $pdo->prepare("SELECT username FROM $table WHERE username = ?");
    $check->execute([$new_username]);

    if ($check->rowCount() > 0 && $new_username !== $username) {
        $msg = "<div class='alert alert-danger'>Username already exists!</div>";
    } else {
        $stmt = $pdo->prepare("
            UPDATE $table 
            SET username = ?, email = ?
            WHERE username = ?
        ");

        if ($stmt->execute([$new_username, $email, $username])) {
            $_SESSION['username'] = $new_username;
            $username = $new_username;

            $msg = "<div class='alert alert-success'>Profile updated successfully!</div>";
        }
    }
}


if (isset($_POST['change_password'])) {

    $old_pass = $_POST['old_password'];
    $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $check = $pdo->prepare("SELECT password FROM $table WHERE username = ?");
    $check->execute([$username]);
    $user = $check->fetch();

if ($user && password_verify($old_pass, $user['password'])) {

    $new_pass_hash = password_hash($new_pass, PASSWORD_DEFAULT);

    $update = $pdo->prepare("
        UPDATE $table 
        SET password = ?, last_password_change = NOW()
        WHERE username = ?
    ");
    $update->execute([$new_pass_hash, $username]);

    $msg = "<div class='alert alert-success'>Password changed successfully!</div>";
} else {
    $msg = "<div class='alert alert-danger'>Current password is incorrect!</div>";
}

}


if (isset($_POST['delete_account'])) {

    $pdo->prepare("
        UPDATE users 
        SET is_deleted = 1,
            is_active = 0,
            deleted_at = NOW()
        WHERE username = ?
    ")->execute([$username]);

    session_destroy();
    echo "<script>alert('Your account has been deleted.'); window.location='login.php';</script>";
    exit();
}


$stmt = $pdo->prepare("SELECT * FROM $table WHERE username = ?");
$stmt->execute([$username]);
$data = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .nav-pills .nav-link.active { background-color: #0d6efd; }
    </style>
</head>
<body>

<div class="container py-5">
    <h2 class="mb-4 text-primary">
        <i class="bi bi-gear-fill"></i> Account Settings
    </h2>

    <?= $msg ?>

    <div class="row">
        <div class="col-md-3">
            <div class="nav flex-column nav-pills shadow-sm p-3 bg-white rounded">
                <button class="nav-link active text-start" data-bs-toggle="pill" data-bs-target="#profile-tab">
                    <i class="bi bi-person me-2"></i> Profile
                </button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#security-tab">
                    <i class="bi bi-lock me-2"></i> Password
                </button>
                <button class="nav-link text-start text-danger" data-bs-toggle="pill" data-bs-target="#danger-tab">
                    <i class="bi bi-trash me-2"></i> Delete Account
                </button>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content shadow-sm p-4 bg-white rounded">

                <!-- PROFILE -->
                <div class="tab-pane fade show active" id="profile-tab">
                    <h4>Edit Profile</h4>
                    <hr>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="new_username" class="form-control"
                                   value="<?= htmlspecialchars($data['username']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= htmlspecialchars($data['email']) ?>" readonly>
                        </div>

                        <?php if ($role === 'STUDENT'): ?>
                            <div class="mb-3">
                                <label class="form-label">Stream</label>
                                <input type="text" class="form-control bg-light"
                                       value="<?= htmlspecialchars($data['stream']) ?>" readonly>
                            </div>
                        <?php elseif ($role === 'FACULTY'): ?>
                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control bg-light"
                                       value="<?= htmlspecialchars($data['department']) ?>" readonly>
                            </div>
                        <?php endif; ?>

                        <button type="submit" name="update_profile" class="btn btn-primary">
                            Save Changes
                        </button>
                    </form>
                </div>

                <!-- PASSWORD -->
                <div class="tab-pane fade" id="security-tab">
                    <h4>Security Settings</h4>
                    <hr>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <button type="submit" name="change_password" class="btn btn-dark">
                            Update Password
                        </button>
                    </form>
                </div>

                <!-- DELETE -->
                <div class="tab-pane fade" id="danger-tab">
                    <div class="alert alert-warning">
                        <h5>Attention!</h5>
                        <p>Deleting your account is permanent.</p>
                        <form method="POST"
                              onsubmit="return confirm('Are you sure you want to delete your account?');">
                            <button type="submit" name="delete_account" class="btn btn-danger">
                                Delete Account
                            </button>
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
