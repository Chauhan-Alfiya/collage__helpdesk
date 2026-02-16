<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role  = $_SESSION['role'] ?? '';
$email = $_SESSION['email'] ?? '';

switch($role) {
    case 'ADMIN':
        header("Location: admin_dashboard.php");
        exit();
    case 'STAFF':
        header("Location: staff_dashboard.php");
        exit();
    case 'CORD':
        header("Location: cord_dashboard.php");
        exit();
    case 'STUDENT':
        header("Location: my_tikcets.php");
        exit();
    case 'FACULTY':
        header("Location: my_tikcets.php");
        exit();
    default:
        header("Location: login.php");
        exit();
}

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE requester_email = ? ORDER BY created_at DESC");
$stmt->execute([$email]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="ticket-container">
    <div class="main-card">
        <div class="card-header">
            <div>
                <h2>My Support Tickets</h2>
                <p>Manage and track your reported issues</p>
            </div>
            <a href="create_ticket.php" class="btn-new-ticket">
                <i class="fas fa-plus"></i> New Ticket
            </a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Ticket #</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if(!empty($tickets)): ?>
                <?php foreach($tickets as $t): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($t['ticket_number']) ?></td>
                        <td><?= htmlspecialchars($t['title']) ?></td>
                        <td><?= htmlspecialchars($t['category']) ?></td>
                        <td>
                            <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $t['status'])) ?>">
                                <?= htmlspecialchars($t['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($t['created_at'])) ?></td>
                        <td>
                            <a href="ticket_view.php?ticket=<?= urlencode($t['ticket_number']) ?>" class="btn-view">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding:30px;">No tickets found</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
