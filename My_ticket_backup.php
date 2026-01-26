<?php
include 'includes/db.php';
include 'includes/functions.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: common_login.php");
    exit();
}

$logged_username = $_SESSION['username'];

try {

    $emailQuery = "SELECT email FROM student WHERE username = ?";
    $stmtEmail = $pdo->prepare($emailQuery);
    $stmtEmail->execute([$logged_username]);
    $userRow = $stmtEmail->fetch();

    
    if (!$userRow) {
        $emailQuery = "SELECT email FROM faculty WHERE username = ?";
        $stmtEmail = $pdo->prepare($emailQuery);
        $stmtEmail->execute([$logged_username]);
        $userRow = $stmtEmail->fetch();
    }

    if ($userRow) {
        $user_email = $userRow['email'];

    
        $sql = "SELECT * FROM tickets WHERE requester_email = ? ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_email]);
        $tickets = $stmt->fetchAll();
    } else {
        $tickets = []; 
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

include 'includes/header.php';
?>

<div class="wrapper" style="padding: 20px;">
    <div class="main-card" style="background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h2>My Tickets</h2>
        <p>Logged in as: <b><?= htmlspecialchars($logged_username) ?></b></p>
        
        <?php if (!empty($user_email)): ?>
            <p>Associated Email: <i><?= htmlspecialchars($user_email) ?></i></p>
        <?php endif; ?>

        <hr>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background: #f4f4f4; text-align: left;">
                    <th style="padding: 10px;">Ticket #</th>
                    <th style="padding: 10px;">Subject</th>
                    <th style="padding: 10px;">Status</th>
                    <th style="padding: 10px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tickets) > 0): ?>
                    <?php foreach ($tickets as $t): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;">#<?= $t['ticket_number'] ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($t['title']) ?></td>
                            <td style="padding: 10px;"><b><?= $t['status'] ?></b></td>
                            <td style="padding: 10px;">
                                <a href="ticket_view.php?ticket=<?= $t['ticket_number'] ?>" style="color: blue;">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="padding: 20px; text-align: center;">No tickets found for this user.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>