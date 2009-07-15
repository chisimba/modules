UPDATE ofVersion  set version =16 where name= 'avoirrealtime';

ALTER TABLE ofAvoirRealtime_Rooms add room_member_username varchar(128);
ALTER TABLE ofAvoirRealtime_Rooms add room_member_name varchar(128);
