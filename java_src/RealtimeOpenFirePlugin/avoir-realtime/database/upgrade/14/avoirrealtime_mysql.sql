UPDATE ofVersion  set version =14 where name= 'avoirrealtime';

CREATE TABLE ofAvoirRealtime_EC2Instances(
username varchar(128),
roomname varchar(128),
property_key varchar(512),
property_value varchar(512),
property_domain varchar(12));
