<?php
include 'includes/db.php';
include 'includes/header.php';

$tickets = [];

if (!empty($userEmail)) {
    if (!empty($_POST['query'])) {
        // Search by ticket number
        $search = $_POST['query'];
        $stmt = $pdo->prepare("
            SELECT * FROM tickets 
            WHERE requester_email = ? AND ticket_number = ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userEmail, $search]);
    } else {
        // Show all tickets for this user
        $stmt = $pdo->prepare("
            SELECT * FROM tickets 
            WHERE requester_email = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userEmail]);
    }
    $tickets = $stmt->fetchAll();
}

// $tickets = []; // Initialize tickets

// if (isset($_POST['query'])) {
//     $search = $_POST['query'];

//     $stmt = $pdo->prepare("
//         SELECT * FROM tickets 
//         WHERE ticket_number = ? OR requester_email = ? 
//         ORDER BY created_at DESC
//     ");
//     $stmt->execute([$search, $search]);
//     $tickets = $stmt->fetchAll();
// }
// ?>

<div class="container">
    <h2>My Tickets</h2>

    <?php if (!empty($tickets)): ?>
        <table cellpadding="10" cellspacing="0">
            <tr>
                <th>Ticket #</th>
                <th>Category</th>
                <th>Title</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php foreach ($tickets as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['ticket_number']) ?></td>
                    <td>
                        <?= htmlspecialchars($t['category']) ?>
                        <?= ($t['category'] === 'Academic') ? ' (' . htmlspecialchars($t['stream']) . ')' : '' ?>
                    </td>
                    <td><?= htmlspecialchars($t['title']) ?></td>
                    <td class="status-<?= htmlspecialchars($t['status']) ?>">
                        <?= htmlspecialchars($t['status']) ?>
                    </td>
                    <td>
                        <a href="ticket_details.php?id=<?= (int)$t['ticket_id'] ?>&public=true" class="btn btn-secondary">
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


session_start();

// Example: after login
$_SESSION['user_email'] = $userEmail; // e.g., ruhi@example.com
<?php
include 'includes/db.php';
session_start();

$userEmail = $_SESSION['user_email']; // Ruhi's email

if (isset($_POST['submit_ticket'])) {
    $ticketNumber = generateTicketNumber(); // your function
    $category = $_POST['category'];
    $title = $_POST['title'];
    
    $stmt = $pdo->prepare("
        INSERT INTO tickets (ticket_number, category, title, requester_email, status, created_at)
        VALUES (?, ?, ?, ?, 'OPEN', NOW())
    ");
    $stmt->execute([$ticketNumber, $category, $title, $userEmail]);

    // Optional: redirect to My Tickets page
    header("Location: my_tickets.php");
    exit;
}
?>
<?php
include 'includes/db.php';
session_start();

$userEmail = $_SESSION['user_email'];
$tickets = [];

if (!empty($userEmail)) {
    $stmt = $pdo->prepare("
        SELECT * FROM tickets 
        WHERE requester_email = ? 
        ORDER BY created_at DESC
    ");
    $stmt->execute([$userEmail]);
    $tickets = $stmt->fetchAll();
}
?>

<div class="container">
    <h2>My Tickets</h2>

    <?php if (!empty($tickets)): ?>
        <table cellpadding="10" cellspacing="0">
            <tr>
                <th>Ticket #</th>
                <th>Category</th>
                <th>Title</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php foreach ($tickets as $t): ?>
                <tr>
                    <td>
                        <a href="ticket_details.php?id=<?= (int)$t['ticket_id'] ?>&public=true">
                            <?= htmlspecialchars($t['ticket_number']) ?>
                        </a>
                    </td>
                    <td>
                        <?= htmlspecialchars($t['category']) ?>
                        <?= ($t['category'] === 'Academic') ? ' (' . htmlspecialchars($t['stream']) . ')' : '' ?>
                    </td>
                    <td><?= htmlspecialchars($t['title']) ?></td>
                    <td class="status-<?= htmlspecialchars($t['status']) ?>">
                        <?= htmlspecialchars($t['status']) ?>
                    </td>
                    <td>
                        <a href="ticket_details.php?id=<?= (int)$t['ticket_id'] ?>&public=true" class="btn btn-secondary">
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
<?php
include 'includes/db.php';
session_start();

$ticket_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE ticket_id = ?");
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    echo "Ticket not found.";
    exit;
}
?>
