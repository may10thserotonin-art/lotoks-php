-- ═══════════════════════════════════════════════════════════════
--  Lotoks — MySQL 8 Schema
--  Converted from SQLite (lotoks-backend/src/db.ts)
-- ═══════════════════════════════════════════════════════════════

SET FOREIGN_KEY_CHECKS = 0;

-- Drop existing tables (for fresh install)
DROP TABLE IF EXISTS activity_log;
DROP TABLE IF EXISTS requirements;
DROP TABLE IF EXISTS languages;
DROP TABLE IF EXISTS config;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS listings;
DROP TABLE IF EXISTS user_documents;
DROP TABLE IF EXISTS applications;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS admins;

SET FOREIGN_KEY_CHECKS = 1;

-- ── admins ──────────────────────────────────────────────────────
CREATE TABLE admins (
  id                   INT AUTO_INCREMENT PRIMARY KEY,
  email                VARCHAR(255) NOT NULL UNIQUE,
  name                 VARCHAR(255) DEFAULT '',
  password_hash        VARCHAR(255) NOT NULL,
  role                 ENUM('super_admin','admin') NOT NULL DEFAULT 'admin',
  verified             TINYINT(1) NOT NULL DEFAULT 1,
  verification_token   VARCHAR(255) DEFAULT NULL,
  reset_token          VARCHAR(255) DEFAULT NULL,
  reset_token_expires  DATETIME DEFAULT NULL,
  created_at           DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ── activity_log ──────────────────────────────────────────────────
CREATE TABLE activity_log (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  user_id          INT DEFAULT NULL,
  admin_id         INT DEFAULT NULL,
  action           VARCHAR(100) NOT NULL DEFAULT '',
  description      TEXT DEFAULT NULL,
  ip_address       VARCHAR(45) DEFAULT NULL,
  created_at       DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
);

-- ── users ───────────────────────────────────────────────────────
CREATE TABLE users (
  id                   INT AUTO_INCREMENT PRIMARY KEY,
  name                 VARCHAR(255) NOT NULL DEFAULT '',
  email                VARCHAR(255) NOT NULL UNIQUE,
  country              VARCHAR(255) DEFAULT '',
  verified             TINYINT(1) NOT NULL DEFAULT 0,
  password_hash        VARCHAR(255) DEFAULT NULL,
  reset_token          VARCHAR(255) DEFAULT NULL,
  reset_token_expires  DATETIME DEFAULT NULL,
  created_at           DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ── applications ────────────────────────────────────────────────
CREATE TABLE applications (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  user_id          INT DEFAULT NULL,
  applicant_name   VARCHAR(255) NOT NULL DEFAULT '',
  email            VARCHAR(255) NOT NULL DEFAULT '',
  country          VARCHAR(255) DEFAULT '',
  sponsorship_type VARCHAR(255) DEFAULT '',
  service_types    JSON DEFAULT (JSON_ARRAY()),
  status           ENUM('submitted','under_review','approved','rejected','more_info') NOT NULL DEFAULT 'submitted',
  documents        JSON DEFAULT (JSON_ARRAY()),
  personal_info    JSON DEFAULT (JSON_OBJECT()),
  answers          JSON DEFAULT (JSON_OBJECT()),
  requirements     JSON DEFAULT (JSON_ARRAY()),
  job_category     VARCHAR(255) DEFAULT '',
  note             TEXT DEFAULT NULL,
  created_at       DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at       DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  admin_notes      TEXT DEFAULT NULL,
  reviewed_by      INT DEFAULT NULL,
  reviewed_at      TIMESTAMP DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (reviewed_by) REFERENCES admins(id) ON DELETE SET NULL
);

-- ── user_documents ───────────────────────────────────────────────
CREATE TABLE user_documents (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT NOT NULL,
  name        VARCHAR(255) NOT NULL DEFAULT '',
  filename    VARCHAR(255) NOT NULL DEFAULT '',
  filepath    VARCHAR(500) NOT NULL DEFAULT '',
  filesize    INT NOT NULL DEFAULT 0,
  mime_type   VARCHAR(100) DEFAULT '',
  category    VARCHAR(100) DEFAULT '',
  verified    TINYINT(1) NOT NULL DEFAULT 0,
  created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ── listings ─────────────────────────────────────────────────────
CREATE TABLE listings (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  title            VARCHAR(255) NOT NULL DEFAULT '',
  employer         VARCHAR(255) DEFAULT '',
  description      TEXT DEFAULT NULL,
  country          VARCHAR(255) DEFAULT '',
  sponsorship_type VARCHAR(255) DEFAULT '',
  salary_range     VARCHAR(255) DEFAULT '',
  requirements     TEXT DEFAULT NULL,
  active           TINYINT(1) NOT NULL DEFAULT 1,
  applicants       INT NOT NULL DEFAULT 0,
  type             VARCHAR(50) DEFAULT 'job',
  created_at       DATETIME DEFAULT CURRENT_TIMESTAMP,
  deleted_at       DATETIME DEFAULT NULL
);

-- ── payments ─────────────────────────────────────────────────────
CREATE TABLE payments (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  transaction_id   VARCHAR(255) NOT NULL UNIQUE,
  applicant_name   VARCHAR(255) DEFAULT '',
  amount           DECIMAL(10,2) NOT NULL DEFAULT 0,
  currency         VARCHAR(10) NOT NULL DEFAULT 'USD',
  gateway          VARCHAR(50) NOT NULL DEFAULT 'card',
  status           ENUM('success','pending','failed','refunded') NOT NULL DEFAULT 'pending',
  created_at       DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ── config ───────────────────────────────────────────────────────
CREATE TABLE config (
  `key`        VARCHAR(255) PRIMARY KEY,
  `value`      TEXT NOT NULL DEFAULT '',
  group_name   VARCHAR(100) NOT NULL DEFAULT 'General',
  label        VARCHAR(255) DEFAULT '',
  type         VARCHAR(50) DEFAULT 'text'
);

-- ── languages ────────────────────────────────────────────────────
CREATE TABLE languages (
  code          VARCHAR(10) PRIMARY KEY,
  translations  JSON NOT NULL DEFAULT (JSON_OBJECT())
);

-- ── requirements ─────────────────────────────────────────────────
CREATE TABLE requirements (
  service_type  VARCHAR(100) PRIMARY KEY,
  categories    JSON NOT NULL DEFAULT (JSON_ARRAY())
);

-- ════════════════════════════════════════════════════════════════
--  SEED DATA
-- ════════════════════════════════════════════════════════════════

-- Default super_admin (password: admin123)
-- bcrypt hash of 'admin123' with cost 10
INSERT INTO admins (email, name, password_hash, role, verified)
VALUES (
  'admin@lotoks.com',
  'Admin',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'super_admin',
  1
);

-- Default config values
INSERT INTO config (`key`, `value`, group_name, label, type) VALUES
('site_name',        'Lotoks',                          'General', 'Site Name',           'text'),
('site_email',       'info@lotoks.co.za',               'General', 'Contact Email',        'email'),
('site_phone',       '+27 11 051 8583',                 'General', 'Phone Number',         'text'),
('whatsapp_number',  '+48790733839',                    'General', 'WhatsApp Number',      'text'),
('maintenance_mode', '0',                               'General', 'Maintenance Mode',     'boolean'),
('application_fee',  '150',                             'Payment', 'Application Fee (USD)','number');

-- Default language (English)
INSERT INTO languages (code, translations)
VALUES ('en', JSON_OBJECT(
  'nav.home', 'Home',
  'nav.about', 'About Us',
  'nav.services', 'Our Services',
  'nav.testimonials', 'Testimonials',
  'nav.contact', 'Contact Us',
  'btn.apply', 'Apply Now',
  'btn.check_eligibility', 'Check Eligibility'
));

-- Default document requirements per service type
-- Items are JSON objects with: name, desc, required, accept
INSERT INTO requirements (service_type, categories) VALUES
('visa', JSON_ARRAY(
  JSON_OBJECT('name', 'Core Documents', 'items', JSON_ARRAY(
    JSON_OBJECT('name', 'International Passport (Biodata Page)', 'desc', 'Scanned copy of your passport biodata page',                         'required', true,  'accept', '.pdf,.jpg,.jpeg,.png'),
    JSON_OBJECT('name', 'Passport-Sized Photograph (white background)', 'desc', 'Recent passport-sized photograph on white background',          'required', true,  'accept', '.jpg,.jpeg,.png'),
    JSON_OBJECT('name', 'Bank Statements (last 3-6 months)', 'desc', 'Official bank statements showing sufficient funds',                      'required', true,  'accept', '.pdf,.jpg,.jpeg,.png'),
    JSON_OBJECT('name', 'Travel Itinerary / Invitation Letter', 'desc', 'Flight booking, travel plan, or host invitation letter',               'required', false, 'accept', '.pdf,.jpg,.jpeg,.png')
  ))
)),
('job', JSON_ARRAY(
  JSON_OBJECT('name', 'Core Documents', 'items', JSON_ARRAY(
    JSON_OBJECT('name', 'International Passport (Biodata Page)', 'desc', 'Scanned copy of your passport biodata page',                         'required', true,  'accept', '.pdf,.jpg,.jpeg,.png'),
    JSON_OBJECT('name', 'Passport-Sized Photograph', 'desc', 'Recent passport-sized photograph (white background)',                            'required', true,  'accept', '.jpg,.jpeg,.png'),
    JSON_OBJECT('name', 'CV / Resume (Updated)', 'desc', 'Your updated curriculum vitae or resume',                                           'required', true,  'accept', '.pdf,.doc,.docx'),
    JSON_OBJECT('name', 'Professional Certifications', 'desc', 'Copies of relevant certifications and licenses',                              'required', false, 'accept', '.pdf,.jpg,.jpeg,.png'),
    JSON_OBJECT('name', 'Reference Letters', 'desc', 'Professional reference letters from previous employers',                               'required', false, 'accept', '.pdf,.doc,.docx')
  ))
)),
('edu', JSON_ARRAY(
  JSON_OBJECT('name', 'Core Documents', 'items', JSON_ARRAY(
    JSON_OBJECT('name', 'International Passport (Biodata Page)', 'desc', 'Scanned copy of your passport biodata page',                         'required', true,  'accept', '.pdf,.jpg,.jpeg,.png'),
    JSON_OBJECT('name', 'Academic Transcripts', 'desc', 'Official transcripts from your previous institution',                                'required', true,  'accept', '.pdf,.jpg,.jpeg,.png'),
    JSON_OBJECT('name', 'Recommendation Letters (minimum 2)', 'desc', 'Academic recommendation letters (minimum 2)',                           'required', true,  'accept', '.pdf,.doc,.docx'),
    JSON_OBJECT('name', 'CV / Resume', 'desc', 'Your academic curriculum vitae',                                                              'required', true,  'accept', '.pdf,.doc,.docx'),
    JSON_OBJECT('name', 'English Proficiency Test Score (IELTS/TOEFL)', 'desc', 'IELTS, TOEFL, or PTE score report',                           'required', false, 'accept', '.pdf,.jpg,.jpeg,.png')
  ))
)),
('pr', JSON_ARRAY(
  JSON_OBJECT('name', 'Core Documents', 'items', JSON_ARRAY(
    JSON_OBJECT('name', 'International Passport (Biodata Page)', 'desc', 'Scanned copy of your passport biodata page',                         'required', true,  'accept', '.pdf,.jpg,.jpeg,.png'),
    JSON_OBJECT('name', 'Birth Certificate', 'desc', 'Official birth certificate',                                                            'required', true,  'accept', '.pdf,.jpg,.jpeg,.png'),
    JSON_OBJECT('name', 'Police Clearance Certificate', 'desc', 'Police clearance from your country of residence',                            'required', true,  'accept', '.pdf,.jpg,.jpeg,.png'),
    JSON_OBJECT('name', 'Medical Examination Report', 'desc', 'Medical report from an approved physician',                                   'required', true,  'accept', '.pdf,.jpg,.jpeg,.png'),
    JSON_OBJECT('name', 'Marriage Certificate (if applicable)', 'desc', 'If applicable',                                                       'required', false, 'accept', '.pdf,.jpg,.jpeg,.png')
  ))
));
