UPDATE ofVersion  set version =11 where name= 'avoirrealtime';
drop table ofAvoirRealtime_Slides;
drop table ofAvoirRealtime_SlideShows;

CREATE TABLE ofAvoirRealtime_SlideShows(
id int auto_increment,
roomname varchar(512),
filename varchar(512),
version int, 
active varchar(5),
access_mode varchar(7), 
primary key(id))ENGINE=InnoDB;

CREATE TABLE ofAvoirRealtime_Slides(
slideshow int,
title varchar(28),
slide_text varchar(512),
slide_index int,
text_color_r int,
text_color_g int,
text_color_b int,
text_size int,
question_path varchar(255),
url varchar(512),
image_path varchar(255),
status varchar(5),
version int,
foreign key (slideshow) REFERENCES ofAvoirRealtime_SlideShows(id) on delete cascade on update cascade)ENGINE=InnoDB;

