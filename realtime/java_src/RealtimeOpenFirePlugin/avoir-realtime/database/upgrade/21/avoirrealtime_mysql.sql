UPDATE ofVersion  set version =21 where name= 'avoirrealtime';
ALTER TABLE ofAvoirRealtime_OnlineUsers drop column access_level;
ALTER TABLE ofAvoirRealtime_OnlineUsers drop column has_mic;
