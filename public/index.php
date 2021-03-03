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

// Redirect clients directly to their front-page
if ($user instanceof User) {
	if ($user->isClient()) {
		header('Location: ' . $linker->urlPath() . 'client.php');
		die();
	}
}

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
			<li><a href="ride.php">Ride requests</a></li>
			<li><a href="payments.php">Driver payments</a></li>
			<li><a href="driver.php">Driver attributes</a></li>
			<li><a href="user.php">Users</a></li>
			<li><a href="address.php">Manage Addresses</a></li>
		</ul>
<?php
	} elseif ($user->isDriver()) {
?>
		<p>Welcome <?= $user->firstName ?>. View your assigned drives or upcoming payments.</p>
		<ul>
			<li><a href="driver.php">Drives</a></li>
			<li><a href="payments.php">Payments</a></li>
		</ul>
<?php
	} elseif ($user->isClient()) {
?>
		<p>Welcome <?= $user->firstName ?>. You may request a ride or view your history or manage your common destinations.</p>
		<ul>
			<li><a href="ride.php">Ride Requests</a></li>
			<li><a href="address.php">Addresses</a></li>
		</ul>
<?php

	}
}

// add page footer
include '../footer.html';
