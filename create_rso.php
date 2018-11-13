<?php //create_rso.php
require_once 'header.php';
require_once 'functions.php';


$error = $rso_name = $student_1 = $student_2 = $student_3 = $student_4 = $student_5 = "";


  //if we have a proper session user then grab the user name 
  if(isset($_SESSION['user']))
  { 
  $user = $_SESSION['user'];
  }
  
//check each student to make sure they have the same university code
///if they do then we will add the RSO to the rso table
//we will then add the five students to the student_rso_table
//we will then add student #1 to the admin table
if ( isset($_POST['rso_name']))
	{
		
		//grab the input from the user and sanitize it
		$rso_name =  sanitizeString($_POST['rso_name']);
		$student_1 = sanitizeString($_POST['student_1']);
		$student_2 = sanitizeString($_POST['student_2']);
		$student_3 = sanitizeString($_POST['student_3']);
		$student_4 = sanitizeString($_POST['student_4']);
		$student_5 = sanitizeString($_POST['student_5']);
	
	if($rso_name == "" || $student_1 == "" || $student_2 == ""  || $student_3 == "" ||$student_4 == "" ||$student_5 == "" )
	{
			$error = 'Not all fields were entered.<br><br>';
	}else
	{	
	//grab the student University ID's	
	$student_1_university_code = queryMysql("SELECT University_ID FROM Student WHERE User_name = '$student_1'");
	$student_2_university_code = queryMysql("SELECT University_ID FROM Student WHERE User_name = '$student_2'");
	$student_3_university_code = queryMysql("SELECT University_ID FROM Student WHERE User_name = '$student_3'");
	$student_4_university_code = queryMysql("SELECT University_ID FROM Student WHERE User_name = '$student_4'");
	$student_5_university_code = queryMysql("SELECT University_ID FROM Student WHERE User_name = '$student_5'");
	}
	$row1 = $student_1_university_code->fetch_assoc();
	$row2 = $student_2_university_code->fetch_assoc();
	$row3 = $student_3_university_code->fetch_assoc();
	$row4 = $student_4_university_code->fetch_assoc();
	$row5 = $student_5_university_code->fetch_assoc();
	//check that the university id's match
	if( $row1['University_ID'] == $row2['University_ID'] && 
		$row2['University_ID'] == $row3['University_ID'] &&
		$row3['University_ID'] == $row4['University_ID'] &&
		$row4['University_ID'] == $row5['University_ID'])
	{
		
		//every student attends the same university, so lets add them to the relevant tables:
		//add student_1 as an admin to the admin table
		queryMysql("INSERT INTO Admin(User_Name) VALUES ('$student_1')");
		queryMysql("UPDATE Users SET Admin_ID = (SELECT Admin_ID FROM Admin WHERE User_Name = '$student_1') WHERE User_name = '$student_1'");
		queryMysql("UPDATE Admin SET University_ID = (SELECT University_ID FROM Student WHERE User_Name = '$student_1')");
		
		//Add the RSO to the RSO table and assign the new admin to the RSO
		queryMysql("INSERT INTO RSO(RSO_Name, Status, Admin_ID) VALUES ('$rso_name', 'active', (SELECT Admin_ID FROM Admin WHERE User_Name = '$student_1'))");
		
		
		//add our student 1-5 to the student_rso_table and link it to the newly created RSO_ID
		queryMysql("INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = '$student_1'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = '$rso_name'))");
		queryMysql("INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = '$student_2'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = '$rso_name'))");
		queryMysql("INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = '$student_3'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = '$rso_name'))");
		queryMysql("INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = '$student_4'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = '$rso_name'))");
		queryMysql("INSERT INTO Student_RSO_List(Student_ID, RSO_ID) VALUES ((SELECT Student_ID FROM Student WHERE User_Name = '$student_5'),
														 (SELECT RSO_ID FROM RSO WHERE RSO_Name = '$rso_name'))");												 
		$error = "Successfully created new RSO: '$rso_name'";
	}
	else{
		$error = "Invalid Student(s)<br>";
	}
	
	}



echo <<<_ADD
      <form method='post' action='create_rso.php'>
        <div data-role='fieldcontain'>
          <label></label>
          <span class='error'>$error</span>
        </div>
        <div data-role='fieldcontain'>
          <label></label>
          Add an RSO (note: all students must attend the same university)
        </div>
        
		<div data-role='fieldcontain'>
          <label>RSO Name</label>
          <input type='text' maxlength='16' name='rso_name' value='$rso_name'>
        </div>
		<div data-role='fieldcontain'>
          <label>Student#1 User Name (logged in user is the admin)</label>
          <input type='text' maxlength='400' name='student_1' value='$user'>
        </div>
		<div data-role='fieldcontain'>
          <label>Student#2 User Name</label>
          <input type='text' maxlength='400' name='student_2' value='$student_2'>
        </div>
		<div data-role='fieldcontain'>
          <label>Student#3 User Name</label>
          <input type='text' maxlength='400' name='student_3' value='$student_3'>
        </div>
		<div data-role='fieldcontain'>
          <label>Student#4 User Name</label>
          <input type='text' maxlength='400' name='student_4' value='$student_4'>
        </div>
		<div data-role='fieldcontain'>
          <label>Student#5 User Name</label>
          <input type='text' maxlength='400' name='student_5' value='$student_5'>
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