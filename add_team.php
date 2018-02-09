<?php 

$page_title = 'Add a team';

##CONNECTION TO DB:
include ('mysqli_connect_to_soccer_db.php');

$team_id="";
$team_name="";
$league_id="";
$venue_id="";


// Check if the form has been submitted. 

if (isset($_POST['submitted'])) {  
		
	#	$team_id= $_POST['team_id'];
		$team_name = $_POST['team_name'];
		$league_id = $_POST['league_id'];
		$venue_id = $_POST['venue_id'];
	   

	// Initialize error array.
	$errors = array(); 
	
	//if (empty($_POST['team_id'])) {
	//	$errors[] = 'You forgot to enter the team ID.';
	//} else {
	//	$team_id = $_POST['team_id'];
	//}
	
	// Check for team name
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
	
	
	// Check for league id
	if (empty($_POST['league_id'])) {
		$errors[] = 'You forgot to enter the league name.';
	} else {
		$league_id = $_POST['league_id'];
	}
	
	
	// Check for venue id
	if (empty($_POST['venue_id'])) {
		$errors[] = 'You forgot to enter the name of the venue.';
	} else {
		$venue_id = $_POST['venue_id'];
	}
	
	#Get the league details: for SUCCESS MESSAGE
		$query = "SELECT  league_name 
				  FROM  leagues 
				  WHERE league_id = $league_id";

		$result = @mysqli_query ($dbc, $query);
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
			{
				$league_name=$row['league_name']; 
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
			$query = "INSERT INTO teams (team_name, league_id, home_ground_venue_id) 
			VALUES ('$team_name', '$league_id', '$venue_id')";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			// If query ran OK.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { 
			
				// Print a message.
				 echo '<h1>Success!</h1>
						<p>You have added:</p>';
						
				echo "<table>
			
				<tr><td>Team Name:</td><td>$team_name</td></tr>
				<tr><td>League Name:</td><td>$league_name</td></tr>
				<tr><td>Venue Name:</td><td>$venue_name</td></tr>
			</table>";
			echo '<p> <a href="add_team.php">Go back to Add a Team </a>';
							
			} 
			else { // If query did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The team could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
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
		
	}  // End of report errors else()

}//if submitted section


	
// MAIN FORM
if(!(isset($_POST['submitted'])&&empty($errors))){	

	echo "<h2>Add a team</h2>
		<form action='add_team.php' method='post'>


	<p>Team name: <input type='text' name='team_name' size='35' maxlength='35' value='"; 
	if (isset($team_name))
		{
			echo $team_name; 
		}	 
	
	echo "'>";

		
echo "<p>League Name: <select name='league_id'>";  
		$query = "SELECT LEAGUE_ID as league_id, LEAGUE_NAME as league_name  FROM leagues ORDER BY league_name ASC";
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
	echo '</select></p>';
		}
		else{
			echo "no results";
		}
		
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
	echo "<br/><input type='submit' name= 'submitted' value='Add Team!'>";
	
	echo "</form>";
		
}
	echo'<p> <a href="view_teams.php">Go to View All Teams</a>&nbsp;&nbsp;&nbsp;<a href="index.php">Go back to Main Menu </a>';

?>