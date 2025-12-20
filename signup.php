<?php
session_start();
include 'includes/db.php';
include 'includes/index_header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $stream = trim($_POST['stream']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } 
    else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM j_users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Username or Email already taken.";
        } 
        else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $role_id = 1; 
            $stmt = $pdo->prepare("INSERT INTO j_users (username, email, stream, password, role_id) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $stream, $hashed_password, $role_id])) {
                header("Location: common_login.php?registered=1");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 400px; padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: rem;">
            <i class="fa-solid fa-user-plus" style="font-size: 3rem; color: var(--primary);"></i>
            <h2 style="border: none; margin-top: 1rem;">Register</h2>
            <p>Create a new account</p>
        </div>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder=" Enter Username" required>   
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
            </div>
            <div>
                <lable>Stream</lable>
                <input type="text" name="stream" class="form-control" placeholder="e.g. MCA, BCA, etc." required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 10px;">
                Register <i class="fa-solid fa-arrow-right" style="margin-left: 10px;"></i>
            </button>
        </form>
    </div>
</div>
