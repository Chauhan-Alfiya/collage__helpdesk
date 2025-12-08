<?php
session_start();
if (strpos($_SESSION['role'] ?? '', '_CORD') === false) header("Location: login.php");
include 'includes/db.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];
// Show tickets assigned to this coordinator
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE assigned_user_id = ? ORDER BY field(status, 'OPEN', 'RESOLVED', 'IN-PROGRESS', 'CLOSED')");
$stmt->execute([$user_id]);
$tickets = $stmt->fetchAll();
?>
<div class="container">
    <h2>Coordinator Dashboard (<?= $_SESSION['role'] ?>)</h2>
    <p>You are seeing tickets assigned to you.</p>
    <table>
        <tr><th>ID</th><th>Title</th><th>Status</th><th>Action</th></tr>
        <?php foreach($tickets as $t): ?>
        <tr>
            <td><?= $t['ticket_number'] ?></td>
            <td><?= $t['title'] ?></td>
            <td class="status-<?= $t['status'] ?>"><?= $t['status'] ?></td>
            <td><a href="ticket_details.php?id=<?= $t['ticket_id'] ?>" class="btn">Manage</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>