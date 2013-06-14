CREATE TABLE IF NOT EXISTS `#__chronoforms` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`form_type` tinyint(1) NOT NULL,
	`content` longtext NOT NULL,
	`wizardcode` longtext,
	`events_actions_map` longtext,
	`params` longtext NOT NULL,
	`published` tinyint(1) NOT NULL DEFAULT '1',
	`app` varchar(100) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`)
) CHARACTER SET `utf8`;
CREATE TABLE IF NOT EXISTS `#__chronoform_actions` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`chronoform_id` int(11) NOT NULL,
	`type` varchar(255) NOT NULL,
	`enabled` tinyint(1) NOT NULL,
	`params` longtext NOT NULL,
	`order` int(11) NOT NULL,
	`content1` longtext NOT NULL,
	PRIMARY KEY (`id`)
) CHARACTER SET `utf8`;