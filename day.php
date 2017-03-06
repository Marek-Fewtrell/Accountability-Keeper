<?php
	/*
	 * Day page.
	 * This page shows which activities can be done when scheduled.
	 * This page provides full CRUD abilities for records.
	 * 
	*/
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		//Only logged in users allowed.
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}
	include 'header.html';
	include 'helper.php';
	
	$dateTimeStamp = mktime(0,0,0, $_GET['month'], $_GET['day'], $_GET['year']);
	$currentDate = date('Y-m-d', $dateTimeStamp);
	
	echo '<h5>This day is:</h5>';
	echo date('l \t\h\e jS \o\f F Y', $dateTimeStamp);
	
	echo '<hr><h4>Activites Scheduled This Day</h4>';
	
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
	
	$previousRecords = db_getRecord($userId, $currentDate); //Retrieves and records which have already been created.
	
	$scheduleArray = db_getUserScheduleAfterDate($userId, $currentDate); //Retrieves the schedule for today.
	
	if ($scheduleArray) {
		echo '<ul class="list-group">';
		foreach($scheduleArray as $item) {
			$timeSInce = calculateIfOnThisDay($currentDate, $item['startDate'])->format('%a days') . '<br/>';

			$searchResult = false;
			if ($previousRecords != FALSE) {
				$searchResult = searchRecordsArray($previousRecords, $item['activitiesId']);
			}
			
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
