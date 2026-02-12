<?php
session_start();
if (strpos($_SESSION['role'] ?? '', '_CORD') === false) header("Location: login.php");
include 'includes/db.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE assigned_user_id = ? ORDER BY field(status, 'OPEN', 'RESOLVED', 'IN-PROGRESS', 'CLOSED')");
$stmt->execute([$user_id]);
$tickets = $stmt->fetchAll();
?>

<div class ="ticket-container">
    <div class="main-card">
        <div class="card-header">
            <div>
                <h2>Coordinator Dashboard (<?= $_SESSION['role'] ?>)</h2>
                <p>You are seeing tickets assigned to you.</p>
            </div>
            <a href="all_tickets.php" class="btn-new-ticket"><i class="fas fa-list"></i> All Tickets</a>
        </div>


<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Date Assigned</th>
            <th>Action</th>
        </tr> 
    </thead>  
    <tbody>
    <?php if (!empty($tickets)): ?>
        <?php foreach($tickets as $t): ?>
        <tr>
            <td><?= $t['ticket_number'] ?></td>
            <td><?= $t['title'] ?></td>
            <td>
                <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $t['status'])) ?>">
                    <?= $t['status'] ?>
                </span>
            </td>
            <td><?= date('d M Y', strtotime($t['created_at'])) ?></td>
            <td><a href="ticket_details.php?id=<?= $t['ticket_id'] ?>" class="btn">Manage</a></td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
                <tr>                    
                    <td colspan="6" style="text-a lign:center; padding:30px;">
                        No assigned tasks found.
                    </td>
                </tr>
            <?php endif; ?>
    </tbody>
</table>
</div>
</div>
        </div>