set foreign_key_checks = 0;
drop table if exists PersonPhones;
set foreign_key_checks = 1;

create table PersonPhones (
  name varchar(20),
  phone varchar(20),
  primary key (name)
);

insert into PersonPhones(name, phone)
values
  ('Alice',   '123456'),
  ('Bea',     '123457'),
  ('Cecilia', '123458'),
  ('Doris',   '123459'),
  ('Emma',    '123455'),
  ('Felicia', '123454');
