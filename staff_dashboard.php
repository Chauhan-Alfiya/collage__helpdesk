<?php
session_start();
if (strpos($_SESSION['role'] ?? '', '_STAFF') === false) header("Location: login.php");
include 'includes/db.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE assigned_user_id = ? AND status = 'IN-PROGRESS'");
$stmt->execute([$user_id]);
$tickets = $stmt->fetchAll();
?>
<div class="container">
    <h2>Staff Dashboard</h2>
    <table>
        <tr><th>ID</th><th>Title</th><th>Action</th></tr>
        <?php foreach($tickets as $t): ?>
        <tr>
            <td><?= $t['ticket_number'] ?></td>
            <td><?= $t['title'] ?></td>
            <td><a href="ticket_details.php?id=<?= $t['ticket_id'] ?>" class="btn">Work on Ticket</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>