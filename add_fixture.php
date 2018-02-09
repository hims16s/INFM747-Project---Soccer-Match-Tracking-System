<?php 

$page_title = 'Add a fixture';

##CONNECTION TO DB:
include ('mysqli_connect_to_soccer_db.php');

$team_id="";
$home_team_id="";
$away_team_id="";
$fixture_date="";
$venue_id="";


// Check if the form has been submitted.  
if (isset($_POST['submitted'])) {  
		
		$home_team_id = $_POST['home_team_id'];
		$away_team_id = $_POST['away_team_id'];
		$fixture_date = $_POST['fixture_date'];
		$venue_id = $_POST['venue_id'];
	   
	// Initialize error array.
	$errors = array(); 
	
	//if (empty($_POST['team_id'])) {
	//	$errors[] = 'You forgot to enter the team ID.';
	//} else {
	//	$team_id = $_POST['team_id'];
	//}
	
	// Check for home team id
	if (empty($_POST['home_team_id'])) {
		$errors[] = 'You forgot to enter the home team name.';
	} else {
		$home_team_id = $_POST['home_team_id'];
	}
	
	
	// Check for away team id
	if (empty($_POST['away_team_id'])) {
		$errors[] = 'You forgot to enter the away team name.';
	} else {
		$away_team_id = $_POST['away_team_id'];
	}
	
	## Check for different home and away teams
	if (($_POST['home_team_id'])==($_POST['away_team_id']))
	{
		$errors[] = 'Please select different home and away team names.';
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
	
	
	// Check for venue id
	if (empty($_POST['venue_id'])) {
		$errors[] = 'You forgot to enter the name of the venue.';
	} else {
		$venue_id = $_POST['venue_id'];
	}
	
	#Get the fixture details: for SUCCESS MESSAGE
	
		$query = "SELECT  team_name as home_team_name 
				  FROM  teams 
				  WHERE team_id = $home_team_id";

		$result = @mysqli_query ($dbc, $query);
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
			{
				$home_team_name=$row['home_team_name']; 
			}

		$query = "SELECT  team_name as away_team_name 
				  FROM  teams 
				  WHERE team_id = $away_team_id";

		$result = @mysqli_query ($dbc, $query);
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
			{
				$away_team_name=$row['away_team_name']; 
			}
			
				#Get the venue details: for SUCCESS MESSAGE
		$query = "SELECT  venue_name 
				  FROM  venues 
				  WHERE venue_id = $venue_id";

		$result = @mysqli_query ($dbc, $query);
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
			{
				$venue_name=$row['venue_name']; 
			}	
	
	//if no errors in the vlaues selected, updation of the entries
	if (empty($errors)) { // If everything's OK.
	
	
			// Make the query.
			$query = "INSERT INTO fixtures (home_team_id, away_team_id, fixture_date,match_venue_id) 
			VALUES ('$home_team_id',$away_team_id,'$fixture_date', '$venue_id')";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			// If query ran OK.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { 
			
				// Print a message.
				 echo '<h1>Success!</h1>
						<p>You have added:</p>';
						
				echo "<table>
			
				<tr><td>Home Team Name:</td><td>$home_team_name</td></tr>
				<tr><td>Away Team Name:</td><td>$away_team_name</td></tr>
				<tr><td>Fixture Date:</td><td>$fixture_date</td></tr>
				<tr><td>Venue Name:</td><td>$venue_name</td></tr>
			</table>";
			echo '<p> <a href="add_fixture.php">Go back to Add a Fixture </a>';
							
			} 
			else { // If query did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The fixture could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
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
if(!(isset($_POST['submitted'])&&empty($errors))){	

	echo "<h2>Add a fixture</h2>
		<form action='add_fixture.php' method='post'>";


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
		$query = "SELECT VENUE_ID as venue_id, VENUE_NAME as venue_name  FROM venues ORDER BY venue_name ASC";
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
	echo '</select></p>';
		}
		else{
			echo "no results";
		}
	//echo "<br/>";
	echo "<br/><input type='submit' name= 'submitted' value='Add Fixture!'>";

	
	echo "</form>";
		
}
	echo'<p> <a href="view_fixtures.php">Go to View All Fixtures</a>&nbsp;&nbsp;&nbsp;<a href="index.php">Go back to Main Menu </a>';

?>