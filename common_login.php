<?php
session_start();
include 'includes/db.php';
include 'includes/index_header.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $tables = ['users', 'student', 'faculty'];
    $user = null;
    $table_found = '';

    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $table_found = $table;
            break;
        }
    }

    if ($user) {
        if (isset($user['is_deleted']) && $user['is_deleted'] == 1) {
            $error = "No such user exists.";
        }
        elseif (isset($user['is_active']) && $user['is_active'] == 0) {
            $error = "Your account is inactive.";
        }
        elseif (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']  = $user['user_id'] ?? $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role_name'] ?? ($table_found == 'student' ? 'STUDENT' : ($table_found == 'faculty' ? 'FACULTY' : 'USER'));

            if ($_SESSION['role'] == 'ADMIN') {
                header("Location: admin_dashboard.php");
            } elseif (strpos($_SESSION['role'], '_CORD') !== false) {
                header("Location: cord_dashboard.php");
            } elseif (strpos($_SESSION['role'], '_STAFF') !== false) {
                header("Location: staff_dashboard.php");
            } elseif ($_SESSION['role'] == 'STUDENT' || $_SESSION['role'] == 'FACULTY') {
                header("Location: home.php");
            } else {
                header("Location: home.php");
            }
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 400px; padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <i class="fa-solid fa-user-shield" style="font-size: 3rem; color: var(--primary);"></i>
            <h2 style="border: none; margin-top: 1rem;">Login</h2>
            <p>Please sign in to access your dashboard</p>
        </div>

        <?php if(isset($error)) echo "<div class='alert error'><i class='fa-solid fa-circle-exclamation'></i> $error</div>"; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
             </div>
             
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 10px;">
                Sign In <i class="fa-solid fa-arrow-right" style="margin-left: 10px;"></i>
            </button>
             <p style="text-align: center; margin-top: 10px; font-size: 14px;">
            <a href="#">Forgot Password?</a>
            </p>
            <p style="text-align: center; margin-top: 10px;">Create New User --> <a href="signup.php" >Sign Up</a></p>
        </form>
    </div>
</div>
</body>
</html>