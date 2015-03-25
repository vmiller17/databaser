-- Delete the old tables
set foreign_key_checks = 0;
drop table if exists Pallets;
drop table if exists Locations;
drop table if exists CookieTypes;
drop table if exists Recipes;
drop table if exists Ingredients;
drop table if exists CookieTypesInOrders;
drop table if exists LoadingInstructions;
drop table if exists Orders;
drop table if exists Customers;
set foreign_key_checks = 1;

-- Create the new tables
create table CookieTypes (
  name           varchar(64),
  primary key (name)
);

create table Ingredients (
  name                varchar(64),
  quantity            integer check (quantity >= 0),
  latestDeliveryDate  date,
  latestDeliverySize  integer,
  primary key (name)
);

create table Customers (
  name    varchar(128),
  address varchar(256),
  primary key (name)
);

create table Orders (
  orderNbr      integer auto_increment,
  deliveryDate  date,
  customerName  varchar(128),
  primary key (orderNbr),
  foreign key (customerName) references Customers(name)
);

create table Locations (
  location     varchar(32),
  primary key (location)
);

create table Pallets (
  barcode       integer auto_increment,
  location      varchar(32) default 'Freezer',
  blocked       boolean default 0,
  producedDate  date,
  producedTime  time,
  cookieName    varchar(64),
  primary key (barcode),
  foreign key (cookieName) references CookieTypes(name),
  foreign key (location) references Locations(location)
);

create table Recipes (
  cookieName      varchar(64),
  ingredientName  varchar(64),
  quantity        integer check (quantity >= 0),
  primary key (cookieName, ingredientName),
  foreign key (cookieName) references CookieTypes(name),
  foreign key (ingredientName) references Ingredients(name)
);

create table CookieTypesInOrders (
  cookieName  varchar(64),
  orderNbr    integer,
  quantity    integer check (quantity >= 0),
  primary key (cookieName, orderNbr),
  foreign key (cookieName) references CookieTypes(name),
  foreign key (orderNbr) references Orders(orderNbr)
);

create table LoadingInstructions (
  orderNbr  integer,
  barcode   integer,
  primary key (barcode),
  foreign key (orderNbr) references Orders(orderNbr),
  foreign key (barcode) references Pallets(barcode)
);

alter table `Pallets` AUTO_INCREMENT = 10000;

-- Add data
insert into CookieTypes(name)
values('Nut Ring'),
      ('Nut Cookie'),
      ('Amneris'),
      ('Tango'),
      ('Almond Delight'),
      ('Berliner');

insert into Locations(location)
values('Freezer'),
      ('Delivered'),
      ('On truck'),
      ('Loading');

insert into Ingredients(name,quantity,latestDeliveryDate,latestDeliverySize)
values('Flour','999999','2014-03-25','999999'),
      ('Butter','999999','2014-03-25','999999'),
      ('Icing sugar','999999','2014-03-25','999999'),
      ('Roasted, chopped nuts','999999','2014-03-25','999999'),
      ('Fine-ground nuts','999999','2014-03-25','999999'),
      ('Ground, roasted nuts','999999','2014-03-25','999999'),
      ('Bread crumbs','999999','2014-03-25','999999'),
      ('Sugar','999999','2014-03-25','999999'),
      ('Egg whites','999999','2014-03-25','999999'),
      ('Chocolate','999999','2014-03-25','999999'),
      ('Marzipan','999999','2014-03-25','999999'),
      ('Eggs','999999','2014-03-25','999999'),
      ('Potato starch','999999','2014-03-25','999999'),
      ('Wheat flour','999999','2014-03-25','999999'),
      ('Sodium bicarbonate','999999','2014-03-25','999999'),
      ('Vanilla','999999','2014-03-25','999999'),
      ('Chopped almonds','999999','2014-03-25','999999'),
      ('Cinnamon','999999','2014-03-25','999999'),
      ('Vanilla sugar','999999','2014-03-25','999999');

insert into Customers(name,address)
values('Finkakor AB'   ,'Helsingborg'),
      ('Småbröd AB'    ,'Malmö'),
      ('Kaffebröd AB'  ,'Landskrona'),
      ('Bjudkakor AB'  ,'Ystad'),
      ('Kalaskakor AB' ,'Trelleborg'),
      ('Partykakor AB' ,'Kristianstad'),
      ('Gästkakor AB'  ,'Hässleholm'),
      ('Skånekakor AB' ,'Perstorp');

insert into Orders(deliveryDate,customerName)
values('2015-05-01','Finkakor AB'),
      ('2015-05-23','Partykakor AB');

insert into Pallets(location,blocked,producedDate,producedTime,cookieName)
values('Freezer','0','2015-03-01','12:00:00','Tango'),
      ('Freezer','0','2015-03-01','12:10:00','Tango'),
      ('Freezer','0','2015-03-01','12:15:00','Tango'),
      ('Freezer','0','2015-03-01','12:20:00','Berliner'),
      ('Freezer','0','2015-03-01','12:25:00','Berliner'),
      ('Freezer','0','2015-03-01','12:30:00','Berliner'),
      ('Freezer','0','2015-03-01','12:35:00','Berliner'),
      ('Freezer','0','2015-03-01','12:40:00','Berliner'),
      ('Freezer','1','2015-03-01','12:45:00','Berliner'),
      ('Freezer','1','2015-03-01','12:50:00','Berliner'),
      ('Freezer','1','2015-03-01','12:55:00','Berliner'),
      ('Freezer','0','2015-03-01','13:00:00','Nut Cookie'),
      ('Freezer','0','2015-03-01','13:10:00','Nut Cookie'),
      ('Freezer','0','2015-03-01','13:15:00','Almond Delight'),
      ('Freezer','0','2015-03-01','13:20:00','Tango'),
      ('Freezer','0','2015-03-01','13:25:00','Tango'),
      ('Freezer','0','2015-03-01','13:30:00','Tango'),
      ('Freezer','0','2015-03-01','13:35:00','Tango');

insert into Recipes(cookieName,ingredientName,quantity)
values('Nut ring','Flour','450'),
      ('Nut ring','Butter','450'),
      ('Nut ring','Icing sugar','190'),
      ('Nut ring','Roasted, chopped nuts','225'),
      ('Nut cookie','Fine-ground nuts','750'),
      ('Nut cookie','Ground, roasted nuts','625'),
      ('Nut cookie','Bread crumbs','125'),
      ('Nut cookie','Sugar','375'),
      ('Nut cookie','Egg whites','361'),
      ('Nut cookie','Chocolate','50'),
      ('Amneris','Marzipan','750'),
      ('Amneris','Butter','250'),
      ('Amneris','Eggs','250'),
      ('Amneris','Potato starch','25'),
      ('Amneris','Wheat flour','25'),
      ('Tango','Butter','200'),
      ('Tango','Sugar','250'),
      ('Tango','Flour','300'),
      ('Tango','Sodium bicarbonate','4'),
      ('Tango','Vanilla','2  '),
      ('Almond delight','Butter','400'),
      ('Almond delight','Sugar','270'),
      ('Almond delight','Chopped almonds','279'),
      ('Almond delight','Flour','400'),
      ('Almond delight','Cinnamon','10'),
      ('Berliner','Flour','350'),
      ('Berliner','Butter','250'),
      ('Berliner','Icing sugar  ','100'),
      ('Berliner','Eggs','50 '),
      ('Berliner','Vanilla sugar','5'),
      ('Berliner','Chocolate','50');

insert into CookieTypesInOrders(cookieName,orderNbr,quantity)
values('Berliner','1','10');

insert into LoadingInstructions(orderNbr,barcode)
values('1','10003');

