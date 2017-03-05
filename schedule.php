<?php
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}

	include 'header.html';
	include 'helper.php';
	
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

<div class="panel-group">
	<div class="panel panel-default">
		<div class="panel-heading">
			<a data-toggle="collapse" href="#createFormPanel">
				<h4 class="panel-title">
					<?php
						if ($editting) {
							echo 'Update';
						} else {
							echo 'Create New ';
						}
					?>
					Schedule Item
				</h4>
			</a>
		</div>
		<div id="createFormPanel" class="collapse panel-collapse <?php if ($editting) { echo 'in'; } ?>">
			<div class="panel-body">
				<form method="post" name="scheduleForm" action="schedule.php">
					<input type="hidden" name="id" value="
					<?php
						if ($editting) {
							echo $id;
						}
					?>">
					<div class="form-group">
					<label for="activityId">Activity:</label>
					<select name="activityId" class="form-control">
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
					</div>
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
					<label>Start Date</label>
					<div class="form-group">
						<label for="activityId">Day:</label>
						<input type="number" class="form-control" name="day" value="<?php echo $day; ?>">
					</div>
					<div class="form-group">
						<label for="activityId">Month:</label>
						<?php echo createMonthDropdown('month', $month); ?>
					</div>
					<div class="form-group">
						<label for="activityId">Year:</label>
						<input type="number" class="form-control" name="year" value="<?php echo $year; ?>">
					</div>
					<div class="form-group">
	
					<!-- Start Date(YYYY-MM-DD):<input name="startDate" type="text" value="<?php  ?>"><br/>-->
	
					<br/>
					<?php
						if ($editting) {
							echo '<input type="submit" class="btn btn-default" name="updateAction" value="Update">';
						} else {
							echo '<input type="submit" class="btn btn-default" name="createAction" value="Create">';
						}
					?>
				</form>
			</div>
		</div>
	</div>
</div>



<h3>Schedule</h3>
<?php
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST['createAction'])) {
			$postDate = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];
			$result = createSchedule($_POST['activityId'], $_SESSION['userId'], $postDate);
			if ($result) {
				outputStatusMessage('Successfully created schedule item.', 'success');
			} else {
				outputStatusMessage('Unsuccessfully created schedule item.', 'warning');
			}
		} else if (isset($_POST['removeAction'])) {
			if (deleteSchedule($_POST['removeAction'])) {
				outputStatusMessage('Successfully removed schedule item.', 'success');
			} else {
				outputStatusMessage('Unsuccessfully removed schedule item.', 'warning');
			}
		} else if (isset($_POST['updateAction'])) {
			$postDate = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];
			$result = updateSchedule($_POST['activityId'], $postDate, $_POST['id']);
			if ($result) {
				outputStatusMessage('Successfully updated schedule item.', 'success');
			} else {
				echo 'unsuccessfully updated activity';
				outputStatusMessage('Unsuccessfully updated schedule item.', 'warning');
			}
		}
		
	}
	
	getUserSchedule($_SESSION['userId']);
	
		?>
<?php
	include 'footer.html'
?>

