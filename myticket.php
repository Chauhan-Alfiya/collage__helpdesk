
 <?php
include 'includes/db.php';
include 'includes/header.php';

$tickets = [];


if (isset($_POST[''])) {
    // Search by Ticket Number OR Email
    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE ticket_number = ? OR requester_email = ? ORDER BY created_at DESC");
    $stmt->execute([$search, $search]);
    $tickets = $stmt->fetchAll();
}
?>
<div class="container">
    <h2>My Tickets</h2>
    <form method="POST" style="margin-bottom: 20px;">
</div>

    <?php if(!empty($tickets)): ?>
        <table>
            <tr>
                <th>Ticket #</th>
                <th>Category</th>
                <th>Title</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach($tickets as $t): ?>
            <tr>
                <td><?= $t['ticket_number'] ?></td>
                <td><?= $t['category'] . ($t['category']=='Academic' ? ' (' . $t['stream'] . ')' : '') ?></td>
                <td><?= htmlspecialchars($t['title']) ?></td>
                <td class="status-<?= $t['status'] ?>"><?= $t['status'] ?></td>
                <td><a href="ticket_details.php?id=<?= $t['ticket_id'] ?>&public=true" class="btn btn-secondary">View</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif(isset($_POST['search'])): ?>
        <p>No tickets found.</p>
    <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
</div>