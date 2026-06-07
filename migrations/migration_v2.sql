-- ============================================================
-- Lotoks — Migration v2: Security, Newsletter, 2FA, Rate Limit
-- ============================================================
-- Run AFTER migrations/001_add_suspended_to_users.sql
-- 
-- Usage:
--   Option A: Visit /migrations/run.php in browser (select this file)
--   Option B: Import via phpMyAdmin or MySQL CLI
--
--   mysql -u root lotoks < migrations/migration_v2.sql
-- ============================================================

-- ── 1. Login Attempts (Rate Limiting) ──────────────────────────
CREATE TABLE IF NOT EXISTS `login_attempts` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ip_address`  VARCHAR(45) NOT NULL COMMENT 'IPv4 or IPv6',
    `action_type` VARCHAR(50) NOT NULL COMMENT 'e.g. login_user, login_admin',
    `success`     TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = successful attempt',
    `attempted_at`DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_login_attempts_ip`   (`ip_address`, `action_type`),
    INDEX `idx_login_attempts_time` (`attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── 2. Password Resets (cleaner than columns on users) ─────────
CREATE TABLE IF NOT EXISTS `password_resets` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email`      VARCHAR(255) NOT NULL,
    `token`      VARCHAR(64) NOT NULL COMMENT 'SHA-256 hash of the reset token',
    `expires_at` DATETIME NOT NULL,
    `used`       TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_password_resets_token`  (`token`),
    INDEX `idx_password_resets_email`  (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── 3. Newsletter Subscribers ─────────────────────────────────
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email`         VARCHAR(255) NOT NULL,
    `status`        ENUM('active','pending','unsubscribed') NOT NULL DEFAULT 'pending',
    `confirm_token` VARCHAR(64) DEFAULT NULL,
    `confirmed_at`  DATETIME DEFAULT NULL,
    `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_newsletter_email` (`email`),
    INDEX `idx_newsletter_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── 4. Two-Factor Auth Columns for Admins ─────────────────────
ALTER TABLE `admins`
    ADD COLUMN IF NOT EXISTS `two_factor_enabled` TINYINT(1) NOT NULL DEFAULT 0 AFTER `role`,
    ADD COLUMN IF NOT EXISTS `two_factor_secret` VARCHAR(255) DEFAULT NULL AFTER `two_factor_enabled`,
    ADD COLUMN IF NOT EXISTS `two_factor_backup_codes` TEXT DEFAULT NULL AFTER `two_factor_secret`;

-- ── 5. Document Verification / Reviewer Notes ─────────────────
-- Note: user_documents uses `verified` column, not `status`
ALTER TABLE `user_documents`
    ADD COLUMN IF NOT EXISTS `verified_by` INT UNSIGNED DEFAULT NULL AFTER `verified`,
    ADD COLUMN IF NOT EXISTS `verified_at` DATETIME DEFAULT NULL AFTER `verified_by`,
    ADD COLUMN IF NOT EXISTS `review_notes` TEXT DEFAULT NULL AFTER `verified_at`,
    ADD COLUMN IF NOT EXISTS `document_status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending' AFTER `category`;

-- ── 6. Indexes for Performance ────────────────────────────────
-- Add index on activity_log for faster filtered queries
-- Note: activity_log uses `action` field, not `action_type`
ALTER TABLE `activity_log`
    ADD INDEX IF NOT EXISTS `idx_activity_user` (`user_id`),
    ADD INDEX IF NOT EXISTS `idx_activity_action` (`action`),
    ADD INDEX IF NOT EXISTS `idx_activity_created` (`created_at`);

-- Add index on applications for status filtering
ALTER TABLE `applications`
    ADD INDEX IF NOT EXISTS `idx_applications_status` (`status`),
    ADD INDEX IF NOT EXISTS `idx_applications_user` (`user_id`);

-- ============================================================
-- Migration Complete
-- ============================================================
