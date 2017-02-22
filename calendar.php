
<?php
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}
	include 'header.html';

	$month = (isset($_GET['month'])) ? $_GET['month'] : date('n');
	$year = (isset($_GET['year'])) ? $_GET['year'] : date('Y');
	
	echo '<form method="get">';
	echo 'Month:<select name="month">';
	for ($i = 1; $i < 13; $i++) {
		echo '<option value="' . $i . '"';
		if ($i == $month) {
			echo ' selected';
		}
		echo '>'.$i.'</option>';
	}
	echo '</select><br/>';
	echo "Year:<input type=\"number\" name=\"year\" value=\"$year\"><br/>";
	echo '<input type="submit">';
	echo '</form>';
	

	echo '<a href="/Accountability-Keeper/tester.php?month=';
	echo ($month - 1 < 1) ? '12' : ($month -1);
	echo '">Previous Month</a>';
	echo '<a href="/Accountability-Keeper/tester.php?month=';
	echo ($month+1 > 12) ? '1' : ($month + 1);
	echo '">Next Month</a>';
	createTable($month, $year);
	
	include 'footer.html';




	function createTable($month, $year) {
		echo "<table>";
		
		$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
		//create the table headers which hold the days.
		echo '<tr>';
		foreach($days as $day) {
			echo "<td>" . $day . '</td>';
		}
		echo "</tr>";
		
		$totalDaysOutput = 0;
		$day = 1;
		//$month = 3;
		//$year = 2017;
		
		$daysInMonth = date('t', mktime(0, 0, 0, $month, $day, $year)); //How many days in the month.
		
		$blankDays = date('w', mktime(0, 0, 0, $month, $day, $year));
		
		echo '<tr>';
		
		for ($i = 0; $i < $blankDays; $i++) {
			echo '<td></td>';
		}
		$totalDaysOutput += $blankDays;
		
		for ($x = 1; $x <= $daysInMonth; $x++) {
			echo '<td><a href="day.php?day='.$x.'&month='.$month.'&year='.$year.'">' . $x;
			if ($x == date('j')) {
				echo 'Today!';
			}
			echo '</a></td>';
			$totalDaysOutput++;
			if ($totalDaysOutput % 7 == 0) {
				echo "</tr><tr>";
			}
		}
		
		/*for ($weeks = 1; $weeks <= 4; $weeks++) {
			echo '<tr>';
			for ($weekDays = 1; $weekDays <= 7; $weekDays++) {
				echo '<td>';
				echo $weekDays * $weeks;
				echo '</td>';
			}
			echo '</tr>';
		}*/
		
		echo "</table>";
	}
?>
