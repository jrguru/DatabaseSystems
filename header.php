<?php //header.php
 session_start();

echo <<<_INIT
<!DOCTYPE html> 
<html>
  <head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'> 
    <link rel='stylesheet' href='jquery.mobile-1.4.5.min.css'>
    <link rel='stylesheet' href='styles.css' type='text/css'>
    <script src='javascript.js'></script>
    <script src='jquery-2.2.4.min.js'></script>
    <script src='jquery.mobile-1.4.5.min.js'></script>

_INIT;

  require_once 'functions.php';

  $userstr = 'Welcome Guest';

  
 
  //write what can be displayed on the logged in screen for the three types of users:  Super, Admin and Student
  //Student needs to search for events, RSO's and add a comment
  
  //Admin needs to be able to create an RSO, create an Event
  //Lets do a big ass switch statement
  
  
  if (isset($_SESSION['user'])  )
  {
    $user     = $_SESSION['user'];
    $loggedin = TRUE;
	$role = $_SESSION['role'];
    $userstr  = "Logged in as: $user";
  }
  else $loggedin = FALSE;
echo <<<_MAIN
    <title>University Event Manager: $userstr</title>
  </head>
  <body>
    <div data-role='page'>
      <div data-role='header'>
        <div id='logo' class='center'>UNIVERSITY EVENT MANAGER</div>
        <div class='username'>$userstr</div>
      </div>
      <div data-role='content'>

_MAIN;
  
  if($loggedin)
  {
		  switch($role){
				case "Admin":
					echo <<<_LOGGEDIN
					<div class='center'>
					<a data-role='button' data-inline='true' data-icon='user'
					data-transition="slide" href='create_rso.php?view=$user'>Create RSO</a>
				  
					<a data-role='button' data-inline='true' data-icon='plus'
					data-transition="slide" href='create_events.php'>Create Event</a>
				  
					<a data-role='button' data-inline='true' data-icon='action'
					data-transition="slide" href='logout.php'>Log out</a>
					</div>
_LOGGEDIN;	
				break;
				
				case "Super":
				echo <<<_LOGGEDIN
				<div class='center'>
				  <a data-role='button' data-inline='true' data-icon='home'
					data-transition="slide" href='manage_university.php?view=$user'>Manage Universities</a>
				  
				  
				  <a data-role='button' data-inline='true' data-icon='action'
					data-transition="slide" href='logout.php'>Log out</a>
				</div>
_LOGGEDIN;
				break;
				
				case "Student":
				echo <<<_LOGGEDIN
				<div class='center'>
				  <a data-role='button' data-inline='true' data-icon='home'
					data-transition="slide" href='search_events.php?view=$user'>Search Events</a>
				  
				  <a data-role='button' data-inline='true' data-icon='user'
					data-transition="slide" href='join_rso.php'>Join RSO</a>
				  
				  <a data-role='button' data-inline='true' data-icon='action'
					data-transition="slide" href='logout.php'>Log out</a>
				</div>
				
_LOGGEDIN;
				break;
				
		  }
  }
  else
  {
	 echo <<<_GUEST
						<div class='center'>
						  <a data-role='button' data-inline='true' data-icon='home'
							data-transition='slide' href='index.php'>Home</a>
						  <a data-role='button' data-inline='true' data-icon='plus'
							data-transition="slide" href='signup.php'>Sign Up</a>
						  <a data-role='button' data-inline='true' data-icon='check'
							data-transition="slide" href='login.php'>Log In</a>
						</div>
						<p class='info'>(You must be logged in to use this app)</p>
_GUEST; 
  }
  
  
  /*
  //Previous login code
  if (isset($_SESSION['user'])  )
  {
    $user     = $_SESSION['user'];
    $loggedin = TRUE;
    $userstr  = "Logged in as: $user";
  }
  else $loggedin = FALSE;

echo <<<_MAIN
    <title>University Event Manager: $userstr</title>
  </head>
  <body>
    <div data-role='page'>
      <div data-role='header'>
        <div id='logo' class='center'>UNIVERSITY EVENT MANAGER</div>
        <div class='username'>$userstr</div>
      </div>
      <div data-role='content'>

_MAIN;

  if ($loggedin)
  {
echo <<<_LOGGEDIN
        <div class='center'>
          <a data-role='button' data-inline='true' data-icon='home'
            data-transition="slide" href='members.php?view=$user'>Home</a>
          
		  <a data-role='button' data-inline='true' data-icon='user'
            data-transition="slide" href='members.php'>Members</a>
          
		  <a data-role='button' data-inline='true' data-icon='heart'
            data-transition="slide" href='friends.php'>Friends</a><br>
          
		  <a data-role='button' data-inline='true' data-icon='mail'
            data-transition="slide" href='messages.php'>Messages</a>
          
		  <a data-role='button' data-inline='true' data-icon='edit'
            data-transition="slide" href='profile.php'>Edit Profile</a>
          
		  <a data-role='button' data-inline='true' data-icon='action'
            data-transition="slide" href='logout.php'>Log out</a>
        </div>
        
_LOGGEDIN;
  }
  else
  {
echo <<<_GUEST
        <div class='center'>
          <a data-role='button' data-inline='true' data-icon='home'
            data-transition='slide' href='index.php'>Home</a>
          <a data-role='button' data-inline='true' data-icon='plus'
            data-transition="slide" href='signup.php'>Sign Up</a>
          <a data-role='button' data-inline='true' data-icon='check'
            data-transition="slide" href='login.php'>Log In</a>
        </div>
        <p class='info'>(You must be logged in to use this app)</p>
        
_GUEST;
  }
  
*/
?>
