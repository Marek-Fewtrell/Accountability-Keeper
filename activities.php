<?php
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}
	include 'header.html'
?>
<h4>Activities</h4>
<?php
	getListOfActivities();

	include 'footer.html'
?>
