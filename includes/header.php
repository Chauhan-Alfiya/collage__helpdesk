<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$username = $_SESSION['username'] ?? null;
$user_id  = $_SESSION['user_id'] ?? null;
$role     = $_SESSION['role'] ?? 'Student';
$initial  = $username ? strtoupper($username[0]) : '?'; 
?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Helpdesk</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/style.css"> <link rel="stylesheet" href="css/sidebar.css"> </head>


    