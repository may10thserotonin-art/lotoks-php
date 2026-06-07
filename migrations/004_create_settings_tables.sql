-- ─────────────────────────────────────────────────────────────────
-- Migration 004: Create settings tables
-- ─────────────────────────────────────────────────────────────────
-- user_settings  — per-user notification preferences + profile extras
-- site_settings  — key-value global config (super admin only)
-- ─────────────────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS user_settings (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  user_id           INT NOT NULL UNIQUE,
  email_notifications  TINYINT(1) DEFAULT 1 COMMENT 'Receive email updates about applications',
  sms_notifications    TINYINT(1) DEFAULT 0 COMMENT 'Receive SMS alerts',
  application_updates  TINYINT(1) DEFAULT 1 COMMENT 'Email on application status change',
  marketing_emails     TINYINT(1) DEFAULT 0 COMMENT 'Promotional / newsletter emails',
  language          VARCHAR(10)  DEFAULT 'en',
  timezone          VARCHAR(50)  DEFAULT 'Africa/Johannesburg',
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS site_settings (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  setting_key       VARCHAR(100) NOT NULL UNIQUE,
  setting_value     TEXT,
  description       VARCHAR(255) DEFAULT NULL,
  updated_by        INT DEFAULT NULL COMMENT 'admin ID who last changed this',
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (updated_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add notification columns to admins table
-- (errors are caught by migrations/run.php and skipped if column already exists)
ALTER TABLE admins ADD COLUMN email_notifications TINYINT(1) DEFAULT 1;
ALTER TABLE admins ADD COLUMN login_alerts TINYINT(1) DEFAULT 1;

-- Default site settings
INSERT IGNORE INTO site_settings (setting_key, setting_value, description) VALUES
  ('site_name',        'Lotoks',           'Public site / brand name'),
  ('site_tagline',     'Your Gateway to Global Opportunities', 'Homepage tagline'),
  ('contact_email',    'support@lotoks.co.za', 'Public contact email'),
  ('applications_open', '1',                'Are new applications being accepted (1=yes, 0=no)'),
  ('maintenance_mode',  '0',                'Put the site in maintenance mode'),
  ('registration_open', '1',                'Are new user registrations allowed');
