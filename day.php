<?php
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}
	include 'header.html';
	
	
		
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST['formAction'])) {
			if ($_POST['formAction'] == 'create') {
				//echo 'form action is to create';
				$result = createRecord($_SESSION['userId'], $_POST['activityId'], $_POST['status'], $currentDate);
				if ($result) {
					echo 'Successfully added record.';
				} else {
					echo $result;
				}
				
			} else if ($_POST['formAction'] == 'update') {
				//echo 'form action is to update';
				$result = updateRecord($_SESSION['userId'], $_POST['theId'], $_POST['status']);
				if ($result) {
					echo 'successfully updated record';
				} else {
					echo 'unsuccessfully updated record';
				}
				
			}
		}
		
	}
	
	
	echo 'This is a day!<br/>';
	echo $_GET['day'] . '<br/>';
	echo $_GET['month']. '<br/>';
	echo $_GET['year']. '<br/>';
	echo date('d(D)-F-Y', mktime(0,0,0, $_GET['month'], $_GET['day'], $_GET['year']));
	
	echo '<br/><br/>List of stuff to output<br/><br/>';
	
	
	$userId = $_SESSION['userId'];
	$currentDate = date('Y-m-d', mktime(0,0,0, $_GET['month'], $_GET['day'], $_GET['year']));
	
	//getRecord($userId, $currentDate);
	
	$previousRecords = db_getRecord($userId, $currentDate);
	
	$scheduleArray = db_getUserSchedule($userId);
	echo '<hr>';
	if ($scheduleArray) {
		foreach($scheduleArray as $item) {
			echo $item['startDate'] . '<br/>';
			echo $timeSInce = calculateIfOnThisDay($currentDate, $item['startDate'])->format('%a days') . '<br/>';
			
			switch($item['day']) {
				case 'daily':
					echo "daily | ";
					
					//$searchResult = array_search(, $previousRecords);
					$searchResult = false;
					if ($previousRecords != FALSE) {
						$searchResult = searchRecordsArray($previousRecords, $item['activitiesId']);
					}
					//echo 'Here is the search Result:'.$searchResult . '<hr>';
					
					if ($searchResult['status'] == 'done') {
						echo 'You have already done: ' . $item['name'] . '<br/>';
					} else {
						echo 'You need to do: ' . $item['name'] . '<br/>';
					}
					
					echo '<form method="post">';
					
					echo '<input type="hidden" name="activityId" value="'.$item['activitiesId'].'">';
					
					if ($searchResult == FALSE) {
						echo '<input type="hidden" name="formAction" value="create">';
					} else {
						echo '<input type="hidden" name="formAction" value="update">';
						echo '<input type="hidden" name="theId" value="'.$searchResult['id'].'">';
					}
					
					echo '<select name="status">';
					if ($searchResult['status'] == 'done') {
						echo '<option value="done" selected>Done</option>';
						echo '<option value="not done">Not Done</option>';
					} else {
						echo '<option value="done">Done</option>';
						echo '<option value="not done" selected>Not Done</option>';
					}
					echo '</select>';
					echo '<input type="submit" value="Set Activity Status">';
					echo '</form>';
					break;
				case 'weekly';
					$weeklyTimeSince = $timeSInce % 7;
					if ($weeklyTimeSince == 0) {
						echo 'It has been a week since<br/>';
						echo 'You need to do activity: ' . $item['name'];
					} else {
						echo 'currently not today, it was '. $item['day'] . '| it is in ' . abs($weeklyTimeSince - 7) . 'day';
					}
					break;
				default:
					echo 'currently not today, it was '. $item['day'];
			}
			echo '<hr>';
			
		}
	} else {
		echo "nothing worked";
	}
	

	
	
	include 'footer.html';
?>
