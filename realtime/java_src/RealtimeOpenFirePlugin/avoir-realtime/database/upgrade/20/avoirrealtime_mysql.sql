UPDATE ofVersion  set version =20 where name= 'avoirrealtime';
ALTER TABLE ofAvoirRealtime_OnlineUsers add permissions varchar(20) Default "";
