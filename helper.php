
<?php
	include 'database.php';

	function getListOfActivities() { 
		$result = db_getListOfActivities();
		
		echo '<form method="post" name="activityForm">';
		echo '<table>';
  	echo '<tr><td>Remove</td><td>Name</td><td>Description</td><td>Time</td><td>Day</td></tr>';
  	foreach($result as $row) {
  		echo "<tr><td><input type=\"radio\" name=\"rowSelRdio\" value=\"" . $row['id'] . "\"></td><td>" . $row["name"] . "</td><td>" . $row["description"] . "</td><td>" . $row["time"] . "</td><td>" . $row["day"] . "</td><td><a href=\"?edit&id=" . $row['id'] . "\">Edit</a></td></tr>";
  	}
  	echo '</table>';
  	echo '<button name="removeAction">Remove</button>';
  	echo '</form>';
	}

	function getUserSchedule($userId) {
		$result = db_getUserSchedule($userId);
		
		echo '<form method="post" name="scheduleForm">';
		echo '<table>';
  	echo '<tr><td>Remove</td><td>Name</td><td>Description</td></tr>';
  	foreach($result as $row) {
  		echo "<tr><td><input type=\"radio\" name=\"rowSelRadio\" value=\"" . $row['id'] . "\"></td><td>" . $row["name"] . "</td><td>" . $row["description"] . "<td><a href=\"?edit&id=" . $row['id'] . "\">Edit</a></td></tr>";
  	}
  	echo '</table>';
  	echo '<button name="removeAction">Remove</button>';
  	echo '</form>';
	}
	
	function getRecord($userId, $date) {
	  $result = db_getRecord($userId, $date);
	  
	  echo '<table>';
  	echo '<tr><td>activityId</td><td>status</td></tr>';
  	if ($result) {
  		foreach($result as $row) {
				echo "<tr><td>" . $row["activityId"] . "</td><td>" . $row["status"] . "</tr>";
			}
		} else {
			echo '<br/>'.'Nothing retrieved for getRecord' . '<br/>';
		}
  	echo '</table>';
	  //echo "userId: " . $row["userId"] . " activityId: " . $row["activityId"] . " status: " . $row["status"];
  }
  
  function searchRecordsArray($array, $searchTerm) {
		foreach($array as $row) {
			if ($row['activityId'] == $searchTerm) {
				return $row;
			}
		}
		return false;
	}
  
  function calculateIfOnThisDay($curDate, $initialDate) {
  	$date1 = new DateTime($curDate);
  	$date2 = new DateTime($initialDate);
  	
  	$interval = $date1->diff($date2);
  	
  	return $interval;
  	
  }
	
?>
