<?php
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		header('Location: /Accountability-Keeper/userAccount.php');
		exit();
	}
	
	
	include 'header.html'
?>
		<h4>Schedule</h4>
		<?php
			//getstuff();
			
			getUserSchedule($_SESSION['userId']);
			
			/*
				Have a database,
				it stores shit.
				
				A table of stored activities.
				
				
				
			*/
		?>
<?php
	include 'footer.html'
?>

