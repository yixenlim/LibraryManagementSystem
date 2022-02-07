create database library;
use library;
-- drop database library;

-- show tables; 
drop table RESERVATION;
drop table BOOK;
drop table ADMIN;
drop table USER;

Create table ADMIN
(
	Admin_ID char(12) NOT NULL PRIMARY KEY,
	Admin_contact varchar (20),
    Admin_password char(8)
);
insert into ADMIN values
('001222000000','0127470851','bryan'),
('000131000000','0108289371','nicholas');
select * from ADMIN;

Create table USER
(
	User_ID char(12) NOT NULL PRIMARY KEY,
    User_email varchar (50),
	User_contact varchar (20),
    User_password char(8),
    User_account_status varchar(10)
);
insert into USER values
('000916000000','yixenlim@hotmail.com','0108195904','yixen','Active'),
('000319000000','ivanwong@gmail.com','0105133990','ivan','Frozen'),
('000614000000','jitchow@gmail.com','0189144616','jitchow','Active');
select * from USER;

Create table BOOK
(
	Book_ID int NOT NULL AUTO_INCREMENT,
    Book_title varchar (200),
	Book_author varchar (100),
    Book_publisher varchar(100),
    Book_published_year char(4),
    Book_ISBN varchar(15),
    Book_status varchar(20),
    Book_info varchar(5000),
    PRIMARY KEY(Book_ID)
);
insert into BOOK (Book_title,Book_author,Book_publisher,Book_published_year,Book_ISBN,Book_status,Book_info) values
('Harry Potter And The Philosopher\'s Stone (Harry Potter)','J. K. Rowling','Bloomsbury Pub Ltd','2000','9780747532743','Borrowed','Harry Potter is a series of seven fantasy novels written by British author J. K. Rowling. The novels chronicle the lives of a young wizard, Harry Potter, and his friends Hermione Granger and Ron Weasley, all of whom are students at Hogwarts School of Witchcraft and Wizardry.'),
('Harry Potter And The Philosopher\'s Stone (Harry Potter)','J. K. Rowling','Bloomsbury Pub Ltd','2000','9780747532743','Borrowed','Harry Potter is a series of seven fantasy novels written by British author J. K. Rowling. The novels chronicle the lives of a young wizard, Harry Potter, and his friends Hermione Granger and Ron Weasley, all of whom are students at Hogwarts School of Witchcraft and Wizardry.'),
('The Fault in Our Stars','John Green','Puffin Books an imprint of Penguin Books Ltd','2012','9780141345659','Reserved','Despite the tumor-shrinking medical miracle that has bought her a few years, Hazel has never been anything but terminal, her final chapter inscribed upon diagnosis. But when a gorgeous plot twist named Augustus Waters suddenly appears at Cancer Kid Support Group, Hazel\'s story is about to be completely rewritten.'),
('The Fault in Our Stars','John Green','Puffin Books an imprint of Penguin Books Ltd','2012','9780141345659','Available','Despite the tumor-shrinking medical miracle that has bought her a few years, Hazel has never been anything but terminal, her final chapter inscribed upon diagnosis. But when a gorgeous plot twist named Augustus Waters suddenly appears at Cancer Kid Support Group, Hazel\'s story is about to be completely rewritten.');
select * from BOOK;

Create table RESERVATION
(
	Reservation_ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    User_ID char(12),
    Book_ID int,
    Late_fees float,
	Reservation_status varchar(20),
    Reservation_date date,
	Return_date date,
    FOREIGN KEY (User_ID) references USER(User_ID),
    FOREIGN KEY (Book_ID) references BOOK(Book_ID)
);
insert into RESERVATION (User_ID,Book_ID,Late_fees,Reservation_status,Reservation_date,Return_date) values
('000916000000','1',0,'PickedUp','2021-09-12','2021-10-11'),
('000916000000','3',0,'Waiting','2021-10-12','2021-11-11'),
('000319000000','2',27,'Overdue','2021-08-22','2021-09-21');
select * from RESERVATION;