<?php
	/*
	 * Activites page.
	 * This page controls the activities which can be done.
	 * This page provides full CRUD abilities.
	 * 
	*/
	
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		//Only logged in users allowed.
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}
	include 'header.html';
	include 'helper.php';
	
	//Some variables used when editing and to fill in the activites form when necessary.
	$editting = false;
	$id = "";
	$name = "";
	$desc = "";
	$time = "";
	$timeHour = "1";
	$timeMinute = "00";
	
	$day = "";
	
	if (isset($_GET['edit'])) {
		$result = getActivity($_GET['id']);
		if ($result) {
			$id = $result['id'];
			$name = $result['name'];
			$desc = $result['description'];
			$time = $result['time'];
			//Time comes in HH:MM
			//{[H],[H], [:], [M],[M]}
			$timeHour = $time[0].$time[1];
			$timeMinute = $time['3'].$time['4'];
			$day = $result['day'];
			$editting = true;
		} else {
			echo '<h5>No activity found.</h5>';
		}
	}
?>

<!-- Collapsible panel to hold the form -->
<div class="panel-group">
	<div class="panel panel-default">
		<div class="panel-heading">
			<a data-toggle="collapse" href="#createFormPanel">
				<h4 class="panel-title">
					<?php
						if ($editting) {
							echo 'Update ';
						} else {
							echo 'Create New ';
						}
					?>
					Activity
				</h4>
			</a>
		</div>
		<div id="createFormPanel" class="collapse panel-collapse <?php if ($editting) { echo 'in'; } ?>">
			<div class="panel-body">
				<!-- Activities form -->
				<form method="post" name="activityForm" action="activities.php">
				<input type="hidden" name="id" value="
				<?php
					if ($editting) {
						echo $id;
					}
				?>">
				<div class="form-group">
					<label for="name">Name:</label>
					<input type="text" name="name" class="form-control" value="<?php
						if ($editting) {
							echo $name;
						}
					?>">
				</div>
				<div class="form-group">
					<label for="description">Description:</label>
					<textarea name="description" class="form-control" cols="30" rows="5"><?php
					if ($editting) {
						echo $desc;
					}
				?></textarea><br/>
				</div>
				<label>Time</label>
				<div class="form-group">
					<label for="timeHour">Hour:</label>
					<input type="number" name="timeHour" class="form-control" min="0" max="12" value="<?php
					echo $timeHour;
				?>">
					<label for="timeMinute">Minutes:</label>
					<input type="number" name="timeMinute" class="form-control" min="0" max="59" value="<?php
					echo $timeMinute;
				?>">
				<!--Time:<input name="time" type="text" value="<?php
					if ($editting) {
						echo $time;
					}
				?>"><br/>-->
				</div>
				<div class="form-group">
					<label for="day">Interval (Daily, Weekly):</label>
					<select name="day" class="form-control">
						<?php
							if ($editting && $day == 'daily') {
								echo '<option value="daily" selected="true">Daily</option>';
							} else {
								echo '<option value="daily">Daily</option>';
							}
							
							if ($editting && $day == 'weekly') {
								echo '<option value="weekly" selected="true">Weekly</option>';
							} else {
								echo '<option value="weekly">Weekly</option>';
							}
						?>
					</select>
				</div>
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
	
	

<h3>Activities</h3>
<?php
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST['createAction'])) {
			$theTime = $_POST['timeHour'] . ':' . $_POST['timeMinute'] . ':00';
			$result = addActivity($_POST['name'], $_POST['description'], $theTime, $_POST['day']);
			if ($result) {
				outputStatusMessage('Successfully added activity.', 'success');
			} else {
				outputStatusMessage('Unsuccessfully added activity.', 'warning');
			}
		} else if (isset($_POST['removeAction'])) {
			if (deleteActivity($_POST['removeAction'])) {
				outputStatusMessage('Successfully removed activity.', 'success');
			} else {
				outputStatusMessage('Unsuccessfully removed activity.', 'warning');
			}
		} else if (isset($_POST['updateAction'])) {
			$theTime = $_POST['timeHour'] . ':' . $_POST['timeMinute'] . ':00';
			$result = updateActivity($_POST['id'], $_POST['name'], $_POST['description'], $theTime, $_POST['day']);
			if ($result) {
				outputStatusMessage('Successfully updated activity.', 'success');
			} else {
				outputStatusMessage('Unsuccessfully updated activity.', 'warning');
			}
		}
	}
	
	getListOfActivities();

	include 'footer.html'
?>
