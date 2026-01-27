<?php 
include 'includes/db.php';
include 'includes/functions.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: common_login.php");
    exit();
}
$username=$_SESSION['username'];
$msg ="";

$table = "";
$id_column = "";
$uid = 0;


$stm = $pdo->prepare("SELECT * FROM settings WHERE username = ?");
$stm->execute([$username]);
$settings = $stm->fetch(PDO::FETCH_ASSOC);