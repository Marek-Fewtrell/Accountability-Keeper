
<?php
	/*
	 * Calendar page.
	 * This page contains the calendar. Can jump to other months with a form.
	 * Can select a day to view that day in details. No other actions available.
	 * 
	*/
	error_reporting(E_ALL);
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		//Only logged in users allowed.
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}
	include 'header.html';
	include 'helper.php';
	
	$month = (isset($_GET['month'])) ? $_GET['month'] : date('n');
	$year = (isset($_GET['year'])) ? $_GET['year'] : date('Y');
	
	//echo "<a href=\"/Accountability-Keeper/calendar.php?month=" . $month . "\">Calendar View</a>";
	//echo "<a href=\"/Accountability-Keeper/calendar.php?view=week&month=" . $month . "\">Week View</a>";
	echo "<a href=\"/Accountability-Keeper/calendar.php?month=" . date('n') . "&year=" . date('Y') . "\">Current Month</a>";
	
	//Form which allows you to jump to a selected month.
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
	
	
	if (isset($_GET['view']) && $_GET['view'] == 'week') {
		echo '<a class="col-xs-6 text-left" href="/Accountability-Keeper/calendar.php?view=week&month=' . $month . '$week=';
		echo '">< Previous Week</a>';
		
		echo '<a class="col-xs-6 text-right" href="/Accountability-Keeper/calendar.php?view=week&month=' . $month . '&week=';
		//echo () ? "" : "";
		echo '">Next Week ></a>';
		
		
		echo 'This is a weekly view';
	} else {
		echo '<a class="col-xs-6 text-left" href="/Accountability-Keeper/calendar.php?month=';
		echo ($month - 1 < 1) ? '12' : ($month -1);
		echo '">< Previous Month</a>';
	
		echo '<a class="col-xs-6 text-right" href="/Accountability-Keeper/calendar.php?month=';
		echo ($month+1 > 12) ? '1' : ($month + 1);
		echo '">Next Month ></a>';
		
		createTable($month, $year);
	}
	
	include 'footer.html';



	/*
	 * createTable(month, year)
	 * Simply outputs a table for the selected month and year.
	 * Each day can be selected to view in more detail.
	*/
	function createTable($month, $year) {
		echo "<table class=\"table text-center\">";
		
		$days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thurs', 'Fri', 'Sat');
		//create the table headers which hold the days.
		echo '<tr>';
		foreach($days as $day) {
			echo "<td>" . $day . '</td>';
		}
		echo "</tr>";
		
		$totalDaysOutput = 0;
		$day = 1;
		
		$daysInMonth = date('t', mktime(0, 0, 0, $month, $day, $year)); //How many days in the month.
		
		$blankDays = date('w', mktime(0, 0, 0, $month, $day, $year)); //Blank days to fill until 1st of month.
		
		$previousMonthDays = date('t', mktime(0, 0, 0, $month-1, 1, $year)); // The days from the previous month
		//echo 'The previous month has this many days:' . $previousMonthDays;
		//echo 'This month starts on the:' . $blankDays;
		$previousMonthDaysOnCal = $previousMonthDays - ($blankDays - 1);
		//echo 'This is the number of blank days for this month:' . $prevoiusMonthDaysOnCal;

		echo '<tr>';
		
		//Retrieve a list/array of activities or whatever between two dates.
		/*
		SELECT schedule.*, activities.colour FROM schedule INNER JOIN activities ON schedule.activityId = activities.id where startDate > '2017-02-25' AND startDate < '2017-02-30';
		*/
		
		$actSchedResults = db_getActivityColours($_SESSION['userId']);
		//echo "act sched resutls:" . $actSchedResults;
		
		for (; $previousMonthDaysOnCal <= $previousMonthDays; $previousMonthDaysOnCal++) {
			
			echo '<td class="active"><a href="day.php?day='.$previousMonthDaysOnCal.'&month=' . ($month-1) . '&year='.$year.'"><div>' . $previousMonthDaysOnCal;
			
			$dateTimeStamp = mktime(0,0,0, ($month-1), $previousMonthDaysOnCal, $year);
			$currentDate = date('Y-m-d', $dateTimeStamp);
			checkForActsOnThisDay($actSchedResults, $currentDate);
			
			echo '</div></a></td>';
			$totalDaysOutput++;
		}
		
		for ($x = 1; $x <= $daysInMonth; $x++) {
			echo '<td';
			if ($x == date('j') && $month == date('m')) {
				echo ' class="info" ';
			} else {
				echo ' class="success" ';
			}
			
			echo '><a href="day.php?day='.$x.'&month='.$month.'&year='.$year.'"><div><strong>' . $x;
			
			$dateTimeStamp = mktime(0,0,0, $month, $x, $year);
			$currentMonthCurrentDate = date('Y-m-d', $dateTimeStamp);
			checkForActsOnThisDay($actSchedResults, $currentMonthCurrentDate);
			
			echo '</strong></div></a></td>';
			$totalDaysOutput++;
			if ($totalDaysOutput % 7 == 0) {
				echo "</tr><tr>";
			}
		}
		
		//echo "This is the total number of days already out" . $totalDaysOutput;
		//echo "This is the total number of week currently:" . $totalDaysOutput / 7;
		$maxNumDaysNextMonth = 7 - ($totalDaysOutput % 7);
		//echo "THis is how many need to be printed to fill it all up:" . $maxNumDaysNextMonth;
		
		for ($nextMonthDaysOnCal = 1; $nextMonthDaysOnCal <= $maxNumDaysNextMonth; $nextMonthDaysOnCal++) {
			
			echo '<td class="active">';

			echo '<a href="day.php?day='.$nextMonthDaysOnCal.'&month='. ($month + 1).'&year='.$year.'"><div>' . $nextMonthDaysOnCal;
			
			$dateTimeStamp = mktime(0,0,0, ($month+1), $nextMonthDaysOnCal, $year);
			$currentMonthCurrentDate = date('Y-m-d', $dateTimeStamp);
			checkForActsOnThisDay($actSchedResults, $currentMonthCurrentDate);
			
			echo '</div></a></td>';
			echo '</td>';
		}
		
		echo "</tr></table>";
	}
	
	function checkForActsOnThisDay($actSchedResults, $currentDate) {
		foreach($actSchedResults as $actSchedResult) {
			$timeSince = calculateIfOnThisDay($currentDate, $actSchedResult['startDate']);
			if ($actSchedResult['day'] == 'daily') {
				echo '<span class="glyphicon glyphicon-asterisk" style="color:' . $actSchedResult['colour'] . '"></span>';
			} else {
				if ($timeSince->format('%a') % 7 == 0) {
					echo '<span class="glyphicon glyphicon-asterisk" style="color:' . $actSchedResult['colour'] . '"></span>';
				}
			}
		}
	}
?>
