<?php
session_start();
include 'includes/db.php';
include 'includes/header.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id = r.role_id WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(); 

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role_name'];
$_SESSION['user_id'] = $user['user_id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role_name'];

header("Location: home.php");
exit();

        
        exit;
    } 
    else {
        $error = "Invalid username or password.";
    }
}
?>

<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 400px; padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <i class="fa-solid fa-user-shield" style="font-size: 3rem; color: var(--primary);"></i>
            <h2 style="border: none; margin-top: 1rem;">Staff Portal</h2>
            <p>Please sign in to access your dashboard</p>
        </div>

        <?php if(isset($error)) echo "<div class='alert error'><i class='fa-solid fa-circle-exclamation'></i> $error</div>"; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="e.g. mca_cord" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
             </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 10px;">
                Sign In <i class="fa-solid fa-arrow-right" style="margin-left: 10px;"></i>
            </button>
            <div style="text-align: center; margin-top: 20px;">
            <a href="#">Forgot Password?</a>
            </div>
            
        </form>
    </div>
</div>
</body>
</html>

 <!-- <p class="extra">
                <a href="#">Forgot Password?</a><br>
                Don’t have an account? <a href="#">Sign up</a>
            </p>  -->