<?php //Join RSO Form
require_once 'header.php';
require_once 'functions.php';
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
?>