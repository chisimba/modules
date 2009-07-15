UPDATE ofVersion  set version =12 where name= 'avoirrealtime';

CREATE TABLE ofAvoirRealtime_Rooms(
id int auto_increment,
room_name varchar(512),
room_owner varchar(512),
room_desc varchar(512),
room_type varchar(20),
primary key(id))ENGINE=InnoDB;

