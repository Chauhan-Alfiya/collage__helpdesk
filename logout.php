<?php
session_start();


$role = $_SESSION['role'] ?? null;

// Unset all session variables
$_SESSION = [];

// Destroy the session cookie if it exists
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to login/home page
if ($role === 'ADMIN' || strpos($role, '_CORD') !== false || strpos($role, '_STAFF') !== false) {
    header("Location: home.php");
}
elseif ($role === 'STUDENT' || $role === 'FACULTY') {
    header("Location: index.php");
}
else {
    header("Location: index.php");
}
exit;
?>