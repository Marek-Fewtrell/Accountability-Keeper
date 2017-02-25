<?php
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}
	include 'header.html';
	
	echo 'This is a day!<br/>';
	echo $_GET['day'] . '<br/>';
	echo $_GET['month']. '<br/>';
	echo $_GET['year']. '<br/>';
	echo date('d(D)-F-Y', mktime(0,0,0, $_GET['month'], $_GET['day'], $_GET['year']));
	
	echo '<br/><br/>List of stuff to output<br/><br/>';
	
	
	$userId = $_SESSION['userId'];
	$currentDate = date('Y-m-d', mktime(0,0,0, $_GET['month'], $_GET['day'], $_GET['year']));
	
	getRecord($userId, $currentDate);
	
	$scheduleArray = db_getUserSchedule($userId);
	echo '<hr>';
	if ($scheduleArray) {
		foreach($scheduleArray as $item) {
			echo $item['startDate'] . '<br/>';
			echo $timeSInce = calculateIfOnThisDay($currentDate, $item['startDate'])->format('%a days') . '<br/>';
			
			switch($item['day']) {
				case 'daily':
					echo "daily | ";
					echo 'You need to do: ' . $item['name'] . '<br/>';
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
