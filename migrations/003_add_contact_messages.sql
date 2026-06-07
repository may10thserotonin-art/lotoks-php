-- Migration 003: Add contact_messages table for contact form submissions
-- Also add sendEmail helper for contact form notifications

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT(11) NOT NULL AUTO_INCREMENT,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) DEFAULT NULL,
    interest VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    read_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX idx_contact_read (read_at),
    INDEX idx_contact_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
