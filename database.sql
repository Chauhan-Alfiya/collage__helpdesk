SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE roles (
  role_id INT NOT NULL AUTO_INCREMENT,
  role VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (role_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO roles (role) VALUES
('ADMIN'),
('CORD'),
('STAFF'),
('STUDENT'),
('FACULTY');


CREATE TABLE users (
  user_id INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role_id INT NOT NULL,
  role VARCHAR(50) NOT NULL,
  is_active BOOLEAN DEFAULT TRUE,
  is_deleted BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_activity_date DATETIME DEFAULT NULL,
  last_password_change DATETIME DEFAULT NULL,
  deleted_at DATETIME DEFAULT NULL,
  reset_token VARCHAR(255) DEFAULT NULL,
  reset_expires DATETIME DEFAULT NULL,
  PRIMARY KEY (user_id),
  FOREIGN KEY (role_id) REFERENCES roles(role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE users
ADD COLUMN department VARCHAR(100) NOT NULL AFTER role;


INSERT INTO users (username, email, password, role, role_id)
VALUES (
    'Admin',
    'Admin@helpdesk.com',
    '$2y$10$FYhRXaElfD9aVBXjzkNn.OvXmBL8lhbzsi/UKlrIVfRAJivad27Vi',
    'ADMIN',
    (SELECT role_id FROM roles WHERE role = 'ADMIN')
);

CREATE TABLE student_details (
  user_id INT NOT NULL,
  semester INT NOT NULL,
  PRIMARY KEY (user_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);


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
CREATEa TABLE `ticket_comments` (
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


