DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='wscaddress' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='wscaddress' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_wscaddress`;

DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='wscproduct' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='wscproduct' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_wscproduct`;

DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='wscsalesorder' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='wscsalesorder' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_wscsalesorder`;