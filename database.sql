SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- 1. Roles Table
CREATE TABLE `roles` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `roles` (`role_name`) VALUES
( 'ADMIN'),
( 'MCA_CORD'), 
( 'MCA_STAFF'),
( 'BBA_CORD'), 
( 'BBA_STAFF'),
( 'ADMINI_CORD'), 
( 'ADMINI_STAFF'),
( 'TECH_CORD'), 
( 'TECH_STAFF'),
( 'FACILITY_CORD'), 
( 'FACILITY_STAFF');


-- 2. Users Table
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int NOT NULL,
  `email` varchar(100) NOT NULL,   
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `last_password_change` datetime DEFAULT NULL,
  `is_active` boolean DEFAULT TRUE,
  `is_deleted` boolean DEFAULT FALSE,
  `last_activity_date` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`role_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  check (LENGTH(is_deleted=='')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Password for all sample users is 'password123'
-- Hash generated via password_hash('password123', PASSWORD_DEFAULT)
INSERT INTO `users` (`username`, `password`, `role_id`, `email`) VALUES
('admin', '$2y$10$FYhRXaElfD9aVBXjzkNn.OvXmBL8lhbzsi/UKlrIVfRAJivad27Vi', 1, 'admin@college.edu'),
('mca_cord', '$2y$10$FYhRXaElfD9aVBXjzkNn.OvXmBL8lhbzsi/UKlrIVfRAJivad27Vi', 2, 'mca_head@college.edu'),
('mca_staff', '$2y$10$FYhRXaElfD9aVBXjzkNn.OvXmBL8lhbzsi/UKlrIVfRAJivad27Vi', 3, 'mca_staff@college.edu'),
('bba_cord', '$2y$10$FYhRXaElfD9aVBXjzkNn.OvXmBL8lhbzsi/UKlrIVfRAJivad27Vi', 4, 'bba_head@college.edu'),
('bba_staff', '$2y$10$FYhRXaElfD9aVBXjzkNn.OvXmBL8lhbzsi/UKlrIVfRAJivad27Vi', 5, 'bba_staff@college.edu'),
('admin_cord', '$2y$10$FYhRXaElfD9aVBXjzkNn.OvXmBL8lhbzsi/UKlrIVfRAJivad27Vi', 6, 'admin_head@college.edu'),
('admin_staff', '$2y$10$FYhRXaElfD9aVBXjzkNn.OvXmBL8lhbzsi/UKlrIVfRAJivad27Vi', 7, 'admin_staff@college.edu'),
('tech_cord', '$2y$10$FYhRXaElfD9aVBXjzkNn.OvXmBL8lhbzsi/UKlrIVfRAJivad27Vi', 8, 'tech_head@college.edu'),
('tech_staff', '$2y$10$FYhRXaElfD9aVBXjzkNn.OvXmBL8lhbzsi/UKlrIVfRAJivad27Vi', 9, 'tech_staff@college.edu'),
('facility_cord', '$2y$10$FYhRXaElfD9aVBXjzkNn.OvXmBL8lhbzsi/UKlrIVfRAJivad27Vi', 10, 'facility_head@college.edu'),
('facility_staff', '$2y$10$FYhRXaElfD9aVBXjzkNn.OvXmBL8lhbzsi/UKlrIVfRAJivad27Vi', 11, 'facility_staff@college.edu');

-- 3. Tickets Table
CREATE TABLE `tickets` (
  `ticket_id` int NOT NULL AUTO_INCREMENT,
  `ticket_number` varchar(20) NOT NULL,
  `requester_email` varchar(100) NOT NULL,
  `requester_type` enum('Student','Faculty') NOT NULL,
  `stream` varchar(50) DEFAULT NULL,
  `category` enum('Academic','Administrative','Technical','Facility') NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('OPEN','IN-PROGRESS','RESOLVED','CLOSED') NOT NULL DEFAULT 'OPEN',
  `assigned_user_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `resolved_at` datetime DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ticket_id`),
  UNIQUE KEY `ticket_number` (`ticket_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- 4. Ticket Comments Table
CREATE TABLE `ticket_comments` (
  `comment_id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int NOT NULL,
  `user_id` int DEFAULT NULL, -- NULL if system generated or public
  `comment_text` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`comment_id`),
  FOREIGN KEY (`ticket_id`) REFERENCES `tickets`(`ticket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 
-- 5. Attachments Table
CREATE TABLE `ticket_attachments` (
  `attachment_id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int NOT NULL,
  `filename` varchar(255) NOT NULL,
  `file_data` longblob NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  PRIMARY KEY (`attachment_id`),
  FOREIGN KEY (`ticket_id`) REFERENCES `tickets`(`ticket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `j_role` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `j_role` (`role_name`) VALUES
( 'STUDENT'),
( 'FACILITY');


CREATE TABLE `j_users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  FOREIGN KEY (`role_id`) REFERENCES `j_role`(`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `j_users` (`username`, `password`, `role_id`, `email`) VALUES

('student', '$2y$10$FYhRXaElfD9aVBXjzkNn.OvXmBL8lhbzsi/UKlrIVfRAJivad27Vi' ,1, 'Student@college.edu'),
('facility', '$2y$10$FYhRXaElfD9aVBXjzkNn.OvXmBL8lhbzsi/UKlrIVfRAJivad27Vi', 2, 'facility@college.edu');
   


CREATE TABLE student (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    stream VARCHAR(100) NOT NULL,
    semester INT NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
);

CREATE TABLE faculty (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL ,
    department VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
);


 