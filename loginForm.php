<?php
	include 'header.html';
?>

<h4>Login</h4>
<?php if (isset($loginErrorMessage)) {echo '<div class="alert alert-warning">'.$loginErrorMessage . '</div>'; }
 if (isset($loginStatusMessage)) {echo '<div class="alert alert-success">'.$loginStatusMessage . '</div>'; } ?>
<form method="post" action="userAccount.php" class="form-horizontal">
	<div class="form-group">
		<label for="username" class="control-label col-sm-2">Username:</label>
		<div class="col-sm-10">
			<input type="text" name="loginUsername" class="form-control" value="<?php if (isset($loginUsername)) { echo $loginUsername; }?>">
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
<?php if (isset($createErrorMessage)) {echo '<div class="alert alert-warning">'.$createErrorMessage . '</div>'; } 
 if (isset($createStatusMessage)) {echo '<div class="alert alert-success">'.$createStatusMessage . '</div>'; } ?>
<form method="post">
<input type="hidden" name="createAccount" action="userAccount.php">
<div class="form-group">
	<label for="username" class="">Username:</label>
	<input type="text" name="username" class="form-control" value="<?php if (isset($createAccountUsername)) { echo $createAccountUsername; } ?>">
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
	include 'footer.html';
?>
