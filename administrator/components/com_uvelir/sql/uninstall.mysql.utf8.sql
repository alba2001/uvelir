DELETE FROM `#__menu_types` WHERE `menutype` = 'com_uvelir';
DELETE FROM `#__menu` WHERE `menutype` = 'com_uvelir';
DROP TABLE IF EXISTS `#__uvelir_users`;
DROP TABLE IF EXISTS `#__uvelir_order_statuses`;
DROP TABLE IF EXISTS `#__uvelir_orders`;
DROP TABLE IF EXISTS `#__uvelir_products_1`;
DROP TABLE IF EXISTS `#__uvelir_products_2`;
DROP TABLE IF EXISTS `#__uvelir_categories`;

