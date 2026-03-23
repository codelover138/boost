CREATE TABLE IF NOT EXISTS `boost_payment_settings` (
  `id` INT(11) NOT NULL,
  `merchant_id` VARCHAR(100) DEFAULT NULL,
  `merchant_key` VARCHAR(100) DEFAULT NULL,
  `passphrase` VARCHAR(255) DEFAULT NULL,
  `test_mode` TINYINT(1) NOT NULL DEFAULT 1,
  `debug_email` VARCHAR(150) DEFAULT NULL,
  `trial_days` INT(11) NOT NULL DEFAULT 45,
  `grace_period_days` INT(11) NOT NULL DEFAULT 7,
  `default_plan_code` VARCHAR(50) DEFAULT NULL,
  `plans_json` LONGTEXT NULL,
  `itn_valid_hosts_json` LONGTEXT NULL,
  `date_created` DATETIME NULL,
  `date_modified` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- If your existing table was created before `trial_days` was introduced,
-- run this once before the INSERT/UPDATE below:
-- ALTER TABLE `boost_payment_settings`
--   ADD COLUMN `trial_days` INT(11) NOT NULL DEFAULT 45 AFTER `debug_email`;

INSERT INTO `boost_payment_settings` (
  `id`,
  `merchant_id`,
  `merchant_key`,
  `passphrase`,
  `test_mode`,
  `debug_email`,
  `trial_days`,
  `grace_period_days`,
  `default_plan_code`,
  `plans_json`,
  `itn_valid_hosts_json`,
  `date_created`,
  `date_modified`
) VALUES (
  1,
  '10046720',
  '3svxw4eas1vel',
  'boostaccounting',
  1,
  'news@boostaccounting.com',
  45,
  7,
  'boost-monthly',
  '{\"boost-monthly\":{\"code\":\"boost-monthly\",\"name\":\"Boost Monthly Subscription\",\"description\":\"Monthly access to Boost Cloud Accounting\",\"amount\":\"60.00\",\"currency\":\"ZAR\",\"cycle_days\":30},\"boost-yearly\":{\"code\":\"boost-yearly\",\"name\":\"Boost Yearly Subscription\",\"description\":\"Yearly access to Boost Cloud Accounting\",\"amount\":\"600.00\",\"currency\":\"ZAR\",\"cycle_days\":365}}',
  '[\"www.payfast.co.za\",\"sandbox.payfast.co.za\",\"w1w.payfast.co.za\",\"w2w.payfast.co.za\"]',
  NOW(),
  NOW()
)
ON DUPLICATE KEY UPDATE
  `merchant_id` = VALUES(`merchant_id`),
  `merchant_key` = VALUES(`merchant_key`),
  `passphrase` = VALUES(`passphrase`),
  `test_mode` = VALUES(`test_mode`),
  `debug_email` = VALUES(`debug_email`),
  `trial_days` = VALUES(`trial_days`),
  `grace_period_days` = VALUES(`grace_period_days`),
  `default_plan_code` = VALUES(`default_plan_code`),
  `plans_json` = VALUES(`plans_json`),
  `itn_valid_hosts_json` = VALUES(`itn_valid_hosts_json`),
  `date_modified` = NOW();
