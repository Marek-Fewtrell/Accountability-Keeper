<?php
	session_start();
	include 'header.html'
?>
<?php
	
	if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true) {
		if (isset($_GET["logout"]) && $_GET['logout'] == 'true') {
			session_unset();
			session_destroy();
			echo "You have been logged out!";
			
			//redirect to login page.
			
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
						//return to the login page with the message displayed.
						echo 'successfully created user.';
					} else {
						echo 'unsuccessfully created user.';
						//return to the login page with the message displayed.
					}
				} else {
				
				}
			} else {
				$result = checkUserLogin($_POST["loginUsername"], $_POST["loginPassword"]);
				if ($result) {
				
					//maybe just redirect to the calendar
					
					echo "Now logged in.";
					$_SESSION['loggedIn'] = true;
					$_SESSION['username'] = $_POST["loginUsername"];
					$_SESSION['userId'] = $result;
				} else {
					echo "Failed. Couldn't login with that stuff!";
					//display the login page as well with this message.
				}
			}
		} else {
			?>
			<h4>Login</h4>
				<form method="post" action="userAccount.php" class="form-horizontal">
					<div class="form-group">
						<label for="username" class="control-label col-sm-2">Username:</label>
						<div class="col-sm-10">
							<input type="text" name="loginUsername" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="control-label col-sm-2">Password:</label>
						<div class="col-sm-10">
							<input type="password" name="loginPassword" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" class="btn btn-default">
						</div>
					</div>
				</form>
			<hr>
			<h4>Create new account</h4>
			<br/>
			<form method="post">
				<input type="hidden" name="createAccount">
				<div class="form-group">
					<label for="username" class="">Username:</label>
					<input type="text" name="username" class="form-control" value="<?php  ?>">
				</div>
				<div class="form-group">
					<label for="username" class="">Password:</label>
					<input type="password" name="password" class="form-control">
				</div>
				<div class="form-group">
					<label for="username" class="">Confirm password:</label>
					<input type="password" name="passwordConfirm" class="form-control">
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-default">
				</div>
			</form>
			<?php
		}
	}
?>

<?php
	include 'footer.html'
?>
