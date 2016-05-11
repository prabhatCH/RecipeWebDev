DROP TABLE IF EXISTS `#__beestowf_bookmarks`;
DROP TABLE IF EXISTS `#__beestowf_comments`;
DROP TABLE IF EXISTS `#__beestowf_files`;
DROP TABLE IF EXISTS `#__beestowf_notes`;
DROP TABLE IF EXISTS `#__beestowf_projects`;
DROP TABLE IF EXISTS `#__beestowf_project_workflow`;
DROP TABLE IF EXISTS `#__beestowf_project_workflow_steps`;
DROP TABLE IF EXISTS `#__beestowf_tasks`;
DROP TABLE IF EXISTS `#__beestowf_users`;


CREATE TABLE IF NOT EXISTS `#__beestowf_bookmarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(1024) NOT NULL COMMENT 'http://',
  `owner` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__beestowf_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'project(0),task(1)',
  `comment` varchar(1024) NOT NULL COMMENT 'task details',
  `parent` int(11) NOT NULL DEFAULT '0' COMMENT 'project/task ID where should appear',
  `added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `owner` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__beestowf_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'project(0),task(1)',
  `parent` int(11) NOT NULL DEFAULT '0' COMMENT 'project/task ID where should appear',
  `name` varchar(255) NOT NULL DEFAULT '',
  `filetype` varchar(255) NOT NULL DEFAULT '',
  `location` varchar(255) NOT NULL DEFAULT '',
  `added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `owner` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__beestowf_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `comment` text NOT NULL COMMENT 'task details',
  `category` varchar(255) NOT NULL DEFAULT '',
  `added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `owner` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__beestowf_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `client` int(11) NOT NULL DEFAULT '0',
  `client_view` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `manager` int(11) NOT NULL DEFAULT '0',
  `start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `due` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `completed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `project_template` int(11) NOT NULL DEFAULT '0' COMMENT 'workflow id',
  `params` text NOT NULL,
  `checked_out` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__beestowf_project_workflow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '(dependent tasks = 0; normal tasks = 1)',
  `owner` int(11) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__beestowf_project_workflow_steps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_workflow` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `due` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `assigned_to` text NOT NULL,
  `priority` tinyint(1) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__beestowf_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(1024) NOT NULL COMMENT 'task details',
  `start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `due` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `assigned_to` text NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `priority` tinyint(1) NOT NULL DEFAULT '0',
  `completed` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0' COMMENT '= workflow steps order',
  `checked_out` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__beestowf_users` (
  `id` int(11) NOT NULL COMMENT 'joomla ID',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `department` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `company_name` varchar(255) NOT NULL DEFAULT '',
  `company_address` varchar(255) NOT NULL DEFAULT '',
  `company_city` varchar(255) NOT NULL DEFAULT '',
  `company_zip` varchar(100) NOT NULL DEFAULT '',
  `company_country` varchar(100) NOT NULL DEFAULT '',
  `company_email` varchar(255) NOT NULL DEFAULT '',
  `company_phone` varchar(255) NOT NULL DEFAULT '',
  `company_fax` varchar(255) NOT NULL DEFAULT '',
  `company_website` varchar(255) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `checked_out` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
