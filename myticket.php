<?php
include 'includes/db.php';
include 'includes/functions.php';

// Get email from URL
$email = $_GET['email'] ?? '';

if (!$email) {
    echo "No user specified.";
    exit;
}

// Fetch tickets for this user
$sql = "SELECT ticket_number, title, category, stream, created_at 
        FROM tickets 
        WHERE requester_email = ? 
        ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h2>My Tickets</h2>

    <?php if (!empty($tickets)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Ticket Number</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Stream</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td>
                            <a href="ticket_view.php?ticket=<?= htmlspecialchars($ticket['ticket_number']) ?>">
                                <?= htmlspecialchars($ticket['ticket_number']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($ticket['title']) ?></td>
                        <td><?= htmlspecialchars($ticket['category']) ?></td>
                        <td><?= htmlspecialchars($ticket['stream']) ?></td>
                        <td><?= htmlspecialchars($ticket['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tickets found.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
