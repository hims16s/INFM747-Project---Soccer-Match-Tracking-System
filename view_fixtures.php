<?php 

include ('mysqli_connect_to_soccer_db.php');

// Page header.
echo '<h1>Fixtures currently in the Database:</h1>';


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
	$query = "SELECT COUNT(*) FROM fixtures";
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

	} // End of else of np IF.
	
	
	// Determine where in the database to start returning results.
	if (isset($_GET['s'])) {
	$start = $_GET['s'];
	} else {
	$start = 0;
	}
	
//LINKS:
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
			$order_by = 'FIXTURE_ID ASC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=c_d";
			break;
		case 'c_d':
			$order_by = 'FIXTURE_ID DESC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=c_a";
			break;
			
		case 't_a':
			$order_by = 'HOME_TEAM_NAME ASC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=t_d";
			break;
		case 't_d':
			$order_by = 'HOME_TEAM_NAME DESC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=t_a";
			break;
			
		case 'cr_a':
			$order_by = 'AWAY_TEAM_NAME ASC';
			$link3 = "{$_SERVER['PHP_SELF']}?sort=cr_d";
			break;
		case 'cr_d':
			$order_by = 'AWAY_TEAM_NAME DESC';
			$link3 = "{$_SERVER['PHP_SELF']}?sort=cr_a";
			break;
			
		case 'd_a':
			$order_by = 'FIXTURE_DATE ASC';
			$link4 = "{$_SERVER['PHP_SELF']}?sort=d_d";
			break;
		case 'd_d':
			$order_by = 'FIXTURE_DATE DESC';
			$link4 = "{$_SERVER['PHP_SELF']}?sort=d_a";
			break;
			
		case 's_a':
			$order_by = 'MATCH_VENUE ASC';
			$link5 = "{$_SERVER['PHP_SELF']}?sort=s_d";
			break;
		case 's_d':
			$order_by = 'MATCH_VENUE DESC';
			$link5 = "{$_SERVER['PHP_SELF']}?sort=s_a";
			break;
					
		default:
			$order_by = 'FIXTURE_ID ASC';
			break;
	}
	
	// $sort will be appended to the pagination links.
	$sort = $_GET['sort'];
	
	} 
	else { // Use the default sorting order.
	$order_by = 'FIXTURE_ID  ASC';
	$sort = 'c_a';
	}

	

// MAIN QUERY: Assign the query string to the variable $query
$query = " SELECT FIXTURE_ID, HOME_TEAM_ID,t1.TEAM_NAME as HOME_TEAM_NAME,AWAY_TEAM_ID,t2.TEAM_NAME as AWAY_TEAM_NAME,MATCH_VENUE_ID,FIXTURE_DATE,v.VENUE_NAME AS MATCH_VENUE FROM fixtures f join teams t1 on t1.TEAM_ID=f.HOME_TEAM_ID join teams t2 on t2.TEAM_ID=f.AWAY_TEAM_ID join
 venues v on f.MATCH_VENUE_ID=v.VENUE_ID  ORDER BY $order_by
		  LIMIT $start, $display";

      

// Run the query against the connection $dbc
$result = @mysqli_query ($dbc, $query);

//Table header
echo "Ordered by $order_by";
echo '<table align="center" cellspacing="0" cellpadding="5">
<tr>
	<td align="left"><b>Edit</b></td>
	<td align="left"><b>Delete</b></td>
	
	<td align="left"><b><a href="' . $link1 . '">Fixture ID </a></b></td>
	<td align="left"><b><a href="' . $link2 . '">Home Team Name </a></b></td>
	<td align="left"><b><a href="' . $link3 . '">Away Team Name </a></b></td>
	<td align="left"><b><a href="' . $link4 . '">Fixture Date </a></b></td>
	<td align="left"><b><a href="' . $link5 . '">Venue Name </a></b></td>
	
	
	
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
		<td align="left"><b><a href="edit_fixture.php?fixture_id='.$row['FIXTURE_ID'] .'">edit</a></b></td>
		<td align="left"><b><a href="delete_fixture.php?fixture_id='.$row['FIXTURE_ID'] .'">delete</a></b></td>
	
		<td align="left">' . $row['FIXTURE_ID'] . '</td>
		<td align="left">' . $row['HOME_TEAM_NAME'] . '</td>
		<td align="left">' . $row['AWAY_TEAM_NAME'] . '</td>
		<td align="left">' . $row['FIXTURE_DATE'] . '</td>
		<td align="left">' . $row['MATCH_VENUE'] . '</td>
		
		</tr>';
		}
	}
	else //If the query did not run OK.
	{
	 
	echo "<h1>System Error</h1>
	<p>We apologize for any inconvenience.</p>"; // Public message.
	echo "<p>" . mysqli_error($dbc) . "<br /><br />Query: $query </p>"; // Debugging message.
	echo'<p> <a href="index.php">Go back to Main Menu </a>';
	} //Fetch and print all the records.
		
echo '</table>';  // Table completed

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
		echo '<a href="view_fixtures.php?s=0&np=' . $num_pages .'&sort=' . $sort .'">First</a> ';
		echo '<a href="view_fixtures.php?s=' . ($start - $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Previous</a> ';
	}
	
	// Make all the numbered pages.
	for ($i = 1; $i <= $num_pages; $i++) {

		if ($i != $current_page) {
			echo '<a href="view_fixtures.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages .'&sort=' . $sort . '">
			' . $i . '</a>&nbsp;';
		} else {
			echo $i . ' ';
		}
	}
	
	// If it's not the last page, make a Last button and a Next button.
	if ($current_page != $num_pages) {
		echo '<a href="view_fixtures.php?s=' . ($start + $display) . '&np=' . $num_pages .'&sort=' . $sort .'">Next</a> ';
		echo '<a href="view_fixtures.php?s=' . (($num_pages-1) * $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Last</a>';

	}
	
	echo '</p>';
	echo'<p> <a href="index.php">Go back to Main Menu </a>';
	
} // End of links section.
?>
