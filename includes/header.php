<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$username = $_SESSION['username'] ?? null;
$user_id  = $_SESSION['user_id'] ?? null;
$role     = $_SESSION['role'] ?? 'Student';
$initial  = $username ? strtoupper($username[0]) : '?'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Helpdesk</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/style.css"> <link rel="stylesheet" href="css/sidebar.css"> </head>
<body>

<nav class="navbar" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 40px; background: #fff; border-bottom: 1px solid #eee;">
    <div class="brand" style="font-weight: bold; font-size: 1.2rem;">
        <i class="fa-solid fa-graduation-cap"></i> College Helpdesk
    </div>

    <div class="nav-right">
        <a href="home.php"><i class="fa-solid fa-house"></i> Home</a>

        <?php if ($user_id): ?>
            <label for="openProfile" style="cursor: pointer; display: flex; align-items: center;">
                <div class="avatar" style="width:38px; height:38px; background:#2563eb; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold;">
                    <?= $initial ?>
                </div>
            </label>
            
            <?php include 'includes/sidebar.php'; ?>

        <?php else: ?>
            <a href="login.php" class="btn-login" style="background: #2563eb; color: #fff; padding: 8px 18px; border-radius: 8px; text-decoration: none;">Login</a>
            <a href="logout.php" class="btn btn-primary" style="margin-left: 15px; color: white; padding: 8px 20px;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        <?php endif; ?>
    </div>
</nav>