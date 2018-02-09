<?php 

$page_title = 'Edit the fixture';

##CONNECTION TO DB:
include ('mysqli_connect_to_soccer_db.php');

$fixture_id="";
$home_team_id="";
$home_team_name="";
$away_team_id="";
$away_team_name="";
$fixture_date="";
$venue_id="";

# GET METHOD: then retireve and populate
if ( (isset($_GET['fixture_id'])) && (is_numeric($_GET['fixture_id'])) ) 
	{ 
	
	$fixture_id = $_GET['fixture_id'];

	// Retrieve the fixture information which we clicked to edit.
	$query = "SELECT FIXTURE_ID, HOME_TEAM_ID,t1.TEAM_NAME as HOME_TEAM_NAME,AWAY_TEAM_ID,t2.TEAM_NAME as AWAY_TEAM_NAME,MATCH_VENUE_ID,FIXTURE_DATE,v.VENUE_NAME AS MATCH_VENUE FROM fixtures f join teams t1 on t1.TEAM_ID=f.HOME_TEAM_ID join teams t2 on t2.TEAM_ID=f.AWAY_TEAM_ID join
 venues v on f.MATCH_VENUE_ID=v.VENUE_ID WHERE fixture_id=$fixture_id";		
	$result = @mysqli_query ($dbc, $query); // Run the query.

	if (mysqli_num_rows($result) == 1) { // Valid fixture ID, then only show the form.

		// Get the fixture information.
		$row = mysqli_fetch_array ($result, MYSQL_ASSOC);
		$fixture_id=$row['FIXTURE_ID'];
		$home_team_id=$row['HOME_TEAM_ID'];
		$away_team_id=$row['AWAY_TEAM_ID'];
		$fixture_date=$row['FIXTURE_DATE'];
		$venue_id=$row['MATCH_VENUE_ID'];
		}
	else { // Not a valid fixture ID.
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error. Not a valid fixture ID.</p><p><br /><br /></p>';
			echo'<p> <a href="index.php">Go back to Main Menu </a>';
		}
	}

elseif ( (isset($_POST['fixture_id'])) && (is_numeric($_POST['fixture_id'])) ) //POST method
	{        
	$fixture_id=$_POST['fixture_id'];

	}
else 
	{ 
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
		echo'<p> <a href="index.php">Go back to Main Menu </a>';
	exit();
	}
	

##IF FORM IS SUBMITTED: CHECK ERRORS, IF NONE THEN UPDATE
// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize error array.
	
	// Check for home team name.
	if (empty($_POST['home_team_id'])) {
		$errors[] = 'You forgot to enter the name of the home team.';
	} else {
		$home_team_id = $_POST['home_team_id'];
	}
	
		// Check for away team name.
	if (empty($_POST['away_team_id'])) {
		$errors[] = 'You forgot to enter the name of the away team.';
	} else {
		$away_team_id = $_POST['away_team_id'];
	}
	
		## Check for different home and away teams
	if (($_POST['home_team_id'])==($_POST['away_team_id']))
	{
		$errors[] = 'Please select different home and away team names.';
	} 
	
	 //Check for venue id.
	if (empty($_POST['venue_id'])) {
	$errors[] = 'You forgot to enter the name of the venue.';
	} else {
		$venue_id = $_POST['venue_id'];
	}
	
	
	// Check for fixture date
	if (empty($_POST['fixture_date'])) {
		$errors[] = 'You forgot to enter the fixture date.';
	}
	elseif(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_POST['fixture_date']))
	{
		$fixture_date = $_POST['fixture_date'];
	}
	else
	{
		$errors[] = 'You entered an invalid fixture date.';
	}
	
	
	//if no errors in the vlaues selected, updation of the entries
	if (empty($errors)) { // If everything's OK.
	
	
			// Make the query.
			$query = "UPDATE fixtures SET home_team_id='$home_team_id',away_team_id=$away_team_id,FIXTURE_DATE='$fixture_date',match_venue_id=$venue_id WHERE fixture_id = $fixture_id";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			// If query ran OK.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { 
			
				// Print a message.
				echo '<h1 id="mainhead">Edit the Fixture</h1>
				<p>The fixture record has been edited.</p><p><br /><br /></p>';	
							
			} else { // If query did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The fixture could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				
				echo'<p> <a href="view_fixtures.php">Go to View All Fixtures</a>&nbsp;&nbsp;&nbsp;<a href="index.php">Go back to Main Menu </a>';
				
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

	echo "<h2>Edit Fixture Details</h2>
		<form action='edit_fixture.php' method='post'>
		";
		
		echo "<p>Home Team Name: <select name='home_team_id'>";  
		$query = "SELECT TEAM_ID as home_team_id,TEAM_NAME as home_team_name FROM teams ORDER BY home_team_name ASC";
		$result = @mysqli_query ($dbc, $query);
		if ($result)
		{
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		{
			if ($row['home_team_id'] == $home_team_id) 
			{
			echo '<option value="' .$row['home_team_id']. '" selected="selected">' . $row['home_team_name'] . '</option>';
			}
			else 
			{
			echo '<option value="' .$row['home_team_id']. '" >' . $row['home_team_name'] . '</option>';
			}   
		}
	echo '</select> </p>';
		}
		else{
			echo "no results";
		}
	
			echo "<p>Away Team Name: <select name='away_team_id'>";  
		$query = "SELECT TEAM_ID as away_team_id,TEAM_NAME as away_team_name FROM teams ORDER BY away_team_name ASC";
		$result = @mysqli_query ($dbc, $query);
		if ($result)
		{
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		{
			if ($row['away_team_id'] == $away_team_id) 
			{
			echo '<option value="' .$row['away_team_id']. '" selected="selected">' . $row['away_team_name'] . '</option>';
			}
			else 
			{
			echo '<option value="' .$row['away_team_id']. '" >' . $row['away_team_name'] . '</option>';
			}   
		}
	echo '</select> </p>';
		}
		else{
			echo "no results";
		}
	
	echo "<p>Fixture Date: <input type='text' name='fixture_date' size='8' maxlength='10' value='".$fixture_date."'><i> #YYYY-MM-DD</i>";
	
	
				
	echo "<p>Venue Name: <select name='venue_id'>";  
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
	echo "<br/><input type='hidden' name='fixture_id' value='".$fixture_id."'>";	
	echo "<input type='submit' value='Submit!'>";
	echo "<input type='hidden' name='submitted' value='TRUE' />";
	echo "<p> <a href='view_fixtures.php'>Go back to View All Fixtures</a>";
	echo "</form>";		

?>