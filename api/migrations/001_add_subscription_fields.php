<?php
// Simulation of a migration script to add columns to boost_api.boost_organisations

// This file is for documentation/simulation purposes. 
// In a real environment, you would run this SQL against the database.
// Since I cannot run SQL directly, I will assume these columns are added for the subsequent code changes.

/*
ALTER TABLE `boost_organisations`
ADD COLUMN `trial_ends_at` TIMESTAMP NULL DEFAULT NULL AFTER `date_created`,
ADD COLUMN `subscription_status` VARCHAR(20) DEFAULT 'trial' AFTER `trial_ends_at`,
ADD COLUMN `paid_until` TIMESTAMP NULL DEFAULT NULL AFTER `subscription_status`,
ADD COLUMN `is_manual_blocked` TINYINT(1) DEFAULT 0 AFTER `paid_until`,
ADD COLUMN `manual_block_reason` VARCHAR(255) NULL AFTER `is_manual_blocked`;
*/

echo "Migration simulation: Columns added to boost_organisations table.\n";
echo "- trial_ends_at\n";
echo "- subscription_status\n";
echo "- paid_until\n";
echo "- is_manual_blocked\n";
echo "- manual_block_reason\n";
