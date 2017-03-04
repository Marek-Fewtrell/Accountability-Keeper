<?php
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}
	include 'header.html';
	include 'helper.php';
	
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
					outputStatusMessage('Successfully handled record.', 'success');
				} else {
					outputStatusMessage('Unsuccessfully created record.', 'warning');
				}
			} else if ($_POST['formAction'] == 'update') {
				$result = updateRecord($_SESSION['userId'], $_POST['theId'], $_POST['status']);
				if ($result) {
					outputStatusMessage('Successfully updated record.', 'success');
				} else {
					outputStatusMessage('Unsuccessfully updated record.', 'warning');
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
					echo outputReportItem('Daily', $searchResult, $item, false);
					break;
				case 'weekly';
					$weeklyTimeSince = $timeSInce % 7;
					if ($weeklyTimeSince == 0) {
						echo outputReportItem('Weekly', $searchResult, $item, false);
					} else {
						echo outputReportItem('Weekly', $searchResult, $item, $weeklyTimeSince);
						/*echo $item['name'] . ' is not today<br/>';
						echo $item['description'] . '<br/>';
						echo 'it is in ' . abs($weeklyTimeSince - 7) . ' day';*/
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
