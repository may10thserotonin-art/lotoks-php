-- ═══════════════════════════════════════════════════════════════
--  Migration 006: Add Account Lockout Columns
--  ═══════════════════════════════════════════════════════════════
--  Adds per-account lockout tracking to users and admins tables.
--  Also adds an index on login_attempts(email) for account-based
--  lookups (existing index is only on ip_address + action_type).
-- ═══════════════════════════════════════════════════════════════

ALTER TABLE users
  ADD COLUMN login_attempts   INT       NOT NULL DEFAULT 0
    COMMENT 'Consecutive failed login attempts',
  ADD COLUMN locked_until     DATETIME  DEFAULT NULL
    COMMENT 'Account is locked until this timestamp (NULL = not locked)',
  ADD COLUMN last_failed_login DATETIME DEFAULT NULL
    COMMENT 'Timestamp of the last failed login attempt';

ALTER TABLE admins
  ADD COLUMN login_attempts   INT       NOT NULL DEFAULT 0
    COMMENT 'Consecutive failed login attempts',
  ADD COLUMN locked_until     DATETIME  DEFAULT NULL
    COMMENT 'Account is locked until this timestamp (NULL = not locked)',
  ADD COLUMN last_failed_login DATETIME DEFAULT NULL
    COMMENT 'Timestamp of the last failed login attempt';

-- Add email-based index to login_attempts for per-account lookups
ALTER TABLE login_attempts
  ADD COLUMN email VARCHAR(255) DEFAULT NULL
    COMMENT 'Email used in the attempt (for per-account lockout tracking)',
  ADD INDEX idx_login_attempts_email (email, action_type);
