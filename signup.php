  <?php
session_start();
include 'includes/db.php';
include 'includes/index_header.php';
include 'includes/send_mail.php';



$error = '';
$role = $_POST['role'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password']  ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';   
    $role     = $_POST['role'] ?? '';

    if (empty($username) || empty($email) || empty($password) || empty($confirm) || empty($role)) {
        $error = "All fields are required.";
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } 
    elseif ($password !== $confirm) {
            $error = "Passwords do not match.";
    }
    elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    }
    else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        if ($role === 'STUDENT') {
            $stream   = $_POST['stream'] ?? '';
            $semester = $_POST['semester'] ?? '';

            if (empty($semester) || empty($stream)) {
                $error = "Semester and stream are required.";
            } 
            else {      
            $stmt = $pdo->prepare("SELECT id FROM student WHERE email = ?");
            $stmt->execute([$email]);
            }
            if ($stmt->rowCount() > 0) {
                $error = "Student already exists.";
            } else {
                $stmt = $pdo->prepare(
                    "INSERT INTO student (username, email, stream, semester, password)
                     VALUES (?, ?, ?, ?, ?)"
                );
                $stmt->execute([$username, $email, $stream, $semester, $hashed_password]);

                sendRegisterSuccessMail($email, $username);
                header("Location: common_login.php");
                exit();
            }
        } elseif ($role === 'FACULTY') {
            $department = $_POST['department'] ?? '';
            if (empty($department)) {
                $error = "Department is required.";
            } else {
            $stmt = $pdo->prepare("SELECT id FROM faculty WHERE email = ?");
            $stmt->execute([$email]);
            }
            if ($stmt->rowCount() > 0) {
                $error = "Faculty already exists.";
            } else {
                $stmt = $pdo->prepare(
                    "INSERT INTO faculty (username, email, department, password)
                     VALUES (?, ?, ?, ?)"
                );
                $stmt->execute([$username, $email, $department, $hashed_password]);
                sendRegisterSuccessMail($email, $username);
                header("Location: common_login.php");
                exit();
            }
        } 
        else {
            $error = "Invalid role selected.";
        }
    }
}
?>
<div style="min-height: 140vh; display: flex; align-items: center; justify-content: center;">
    <div class="card" style="width: 120%; max-width: 440px; padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: rem;">
            <i class="fa-solid fa-user-plus" style="font-size: 3rem; color: var(--primary);"></i>
            <h2 style="border: none; margin-top: 1rem;">Register</h2>
            <p>Create a new account</p>
        </div>
        <?php if(isset($error)) echo "<div style='color: red; margin-top: 1rem; text-align: center;'> $error</div>"; ?>

        
        <form method="POST">
        <div class="form-group" > 

        <select name="role" class="form-control" onchange="this.form.submit()" required>
            <option value="">Select Role</option>
            <option value="STUDENT" <?= $role === 'STUDENT' ? 'selected' : '' ?>>Student</option>
            <option value="FACULTY" <?= $role === 'FACULTY' ? 'selected' : '' ?>>Faculty</option>
        </select>
        </div>
            
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder=" Enter Username" required>   
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter Email" required> 
            </div>
            <?php if($role === 'STUDENT'): ?> 
            <div class="form-group">
                <label>Stream</label>
                    <select name="stream" class="form-control" required> 
                        <option value="MCA" <?= (($_POST['stream'] ?? '') === 'MCA') ? 'selected' : '' ?>>MCA</option>
                        <option value="BCA" <?= (($_POST['stream'] ?? '') === 'BCA') ? 'selected' : '' ?>>BCA</option>
                        <option value="BBA" <?= (($_POST['stream'] ?? '') === 'BBA') ? 'selected' : '' ?>>BBA</option>
                        <option value="MBA" <?= (($_POST['stream'] ?? '') === 'MBA') ? 'selected' : '' ?>>MBA</option>
                        </select>
            </div>              
            <div class="form-group">
                <label>Semester</label>
                <input type="number" name="semester" class="form-control"  placeholder="Enter Semester" min="1" max="8" required>
            </div>
            <?php endif; ?>

            <?php if($role === 'FACULTY') : ?>
            <div class="form-group" id="faculty-field">
                <label>Department</label>
                <select name="department" class="form-control" required>
                    <option value="">Select Department</option>
                    <option value="MCA" <?= (($_POST['department'] ?? '') === 'MCA') ? 'selected' : '' ?>>MCA</option>
                    <option value="BCA" <?= (($_POST['department'] ?? '') === 'BCA') ? 'selected' : '' ?>>BCA</option>
                    <option value="BBA" <?= (($_POST['department'] ?? '') === 'BBA') ? 'selected' : '' ?>>BBA</option>
                    <option value="MBA" <?= (($_POST['department'] ?? '') === 'MBA') ? 'selected' : '' ?>>MBA</option>
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
            <button type="submit" name="register" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 10px;">
                Register <i class="fa-solid fa-arrow-right" style="margin-left: 10px;"></i>
            </button>
            <div style="text-align: center; margin-top: 20px;">
        <p>Already have an account? <a href="common_login.php">Log in</a></p>
</div>
        </form>
    </div>
</div> 

 

 


 