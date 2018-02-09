<?php 

$page_title = 'Delete the fixture';

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

	// Retrieve the fixture information which we clicked to delete.
	$query = "SELECT FIXTURE_ID, HOME_TEAM_ID,t1.TEAM_NAME as HOME_TEAM_NAME,AWAY_TEAM_ID,t2.TEAM_NAME as AWAY_TEAM_NAME,MATCH_VENUE_ID,FIXTURE_DATE,v.VENUE_NAME AS MATCH_VENUE FROM fixtures f join teams t1 on t1.TEAM_ID=f.HOME_TEAM_ID join teams t2 on t2.TEAM_ID=f.AWAY_TEAM_ID join
 venues v on f.MATCH_VENUE_ID=v.VENUE_ID WHERE fixture_id=$fixture_id";
			  
	$result = @mysqli_query ($dbc, $query); // Run the query.

	if (mysqli_num_rows($result) == 1) { // Valid fixture ID, then only show the form.

		// Get the fixture's information.
		$row = mysqli_fetch_array ($result, MYSQL_ASSOC);
		$fixture_id=$row['FIXTURE_ID'];
		$home_team_id=$row['HOME_TEAM_ID'];
		$home_team_name=$row['HOME_TEAM_NAME'];
		$away_team_id=$row['AWAY_TEAM_ID'];
		$away_team_name=$row['AWAY_TEAM_NAME'];
		$fixture_date=$row['FIXTURE_DATE'];
		$venue_id=$row['MATCH_VENUE_ID'];
		$venue_name=$row['MATCH_VENUE'];
		}
	else { // Not a valid fixture ID.
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error. Not a valid fixture ID #1.</p><p><br /><br /></p>';
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
	<p class="error">This page has been accessed in error #2.</p><p><br /><br /></p>';
		echo'<p> <a href="index.php">Go back to Main Menu </a>';
	exit();
	}
	

##IF FORM IS SUBMITTED: CHECK YES/NO SURE, THEN DELETE POST METHOD:
// Check if the form has been submitted.
if (isset($_POST['submitted'])) {
					
			$query = "SELECT fixture_id,fixture_date,HOME_TEAM_ID,t1.TEAM_NAME as HOME_TEAM_NAME,
						AWAY_TEAM_ID,t2.TEAM_NAME 	as AWAY_TEAM_NAME
					 FROM fixtures f join teams t1 on f.home_team_id=t1.team_id
					join teams t2 on f.away_team_id=t2.team_id WHERE fixture_id=$fixture_id";	
					 
		   $result = @mysqli_query ($dbc, $query); // Run the query.
		   if (mysqli_num_rows($result) == 1) { 
		
			$row = mysqli_fetch_array ($result, MYSQL_ASSOC);

			$fixture_id=$row['fixture_id'];
			$fixture_date=$row['fixture_date'];
			$home_team_name=$row['HOME_TEAM_NAME'];
			$away_team_name=$row['AWAY_TEAM_NAME'];
			
		   }
		   else { 
					echo '<h1 id="mainhead">Page Error</h1>
			<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
				echo'<p> <a href="index.php">Go back to Main Menu </a>';
			}
			

			
			
			
	//Check if sure!
		if ($_POST['sure'] == 'Yes') { 
			
			//query for deletion
			$query = "DELETE FROM fixtures WHERE fixture_id=$fixture_id";		
			$result_del = @mysqli_query ($dbc, $query); // Run the query.
			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

				// Get the fixture information.
				$row = mysqli_fetch_array ($result, MYSQL_NUM);
			
				// Create the result page.
				echo '<h1 id="mainhead">Delete the Fixture</h1>
				<p>The fixture <b>'.$home_team_name.'</b> vs <b>'.$away_team_name.'</b> has been deleted.</p><p><br /><br /></p>';
				echo '<p> <a href="view_fixtures.php">Go back to View All Fixtures </a>';
			} 
			else { // Did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The fixture could not be deleted due to a system error.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				
				echo'<p> <a href="view_fixtures.php">Go to View All Fixtures</a>&nbsp;&nbsp;&nbsp;<a href="index.php">Go back to Main Menu </a>';
				
			}
		
		} // End of $_POST['sure'] == 'Yes' if().
		
		else  // Wasn't sure about deleting the team.
		{ 
			echo '<h1 id="mainhead">Delete the Fixture</h1>';
	
				// Create the result page.
				echo'<p>The fixture <b>'.$home_team_name.'</b> vs <b>'.$away_team_name.'</b> has NOT been deleted.</p><p><br /><br /></p>';
				echo '<p> <a href="view_fixtures.php">Go back to View All Fixtures </a>';
			

		} 


}//if submitted section

	
// MAIN FORM	
if((isset($_GET['fixture_id']))  && (is_numeric($_GET['fixture_id'])) ){
	echo '<h2>Delete the Fixture</h2>
	<form action="delete_fixture.php" method="post">
	<h3>Fixture ID: ' . $fixture_id . '</h3>
	<h3>Home Team Name: ' . $home_team_name . '</h3>
	<h3>Away Team Name: ' . $away_team_name. '</h3>
	<h3>Fixture Date: ' . $fixture_date. '</h3>
	<h3>Venue Name: ' . $venue_name . '</h3>
	
	
	<p>Are you sure you want to delete this Fixture?<br />
	<input type="radio" name="sure" value="Yes" /> Yes 
	<input type="radio" name="sure" value="No" checked="checked" /> No</p>
	
	<p><input type="submit" name="submit" value="Submit!" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="fixture_id" value="' . $fixture_id . '" />  
	<p> <a href="view_fixtures.php">Go back to View All Fixtures </a>
	</form>'; 
	
	
		
}


?>