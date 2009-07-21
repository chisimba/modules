UPDATE ofVersion  set version =19 where name= 'avoirrealtime';
ALTER TABLE ofAvoirRealtime_OnlineUsers add access_level int Default 3;
