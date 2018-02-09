<?php 


// Page header.

// Check for a valid team ID, through GET.
if ( (isset($_GET['team_id'])) && (is_numeric($_GET['team_id'])) ) { // Accessed through view_teams.php
	$team_id = $_GET['team_id'];
	} 
	else { // No valid ID, kill the script.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
	echo'<p> <a href="index.php">Go back to Main Menu </a>';
	exit();
}

include ('mysqli_connect_to_soccer_db.php');
$page_title = 'View Players';

//$team_id="";
//$team_name="";

// Number of records to show per page:
$display = 5;

// Determine how many pages there are. 
if (isset($_GET['np'])) { // Already been determined.
	$num_pages = $_GET['np'];
	} 
	else { // Need to determine.

 	// Count the number of records
	$query = "SELECT COUNT(*) FROM players WHERE team_id=$team_id";
	$result = @mysqli_query ($dbc, $query);
	$row = mysqli_fetch_array ($result, MYSQL_NUM);
	$num_records = $row[0];
	
	// Calculate the number of pages.
	if ($num_records > $display) { // More than 1 page.
		$num_pages = ceil ($num_records/$display);
		} else {
		$num_pages = 1;
		}

	} // End of else of np IF.


// Determine where in the database to start returning results.
	if (isset($_GET['s'])) {
		$start = $_GET['s'];
	} else {
	$start = 0;
	}
	

// Default column links.
	$link1 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=c_a";
	$link2 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=s_a";
	//$link3 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=y_a";
	$link3 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=p_a";
	$link4 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=w_a";
	$link5 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=v_a";


// Determine the sorting order.
if (isset($_GET['sort'])) {

	// Use existing sorting order.
	switch ($_GET['sort']) {
		case 'c_a':
			$order_by = 'player_id ASC';
			$link1 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=c_d";
			break;
		case 'c_d':
			$order_by = 'player_id DESC';
			$link1 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=c_a";
			break;
			
		case 's_a':
			$order_by = 'player_name ASC';
			$link2 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=s_d";
			break;
		case 's_d':
			$order_by = 'player_name DESC';
			$link2 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=s_a";
			break;
			
			
		case 'p_a':
			$order_by = 'height ASC';
			$link3 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=p_d";
			break;
		case 'p_d':
			$order_by = 'height DESC';
			$link3 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=p_a";
			break;
			
		case 'w_a':
			$order_by = 'weight ASC';
			$link4 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=w_d";
			break;
		case 'w_d':
			$order_by = 'weight DESC';
			$link4 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=w_a";
			break;
			
		case 'v_a':
			$order_by = 'position_name ASC';
			$link5 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=v_d";
			break;
		case 'v_d':
			$order_by = 'position_name DESC';
			$link5 = "{$_SERVER['PHP_SELF']}?team_id=$team_id&sort=v_a";
			break;
	}
	
	// $sort will be appended to the pagination links.
	$sort = $_GET['sort'];
	
	} else { // Use the default sorting order.
	$order_by = 'player_id ASC';
	$sort = 'c_a';
	}

$query_cnt = "SELECT count(1)
		  FROM players p,teams t
		  WHERE p.team_id=t.team_id AND p.team_id=$team_id";		
$result_cnt = @mysqli_query ($dbc, $query_cnt);
$num_rows = mysqli_fetch_array ($result_cnt, MYSQL_NUM);

$query = "SELECT player_id, CONCAT(first_name, ' ', last_name) as player_name,height,weight,p2.position_name,
		  t.team_id as team_id,t.team_name as team_name
		  FROM players p1,positions p2,teams t 
		  WHERE p1.position_id=p2.position_id AND p1.team_id=t.team_id AND p1.team_id=$team_id ORDER BY $order_by LIMIT $start, $display";		
$result = @mysqli_query ($dbc, $query); // Run the query.


if($result && $num_rows[0] > 0){

$row = mysqli_fetch_array($result, MYSQL_ASSOC);

// Page header.
echo '<h1 id="mainhead">Players of the selected Team: ' . $row['team_id'].' - '.$row['team_name'] . '</h1>';


// Table header.
echo "Ordered by $order_by";
echo '<table align="center" cellspacing="0" cellpadding="5">
<tr>
	<td align="left"><b>Edit</b></td>
	<td align="left"><b>Delete</b></td>
	<td align="left"><b><a href="' . $link1 . '">Player ID </a></b></td>
	<td align="left"><b><a href="' . $link2 . '">Player Name</a></b></td>
	<td align="left"><b><a href="' . $link3 . '">Height</a></b></td>
	<td align="left"><b><a href="' . $link4 . '">Weight</a></b></td>
	<td align="left"><b><a href="' . $link5 . '">Position</a></b></td>
</tr>';

	#Again Query:
	$query = "SELECT player_id, CONCAT(first_name, ' ', last_name) as player_name,height,weight,p2.position_name
		  FROM players p1,positions p2
		  WHERE p1.position_id=p2.position_id AND p1.team_id=$team_id ORDER BY $order_by LIMIT $start, $display";	
		  
	$result = @mysqli_query ($dbc, $query); // Run the query.

// Fetch and print all the records.
	$bg = '#eeeeee'; // Set the initial background color.

	
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) 
			{
			$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
		
			// Define the background color for the table row.
			echo '<tr bgcolor="' . $bg . '" > 
			<td align="left"><b><a href="edit_player.php?player_id='.$row['player_id'] .'">edit</a></b></td>
			<td align="left"><b><a href="delete_player.php?player_id='.$row['player_id'] .'">delete</a></b></td>
			
			<td align="left">' . $row['player_id'] . '</td>
			<td align="left">' . $row['player_name'] . '</td>
			<td align="left">' . $row['height'] . '</td>
			<td align="left">' . $row['weight'] . '</td>
			<td align="left">' . $row['position_name'] . '</td>
			
			</tr>
			';
			}
	 //Fetched and printed all the records.
		

echo '</table>';
}else{

	echo '<p>This team does not have any players. <br>';
}
mysqli_free_result ($result); // Free up the resources.	

mysqli_close($dbc); // Close the database connection.

#LINKS:

if ($num_pages > 1) {
	
	echo '<br /><p>';
	// Determine what page the script is on.	
	$current_page = ($start/$display) + 1;
	
	// If it's not the first page, make a First button and a Previous button.
	if ($current_page != 1) {
		echo '<a href="view_players.php?s=0&np=' . $num_pages . '&team_id=' . $team_id . '&sort=' . $sort .'">First</a> ';
		echo '<a href="view_players.php?s=' . ($start - $display) . '&np=' . $num_pages . '&team_id=' . $team_id . '&sort=' . $sort .'">Previous</a> ';
	}
	
	// Make all the numbered pages.
	for ($i = 1; $i <= $num_pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="view_players.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '&team_id=' . $team_id . '&sort=' . $sort .'">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
	
	// If it's not the last page, make a Last button and a Next button.
	if ($current_page != $num_pages) {
		echo '<a href="view_players.php?s=' . ($start + $display) . '&np=' . $num_pages . '&team_id=' . $team_id . '&sort=' . $sort .'">Next</a> ';
		echo '<a href="view_players.php?s=' . (($num_pages-1) * $display) . '&np=' . $num_pages . '&team_id=' . $team_id . '&sort=' . $sort .'">Last</a>';

	}
	
	echo '</p>';
	
} // End of links section.

echo '<p><a href="add_player.php?team_id='.$team_id.'">Add a new Player to this Team.</a></p>';
echo '<p> <a href="view_teams.php">Go back to View All Teams </a>';
?>


