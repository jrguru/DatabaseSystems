<?php // signup.php
  require_once 'header.php';

echo <<<_END
  <script>
    function checkUser(user)
	{
		if (user.value == '')
		{
			O('info').innerHTML = ''
			return
		}
		params = "user=" + user.value
		request = new ajaxRequest()
		request.open("POST", "checkuser.php", true)
		request.setRequestHeader("Content-type",
		"application/x-www-form-urlencoded")
		request.setRequestHeader("Content-length", params.length)
		request.setRequestHeader("Connection", "close")
		request.onreadystatechange = function()
		{
		if (this.readyState == 4)
		if (this.status == 200)
		if (this.responseText != null)
		O('info').innerHTML = this.responseText
		}
		request.send(params)
	}
	
	function ajaxRequest()
	{
		try { var request = new XMLHttpRequest() }
		catch(e1) 
		{
			try { request = new ActiveXObject("Msxml2.XMLHTTP") }
			catch(e2) 
			{
				try { request = new ActiveXObject("Microsoft.XMLHTTP") }
				catch(e3) 
				{
					request = false
				} 
			} 
		}
	return request
	}
</script>
	<div class='main'><h3>Please enter your details to sign up</h3>

   
_END;

  $error = $user = $pass = "";
  $first_name = $last_name = $university = "";
  if (isset($_SESSION['user'])) destroySession();

  if (isset($_POST['user']))
  {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);
	$first_name = sanitizeString($_POST['first_name']);
	$last_name = sanitizeString($_POST['last_name']);
	$university = sanitizeString($_POST['university']);
	
    if ($user == "" || $pass == "" ||$first_name == "" || $last_name == "" || $university == "" )
	{
      $error = 'Not all fields were entered.<br><br>';
    }
	//Make sure that the university name is the correct input, if it is check the username and insert
	elseif ($university == "UCF" || $university == "USF" || $university == "FAU")
    {
			
			$result = queryMysql("SELECT * FROM Users WHERE User_Name='$user'");
			if ($result->num_rows)
				$error = 'That username already exists<br><br>';
			else
			{
        //Insert into users, insert into students, update Users table, update student table, update university code in the users table for the student
			queryMysql("INSERT INTO Users (User_Name, First_Name, Last_Name, Password) VALUES('$user', '$first_name', '$last_name', '$pass')");
			queryMysql("INSERT INTO Student(User_Name) VALUES ('$user')");
			queryMysql("UPDATE Users SET Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = '$user') WHERE User_Name = '$user'");
			queryMysql("UPDATE Student SET University_ID = (SELECT University_ID FROM University_Profiles WHERE University_Name = '$university') WHERE User_Name = '$user'");
			die('<h4>Account created</h4>Please Log in.</div></body></html>');
			}
    }
	else //we failed the check on the universtiy name so we let the user know
		$error = "Invalid University Name";
  }

echo <<<_END
      <form method='post' action='signup.php'>$error
      <div data-role='fieldcontain'>
        <label></label>
        Please enter your details to sign up
      </div>
      <div data-role='fieldcontain'>
        <label>Username</label>
        <input type='text' maxlength='16' name='user' value='$user'
          onBlur='checkUser(this)'>
        <label></label><div id='used'>&nbsp;</div>
      </div>
      <div data-role='fieldcontain'>
        <label>Password</label>
        <input type='text' maxlength='16' name='pass' value='$pass'>
      </div>
	  <div data-role='fieldcontain'>
        <label>First Name</label>
        <input type='text' maxlength='16' name='first_name' value='$first_name'>
		</div>
		<div data-role='fieldcontain'>
        <label>Last Name</label>
        <input type='text' maxlength='16' name='last_name' value='$last_name'>
		</div>
		<div data-role='fieldcontain'>
        <label>University (UCF, USF or FAU)</label>
        <input type='text' maxlength='16' name='university' value='$university'>
		</div>
      <div data-role='fieldcontain'>
        <label></label>
        <input data-transition='slide' type='submit' value='Sign Up'>
      </div>
    </div>
  </body>
</html>
_END;
?>
