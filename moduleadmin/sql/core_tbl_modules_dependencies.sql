#
# Table structure for table `tbl_modules_dependencies`
#

CREATE TABLE `tbl_modules_dependencies` (
  `id` int(11) NOT NULL auto_increment,
  `module_id` varchar(50) default NULL,
  `dependency` varchar(50) default NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`dependency`)
) TYPE=InnoDB  AUTO_INCREMENT=1 ;
