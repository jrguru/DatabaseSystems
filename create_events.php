<?php //create_events.php


require_once 'header.php';
require_once 'functions.php';


$error = $contact_phone = $contact_email = $event_name = "";
$event_description = $event_type = $event_visible = $event_location = $start_time = $end_time = $rso_ID = "";
$flag = true;

  //if we have a proper session user then grab the user name 
  if(isset($_SESSION['user']))
  { 
  $user = $_SESSION['user'];
  }
  
//Show all of the current Events associated to a given administrator in the database at the top of the page
$result = queryMysql("SELECT Event_ID, Event_Name, Event_Location, Start_Time, End_Time, Event_Type, Event_Visible FROM Events WHERE Admin_ID = (Select Admin_ID FROM Admin WHERE User_Name = '$user')");


if ($result->num_rows != 0) 
{
    echo "List of all Events associated with : '$user'";
	echo "<br>";
	echo "<br>";
    while($row = $result->fetch_assoc()) 
	{
		
        echo "Event ID: " . $row["Event_ID"] . "<br>";
		echo "Event Name: " . $row["Event_Name"]. "<br>"; 
		echo "Event Location: " . $row["Event_Location"]. "<br>";
		echo "Event Type: " . $row["Event_Type"]. "<br>";
		echo "Event Visibility: " . $row["Event_Visible"] . "<br>";
		echo "Start Time: " . $row["Start_Time"]. "<br>";
		echo "End Time: " . $row["End_Time"] .  "<br>";
		echo "<hr>";
    }
} else {
    echo "0 results";
}	

		//see if they have started to fill out the form
	
	//This is working but need to figure out how to properly convert the datetime 
	if ( isset($_POST['contact_phone']))
	{
		
		//grab the input from the user and sanitize it
		$contact_phone = sanitizeString($_POST['contact_phone']);
		$contact_email = sanitizeString($_POST['contact_email']);
		$event_name = sanitizeString($_POST['event_name']);
		$event_description = sanitizeString($_POST['event_description']);
		$event_type = sanitizeString($_POST['event_type']);
		$event_visible = sanitizeString($_POST['event_visible']);
		$event_location = sanitizeString($_POST['event_location']);
		$start_time = sanitizeString($_POST['start_time']);
		$end_time = sanitizeString($_POST['end_time']);
		$rso_ID = sanitizeString($_POST['rso_ID']);
		
		//check if they are the admin of the RSO_ID they input
		//if they have put a value in the entry box below
		if($rso_ID != "")
		{
			//we grab the admin id from each table in the database
				$rso_check = queryMysql("SELECT Admin_ID FROM RSO WHERE RSO_ID = '$rso_ID'");
				$admin_check = queryMysql("SELECT Admin_ID FROM Admin WHERE User_name = '$user'");
			//now if the rso_check returns a row (which it should) we compare the two values
			if($rso_check->num_rows != 0 && $admin_check->num_rows != 0)
			{
				$row_r = $rso_check->fetch_assoc();
				$row_a = $admin_check->fetch_assoc();
				
				//if the values don't match then we will set our flag to false and let the know they can't admin that RSO
				if($row_r['Admin_ID'] != $row_a['Admin_ID'] )
				{
				$flag = false;
				$error = "You are not the administrator of that RSO<br>";
				}
		
			}else{
				$error = "Invalid RSO number<br>";
			}
		}
		
		if($contact_email == "" || $contact_phone == "" || $event_name == ""  || $event_description == "" ||$event_type == "" ||$event_visible == "" || $event_location == "" || $start_time == "" || $end_time == "" )
		{
			$error = 'Not all fields were entered.<br><br>';
		}
		else{//input the event into the database.  If there is an associated RSO account for that.
			
			//We have an RSO attached to the event
			if($rso_ID != "" && $flag)
			{
				
			queryMysql("INSERT INTO Events(
					Description, 
					Event_Location, 
					Contact_Email, 
					Contact_Phone, 
					Event_Name, 
					Event_Type, 
					Event_Visible,
					Start_Time,
					End_Time,
					Admin_ID,
					RSO_ID,
					University_ID
					) VALUES 
					('$event_description', '$event_location', '$contact_email', 
					'$contact_phone', '$event_name', '$event_type', '$event_visible', '$start_time', '$end_time',					
					(SELECT Admin_ID FROM Admin WHERE User_name = '$user' ), '$rso_ID', (SELECT University_ID from Admin where User_Name = '$user'))");	
			}else{ //No RSO attached to the event
			
				queryMysql("INSERT INTO Events(
					Description, 
					Event_Location, 
					Contact_Email, 
					Contact_Phone, 
					Event_Name, 
					Event_Type, 
					Event_Visible,
					Start_Time,
					End_Time,
					Admin_ID,
					University_ID
					) VALUES 
					('$event_description', '$event_location', '$contact_email', 
					'$contact_phone', '$event_name', '$event_type', '$event_visible', '$start_time', '$end_time',					
					(SELECT Admin_ID FROM Admin WHERE User_name = '$user' ), (SELECT University_ID from Admin where User_Name = '$user'))");
			}
					
		}
		
	}
echo "Date/Time/Location Conflict";

echo <<<_ADD
      <form method='post' action='create_events.php'>
        <div data-role='fieldcontain'>
          <label></label>
          <span class='error'>$error</span>
        </div>
        <div data-role='fieldcontain'>
          <label></label>
          Add an Event
        </div>
		<div data-role='fieldcontain'>
          <label>Contact Phone</label>
          <input type='text' maxlength='30' name='contact_phone' value='$contact_phone'>
        </div>
		<div data-role='fieldcontain'>
          <label>Contact Email</label>
          <input type='text' maxlength='30' name='contact_email' value='$contact_email'>
        </div>
		<div data-role='fieldcontain'>
          <label>Event Name</label>
          <input type='text' maxlength='30' name='event_name' value='$event_name'>
        </div>
        <div data-role='fieldcontain'>
          <label>Event Description</label>
          <input type='text' maxlength='100' name='event_description' value='$event_description'>
        </div>
		<div data-role='fieldcontain'>
          <label>Event Type (Social, Sports, Fundraising, Tech talk)</label>
          <input type='text' maxlength='100' name='event_type' value='$event_type'>
        </div>
		<div data-role='fieldcontain'>
          <label>Event Visible (Pub, Pri, RSO)</label>
          <input type='text' maxlength='100' name='event_visible' value='$event_visible'>
        </div>
		<div data-role='fieldcontain'>
          <label>Event Location (Student Union, Library, Arena, ClassRoom1, ClassRoom2)</label>
          <input type='text' maxlength='100' name='event_location' value='$event_location'>
        </div>
		<div data-role='fieldcontain'>
          <label>Event Start Time</label>
          <input type="datetime-local" name="start_time" value='$start_time' min="2018-06-07T00:00" max="2020-06-14T00:00">
        </div>
		<div data-role='fieldcontain'>
          <label>Event End Time</label>
          <input type="datetime-local" name="end_time" value='$end_time' min="2018-06-07T00:00" max="2020-06-14T00:00">
        </div>
		<div data-role='fieldcontain'>
          <label>Associated RSO ID (if RSO event)</label>
          <input type='number' maxlength='30' name='rso_ID' value='$rso_ID'>
        </div>
		<div data-role='fieldcontain'>
          <label></label>
          <input data-transition='slide' type='submit' value='Submit'>
        </div>
      </form>
    </div>
  </body>
</html>
_ADD;

?>
