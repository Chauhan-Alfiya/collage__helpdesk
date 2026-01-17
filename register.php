

<?php
session_start();
include 'includes/db.php';
include 'includes/header.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } 
    else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Username already taken.";

        } else {

            //last role id
            $roleStmt = $pdo->query("SELECT MAX(role_id) AS last_id FROM roles");
            $lastRole = $roleStmt->fetch();
            $nextRoleId = $lastRole['last_id'] + 1;

            // Insert new role
            $roleName = strtoupper($username) . " "; 
            $stmtRole = $pdo->prepare("INSERT INTO roles (role_id, role_name) VALUES (?, ?)");
            $stmtRole->execute([$nextRoleId, $roleName]);


            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username,$email, $hashed_password, $nextRoleId]); // Use the new role_id
            //header("Location: login.php");
            exit;
        }
    }
}
//<?php if(isset($error)) echo "<div class='alert error'><i class='fa-solid fa-circle-exclamation'></i> $error</div>"; ?>

<div style="min-height: 110vh; display: flex; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 400px; padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: rem;">
            <i class="fa-solid fa-user-plus" style="font-size: 3rem; color: var(--primary);"></i>
            <h2 style="border: none; margin-top: 1rem;">Register</h2>
            <p>Create a new account</p>
        </div>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="e.g. mca_cord" required>   
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="e.g. user@example.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 10px;">
                Register <i class="fa-solid fa-arrow-right" style="margin-left: 10px;"></i>
            </button>
            <div style="text-align: center; margin-top: 20px;">
        <p>Already have an account? <a href="login.php">Log in</a></p>
</div>
        </form>
    </div>
</div>



