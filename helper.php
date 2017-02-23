
<?php
	include 'database.php';

	function getListOfActivities() { 
		$result = db_getListOfActivities();
		
		echo '<table>';
  	echo '<tr><td>Name</td><td>Description</td><td>Time</td><td>Day</td></tr>';
  	foreach($result as $row) {
  		echo "<tr><td>" . $row["name"] . "</td><td>" . $row["description"] . "</td><td>" . $row["time"] . "</td><td>" . $row["day"] . "</td</tr>";
  	}
  	echo '</table>';
	}

	function getUserSchedule($userId) {
		$result = db_getUserSchedule($userId);
		
		echo '<table>';
  	echo '<tr><td>Name</td><td>Description</td></tr>';
  	foreach($result as $row) {
  		echo "<tr><td>" . $row["name"] . "</td><td>" . $row["description"] . "</tr>";
  	}
  	echo '</table>';
	}
	
	function getRecord($userId, $date) {
	  $result = db_getRecord($userId, $date);
	  
	  echo '<table>';
  	echo '<tr><td>activityId</td><td>status</td></tr>';
  	foreach($result as $row) {
  		echo "<tr><td>" . $row["activityId"] . "</td><td>" . $row["status"] . "</tr>";
  	}
  	echo '</table>';
	  //echo "userId: " . $row["userId"] . " activityId: " . $row["activityId"] . " status: " . $row["status"];
  }
	
?>
