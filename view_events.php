<?php //view_events.php
require_once 'header.php';
require_once 'functions.php';


	//if we have a proper session user then grab the user name 
	if(isset($_SESSION['user']))
	{ 
	$user = $_SESSION['user'];
	}
  
  //return all of the events that the student is a member
  /*Need to get this working again, states it returns with more than two rows so the query isnt proper.  If the user is a member of multiple RSO's 
	then sql won't return all the events that they can see.  If they are only part of one rso then it will work.
  */
  $RSO_result = queryMysql("SELECT Event_ID, Event_Name, Description, Event_Location, Start_Time, End_Time, Event_Type, Event_Visible 
  FROM Events WHERE RSO_ID = (SELECT RSO_ID FROM Student_RSO_List WHERE Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = '$user')) AND
  Event_Visible = 'RSO'");
  
  /*
  Select Event_ID
  from student S, student_rso_list R, events E
  where s.user_name = 'jhghar' s.student_id = R.student_ID AND R.rso_ID = E.rso_ID
  
	//return the events that are at the university that the student attends that they can see
  */
  $Pri_result = queryMysql("SELECT Event_ID, Event_Name, Description, Event_Location, Start_Time, End_Time, Event_Type, Event_Visible 
  FROM Events WHERE University_ID = (SELECT University_ID FROM Student WHERE User_name = '$user') AND Event_Visible = 'Pri'");
  
  //return the events from every university available that are public to anyone
  $Pub_result = queryMysql("SELECT Event_ID, Event_Name, Description, Event_Location, Start_Time, End_Time, Event_Type, Event_Visible, University_ID 
  FROM Events WHERE Event_Visible = 'Pub'");

  
	if ($RSO_result->num_rows != 0) 
	{
		echo "List of all RSO Events associated with : '$user'";
		echo "<br>";
		echo "<br>";
		while($row = $RSO_result->fetch_assoc()) 
		{
		
        echo "Event ID: " . $row["Event_ID"] . "<br>";
		echo "Event Name: " . $row["Event_Name"]. "<br>"; 
		echo "Description: " . $row["Description"]. "<br>";
		echo "Event Location: " . $row["Event_Location"]. "<br>";
		echo "Event Type: " . $row["Event_Type"]. "<br>";
		echo "Event Visibility: " . $row["Event_Visible"] . "<br>";
		echo "Start Time: " . $row["Start_Time"]. "<br>";
		echo "End Time: " . $row["End_Time"] .  "<br>";
		echo "<hr>";
		}
	}
	
	if ($Pri_result -> num_rows != 0)
	{
		echo "List of all Private Events associated with : '$user'";
		echo "<br>";
		echo "<br>";
		while($row = $Pri_result->fetch_assoc()) 
		{
		
        echo "Event ID: " . $row["Event_ID"] . "<br>";
		echo "Event Name: " . $row["Event_Name"]. "<br>";
		echo "Description: " . $row["Description"]. "<br>";		
		echo "Event Location: " . $row["Event_Location"]. "<br>";
		echo "Event Type: " . $row["Event_Type"]. "<br>";
		echo "Event Visibility: " . $row["Event_Visible"] . "<br>";
		echo "Start Time: " . $row["Start_Time"]. "<br>";
		echo "End Time: " . $row["End_Time"] .  "<br>";
		echo "<hr>";
		}
	}
	if ($Pub_result-> num_rows != 0)
	{
		echo "List of all Public Events associated with : '$user'";
		echo "<br>";
		echo "<br>";
		while($row = $Pub_result->fetch_assoc()) 
		{
		
        echo "Event ID: " . $row["Event_ID"] . "<br>";
		echo "Event Name: " . $row["Event_Name"]. "<br>"; 
		echo "Description: " . $row["Description"]. "<br>";
		echo "Event Location: " . $row["Event_Location"]. "<br>";
		echo "Event Type: " . $row["Event_Type"]. "<br>";
		echo "Event Visibility: " . $row["Event_Visible"] . "<br>";
		echo "Start Time: " . $row["Start_Time"]. "<br>";
		echo "End Time: " . $row["End_Time"] .  "<br>";
		echo "<hr>";
		}
	}




?>