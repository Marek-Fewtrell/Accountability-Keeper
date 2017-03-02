<?php
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}
	include 'header.html';
	
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
			$timeHour = $time[0].$time[1];
			$timeMinute = $time['3'].$time['4'];
			$day = $result['day'];
			$editting = true;
		} else {
			echo 'No activity found by that id';
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
							echo 'Update ';
						} else {
							echo 'Create New ';
						}
					?>
					Activity
				</h4>
			</a>
		</div>
		<div id="createFormPanel" class="collapse panel-collapse">
			<div class="panel-body">
				<form method="post" name="activityForm">
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
					<label for="day">Day (daily, weekly):</label>
					<input name="day" type="text" class="form-control" value="<?php
					if ($editting) {
						echo $day;
					}
				?>">
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
			//$result = addActivity($_POST['name'], $_POST['description'], $_POST['time'], $_POST['day']);
			$result = addActivity($_POST['name'], $_POST['description'], $theTime, $_POST['day']);
			if ($result) {
				echo 'Successfully added activity.';
			} else {
				echo $result;
			}
		} else if (isset($_POST['removeAction'])) {
			if (deleteActivity($_POST['rowSelRdio'])) {
				echo "successfully removed activity!";
			} else {
				echo 'unsuccessfully remvoe activity!';
			}
		} else if (isset($_POST['updateAction'])) {
			$theTime = $_POST['timeHour'] . ':' . $_POST['timeMinute'] . ':00';
			//$result = updateActivity($_POST['id'], $_POST['name'], $_POST['description'], $_POST['time'], $_POST['day']);
			$result = updateActivity($_POST['id'], $_POST['name'], $_POST['description'], $theTime, $_POST['day']);
			if ($result) {
				echo 'successfully updated activity';
			} else {
				echo 'unsuccessfully updated activity';
			}
		}
		
	}
	
	getListOfActivities();

	include 'footer.html'
?>
