<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_email'])) {
    echo "<p>Please create a ticket first.</p>";
    include 'includes/footer.php';
    exit;
}

$email = $_SESSION['user_email'];

$sql = "SELECT ticket_number, title, category, status, created_at
        FROM tickets
        WHERE requester_email = ?
        ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$tickets = $stmt->fetchAll();
?>

<div class="container">
    <h2>My Tickets</h2>

    <?php if ($tickets): ?>
    <table border="1" cellpadding="8">
        <tr>
            <th>Ticket No</th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Date</th>
        </tr>

        <?php foreach ($tickets as $t): ?>
        <tr>
            <td><?= htmlspecialchars($t['ticket_number']) ?></td>
            <td><?= htmlspecialchars($t['title']) ?></td>
            <td><?= htmlspecialchars($t['category']) ?></td>
            <td><?= htmlspecialchars($t['status']) ?></td>
            <td><?= htmlspecialchars($t['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p>No tickets found.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
