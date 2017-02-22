<?php
	include 'header.html'
?>
<h4>User account</h4>
<?php
	session_start();
	if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true) {
		if (isset($_GET["logout"]) && $_GET['logout'] == 'true') {
			session_unset();
			session_destroy();
		
			echo "session has been destroyed!";
		} else {
			
			echo "Output the details about the user.<br/>";
			echo "username: " . $_SESSION['username'] . '<br/>';
			echo 'userId: ' . $_SESSION['userId'];
			echo "<br/><br/><a href=\"userAccount.php?logout=true\">Logout</a>";
			echo '<br/><br/><br/>';
		}
	} else {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$result = checkUserLogin($_POST["username"], $_POST["password"]);
			if ($result) {
				echo "Now logged in.";
				$_SESSION['loggedIn'] = true;
				$_SESSION['username'] = $_POST["username"];
				$_SESSION['userId'] = $result;
			} else {
				echo "Failed. Couldn't login with that stuff!";
			}
		} else {
			?>
			<form method="post" action="userAccount.php" >
				Username: <input type="text" name="username"><br/>
				Password: <input type="password" name="password"><br/>
				<input type="submit">
			</form>
			<?php
		}
	}
?>

<?php
	include 'footer.html'
?>
