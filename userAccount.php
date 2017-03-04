<?php
	session_start();
	if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true) {
		if (isset($_GET["logout"]) && $_GET['logout'] == 'true') {
			session_unset();
			session_destroy();
			
			$loginStatusMessage = "You have been logged out!";
			include 'loginForm.php';
			
		} else {
			include 'header.html';
			echo "User details<br/>";
			echo "username: " . $_SESSION['username'] . '<br/>';
			echo "<br/><a href=\"userAccount.php?logout=true\">Logout</a>";
			include 'footer.html';
		}
	} else {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (isset($_POST['createAccount'])) {
				if ($_POST['password'] == $_POST['passwordConfirm']) {
					include 'database.php';
					$result = createUser($_POST['username'], $_POST['password']);
					if ($result) {
						//return to the login page with the message displayed.
						$createStatusMessage = 'successfully created user.';
						//set the login user to the username.
						$loginUsername = $_POST['username'];
					} else {
						$createErrorMessage = 'unsuccessfully created user.';
						//return to the login page with the message displayed.
						//fill the create new account form with the username.
						$createAccountUsername = $_POST['username'];
					}
					include 'loginForm.php';
				} else {
					$createAccountUsername = $_POST['username'];
					$createErrorMessage = 'The passwords did not match!';
					include 'loginForm.php';
				}
			} else {
				include 'database.php';
				$result = checkUserLogin($_POST["loginUsername"], $_POST["loginPassword"]);
				if ($result) {
				
					//maybe just redirect to the calendar
					
					//echo "Now logged in.";
					$_SESSION['loggedIn'] = true;
					$_SESSION['username'] = $_POST["loginUsername"];
					$_SESSION['userId'] = $result;
					
					header('Location: /Accountability-Keeper/calendar.php');
					exit();
					
				} else {
					$loginErrorMessage = "Failed. Couldn't login with that stuff!";
					$loginUsername = $_POST["loginUsername"];
					//display the login page as well with this message.
					include 'loginForm.php';
				}
			}
		} else {
			include 'loginForm.php';
		}
	}
?>
