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
		
			echo "You have been logged out!";
		} else {
			
			echo "User details<br/>";
			echo "username: " . $_SESSION['username'] . '<br/>';
			echo "<br/><a href=\"userAccount.php?logout=true\">Logout</a>";
		}
	} else {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (isset($_POST['createAccount'])) {
				if ($_POST['password'] == $_POST['passwordConfirm']) {
					$result = createUser($_POST['username'], $_POST['password']);
					if ($result) {
						echo 'successfully created user.';
					} else {
						echo 'unsuccessfully created user.';
					}
				} else {
				
				}
			} else {
				$result = checkUserLogin($_POST["username"], $_POST["password"]);
				if ($result) {
					echo "Now logged in.";
					$_SESSION['loggedIn'] = true;
					$_SESSION['username'] = $_POST["username"];
					$_SESSION['userId'] = $result;
				} else {
					echo "Failed. Couldn't login with that stuff!";
				}
			}
		} else {
			?>
			Login
			<br/>
			<form method="post" action="userAccount.php" >
				Username: <input type="text" name="username"><br/>
				Password: <input type="password" name="password"><br/>
				<input type="submit">
			</form>
			<br/>
			<br/>
			Create new account
			<br/>
			<form method="post">
				<input type="hidden" name="createAccount">
				Username:<input type="text" name="username" value="<?php  ?>"><br/>
				Password:<input type="password" name="password"><br/>
				Confirm password:<input type="password" name="passwordConfirm"><br/>
				<input type="submit">
			</form>
			<?php
		}
	}
?>

<?php
	include 'footer.html'
?>
