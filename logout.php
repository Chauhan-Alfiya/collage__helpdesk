<?php
session_start();


$role = $_SESSION['role'] ?? null;

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();



if ($role === 'ADMIN' || $role === 'STUDENT' || $role === 'FACULTY') {
    header("Location: index.php");
}
else {
    header("Location: index.php");
}
exit; 
?>