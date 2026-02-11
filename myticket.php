<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

if (!isset($_SESSION['username'])) {
    header("Location: common_login.php");
    exit();
}

$role = $_SESSION['role'] ?? '';

if (stripos($role, '_STAFF') !== false) {
    header("Location: staff_dashboard.php");
    exit();
}

if (stripos($role, '_CORD') !== false) {
    header("Location: cord_dashboard.php");
    exit();
}

if ($role === 'ADMIN') {
    header("Location: admin_dashboard.php");
    exit();
}

$username = $_SESSION['username'];
$tickets = [];
$found_email = "";

$stmt = $pdo->prepare("SELECT email FROM student WHERE username = ? AND is_deleted = 0");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $stmt = $pdo->prepare("SELECT email FROM faculty WHERE username = ? AND is_deleted = 0");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($user) {
    $found_email = $user['email'];

    $stmt = $pdo->prepare("
        SELECT * FROM tickets
        WHERE requester_email = ?
        ORDER BY created_at DESC
    ");
    $stmt->execute([$found_email]);
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

include 'includes/header.php';
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

        <?php if (!$found_email): ?>
            <div class="alert alert-danger" style="margin:20px;">
                Email not found. Please update your profile.
            </div>
        <?php endif; ?>

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
            <?php if (!empty($tickets)): ?>
                <?php foreach ($tickets as $t): ?>
                    <tr>
                        <td style="font-weight:600;">#<?= $t['ticket_number'] ?></td>
                        <td><?= htmlspecialchars($t['title']) ?></td>
                        <td><?= $t['category'] ?></td>
                        <td>
                            <span class="status-badge status-<?= strtolower($t['status']) ?>">
                                <?= $t['status'] ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($t['created_at'])) ?></td>
                        <td>
                            <a href="ticket_view.php?ticket=<?= $t['ticket_number'] ?>" class="btn-view">
                                View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding:30px;">
                        No tickets found
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<?php include 'includes/footer.php'; ?>