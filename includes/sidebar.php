
<input type="checkbox" id="openProfile" hidden>

<label for="openProfile" class="sidebar-overlay"></label>

<aside class="profile-sidebar">
    <div class="sidebar-header">
        <strong style="color: #64748b; font-size: 14px;">MY ACCOUNT</strong>
        <label for="openProfile" style="cursor: pointer; font-size: 20px;">&times;</label>
    </div>

    <div class="sidebar-user-card">
        <div class="avatar-large"><?= $initial ?></div>
        <strong style="display: block; font-size: 16px;"><?= htmlspecialchars($username) ?></strong>
        <span style="font-size: 12px; color: #2563eb; font-weight: bold;"><?= strtoupper($role) ?></span>
    </div>

    <nav class="sidebar-menu">
        <a href="profile.php"><i class="fa-solid fa-user"></i> Profile</a>
        <a href="home.php"><i class="fa-solid fa-house"></i> Dashboard</a>
        <a href="myticket.php"><i class="fa-solid fa-ticket"></i> My Tickets</a>
        <a href="messages.php"><i class="fa-solid fa-envelope"></i> Messages</a>
        
        <div style="height: 1px; background: #eee; margin: 10px 0;"></div>
        
        <a href="settings.php"><i class="fa-solid fa-gear"></i> Settings</a>
        <a href="contact.php"><i class="fa-solid fa-phone"></i> Contact Us</a>
        
        <a href="logout.php" class="logout-link">
            <i class="fa-solid fa-right-from-bracket"></i> Sign Out
        </a>
    </nav>
</aside>