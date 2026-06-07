-- Migration 001: Add suspended column to users table
-- Run this to enable suspend/unsuspend functionality for user accounts.
ALTER TABLE users ADD COLUMN suspended TINYINT(1) NOT NULL DEFAULT 0 AFTER verified;
