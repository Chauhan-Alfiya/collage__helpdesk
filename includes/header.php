<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'] ?? null;
$user_id  = $_SESSION['user_id'] ?? null;

$initial  = $username ? strtoupper($username[0]) : '';
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
    <div class="nav-right">
        <a href="home.php"><i class="fa-solid fa-house"></i> Home</a>

    <?php if ($user_id):?>

        <input type="checkbox" id="openProfile" hidden>
        <label for="openProfile" class="profile-btn">
            <span class="avatar"><?= $initial ?></span>
        </label>
        <aside class="profile-sidebar">
         <label for="openProfile" class="close-btn">✖</label>
            <div class="user">
                <div class="avatar"><?= $initial ?></div>
                <strong><?= htmlspecialchars($username) ?></strong>
                
            </div>
            <div class="divider"></div>
            <nav class="menu">
                <a href="profile.php"><i class="fa-solid fa-user"></i> Profile</a>
                <a href="home.php"><i class="fa-solid fa-house"></i> Dashboard</a>
                <a href="create_ticket.php"><i class="fa-solid fa-ticket"></i> My Ticket</a>
                <a href="view_ticket.php"><i class="fa-solid fa-check"></i> Check Ticket</a>
                <a href="messages.php"><i class="fa-solid fa-envelope"></i> Messages</a>
            </nav>
            <div class="divider"></div>
            <nav class="menu secondary">
                <a href="settings.php"><i class="fa-solid fa-gear"></i> Settings</a>
                <a href="about.php" ><i class="fa-solid fa-info-circle"></i> About</a>
            <a href="services.php" ><i class="fa-solid fa-cogs"></i> Services</a> 
            <a href="contact.php" ><i class="fa-solid fa-phone"></i> Contact</a>  
            </nav>
             <div class="divider"></div>
            <nav class="menu secondary">
            <a href="logout.php" class="logout">
                    <i class="fa-solid fa-right-from-bracket"></i> Sign Out</a>
            </nav>
        </aside>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary" style="margin-left: 15px; color: white; padding: 8px 20px;"><i class="fa-solid fa-lock"></i> Staff Login</a>
            <a href="logout.php" class="btn btn-primary" style="margin-left: 15px; color: white; padding: 8px 20px;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        <?php endif; ?>
    </div>
</nav>

<!-- ===========css========== -->
    <style>
body {
    font-family: 'Inter', sans-serif;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 24px;
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
}

.brand {
    font-weight: 700;
    font-size: 18px;
}

.nav-right {
    display: flex;
    align-items: center;
    gap: 15px;
}

.profile-btn {
    cursor: pointer;
}

.avatar {
    width: 40px;
    height: 40px;
    background: #2563eb;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.profile-sidebar {
    position: fixed;
    top: 0;
    right: -320px;
    width: 280px;
    height: 100vh;
    background: #ffffff;
    box-shadow: -4px 0 20px rgba(0,0,0,0.15);
    padding: 20px;
    transition: right 0.3s ease;
    display: flex;
    flex-direction: column;
    z-index: 999;
}

#openProfile:checked ~ .profile-sidebar {
    right: 0;
}

.close-btn {
    font-size: 18px;
    text-align: right;
    cursor: pointer;
}

.avatar.large {
    width: 60px;
    height: 60px;
    font-size: 20px;
}

.user {
    text-align: center;
    margin: 20px 0;
}

.role {
    font-size: 13px;
    color: #c4c5c8;
}

.divider {
    height: 1px;
    background: #e5e7eb;
    margin: 15px 0;
}

.menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    border-radius: 6px;
    text-decoration: none;
    color: #111827;
}

.menu a:hover {
    background: #f3f4f6;
}

.logout {
    margin-top: auto;
    padding: 10px;
    border-radius: 6px;
    color: #dc2626;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
}

.logout:hover {
    background: #fee2e2;
}

.btn-primary {
    background: #2563eb;
    color: white;
    padding: 8px 18px;
    border-radius: 6px;
    text-decoration: none;
}
</style>


