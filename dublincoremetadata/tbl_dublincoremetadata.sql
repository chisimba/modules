<?
$sqldata[]="CREATE TABLE tbl_dublincoremetadata (
  id VARCHAR(32) NOT NULL ,
  provider varchar(255),
  url varchar(255),
  enterdate datetime,
  oai_identifier varchar(255),
  oai_set varchar(255),
  datestamp datetime,
  deleted enum('false', 'true') NOT NULL,
  tbl_context_nodes_id VARCHAR(32) NOT NULL ,
  dc_title VARCHAR(255) NULL ,
  dc_subject VARCHAR(255) NULL ,
  dc_description VARCHAR(255) NULL,
  dc_type VARCHAR(255) NULL,
  dc_source VARCHAR(255) NULL,
  dc_sourceurl VARCHAR(255) NULL,
  dc_relationship VARCHAR(255) NULL,
  dc_coverage VARCHAR(255) NULL,
  dc_creator VARCHAR(255) NULL,
  dc_publisher VARCHAR(255) NULL,
  dc_contributor VARCHAR(255) NULL,
  dc_rights VARCHAR(255) NULL,
  dc_date VARCHAR(255) NULL,
  dc_format VARCHAR(255) NULL,
  dc_identifier VARCHAR(255) NULL,
  dc_language VARCHAR(255) NULL,
  dc_audience VARCHAR(255) NULL,
  PRIMARY KEY(id, tbl_context_nodes_id),
  INDEX tbl_dublincoremetadata_FKIndex1(tbl_context_nodes_id),
  FOREIGN KEY(tbl_context_nodes_id)
    REFERENCES tbl_context_nodes(id)
      ON DELETE CASCADE
      ON UPDATE CASCADE
)
TYPE=InnoDB";
?>