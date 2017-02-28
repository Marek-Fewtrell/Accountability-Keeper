<?php
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}

	include 'header.html';
	
	$editting = false;
	$id = "";
	$activityId = "";
	$tempCurrentDate = getdate();
	$startDate = $tempCurrentDate['year'].'-'.$tempCurrentDate['mon'].'-'.$tempCurrentDate['mday'];
	//var_dump($startDate);
	//echo date_format($startDate, "Y/m/d");
	
	if (isset($_GET['edit'])) {
		$result = db_getSingleScheduleItem($_GET['id']);
		if ($result) {
			$id = $result['id'];
			$activityId = $result['activityId'];
			//$startDate = date_create($result['startDate']);
			$startDate = $result['startDate'];
			$editting = true;
		} else {
			echo 'No schedule item found by that id';
		}
	}
?>


<h5>Create new</h5>
<form method="post" name="scheduleForm">
	<input type="hidden" name="id" value="
	<?php
		if ($editting) {
			echo $id;
		}
	?>">
	Activity:<select name="activityId">
		<?php
			$result = db_getListOfActivities();
			foreach ($result as $row) {
				echo "<option value=\"".$row['id']."\"";
				if($row['id'] == $activityId) {
					echo ' selected ';
				}
				echo ">".$row['name']."</option>";
			}
		?>
	</select>
	<br/>
	
	<?php
		
		$newDate = new DateTime($startDate);
		//echo $newDate->format('Y-m-d');
		
		$day = $newDate->format('d');
		$month = $newDate->format('m');
		$year = $newDate->format('Y');
		//echo $day . '-' . $month . '-' . $year;
		
		if ($editting) {
			echo 'Current start date (d-m-y): ' . $newDate->format('d-m-Y') . '<br/>';
			
		}
	?>
	
	
	Day: <input type="number" name="day" value="<?php echo $day; ?>">
	Month: <input type="number" name="month" value="<?php echo $month; ?>">
	Year: <input type="number" name="year" value="<?php echo $year; ?>">
	
	<!-- Start Date(YYYY-MM-DD):<input name="startDate" type="text" value="<?php  ?>"><br/>-->
	
	<br/>
	<?php
		if ($editting) {
			echo '<input type="submit" name="updateAction" value="Update">';
		} else {
			echo '<input type="submit" name="createAction" value="Create">';
		}
	?>
</form>



<h4>Schedule</h4>
<?php
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST['createAction'])) {
			$postDate = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];
			$result = createSchedule($_POST['activityId'], $_SESSION['userId'], $postDate);
			if ($result) {
				echo 'Successfully created schedule item.';
			} else {
				echo 'unsuccessfully created schedule item.';
				echo $result;
			}
		} else if (isset($_POST['removeAction'])) {
			if (deleteSchedule($_POST['rowSelRadio'])) {
				echo "successfully removed schedule item!";
			} else {
				echo 'unsuccessfully removed schedule item!';
			}
		} else if (isset($_POST['updateAction'])) {
			$postDate = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];
			$result = updateSchedule($_POST['activityId'], $postDate, $_POST['id']);
			if ($result) {
				echo 'successfully updated activity';
			} else {
				echo 'unsuccessfully updated activity';
			}
		}
		
	}
	
	getUserSchedule($_SESSION['userId']);
	
		?>
<?php
	include 'footer.html'
?>

