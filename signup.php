<?php
session_start();
    include 'includes/db.php';
    include 'includes/index_header.php';
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $stream = $_POST['stream'];
        $role = $_POST['role'];
        $semester = $_POST['semester'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } 
            // // Check if username or email already exists
             $stmt = $pdo->prepare("SELECT * FROM student WHERE username = ? OR email = ?");
             $stmt->execute([$username, $email]);
             if ($stmt->rowCount() > 0) {
                $error = "Username or Email already exists.";
            
        }
        else {
    
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare(
                "INSERT INTO student (username, email, stream, role, semester, password)
                 VALUES (:username, :email, :stream, :role, :semester, :password)"
            );
            $stmt->execute([
                ':username' => $username,
                ':email'    => $email,
                ':stream'   => $stream,
                ':role'     => $role,
                ':semester' => $semester,
                ':password' => $hashed_password
            ]);
    
            header("Location: common_login.php");
            exit();
        }
    }
?>


<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 400px; padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: rem;">
            <i class="fa-solid fa-user-plus" style="font-size: 3rem; color: var(--primary);"></i>
            <h2 style="border: none; margin-top: 1rem;">Register</h2>
            <p>Create a new account</p>
        </div>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder=" Enter Username" required>   
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
            </div>
            <div class="form-group">
                <label>Stream</label>
                <input type="text" name="stream" class="form-control" placeholder="e.g. MCA, BCA, etc." required>
            </div>
            <div class="form-group">
                <label>Semester</label>
                <input type="number" name="semester" class="form-control" placeholder="e.g. 1, 2, 3, etc." required>
            </div>
            <div class="frorm-grop">
                <lable>Role</lable>
                <select name="role" class="form-control" required>
                    <option value="STUDENT">Student</option>
                    <option value="FACULTY">Faculty</option>
                </select>

            <div>
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
        </form>
    </div>
</div>
