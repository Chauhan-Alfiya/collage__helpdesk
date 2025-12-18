<?php
session_start();
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Helpdesk</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="brand">
        <i class="fa-solid fa-graduation-cap"></i> College Helpdesk
    </div>
    <div>
        
         
        
        <?php
            if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] === 'STUDENT' || $_SESSION['role'] === 'FACULTY') {
                    $home_link = 'home.php';
            }
        }
?>
        <a ><i class="fa-solid fa-house"></i> Home</a>     
        <?php if(isset($_SESSION['user_id'])): ?>
            <span class="user-badge"><i class="fa-solid fa-user"></i> <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        <?php else: ?>
            <a  class="btn btn-primary" style="margin-left: 15px; color: white; padding: 8px 20px;"><i class="fa-solid fa-lock"></i> Staff Login</a>
        <?php endif; ?>
    </div>
</nav>
<?php





if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT u.*, r.role_name FROM j_users u JOIN j_role r ON u.role_id = r.role_id WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role_name'];

        if ($user['role_name'] == 'STUDENT') header("Location: home.php");
        elseif ($user['role_name'] == 'FACULTY') header("Location: home.php");
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
            <h2 style="border: none; margin-top: 1rem;">Students/Faculty Portal</h2>
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
        </form>
    </div>
</div>
</body>
</html>