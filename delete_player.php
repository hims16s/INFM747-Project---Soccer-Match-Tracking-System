<?php 

$page_title = 'Delete the player';

##CONNECTION TO DB:
include ('mysqli_connect_to_soccer_db.php');

$player_id="";
$player_name="";
$first_name="";
$last_name="";
$height="";
$weight="";
$position="";



# GET METHOD: then retireve and populate
if ( (isset($_GET['player_id'])) && (is_numeric($_GET['player_id'])) )  
	{ 
	
	$player_id = $_GET['player_id'];

	// Retrieve the player's information which we clicked to delete.
	$query = "SELECT player_id, CONCAT(first_name, ' ', last_name) as player_name,first_name,last_name,height,weight,p2.position_name,
		  t.team_id as team_id,t.team_name as team_name
		  FROM players p1,positions p2,teams t 
		  WHERE p1.position_id=p2.position_id AND p1.team_id=t.team_id AND p1.player_id=$player_id";
		
			  
	$result = @mysqli_query ($dbc, $query); // Run the query.

	if (mysqli_num_rows($result) == 1) { // Valid player ID, then only show the form.

		// Get the player's information.
		$row = mysqli_fetch_array ($result, MYSQL_ASSOC);
		$player_id=$row['player_id'];
		$player_name=$row['player_name'];
		$first_name=$row['first_name'];
		$last_name=$row['last_name'];
		$height=$row['height'];
		$weight=$row['weight'];
		$position=$row['position_name'];
		
		}
	else { // Not a valid player ID.
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error. Not a valid Player ID .</p><p><br /><br /></p>';
			echo'<p> <a href="index.php">Go back to Main Menu </a>';
		}
	}

elseif ( (isset($_POST['player_id'])) && (is_numeric($_POST['player_id'])) ) //POST method
	{        
	$player_id=$_POST['player_id'];
	}
else 
	{ // No valid ID, kill the script.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error .</p><p><br /><br /></p>';
		echo'<p> <a href="index.php">Go back to Main Menu </a>';
	exit();
	}
	

##IF FORM IS SUBMITTED: CHECK YES/NO SURE, THEN DELETE POST METHOD:
// Check if the form has been submitted.
if (isset($_POST['submitted'])) {
		
			#FOR SUCCESS MESSAGES
			$query = "SELECT player_id, CONCAT(first_name, ' ', last_name) as player_name,height,weight,p2.position_name,
		  t.team_id as team_id,t.team_name as team_name
		  FROM players p1,positions p2,teams t 
		  WHERE p1.position_id=p2.position_id AND p1.team_id=t.team_id AND p1.player_id=$player_id";	
					 
		   $result = @mysqli_query ($dbc, $query); // Run the query.
		   if (mysqli_num_rows($result) == 1) { // Valid player ID, show the result.

			// Get the player information.  // for SUCCESS MESSAGES
			$row = mysqli_fetch_array ($result, MYSQL_ASSOC);

			$player_name=$row['player_name'];
			$height=$row['height'];
			$weight=$row['weight'];
			$position=$row['position_name'];  
			
		   }
		   else { // Not a valid player ID.
					echo '<h1 id="mainhead">Page Error</h1>
			<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
			}
			
			
	//Check if sure!
		if ($_POST['sure'] == 'Yes') { // Delete them.

			
			//query for deletion
			$query = "DELETE FROM players WHERE player_id=$player_id";		
			$result_del = @mysqli_query ($dbc, $query); // Run the query.
			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

				$row = mysqli_fetch_array ($result, MYSQL_NUM);
			
				// Create the result page.
				echo '<h1 id="mainhead">Delete the Player</h1>
				<p>The player <b>'.$player_name.'</b> has been deleted.</p><p><br /><br /></p>';	
				echo '<p> <a href="view_teams.php">Go back to View all Teams </a> ';
			} 
			else { // Did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The player could not be deleted due to a system error.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
					echo'<p> <a href="index.php">Go back to Main Menu </a>';
			}
		
		} // End of $_POST['sure'] == 'Yes' if().
		
		else  // Wasn't sure about deleting the section.
		{ 
			echo '<h1 id="mainhead">Delete the Player</h1>
				<p>The player <b>'.$player_name.'</b> has  NOT been deleted.</p><p><br /><br /></p>';	
				echo '<p> <a href="view_teams.php">Go back to View all Teams </a> ';


		} // End of wasn’t sure else().


}//if submitted section


	
// MAIN FORM	
if((isset($_GET['player_id']))  && (is_numeric($_GET['player_id'])) ){
	echo '<h2>Delete the player</h2>
	<form action="delete_player.php" method="post">
	<h3>Player ID: ' . $player_id . '</h3>
	<h3>Player Name: ' . $player_name . '</h3>
	<h3>Height: ' . $height . '</h3>
	<h3>Weight: ' . $weight. '</h3>
	<h3>Position: ' . $position . '</h3>
	
		
	
	
	
	<p>Are you sure you want to delete this Player?<br />
	<input type="radio" name="sure" value="Yes" /> Yes 
	<input type="radio" name="sure" value="No" checked="checked" /> No</p>
	
	<p><input type="submit" name="submit" value="Submit!" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="player_id" value="' . $player_id . '" />  
	<p> <a href="view_teams.php">Go back to View all Teams </a>
	</form>';  
	
	
}


?>