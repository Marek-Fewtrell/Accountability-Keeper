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

<h5>Create new</h5>
<form method="post" name="activityForm">
	<input type="hidden" name="id" value="
	<?php
		if ($editting) {
			echo $id;
		}
	?>">
	Name:<input type="text" name="name" value="<?php
		if ($editting) {
			echo $name;
		}
	?>"><br/>
	Description:<textarea name="description" cols="30" rows="5"><?php
		if ($editting) {
			echo $desc;
		}
	?></textarea><br/>
	
	Time</br>
	Hour: <input type="number" name="timeHour" min="0" max="12" value="<?php
		echo $timeHour;
	?>">
	Minutes: <input type="number" name="timeMinute" min="0" max="59" value="<?php
		echo $timeMinute;
	?>">
	<br/>
	<!--Time:<input name="time" type="text" value="<?php
		if ($editting) {
			echo $time;
		}
	?>"><br/>-->
	Day (daily, weekly):<input name="day" type="text" value="<?php
		if ($editting) {
			echo $day;
		}
	?>"><br/>
	<?php
	if ($editting) {
		echo '<input type="submit" name="updateAction" value="Update">';
	} else {
		echo '<input type="submit" name="createAction" value="Create">';
	}
	?>
</form>

<h4>Activities</h4>
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
