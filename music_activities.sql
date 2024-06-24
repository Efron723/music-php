create database music_project;
use music_project;

create table activities (
    id int auto_increment primary key,
    activity_class int,
    activity_name varchar(100) not null,
    a_date date not null,
    a_time time not null,
    location varchar(100) not null,
    descriptions text,
    organizer varchar(100) not null,
    artist_id int,
    foreign key (activity_class) references aclass(id),
    foreign key (artist_id) references artist(id)
);

create table aclass (
id int auto_increment primary key,
class varchar(100) not null
);

desc activities;
show warnings;

-- 下面是新增的
create table banner (
id int auto_increment primary key,
picture varchar(200) not null
);

create table notification (
id int auto_increment primary key,
title varchar(50) not null,
content varchar(100) not null,
sent_time time not null,
noti_class int not null,
foreign key (noti_class) references nclass(id)
);

create table nclass (
id int auto_increment primary key,
class varchar(50)
);

create table user_notification (
id int auto_increment primary key,
members_id int,
notification_id int,
isread tinyint not null default 0,
accept_time datetime default now(),
foreign key (member_id) references members(id),
foreign key (notification_id) references notification(id)
);