<?php
if (!isset($_SESSION)) session_start();
$username = $_SESSION['username'] ?? 'User';
?>

<div class="profile-dropdown">

    <!-- Hidden Checkbox -->
    <input type="checkbox" id="profileToggle" hidden>

    <!-- Profile Icon -->
    <label for="profileToggle" class="profile-btn">
        <span class="avatar"><?= strtoupper($username[0]) ?></span>
    </label>

    <!-- Dropdown Menu -->
    <div class="dropdown-menu">
        <div class="menu-header">
            <span class="avatar"><?= strtoupper($username[0]) ?></span><br>
            <strong><?= htmlspecialchars($username) ?></strong>
        </div>

        <div class="divider"></div>

        <a href="profile.php" class="menu-item">👤 Profile</a>
        <a href="home.php" class="menu-item">🏠 Dashboard</a>
        <a href="messages.php" class="menu-item">📧 Messages</a>

        <div class="divider"></div>

        <a href="settings.php" class="menu-item">⚙ Settings</a>
        <a href="logout.php" class="menu-item logout">🚪 Sign out</a>
    </div>

</div>
<style>
    .profile-dropdown {
    position: relative;
}

/* Button */
.profile-btn {
    cursor: pointer;
    display: inline-flex;
}

.avatar {
    width: 32px;
    height: 32px;
    background: #2563eb;
    color: #fff;
    border-radius: 50%;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Dropdown */
.dropdown-menu {
    position: absolute;
    right: 0;
    top: 45px;
    width: 240px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    display: none;
    z-index: 1000;
}

/* 🔥 Checkbox magic */
#profileToggle:checked ~ .dropdown-menu {
    display: block;
}

.menu-header {
    padding: 12px 16px;
    font-size: 0.85rem;
}

.menu-item {
    display: block;
    padding: 10px 16px;
    text-decoration: none;
    color: #0f172a;
}

.menu-item:hover {
    background: #f1f5f9;
}

.divider {
    height: 1px;
    background: #e2e8f0;
    margin: 6px 0;
}

.menu-item.logout {
    color: #dc2626;
}
</style>

<!-- <?php
if (!isset($_SESSION)) session_start();
$username = $_SESSION['username'] ?? 'User';
?>

<div class="profile-dropdown">

    

    <div class="dropdown-menu">
        <button class="profile-btn">
        <span class="avatar" ><?= strtoupper($username[0]) ?></span>
        
    </button>
        <div class="menu-header">
            <span class="avatar"><?= strtoupper($username[0]) ?></span><br>
            <strong><?= htmlspecialchars($username) ?></strong>
        </div>

        

        <div class="divider"></div>

        <a href="profile.php" class="menu-item">👤 Profile</a>
        <a href="home.php" class="menu-item">🏠 Dashboard</a>
        <a href="messages.php" class="menu-item">📧 Messages</a>

        
        <div class="divider"></div>

        <a href="settings.php" class="menu-item">⚙ Settings</a>
        
        <a href="logout.php" class="menu-item logout">🚪 Sign out</a>
    </div>

</div>
<style>
    .profile-dropdown {
    position: relative;
}

/* Button */
.profile-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
}

.avatar {
    width: 32px;
    height: 32px;
    background: #2563eb;
    color: #fff;
    border-radius: 50%;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}

.caret {
    font-size: 12px;
    color: #64748b;
}

/* Dropdown menu */
.dropdown-menu {
    position: absolute;
    right: 0;
    top: 45px;
    width: 240px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    display: none;
    z-index: 1000;
    overflow: hidden;
}

/* 🔥 CSS MAGIC */
.profile-dropdown:focus-within .dropdown-menu {
    display: block;
}

.menu-header {
    padding: 12px 16px;
    font-size: 0.85rem;
    color: #475569;
}

.menu-header strong {
    color: #0f172a;
}

.menu-item {
    display: block;
    padding: 10px 16px;
    text-decoration: none;
    font-size: 0.9rem;
    color: #0f172a;
}

.menu-item:hover {
    background: #f1f5f9;
}

.divider {
    height: 1px;
    background: #e2e8f0;
    margin: 6px 0;
}

.menu-item.logout {
    color: #dc2626;
}
</style> -->


