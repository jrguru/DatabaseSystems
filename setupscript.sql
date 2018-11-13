
CREATE DATABASE IF NOT EXISTS ucf;
USE ucf;

CREATE TABLE IF NOT EXISTS University_Profiles (
  University_ID INT AUTO_INCREMENT,
  University_Name varchar(20),
  Num_Students INT,
  University_photo blob,
  Super_ID INT,
  University_Location varchar(60),
  Description varchar(400),
  PRIMARY KEY (University_ID)
  
);

CREATE TABLE IF NOT EXISTS Events (
   Event_ID INT AUTO_INCREMENT,
   Description varchar(100),
   Start_Time datetime,
  End_Time datetime,
  Event_Location ENUM('Student Union', 'Library', 'Arena', 'ClassRoom1', 'ClassRoom2'),
  Contact_Email varchar(40),
  Contact_Phone varchar(20),
  Event_Name varchar(40),
  Event_Type ENUM('Social', 'Sports', 'Fundraising', 'Tech talk'),
  Event_Visible ENUM('Pri', 'Pub', 'RSO'),
  Admin_ID INT,
  RSO_ID INT,
  University_ID INT, 
  PRIMARY KEY (Event_ID)
);

CREATE TABLE IF NOT EXISTS RSO (
  RSO_ID INT AUTO_INCREMENT,
  RSO_Name varchar(20),
  Admin_ID INT,
  Status ENUM('active', 'inactive'),
  PRIMARY KEY (RSO_ID)
  
);

CREATE TABLE IF NOT EXISTS Admin (
  Admin_ID INT AUTO_INCREMENT,
  User_Name varchar(20),
  University_ID INT,
  PRIMARY KEY (Admin_ID)
 
);

CREATE TABLE IF NOT EXISTS Users (
  User_Name varchar(20),
  First_Name varchar(20),
  Last_Name varchar(20),
  Password varchar(20),
  Super_ID INT,
  Admin_ID INT,
  Student_ID INT,
  PRIMARY KEY (User_Name)
  
);

CREATE TABLE IF NOT EXISTS Student_RSO_List (
  Student_ID INT,
  RSO_ID INT,
  PRIMARY KEY (Student_ID, RSO_ID)
);

CREATE TABLE IF NOT EXISTS Student (
  Student_ID INT AUTO_INCREMENT,
  User_Name varchar(20),
  University_ID INT,
  PRIMARY KEY (Student_ID)
  
);

CREATE TABLE IF NOT EXISTS Super_Users (
  Super_ID INT AUTO_INCREMENT,
  User_Name varchar(20),
  PRIMARY KEY (Super_ID)
 
);

CREATE TABLE IF NOT EXISTS Comments (
  Comment_ID INT AUTO_INCREMENT,
  Event_ID INT,
  Student_ID INT,
  Comments VARCHAR(100),
  Stars INT,
  PRIMARY KEY (Comment_ID)
  
);

/*Alter tables to create foreign keys*/
ALTER TABLE Users ADD FOREIGN KEY (Super_ID) REFERENCES Super_Users(Super_ID) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE Users ADD FOREIGN KEY (Admin_ID) REFERENCES Admin(Admin_ID) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE Users ADD FOREIGN KEY (Student_ID) REFERENCES Student(Student_ID) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE Super_Users ADD FOREIGN KEY (User_Name) REFERENCES Users(User_Name) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE Admin ADD FOREIGN KEY (User_Name) REFERENCES Users(User_Name) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE Admin ADD FOREIGN KEY (University_ID) REFERENCES University_Profiles(University_ID) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE Student ADD FOREIGN KEY (User_Name) REFERENCES Users(User_Name) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE Student ADD FOREIGN KEY (University_ID) REFERENCES University_Profiles(University_ID) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE RSO ADD FOREIGN KEY (Admin_ID) REFERENCES Admin(Admin_ID) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE University_Profiles ADD FOREIGN KEY (Super_ID) REFERENCES Super_Users(Super_ID) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE Events ADD FOREIGN KEY (Admin_ID) REFERENCES Admin(Admin_ID) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE Events ADD FOREIGN KEY (RSO_ID) REFERENCES RSO(RSO_ID) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE Events ADD FOREIGN KEY (University_ID) REFERENCES University_Profiles(University_ID) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE Comments ADD FOREIGN KEY (Event_ID) REFERENCES Events(Event_ID) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE Comments ADD FOREIGN KEY (Student_ID) REFERENCES Student(Student_ID) ON DELETE RESTRICT ON UPDATE CASCADE;


											/*CREATE OUR SUPERUSER*/
INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('jrayfield', 'Jamison', 'Rayfield', 'veteran');
INSERT INTO Super_Users(User_Name) VALUES ('jrayfield');
UPDATE Users SET Super_ID = (SELECT Super_ID FROM Super_Users WHERE User_Name = 'jrayfield') WHERE User_name = 'jrayfield';
SELECT * FROM Super_Users;

											/*CREATE SOME UNIVERSITIES*/
INSERT INTO University_Profiles (University_Name, Num_Students, University_location, Description ) VALUES 
('UCF', 65000, 'Orlando', 'The fastest growing school in the nation');


INSERT INTO University_Profiles (University_Name, Num_Students, University_location, Description) VALUES 
('USF', 55000, 'Tampa', 'Has a terrible football team and they smell bad');


INSERT INTO University_Profiles (University_Name, Num_Students, University_location, Description ) VALUES 
('FAU', 35000, 'Miami', 'Joey Freshwater has a new home.');

UPDATE University_Profiles SET Super_ID = (SELECT Super_ID FROM Super_Users WHERE User_Name = 'jrayfield') WHERE University_Name = 'UCF';
UPDATE University_Profiles SET Super_ID = (SELECT Super_ID FROM Super_Users WHERE User_Name = 'jrayfield') WHERE University_Name = 'USF';
UPDATE University_Profiles SET Super_ID = (SELECT Super_ID FROM Super_Users WHERE User_Name = 'jrayfield') WHERE University_Name = 'FAU';

SELECT * FROM University_Profiles;



											/*USERS THAT ARE ADMINS*/
/*we have to create the admin, update the user table, update the admin table with the university ID they are associated with*/


INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('srayfield', 'Stephanie', 'Rayfield', 'veteran');
INSERT INTO Admin(User_Name) VALUES ('srayfield');
UPDATE Users SET Admin_ID = (SELECT Admin_ID FROM Admin WHERE User_Name = 'srayfield') WHERE User_name = 'srayfield';
UPDATE Admin SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'UCF') WHERE User_Name = 'srayfield';


INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('brayfield', 'Beverly', 'Rayfield', 'veteran');
INSERT INTO Admin(User_Name) VALUES ('brayfield');
UPDATE Users SET Admin_ID = (SELECT Admin_ID FROM Admin WHERE User_Name = 'brayfield') WHERE User_name = 'brayfield';
UPDATE Admin SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'USF') WHERE User_Name = 'brayfield';

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('mrayfield', 'Margaret', 'Rayfield', 'veteran');
INSERT INTO Admin(User_Name) VALUES ('mrayfield');
UPDATE Users SET Admin_ID = (SELECT Admin_ID FROM Admin WHERE User_Name = 'mrayfield') WHERE User_name = 'mrayfield';
UPDATE Admin SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'FAU') WHERE User_Name = 'mrayfield';


SELECT * FROM Admin;	

												/*Create some RSO's*/
										/*An RSO must have an associated Admin*/				
INSERT INTO RSO(RSO_Name, Status, Admin_ID) VALUES ('Tau Kappa Epsilon', 'active', (SELECT Admin_ID FROM Admin WHERE User_Name = 'srayfield'));
INSERT INTO RSO(RSO_Name, Status, Admin_ID) VALUES ('UCF Swim Team', 'active', (SELECT Admin_ID FROM Admin WHERE User_Name = 'srayfield'));
INSERT INTO RSO(RSO_Name, Status, Admin_ID) VALUES ('UCF Programming Team', 'active', (SELECT Admin_ID FROM Admin WHERE User_Name = 'srayfield'));

INSERT INTO RSO(RSO_Name, Status, Admin_ID) VALUES ('Tau Delta Tau', 'active', (SELECT Admin_ID FROM Admin WHERE User_Name = 'brayfield'));
INSERT INTO RSO(RSO_Name, Status, Admin_ID) VALUES ('USF Chess Team', 'active', (SELECT Admin_ID FROM Admin WHERE User_Name = 'brayfield'));

INSERT INTO RSO(RSO_Name, Status, Admin_ID) VALUES ('FAU Rugby Team', 'active', (SELECT Admin_ID FROM Admin WHERE User_Name = 'mrayfield'));

SELECT * FROM RSO;
										
										/*USERS THAT ARE STUDENTS*/
										
										/*insert a new user to the user table*/
										/*insert the new user if they are a student into the student table*/
										/*update the student table with the university ID of the university they attend*/
										/*update the users table with the student id from the student table for the new student*/
										/*add the student to an existing RSO at his university in the RSO student list*/
INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('jhghar', 'Jaqen', 'Hghar', 'veteran');
INSERT INTO Student(User_Name) VALUES ('jhghar');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'jhghar') WHERE User_name = 'jhghar';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'UCF') WHERE User_Name = 'jhghar';

INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'jhghar'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('jlannister', 'Jaime', 'Lannister', 'veteran');
INSERT INTO Student(User_Name) VALUES ('jlannister');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'jlannister') WHERE User_name = 'jlannister';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'UCF') WHERE User_Name = 'jlannister';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'jlannister'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon'));


INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('jsnow', 'John', 'Snow', 'veteran');
INSERT INTO Student(User_Name) VALUES ('jsnow');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'jsnow') WHERE User_name = 'jsnow';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'USF') WHERE User_Name = 'jsnow';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'jsnow'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Delta Tau'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('estark', 'Eddard', 'Stark', 'veteran');
INSERT INTO Student(User_Name) VALUES ('estark');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'estark') WHERE User_name = 'estark';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'UCF') WHERE User_Name = 'estark';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'estark'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('rbaratheon', 'Robert', 'Baratheon', 'veteran');
INSERT INTO Student(User_Name) VALUES ('rbaratheon');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'rbaratheon') WHERE User_name = 'rbaratheon';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'USF') WHERE User_Name = 'rbaratheon';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'rbaratheon'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Delta Tau'));


INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('cstark', 'Catelyn', 'Stark', 'veteran');
INSERT INTO Student(User_Name) VALUES ('cstark');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'cstark') WHERE User_name = 'cstark';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'UCF') WHERE User_Name = 'cstark';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'cstark'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('dtargaryen', 'Daenerys', 'Targaryen', 'veteran');
INSERT INTO Student(User_Name) VALUES ('dtargaryen');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'dtargaryen') WHERE User_name = 'dtargaryen';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'FAU') WHERE User_Name = 'dtargaryen';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'dtargaryen'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'FAU Rugby Team'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('jmormont', 'Jorah', 'Mormont', 'veteran');
INSERT INTO Student(User_Name) VALUES ('jmormont');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'jmormont') WHERE User_name = 'jmormont';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'UCF') WHERE User_Name = 'jmormont';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'jmormont'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('sstark', 'Sansa', 'Stark', 'veteran');
INSERT INTO Student(User_Name) VALUES ('sstark');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'sstark') WHERE User_name = 'sstark';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'UCF') WHERE User_Name = 'sstark';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'sstark'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('tgreyjoy', 'Theon', 'Greyjoy', 'veteran');
INSERT INTO Student(User_Name) VALUES ('tgreyjoy');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'tgreyjoy') WHERE User_name = 'tgreyjoy';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'USF') WHERE User_Name = 'tgreyjoy';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'tgreyjoy'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Delta Tau'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('jbaratheon', 'Joffrey', 'Baratheon', 'veteran');
INSERT INTO Student(User_Name) VALUES ('jbaratheon');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'jbaratheon') WHERE User_name = 'jbaratheon';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'USF') WHERE User_Name = 'jbaratheon';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'jbaratheon'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Delta Tau'));


INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('sclegane', 'Sandor', 'Clegane', 'veteran');
INSERT INTO Student(User_Name) VALUES ('sclegane');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'sclegane') WHERE User_name = 'sclegane';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'FAU') WHERE User_Name = 'sclegane';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'sclegane'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'FAU Rugby Team'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('kdrogo', 'Khal', 'Drogo', 'veteran');
INSERT INTO Student(User_Name) VALUES ('kdrogo');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'kdrogo') WHERE User_name = 'kdrogo';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'UCF') WHERE User_Name = 'kdrogo';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'kdrogo'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('pbaelish', 'Petyr', 'Baelish', 'veteran');
INSERT INTO Student(User_Name) VALUES ('pbaelish');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'pbaelish') WHERE User_name = 'pbaelish';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'USF') WHERE User_Name = 'pbaelish';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'pbaelish'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Delta Tau'));


INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('dseaworth', 'Davos', 'Seaworth', 'veteran');
INSERT INTO Student(User_Name) VALUES ('dseaworth');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'dseaworth') WHERE User_name = 'dseaworth';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'UCF') WHERE User_Name = 'dseaworth';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'dseaworth'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('bdetarth', 'Brienne', 'deTarth', 'veteran');
INSERT INTO Student(User_Name) VALUES ('bdetarth');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'bdetarth') WHERE User_name = 'bdetarth';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'FAU') WHERE User_Name = 'bdetarth';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'bdetarth'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'FAU Rugby Team'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('starly', 'Samwell', 'Tarly', 'veteran');
INSERT INTO Student(User_Name) VALUES ('starly');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'starly') WHERE User_name = 'starly';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'UCF') WHERE User_Name = 'starly';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'starly'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('tgiantsbane', 'Tormund', 'Giantsbane', 'veteran');
INSERT INTO Student(User_Name) VALUES ('tgiantsbane');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'tgiantsbane') WHERE User_name = 'tgiantsbane';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'FAU') WHERE User_Name = 'tgiantsbane';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'tgiantsbane'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'FAU Rugby Team'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('mtyrell', 'Margaery', 'Tyrell', 'veteran');
INSERT INTO Student(User_Name) VALUES ('mtyrell');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'mtyrell') WHERE User_name = 'mtyrell';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'UCF') WHERE User_Name = 'mtyrell';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'mtyrell'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('rbolton', 'Ramsay', 'Bolton', 'veteran');
INSERT INTO Student(User_Name) VALUES ('rbolton');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'rbolton') WHERE User_name = 'rbolton';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'UCF') WHERE User_Name = 'rbolton';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'rbolton'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon'));

INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('rstark', 'Robert', 'Stark', 'veteran');
INSERT INTO Student(User_Name) VALUES ('rstark');
UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = 'rstark') WHERE User_name = 'rstark';
UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = 'FAU') WHERE User_Name = 'rstark';
INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = 'rstark'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = 'FAU Rugby Team'));

SELECT * FROM Users;
											
										


										/* CREATE SOME EVENTS*/
/*the event has to have an associated admin_ID, but doesnt necessaritly have to be assocaited with an RSO*/

/*UCF Swim Team Event in the Student Union*/
INSERT INTO Events( Description, 
					Start_Time, 
					End_Time, 
					Event_Location, 
					Contact_Email, 
					Contact_Phone, 
					Event_Name, 
					Event_Type, 
					Event_Visible, 
					Admin_ID, 
					RSO_ID,
					University_ID) VALUES
								('UCF Swim Team Annual end of year social', 
								'2018-12-01 12:00:00', 
								'2018-12-01 14:00:00', 
								'Student Union', 
								'admin@ucf.edu', 
								'407-999-9999', 
								'2018 UCF Swim Social', 
								'Social', 
								'RSO', 
								(SELECT Admin_ID FROM RSO WHERE RSO_Name = 'UCF Swim Team'), 
								(SELECT RSO_ID FROM RSO WHERE RSO_Name = 'UCF Swim Team'),
								(SELECT University_ID FROM Admin WHERE Admin_ID = (SELECT Admin_ID FROM RSO WHERE RSO_Name = 'UCF Programming Team' ))
								);

								/*UCF Programming Team event in ClassRoom 1, public event*/								
INSERT INTO Events( Description, 
					Start_Time, 
					End_Time, 
					Event_Location, 
					Contact_Email, 
					Contact_Phone, 
					Event_Name, 
					Event_Type, 
					Event_Visible, 
					Admin_ID, 
					RSO_ID, 
					University_ID) VALUES
								('UCF Programming Team 2018 Tech Talk', 
								'2018-12-10 17:00:00', 
								'2018-12-10 19:00:00', 
								'Student Union', 
								'admin@ucf.edu', 
								'407-111-9999', 
								'2018 UCF Programming Team Tech Talk', 
								'Tech talk', 
								'Pub', 
								(SELECT Admin_ID FROM RSO WHERE RSO_Name = 'UCF Programming Team'), 
								(SELECT RSO_ID FROM RSO WHERE RSO_Name = 'UCF Programming Team'),
								(SELECT University_ID FROM Admin WHERE Admin_ID = (SELECT Admin_ID FROM RSO WHERE RSO_Name = 'UCF Programming Team' ))
								);

/*UCF 		TKE Red Carnation Ball RSO only event*/						
INSERT INTO Events( Description, 
					Start_Time, 
					End_Time, 
					Event_Location, 
					Contact_Email, 
					Contact_Phone, 
					Event_Name, 
					Event_Type, 
					Event_Visible, 
					Admin_ID, 
					RSO_ID,
					University_ID) VALUES
								('UCF Tau Kappa Epsilon Red Carnation Ball with private guests and catoring', 
								'2018-12-18 18:00:00', 
								'2018-12-18 22:30:00', 
								'Student Union', 
								'Tke@ucf.edu', 
								'407-999-9999', 
								'2018 TKE Red Carnation Ball', 
								'Social', 
								'RSO', 
								(SELECT Admin_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon'), 
								(SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon'),
								(SELECT University_ID FROM Admin WHERE Admin_ID = (SELECT Admin_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon' ))
								);
								
INSERT INTO Events( Description, 
					Start_Time, 
					End_Time, 
					Event_Location, 
					Contact_Email, 
					Contact_Phone, 
					Event_Name, 
					Event_Type, 
					Event_Visible, 
					Admin_ID, 
					RSO_ID,
					University_ID) VALUES
								('UCF Tau Kappa Epsilon Movie Night', 
								'2018-12-19 18:00:00', 
								'2018-12-19 22:30:00', 
								'ClassRoom1', 
								'Tke@ucf.edu', 
								'407-999-9999', 
								'2018 TKE Movie Night', 
								'Social', 
								'Pri', 
								(SELECT Admin_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon'), 
								(SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon'),
								(SELECT University_ID FROM Admin WHERE Admin_ID = (SELECT Admin_ID FROM RSO WHERE RSO_Name = 'Tau Kappa Epsilon' ))
								);								

								/*USF Tau Delta Tau Fundraiser Event*/								
INSERT INTO Events( Description, 
					Start_Time, 
					End_Time, 
					Event_Location, 
					Contact_Email, 
					Contact_Phone, 
					Event_Name, 
					Event_Type, 
					Event_Visible, 
					Admin_ID, 
					RSO_ID,
					University_ID) VALUES
								('Tau Delta Tau Fundraiser', 
								'2018-12-01 12:00:00', 
								'2018-12-01 14:00:00', 
								'Student Union', 
								'admin@ucf.edu', 
								'407-999-9999', 
								'2018 TDT Fundraiser for Cancer Research', 
								'Fundraising', 
								'Pub', 
								(SELECT Admin_ID FROM RSO WHERE RSO_Name = 'Tau Delta Tau'), 
								(SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Delta Tau'),
								(SELECT University_ID FROM Admin WHERE Admin_ID = (SELECT Admin_ID FROM RSO WHERE RSO_Name = 'Tau Delta Tau' ))
								);
	/*USF Chess Team Fundraiser*/ 							
INSERT INTO Events( Description, 
					Start_Time, 
					End_Time, 
					Event_Location, 
					Contact_Email, 
					Contact_Phone, 
					Event_Name, 
					Event_Type, 
					Event_Visible, 
					Admin_ID, 
					RSO_ID,
					University_ID) VALUES
								('USF Chess Team Fundraiser for travel expenses to Germany for the 2019 Chess World Cub', 
								'2018-12-08 17:00:00', 
								'2018-12-08 19:30:00', 
								'ClassRoom2', 
								'admin@usf.edu', 
								'407-999-9999', 
								'USF Chess Team Fundraiser', 
								'Fundraising', 
								'Pub', 
								(SELECT Admin_ID FROM RSO WHERE RSO_Name = 'USF Chess Team'), 
								(SELECT RSO_ID FROM RSO WHERE RSO_Name = 'USF Chess Team'),
								(SELECT University_ID FROM Admin WHERE Admin_ID = (SELECT Admin_ID FROM RSO WHERE RSO_Name = 'USF Chess Team' ))
								);
/*USF TDT Early alumni meet and greet private event*/
INSERT INTO Events( Description, 
					Start_Time, 
					End_Time, 
					Event_Location, 
					Contact_Email, 
					Contact_Phone, 
					Event_Name, 
					Event_Type, 
					Event_Visible, 
					Admin_ID, 
					RSO_ID,
					University_ID) VALUES
								('Private meeting between USF alumni and new alumni', 
								'2018-12-08 17:00:00', 
								'2018-12-08 19:30:00', 
								'Student Union', 
								'admin@usf.edu', 
								'407-999-9999', 
								'USF Early Alum Meet and Greet', 
								'Fundraising', 
								'Pri', 
								(SELECT Admin_ID FROM RSO WHERE RSO_Name = 'Tau Delta Tau'), 
								(SELECT RSO_ID FROM RSO WHERE RSO_Name = 'Tau Delta Tau'),
								(SELECT University_ID FROM Admin WHERE Admin_ID = (SELECT Admin_ID FROM RSO WHERE RSO_Name = 'Tau Delta Tau' ))
								);
		/*FAU Rugby Team*/					
INSERT INTO Events( Description, 
					Start_Time, 
					End_Time, 
					Event_Location, 
					Contact_Email, 
					Contact_Phone, 
					Event_Name, 
					Event_Type, 
					Event_Visible, 
					Admin_ID, 
					RSO_ID,
					University_ID) VALUES
								('FAU Rugby Team fundraiser for travel expenses to Rugby World Cup in 2019 in New Zealand', 
								'2018-12-19 12:00:00', 
								'2018-12-19 14:00:00', 
								'Student Union', 
								'admin@fau.edu', 
								'407-999-9999', 
								'2018 FAU Rugby Fundraiser', 
								'Fundraising', 
								'Pub', 
								(SELECT Admin_ID FROM RSO WHERE RSO_Name = 'FAU Rugby Team'), 
								(SELECT RSO_ID FROM RSO WHERE RSO_Name = 'FAU Rugby Team'),
								(SELECT University_ID FROM Admin WHERE Admin_ID = (SELECT Admin_ID FROM RSO WHERE RSO_Name = 'FAU Rugby Team' ))
								);
								
select * from Student_RSO_List;
/*create a trigger for the events table.  We want to make sure that when the admin creates an event they can only create it for the same university id*/
								
/*Comments for an Event that has already occured with a rating*/

/*Code to return the number of distinct students per RSO*/
/*
	SELECT rso_id, COUNT(DISTINCT student_id)
    FROM student_rso_list
    ROUP BY rso_id;
*/


/*Create a trigger to ensure events cannot overlap at the same location and time*/
/*
DELIMITER $$
CREATE TRIGGER before_events_insert
    BEFORE INSERT ON Events
    FOR EACH ROW 
BEGIN
    INSERT INTO employees_audit
    SET action = 'update',
     employeeNumber = OLD.employeeNumber,
        lastname = OLD.lastname,
        changedat = NOW(); 
END$$
DELIMITER ;
*/
/*Create three comments per event*/


