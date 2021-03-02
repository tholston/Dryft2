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
if (!$user) {
	echo '<h1>Nothing to see here</h1>';
} else {
	if($user-> isClient()){
		header('Location: ' . $linker->urlPath() . 'client.php');
		die();
	}
}

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
}

// add page footer
include '../footer.html';
?>
