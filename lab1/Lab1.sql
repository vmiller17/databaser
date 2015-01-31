-- Task 3 a: What are the names(fist name, last name) of all students? [72]
-- mysql db30 -p -h puccini.cs.lth.se db30

select firstName, lastName
from students;

-- 3 b (sorted)
select firstName, lastName
from students
order by lastName;

select firstName, lastName
from students
order by firstName;

-- 3 c 
select pNbr
from students
where pNbr like '85%';

-- 3 d
select firstName, lastName, pNbr
from students
where mod(substr(pNbr,10,1),2) = 0;

-- 3 e
select count(*)
from students;

-- 3 f
select courseCode
from Courses
where courseCode like 'FMA%';

-- 3 g
select courseCode, credits
from Courses
where credits > 7.5;

-- 3 h
-- Level G1 (rest is similar)
select count(*) 
from Courses
where level = 'G1';

-- 3 i 
select courseCode
from TakenCourses
where pNbr = '910101-1234';

-- 3 j
select courseName, credits
from TakenCourses, Courses
where TakenCourses.pNbr = '910101-1234' 
and TakenCourses.courseCode = Courses.courseCode;

-- 3 k
select sum(credits)
from TakenCourses, Courses
where pNbr = '910101-1234' 
and TakenCourses.courseCode = Courses.courseCode;

-- 3 k
select avg(grade)
from TakenCourses
where pNbr = '910101-1234';

-- 3 m
-- (i)
select courseCode
from TakenCourses, students
where firstName = 'Peter' and lastName = 'Solberg'
and TakenCourses.pNbr = students.pNbr;

-- (j)
select courseName, credits
from TakenCourses, Courses, students
where firstName = 'Eva' and lastName = 'Alm'
and TakenCourses.courseCode = Courses.courseCode
and TakenCourses.pNbr = students.pNbr;

-- (k)
select sum(credits)
from TakenCourses, Courses, students
where firstName = 'Eva' and lastName = 'Alm'
and TakenCourses.courseCode = Courses.courseCode
and TakenCourses.pNbr = students.pNbr;

-- (l) Not nessessay 

-- 3 n
select *
from students
where pNbr not in (select pNbr from TakenCourses);

-- 3 o
create view AvgScore as
	select pNbr, avg(grade) avgrade
	from TakenCourses
	group by pNbr;

select pNbr, avgrade
from AvgScore
order by avgrade DESC;

-- 3 p
create view TotScore as
	select pNbr, sum(credits) totCred
	from TakenCourses, Courses
	where TakenCourses.courseCode = Courses.courseCode
	group by pNbr;

select students.pNbr, firstName, lastName, coalesce(totCred,0) 
from students left outer join TotScore on students.pNbr = TotScore.pNbr
order by totCred DESC;

-- 3 q
select s1.pNbr, s1.firstName, s1.lastName
from students s1, students s2
where s1.firstName = s2.firstName and s1.lastName = s2.lastName 
and s1.pNbr <> s2.pNbr; -- Not uniquely. FIX!!







