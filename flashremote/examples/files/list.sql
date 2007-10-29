CREATE TABLE list (
  PkProduct int(11) NOT NULL auto_increment,
  Name varchar(255) NOT NULL default '',
  Weigth varchar(10) NOT NULL default '',
  Price varchar(10) NOT NULL default '',
  PRIMARY KEY (PkProduct)
) TYPE=MyISAM;

#
# Dumping data for table `list`
#

INSERT INTO list VALUES (1, 'Handmade spicy sausage', '2.00', '5.20');
INSERT INTO list VALUES (2, '\'Gran Vela\' spicy sausage', '9.90', '7.10');
INSERT INTO list VALUES (3, 'Spicy sausage with pepper', '6.40', '7.20');
INSERT INTO list VALUES (4, '\'Red Label\' spicy sausage', '9.90', '7.60');
INSERT INTO list VALUES (5, 'Home-made spicy sausage', '3.00', '5.30');
INSERT INTO list VALUES (6, 'Home-made extra spicy sausage', '3.00', '4.50');
INSERT INTO list VALUES (7, '\'Pamplona\' spicy sausage', '4.00', '6.00');
INSERT INTO list VALUES (8, 'Extra quality spicy sausage 1 kg', '6.00', '8.20');
INSERT INTO list VALUES (9, '\'Velita\' extra quality sausage', '3.00', '9.00');
INSERT INTO list VALUES (10, '\'Cardenal\' extra quality sausage', '6.80', '8.65');