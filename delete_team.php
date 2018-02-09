<?php 

$page_title = 'Delete the team';

##CONNECTION TO DB:
include ('mysqli_connect_to_soccer_db.php');

$team_id="";
$team_name="";
$league_id="";
$venue_id="";

# GET METHOD: 
if ( (isset($_GET['team_id'])) && (is_numeric($_GET['team_id'])) )  
	{ 
	
	$team_id = $_GET['team_id'];

	// Retrieve the team's information which we clicked to delete.
	$query = "SELECT TEAM_ID, TEAM_NAME,t.LEAGUE_ID as LEAGUE_ID, l.LEAGUE_NAME as LEAGUE_NAME,t.HOME_GROUND_VENUE_ID as VENUE_ID,v.VENUE_NAME as VENUE_NAME FROM teams t join venues v on t.HOME_GROUND_VENUE_ID=v.VENUE_ID JOIN leagues l on t.LEAGUE_ID = l.LEAGUE_ID WHERE team_id=$team_id";
			  
	$result = @mysqli_query ($dbc, $query); // Run the query.

	if (mysqli_num_rows($result) == 1) { // Valid team ID, then only show the form.

		// Get the team's information.
		$row = mysqli_fetch_array ($result, MYSQL_ASSOC);
		$team_id=$row['TEAM_ID'];
		$team_name=$row['TEAM_NAME'];
		$league_name=$row['LEAGUE_NAME'];
		$venue_name=$row['VENUE_NAME'];
		}
	else { 
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error. Not a valid team ID #1.</p><p><br /><br /></p>';
			echo'<p> <a href="index.php">Go back to Main Menu </a>';
		}
	}

elseif ( (isset($_POST['team_id'])) && (is_numeric($_POST['team_id'])) ) //POST method
	{        
	$team_id=$_POST['team_id'];
	}
else 
	{ 
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error #2.</p><p><br /><br /></p>';
		echo'<p> <a href="index.php">Go back to Main Menu </a>';
	exit();
	}
	

##IF FORM IS SUBMITTED: CHECK YES/NO SURE, THEN DELETE POST METHOD:
// Check if the form has been submitted.
if (isset($_POST['submitted'])) {
			
			$query = "SELECT team_id,team_name
					 FROM teams WHERE team_id=$team_id";	
					 
		   $result = @mysqli_query ($dbc, $query); // Run the query.
		   if (mysqli_num_rows($result) == 1) { // Valid team ID, show the result.

			// Get the team information.
			$row = mysqli_fetch_array ($result, MYSQL_ASSOC);

			$team_id=$row['team_id'];
			$team_name=$row['team_name'];
			
		   }
		   else { 
					echo '<h1 id="mainhead">Page Error</h1>
			<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
			}
			
			
	//Check if sure!
		if ($_POST['sure'] == 'Yes') { 
			
			//query for deletion
			$query = "DELETE FROM teams WHERE team_id=$team_id";		
			$result_del = @mysqli_query ($dbc, $query); // Run the query.
			if (mysqli_affected_rows($dbc) == 1) { // If it ran successfully.

				// Get the team information.
				$row = mysqli_fetch_array ($result, MYSQL_NUM);
			
				// Create the result page.
				echo '<h1 id="mainhead">Delete a Team</h1>
				<p>The team <b>'.$team_name.'</b> has been deleted.</p><p><br /><br /></p>';
				echo '<p> <a href="view_teams.php">Go back to View All Teams </a>';
			} 
			else { // Did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The team could not be deleted due to a system error.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				echo '<p> <a href="view_teams.php">Go back to View All Teams </a>';
			}
		
		} 
		
		else  // Not sure about deleting the team.
		{ 
			echo '<h1 id="mainhead">Delete the Team</h1>';
	
				// Create the result page.
				echo'<p>The team <b>'.$team_name.'</b> has NOT been deleted.</p><p><br /><br /></p>';
				echo '<p> <a href="view_teams.php">Go back to View All Teams </a>';
			

		} // End of wasn’t sure else().


}//if submitted section


	
// MAIN FORM	
if((isset($_GET['team_id']))  && (is_numeric($_GET['team_id'])) ){
	echo '<h2>Delete the team</h2>
	<form action="delete_team.php" method="post">
	<h3>Team ID: ' . $team_id . '</h3>
	<h3>Team Name: ' . $team_name . '</h3>
	<h3>League Name: ' . $league_name. '</h3>
	<h3>Venue Name: ' . $venue_name . '</h3>
	
	
	<p>Are you sure you want to delete this Team?<br />
	<input type="radio" name="sure" value="Yes" /> Yes 
	<input type="radio" name="sure" value="No" checked="checked" /> No</p>
	
	<p><input type="submit" name="submit" value="Submit!" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="team_id" value="' . $team_id . '" />  
	<p> <a href="view_teams.php">Go back to View All Teams </a>
	</form>'; 
	
}

?>