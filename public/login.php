<?php

/**
 * login.php
 *
 * Handle user authentication
 *
 * The basic process for this page is to:
 * - determine existing session
 *   - logout or
 *   - redirect to front page
 * - accept credentials and
 *   - start a session or
 *   - show an error
 * - show the login form
 */

require_once('../bootstrap.php');

// determine if there is a session
$user = DRyft\Session::getSession()->getUser();

// determine if a username has been submitted
if (
	array_key_exists('user', $_REQUEST) &&
	array_key_exists('password', $_REQUEST)
) {
	$user = \DRyft\User::getUserByName($_REQUEST['user']);

	// try to validate the provided password
	if ($user->validatePassword($_REQUEST['password'])) {

		// setup the session
		DRyft\Session::getSession()->setupSession($user);
	} else {
		unset($user);
	}
}

// Setup a shortcut to our application path
$linker = new DRyft\Linker;

if (array_key_exists('logout', $_REQUEST)) {
	DRyft\Session::destroy();
	$message = 'Logged out successfully.';
} elseif ($user) {

	$message = 'Session started.';
	// redirect the user to the main page
	header('Location: ' . $linker->urlPath());
} elseif (array_key_exists('login', $_REQUEST)) {
	echo '<h1>Unable to log in.</h1>';
} else {
	echo '<h1>Please login</h1>';
}

// Give the user the login form
?>
<form method="POST" action="<?php echo $linker->urlPath(); ?>login.php">
	<fieldset>
		<legend>Username</legend>
		<input type="text" name="user">
	</fieldset>
	<fieldset>
		<legend>Password</legend>
		<input type="password" name="password">
	</fieldset>
	<input type="submit" name="login" value="Login">
</form>
<?php

include '../testing_links.html';
