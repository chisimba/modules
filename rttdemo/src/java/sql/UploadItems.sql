CREATE TABLE IF NOT EXISTS rttdemo_uploaditems (
  id int(11) NOT NULL AUTO_INCREMENT,
  filename char(50) NOT NULL,
  date_uploaded timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  filepath varchar(100) NOT NULL,
  usertype varchar(10) NOT NULL,
  PRIMARY KEY (id)
);