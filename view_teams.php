<?php 

include ('mysqli_connect_to_soccer_db.php');

// Page header.
echo '<h1>Teams currently in the Database:</h1>';


// Number of records to show per page:
	$display = 5;

// Determine how many pages there are. Num of pages: np
	if (isset($_GET['np'])) 
		{ 
		$num_pages = $_GET['np'];
		} 
	else 
	{ // Need to determine.

 	// First, Count the number of records
	$query = "SELECT COUNT(*) FROM teams";
	$result = @mysqli_query ($dbc, $query);
	$row = mysqli_fetch_array ($result, MYSQL_NUM);
	$num_records = $row[0];
	
	// Calculate the number of pages.
	    if ($num_records > $display) { // More than 1 page.
		$num_pages = ceil ($num_records/$display);
		} else 
		{
		$num_pages = 1;
		}

	} 
	
	
	// Determine where in the database to start returning results.
	if (isset($_GET['s'])) {
	$start = $_GET['s'];
	} else {
	$start = 0;
	}
	

	//Default column links.
	
	$link1 = "{$_SERVER['PHP_SELF']}?sort=c_a";
	$link2 = "{$_SERVER['PHP_SELF']}?sort=t_a";
	$link3 = "{$_SERVER['PHP_SELF']}?sort=cr_a";
	$link4 = "{$_SERVER['PHP_SELF']}?sort=d_a";
	$link5 = "{$_SERVER['PHP_SELF']}?sort=s_a";


	// Determine the sorting order.
	if (isset($_GET['sort'])) {

	// Use existing sorting order.
	switch ($_GET['sort']) {
		
		case 'c_a':
			$order_by = 'TEAM_ID ASC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=c_d";
			break;
		case 'c_d':
			$order_by = 'TEAM_ID DESC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=c_a";
			break;
			
		case 't_a':
			$order_by = 'TEAM_NAME ASC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=t_d";
			break;
		case 't_d':
			$order_by = 'TEAM_NAME DESC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=t_a";
			break;
			
		case 'cr_a':
			$order_by = 'LEAGUE_NAME ASC';
			$link3 = "{$_SERVER['PHP_SELF']}?sort=cr_d";
			break;
		case 'cr_d':
			$order_by = 'LEAGUE_NAME DESC';
			$link3 = "{$_SERVER['PHP_SELF']}?sort=cr_a";
			break;
			
		case 'd_a':
			$order_by = 'VENUE_NAME ASC';
			$link4 = "{$_SERVER['PHP_SELF']}?sort=d_d";
			break;
		case 'd_d':
			$order_by = 'VENUE_NAME DESC';
			$link4 = "{$_SERVER['PHP_SELF']}?sort=d_a";
			break;
			
		case 's_a':
			$order_by = 'Number_of_Players ASC';
			$link5 = "{$_SERVER['PHP_SELF']}?sort=s_d";
			break;
		case 's_d':
			$order_by = 'Number_of_Players DESC';
			$link5 = "{$_SERVER['PHP_SELF']}?sort=s_a";
			break;
					
		default:
			$order_by = 'TEAM_ID ASC';
			break;
	}
	
	// $sort will be appended to the pagination links.
	$sort = $_GET['sort'];
	
	} 
	else { // Use the default sorting order.
	$order_by = 'TEAM_ID  ASC';
	$sort = 'c_a';
	}

	

// MAIN QUERY: Assign the query string to the variable $query
$query = "SELECT TEAM_ID, TEAM_NAME, LEAGUE_NAME,VENUE_NAME,(SELECT COUNT(1) FROM PLAYERS P WHERE t.TEAM_ID=P.TEAM_ID)as Number_of_Players FROM teams t join venues v on t.HOME_GROUND_VENUE_ID=v.VENUE_ID JOIN leagues l on t.LEAGUE_ID = l.LEAGUE_ID ORDER BY $order_by
		  LIMIT $start, $display";
     

// Run the query against the connection $dbc
$result = @mysqli_query ($dbc, $query);

//Table header- form - ECHO
echo "Ordered by $order_by";
echo '<table align="center" cellspacing="0" cellpadding="5">
<tr>
	<td align="left"><b>Edit</b></td>
	<td align="left"><b>Delete</b></td>
	
	<td align="left"><b><a href="' . $link1 . '">Team ID </a></b></td>
	<td align="left"><b><a href="' . $link2 . '">Team Name </a></b></td>
	<td align="left"><b><a href="' . $link3 . '">League Name </a></b></td>
	<td align="left"><b><a href="' . $link4 . '">Venue Name </a></b></td>
	<td align="left"><b><a href="' . $link5 . '">Number of Players </a></b></td>
	
	
	
</tr>';

// Fetch and print all the records.
	$bg = '#eeeeee'; // Set the initial background color.

	if($result) //checks if the query ran correct
	{	
	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) 
		{
		$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
	
		// Define the background color for the table row.
		echo '<tr bgcolor="' . $bg . '" > 
		<td align="left"><b><a href="edit_team.php?team_id='.$row['TEAM_ID'] .'">edit</a></b></td>
		<td align="left"><b><a href="delete_team.php?team_id='.$row['TEAM_ID'] .'">delete</a></b></td>
	
		<td align="left">' . $row['TEAM_ID'] . '</td>
		<td align="left">' . $row['TEAM_NAME'] . '</td>
		<td align="left">' . $row['LEAGUE_NAME'] . '</td>
		<td align="left">' . $row['VENUE_NAME'] . '</td>
		<td align="left">' . $row['Number_of_Players'] . '</td>
		<td align="left"><a href="view_players.php?team_id='.$row['TEAM_ID'] .'">View Players</a></td>
		
		</tr>';
		}
	}
	else //If the query did not run properly.
	{
	 
	echo "<h1>System Error</h1>
	<p>We apologize for any inconvenience.</p>"; // Public message.
	echo "<p>" . mysqli_error($dbc) . "<br /><br />Query: $query </p>"; // Debugging message.
	echo'<p> <a href="index.php">Go back to Main Menu </a>';
	} 
		
echo '</table>';  

mysqli_free_result ($result); 
mysqli_close ($dbc); // Close the database connection.

#LINKS:


// Make the links to other pages, if necessary.
if ($num_pages > 1) {
	
	echo '<br /><p>';
	// Determine what page the script is on.	
	$current_page = ($start/$display) + 1;
	
	// If it's not the first page, make a First button and a Previous button.
	if ($current_page != 1) {
		echo '<a href="view_teams.php?s=0&np=' . $num_pages .'&sort=' . $sort .'">First</a> ';
		echo '<a href="view_teams.php?s=' . ($start - $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Previous</a> ';
	}
	
	// Make all the numbered pages.
	for ($i = 1; $i <= $num_pages; $i++) {

		if ($i != $current_page) {
			echo '<a href="view_teams.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages .'&sort=' . $sort . '">
			' . $i . '</a>&nbsp;';
		} else {
			echo $i . ' ';
		}
	}
	
	// If it's not the last page, make a Last button and a Next button.
	if ($current_page != $num_pages) {
		echo '<a href="view_teams.php?s=' . ($start + $display) . '&np=' . $num_pages .'&sort=' . $sort .'">Next</a> ';
		echo '<a href="view_teams.php?s=' . (($num_pages-1) * $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Last</a>';

	}
	
	echo '</p>';
	echo'<p> <a href="index.php">Go back to Main Menu </a>';
	
} // End of links section.
?>
