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
	$startDate = "";
	
	if (isset($_GET['edit'])) {
		$result = db_getSingleScheduleItem($_GET['id']);
		if ($result) {
			$id = $result['id'];
			$activityId = $result['activityId'];
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
	Start Date:<input name="startDate" type="text" value="<?php if ($editting) { echo $startDate; } ?>"><br/>
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
		foreach($_REQUEST as $key => $value) {
			echo $key . " " . $value . '<br/>';
		}
		if (isset($_POST['createAction'])) {
			echo 'doing create action stuff';
			$result = createSchedule($_POST['activityId'], $_SESSION['userId'], $_POST['startDate']);
			if ($result) {
				echo 'Successfully added schedule item.';
			} else {
				echo $result;
			}
		} else if (isset($_POST['removeAction'])) {
			echo 'Doing remove action stuff.';
			if (deleteSchedule($_POST['rowSelRadio'])) {
				echo "successfully removed schedule item!";
			} else {
				echo 'unsuccessfully remvoe schedule item!';
			}
		} else if (isset($_POST['updateAction'])) {
			echo 'Doing update action stuff';
			$result = updateSchedule($_POST['activityId'], $_POST['startDate'], $_POST['id']);
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

