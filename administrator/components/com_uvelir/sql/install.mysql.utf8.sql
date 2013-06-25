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

DROP TABLE IF EXISTS `#__uvelir_orders`;
CREATE TABLE `#__uvelir_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oplata_id` int(2) NOT NULL COMMENT 'Способ оплаты',
  `dostavka_id` int(2) NOT NULL COMMENT 'Способ доставки',
  `userid` int(11) NOT NULL COMMENT 'ИД пользователя в системе',
  `order_status_id` int(2) NOT NULL COMMENT 'Статус заказа',
  `order_dt` datetime NOT NULL COMMENT 'Дата и время заказа',
  `sum` DECIMAL(15,2)  NOT NULL COMMENT 'Сумма заказа',
  `caddy` text NOT NULL COMMENT 'Детали заказа',
  `ch_status` text NOT NULL COMMENT 'Инф. об изменении заказа',
  `checked_out` INT(11)  NOT NULL ,
  `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_dt` DATE NOT NULL DEFAULT '0000-00-00',
  `created_by` INT(11)  NOT NULL ,

   PRIMARY KEY  (`id`),
   KEY `userid` (`userid`),
   KEY `order_status_id` (`order_status_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT 'Заказы';

DROP TABLE IF EXISTS `#__uvelir_order_statuses`;
CREATE TABLE `#__uvelir_order_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT 'Наименование статуза заказа',
   PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT 'Статусы заказа';

INSERT INTO `#__uvelir_order_statuses` (`id`, `name`) VALUES
(1, 'Начальный'),
(2, 'Оплачен'),
(3, 'Отгружен'),
(4, 'Доставлен');


INSERT INTO `#__menu_types` (`menutype`,`title`,`description`) VALUES
        ('com_uvelir','Ювелир','Меню для магазина ювелирных изделий');

DROP TABLE IF EXISTS `#__uvelir_products`;
CREATE TABLE IF NOT EXISTS `#__uvelir_products` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`category_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`zavod_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`name` VARCHAR(255)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`desc` text  NOT NULL ,
`artikul` VARCHAR(255)  NOT NULL ,
`material` VARCHAR(255)  NOT NULL ,
`proba` VARCHAR(20)  NOT NULL ,
`average_weight` DECIMAL(10,2)  NOT NULL ,
`vstavki` VARCHAR(255)  NOT NULL ,
`opisanije` VARCHAR(255)  NOT NULL ,
`razmer` VARCHAR(255)  NOT NULL ,
`cena_mag` DECIMAL(15,2)  NOT NULL ,
`cena_tut` DECIMAL(15,2)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`spets_predl` TINYINT(1)  NOT NULL DEFAULT '0',
`available` TINYINT(1)  NOT NULL DEFAULT '0'  COMMENT 'Детали заказа',
`novinka_dt` DATE NOT NULL DEFAULT '0000-00-00',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_dt` DATE NOT NULL DEFAULT '0000-00-00',
`created_by` INT(11)  NOT NULL ,
KEY `category_id` (`category_id`),
KEY `zavod_id` (`zavod_id`),
KEY `alias` (`alias`),
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `#__uvelir_categories`;
CREATE TABLE IF NOT EXISTS `#__uvelir_categories` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`parent_id` int(11) UNSIGNED NOT NULL,
`producttype_id` int(11) UNSIGNED NOT NULL,
`lft` int(11) NOT NULL DEFAULT '0',
`rgt` int(11) NOT NULL DEFAULT '0',
`level` INT(10)  NOT NULL ,
`title` VARCHAR(255)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`access` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
`path` VARCHAR(255)  NOT NULL ,
`zavod` int(2) UNSIGNED NOT NULL,
`name` VARCHAR(255)  NOT NULL ,
`source_url` VARCHAR(255)  NOT NULL ,
`img` VARCHAR(255)  NOT NULL ,
`note` VARCHAR(255)  NOT NULL ,
`desc` TEXT  NOT NULL ,
`description` TEXT  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
KEY `name` (`name`),
KEY `title` (`title`),
KEY `idx_left_right` (`lft`,`rgt`),
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;
INSERT INTO `#__uvelir_categories` SET parent_id = 0, lft = 0, rgt = 1, level = 0, title = 'root', alias = 'root', access = 1, path = '';

DROP TABLE IF EXISTS `#__uvelir_producttypes`;
CREATE TABLE IF NOT EXISTS `#__uvelir_producttypes` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`name` VARCHAR(255)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`cena_mag` DECIMAL(15,2)  NOT NULL ,
`cena_tut` DECIMAL(15,2)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
KEY `name` (`name`),
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `#__uvelir_productvids`;
CREATE TABLE IF NOT EXISTS `#__uvelir_productvids` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`title` VARCHAR(255)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`sizes` VARCHAR(255)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
KEY `alias` (`alias`),
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

INSERT INTO `#__uvelir_productvids` (`id`, `title`, `alias`, `sizes`, `state`, `checked_out`, `checked_out_time`, `created_by`) VALUES
(1, 'Кольца', 'koltsa', '10:20', 1, 0, '0000-00-00 00:00:00', 386),
(2, 'Серьги', 'sergi', '', 1, 0, '0000-00-00 00:00:00', 386),
(3, 'Броши', 'broshi', '', 1, 0, '0000-00-00 00:00:00', 386),
(4, 'Подвеска', 'podveska', '', 1, 0, '0000-00-00 00:00:00', 386),
(5, 'Браслеты', 'braslety', '', 1, 0, '0000-00-00 00:00:00', 386),
(6, 'Колье', 'kole', '', 1, 0, '0000-00-00 00:00:00', 386),
(7, 'Цепи', 'tsepi', '', 1, 0, '0000-00-00 00:00:00', 386),
(8, 'Сувениры', 'suveniry', '', 1, 0, '0000-00-00 00:00:00', 386),
(9, 'Иконы', 'ikony', '', 1, 0, '0000-00-00 00:00:00', 386),
(10, 'Пирсинг', 'pirsing', '', 1, 0, '0000-00-00 00:00:00', 386);



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
`state` TINYINT(1)  NOT NULL DEFAULT '1',
KEY `name` (`name`),
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

INSERT INTO `#__uvelir_zavods` (`id`, `name`, `base_url`, `products`, `ordering`, `state`, `checked_out`, `checked_out_time`, `created_by`) VALUES
(1, 'Ювелиры Урала', 'http://ju-ur.ru', 'Кольца', 1, 1, 0, '0000-00-00 00:00:00', 42),
(2, 'Атолл, г. Новосибирск', 'http://www.atollnsk.ru/', 'Кольца^http://www.atollnsk.ru/rings.html;\r\nСерьги^http://www.atollnsk.ru/earrings.html', 2, 1, 0, '0000-00-00 00:00:00', 42);

DROP TABLE IF EXISTS `#__uvelir_oplata`;
CREATE TABLE IF NOT EXISTS `#__uvelir_oplata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `#__uvelir_oplata` (`id`, `name`) VALUES
(1, 'Наличными при получении'),
(2, 'Банковскими картами, электронными деньгами');

DROP TABLE IF EXISTS `#__uvelir_dostavka`;
CREATE TABLE IF NOT EXISTS `#__uvelir_dostavka` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `#__uvelir_dostavka` (`id`, `name`) VALUES
(1, 'Курьером (только для Тюмени)'),
(2, 'Доставка СПСР (по всей России)');

