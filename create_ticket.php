<?php
include 'includes/db.php';
include 'includes/functions.php';

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $requester_type = $_POST['requester_type'];
    $email          = $_POST['email'];
    $stream         = $_POST['stream'];
    $category       = $_POST['category'];
    $title          = $_POST['title'];
    $desc           = $_POST['description'];

  
    $assigned_to = getCoordinatorId($pdo, $category, $stream);

    if ($assigned_to) {

       
        $ticket_num = generateTicketNumber();

        
        $sql = "INSERT INTO tickets
        (ticket_number, requester_email, requester_type, stream, category, title, description, assigned_user_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $ticket_num,
            $email,
            $requester_type,
            $stream,
            $category,
            $title,
            $desc,
            $assigned_to
        ]);

        $ticket_id = $pdo->lastInsertId();

      
        if (!empty($_FILES['attachment']['name'])) {
            $stmtAtt = $pdo->prepare(
                "INSERT INTO ticket_attachments (ticket_id, filename, file_data, mime_type)
                 VALUES (?, ?, ?, ?)"
            );
            $stmtAtt->execute([
                $ticket_id,
                $_FILES['attachment']['name'],
                file_get_contents($_FILES['attachment']['tmp_name']),
                $_FILES['attachment']['type']
            ]);
        }

        
        $msg = "
        <div class='alert success'>
            Ticket Created Successfully! <br><br>
            <b>Your Ticket Number:</b>
            <a href='ticket_view.php?ticket=$ticket_num'>$ticket_num</a>
            <br><br>
            <a href='myticket.php?email=$email'>View All My Tickets</a>
        </div>
        ";

    } else {
        $msg = "<div class='alert error'>No coordinator found.</div>";
    }
}
?>

<?php include 'includes/header.php'; ?>


<link rel="stylesheet" href="css/create_ticket.css">

<div class="wrapper">
    <div class="main-card">
        <div class="form-header">
            <h1 style="color: #1e40af;">Create Ticket</h1>
            <p>Describe your issue and we'll resolve it as soon as possible.</p>
        </div>

        <?= $msg ?>

        <form method="POST" enctype="multipart/form-data">

            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

            <div class="form-grid">
                <div class="form-group">
                    <label>Identification</label>
                    <div class="input-wrapper">
                        <select name="requester_type" class="form-control" required>
                            <option value="Student">Student</option>
                            <option value="Faculty">Faculty</option>
                        </select>
                        <i class="fas fa-user-tag"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" class="form-control" placeholder="" required>
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Academic Stream</label>
                    <div class="input-wrapper">
                        <select name="stream" class="form-control" required>
                            <option value="MCA">MCA</option>
                            <option value="BBA">BBA</option>
                            <option value="BCA">BCA</option>
                            <option value="BCom">BCom</option>
                        </select>
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <div class="input-wrapper">
                        <select name="category" class="form-control" required>
                            <option value="Academic">Academic</option>
                            <option value="Administrative">Administrative</option>
                            <option value="Technical">Technical</option>
                            <option value="Facility">Facility</option>
                        </select>
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Ticket Subject</label>
                <div class="input-wrapper">
                    <input type="text" name="title" class="form-control" placeholder="Brief summary of the issue" required>
                    <i class="fas fa-pen-nib"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Detailed Description</label>
                <textarea name="description" class="form-control" placeholder="Please provide all details..." required></textarea>
            </div>

            <div class="form-group">
                 <label>Attachment (Optional):</label>
                 <input type="file" name="attachment" class="form-control">
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Create Ticket <i class="fas fa-arrow-right"></i>
            </button>

        </form>
    </div>
</div>

