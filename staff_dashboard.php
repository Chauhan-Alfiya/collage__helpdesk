<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

$role = $_SESSION['role'] ?? '';
if (stripos($role, '_STAFF') === false) {
    header("Location: common_login.php");
    exit();
}

$username = $_SESSION['username'];
$user_id  = $_SESSION['user_id'];
$stream   = $_SESSION['stream']; 

$stmt = $pdo->prepare("
    SELECT * FROM tickets  
    WHERE assigned_user_id = ? 
      AND stream = ?
      AND status != 'CLOSED' 
    ORDER BY created_at DESC
");

$stmt->execute([$user_id, $stream]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
include 'includes/header.php'; 
?>
 
<div class="ticket-container">
    <div class="main-card">
        <div class="card-header">
            <div>
                <h2>Staff Task Queue (<?= htmlspecialchars($stream) ?>)</h2>
                <p>Manage and resolve tickets assigned to you</p>
            </div>
            <a href="all_tickets.php" class="btn-new-ticket">
                <i class="fas fa-list"></i> All Tickets
            </a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Ticket #</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Date Assigned</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($tickets)): ?>
                <?php foreach ($tickets as $t): ?>
                    <tr>
                        <td style="font-weight:600;">#<?= htmlspecialchars($t['ticket_number']) ?></td>
                        <td><?= htmlspecialchars($t['title']) ?></td>
                        <td><?= htmlspecialchars($t['category']) ?></td>
                        <td>
                            <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $t['status'])) ?>">
                                <?= htmlspecialchars($t['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($t['created_at'])) ?></td>
                        <td>
                            <a href="ticket_details.php?id=<?= $t['ticket_id'] ?>" class="btn-view">Process</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding:30px;">No assigned tasks found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
