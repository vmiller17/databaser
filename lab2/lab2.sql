--
-- a) Create the tables
-- 

-- Delete the tables...
set foreign_key_checks = 0;
drop table if exists Users;
drop table if exists Movies;
drop table if exists Theaters;
drop table if exists Performances;
drop table if exists Reservations;
set foreign_key_checks = 1;

-- Create the tables
create table Users (
  username        char(20),
  fullName        char(40),
  address         varchar(256),
  telephoneNbr    char(20),
  primary key (username)
);

create table Movies (
  title           varchar(128),
  primary key (title)
);

create table Theaters (
  name     varchar(128),
  nbrOfSeats      integer,
  primary key (name)
);

create table Performances (
  date            date,
  bookings        integer default 0,
  movieTitle      varchar(128),
  theaterName     varchar(128),    
  primary key (date, movieTitle),
  foreign key (movieTitle) references Movies(title),
  foreign key (theaterName) references Theaters(name)
  -- on delete set null
  -- on update cascade
);

create table Reservations (
  resNbr                  integer auto_increment,
  userUsername            char(20),
  performanceDate         date,
  performanceMovieTitle   varchar(128),
  primary key (resNbr),
  foreign key (userUsername) references Users(username),
  foreign key (performanceDate) references Performances(date),
  foreign key (performanceMovieTitle) references Performances(movieTitle)
);

-- Insert data into Users
insert into Users(username, fullName, address, telephoneNbr)
values('zicvic',  'Victor',   'Lund',   '123');

insert into Users(username, fullName, address, telephoneNbr)
values('jojo',    'Johannes', 'Lund',   '123');

insert into Users(username, fullName, address, telephoneNbr)
values('fru',     'Carro',    'Lund',   '123'),
      ('granne',  'Mateusz',  'Lund',   '456'); 



-- Insert data into Movies
insert into Movies(title)
  values
    ('James Bond'   ),
    ('1984'         ),
    ('Jaws'         ),
    ('Star Wars I'  ),
    ('Star Wars III'),
    ('About Time'   );



-- Insert data into Theaters
insert into Theaters(name, nbrOfSeats)
values
  ('SF',         300),
  ('Vildanden',  2);

-- Insert data into Performances
insert into Performances(date, movieTitle, theaterName)
values('2015-2-3', 'James Bond', 'SF');

insert into Performances(date, movieTitle, theaterName)
values('2015-2-3', 'Jaws', 'Vildanden');

insert into Performances(date, movieTitle, theaterName)
values('2015-2-5', 'James Bond', 'Vildanden');



--
-- b)
--

-- List all movies
select * from Movies;

-- List dates when a movie is shown
select date
from Performances
where movieTitle = 'James Bond';

-- List all data concerning a movie performance
select date, bookings, movieTitle, theaterName, nbrOfSeats
from Performances, Theaters
where theaterName = name 
and date = '2015-02-03' 
and movieTitle = 'James Bond';


--
-- c)
-- 

-- Insert reservations
insert into Reservations(userUsername, performanceDate, performanceMovieTitle)
values('zicvic', '2015-2-3', 'James Bond');
insert into Reservations(userUsername, performanceDate, performanceMovieTitle)
values('zicvic', '2015-2-3', 'James Bond');
insert into Reservations(userUsername, performanceDate, performanceMovieTitle)
values('fru',    '2015-2-5', 'James Bond');

-- 
-- 8
-- 

-- insert two movie theaters with the same name
insert into Theaters(name,nbrOfSeats)
  values ('SF', 200);
/*--insert into Movies(title)
--values('James Bond');

-- insert two performances of the 
-- same movie on the same date
insert into Performances(date, movieTitle, theaterName)
values('2015-2-3', 'James Bond', 'Vildanden');


-- insert a performance where the theater 
-- doesn’t exist in the database
insert into Performances(date, movieTitle, theaterName)
values('2015-3-3', 'Jaws', 'Delphi');

-- insert a ticket reservation where either 
-- the user or the performance doesn’t exist
insert into Reservations(userUsername, performanceDate, performanceMovieTitle)
values('Jultomten', '2015-2-3', 'James Bond');

insert into Reservations(userUsername, performanceDate, performanceMovieTitle)
values('zicvic', '2015-2-3', '1984');

-- username not unique
insert into Users(username, fullName, address, telephoneNbr)
values('fru',  'Victor',   'Lund',   '123');

-- (only the address is optional, others must be assigned) tk

--
-- 9
--

-- Since there's a time gap between checking available
-- spots and actually making the reservation, it's 
-- possible that the performances could be over filled.*/
