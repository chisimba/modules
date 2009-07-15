UPDATE ofVersion  set version =2 where name= 'avoirrealtime';

ALTER TABLE ofAvoirRealtime_Classroom_SlideShows add version int;
UPDATE ofAvoirRealtime_Classroom_SlideShows set version = 0;