<?php 

$page_title = 'Add a player';

##CONNECTION TO DB:
include ('mysqli_connect_to_soccer_db.php');

//$player_id="";
$team_id="";
$team_name="";
$player_name="";
$first_name="";
$last_name="";
$height="";
$weight="";
$position="";


	// Check for a Team name (Not Required).
if ( (isset($_GET['team_id'])) && (is_numeric($_GET['team_id'])) ) { // Accessed through view_teams.php
	$team_id = $_GET['team_id'];
	//echo "$team_id";
	} 
	

##IF FORM IS SUBMITTED: CHECK ERRORS, IF NONE THEN ADD THE NEW DEPARTMENT

if (isset($_POST['submitted'])) {  
		
						  
	// Initialize error array.
	$errors = array(); 
	
	//Check for first name
		if (empty($_POST['first_name'])) 
	{
		$errors[] = 'You forgot to enter the first name of the player.';
	}	
	elseif(is_numeric($_POST['first_name']))
	{
		$errors[] = 'You have entered an invalid first name.';
	} 
	else {		
		$first_name = $_POST['first_name'];	
	}
	
	// Check for last name.
		if (empty($_POST['last_name'])) {
		$errors[] = 'You forgot to enter the last name of the player.';
	}
	elseif(is_numeric($_POST['last_name']))
	{
		$errors[] = 'You have entered an invalid last name.';
	}
	else
	{		
		$last_name = $_POST['last_name'];	
	}
	
	// Check for height
	if(empty($_POST['height']))
		{
			$errors[] = 'You have entered an incorrect value for height of the player.';
		}
		else {
		if(is_numeric($_POST['height']))
		{
		$height = $_POST['height'];
		}
	
		else
		{
		$height = $_POST['height'];
		$errors[] = 'You entered a non-numeric value for the height.';
		}	
	}
	
		// Check for weight
	if (empty($_POST['weight'])) {
		$errors[] = 'You have entered an incorrect value for weight of the player.';
	} else {
		if(is_numeric($_POST['weight']))
		{
		$weight = $_POST['weight'];
		}
	
		else
		{
		$weight = $_POST['weight'];
		$errors[] = 'You entered a non-numeric value for the weight.';
		}	
	}
	
	
	if (empty($_POST['position_id'])) {
		$errors[] = 'You forgot to select the Position.';
	} else {
		$position_id = $_POST['position_id'];
		//	echo "$position_id"."-post";
	}
	
	if (empty($_POST['team_id'])) {
		//echo "$team_id";
		$errors[] = 'You forgot to select team name.';
	} else {
		$team_id = $_POST['team_id'];
		//echo "$team_id"."-post";
	}
	
	#Get the Position details: for SUCCESS MESSAGE
	
		$query = "SELECT  t.team_id, team_name, p.position_id, position_name
				  FROM  positions p, teams t, players 
				  WHERE t.team_id = $team_id AND p.position_id = $position_id";

		$result = @mysqli_query ($dbc, $query);
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
			{				 
				$team_name=$row['team_name']; 
				//$player_id=$row['player_id']; 
				//$player_name=$row['player_name']; 
				$position=$row['position_name']; 
		// they will be used as part of the success message
			}
	
	//if no errors in the values selected, updation of the entries
	if (empty($errors)) { // If everything's OK.
	
	
			// Make the query.
			$query = "INSERT INTO players (team_id,first_name,last_name,height,weight,position_id) 
					 VALUES ($team_id,'$first_name','$last_name',$height,$weight,$position_id)";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			// If query ran OK.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { 
			
				// Print a message.
				 echo '<h1>Success!</h1>
						<p>You have added following player to the team: <b>'.$team_id.'</b> titled <b> '.$team_name.'</b></p>';
						
				echo "<table>
				
				<tr><td>Player Name:</td><td>$first_name $last_name</td></tr>
				<tr><td>Height:</td><td>$height</td></tr>
				<tr><td>Weight:</td><td>$weight</td></tr>
				<tr><td>Position:</td><td>$position</td></tr>
			</table>";
			echo '<p><a href="add_player.php?team_id='.$team_id.'">Go Back to add a Player</a></p>';
							
			} 
			else { // If query did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The player could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
					echo'<p> <a href="index.php">Go back to Main Menu </a>';
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

	echo "<h2>Add a Player</h2>
		<form action='add_player.php' method='post'>
		
	<input name='team_id' type='hidden' size='35' maxlength='35' value='";  
		if (isset($team_id))
		{
			echo $team_id;  
		}
		
	echo "'>";
	
	echo "<p>First Name: <input type='text' name='first_name' size='30' maxlength='35' value='";  
		if (isset($first_name))
		{
			echo $first_name;  //to make it sticky
		}
		
	echo "'>";

	echo "<p>Last Name: <input type='text' name='last_name' size='30' maxlength='35' value='";  
		if (isset($last_name))
		{
			echo $last_name;  //to make it sticky
		}	 	
	
	echo "'>";
	
		
	echo "<p>Height: <input type='text' name='height' size='8' maxlength='4' value='"; 
			if (isset($height))
		{
			echo $height;  //to make it sticky
		}	 
	
	echo "'> <i>#in cm</i>";
	
	echo "<p>Weight: <input type='text' name='weight' size='8' maxlength='4' value='"; 
			if (isset($weight))
		{
			echo $weight;  //to make it sticky
		}	 
	
	echo "'> <i>#in lbs</i>";

		
	echo "<p>Position: <select name='position_id'>";  
		$query = "SELECT position_id, position_name FROM positions ORDER BY position_id ASC";
		$result = @mysqli_query ($dbc, $query);
		if ($result)
		{
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		{
			if ($row['position_id'] == $position_id) 
			{
			echo '<option value="' .$row['position_id']. '" selected="selected">' . $row['position_name'] . '</option>';
			}
			else 
			{
			echo '<option value="' .$row['position_id']. '" >' . $row['position_name'] . '</option>';
			}   
		}
	echo '</select> </p>';
		}
		else{
			echo "no results";
		}
		
		

	echo "<br/><input type='submit' name= 'submitted' value='Add Player!'>";

	echo "</form>";
}	
	echo '<p><a href="view_teams.php">Go back to View all Teams</a>';


?>