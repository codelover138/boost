-- Add payfast_token to subscription_payments to track the recurring subscription token
-- PayFast returns this token in ITN for each recurring charge
ALTER TABLE `boost_subscription_payments`
    ADD COLUMN IF NOT EXISTS `payfast_token` VARCHAR(100) DEFAULT NULL AFTER `payfast_reference`;

-- Add payfast_token to organisations to link the active PayFast recurring subscription
ALTER TABLE `boost_organisations`
    ADD COLUMN IF NOT EXISTS `payfast_token` VARCHAR(100) DEFAULT NULL AFTER `cancellation_requested_at`;
