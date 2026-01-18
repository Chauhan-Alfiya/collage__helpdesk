<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';


if (!isset($_SESSION['login_type'])) {
    // header("Location: login.php");
    // exit;
}

$loginType = $_SESSION['login_type'];
$tickets   = [];

if ($loginType === 'STUDENT') {

    $stmt = $pdo->prepare("
        SELECT * FROM tickets
        WHERE requester_number  = ?
        ORDER BY created_at DESC
    ");
    $stmt->execute([$_SESSION['email']]);

}
elseif ($loginType === 'FACULTY') {

    $stmt = $pdo->prepare("
        SELECT * FROM tickets
        WHERE requester_email = ?
        ORDER BY created_at DESC
    ");
    $stmt->execute([$_SESSION['email']]);

}

    $tickets = $stmt->fetchAll();
?>
<div class="container">
    <h2>Show Your Tickets</h2>

    <?php if ($tickets): ?>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr>
            <th>Ticket #</th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php foreach ($tickets as $t): ?>
        <tr>
            <td>
                <a href="ticket_details.php?id=<?= $t['ticket_id'] ?>">
                    <?= htmlspecialchars($t['ticket_number']) ?>
                </a>
            </td>
            <td><?= htmlspecialchars($t['title']) ?></td>
            <td><?= $t['category'] ?> (<?= $t['stream'] ?>)</td>
            <td class="status-<?= strtolower($t['status']) ?>">
                <?= $t['status'] ?>
            </td>
            <td>
                <a href="ticket_details.php?id=<?= $t['ticket_id'] ?>" class="btn btn-secondary">
                    View
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p>No tickets found.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
