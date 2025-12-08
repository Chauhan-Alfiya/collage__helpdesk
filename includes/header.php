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
        $home_link = 'index.php';
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] == 'ADMIN') $home_link = 'admin_dashboard.php';
            elseif (strpos($_SESSION['role'], '_CORD') !== false) $home_link = 'cord_dashboard.php';
            elseif (strpos($_SESSION['role'], '_STAFF') !== false) $home_link = 'staff_dashboard.php';
        }
        ?>
        
        <a href="<?= $home_link ?>"><i class="fa-solid fa-house"></i> Home</a>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <span class="user-badge"><i class="fa-solid fa-user"></i> <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary" style="margin-left: 15px; color: white; padding: 8px 20px;"><i class="fa-solid fa-lock"></i> Staff Login</a>
        <?php endif; ?>
    </div>
</nav>