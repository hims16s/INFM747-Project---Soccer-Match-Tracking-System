<?php 

$page_title = 'Delete the match goal record';

##CONNECTION TO DB:
include ('mysqli_connect_to_soccer_db.php');

$match_event_id="";
$fixture_id="";
$fixture_name="";
$goal_for="";
$goal_minute="";
$goal_by_player_id="";
$assist_by_player_id="";

# GET METHOD: then retireve and populate
if ( (isset($_GET['match_event_id'])) && (is_numeric($_GET['match_event_id'])) )  
	{ 
	
	$match_event_id = $_GET['match_event_id'];

	// Retrieve the match goal record information which we clicked to delete.
	$query = " SELECT MATCH_EVENT_ID,m.FIXTURE_ID,t1.TEAM_NAME as HOME_TEAM,t2.TEAM_NAME as AWAY_TEAM,
	CONCAT(t1.team_name,' vs ',t2.team_name) as FIXTURE_NAME,
 GOAL_FOR,GOAL_MINUTE,CONCAT(p1.FIRST_NAME,' ',p1.LAST_NAME) AS GOALSCORER,
 CONCAT(p2.FIRST_NAME,' ',p2.LAST_NAME) as ASSIST_BY  FROM match_goals m join players p1
 on p1.player_id=m.GOAL_BY_PLAYER_ID join players p2
 on p2.player_id=m.ASSIST_BY_PLAYER_ID join fixtures f on f.FIXTURE_ID=m.FIXTURE_ID join teams t1 
 on t1.TEAM_ID=f.HOME_TEAM_ID join teams t2
 on t2.TEAM_ID=f.AWAY_TEAM_ID  WHERE match_event_id=$match_event_id";
			  
	$result = @mysqli_query ($dbc, $query); // Run the query.

	if (mysqli_num_rows($result) == 1) { // Valid match event ID, then only show the form.

		// Get the match goal record information.
		$row = mysqli_fetch_array ($result, MYSQL_ASSOC);
		$match_event_id=$row['MATCH_EVENT_ID'];
		$fixture_id=$row['FIXTURE_ID'];	
		$fixture_name=$row['FIXTURE_NAME'];	
		$goal_for=$row['GOAL_FOR'];
		$goal_minute=$row['GOAL_MINUTE'];
	//	$goal_by_player_id=$row['GOAL_BY_PLAYER_ID'];
		$goalscorer=$row['GOALSCORER'];
	//	$assist_by_player_id=$row['ASSIST_BY_PLAYER_ID'];
		$assist_by=$row['ASSIST_BY'];
		}
	else { // Not a valid match event ID.
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error. Not a valid match event ID #1.</p><p><br /><br /></p>';
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
	<p class="error">This page has been accessed in error #2.</p><p><br /><br /></p>';
		echo'<p> <a href="index.php">Go back to Main Menu </a>';
	exit();
	}
	

##IF FORM IS SUBMITTED: CHECK YES/NO SURE, THEN DELETE POST METHOD:
// Check if the form has been submitted.
if (isset($_POST['submitted'])) {
					
			$query = "SELECT match_event_id,goal_for,goal_minute
					 FROM match_goals WHERE match_event_id=$match_event_id";	
					 
		   $result = @mysqli_query ($dbc, $query); // Run the query.
		   if (mysqli_num_rows($result) == 1) { // Valid match event ID, show the result.

			// Get the match goal record information.
			$row = mysqli_fetch_array ($result, MYSQL_ASSOC);

			$match_event_id=$row['match_event_id'];
			$goal_for=$row['goal_for'];
			$goal_minute=$row['goal_minute'];
			
			
		   }
		   else { 
					echo '<h1 id="mainhead">Page Error</h1>
			<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
				echo'<p> <a href="index.php">Go back to Main Menu </a>';
			}
			
		
			
	//Check if sure!
		if ($_POST['sure'] == 'Yes') { // Delete them.
			
			//query for deletion
			$query = "DELETE FROM match_goals WHERE match_event_id=$match_event_id";		
			$result_del = @mysqli_query ($dbc, $query); // Run the query.
			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			
				$row = mysqli_fetch_array ($result, MYSQL_NUM);
			
				// Create the result page.
				echo '<h1 id="mainhead">Delete the Match Goal Record</h1>
				<p>The match goal record for Match Event ID <b>'.$match_event_id.'</b> has been deleted.</p><p><br /><br /></p>';
				echo '<p> <a href="view_match_goals.php">Go back to View All Match Goal Records </a>';
			} 
			else { // Did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The match goal record could not be deleted due to a system error.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
					echo'<p> <a href="index.php">Go back to Main Menu </a>';
			}
		
		} // End of $_POST['sure'] == 'Yes' if().
		
		else  // Wasn't sure about deleting the team.
		{ 
			echo '<h1 id="mainhead">Delete the Match Goal Record</h1>';
	
				// Create the result page.
				echo'<p>The match goal record for Match Event ID <b>'.$match_event_id.'</b> has NOT been deleted.</p><p><br /><br /></p>';
				echo '<p> <a href="view_match_goals.php">Go back to View All Match Goal Records </a>';
			

		} // End of wasn’t sure else().


}//if submitted section


	
// MAIN FORM	
if((isset($_GET['match_event_id']))  && (is_numeric($_GET['match_event_id'])) ){
	echo '<h2>Delete the Match Goal Record</h2>
	<form action="delete_match_goals.php" method="post">
	<h3>Match Event ID: ' . $match_event_id . '</h3>
	<h3>Fixture Name: ' . $fixture_name . '</h3>
	<h3>Goal For: ' . $goal_for. '</h3>
	<h3>Goal Minute: ' . $goal_minute . '</h3>
		<h3>GoalScorer: ' . $goalscorer . '</h3>
			<h3>Assist By: ' . $assist_by . '</h3>
	
	
	<p>Are you sure you want to delete this Match Goal Record?<br />
	<input type="radio" name="sure" value="Yes" /> Yes 
	<input type="radio" name="sure" value="No" checked="checked" /> No</p>
	
	<p><input type="submit" name="submit" value="Submit!" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="match_event_id" value="' . $match_event_id . '" />  
	<p> <a href="view_match_goals.php">Go back to View All Match Goal Records </a>
	</form>'; 
			
}
?>