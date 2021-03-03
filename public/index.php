<?php

/**
 * index.php
 *
 * Initial page for users. Should redirect based on session status and user type.
 *
 * @author Errol Sayre
 */

namespace DRyft;

require_once('../bootstrap.php');

// determine if we have a user
$user = Session::getSession()->getUser();

// setup a linker
$linker = new Linker();

// add HTML head
include '../head.html';

// Output a page title and any other specific head elements
echo '		<title>Welcome to DRyft</title>' . PHP_EOL;

// add page header
include '../header.html';

if (!$user) {
	echo '<h1>Welcome to DRyft</h1>';
	echo '<p><a href="' . $linker->urlPath() . 'login.php">Please login to begin.</a></p>';
} else {

	echo '<h1>Welcome ' . $user->username() . '</h1>';

	// Put some shortcuts that make sense for the given user
	if ($user->isCoordinator()) {
?>
		<p>Welcome <?= $user->firstName ?>. As a coordinator you can manage all aspects of the DRyft application. Please make use of the links below or the navigation above.</p>
		<ul>
			<li><a href="payments.php">Manage driver payments</a></li>
			<li><a href="user.php">List all users</a></li>
			<li><a href="driver.php">List all drivers</a></li>
			<li><a href="address.php">Manage addresses</a></li>
		</ul>
<?php
	}
}

// add page footer
include '../footer.html';
