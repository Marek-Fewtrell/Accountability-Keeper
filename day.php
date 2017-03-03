<?php
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}
	include 'header.html';
	
	$dateTimeStamp = mktime(0,0,0, $_GET['month'], $_GET['day'], $_GET['year']);
	$currentDate = date('Y-m-d', $dateTimeStamp);
	
	echo 'The day is:<br/>';
	echo date('d(D)-F-Y', $dateTimeStamp);
	
	echo '<br/><br/>List of stuff to output<br/><br/>';
	
	$userId = $_SESSION['userId'];
	
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST['formAction'])) {
			if ($_POST['formAction'] == 'create') {
				$result = createRecord($_SESSION['userId'], $_POST['activityId'], $_POST['status'], $currentDate);
				if ($result) {
					echo 'Successfully added record.';
				} else {
					echo 'unsuccessfully created record.';
					echo $result;
				}
			} else if ($_POST['formAction'] == 'update') {
				$result = updateRecord($_SESSION['userId'], $_POST['theId'], $_POST['status']);
				if ($result) {
					echo 'successfully updated record';
				} else {
					echo 'unsuccessfully updated record';
				}
				
			}
		}
		
	}
	
	//getRecord($userId, $currentDate);
	
	$previousRecords = db_getRecord($userId, $currentDate);
	
	$scheduleArray = db_getUserScheduleAfterDate($userId, $currentDate);
	echo '<hr>';
	if ($scheduleArray) {
		echo '<ul class="list-group">';
		foreach($scheduleArray as $item) {
			//echo 'Started On: ' . $item['startDate'] . '<br/>';
			$timeSInce = calculateIfOnThisDay($currentDate, $item['startDate'])->format('%a days') . '<br/>';
			
			//$searchResult = array_search(, $previousRecords);
			$searchResult = false;
			if ($previousRecords != FALSE) {
				$searchResult = searchRecordsArray($previousRecords, $item['activitiesId']);
			}
			//echo 'Here is the search Result:'.$searchResult . '<hr>';
			
			//if it's not on today, then output the number of days until it is.
			//else output the item for today.
			//check whether a record alreadys exists for this activity on this day.
			switch($item['day']) {
				case 'daily':
				
				
				/*echo '<div class="row">';
					echo '<div class="col-md-9 row">';
						echo '<h4 class="list-group-item-heading col-md-6">Name: ' . $row["name"] . "</h4>";
						echo '<p class="col-md-6 text-center">Start Date: ' . $row['startDate'] . '</p>';
						echo '<p class="list-group-item-text col-md-12">Description: ' . $row["description"] . "</p>";
					echo '</div>';
					echo "<div class=\"col-md-3 btn-group\"><a href=\"?edit&id=" . $row['id'] . "\" class=\"btn btn-default\">Edit</a>";
					echo '<button name="removeAction" value="'.$row['id'].'" class="btn btn-danger">Remove</button></div>';
				echo '</div>';
				echo '</li>';*/
				
				
				
					echo '<li class="list-group-item">';
					echo '<div class="row">';
					echo "<h4 class=\"list-group-item-heading col-md-6\">Daily activity | ";
					echo outputReportItem($searchResult, $item);
					echo '</li>';
					break;
				case 'weekly';
					$weeklyTimeSince = $timeSInce % 7;
					echo 'Weekly activity | ';
					if ($weeklyTimeSince == 0) {
						
						echo outputReportItem($searchResult, $item);
						
					} else {
						echo $item['name'] . ' is not today<br/>';
						echo $item['description'] . '<br/>';
						echo 'it is in ' . abs($weeklyTimeSince - 7) . ' day';
					}
					break;
				default:
					echo 'currently not today, it was '. $item['day'];
			}
			echo '<hr>';
		}
		echo '</ul>';
	} else {
		echo "Nothing from the schedule is on today.";
	}
	
	include 'footer.html';
?>
