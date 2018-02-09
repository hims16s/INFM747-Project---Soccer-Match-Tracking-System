<?php 

$page_title = 'Edit the player';

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

	// Retrieve the player's information which we clicked to edit.
	$query = "SELECT player_id, CONCAT(first_name, ' ', last_name) as player_name,first_name,last_name,height,weight,position_id
		  FROM players p1 WHERE player_id=$player_id";		
	$result = @mysqli_query ($dbc, $query); // Run the query.

	if (mysqli_num_rows($result) == 1) { // Valid player ID, then only show the form.

		// Get the player's information.
		$row = mysqli_fetch_array ($result, MYSQL_ASSOC);
		$player_name=$row['player_name'];
		$first_name=$row['first_name'];
		$last_name=$row['last_name'];
		$height=$row['height'];
		$weight=$row['weight'];
		$position_id=$row['position_id'];
		
		}
	else { // Not a valid player ID.
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error. Not a valid Player ID.</p><p><br /><br /></p>';
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
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
		echo'<p> <a href="index.php">Go back to Main Menu </a>';
	exit();
	}
	

##IF FORM IS SUBMITTED: CHECK ERRORS, IF NONE THEN UPDATE
// Check if the form has been submitted.
if (isset($_POST['submitted'])) {                      

	$errors = array(); // Initialize error array.
	
	// Check for a code.
	//if (empty($_POST['player_name'])) {
	//	$errors[] = 'You forgot to enter the player name.';
//	} else {		
//		$player_name = $_POST['player_name'];	
//	}
	
		// Check for first name.
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

	
	// Check for position name
	if (empty($_POST['position_id'])) {
		$errors[] = 'You forgot to select the Position.';
	} else {
		$position_id = $_POST['position_id'];
	}
	
	//if no errors in the vlaues selected, updation of the entries
	if (empty($errors)) { // If everything's OK.
	
	
			// Make the query.
			$query = "UPDATE players SET first_name='$first_name',last_name='$last_name',height=$height, weight=$weight,  position_id=$position_id
			WHERE player_id = $player_id";    
			$result = @mysqli_query ($dbc, $query); // Run the query.
			// If query ran OK.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { 
			
				// Print a message.
				echo '<h1 id="mainhead">Edit the Player Details</h1>
				<p>The Player record has been edited.</p><p><br /><br /></p>';	
				
							
			} else { // If query did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The player details could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
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

	echo "<h2>Edit Player Details</h2>
		<form action='edit_player.php' method='post'>
		<p>First Name: <input type='text' name='first_name' size='35' maxlength='35' value='";  
		if (isset($first_name))
		{
			echo $first_name;  //to make it sticky
		}
		
	echo "'>";

	echo "<p>Last Name: <input type='text' name='last_name' size='35' maxlength='35' value='";  
		if (isset($last_name))
		{
			echo $last_name;  //to make it sticky
		}	 	
	
	echo "'>";
	
		
	echo "<p>Height: <input type='text' name='height' size='10' maxlength='4' value='"; 
			if (isset($height))
		{
			echo $height;  //to make it sticky
		}	 
	
	echo "'> <i>#in cm</i>";
	
	echo "<p>Weight: <input type='text' name='weight' size='10' maxlength='4' value='"; 
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
		
	
		
		
	echo "<br/><input type='hidden' name='player_id' value='".$player_id."'>";	
	echo "<br/><input type='submit' value='Submit!'>";
	echo "<input type='hidden' name='submitted' value='TRUE' />";
	echo "<p> <a href='view_teams.php'>Go back to View all Teams</a>";
	echo "</form>";
		
	


?>