create database music_project;

use music_project;

create table artist_register(
id int not null auto_increment primary key,
artist_name varchar(50),
email varchar(50),
passwords varchar(30),
phone_number varchar(20),
introduction text,
debutDate DATETIME,
ManagementCompany varchar(30)
);

select * from artist_register;