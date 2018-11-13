<!DOCTYPE html>
<html>
  <head>
    <title>Setting up database</title>
  </head>
  <body>

    <h3>Setting up...</h3>

<?php //  setup.php
  require_once 'functions.php';

  /*
  createTable('members',
              'user VARCHAR(16),
              pass VARCHAR(16),
              INDEX(user(6))');

  createTable('messages', 
              'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              auth VARCHAR(16),
              recip VARCHAR(16),
              pm CHAR(1),
              time INT UNSIGNED,
              message VARCHAR(4096),
              INDEX(auth(6)),
              INDEX(recip(6))');

  createTable('friends',
              'user VARCHAR(16),
              friend VARCHAR(16),
              INDEX(user(6)),
              INDEX(friend(6))');

  createTable('profiles',
              'user VARCHAR(16),
              text VARCHAR(4096),
              INDEX(user(6))');
			  
			  
*/  



//add our foreign keys using alter table after we have created the other tables
//KEY `FK` (`User_Name`)
createTable ('Users', 
	  'User_Name VARCHAR(20),
	  First_Name VARCHAR(20),
	  Last_Name VARCHAR(20),
	  Password VARCHAR(20),
	  Super_ID INT,
	  Admin_ID INT,
	  Student_ID INT,
	  PRIMARY KEY (User_Name)');
	  //add FK for superid, adminid, studentid
	  


//do we need have a foreign key in the student table? Don't think so.
createTable ('Student', 
	  'Student_ID INT AUTO_INCREMENT,
	  User_Name VARCHAR(20),
	  PRIMARY KEY (Student_ID)');

//super users has to have a FK for the universities it can create
createTable ('Super_Users', 
	  'Super_ID INT AUTO_INCREMENT,
	  User_Name VARCHAR(20),
	  PRIMARY KEY (Super_ID)');

createTable ('University_Profile', 
	  'University_ID INT AUTO_INCREMENT,
	  University_Name VARCHAR(20),
	  Num_Students INT,
	  University_photo BLOB,
	  Super_ID INT,
	  University_location VARCHAR(60),
	  Description VARCHAR(400),
	  PRIMARY KEY (University_ID)');

createTable ('Events', 
	  'Event_ID INT AUTO_INCREMENT,
	  Event_Description VARCHAR(100),
	  Start_Time DATETIME,
	  End_Time DATETIME,
	  Event_Location VARCHAR(100),
	  Contact_Email VARCHAR(40),
	  Contact_Phone VARCHAR(11),
	  Event_Name VARCHAR(40),
	  Event_Type VARCHAR(40),
	  Admin_ID INT,
	  RSO_ID INT,
	  PRIMARY KEY (Event_ID)');

createTable ('RSO', 
  'RSO_ID INT AUTO_INCREMENT,
  RSO_Name VARCHAR(20),
  Admin_ID INT,
  PRIMARY KEY (RSO_ID)');

createTable ('Admin', 
  'Admin_ID INT AUTO_INCREMENT,
  User_Name VARCHAR(20),
  University_ID INT,
  PRIMARY KEY (Admin_ID)');

createTable ('Student_RSO_List', 
  'Student_ID INT AUTO_INCREMENT,
  RSO_ID INT,
  PRIMARY KEY (Student_ID, RSO_ID)');

 createTable ('Comments', 
  'Comment_ID INT AUTO_INCREMENT,
  Comment VARCHAR(100),
  Stars TINYINT,
  Event_ID INT,
  Student_ID INT,
  PRIMARY KEY (Comment_ID)');
//Build FK for the Users table



//alterTable($name, $foreign_key, $primary_table, $primary_key )
//Modify User's Table
alterTable ('Users', 'Super_ID', 'Super_Users', 'Super_ID');
alterTable ('Users', 'Admin_ID', 'Admin', 'Admin_ID');
alterTable ('Users', 'Student_ID', 'Student', 'Student_ID');


//Build the FK for the Super_Users Table
alterTable ('Super_Users', 'User_Name', 'Users', 'User_Name');


//Build FK for the Admin table
alterTable('Admin', 'University_ID', 'University_Profile', 'University_ID');
alterTable('Admin', 'User_Name', 'Users', 'User_Name');


//Modify the Student Table FK

alterTable('Student', 'User_Name', 'Users', 'User_Name');

//RSO FK's
alterTable('RSO', 'Admin_ID', 'Admin', 'Admin_ID');

//University_Profile FK
alterTable('University_Profile', 'Super_ID', 'Super_Users', 'Super_ID');

//Events FK
alterTable('Events', 'Admin_ID', 'Admin', 'Admin_ID');
alterTable('Events', 'RSO_ID', 'RSO', 'RSO_ID');

//Comments FK setup
alterTable('Comments', 'Event_ID', 'Events', 'Event_ID');
alterTable('Comments', 'Student_ID', 'Student', 'Student_ID');

//Build some DATA for the Database

//USER 1

queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('jhghar', 'Jaqen', 'Hghar', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('jhghar')");
//Student 2
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('jlannister', 'Jaime', 'Lannister', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('jlannister')");
//Student 3
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('jsnow', 'John', 'Snow', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('jsnow')");
//Student 4
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('estark', 'Eddard', 'Stark', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('estark')");
//Student 5
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('rbaratheon', 'Robert', 'Baratheon', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('rbaratheon')");
//Student 6
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('cstark', 'Catelyn', 'Stark', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('cstark')");
//Student 7
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('dtargaryen', 'Daenerys', 'Targaryen', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('dtargaryen')");
//Student 8
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('jmormont', 'Jorah', 'Mormont', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('jmormont')");
//Student 9
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('sstark', 'Sansa', 'Stark', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('sstark')");
//Student 10
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('tgreyjoy', 'Theon', 'Greyjoy', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('tgreyjoy')");
//Student 11
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('jbaratheon', 'Joffrey', 'Baratheon', 'veteran')");

//Student 12
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('sclegane', 'Sandor', 'Clegane', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('sclegane')");
//Student 13
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('kdrogo', 'Khal', 'Drogo', 'veteran')");
//Student 14
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('pbaelish', 'Petyr', 'Baelish', 'veteran')");

//Student 15
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('dseaworth', 'Davos', 'Seaworth', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('dseaworth')");
//Student 16
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('bdetarth', 'Brienne', 'deTarth', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('bdetarth')");
//Student 17
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('starly', 'Samwell', 'Tarly', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('starly')");
//Student 18
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('tgiantsbane', 'Tormund', 'Giantsbane', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('tgiantsbane')");
//Student 19
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('mtyrell', 'Margaery', 'Tyrell', 'veteran')");
queryMysql("INSERT INTO Student (User_Name) VALUES ('mtyrell')");
//Student 20
queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES ('rbolton', 'Ramsay', 'Bolton', 'veteran')");

echo('passed user insert');
//Create super user and link it to the users table
queryMysql("INSERT INTO Super_Users (User_Name) VALUES ('rbolton')");
queryMysql("UPDATE Users SET Super_ID = (SELECT Super_ID FROM Super_Users WHERE User_Name = 'rbolton') WHERE User_Name = 'rbolton'");


//Set the superuser info in the User table
//queryMysql("INSERT INTO Users(Super_ID) VALUES (SELECT Super_ID FROM Super_Users WHERE User_Name = 'rbolton')");

//create some Universities

queryMysql("INSERT INTO University_Profile (University_Name, Num_Students, University_location, Description, Super_ID ) VALUES 
('UCF', 65000, 'Orlando', 'The fastest growing school in the nation', 1)");

queryMysql("INSERT INTO University_Profile (University_Name, Num_Students, University_location, Description, Super_ID ) VALUES 
('USF', 55000, 'Tampa', 'Has a terrible football team and they smell bad', 1)");

queryMysql("INSERT INTO University_Profile (University_Name, Num_Students, University_location, Description, Super_ID ) VALUES 
('FAU', 35000, 'Miami', 'Joey Freshwater has a new home.', 1)");

//create some admins

queryMysql("INSERT INTO Admin(User_Name, University_ID) VALUES ('jbaratheon', 1)");
queryMysql("INSERT INTO Admin(User_Name, University_ID) VALUES ('pbaelish', 1)");
queryMysql("INSERT INTO Admin(User_Name, University_ID) VALUES ('kdrogo', 1)");


?>

    <br>...done.
  </body>
</html>
