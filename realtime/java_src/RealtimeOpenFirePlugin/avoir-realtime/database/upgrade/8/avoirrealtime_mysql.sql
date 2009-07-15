UPDATE ofVersion  set version =8 where name= 'avoirrealtime';
ALTER TABLE ofAvoirRealtime_SlideShows add ACTIVE varchar(5);
ALTER TABLE ofAvoirRealtime_SlideShows add ACCESS_MODE varchar(7);


