
<?php
	/*
	 * A helper file which handles misc stuff.
	 * 
	*/

	include 'database.php';

	$colours = array("red", "blue", "black", "green", "yellow", "orange", "purple", "pink", "brown");

	//Creates the list of activities that are available.
	function getListOfActivities() { 
		global $colours;
		$result = db_getListOfActivities();
	
		echo '<form method="post" name="activityForm">';
		echo '<ul class="list-group">';
		foreach($result as $row) {
			echo '<li class="list-group-item">';
				echo '<div class="row">';
					echo '<div class="col-md-9 row">';
						echo '<h4 class="list-group-item-heading col-md-4" style="color:' . $row["colour"] . '">Name: ' . $row["name"] . "</h4>";
						echo '<p class="col-md-4">Time: ' . $row["formatted_time"] . '</p>';
						echo '<p class="col-md-4">Interval: ' . $row["day"] . '</p>';
						echo '<p class="list-group-item-text col-md-12">Description: ' . $row["description"] . "</p>";
					echo '</div>';
					echo "<div class=\"col-md-3 btn-group\"><a href=\"?edit&id=" . $row['id'] . "\" class=\"btn btn-default\">Edit</a>";
					echo '<button name="removeAction" value="'.$row['id'].'" class="btn btn-danger">Remove</button></div>';
				echo '</div>';
			
			echo '</li>';
  	}
  	echo '</ul>';
  	echo '</form>';
	}

	//Creates the list for the user schedule.
	function getUserSchedule($userId) {
		$result = db_getUserSchedule($userId);
		if ($result) {
			echo '<form method="post" name="scheduleForm">';
			echo '<ul class="list-group">';
			if ($result != FALSE) {
				foreach($result as $row) {
					echo '<li class="list-group-item">';
					echo '<div class="row">';
						echo '<div class="col-md-9 row">';
							echo '<h4 class="list-group-item-heading col-md-6" style="color:'. $row['colour'] .'">Name: ' . $row["name"] . "</h4>";
							echo '<p class="col-md-6 text-center">Start Date(d-m-y): ' . $row['startDate'] . '</p>';
							echo '<p class="list-group-item-text col-md-12">Description: ' . $row["description"] . "</p>";
						echo '</div>';
						echo "<div class=\"col-md-3 btn-group\"><a href=\"?edit&id=" . $row['id'] . "\" class=\"btn btn-default\">Edit</a>";
						echo '<button name="removeAction" value="'.$row['id'].'" class="btn btn-danger">Remove</button></div>';
					echo '</div>';
					echo '</li>';
				}
			}
			echo '</ul>';
			echo '</form>';
		} else {
			echo 'Nothing has been scheduled';
		}
	}

  //Outputs a single record item as a list item.
  function outputReportItem($interval, $searchResult, $item, $weeklyTimeSince) {
		if ($item['day'] == 'weekly') {
			//echo 'It has been a week since<br/>';
		}
	
		echo '<li class="list-group-item">';
		echo '<div class="row">';
		echo "<h4 class=\"list-group-item-heading col-md-6\" style=\"color:" . $item['colour'] . "\">$interval activity | ";
	
  		echo $item['name'] . '</h4>';
  	
		echo '<p class="col-md-5">At time: ' . $item['time'] . '</p>';
		echo '</div>';
		echo $item['description'] . '<br/>';
	
		if (!$weeklyTimeSince) {
			echo '<form method="post" class="form-inline">';
			echo '<input type="hidden" name="activityId" value="'.$item['activitiesId'].'">';
			if ($searchResult == FALSE) {
				echo '<input type="hidden" name="formAction" value="create">';
			} else {
				echo '<input type="hidden" name="formAction" value="update">';
				echo '<input type="hidden" name="theId" value="'.$searchResult['id'].'">';
			}
			echo '<div class="form-group">';
	
			if ($searchResult['status'] == 'done') {
				echo '<div class="label label-success">Done</div>';
			} else if ($searchResult['status'] == 'partial done') {
				echo '<div class="label label-warning">Partially Done</div>';
			} else {
				echo '<div class="label label-danger">Not Done</div>';
			}
	
			echo '<label for="status">Status:</label>';
			echo '<select name="status" class="form-control">';
			if ($searchResult['status'] == 'done') {
				echo '<option value="done" selected>Done</option>';
				echo '<option value="not done">Not Done</option>';
				echo '<option value="partial done">Partially Done</option>';
			} else if ($searchResult['status'] == 'partial done') {
				echo '<option value="done">Done</option>';
				echo '<option value="not done">Not Done</option>';
				echo '<option value="partial done" selected>Partially Done</option>';
			} else {
				echo '<option value="done">Done</option>';
				echo '<option value="not done" selected>Not Done</option>';
				echo '<option value="partial done">Partially Done</option>';
			}
			echo '</select>';
			echo '</div>';
			echo '<input type="submit" class="btn btn-default" value="Set Activity Status">';
			echo '</form>';
		} else {
			echo $item['name'] . ' is not today<br/>';
			//echo $item['description'] . '<br/>';
			echo 'it is in ' . abs($weeklyTimeSince - 7) . ' day';
		}
		echo '</li>';
  }
  
  //Searches the record array to find a specific item and returns it.
  function searchRecordsArray($array, $searchTerm) {
		foreach($array as $row) {
			if ($row['activityId'] == $searchTerm) {
				return $row;
			}
		}
		return false;
	}
  
  //Creates the month dropdown in forms.
  function createMonthDropdown($name, $currentlySelected) {
  	$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
  	$output = '<select name="'.$name.'" class="form-control">';
  	$counter = 1;
  	foreach ($months as $month) {
  		$output .= '<option value="'.$counter.'"';
  		if ($counter == $currentlySelected) {
  			$output .= 'selected="true"';
  		}
  		$output .= '>'.$month.'</option>';
  		$counter++;
  	}
  	$output .= '</select>';
  	return $output;
  }
  
  //Calculates how many days have passed between two dates.
  function calculateIfOnThisDay($curDate, $initialDate) {
  	$date1 = new DateTime($curDate);
  	$date2 = new DateTime($initialDate);
  	
  	$interval = $date1->diff($date2);

	return $interval;

	}

	//Outputs a div which is styled to look as an alert.
	function outputStatusMessage($message, $status) {
		echo '<div class="alert alert-'.$status.'">'.$message.'</div>';
	}

	function temp($userId, $startDate, $endDate) {
		$results = db_getActivityColours($userId);
		foreach($results as $result) {
			if (calculateIfOnThisDay($result['startDate'], $startDate)) {
				
			}
		}
	}

	?>
