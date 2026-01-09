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
         $home_link = 'home.php';
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] == 'ADMIN') 
                $home_link = 'admin_dashboard.php';
            elseif (strpos($_SESSION['role'], '_CORD') !== false) 
                $home_link = 'cord_dashboard.php';
            elseif (strpos($_SESSION['role'], '_STAFF') !== false) 
                $home_link = 'staff_dashboard.php';
        }
        ?>
        
        <a href="home.php"><i class="fa-solid fa-house"></i> Home</a>


        


<?php if(isset($_SESSION['user_id'])): ?>
            <span class="user-badge" ><i class="fa-solid fa-user"></i> <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="logout.php"   ><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary" style="margin-left: 15px; color: white; padding: 8px 20px;"><i class="fa-solid fa-lock"></i> Staff Login</a>
            <a href="logout.php" class="btn btn-primary" style="margin-left: 15px; color: white; padding: 8px 20px;"><i class="fa-solid fa-lock"></i> Logout</a>
          <?php
if (!isset($_SESSION)) session_start();
$username = $_SESSION['username'] ?? 'User';
?>

<!-- TOGGLE -->
<input type="checkbox" id="openProfile" hidden>

<!-- PROFILE BUTTON (NO LINK) -->
<label for="openProfile" class="profile-btn">
    👤 Profile
</label>

<!-- SIDEBAR (SAME PAGE) -->
<div class="profile-sidebar">
    <label for="openProfile" class="close-btn">✖</label>

    <div class="user">
        <div class="avatar"><?= strtoupper($username[0]) ?></div>
        <strong><?= htmlspecialchars($username) ?></strong>
    </div>

    <a href="settings.php">⚙ Settings</a>
    <a href="messages.php">📧 Messages</a>
    <a href="logout.php" class="logout">🚪 Logout</a>
</div>
<style>
    .profile-btn {
    cursor: pointer;
    padding: 10px;
    display: inline-block;
    background: #e5e7eb;
    border-radius: 6px;
}

/* SIDEBAR */
.profile-sidebar {
    position: fixed;
    top: 0;
    right: -300px;
    width: 280px;
    height: 100vh;
    background: #fff;
    box-shadow: -4px 0 20px rgba(0,0,0,0.2);
    padding: 20px;
    transition: 0.3s ease;
}

/* OPEN SIDEBAR */
#openProfile:checked ~ .profile-sidebar {
    right: 0;
}

.close-btn {
    cursor: pointer;
    font-size: 18px;
    display: block;
    margin-bottom: 20px;
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

.user {
    margin-bottom: 20px;
}

.profile-sidebar a {
    display: block;
    margin: 10px 0;
    text-decoration: none;
}

.logout {
    color: red;
}
</style>
        <?php endif; ?>
    </div>
</nav>