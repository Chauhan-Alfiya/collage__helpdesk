<?php
<<<<<<< HEAD
// unique ticket number hd is helpdesk data for-exmple hd.20250209-5898
=======
// ===============================
// Generate Unique Ticket Number
// ===============================
>>>>>>> 984c313 (all file commit)
function generateTicketNumber() {
    return 'HD-' . date('Ymd') . '-' . rand(1000, 9999);
}

<<<<<<< HEAD
// ticket to correct Coordinator ID based on Category & Stream
function getCoordinatorId($pdo, $category, $stream) {
    $role_name = '';
    
    if ($category == 'Academic') {
        $role_name = strtoupper($stream) . '_CORD';
    } elseif ($category == 'Administrative') {
        $role_name = 'ADMINI_CORD';
    } elseif ($category == 'Technical') {
        $role_name = 'TECH_CORD';
    } elseif ($category == 'Facility') {
        $role_name = 'FACILITY_CORD';
    }
    
    $stmt = $pdo->prepare("SELECT u.user_id FROM users u JOIN roles r ON u.role_id = r.role_id WHERE r.role_name = ? LIMIT 1");
    $stmt->execute([$role]);
    $user = $stmt->fetch();
    return $user ? $user['user_id'] : null; 
    // Fallback logic could be added here to assign to generic admin if specific cord not found
}


// Function to route ticket to Staff based on Coordinator
function getStaffIdByCoordinator($pdo, $coordinator_id) {
    // Logic: Find the corresponding staff role for this coordinator's stream/cat
    // Simplify: Get Cord role, replace _CORD with _STAFF, find user
    $stmt = $pdo->prepare("SELECT r.role_name FROM users u JOIN roles r ON u.role_id = r.role_id WHERE u.user_id = ?");
    $stmt->execute([$coordinator_id]);
    $cord_role = $stmt->fetchColumn();
    
    $staff_role = str_replace('_CORD', '_STAFF', $cord_role);
    
    $stmt = $pdo->prepare("SELECT u.user_id FROM users u JOIN roles r ON u.role_id = r.role_id WHERE r.role_name = ? LIMIT 1");
    $stmt->execute([$staff_role]);
    $user = $stmt->fetch();
    return $user ? $user['user_id'] : null;
}

// Simple email mock
function sendNotification($email, $subject, $message) {
    // In production, use mail() or PHPMailer. 
    // For now, we assume success.
=======

// =====================================================
// Get Coordinator ID based on Category & Stream


// =====================================================
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

    // Fetch user directly from users table
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

    // Fallback: If no specific coordinator found
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


// =====================================================
// Get Staff ID based on Coordinator
// =====================================================
function getStaffIdByCoordinator($pdo, $coordinator_id) {

    // Step 1: Get coordinator role
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

    // Step 2: Convert CORD → STAFF
    $staff_role = str_replace('_CORD', '_STAFF', $cord_role);

    // Step 3: Find staff
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


// =====================================================
// Email Notification (Basic Mock)
// =====================================================
function sendNotification($email, $subject, $message) {
    // For now just return true
    // Later you can use mail() or PHPMailer
>>>>>>> 984c313 (all file commit)
    return true;
}
?>