<?php
include 'includes/db.php';
include 'includes/functions.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: common_login.php");
    exit();
}

$username = $_SESSION['username'];
$msg = ""; 

$table = ""; $id_col = ""; $uid = 0;

$stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ?");
$stmt->execute([$username]);
$u = $stmt->fetch();
if ($u) { $table = "users"; $id_col = "user_id"; $uid = $u['user_id']; }
else {
    $stmt = $pdo->prepare("SELECT id FROM student WHERE username = ?");
    $stmt->execute([$username]);
    $u = $stmt->fetch();
    if ($u) { $table = "student"; $id_col = "id"; $uid = $u['id']; }
    else {
        $stmt = $pdo->prepare("SELECT id FROM faculty WHERE username = ?");
        $stmt->execute([$username]);
        $u = $stmt->fetch();
        if ($u) { 
            $table = "faculty"; $id_col = "id"; $uid = $u['id']; 
            }
    }
}

if (isset($_POST['change_username'])) {
    $new_user = trim($_POST['new_username']);
    $stmt = $pdo->prepare("UPDATE $table SET username = ? WHERE $id_col = ?");
    $stmt->execute([$new_user, $uid]);
    $_SESSION['username'] = $new_user;
    $msg = "<div class='alert success'>Username updated successfully!</div>";
}

if (isset($_POST['change_password'])) {
    $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE $table SET password = ? WHERE $id_col = ?");
    $stmt->execute([$new_pass, $uid]);
    $msg = "<div class='alert success'>Password changed successfully!</div>";
}

if (isset($_POST['delete_account'])) {
    $pdo->prepare("DELETE FROM $table WHERE $id_col = ?")->execute([$uid]);
    session_destroy();
    header("Location: common_login.php?status=deleted");
    exit();
}

include 'includes/header.php';
?>

<link rel="stylesheet" href="css/settings.css">
<link rel="stylesheet" href="css/profile.css"> <div class="settings-container">
    <h1 style="margin-bottom: 30px; font-weight: 800; color: #0f172a;">Account Settings</h1>
    
    <?= $msg ?>

    <div class="settings-card">
        <div class="settings-header">
            <h3><i class="fas fa-user-edit"></i> Edit Username</h3>
        </div>
        <div class="settings-body">
            <form method="POST">
                <div class="settings-form-group">
                    <label>Current Username</label>
                    <input type="text" name="new_username" class="settings-input" value="<?= htmlspecialchars($_SESSION['username']) ?>" required>
                </div>
                <button type="submit" name="change_username" class="btn-update">Update Name</button>
            </form>
        </div>
    </div>

    <div class="settings-card">
        <div class="settings-header">
            <h3><i class="fas fa-lock"></i> Security & Password</h3>
        </div>
        <div class="settings-body">
            <form method="POST">
                <div class="settings-form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="settings-input" placeholder="••••••••" required>
                    <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">Kam se kam 8 characters use karein.</small>
                </div>
                <button type="submit" name="change_password" class="btn-update">Change Password</button>
            </form>
        </div>
    </div>

    <div class="settings-card danger-zone">
        <div class="settings-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Danger Zone</h3>
        </div>
        <div class="settings-body">
            <p style="color: #991b1b; font-size: 14px; margin-top: 0;">Account delete karne se aapka sara data (Tickets, Comments, Profile) permanently khatam ho jayega.</p>
            <form method="POST" onsubmit="return confirm('SURE? Aapka data wapas nahi aayega!');">
                <button type="submit" name="delete_account" class="btn-delete">Delete My Account</button>
            </form>
        </div>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <a href="profile.php" style="text-decoration: none; color: #64748b; font-weight: 600;">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>