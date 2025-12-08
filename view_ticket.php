<?php
include 'includes/db.php';
include 'includes/header.php';

$tickets = [];
$search = "";

if (isset($_POST['search'])) {
    $search = $_POST['query'];
    // Search by Ticket Number OR Email
    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE ticket_number = ? OR requester_email = ? ORDER BY created_at DESC");
    $stmt->execute([$search, $search]);
    $tickets = $stmt->fetchAll();
}
?>
<div class="container">
    <h2>Check Ticket Status</h2>
    <form method="POST" style="margin-bottom: 20px;">
        <input type="text" name="query" class="form-control" placeholder="Enter Ticket Number or Email" value="<?= htmlspecialchars($search) ?>" required style="width: 70%; display: inline-block;">
        <button type="submit" name="search" class="btn">Search</button>
    </form>

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