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
		foreach($scheduleArray as $item) {
			//echo 'Started On: ' . $item['startDate'] . '<br/>';
			$timeSInce = calculateIfOnThisDay($currentDate, $item['startDate'])->format('%a days') . '<br/>';
			
			//$searchResult = array_search(, $previousRecords);
			$searchResult = false;
			if ($previousRecords != FALSE) {
				$searchResult = searchRecordsArray($previousRecords, $item['activitiesId']);
			}
			//echo 'Here is the search Result:'.$searchResult . '<hr>';
			
			switch($item['day']) {
				case 'daily':
					echo "daily activity | ";
					
					if ($searchResult['status'] == 'done') {
						echo 'You have already done: ' . $item['name'] . '<br/>';
					} else {
						echo 'You need to do: ' . $item['name'] . '<br/>';
					}
					
					echo $item['description'] . '<br/>';
					echo 'At time: ' . $item['time'] . '<br/>';
					
					echo '<form method="post" class="form-inline">';
					
					echo '<input type="hidden" name="activityId" value="'.$item['activitiesId'].'">';
					
					if ($searchResult == FALSE) {
						echo '<input type="hidden" name="formAction" value="create">';
					} else {
						echo '<input type="hidden" name="formAction" value="update">';
						echo '<input type="hidden" name="theId" value="'.$searchResult['id'].'">';
					}
					
					echo '<div class="form-group">';
					echo '<label for="status">Status:</label>';
					echo '<select name="status" class="form-control">';
					if ($searchResult['status'] == 'done') {
						echo '<option value="done" selected>Done</option>';
						echo '<option value="not done">Not Done</option>';
					} else {
						echo '<option value="done">Done</option>';
						echo '<option value="not done" selected>Not Done</option>';
					}
					echo '</select>';
					echo '</div>';
					echo '<input type="submit" class="btn btn-default" value="Set Activity Status">';
					echo '</form>';
					break;
				case 'weekly';
					$weeklyTimeSince = $timeSInce % 7;
					echo 'weekly activity | ';
					if ($weeklyTimeSince == 0) {
						//echo 'It has been a week since<br/>';
						echo 'You need to do activity: ' . $item['name'] . '<br/>';
						echo $item['description'] . '<br/>';
						echo '<form method="post" class="form-inline">';
					
						echo '<input type="hidden" name="activityId" value="'.$item['activitiesId'].'">';
					
						if ($searchResult == FALSE) {
							echo '<input type="hidden" name="formAction" value="create">';
						} else {
							echo '<input type="hidden" name="formAction" value="update">';
							echo '<input type="hidden" name="theId" value="'.$searchResult['id'].'">';
						}
						
						echo '<div class="form-group">';
						echo '<label for="status">Status:</label>';
						echo '<select name="status" class="form-control">';
						if ($searchResult['status'] == 'done') {
							echo '<option value="done" selected>Done</option>';
							echo '<option value="not done">Not Done</option>';
						} else {
							echo '<option value="done">Done</option>';
							echo '<option value="not done" selected>Not Done</option>';
						}
						echo '</select>';
						echo '</div>';
						echo '<input type="submit" class="btn btn-default" value="Set Activity Status">';
						echo '</form>';
						
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
	} else {
		echo "nothing worked";
	}
	

	
	
	include 'footer.html';
?>
