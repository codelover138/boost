ALTER TABLE `boost_organisations`
ADD COLUMN `trial_ends_at` TIMESTAMP NULL DEFAULT NULL,
ADD COLUMN `subscription_status` VARCHAR(20) DEFAULT 'trial',
ADD COLUMN `grace_period_ends_at` TIMESTAMP NULL DEFAULT NULL,
ADD COLUMN `is_manual_blocked` TINYINT(1) DEFAULT 0,
ADD COLUMN `manual_block_reason` VARCHAR(255) NULL,
ADD COLUMN `paid_until` TIMESTAMP NULL DEFAULT NULL;
