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
            <a href="logout.php" class="btn btn-primary" style="margin-left: 15px; color: white; padding: 8px 20px;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        <?php endif; ?>
    </div>
</nav>