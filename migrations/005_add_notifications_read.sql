-- Add read_at column to activity_log for read/unread tracking
ALTER TABLE `activity_log`
    ADD COLUMN IF NOT EXISTS `read_at` DATETIME DEFAULT NULL AFTER `created_at`;

-- Index for counting unread notifications
ALTER TABLE `activity_log`
    ADD INDEX IF NOT EXISTS `idx_activity_read` (`read_at`);
