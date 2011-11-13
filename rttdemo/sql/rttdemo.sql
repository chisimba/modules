CREATE TABLE `users` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `title` varchar(32) DEFAULT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `emailaddress` varchar(255) NOT NULL,
  `telphone` varchar(32) DEFAULT NULL,
  `cellphone` varchar(32) DEFAULT NULL,
  `sex` varchar(1) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  `sipnumber` int(11) DEFAULT NULL,
  `extpin` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `authorities` (
  `username` varchar(50) NOT NULL,
  `authority` varchar(50) NOT NULL,
  UNIQUE KEY `ix_auth_username` (`username`,`authority`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE rttdemo_jnlp(
 `username` varchar(50) NOT NULL,
 jnlp_key varchar(512),
 jnlp_value text,
  createdon date);
