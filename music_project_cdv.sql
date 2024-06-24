create database music_project;

use music_project;

-- Jin
create table members(
id int primary key auto_increment,
firstname varchar(50),
lastname varchar(50),
email varchar(50),
passwords varchar(30),
phone_number varchar(20),
address varchar(50)
);

create table empolyees(
id int primary key auto_increment,
firstname varchar(50),
lastname varchar(50),
email varchar(50),
passwords varchar(30),
phone_number varchar(20)
);

-- paomin
create table aclass (
id int auto_increment primary key,
class varchar(100) not null
);

create table nclass (
id int auto_increment primary key,
class varchar(50)
);

-- Ming
create table ticket_status(
id int primary key auto_increment,
t_status varchar(50)
);

-- Alice
create table suppliers (
  id int primary key auto_increment,
  company_name varchar(100),
  contact_name varchar(100),
  contact_title varchar(50),
  address varchar(200),
  city_or_county varchar(100),
  postal_code int,
  phone int,
  fax int,
  email varchar(200)
);

-- Daniel
CREATE TABLE artist(
id INT AUTO_INCREMENT PRIMARY KEY,
art_name varchar(50),
followers INT,
introduction text,
debutDate DATETIME,
album varchar(50),
albumDate datetime
);

-- paomin
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
    picture varchar(500),
    foreign key (activity_class) references aclass(id),
    foreign key (artist_id) references artist(id)
);

-- 下面是新增的
create table banner (
id int auto_increment primary key,
activities_id int,
picture varchar(200) not null,
class varchar(100) not null,
foreign key (activities_id) references activities(id)
);

create table notification (
id int auto_increment primary key,
title varchar(50) not null,
content varchar(100) not null,
sent_time datetime not null,
noti_class int not null,
foreign key (noti_class) references nclass(id)
);



create table user_notification (
id int auto_increment primary key,
members_id int,
notification_id int,
isread tinyint not null default 0,
accept_time datetime default now(),
foreign key (members_id) references members(id),
foreign key (notification_id) references notification(id)
);

-- Ming
create table ticket(
id int not null primary key auto_increment,
activities_id int not null,
ticket_area varchar(50),
counts int not null,
price int not null,
foreign key (activities_id) references activities(id)
);

create table ticket_order(
id int not null primary key auto_increment,
user_id int not null,
ticket_id int not null,
ticket_status int not null,
counts int,
foreign key (id) references ticket(id),
foreign key (user_id) references members(id),
foreign key (ticket_id) references ticket(id)
);

-- Alice 商品
create table products (
  id int not null primary key auto_increment,
  product_name varchar(100),
  unit_price int not null,   #單價
  units_instock int,   #庫存
  units_on_order int,  #被訂購的量
  supplier_id int,
  activitie_id int,
  created_at datetime not null default now(), #輸入時間 
  foreign key (supplier_id) references suppliers(id),
  foreign key (activitie_id) references activities(id)
);

-- 訂單
create table order_details (
  order_id int not null primary key auto_increment,
  product_id int not null,
  members_id int not null,
  unit_price int,
  quantity int,
  discount int,
  created_at datetime not null default now(), #輸入時間
  foreign key (product_id) references products(id),
  foreign key (members_id) references members(id)
);

-- 商品收藏(連接會員?)
create table product_collection(
  id int not null primary key auto_increment,
  members_id int not null,
  product_id int not null,
  foreign key (members_id) references members(id),  
  foreign key (product_id) references products(id)
);