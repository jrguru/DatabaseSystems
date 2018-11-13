<?php //manage_university.php

require_once 'header.php';
require_once 'functions.php';


$error = $university_name = $university_location = $university_description = $university_ID = "";
$num_students = "";

  //if we have a proper session user then grab the user name 
  if(isset($_SESSION['user']))
  { 
  $user = $_SESSION['user'];
  }
  
	//Add a university code
	if (isset($_POST['university_name']))
	{
    $university_name = sanitizeString($_POST['university_name']);
    $num_students = sanitizeString($_POST['num_students']);
    $university_location = sanitizeString($_POST['university_location']);
	$university_description = sanitizeString($_POST['university_description']);
	 
	
			if ($university_name == "" || $num_students == "" ||$university_location == "" || $university_description == ""  )
			{
			$error = 'Not all fields were entered.<br><br>';
			}
			else 
			{
				queryMysql("INSERT INTO University_Profiles (University_Name, Num_Students, University_location, Description ) VALUES 
				('$university_name', $num_students, '$university_location', '$university_description')");
				queryMysql("UPDATE University_Profiles SET Super_ID = (SELECT Super_ID FROM Super_Users WHERE User_Name = '$user') WHERE University_Name = '$university_name'");
			}
	}


	//delete a university
	if(isset($_POST['university_ID'])){
		$university_ID = sanitizeString($_POST['university_ID']);
		queryMysql("DELETE FROM University_Profiles WHERE University_ID = '$university_ID'");
	}
	
$result = queryMysql("SELECT University_ID, University_Name, Num_Students, University_Location, Description FROM University_Profiles");

//Show all of the current Universities in the database at the top of the page
if ($result->num_rows != 0) {
    echo "List of all Universities in the Database";
	echo "<br>";
	echo "<br>";
    while($row = $result->fetch_assoc()) 
	{
		
        echo "University ID: " . $row["University_ID"]. "   Name: " . $row["University_Name"]. "   " . "Number of students: " . $row["Num_Students"]. "  Location: " . $row["University_Location"] . " " . "Description: " . $row["Description"] . "<br>";
    }
} else {
    echo "0 results";
}




echo <<<_ADD
      <form method='post' action='manage_university.php'>
        <div data-role='fieldcontain'>
          <label></label>
          <span class='error'>$error</span>
        </div>
        <div data-role='fieldcontain'>
          <label></label>
          Add a University
        </div>
        <div data-role='fieldcontain'>
          <label>University Name</label>
          <input type='text' maxlength='16' name='university_name' value='$university_name'>
        </div>
        <div data-role='fieldcontain'>
          <label>Number of Students</label>
          <input type='number' name='num_students' min="1" max="100000" value='$num_students'>
        </div>
		<div data-role='fieldcontain'>
          <label>University Location</label>
          <input type='text' maxlength='16' name='university_location' value='$university_location'>
        </div>
		<div data-role='fieldcontain'>
          <label>University Description</label>
          <input type='text' maxlength='400' name='university_description' value='$university_description'>
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

echo <<<_DELETE
	<form method='post' action='manage_university.php'>
        <div data-role='fieldcontain'>
          <label></label>
          <span class='error'>$error</span>
        </div>
        <div data-role='fieldcontain'>
          <label></label>
          Delete a University by ID number
        </div>
        <div data-role='fieldcontain'>
          <label>University Number</label>
          <input type='number'  name='university_ID' value='$university_ID'>
        </div>
		<div data-role='fieldcontain'>
          <label></label>
          <input data-transition='slide' type='submit' value='Submit'>
        </div>
		</form>
    </div>
  </body>
</html>
_DELETE;
?>