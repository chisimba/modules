################################# Modules ##############################3

#
# Table structure for table `tbl_modules`
#

CREATE TABLE `tbl_modules` (
   id int(11) NOT NULL auto_increment,
  module_id varchar(50) NOT NULL default '0',
  module_authors text,
  module_releasedate datetime default NULL,
  module_version varchar(20) default NULL,
  module_path varchar(255) default NULL,
  isAdmin tinyint(1) NOT NULL default '0',
  isVisible tinyint(1) NOT NULL default '1',
  hasAdminPage tinyint(1) default '1',
  isContextAware tinyint(1) NOT NULL default '0',
  dependsContext tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=InnoDB;