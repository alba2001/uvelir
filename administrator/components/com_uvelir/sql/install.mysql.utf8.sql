DROP TABLE IF EXISTS `#__uvelir_users`;
CREATE TABLE `#__uvelir_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT 'ИД пользователя в системе',
  `user_type_id` int(2) NOT NULL COMMENT 'Тип клиента',
  `fam` varchar(25) NOT NULL COMMENT 'Фамилия',
  `im` varchar(25) NOT NULL COMMENT 'Имя',
  `ot` varchar(25) NOT NULL COMMENT 'Отчество',
  `address` varchar(100) NOT NULL COMMENT 'Почтовый адрес',
  `phone` varchar(20) NOT NULL COMMENT 'Телефон',
  `email` varchar(70) NOT NULL COMMENT 'E-mail',
   PRIMARY KEY  (`id`),
   KEY `uid` (`uid`),
   KEY `user_type_id` (`user_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT 'Клиенты';


INSERT INTO `#__menu_types` (`menutype`,`title`,`description`) VALUES
        ('com_uvelir','Ювелир','Меню для магазина ювелирных изделий');

DROP TABLE IF EXISTS `#__uvelir_products_1`;
CREATE TABLE IF NOT EXISTS `#__uvelir_products_1` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`menu_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`category_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`name` VARCHAR(255)  NOT NULL ,
`desc` text  NOT NULL ,
`artikul` VARCHAR(255)  NOT NULL ,
`material` VARCHAR(255)  NOT NULL ,
`proba` VARCHAR(20)  NOT NULL ,
`average_weight` DECIMAL(10,2)  NOT NULL ,
`vstavki` VARCHAR(255)  NOT NULL ,
`cena_mag` DECIMAL(15,2)  NOT NULL ,
`cena_tut` DECIMAL(15,2)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
KEY `category_id` (`category_id`),
KEY `menu_id` (`menu_id`),
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `#__uvelir_products_2`;
CREATE TABLE IF NOT EXISTS `#__uvelir_products_2` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`menu_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`category_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`name` VARCHAR(255)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`path` VARCHAR(255)  NOT NULL ,
`desc` text  NOT NULL ,
`artikul` VARCHAR(255)  NOT NULL ,
`material` VARCHAR(255)  NOT NULL ,
`proba` VARCHAR(20)  NOT NULL ,
`average_weight` DECIMAL(10,2)  NOT NULL ,
`vstavki` VARCHAR(255)  NOT NULL ,
`opisanije` VARCHAR(255)  NOT NULL ,
`cena_mag` DECIMAL(15,2)  NOT NULL ,
`cena_tut` DECIMAL(15,2)  NOT NULL ,
`level` INT(3)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
KEY `category_id` (`category_id`),
KEY `menu_id` (`menu_id`),
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `#__uvelir_categories`;
CREATE TABLE IF NOT EXISTS `#__uvelir_categories` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`parent_id` int(11) UNSIGNED NOT NULL,
`zavod` int(2) UNSIGNED NOT NULL,
`name` VARCHAR(255)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`path` VARCHAR(255)  NOT NULL ,
`img` VARCHAR(255)  NOT NULL ,
`note` VARCHAR(255)  NOT NULL ,
`desc` TEXT  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`level` INT(2)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
KEY `name` (`name`),
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `#__uvelir_zavods`;
CREATE TABLE IF NOT EXISTS `#__uvelir_zavods` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`name` VARCHAR(255)  NOT NULL ,
`base_url` VARCHAR(255)  NOT NULL ,
`products` TEXT  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
KEY `name` (`name`),
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

INSERT INTO `#__uvelir_zavods` (`id`, `name`, `base_url`, `products`, `ordering`, `state`, `checked_out`, `checked_out_time`, `created_by`) VALUES
(1, 'Ювелиры Урала', 'http://ju-ur.ru', 'Кольца', 1, 1, 0, '0000-00-00 00:00:00', 42),
(2, 'Атолл, г. Новосибирск', 'http://www.atollnsk.ru/', 'Кольца^http://www.atollnsk.ru/rings.html;\r\nСерьги^http://www.atollnsk.ru/earrings.html', 2, 1, 0, '0000-00-00 00:00:00', 42);

