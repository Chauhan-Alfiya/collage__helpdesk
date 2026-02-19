<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';
include 'includes/send_mail.php';

$error = '';
$role = $_POST['role'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username   = trim($_POST['username'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';
    $confirm    = $_POST['confirm_password'] ?? '';
    $role       = $_POST['role'] ?? '';
    $department = $_POST['department'] ?? '';
    $stream     = $_POST['stream'] ?? '';
    $semester   = $_POST['semester'] ?? '';

    if (empty($role)) {
        $error = "Please select a role first.";
    } elseif (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        $check = $pdo->prepare("SELECT user_id FROM users WHERE email = ? OR username = ?");
        $check->execute([$email, $username]);
        if ($check->rowCount() > 0) {
            $error = "Username or Email already exists.";
        } else {
            $roleStmt = $pdo->prepare("SELECT role_id FROM roles WHERE role = ?");
            $roleStmt->execute([$role]);
            $roleData = $roleStmt->fetch(PDO::FETCH_ASSOC);

            if (!$roleData) {
                $error = "Invalid role selected.";
            } else {
                $role_id = $roleData['role_id'];
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                $dept_value = $role === 'STUDENT' ? $stream : ($department ?? 'N/A');

                $stmt = $pdo->prepare("
                    INSERT INTO users (username, email, password, role_id, role, department)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$username, $email, $hashed_password, $role_id, $role, $dept_value]);

                if ($role === 'STUDENT') {
                    $user_id = $pdo->lastInsertId();
                    $stmt2 = $pdo->prepare("INSERT INTO student_details (user_id, semester) VALUES (?, ?)");
                    $stmt2->execute([$user_id, $semester]);
                }

                sendRegisterSuccessMail($email, $username);
                header("Location: home.php");
                exit();
            }
        }
    }
}
?>
<div style="font-weight:bold; font-size:1.5rem; color:var(--primary); align-items:flex-end; gap:0.5rem; transform:translate(50px,6px);">
    <i class="fa-solid fa-graduation-cap" ></i> College Helpdesk
</div> 

<div style="min-height: 140vh; display: flex; align-items: center; justify-content: center;">
    <div class="card" style="width: 120%; max-width: 440px; padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 1rem;">
            <i class="fa-solid fa-user-plus" style="font-size: 3rem; color: var(--primary);"></i>
            <h2 style="margin-top: 1rem;">Register</h2>
            <p>Create a new account</p>
        </div>

        <?php if ($error): ?>
            <div style="color:red; text-align:center; margin-bottom:1rem;"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Select Role</label>
                <select name="role" class="form-control" required onchange="this.form.submit()">
                    <option value="">-- Select Role --</option>
                    <option value="STUDENT" <?= $role === 'STUDENT' ? 'selected' : '' ?>>Student</option>
                    <option value="FACULTY" <?= $role === 'FACULTY' ? 'selected' : '' ?>>Faculty</option>
                    <option value="STAFF" <?= $role === 'STAFF' ? 'selected' : '' ?>>Staff</option>
                    <option value="CORD" <?= $role === 'CORD' ? 'selected' : '' ?>>CORD</option>
                </select>
            </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter Username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter Email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Department</label>
                        <select name="department" class="form-control" required>
                            <option value="">Select Department</option>
                            <option value="MCA" <?= ($_POST['department'] ?? '') === 'MCA' ? 'selected' : '' ?>>MCA</option>
                            <option value="BCA" <?= ($_POST['department'] ?? '') === 'BCA' ? 'selected' : '' ?>>BCA</option>
                            <option value="BBA" <?= ($_POST['department'] ?? '') === 'BBA' ? 'selected' : '' ?>>BBA</option>
                            <option value="MBA" <?= ($_POST['department'] ?? '') === 'MBA' ? 'selected' : '' ?>>MBA</option>
                            <option value="BCOM" <?= ($_POST['department'] ?? '') === 'BCOM' ? 'selected' : '' ?>>BCOM</option>
                            <option value="MCOM" <?= ($_POST['department'] ?? '') === 'MCOM' ? 'selected' : '' ?>>MCOM</option>
                            <option value="BSC" <?= ($_POST['department'] ?? '') === 'BSC' ? 'selected' : '' ?>>BSC</option> <option value="MSC" <?= ($_POST['department'] ?? '') === 'MSC' ? 'selected' : '' ?>>MSC</option>
                            <option value="BA" <?= ($_POST['department'] ?? '') === 'BA' ? 'selected' : '' ?>>BA</option> 
                            </select>
        </div>
                <?php if ($role === 'STUDENT'): ?>
                    <div class="form-group">
                        <label>Stream</label>
                        <select name="stream" class="form-control" required>
                            <option value="MCA" <?= ($_POST['stream'] ?? '') === 'MCA' ? 'selected' : '' ?>>MCA</option>
                            <option value="BCA" <?= ($_POST['stream'] ?? '') === 'BCA' ? 'selected' : '' ?>>BCA</option>
                            <option value="BBA" <?= ($_POST['stream'] ?? '') === 'BBA' ? 'selected' : '' ?>>BBA</option>
                            <option value="MBA" <?= ($_POST['stream'] ?? '') === 'MBA' ? 'selected' : '' ?>>MBA</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <input type="number" name="semester" class="form-control" placeholder="Enter Semester" min="1" max="8" required value="<?= htmlspecialchars($_POST['semester'] ?? '') ?>">
                    </div>
                <?php elseif ($role === 'FACULTY'): ?>
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department" class="form-control" required>
                            <option value="">Select Department</option>
                            <option value="MCA" <?= ($_POST['department'] ?? '') === 'MCA' ? 'selected' : '' ?>>MCA</option>
                            <option value="BCA" <?= ($_POST['department'] ?? '') === 'BCA' ? 'selected' : '' ?>>BCA</option>
                            <option value="BBA" <?= ($_POST['department'] ?? '') === 'BBA' ? 'selected' : '' ?>>BBA</option>
                            <option value="MBA" <?= ($_POST['department'] ?? '') === 'MBA' ? 'selected' : '' ?>>MBA</option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" name="register" class="btn btn-primary" style="width:100%; margin-top:10px;">Register</button>

            <div style="text-align:center; margin-top:20px;">
                <p>Already have an account? <a href="common_login.php">Log in</a></p>
            </div>
        </form>
    </div>
</div>
