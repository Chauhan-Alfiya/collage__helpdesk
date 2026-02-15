<?php
session_start();
include 'includes/db.php';
include 'includes/header.php'; 

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("
        SELECT u.user_id, u.username, u.email, u.password, u.is_active, u.is_deleted, r.role AS role_name, u.department
        FROM users u
        JOIN roles r ON u.role_id = r.role_id
        WHERE u.username = ?
    ");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['is_deleted']) {
            $error = "No such user exists.";
        } elseif (!$user['is_active']) {
            $error = "Your account is deactivated.";
        } elseif (password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['user_id']  = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = strtoupper($user['role_name']); // ADMIN, CORD, STAFF, STUDENT, FACULTY
            $_SESSION['email']    = $user['email']; // student/faculty tickets
            $_SESSION['initial']  = strtoupper(substr($user['username'], 0, 1));

            // 🔹 Add stream for staff/cord
            if (in_array($_SESSION['role'], ['STAFF','CORD'])) {
                $_SESSION['stream'] = $user['department']; // department acts as stream
            }

            // Redirect all to home.php
            header("Location: home.php"); 
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<div class="navbar">
    <a href="index.php"><i class="fa-solid fa-arrow-left"></i></a>
</div>

<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 400px; padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <i class="fa-solid fa-user-shield" style="font-size: 3rem; color: var(--primary);"></i>
            <h2 style="border: none; margin-top: 1rem;">Login</h2>
            <p>Please sign in to access your dashboard</p>
        </div>

        <?php if(isset($error) && $error !== ""): ?>
            <div class='alert alert-danger'><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
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
