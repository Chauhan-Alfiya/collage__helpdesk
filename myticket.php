<?php
include 'includes/db.php';
include 'includes/header.php';

$email = $_GET['email'] ?? ''; // URL se email le raha hai

if ($email) {
    // Database se us email ke saare tickets nikalna
    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE requester_email = ? ORDER BY created_at DESC");
    $stmt->execute([$email]);
    $tickets = $stmt->fetchAll();
}
?>

<div class="container">
    <h2>My Tickets</h2>
    <p>Showing tickets for: <b><?= htmlspecialchars($email) ?></b></p>

    <table border="1" cellpadding="10" style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Ticket Number</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($tickets): ?>
                <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td>
                        <a href="ticket_view.php?ticket=<?= $ticket['ticket_number'] ?>">
                            <?= $ticket['ticket_number'] ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($ticket['title']) ?></td>
                    <td><?= htmlspecialchars($ticket['category']) ?></td>
                    <td><?= $ticket['status'] ?? 'Open' ?></td>
                    <td><?= $ticket['created_at'] ?? 'N/A' ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No tickets found for this email.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>