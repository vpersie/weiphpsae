DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='ml_microcatering_set' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='ml_microcatering_set' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_ml_microcatering_set`;
DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='ml_microcatering_dishes' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='ml_microcatering_dishes' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_ml_microcatering_dishes`;
DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='ml_microcatering_scheduledtask' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='ml_microcatering_scheduledtask' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_ml_microcatering_scheduledtask`;
DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='ml_microcatering_tablemanage' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='ml_microcatering_tablemanage' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_ml_microcatering_tablemanage`;
DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='ml_microcatering_yuyuemanage' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='ml_microcatering_yuyuemanage' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_ml_microcatering_yuyuemanage`;
DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='ml_microcatering_dishes_type' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='ml_microcatering_dishes_type' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_ml_microcatering_dishes_type`;
DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='ml_microcatering_review' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='ml_microcatering_review' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_ml_microcatering_review`;
DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='ml_microcatering_order' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='ml_microcatering_order' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_ml_microcatering_order`;
DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='ml_microcatering_discount_type' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='ml_microcatering_discount_type' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_ml_microcatering_discount_type`;
DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='ml_microcatering_order_temp' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='ml_microcatering_order_temp' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_ml_microcatering_order_temp`;
DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='ml_microcatering_users' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='ml_microcatering_users' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_ml_microcatering_users`;