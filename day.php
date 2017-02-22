<?php
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}
	include 'header.html';
	
	echo 'This is a day!<br/>';
	echo $_GET['day'] . '<br/>';
	echo $_GET['month']. '<br/>';
	echo $_GET['year']. '<br/>';
	echo date('d(D)-F-Y', mktime(0,0,0, $_GET['month'], $_GET['day'], $_GET['year']));
	
	echo '<br/><br/>List of stuff to output<br/><br/>';
	
	
	$userId = $_SESSION['userId'];
	$currentDate = date('Y-m-d', mktime(0,0,0, $_GET['month'], $_GET['day'], $_GET['year']));
	
	getRecord($userId, $currentDate);
	
	include 'footer.html';
?>
