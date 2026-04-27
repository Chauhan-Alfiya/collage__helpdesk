<?php

// ===============================
// Generate Unique Ticket Number
// ===============================
function generateTicketNumber() {
    return 'HD-' . date('Ymd') . '-' . rand(1000, 9999);
}


// ===============================
// Get Coordinator ID based on Category & Stream
// ===============================
function getCoordinatorId($pdo, $category, $stream) {

    $role = '';

    // Decide role based on category
    if ($category == 'Academic') {
        $role = strtoupper($stream) . '_CORD';   // Example: BCA_CORD
    } elseif ($category == 'Administrative') {
        $role = 'ADMINI_CORD';
    } elseif ($category == 'Technical') {
        $role = 'TECH_CORD';
    } elseif ($category == 'Facility') {
        $role = 'FACILITY_CORD';
    }

    // Fetch coordinator
    $stmt = $pdo->prepare("
        SELECT user_id 
        FROM users 
        WHERE role = ? 
        AND is_active = 1 
        AND is_deleted = 0
        LIMIT 1
    ");
    $stmt->execute([$role]);
    $user_id = $stmt->fetchColumn();

    // Fallback: assign to ADMIN if not found
    if (!$user_id) {
        $stmt = $pdo->prepare("
            SELECT user_id 
            FROM users 
            WHERE role = 'ADMIN' 
            LIMIT 1
        ");
        $stmt->execute();
        $user_id = $stmt->fetchColumn();
    }

    return $user_id;
}


// ===============================
// Get Staff ID based on Coordinator
// ===============================
function getStaffIdByCoordinator($pdo, $coordinator_id) {

    // Get coordinator role
    $stmt = $pdo->prepare("
        SELECT role 
        FROM users 
        WHERE user_id = ?
    ");
    $stmt->execute([$coordinator_id]);
    $cord_role = $stmt->fetchColumn();

    if (!$cord_role) {
        return null;
    }

    // Convert CORD → STAFF
    $staff_role = str_replace('_CORD', '_STAFF', $cord_role);

    // Find staff
    $stmt = $pdo->prepare("
        SELECT user_id 
        FROM users 
        WHERE role = ? 
        AND is_active = 1 
        AND is_deleted = 0
        LIMIT 1
    ");
    $stmt->execute([$staff_role]);
    $staff_id = $stmt->fetchColumn();

    return $staff_id ? $staff_id : null;
}


// ===============================
// Email Notification (Mock)
// ===============================
function sendNotification($email, $subject, $message) {
    // Later you can use mail() or PHPMailer
    return true;
}

?>