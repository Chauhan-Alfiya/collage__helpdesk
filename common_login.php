<?php
session_start();
include 'includes/db.php';
include 'includes/index_header.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $user = null;
    $table_found = '';

    $stmt = $pdo->prepare("
        SELECT u.*, r.role_name
        FROM users u
        JOIN roles r ON u.role_id = r.role_id
        WHERE u.username = ?
    ");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $table_found = 'users';
    }

    if (!$user) {
        $stmt = $pdo->prepare("SELECT * FROM student WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $table_found = 'student';
        }
    }

    if (!$user) {
        $stmt = $pdo->prepare("SELECT * FROM faculty WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $table_found = 'faculty';
        }
    }

    if ($user) {

        if (isset($user['is_deleted']) && $user['is_deleted'] == 1) {
            $error = "No such user exists.";
        }
        elseif (isset($user['is_active']) && $user['is_active'] == 0) {
            $error = "Your account is deactivated.";
        }
        elseif (password_verify($password, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['user_id']  = $user['user_id'] ?? $user['id'];
            $_SESSION['username'] = $user['username'];

            if ($table_found === 'users') {
                $_SESSION['role'] = $user['role_name']; 
            } elseif ($table_found === 'student') {
                $_SESSION['role'] = 'STUDENT';
            } elseif ($table_found === 'faculty') {
                $_SESSION['role'] = 'FACULTY';
            }

            $role = $_SESSION['role'];

            if ($role === 'ADMIN') {
                header("Location: admin_dashboard.php");
            }
            elseif (stripos($role, '_CORD') !== false) {
                header("Location: cord_dashboard.php");
            }
            elseif (stripos($role, '_STAFF') !== false) {
                header("Location: staff_dashboard.php");
            }
            elseif ($role === 'STUDENT' || $role === 'FACULTY') {
                header("Location: home.php");
            }
            else {
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

        <?php if(isset($error)) echo "<div class='alert alert-danger'>  $error</div>"; ?>
        
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
            <a href="forgot_password.php">Forgot Password?</a>
            </p>
            <p style="text-align: center; margin-top: 10px;">Create New User --> <a href="signup.php" >Sign Up</a></p>
        </form>
    </div>
</div>
</body>
</html>
