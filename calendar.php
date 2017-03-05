
<?php
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}
	include 'header.html';
	include 'helper.php';

	$month = (isset($_GET['month'])) ? $_GET['month'] : date('n');
	$year = (isset($_GET['year'])) ? $_GET['year'] : date('Y');
	echo '<h4>Jump To Month</h4>';
	echo '<form method="get" class="form-inline">';
	echo '<div class="form-group">';
	echo '<label for="year">Month:</label>';
	echo createMonthDropdown('month', $month);
	echo '</div>';
	echo '<div class="form-group">';
	echo '<label for="year">Year:</label>';
	echo "<input type=\"number\" class=\"form-control\" name=\"year\" value=\"$year\"><br/>";
	echo '</div>';
	echo '<input type="submit" class="btn btn-default" value="Change">';
	echo '</form>';
	echo '<hr>';
	
	echo '<div class="row">';
	echo '<a class="col-xs-6 text-left" href="/Accountability-Keeper/calendar.php?month=';
	echo ($month - 1 < 1) ? '12' : ($month -1);
	echo '">< Previous Month</a>';
	
	echo '<a class="col-xs-6 text-right" href="/Accountability-Keeper/calendar.php?month=';
	echo ($month+1 > 12) ? '1' : ($month + 1);
	echo '">Next Month ></a>';
	createTable($month, $year);
	
	include 'footer.html';




	function createTable($month, $year) {
		echo "<table class=\"table text-center\">";
		
		//$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
		$days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thurs', 'Fri', 'Sat');
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
			echo '<td';
			if ($x == date('j') && $month == date('m')) {
				echo ' class="alert alert-info" ';
			}
			echo '><a href="day.php?day='.$x.'&month='.$month.'&year='.$year.'"><div>' . $x . '</div></a></td>';
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
