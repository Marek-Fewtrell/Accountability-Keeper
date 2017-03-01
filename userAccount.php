<?php
	session_start();
	include 'header.html'
?>
<h4>User account</h4>
<?php
	
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
				<form method="post" action="userAccount.php" class="form-horizontal">
					<div class="form-group">
						<label for="username" class="control-label col-sm-2">Username:</label>
						<div class="col-sm-10">
							<input type="text" name="username" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="control-label col-sm-2">Password:</label>
						<div class="col-sm-10">
							<input type="password" name="password" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" class="btn btn-default">
						</div>
					</div>
				</form>
			<br/>
			<br/>
			Create new account
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
