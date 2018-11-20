<?php //comments.php
//view all my comments
//insert a comments
//view comments for a particular event


require_once 'header.php';
require_once 'functions.php';

$queryComments = $university_name = $error1 = $error2 = $error3 = $rating = $comment = $event_number = $comment_number_to_delete = $comment_number_to_modify = $rating_to_modify = $comment_to_modify = "";

if(isset($_SESSION['user']))
  { 
  $user = $_SESSION['user'];
  }
  
  //display all of the comments for the user for their university id
  
  $queryComments = queryMysql("SELECT Comment_ID, Event_ID, Student_ID, Comments, Stars FROM Comments WHERE University_ID = (SELECT University_ID FROM Student where User_Name  = '$user')");
  //$university_name = queryMysql("SELECT University_ID FROM Students where User_Name  = '$user'");
  
  
  if ($queryComments->num_rows != 0) 
	{
		echo "List of all events available to comment on for : '$user'";
		echo "<br>";
		echo "<br>";
		while($row = $queryComments->fetch_assoc()) 
		{
			echo "Comment_ID: " . $row["Comment_ID"] . "<br>";
			echo "Event ID: " . $row["Event_ID"] . "<br>";
			echo "Student_ID: " . $row["Student_ID"]. "<br>"; 
			echo "Comments: " . $row["Comments"]. "<br>";
			echo "Stars: " . $row["Stars"]. "<br>";
			echo "<hr>";
		}
	}else{
		echo "0 results";
	}
	
	//if they want to create a comment for an existing event
	if ( isset($_POST['event_number']))
	{
		
		//grab the input from the user and sanitize it
		$event_number = sanitizeString($_POST['event_number']);
		$comment = sanitizeString($_POST['comment']);
		$rating = sanitizeString($_POST['rating']);
		
		if($event_number == "" || $comment == "" || $rating == "" )
		{
			$error1 = 'Not all fields were entered.<br><br>';
		}
		else{//input the event into the database.  
			//echo "in the else<br>";
			//Insert the comment into the database
			queryMysql("INSERT INTO Comments 
						(Event_ID, Student_ID, Comments, Stars, University_ID) 
						VALUES (
						'$event_number', (SELECT Student_ID FROM Student WHERE User_Name = '$user'), '$comment', '$rating', (SELECT University_ID FROM Student where User_Name  = '$user') )");
		
		
		
		}
	}
	
	
	//if they want to delete a comment that they created
	if ( isset($_POST['comment_number_to_delete']) )
	{
		//sanitize the input
		$comment_number_to_delete = sanitizeString($_POST['comment_number_to_delete']);
		
		if($comment_number_to_delete == "" ){
			$error2 = 'Not all fields were entered.<br><br>';
		}else{
			//we know its not empty since we already ran an isset test for the if statment.  At this point we can go ahead and run the queryComments
		//We delete the comment that the user made.  
			$delete_query = queryMysql("DELETE FROM Comments WHERE Comment_ID = '$comment_number_to_delete' AND Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = '$user')");
		}
		
	}
	
	//Let the user modify a comment that they have created
	if ( isset($_POST['comment_number_to_modify']) )
	{
		//sanitize the user input
		$comment_number_to_modify = sanitizeString($_POST['comment_number_to_modify']);
		$comment_to_modify = sanitizeString($_POST['comment_to_modify']);
		$rating_to_modify = sanitizeString($_POST['rating_to_modify']);
		
		/*
		echo $comment_to_modify;
		echo $comment_number_to_modify;
		echo $rating_to_modify;
		*/
		
		//check if the user input is empty
		if($comment_to_modify == "" || $rating_to_modify == "" || $comment_number_to_modify == "" )
		{
			$error3 = 'Not all fields were entered.<br><br>';
		}else
		{
			echo "in the else";
			//queryMysql("");
			queryMysql("UPDATE Comments SET Comments = '$comment_to_modify' WHERE Comment_ID = '$comment_number_to_modify' AND Student_ID = (SELECT Student_ID FROM Student WHERE User_Name = '$user')");
		}
		
		
	}
  
  echo <<<_ADD
      <form method='post' action='create_comments.php'>
        <div data-role='fieldcontain'>
          <label></label>
          <span class='error'>$error1</span>
        </div>
        <div data-role='fieldcontain'>
          <label></label>
          Add a Comment about an Event
        </div>
		<div data-role='fieldcontain'>
          <label>Event Number</label>
          <input type='number' maxlength='30' name='event_number' value='$event_number'>
        </div>
        <div data-role='fieldcontain'>
          <label>Comments</label>
          <input type='text' maxlength='100' name='comment' value='$comment'>
        </div>
		<div data-role='fieldcontain'>
          <label>Rating</label>
          <input type='number' min = "1" max= "5" name='rating' value='$rating'>
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
      <form method='post' action='create_comments.php'>
        <div data-role='fieldcontain'>
          <label></label>
          <span class='error'>$error2</span>
        </div>
        <div data-role='fieldcontain'>
          <label></label>
          Delete a Comment by Comment ID
        </div>
		<div data-role='fieldcontain'>
          <label>Comment_ID</label>
          <input type='number' maxlength='30' name='comment_number_to_delete' value='$comment_number_to_delete'>
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

	echo <<<_MODIFY
      <form method='post' action='create_comments.php'>
        <div data-role='fieldcontain'>
          <label></label>
          <span class='error'>$error3</span>
        </div>
        <div data-role='fieldcontain'>
          <label></label>
          Modify a Comment by Comment ID
        </div>
		<div data-role='fieldcontain'>
          <label>Comment_ID</label>
          <input type='number' maxlength='30' name='comment_number_to_modify' value='$comment_number_to_modify'>
        </div>
		<div data-role='fieldcontain'>
          <label>Comment</label>
          <input type='text' maxlength='100' name='comment_to_modify' value='$comment_to_modify'>
        </div>
		<div data-role='fieldcontain'>
          <label>Rating</label>
          <input type='number' maxlength='30' name='rating_to_modify' value='$rating_to_modify'>
        </div>
		<div data-role='fieldcontain'>
          <label></label>
          <input data-transition='slide' type='submit' value='Submit'>
        </div>
      </form>
    </div>
  </body>
</html>
_MODIFY;

?>