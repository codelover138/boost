CREATE TABLE IF NOT EXISTS `boost_subscription_payments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` INT(11) NOT NULL,
  `user_id` INT(11) DEFAULT NULL,
  `account_name` VARCHAR(50) NOT NULL,
  `plan_code` VARCHAR(50) NOT NULL,
  `item_name` VARCHAR(150) NOT NULL,
  `merchant_reference` VARCHAR(100) NOT NULL,
  `payment_status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `amount_gross` DECIMAL(10,2) NOT NULL,
  `amount_fee` DECIMAL(10,2) DEFAULT NULL,
  `amount_net` DECIMAL(10,2) DEFAULT NULL,
  `billing_date_from` DATETIME DEFAULT NULL,
  `billing_date_to` DATETIME DEFAULT NULL,
  `payfast_payment_id` VARCHAR(100) DEFAULT NULL,
  `payfast_reference` VARCHAR(100) DEFAULT NULL,
  `payment_method` VARCHAR(50) DEFAULT NULL,
  `signature` VARCHAR(255) DEFAULT NULL,
  `itn_verified` TINYINT(1) NOT NULL DEFAULT 0,
  `raw_request_data` LONGTEXT DEFAULT NULL,
  `raw_itn_data` LONGTEXT DEFAULT NULL,
  `failure_reason` VARCHAR(255) DEFAULT NULL,
  `confirmed_at` DATETIME DEFAULT NULL,
  `itn_received_at` DATETIME DEFAULT NULL,
  `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `boost_subscription_events` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` INT(11) NOT NULL,
  `payment_id` INT(11) DEFAULT NULL,
  `event_type` VARCHAR(50) NOT NULL,
  `message` VARCHAR(255) DEFAULT NULL,
  `payload` LONGTEXT DEFAULT NULL,
  `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);
