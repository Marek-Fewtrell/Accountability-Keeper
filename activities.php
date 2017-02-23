<?php
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}
	include 'header.html'
?>

<h5>create new</h5>
<form method="post">
	Name:<input type="text" name="name"><br/>
	Description:<textarea name="description" cols="30" rows="5"></textarea><br/>
	Time:<input name="time" type="time"><br/>
	Day:<input name="day" type="text"><br/>
	<input type="submit">
</form>

<h4>Activities</h4>
<?php
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$result = addActivity($_POST['name'], $_POST['description'], $_POST['time'], $_POST['day']);
		if ($result) {
			echo 'Successfully added activity.';
		} else {
			echo $result;
		}
	}
	
	getListOfActivities();

	include 'footer.html'
?>
