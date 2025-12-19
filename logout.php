<?php
session_start();

// Unset all session variables
$_SESSION = array();

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
if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] == 'ADMIN'); 
            elseif (strpos($_SESSION['role'], '_CORD') !== false);                
            elseif (strpos($_SESSION['role'], '_STAFF') !== false); 
                header("Location: home.php");
        }

else {
    header("Location: index.php");
}
exit;
?>