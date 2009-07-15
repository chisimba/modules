UPDATE ofVersion  set version =15 where name= 'avoirrealtime';

CREATE TABLE ofAvoirRealtime_ShortUrls(
id varchar(12) primary key,
roomname varchar(128),
longurl varchar(1024));
