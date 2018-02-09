<?php 

$page_title = 'Add a Venue';

include ('mysqli_connect_to_soccer_db.php');

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize error array.

	// Check for a venue name.
	if (empty($_POST['venue_name'])) {
		$errors[] = 'You forgot to enter the venue name.';
	} elseif(is_numeric($_POST['venue_name']))
	{
		$errors[] = 'Please enter a valid alphabetical value for venue name.';
	}	
	else {
		$venue_name = $_POST['venue_name'];
	}
	
		// Check for a venue country.
	if (empty($_POST['venue_country'])) {
		$errors[] = 'You forgot to enter the venue country.';
	}elseif(is_numeric($_POST['venue_country']))
	{
		$errors[] = 'Please enter a valid alphabetical value for venue country.';
	}	
	else {
		$venue_country = $_POST['venue_country'];
	}
	
	if (empty($errors)) { // If everything's okay.
	
		// Add the venue to the database.
		
		// Make the query.
		$query = "INSERT INTO venues (venue_name,venue_country) VALUES ('$venue_name','$venue_country')";		
		$result = @mysqli_query ($dbc, $query); // Run the query.
		if ($result) { // If it ran OK.
		
			// Print a message.
			echo '<h1 id="mainhead">Success!</h1>
		<p>You have added to the Venues table:</p>';

		   echo "<table>
		<tr><td>Venue Name:</td><td>{$venue_name}</td></tr>
		<tr><td>Venue Country:</td><td>{$venue_country}</td></tr>
		</table>";
		
		echo'<p> <a href="add_venue.php">Go to Add a Venue</a>&nbsp;&nbsp;&nbsp;<a href="index.php">Go back to Main Menu </a>';
			exit();
			
		} else { // If it did not run OK.
			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The venue could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
			exit();
		}
		
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';

	} // End of if (empty($errors)) IF.
	

	mysqli_close($dbc); // Close the database connection.
		
} // End of the main Submit conditional.

?>
<h2>Add Venue</h2>
<form action="add_venue.php" method="post">
	<p>Venue Name: <input type="text" name="venue_name" size="30" maxlength="30" value="<?php if (isset($_POST['venue_name'])) echo $_POST['venue_name']; ?>" /></p>	
	<p>Venue Country: <input type="text" name="venue_country" size="30" maxlength="30" value="<?php if (isset($_POST['venue_country'])) echo $_POST['venue_country']; ?>" /></p>
	<p><input type="submit" name="submit" value="Add Venue" /></p>
	<input type="hidden" name="submitted" value="TRUE" />	
	<?php
	echo'<p><a href="index.php">Go back to Main Menu </a>';
	?>
</form>


