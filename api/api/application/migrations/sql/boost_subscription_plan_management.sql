ALTER TABLE `boost_organisations`
  ADD COLUMN `current_plan_code` VARCHAR(50) NULL AFTER `paid_until`,
  ADD COLUMN `pending_plan_code` VARCHAR(50) NULL AFTER `current_plan_code`,
  ADD COLUMN `cancel_at_period_end` TINYINT(1) NOT NULL DEFAULT 0 AFTER `pending_plan_code`,
  ADD COLUMN `cancellation_requested_at` DATETIME NULL AFTER `cancel_at_period_end`;

-- Optional backfill:
-- Set `current_plan_code` from the most recent completed subscription payment
-- for organisations that already have paid subscription history.
UPDATE `boost_organisations` o
JOIN (
  SELECT sp.organisation_id, sp.plan_code
  FROM `boost_subscription_payments` sp
  JOIN (
    SELECT organisation_id, MAX(id) AS max_id
    FROM `boost_subscription_payments`
    WHERE payment_status = 'complete'
    GROUP BY organisation_id
  ) latest
    ON latest.organisation_id = sp.organisation_id
   AND latest.max_id = sp.id
) p
  ON p.organisation_id = o.id
SET o.current_plan_code = p.plan_code
WHERE o.current_plan_code IS NULL;

-- Rollback SQL:
-- ALTER TABLE `boost_organisations`
--   DROP COLUMN `cancellation_requested_at`,
--   DROP COLUMN `cancel_at_period_end`,
--   DROP COLUMN `pending_plan_code`,
--   DROP COLUMN `current_plan_code`;
