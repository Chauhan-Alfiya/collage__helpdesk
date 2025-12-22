<?php
session_start();
include 'includes/db.php';
include 'includes/index_header.php';


$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $email    = $_POST['email'];
    $role     = $_POST['role'];
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        if ($role === 'STUDENT') {

            $stream   = $_POST['stream'];
            $semester = $_POST['semester'];

            // check if student exists
            $stmt = $pdo->prepare("SELECT id FROM student WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                $error = "Student already exists.";
            } else {
                $stmt = $pdo->prepare(
                    "INSERT INTO student (username, email, stream, semester, password)
                     VALUES (?, ?, ?, ?, ?)"
                );
                $stmt->execute([$username, $email, $stream, $semester, $hashed_password]);

                header("Location: common_login.php");
                exit();
            }

        } elseif ($role === 'FACULTY') {

            $department = $_POST['department'];

            // check if faculty exists
            $stmt = $pdo->prepare("SELECT id FROM faculty WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                $error = "Faculty already exists.";
            } else {
                $stmt = $pdo->prepare(
                    "INSERT INTO faculty (username, email, department, password)
                     VALUES (?, ?, ?, ?)"
                );
                $stmt->execute([$username, $email, $department, $hashed_password]);

                header("Location: common_login.php");
                exit();
            }

        } 
        elseif($role === 'CORD') {

            // check if coordinator exists
            $stmt = $pdo->prepare("SELECT id FROM coordinator WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                $error = "Coordinator already exists.";
            } else {
                $stmt = $pdo->prepare(
                    "INSERT INTO coordinator (username, email, department, password)
                     VALUES (?, ?, ?, ?)"
                );
                $stmt->execute([$username, $email,$department, $hashed_password]);

                header("Location: common_login.php");
                exit();
            }
        }
        elseif($role ==='Staff') {
            // check if staff exists
            $stmt = $pdo->prepare("SELECT id FROM staff WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                $error = "Staff already exists.";
            } else {
                $stmt = $pdo->prepare(
                    "INSERT INTO staff (username, email, department, password)
                     VALUES (?, ?, ?, ?)"
                );
                $stmt->execute([$username, $email, $hashed_password]);

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
<div style="min-height: 150vh; display: flex; align-items: center; justify-content: center;">
    <div class="card" style="width: 120%; max-width: 440px; padding: 2.5rem;">
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
                    <select name="stream" class="form-control" required>
                        <option value="MCA">MCA</option>
                        <option value="BCA">BCA</option>
                        <option value="BBA">BBA</option>
                        <option value="MBA">MBA</option>
                        </select>
            </div>
            <div class="form-group" id="student-field">
                <label>Semester</label>
                <input type="number" name="semester" class="form-control" placeholder="e.g. 1, 2, 3..." min="1" max="8">
            </div>

            <div class="form-group" id="faculty-field">
                <label>Department</label>
                <select name="department" class="form-control">
                    <option value="">Select Department</option>
                    <option value="MCA">MCA</option>
                    <option value="BCA">BCA</option>
                    <option value="BBA">BBA</option>
                    <option value="MBA">MBA</option>
                </select>
            </div>  
            <div class="frorm-grop">
                <lable>Role</lable>
                <select name="role" class="form-control" required>
                    <option value="STUDENT">Student</option>
                    <option value="FACULTY">Faculty</option>
                    <option value="CORD">Coordinator</option>
                    <option value="Staff">Staff</option>
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
            <div style="text-align: center; margin-top: 20px;">
        <p>Already have an account? <a href="common_login.php">Log in</a></p>
</div>
        </form>
    </div>
</div> 


