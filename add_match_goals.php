<?php 

$page_title = 'Add a Match Goal Record';

##CONNECTION TO DB:
include ('mysqli_connect_to_soccer_db.php');

$match_event_id="";
$fixture_id="";
$goal_for="";
$goal_minute="";
$goal_by_player_id="";
$assist_by_player_id="";

##IF FORM IS SUBMITTED: CHECK ERRORS, IF NONE THEN ADD THE NEW COURSE
// Check if the form has been submitted.  
if (isset($_POST['submitted'])) {  
		
	#	$team_id= $_POST['team_id'];
		$fixture_id = $_POST['fixture_id'];
		$goal_for = $_POST['goal_for'];
		$goal_minute = $_POST['goal_minute'];
		$goal_by_player_id = $_POST['goal_by_player_id'];
		$assist_by_player_id = $_POST['assist_by_player_id'];
	   

	// Initialize error array.
	$errors = array(); 
	
	// Check for a course code.
	//if (empty($_POST['team_id'])) {
	//	$errors[] = 'You forgot to enter the team ID.';
	//} else {
	//	$team_id = $_POST['team_id'];
	//}
	
	// Check for fixture id
	if (empty($_POST['fixture_id'])) {
	$errors[] = 'You forgot to enter the name of the fixture.';
	} else {
		$fixture_id = $_POST['fixture_id'];
	}
	
	
	// Check for goal for field.
	
		if(is_numeric($_POST['goal_for']))
	{
		if($_POST['goal_for']==1 or $_POST['goal_for']==0)
		{
		$goal_for = $_POST['goal_for'];
		}
		else
		{
		$errors[] = 'You have entered an invalid goal for value. Enter either 1 or 0';	
		}
	}
	else
	{
		$errors[] = 'You have entered an invalid goal for value. Enter either 1 or 0';
	}
	
	// Check for goal minute field.
		if(is_numeric($_POST['goal_minute']))
	{
		if(($_POST['goal_minute'])>=1 and ($_POST['goal_minute'])<=120)
		{
		$goal_minute = $_POST['goal_minute'];
		}
		else
		{
			$errors[] = 'You have entered an invalid goal minute. Enter value between 1 and 120.';
		}
	}
	else
	{
		$errors[] = 'You have entered an invalid goal minute. Enter value between 1 and 120.';
	}
	
	// Check for goal by player field
	if (empty($_POST['goal_by_player_id'])) {
		$errors[] = 'You forgot to enter the goalscorer.';
	} else {
		$goal_by_player_id = $_POST['goal_by_player_id'];
	}
	
	// Check for assist by player field
	if (empty($_POST['assist_by_player_id'])) {
		$errors[] = 'You forgot to enter the name of the assist player.';
	} else {
		$assist_by_player_id = $_POST['assist_by_player_id'];
	}
	
	#Get the match goal record details: for SUCCESS MESSAGE
	
		$query = "SELECT f.fixture_id,f.home_team_id,f.away_team_id,t1.team_name as home_team,t2.team_name as away_team,
				CONCAT(t1.team_name,' vs ',t2.team_name) as fixture_name from fixtures f join teams t1
				on f.home_team_id=t1.team_id join teams t2 on f.away_team_id=t2.team_id where fixture_id=$fixture_id";

		$result = @mysqli_query ($dbc, $query);
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
			{
				$fixture_name=$row['fixture_name']; 
			}

		$query = "SELECT  CONCAT(p1.FIRST_NAME,' ',p1.LAST_NAME) AS GOALSCORER 
				  FROM  players p1
				  WHERE player_id = $goal_by_player_id";

		$result = @mysqli_query ($dbc, $query);
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
			{
				$goalscorer=$row['GOALSCORER']; 
			}
			
		$query = "SELECT  CONCAT(p1.FIRST_NAME,' ',p1.LAST_NAME) AS ASSIST_BY 
				  FROM  players p1
				  WHERE player_id = $assist_by_player_id";

		$result = @mysqli_query ($dbc, $query);
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
			{
				$assist_by=$row['ASSIST_BY']; 
			}	
	
	if (empty($errors)) { // If everything's OK.
	
	
			// Make the query.
			$query = "INSERT INTO match_goals (fixture_id,goal_for, goal_minute,goal_by_player_id,assist_by_player_id) 
			VALUES ($fixture_id,$goal_for,$goal_minute,$goal_by_player_id,$assist_by_player_id)";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			// If query ran OK.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { 
			
				// Print a message.
				 echo '<h1>Success!</h1>
						<p>You have added:</p>';
						
				echo "<table>
			
				<tr><td>Fixture Name:</td><td>$fixture_name</td></tr>
				<tr><td>Goal For:</td><td>$goal_for</td></tr>
				<tr><td>Goal Minute:</td><td>$goal_minute</td></tr>
				<tr><td>Goalscorer:</td><td>$goalscorer</td></tr>
				<tr><td>Assist By:</td><td>$assist_by</td></tr>
			</table>";
			echo '<p> <a href="add_match_goals.php">Go back to Add a Match Goal Record </a>';
							
			} 
			else { // If query did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The match goal record could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
					echo'<p> <a href="index.php">Go back to Main Menu </a>';
				exit();
			}
				
	}	// End of if (empty($errors)) IF.
	
	else {  // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		} // End of foreach
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	}  // End of report errors else()

}//if submitted section


	
// MAIN FORM
if(!(isset($_POST['submitted'])&&empty($errors))){	

	echo "<h2>Add a Match Goal Record</h2>
		<form action='add_match_goals.php' method='post'>";


	echo "<p>Fixture Name: <select name='fixture_id'>"; 
		$query = " SELECT f.fixture_id,f.home_team_id,f.away_team_id,t1.team_name as home_team,t2.team_name as away_team,
			CONCAT(t1.team_name,' vs ',t2.team_name) as fixture_name from fixtures f join teams t1
			on f.home_team_id=t1.team_id join teams t2 on f.away_team_id=t2.team_id order by fixture_name ASC";
		$result = @mysqli_query ($dbc, $query);
		if ($result)
		{
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		{
			if ($row['fixture_id'] == $fixture_id) 
			{
			echo '<option value="' .$row['fixture_id']. '" selected="selected">' . $row['fixture_name'] . '</option>';
			}
			else 
			{
			echo '<option value="' .$row['fixture_id']. '" >' . $row['fixture_name'] . '</option>';
			}   
		}
	echo '</select> </p>';
		}
		else{
			echo "no results";
		}
		
		echo "<p>Goal For: <input type='text' name='goal_for' size='8' maxlength='2' value='".$goal_for."'><i>#1-Home Team,0-Away Team</i>";
		
		echo "<p>Goal Minute: <input type='text' name='goal_minute' size='8' maxlength='5' value='".$goal_minute."'><i>#Between 1 and 120 inclusive</i>";
	
	
		echo "<p>Goalscorer: <select name='goal_by_player_id'>"; 
		$query = " select p1.player_id as goal_by_player_id,CONCAT(p1.FIRST_NAME,' ',p1.LAST_NAME) as player_name from players p1 ORDER BY player_name ASC";
		$result = @mysqli_query ($dbc, $query);
		if ($result)
		{
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		{
			if ($row['goal_by_player_id'] == $goal_by_player_id) 
			{
			echo '<option value="' .$row['goal_by_player_id']. '" selected="selected">' . $row['player_name'] . '</option>';
			}
			else 
			{
			echo '<option value="' .$row['goal_by_player_id']. '" >' . $row['player_name'] . '</option>';
			}   
		}
	echo '</select> </p>';
		}
		else{
			echo "no results";
		}
	
	
				
	echo "<p>Assist By: <select name='assist_by_player_id'>";  
		$query = "select p1.player_id as assist_by_player_id,CONCAT(p1.FIRST_NAME,' ',p1.LAST_NAME) as player_name from players p1 ORDER BY player_name ASC";
		$result = @mysqli_query ($dbc, $query);
		if ($result)
		{
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		{
			if ($row['assist_by_player_id'] == $assist_by_player_id) 
			{
			echo '<option value="' .$row['assist_by_player_id']. '" selected="selected">' . $row['player_name'] . '</option>';
			}
			else 
			{
			echo '<option value="' .$row['assist_by_player_id']. '" >' . $row['player_name'] . '</option>';
			}   
		}
	echo '</select> </p>';
		}
		else{
			echo "no results";
		}

	echo "<br/><input type='submit' name= 'submitted' value='Add Match Goal Record!'>";

	
	echo "</form>";
		
}
	echo'<p> <a href="view_match_goals.php">Go to View All Match Goal Records</a>&nbsp;&nbsp;&nbsp;<a href="index.php">Go back to Main Menu </a>';

?>