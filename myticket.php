
 <?php
include 'includes/db.php';
include 'includes/header.php';

    $tickets = [];


    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE ticket_number = ? OR requester_email = ? ORDER BY created_at DESC");
    $tickets = $stmt->fetchAll();
    if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] == 'ADMIN') 
                $home_link = 'home.php'; 

             elseif ($_SESSION['role'] == 'STUDENT') 
                 $home_link = 'home.php';
            elseif ($_SESSION['role'] == 'FACULTY') 
                 $home_link = 'home.php';
                
            elseif (strpos($_SESSION['role'], '_CORD') !== false) 
                $home_link = 'home.php';
            elseif (strpos($_SESSION['role'], '_STAFF') !== false) 
                $home_link = 'home.php';
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
    <?php elseif(isset($_POST['my tickets'])): ?>
        <p>No tickets found.</p>
    <?php endif; ?>
</div>

</div>