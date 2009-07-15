UPDATE ofVersion  set version =1 where name= 'avoirrealtime';

CREATE TABLE ofAvoirRealtime_Classroom_SlideShows(
file_path varchar(255) primary key,
room_name varchar(128)
);