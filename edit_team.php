<?php 

$page_title = 'Edit the team';

##CONNECTION TO DB:
include ('mysqli_connect_to_soccer_db.php');

$team_id="";
$team_name="";
$league_id="";
$venue_id="";

if ( (isset($_GET['team_id'])) && (is_numeric($_GET['team_id'])) )  // Already been determined
	{ 
	
	$team_id = $_GET['team_id'];

	// Retrieve the team's information which we clicked to edit.
	$query = "SELECT TEAM_ID, TEAM_NAME,t.LEAGUE_ID as LEAGUE_ID, l.LEAGUE_NAME,t.HOME_GROUND_VENUE_ID as VENUE_ID,v.VENUE_NAME as VENUE_NAME FROM teams t join venues v on t.HOME_GROUND_VENUE_ID=v.VENUE_ID JOIN leagues l on t.LEAGUE_ID = l.LEAGUE_ID WHERE team_id=$team_id";		
	$result = @mysqli_query ($dbc, $query); // Run the query.

	if (mysqli_num_rows($result) == 1) { // Valid team ID, then only show the form.

		// Get the team's information.
		$row = mysqli_fetch_array ($result, MYSQL_ASSOC);
		$team_id=$row['TEAM_ID'];
		$team_name=$row['TEAM_NAME'];
		$league_id=$row['LEAGUE_ID'];
		$venue_id=$row['VENUE_ID'];
		}
	else { // Not a valid team ID.
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error. Not a valid team ID.</p><p><br /><br /></p>';
		echo'<p> <a href="index.php">Go back to Main Menu </a>';
		}
	}

elseif ( (isset($_POST['team_id'])) && (is_numeric($_POST['team_id'])) ) 
	{        
	$team_id=$_POST['team_id'];
	}
else 
	{ 
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
	exit();
	}
	

##IF FORM IS SUBMITTED: CHECK ERRORS, IF NONE THEN UPDATE
// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize error array.
	
	// Check for a team name.
	if (empty($_POST['team_name'])) {
		$errors[] = 'You forgot to enter the name of the team.';
	}
	elseif(is_numeric($_POST['team_name']))
	{
		$errors[] = 'Please enter a valid alphabetical value.';
	}		
	else {
		$team_name = $_POST['team_name'];
	}
	
	 //Check for venue id.
	if (empty($_POST['venue_id'])) {
	$errors[] = 'You forgot to enter the name of the venue.';
	} else {
		$venue_id = $_POST['venue_id'];
	}
	
	
	// Check for a team id.
	if (empty($_POST['team_id'])) {
		$errors[] = 'You forgot to enter the team ID.';
	} else {
		$course_credits = $_POST['team_id'];
	}
	
	
	// Check for league id.
	if (empty($_POST['league_id'])) {
		$errors[] = 'You forgot to enter the name of the league.';
	} else {
		$league_id = $_POST['league_id'];
	}
	
	//if no errors in the values selected, updation of the entries
	if (empty($errors)) { 
	
	
			// Make the update query.
			$query = "UPDATE teams SET team_name='$team_name',league_id=$league_id,home_ground_venue_id=$venue_id WHERE team_id = $team_id";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			// If query ran OK.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { 
			
				// Print a message.
				echo '<h1 id="mainhead">Edit a 	Team</h1>
				<p>The team record has been edited.</p><p><br /><br /></p>';	
							
			} else { // If query did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The team could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				
				echo'<p> <a href="view_teams.php">Go to View All Teams</a>&nbsp;&nbsp;&nbsp;<a href="index.php">Go back to Main Menu </a>';
				
				exit();
			}
				
	}	
	else {  // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		} 
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	}  

}//if submitted section

	
// MAIN FORM	

	echo "<h2>Edit Team Details</h2>
		<form action='edit_team.php' method='post'>
		";
		
	echo "<p>Team Name: <input type='text' name='team_name' size='35' maxlength='35' value='".$team_name."'><br/> ";
		
	echo "<p>League Name: <select name = 'league_id'>";  //league names	
$query = "SELECT LEAGUE_ID as league_id,LEAGUE_NAME as league_name FROM leagues ORDER BY league_name ASC";
		$result = @mysqli_query ($dbc, $query);
		if ($result)
		{
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		{
			if ($row['league_id'] == $league_id) 
			{
			echo '<option value="' .$row['league_id']. '" selected="selected">' . $row['league_name'] . '</option>';
			}
			else 
			{
			echo '<option value="' .$row['league_id']. '" >' . $row['league_name'] . '</option>';
			}   
		}
	echo '</select> </p>';
		}
		else{
			echo "no results";
		}
		
	echo "<p>Venue Name: <select name='venue_id'>";  //venue NAMES
		$query = "SELECT VENUE_ID as venue_id,VENUE_NAME as venue_name FROM venues ORDER BY venue_name ASC";
		$result = @mysqli_query ($dbc, $query);
		if ($result)
		{
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		{
			if ($row['venue_id'] == $venue_id) 
			{
			echo '<option value="' .$row['venue_id']. '" selected="selected">' . $row['venue_name'] . '</option>';
			}
			else 
			{
			echo '<option value="' .$row['venue_id']. '" >' . $row['venue_name'] . '</option>';
			}   
		}
	echo '</select> </p>';
		}
		else{
			echo "no results";
		}
	echo "<br/><input type='hidden' name='team_id' value='".$team_id."'>";	
	echo "<input type='submit' value='Submit!'>";
	echo "<input type='hidden' name='submitted' value='TRUE' />";
	echo "<p> <a href='view_teams.php'>Go back to View All Teams</a>";
	echo "</form>";
		
	


?>