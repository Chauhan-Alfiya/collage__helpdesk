<?php
session_start();
include 'includes/db.php';
include 'includes/send_mail.php';

$error = '';
$step = $_POST['step'] ?? 1; // Step 1 = select role, Step 2 = fill form
$role = $_POST['role'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_form'])) {
    // Step 2: Handle registration
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $role     = $_POST['role'] ?? '';
    $stream   = $_POST['stream'] ?? '';
    $semester = $_POST['semester'] ?? '';
    $department = $_POST['department'] ?? '';

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm) || empty($role)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Check if email or username exists
        $check = $pdo->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);
        if ($check->rowCount() > 0) {
            $error = "Username or Email already exists.";
        } else {
            // Get role_id
            $roleStmt = $pdo->prepare("SELECT role_id FROM roles WHERE role = ?");
            $roleStmt->execute([$role]);
            $roleData = $roleStmt->fetch(PDO::FETCH_ASSOC);

            if (!$roleData) {
                $error = "Invalid role selected.";
            } else {
                $role_id = $roleData['role_id'];
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Insert user
                $stmt = $pdo->prepare("
                    INSERT INTO users (username, email, password, role_id, role, department)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");

                // Determine department or stream value
                $dept_value = $department ?: ($stream ?: 'N/A');

                $stmt->execute([
                    $username,
                    $email,
                    $hashed_password,
                    $role_id,
                    $role,
                    $dept_value
                ]);

                // If student, add to student_details
                if ($role === 'STUDENT') {
                    $user_id = $pdo->lastInsertId();
                    $stmt2 = $pdo->prepare("INSERT INTO student_details (user_id, semester) VALUES (?, ?)");
                    $stmt2->execute([$user_id, $semester]);
                }

                sendRegisterSuccessMail($email, $username);
                header("Location: common_login.php");
                exit();
            }
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['select_role'])) {
    // Step 1: Role selected, go to step 2
    if (empty($role)) {
        $error = "Please select a role first.";
        $step = 1;
    } else {
        $step = 2;
    }
}
?>

<div style="display:flex; justify-content:center; align-items:center; min-height:100vh;">
    <div style="width:400px; padding:2rem; border:1px solid #ccc; border-radius:5px;">
        <h2 style="text-align:center;">Register</h2>
        <?php if($error) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>

        <?php if($step == 1): ?>
        <!-- Step 1: Select Role -->
        <form method="POST">
            <select name="role" style="width:100%; padding:0.5rem;" required>
                <option value="">-- Select Role --</option>
                <option value="STUDENT">Student</option>
                <option value="FACULTY">Faculty</option>
                <option value="STAFF">Staff</option>
                <option value="CORD">CORD</option>
                <option value="ADMIN">Admin</option>
            </select>
            <button type="submit" name="select_role" style="width:100%; padding:0.5rem; margin-top:1rem;">Next</button>
        </form>

        <?php elseif($step == 2): ?>
        <!-- Step 2: Role-specific form -->
        <form method="POST">
            <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">
            
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <?php if($role === 'STUDENT'): ?>
            <div class="form-group">
                <label>Stream</label>
                <select name="stream" class="form-control" required>
                    <option value="MCA">MCA</option>
                    <option value="BCA">BCA</option>
                    <option value="BBA">BBA</option>
                    <option value="MBA">MBA</option>
                </select>
            </div>
            <div class="form-group">
                <label>Semester</label>
                <input type="number" name="semester" class="form-control" min="1" max="8" required>
            </div>
            <?php elseif($role === 'FACULTY'): ?>
            <div class="form-group">
                <label>Department</label>
                <select name="department" class="form-control" required>
                    <option value="MCA">MCA</option>
                    <option value="BCA">BCA</option>
                    <option value="BBA">BBA</option>
                    <option value="MBA">MBA</option>
                </select>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>

            <button type="submit" name="submit_form" style="width:100%; padding:0.5rem; margin-top:1rem;">Register</button>
        </form>
        <?php endif; ?>
    </div>
</div>
