<?php
include 'includes/db.php';
include 'includes/functions.php';
include 'includes/header.php';

$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requester_type = $_POST['requester_type'];
    $email = $_POST['email'];
    $stream = $_POST['stream'];
    $category = $_POST['category'];
    $title = $_POST['title'];
    $desc = $_POST['description'];

    // Routing Logic
    $assigned_to = getCoordinatorId($pdo, $category, $stream);
    
    if ($assigned_to) {
        $ticket_num = generateTicketNumber();
        
        // Insert Ticket
        $sql = "INSERT INTO tickets (ticket_number, requester_email, requester_type, stream, category, title, description, assigned_user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ticket_num, $email, $requester_type, $stream, $category, $title, $desc, $assigned_to]);
        $ticket_id = $pdo->lastInsertId();

        // Handle Attachment
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
            $fileName = $_FILES['attachment']['name'];
            $fileType = $_FILES['attachment']['type'];
            $fileData = file_get_contents($_FILES['attachment']['tmp_name']);
            $stmtAtt = $pdo->prepare("INSERT INTO ticket_attachments (ticket_id, filename, file_data, mime_type) VALUES (?, ?, ?, ?)");
            $stmtAtt->execute([$ticket_id, $fileName, $fileData, $fileType]);
        }

        $msg = "<div class='alert success'>Ticket created successfully! Your Ticket ID is <strong>$ticket_num</strong>. Please save this.</div>";
    } else {
        $msg = "<div class='alert error'>Error: Could not find a coordinator for this selection. Please contact admin.</div>";
    }
}
?>

<div class="container">
    <h2>Create New Ticket</h2>
    <?= $msg ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>I am a:</label>
            <select name="requester_type" class="form-control" required>
                <option value="Student">Student</option>
                <option value="Faculty">Faculty</option>
            </select>
        </div>
        <div class="form-group">
            <label>Email Address:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Stream:</label>
            <select name="stream" class="form-control" required>
                <option value="MCA">MCA</option>
                <option value="BBA">BBA</option>
                <option value="BCA">BCA</option>
                </select>
        </div>
        <div class="form-group">
            <label>Category:</label>
            <select name="category" class="form-control" required>
                <option value="Academic">Academic</option>
                <option value="Administrative">Administrative</option>
                <option value="Technical">Technical</option>
                <option value="Facility">Facility</option>
            </select>
        </div>
        <div class="form-group">
            <label>Issue Title:</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Description:</label>
            <textarea name="description" class="form-control" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label>Attachment (Optional):</label>
            <input type="file" name="attachment" class="form-control">
        </div>
        <button type="submit" class="btn">Submit Ticket</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>