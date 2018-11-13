<?php //login.php
  require_once 'header.php';
  $error = $user = $pass = $role = "";

  if (isset($_POST['user']))
  {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);
    $role = sanitizeString($_POST['role']);
    
	if ($user == "" || $pass == "" || $role == "")
	{
        $error = 'Not all fields were entered...';
	}else{
    //check the selected role in the database and that the login and password is good
	
		switch($role){
			case "Admin":
				$isAdmin = queryMySQL("SELECT Admin_ID FROM Admin WHERE User_Name = '$user'");
				$isUser = queryMySQL("SELECT User_Name,Password FROM Users WHERE User_name='$user' AND Password='$pass'");
				
				//verify they are an admin and a user
				if($isAdmin->num_rows == 0 || $isUser->num_rows == 0)
				{
				$error = "Not valid Admin";	
				}
				else
				{
				$_SESSION['user'] = $user;
				$_SESSION['pass'] = $pass;
				$_SESSION['role'] = $role;
				die("<div class='center'>You are now logged in. Please
				<a data-transition='slide' href='members.php?view=$user'>click here</a>
				to continue.</div></div></body></html>");	
				}
				break;
			case "Super":
				$isSuper = queryMySQL("SELECT Super_ID FROM Super_Users WHERE User_Name = '$user'");
				$isUser = queryMySQL("SELECT User_Name,Password FROM Users WHERE User_name='$user' AND Password='$pass'");
				
				//verify they are an admin and a user
				if($isSuper->num_rows == 0 || $isUser->num_rows == 0)
				{
				$error = "Not valid Super User";	
				}
				else
				{
				$_SESSION['user'] = $user;
				$_SESSION['pass'] = $pass;
				$_SESSION['role'] = $role;
				die("<div class='center'>You are now logged in. Please
				<a data-transition='slide' href='members.php?view=$user'>click here</a>
				to continue.</div></div></body></html>");	
				}
				break;
			case "Student":
				$isStudent = queryMySQL("SELECT Student_ID FROM Student WHERE User_Name = '$user'");
				$isUser = queryMySQL("SELECT User_Name,Password FROM Users WHERE User_name='$user' AND Password='$pass'");
				
				//verify they are an admin and a user
				if($isStudent->num_rows == 0 || $isUser->num_rows == 0)
				{
				$error = "Not valid Student";	
				}
				else
				{
				$_SESSION['user'] = $user;
				$_SESSION['pass'] = $pass;
				$_SESSION['role'] = $role;
				die("<div class='center'>You are now logged in. Please
				<a data-transition='slide' href='members.php?view=$user'>click here</a>
				to continue.</div></div></body></html>");	
				}
				break;
			default:
				$error = "Not a valid role";
				
		}
		
	}
  }
	

echo <<<_END
      <form method='post' action='login.php'>
        <div data-role='fieldcontain'>
          <label></label>
          <span class='error'>$error</span>
        </div>
        <div data-role='fieldcontain'>
          <label></label>
          Please enter your details to log in
        </div>
        <div data-role='fieldcontain'>
          <label>Username</label>
          <input type='text' maxlength='16' name='user' value='$user'>
        </div>
        <div data-role='fieldcontain'>
          <label>Password</label>
          <input type='password' maxlength='16' name='pass' value='$pass'>
        </div>
		<div data-role='fieldcontain'>
          <label>Role: Super, Admin or Student</label>
          <input type='role' maxlength='16' name='role' value='$role'>
        </div>
        <div data-role='fieldcontain'>
          <label></label>
          <input data-transition='slide' type='submit' value='Login'>
        </div>
      </form>
    </div>
  </body>
</html>
_END;
?>
