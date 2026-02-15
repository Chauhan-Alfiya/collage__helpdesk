<?php
session_start();
if (strpos($_SESSION['role'] ?? '', '_CORD') === false) header("Location: login.php");
include 'includes/db.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];
$stream  = $_SESSION['stream']; // cord ka stream

// Coordinator sees all tickets in their stream (assigned or not)
$stmt = $pdo->prepare("
    SELECT * FROM tickets 
    WHERE stream = ?
    ORDER BY FIELD(status, 'OPEN', 'IN-PROGRESS', 'RESOLVED', 'CLOSED'), created_at DESC
");
$stmt->execute([$stream]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="ticket-container">
    <div class="main-card">
        <div class="card-header">
            <div>
                <h2>Coordinator Dashboard (<?= htmlspecialchars($stream) ?>)</h2>
                <p>You are seeing all tickets in your stream.</p>
            </div>
            <a href="all_tickets.php" class="btn-new-ticket"><i class="fas fa-list"></i> All Tickets</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Ticket #</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr> 
            </thead>  
            <tbody>
            <?php if (!empty($tickets)): ?>
                <?php foreach($tickets as $t): ?>
                <tr>
                    <td>#<?= htmlspecialchars($t['ticket_number']) ?></td>
                    <td><?= htmlspecialchars($t['title']) ?></td>
                    <td>
                        <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $t['status'])) ?>">
                            <?= htmlspecialchars($t['status']) ?>
                        </span>
                    </td>
                    <td><?= date('d M Y', strtotime($t['created_at'])) ?></td>
                    <td><a href="ticket_details.php?id=<?= $t['ticket_id'] ?>" class="btn-view">Manage</a></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>                    
                    <td colspan="5" style="text-align:center; padding:30px;">
                        No tickets found in your stream.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
