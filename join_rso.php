<?php //Join RSO Form
require_once 'header.php';
require_once 'functions.php';

$rso_name = $error = "";
//Provide a list of RSO's that are joinable
//Provide a form so that the User can request to join the RSO
if(isset($_SESSION['user']))
	{ 
	$user = $_SESSION['user'];
	}

echo"<br>";
echo"<br>";
//get the list of RSOs at the students university
$RSO_result = queryMysql("SELECT RSO_Name FROM RSO WHERE admin_id = 
										(SELECT admin_id FROM Admin WHERE University_ID = 
															(SELECT University_ID FROM Student WHERE User_Name = '$user'))");
$university_name = queryMysql("SELECT University_Name FROM University_profiles WHERE
								University_ID = (SELECT University_ID FROM Student WHERE User_Name = '$user')");

if ($RSO_result->num_rows != 0) 
	{
		echo "List of all RSO's $user can apply to join at  ";
		echo "<br>";
		echo "<br>";
		while($row = $RSO_result->fetch_assoc()) 
		{
		
        echo "RSO: " . $row["RSO_Name"] . "<br>";
		echo "<hr>";
		}
	}else
	{
		echo "Sorry, there are no avaible RSO's";
	}
	
	if ( isset($_POST['rso_name']) )
	{
		//sanitize the user input
		$rso_name = sanitizeString($_POST['rso_name']);
		
		
		/*
		echo $comment_to_modify;
		echo $comment_number_to_modify;
		echo $rating_to_modify;
		*/
		
		//check if the user input is empty
		if($rso_name == "" )
		{
			$error = 'Not all fields were entered.<br><br>';
		}else
		{
			
			//insert the student into the student_rso_list table
			queryMysql("INSERT INTO student_rso_list (Student_ID, RSO_ID) VALUES 
			((SELECT student_ID from student where user_name = '$user'), 
			 (SELECT RSO_ID from rso where rso_name = '$rso_name'))");
			echo "RSO: $rso_name successfully joined<br><br>";
		}
		
	}
	
	
	
	echo <<<_ADD
      <form method='post' action='join_rso.php'>
        <div data-role='fieldcontain'>
          <label></label>
          <span class='error'>$error</span>
        </div>
        <div data-role='fieldcontain'>
          <label></label>
          Enter an RSO Name to Join
        </div>
		<div data-role='fieldcontain'>
          <label>RSO Name :</label>
          <input type='text' maxlength='100' name='rso_name' value='$rso_name'>
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