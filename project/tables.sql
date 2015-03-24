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
  quantity            integer,
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
  location      varchar(32),
  blocked       boolean,
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
  quantity        integer,
  primary key (cookieName, ingredientName),
  foreign key (cookieName) references CookieTypes(name),
  foreign key (ingredientName) references Ingredients(name)
);

create table CookieTypesInOrders (
  cookieName  varchar(64),
  orderNbr    integer,
  quantity    integer,
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
values('Flour','1000','2015-01-01','5000');

insert into Customers(name,address)
values('Victor Miller','Delphi'),
      ('Johannes Jansson','Vildanden');

insert into Orders(deliveryDate,customerName)
values('2015-05-01','Victor Miller'),
      ('2015-05-23','Johannes Jansson');

insert into Pallets(barcode,location,blocked,producedDate,producedTime,cookieName)
values('1337','Freezer','0','2015-03-01','12:00:00','Tango'),
      ('1338','Freezer','0','2015-03-01','12:10:00','Tango'),
      ('1339','Freezer','0','2015-03-01','12:15:00','Tango'),
      ('1340','Freezer','0','2015-03-01','12:20:00','Berliner'),
      ('1341','Freezer','0','2015-03-01','12:25:00','Berliner'),
      ('1342','Freezer','0','2015-03-01','12:30:00','Berliner'),
      ('1343','Freezer','0','2015-03-01','12:35:00','Berliner'),
      ('1344','Freezer','0','2015-03-01','12:40:00','Berliner'),
      ('1345','Freezer','0','2015-03-01','12:45:00','Berliner'),
      ('1346','Freezer','0','2015-03-01','12:50:00','Berliner'),
      ('1347','Freezer','0','2015-03-01','12:55:00','Berliner'),
      ('1348','Freezer','0','2015-03-01','13:00:00','Nut Cookie'),
      ('1349','Freezer','0','2015-03-01','13:10:00','Nut Cookie'),
      ('1350','Freezer','0','2015-03-01','13:15:00','Almond Delight'),
      ('1351','Freezer','0','2015-03-01','13:20:00','Tango'),
      ('1352','Freezer','0','2015-03-01','13:25:00','Tango'),
      ('1353','Freezer','0','2015-03-01','13:30:00','Tango'),
      ('1354','Freezer','0','2015-03-01','13:35:00','Tango');
insert into Recipes(cookieName,ingredientName,quantity)
values('Berliner','Flour','100');

insert into CookieTypesInOrders(cookieName,orderNbr,quantity)
values('Berliner','1','10');

insert into LoadingInstructions(orderNbr,barcode)
values('1','1337');
