<?php 

$page_title = 'Edit the Match Goals Records';

##CONNECTION TO DB:
include ('mysqli_connect_to_soccer_db.php');

$match_event_id="";
$home_team_id="";
$home_team_name="";
$away_team_id="";
$away_team_name="";
$goal_for="";
$goal_minute="";
$goal_by_player_id="";
$goalscorer="";
$assist_by_player_id="";
$assist_by="";

# GET METHOD: then retireve and populate
if ( (isset($_GET['match_event_id'])) && (is_numeric($_GET['match_event_id'])) ) 
	{ 
	
	$match_event_id = $_GET['match_event_id'];

	// Retrieve the match goal record's information which we clicked to edit.
	$query = " SELECT MATCH_EVENT_ID,m.FIXTURE_ID as FIXTURE_ID,t1.TEAM_ID as HOME_TEAM_ID,t2.TEAM_ID as AWAY_TEAM_ID,t1.TEAM_NAME as HOME_TEAM,t2.TEAM_NAME as AWAY_TEAM,
 m.GOAL_FOR as GOAL_FOR,m.GOAL_MINUTE as GOAL_MINUTE,CONCAT(p1.FIRST_NAME,' ',p1.LAST_NAME) AS GOALSCORER,p1.player_id as GOAL_BY_PLAYER_ID,p2.player_id as ASSIST_BY_PLAYER_ID,
 CONCAT(p2.FIRST_NAME,' ',p2.LAST_NAME) as ASSIST_BY  FROM match_goals m join players p1
 on p1.player_id=m.GOAL_BY_PLAYER_ID join players p2
 on p2.player_id=m.ASSIST_BY_PLAYER_ID join fixtures f on f.FIXTURE_ID=m.FIXTURE_ID join teams t1 
 on t1.TEAM_ID=f.HOME_TEAM_ID join teams t2
 on t2.TEAM_ID=f.AWAY_TEAM_ID WHERE match_event_id=$match_event_id";		
	$result = @mysqli_query ($dbc, $query); // Run the query.

	if (mysqli_num_rows($result) == 1) { // Valid match event ID, then only show the form.

		// Get the match goal record's information.
		$row = mysqli_fetch_array ($result, MYSQL_ASSOC);
		$match_event_id=$row['MATCH_EVENT_ID'];
		$fixture_id=$row['FIXTURE_ID'];
		$home_team_id=$row['HOME_TEAM_ID'];
	//	$home_team_name=$row['HOME_TEAM'];
		$away_team_id=$row['AWAY_TEAM_ID'];
	//	$away_team_name=$row['AWAY_TEAM'];
		$goal_for=$row['GOAL_FOR'];
		$goal_minute=$row['GOAL_MINUTE'];
		$goal_by_player_id=$row['GOAL_BY_PLAYER_ID'];
	//	$goalscorer=$row['GOALSCORER'];
		$assist_by_player_id=$row['ASSIST_BY_PLAYER_ID'];
		//$assist_by=$row['ASSIST_BY'];
		}
	else { // Not a valid match event ID.
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error. Not a valid match event ID.</p><p><br /><br /></p>';
			echo'<p> <a href="index.php">Go back to Main Menu </a>';
		}
	}

elseif ( (isset($_POST['match_event_id'])) && (is_numeric($_POST['match_event_id'])) ) //POST method
	{        
	$match_event_id=$_POST['match_event_id'];
	}
else 
	{ // No valid ID, kill the script.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
		echo'<p> <a href="index.php">Go back to Main Menu </a>';
	exit();
	}
	

##IF FORM IS SUBMITTED: CHECK ERRORS, IF NONE THEN UPDATE
// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize error array.
	
	// Check for a team name.
	//if (empty($_POST['home_team_id'])) {
		//$errors[] = 'You forgot to enter the name of the home team.';
	//} else {
	//	$home_team_id = $_POST['home_team_id'];
	//}
	
	
	//if (empty($_POST['away_team_id'])) {
		//$errors[] = 'You forgot to enter the name of the away team.';
	//} else {
		//$away_team_id = $_POST['away_team_id'];
	//}
	
	
	 //Check for fixture id.
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
		$errors[] = 'You forgot to enter the name of the player who gave assist .';
	} else {
		$assist_by_player_id = $_POST['assist_by_player_id'];
	}
	
	//if (empty($_POST['goalscorer'])) {
	//	$errors[] = 'You forgot to enter the goalscorer.';
	//} else {
	//	$goalscorer = $_POST['goalscorer'];
	//}
	
	//if (empty($_POST['assist_by'])) {
	//	$errors[] = 'You forgot to enter the goalscorer.';
	//} else {
	//	$assist_by = $_POST['assist_by'];
	//}
	
	
	//if no errors in the vlaues selected, updation of the entries
	if (empty($errors)) { // If everything's OK.
	
	
			// Make the query.
			$query = "UPDATE match_goals SET goal_for=$goal_for,goal_minute=$goal_minute,goal_by_player_id=$goal_by_player_id,assist_by_player_id=$assist_by_player_id WHERE match_event_id = $match_event_id";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			// If query ran OK.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { 
			
				// Print a message.
				echo '<h1 id="mainhead">Edit the Match Goal Record</h1>
				<p>The match goal record has been edited.</p><p><br /><br /></p>';	
							
			} else { // If query did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The match goal record could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
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

	echo "<h2>Edit Match Goal Record Details</h2>
		<form action='edit_match_goals.php' method='post'>
		";
		
		#Fixture name is not editable. It is fixed
		echo "<p>Fixture Name: <select name='fixture_id'>";  
		$query = " SELECT f.fixture_id,f.home_team_id,f.away_team_id,t1.team_name as home_team,t2.team_name as away_team,
			CONCAT(t1.team_name,' vs ',t2.team_name) as fixture_name from fixtures f join teams t1
			on f.home_team_id=t1.team_id join teams t2 on f.away_team_id=t2.team_id where fixture_id=$fixture_id";
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
		$query = " select distinct p1.player_id as goal_by_player_id,CONCAT(p1.FIRST_NAME,' ',p1.LAST_NAME) as player_name from players p1 
		join fixtures f1  on p1.team_id=f1.home_team_id or p1.team_id=f1.away_team_id
		join match_goals m on m.fixture_id = f1.fixture_id where m.fixture_id=$fixture_id ORDER BY player_name ASC";
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
		$query = " select distinct p1.player_id as assist_by_player_id,CONCAT(p1.FIRST_NAME,' ',p1.LAST_NAME) as player_name from players p1 
		join fixtures f1  on p1.team_id=f1.home_team_id or p1.team_id=f1.away_team_id
		join match_goals m on m.fixture_id = f1.fixture_id where m.fixture_id=$fixture_id ORDER BY player_name ASC";
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
	echo "<br/><input type='hidden' name='match_event_id' value='".$match_event_id."'>";	
	echo "<input type='submit' value='Submit!'>";
	echo "<input type='hidden' name='submitted' value='TRUE' />";
	echo "<p> <a href='view_match_goals.php'>Go back to View All Match Goal Records</a>";
	echo "</form>";
		
	


?>