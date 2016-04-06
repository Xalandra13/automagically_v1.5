<?php
/*
	Automagically
	16.04.15, db
	functions for search, logging and error handling
*/
	
	// util function to debug
	require_once('util/printDebug.php');

	// initialize all required variables
	$budget = $_POST['budget'];
	$duration = $_POST['duration'];
	$amount = $_POST['amount'];
	$variety = $_POST['variety'];
	$level = '';
	$event = '';
	
	// variable to display additional column in results
	$minDurationDays = '';
	$quantity = '';
	$requiredAmount = '';
	$cost = 0; // cost for each entry (price * required amount)
	$totalCost = 0;
	
	// query variables which can be empty, but the sql-query will be still correct
	$queryDuration = ' ';
	$queryAmount = ' ';
	$queryVariety = ' ';
		
	// get ip-address
	$ipaddr = $_SERVER['REMOTE_ADDR'];
	
	// db access parameters
	require_once('_db_inc/db_inc.php');
	
	// connect to db
	$connection = mysqli_connect($host, $user, $password);
	
	// check db connection
	if(mysqli_connect_errno()){
		/*** ERROR: db-connection failed -> notify user ***
		***  This error cannot be logged since there's no connection!
		***/
		echo "Failed to connect with db: " . mysqli_connect_error();	
	} else {
		mysqli_query($connection, "SET NAMES 'utf8'");
		mysqli_query($connection, "SET CHARACTER SET 'utf8'");
		
		// select db
		mysqli_select_db($connection, $database);
		
		// check duration for correct unit and save this query-part in $queryDuration
		switch($duration){
			case "1 day":
				$queryDuration = "
					AND periodMin <= 1
					AND (slotUnit='PIECE' OR slotUnit='DAY')";
				break;
			case "1 week":
				$queryDuration = "
					AND (periodMin <= 7)
					AND (slotUnit='PIECE' OR slotUnit='DAY')";
				break;
			case "2 weeks":
				$queryDuration = "
					AND (periodMin <= 14)
					AND (slotUnit='PIECE' OR slotUnit='DAY')";
				break;
			case "3 weeks":
				$queryDuration = "
					AND (periodMin <= 21)
					AND (slotUnit='PIECE' OR slotUnit='DAY')";
				break;
			case "1 year":
				$queryDuration = "
					AND (periodMin <= 365)
					AND ((slotUnit='PIECE' OR slotUnit='DAY')
					OR (periodMin <= 12 AND slotUnit='MONTH'))";
				break;
			case "2 years":
				$queryDuration = "
					AND (periodMin <= 730)
					AND ((slotUnit='PIECE' OR slotUnit='DAY')
					OR (periodMin <= 24 AND slotUnit='MONTH'))";
				break;
			case "3 years":
				$queryDuration = "
					AND (periodMin <= 1095)
					AND ((slotUnit='PIECE' OR slotUnit='DAY')
					OR (periodMin <= 36 AND slotUnit='MONTH'))";
				break;
			case "4 years":
				$queryDuration = "
					AND (periodMin <= 1460)
					AND ((slotUnit='PIECE' OR slotUnit='DAY')
					OR (periodMin <= 48 AND slotUnit='MONTH'))";
				break;
			case "5 years":
				$queryDuration = "
					AND (periodMin <= 1825)
					AND ((slotUnit='PIECE' OR slotUnit='DAY')
					OR (periodMin <= 60 AND slotUnit='MONTH'))";
				break;
			default:
				/*** WARNING: no duration provided ***/
				$level = 'warning';
				$event = 'User did not provide a valid duration!';
				
				// db-query for insert
				$logQuery = "INSERT INTO dashboard_log (ipaddr, level, event) VALUES ('".$ipaddr."', '".$level."', '".$event."')";
				$res = mysqli_query($connection, $logQuery);
				break;
		}
		
		// check variety selected
		switch($variety){
			case "low":
				$queryVariety = "
					AND CategoryL2 IN
					(
						SELECT * FROM
						(
							SELECT DISTINCT CategoryL2 FROM dashboard_places
							WHERE CategoryL2 IS NOT NULL
							AND cost <= $budget / 10
							ORDER BY RAND()
							LIMIT 1
						) as InnerSelect
					)";
				break;
			case "med. low":
				$queryVariety = "
					AND CategoryL2 IN
					(
						SELECT * FROM
						(
							SELECT DISTINCT CategoryL2 FROM dashboard_places
							WHERE CategoryL2 IS NOT NULL
							AND cost <= $budget / 10
							ORDER BY RAND()
							LIMIT 2
						) as InnerSelect
					)";
				break;
			case "medium":
				$queryVariety = "
					AND CategoryL2 IN
					(
						SELECT * FROM
						(
							SELECT DISTINCT CategoryL2 FROM dashboard_places
							WHERE CategoryL2 IS NOT NULL
							AND cost <= $budget / 10
							ORDER BY RAND()
							LIMIT 4
						) as InnerSelect
					)";
				break;
			case "med. high":
				$queryVariety = "
					AND CategoryL2 IN
					(
						SELECT * FROM
						(
							SELECT DISTINCT CategoryL2 FROM dashboard_places
							WHERE CategoryL2 IS NOT NULL
							AND cost <= $budget / 10
							ORDER BY RAND()
							LIMIT 7
						) as InnerSelect
					)";
				break;
			case "high":
				$queryVariety = "
					AND CategoryL2 IN
					(
						SELECT * FROM
						(
							SELECT DISTINCT CategoryL2 FROM dashboard_places
							WHERE CategoryL2 IS NOT NULL
							AND cost <= $budget / 10
							ORDER BY RAND()
							LIMIT 10
						) as InnerSelect
					)";
				break;
			default:
				/*** WARNING: no variety provided ***/
				$level = 'warning';
				$event = 'User did not provide a valid variety of spaces!';
				
				// db-query for insert
				$logQuery = "INSERT INTO dashboard_log (ipaddr, level, event) VALUES ('".$ipaddr."', '".$level."', '".$event."')";
				$res = mysqli_query($connection, $logQuery);
				break;
		}
		
		// check amount selected for entries which will be returned 
		switch($amount){
			case "low":
				$queryAmount = "LIMIT 1";
				$quantity = 1;
				break;
			case "med. low":
				$queryAmount = "LIMIT 2";
				$quantity = 2;
				break;
			case "medium":
				$queryAmount = "LIMIT 4";
				$quantity = 4;
				break;
			case "med. high":
				$queryAmount = "LIMIT 7";
				$quantity = 7;
				break;
			case "high":
				$queryAmount = "LIMIT 10";
				$quantity = 10;
				break;
			default:
				/*** WARNING: no amount provided ***/
				$level = 'warning';
				$event = 'User did not provide a valid amount of spaces!';
				
				// db-query for insert
				$logQuery = "INSERT INTO dashboard_log (ipaddr, level, event) VALUES ('".$ipaddr."', '".$level."', '".$event."')";
				$res = mysqli_query($connection, $logQuery);
				break;
		}
		
		// concate db-query
		$query = "
			SELECT id, cost, title, CategoryL2, slotQuantityMin, periodMin, slotUnit, ($budget / $quantity) / cost AS Required, $budget / $quantity AS Cost
			FROM dashboard_places
			WHERE (($budget / $quantity) >= cost)
			AND (($budget / $quantity) / cost >= 1)
			AND !($budget / cost < 1 AND $budget / cost < slotQuantityMin)"
			.$queryDuration.
			"AND visible = 'true'"
			.$queryVariety
			.$queryAmount;
		
		if($result = mysqli_query($connection, $query)){

			// display number of results found
			echo 'Results found: '.mysqli_num_rows($result);
			
			/*** INFO: log the search result into db ***/
			$level = 'info';
			$event = 'Query returned '.mysqli_num_rows($result).' results.';
			
			// db-query for insert
			$logQuery = "INSERT INTO dashboard_log (ipaddr, level, event) VALUES ('".$ipaddr."', '".$level."', '".$event."')";
			$res = mysqli_query($connection, $logQuery);
			
			// display table header
			echo '<br>';
			echo '<table><tr>';
			echo '<th>ID</th><th>Title</th><th>Type</th><th>Unit</th><th>Price (CHF)</th><th>Required amount</th><th>Min. duration in days</th><th>Cost</th>';
			echo '</tr>';

			// display each entry found
			while($row = mysqli_fetch_assoc($result)){
				
				// calculate min. duration in days
				switch($row['slotUnit']){
					case "DAY":
						$minDurationDays = $row['periodMin'];
						break;
					case "MONTH":
						$minDurationDays = $row['periodMin'] * 31;
						break;
					case "YEAR":
						$minDurationDays = $row['periodMin'] * 365;
						break;	
				}
				
				// calculations for additional columns
				$requiredAmount = floor(($budget/$quantity)/$row['cost']);
				$cost = $row['cost']* $requiredAmount;
				
				echo '<tr>';
				echo '<td>'.$row['id'].'</td><td>'.$row['title'].'</td><td>'.$row['CategoryL2'].'</td><td>'.$row['slotUnit'].'</td>'; 
				echo '<td>'.number_format($row['cost'], 2).'</td><td>'.$requiredAmount.'</td><td>'.$minDurationDays.'</td><td>'.number_format($cost, 2).'</td>';
				echo '</tr>';
				
				// calculate the total cost
				$totalCost += $cost;
			}
			
			echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td><b>Total cost</b></td><td><b>'.number_format($totalCost, 2).'</b></td></tr>';
			echo '</table>';
			
			// free results
			mysqli_free_result($result);
		
		} else {
			// notify the user that an error occurred
			echo 'Failed to execute query ('.mysqli_error($connection).')!';
			/*** ERROR: Log execution failure to db ***/
			$level = 'error';
			$event = 'Failed to execute query ('.mysqli_error($connection).')!';
			
			// db-query for insert
			$logQuery = "INSERT INTO dashboard_log (ipaddr, level, event) VALUES ('".$ipaddr."', '".$level."', '".$event."')";
			$res = mysqli_query($connection, $logQuery);			
		}		
		
		// close db connection
		mysqli_close($connection);
	}
?>